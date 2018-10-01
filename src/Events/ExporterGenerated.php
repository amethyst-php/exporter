<?php

namespace Railken\Amethyst\Events;

use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Models\Exporter;
use Railken\Amethyst\Models\File;
use Railken\Lem\Contracts\AgentContract;

class ExporterGenerated
{
    use SerializesModels;

    public $exporter;
    public $file;
    public $agent;

    /**
     * Create a new event instance.
     *
     * @param \Railken\Amethyst\Models\Exporter    $exporter
     * @param \Railken\Amethyst\Models\File        $file
     * @param \Railken\Lem\Contracts\AgentContract $agent
     */
    public function __construct(Exporter $exporter, File $file, AgentContract $agent = null)
    {
        $this->exporter = $exporter;
        $this->file = $file;
        $this->agent = $agent;
    }
}
