<?php

namespace Railken\Amethyst\Managers;

use Illuminate\Support\Facades\Config;
use Railken\Amethyst\Jobs\GenerateExporter;
use Railken\Amethyst\Models\Exporter;
use Railken\Lem\Manager;

class ExporterManager extends Manager
{
    /**
     * Describe this manager.
     *
     * @var string
     */
    public $comment = '...';

    /**
     * Register Classes.
     */
    public function registerClasses()
    {
        return Config::get('amethyst.exporter.managers.exporter');
    }

    /**
     * Request a exporter.
     *
     * @param Exporter $exporter
     * @param array    $data
     *
     * @return \Railken\Lem\Contracts\ResultContract
     */
    public function generate(Exporter $exporter, array $data = [])
    {
        $result = (new DataBuilderManager())->validateRaw($exporter->data_builder, $data);

        if (!$result->ok()) {
            return $result;
        }

        dispatch(new GenerateExporter($exporter, $data, $this->getAgent()));

        return $result;
    }
}
