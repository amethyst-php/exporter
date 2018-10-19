<?php

namespace Railken\Amethyst\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Contracts\GenerateExportContract;
use Railken\Amethyst\Exceptions\FormattingException;
use Railken\Amethyst\Managers\FileManager;
use Railken\Amethyst\Models\Exporter;
use Railken\Lem\Contracts\AgentContract;
use Railken\Template\Generators;

abstract class GenerateExportCommon implements ShouldQueue, GenerateExportContract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Railken\Amethyst\Models\Exporter
     */
    protected $exporter;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Railken\Lem\Contracts\AgentContract|null
     */
    protected $agent;

    /**
     * Create a new job instance.
     *
     * @param Exporter                             $exporter
     * @param array                                $data
     * @param \Railken\Lem\Contracts\AgentContract $agent
     */
    public function __construct(Exporter $exporter, array $data = [], AgentContract $agent = null)
    {
        $this->exporter = $exporter;
        $this->data = $data;
        $this->agent = $agent;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $exporter = $this->exporter;
        $data = $this->data;

        $data_builder = $exporter->data_builder;

        $generator = new Generators\TextGenerator();

        try {
            $query = $data_builder->newInstanceQuery($data);

            $filename = sys_get_temp_dir().'/'.$generator->generateAndRender($exporter->filename, $data);

            $writer = $this->newWriter($filename);

            $row = array_values((array) $exporter->body);

            if ($this->shouldWriteHead()) {
                $this->write($writer, array_keys((array) $exporter->body));
            }

            $query->chunk(100, function ($resources) use ($writer, $row, $generator, $data_builder) {
                $data_builder->extract($resources, function ($resource, $data) use ($writer, $row, $generator) {
                    $encoded = $generator->generateAndRender((string) json_encode($row), $data);

                    $encoded = preg_replace('/\t+/', '\\\\t', $encoded);
                    $encoded = preg_replace('/\n+/', '\\\\n', $encoded);
                    $encoded = preg_replace('/\r+/', '\\\\r', $encoded);

                    $value = json_decode($encoded, true);

                    if ($value === null) {
                        throw new FormattingException(sprintf('Error while formatting resource #%s', $resource->id));
                    }

                    $this->write($writer, $value);
                });
            });
        } catch (FormattingException | \PDOException | \Railken\SQ\Exceptions\QuerySyntaxException $e) {
            return event(new \Railken\Amethyst\Events\ExporterFailed($exporter, $e, $this->agent));
        } catch (\Twig_Error $e) {
            $e = new \Exception($e->getRawMessage().' on line '.$e->getTemplateLine());

            return event(new \Railken\Amethyst\Events\ExporterFailed($exporter, $e, $this->agent));
        }

        $fm = new FileManager();
        $this->save($writer);

        $result = $fm->create([]);
        $resource = $result->getResource();

        $resource
            ->addMedia($filename)
            ->addCustomHeaders([
                'ContentDisposition' => 'attachment; filename='.basename($filename).'',
                'ContentType'        => $this->getMimeType(),
            ])
            ->toMediaCollection('exporter');

        event(new \Railken\Amethyst\Events\ExporterGenerated($exporter, $result->getResource(), $this->agent));
    }

    public function getMimeType()
    {
        return 'text/plain';
    }

    public function newWriter($filename)
    {
        return fopen($filename, 'w');
    }

    public function write($writer, $value)
    {
        fwrite($writer, implode(',', $value));
    }

    public function shouldWriteHead()
    {
        return true;
    }

    public function save($writer)
    {
        fclose($writer);
    }
}
