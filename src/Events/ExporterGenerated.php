<?php

namespace Amethyst\Events;

use Amethyst\Models\Exporter;
use Amethyst\Models\File;
use Illuminate\Queue\SerializesModels;
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
     * @param \Amethyst\Models\Exporter            $exporter
     * @param \Amethyst\Models\File                $file
     * @param \Railken\Lem\Contracts\AgentContract $agent
     */
    public function __construct(Exporter $exporter, File $file, AgentContract $agent = null)
    {
        $this->exporter = $exporter;
        $this->file = $file;
        $this->agent = $agent;
    }
}
