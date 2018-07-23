<?php

namespace Railken\LaraOre\Tests\Exporter\Repositories;

use Closure;
use Illuminate\Support\Collection;
use Railken\LaraOre\Contracts\RepositoryContract;
use Railken\LaraOre\Exporter\ExporterManager;

class ExporterRepository implements RepositoryContract
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ExporterManager();
    }

    public function newQuery()
    {
        return $this->manager->getRepository()->newQuery();
    }

    public function getTableName()
    {
        return $this->manager->newEntity()->getTable();
    }

    /**
     * @param Collection $resources
     * @param \Closure   $callback
     */
    public function extract(Collection $resources, Closure $callback)
    {
        foreach ($resources as $resource) {
            $callback($resource, ['exports' => $resource]);
        }
    }

    /**
     * @param Collection $resources
     *
     * @return Collection
     */
    public function parse(Collection $resources)
    {
        return ['exports' => $resources];
    }
}
