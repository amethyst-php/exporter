<?php

namespace Railken\Amethyst\Events;

use Exception;
use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Models\Exporter;
use Railken\Lem\Contracts\AgentContract;

class ExporterFailed
{
    use SerializesModels;

    public $exporter;
    public $error;
    public $agent;

    /**
     * Create a new event instance.
     *
     * @param \Railken\Amethyst\Models\Exporter    $exporter
     * @param \Exception                           $exception
     * @param \Railken\Lem\Contracts\AgentContract $agent
     */
    public function __construct(Exporter $exporter, Exception $exception, AgentContract $agent = null)
    {
        $this->exporter = $exporter;
        $this->error = (object) [
            'class'   => get_class($exception),
            'message' => $exception->getMessage(),
        ];

        $this->agent = $agent;
    }
}
