<?php

namespace Railken\LaraOre\Tests\Exporter;

use Illuminate\Support\Facades\Config;
use Railken\LaraOre\Exporter\ExporterFaker;
use Railken\LaraOre\Exporter\ExporterManager;
use Railken\LaraOre\Support\Testing\ApiTestableTrait;

class ApiTest extends BaseTest
{
    use ApiTestableTrait;

    /**
     * Retrieve basic url.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return Config::get('ore.api.router.prefix').Config::get('ore.exporter.http.admin.router.prefix');
    }

    /**
     * Test common requests.
     */
    public function testSuccessCommon()
    {
        $this->commonTest($this->getBaseUrl(), ExporterFaker::make()->parameters());
    }

    public function testGenerate()
    {
        $manager = new ExporterManager();

        $result = $manager->create(ExporterFaker::make()->parameters()->set('repository.class_name', \Railken\LaraOre\Tests\Exporter\Repositories\ExporterRepository::class));
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->post($this->getBaseUrl().'/'.$resource->id.'/generate', ['data' => ['name' => $resource->name]]);
        $response->assertStatus(200);
    }
}
