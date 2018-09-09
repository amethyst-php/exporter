<?php

namespace Railken\LaraOre\Tests\Exporter;

use Illuminate\Support\Facades\Config;
use Railken\LaraOre\Api\Support\Testing\TestableBaseTrait;
use Railken\LaraOre\Exporter\ExporterFaker;
use Railken\LaraOre\Exporter\ExporterManager;

class ApiTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = ExporterFaker::class;

    /**
     * Router group resource.
     *
     * @var string
     */
    protected $group = 'admin';

    /**
     * Base path config.
     *
     * @var string
     */
    protected $config = 'ore.exporter';

    public function testGenerate()
    {
        $manager = new ExporterManager();

        $result = $manager->create(ExporterFaker::make()->parameters()->set('repository.class_name', \Railken\LaraOre\Tests\Exporter\Repositories\ExporterRepository::class));
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->calLAndTest('POST', $this->getResourceUrl().'/'.$resource->id.'/generate', ['data' => ['name' => $resource->name]], 200);
    }
}
