<?php

namespace Railken\LaraOre\Tests\Exporter;

use Railken\LaraOre\Exporter\ExporterFaker;
use Railken\LaraOre\Exporter\ExporterManager;
use Railken\LaraOre\Support\Testing\ManagerTestableTrait;

class ManagerTest extends BaseTest
{
    use ManagerTestableTrait;

    /**
     * Retrieve basic url.
     *
     * @return \Railken\Laravel\Manager\Contracts\ManagerContract
     */
    public function getManager()
    {
        return new ExporterManager();
    }

    public function testSuccessCommon()
    {
        $this->commonTest($this->getManager(), ExporterFaker::make()->parameters());
    }

    public function testGenerate()
    {
        $manager = $this->getManager();

        $result = $manager->create(ExporterFaker::make()->parameters()->set('repository.class_name', \Railken\LaraOre\Tests\Exporter\Repositories\ExporterRepository::class));
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());
    }
}
