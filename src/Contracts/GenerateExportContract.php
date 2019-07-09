<?php

namespace Amethyst\Contracts;

use Amethyst\Models\Exporter;
use Railken\Lem\Contracts\AgentContract;

interface GenerateExportContract
{
    /**
     * Create a new job instance.
     *
     * @param Exporter                             $exporter
     * @param array                                $data
     * @param \Railken\Lem\Contracts\AgentContract $agent
     */
    public function __construct(Exporter $exporter, array $data = [], AgentContract $agent = null);
}
