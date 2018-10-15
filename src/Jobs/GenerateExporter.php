<?php

namespace Railken\Amethyst\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Events\ExporterFailed;
use Railken\Amethyst\Events\ExporterGenerated;
use Railken\Amethyst\Exceptions\FormattingException;
use Railken\Amethyst\Managers\FileManager;
use Railken\Amethyst\Models\Exporter;
use Railken\Lem\Contracts\AgentContract;
use Railken\Template\Generators;

class GenerateExporter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exporter;
    protected $data;
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

            $filename = sys_get_temp_dir().'/'.$generator->generateAndRender($exporter->filename, $data).'.csv';

            $file = fopen($filename, 'w');

            if (!$file) {
                throw new \Exception();
            }

            $head = array_keys((array) $exporter->body);
            $row = array_values((array) $exporter->body);

            fputcsv($file, $head);

            $query->chunk(100, function ($resources) use ($file, $row, $generator, $data_builder) {
                $data_builder->extract($resources, function ($resource, $data) use ($file, $row, $generator) {
                    $encoded = $generator->generateAndRender((string) json_encode($row), $data);

                    $encoded = preg_replace('/\t+/', '\\\\t', $encoded);
                    $encoded = preg_replace('/\n+/', '\\\\n', $encoded);
                    $encoded = preg_replace('/\r+/', '\\\\r', $encoded);

                    $value = json_decode($encoded, true);

                    if ($value === null) {
                        throw new FormattingException(sprintf('Error while formatting resource #%s', $resource->id));
                    }

                    fputcsv($file, $value);
                });
            });
        } catch (FormattingException | \PDOException | \Railken\SQ\Exceptions\QuerySyntaxException $e) {
            return event(new ExporterFailed($exporter, $e, $this->agent));
        } catch (\Twig_Error $e) {
            $e = new \Exception($e->getRawMessage().' on line '.$e->getTemplateLine());

            return event(new ExporterFailed($exporter, $e, $this->agent));
        }

        $fm = new FileManager();
        fclose($file);

        $result = $fm->create([]);
        $resource = $result->getResource();

        $resource
            ->addMedia($filename)
            ->addCustomHeaders([
                'ContentDisposition' => 'attachment; filename='.basename($filename).'',
                'ContentType'        => 'text/csv',
            ])
            ->toMediaCollection('exporter');

        event(new ExporterGenerated($exporter, $result->getResource(), $this->agent));
    }
}
