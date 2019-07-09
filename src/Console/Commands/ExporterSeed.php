<?php

namespace Amethyst\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Amethyst\Managers\DataBuilderManager;
use Amethyst\Managers\ExporterManager;
use Symfony\Component\Yaml\Yaml;

class ExporterSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amethyst:exporter:seed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dataBuilderManager = new DataBuilderManager();
        $exporterManager = new ExporterManager();

        $managers = app('amethyst')->getData()->map(function ($data) {
            return Arr::get($data, 'manager');
        });

        foreach ($managers as $classManager) {
            $dataBuilderRecord = $dataBuilderManager->getRepository()->findOneBy([
                'name' => (new $classManager())->getName().' by dates',
            ]);

            $dataBuilder = $dataBuilderRecord->newInstanceData();

            $exporterManager->updateOrCreateOrFail([
                'name' => (new $classManager())->getName().' by dates',
            ], [
                'data_builder_id' => $dataBuilderRecord->id,
                'filename'        => (new $classManager())->getName().'.xlsx',
                'class_name'      => \Amethyst\Jobs\GenerateExportXls::class,
                'body'            => Yaml::dump($dataBuilder->getManager()->getAttributes()->mapWithKeys(function ($attribute) use ($dataBuilder) {
                    return [$attribute->getName() => '{{ '.$dataBuilder->getVariableName().'.'.$attribute->getName().' }}'];
                })->toArray()),
            ]);
        }
    }
}
