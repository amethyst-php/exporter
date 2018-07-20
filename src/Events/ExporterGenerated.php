<?php

namespace Railken\LaraOre\Events;

use Illuminate\Queue\SerializesModels;
use Railken\LaraOre\Exporter\Exporter;
use Railken\LaraOre\File\File;
use Railken\Laravel\Manager\Contracts\AgentContract;

class ExporterGenerated
{
    use SerializesModels;

    public $exporter;
    public $file;
    public $agent;

    /**
     * Create a new event instance.
     *
     * @param \Railken\LaraOre\Exporter\Exporter               $exporter
     * @param \Railken\LaraOre\File\File                       $file
     * @param \Railken\Laravel\Manager\Contracts\AgentContract $agent
     */
    public function __construct(Exporter $exporter, File $file, AgentContract $agent = null)
    {
        $this->exporter = $exporter;
        $this->file = $file;
        $this->agent = $agent;
    }
}
