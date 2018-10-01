<?php

namespace Railken\Amethyst\Tests\Http\Admin;

use Railken\Amethyst\Api\Support\Testing\TestableBaseTrait;
use Railken\Amethyst\Fakers\ExporterFaker;
use Railken\Amethyst\Managers\ExporterManager;
use Railken\Amethyst\Tests\BaseTest;

class ExporterTest extends BaseTest
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
    protected $config = 'amethyst.exporter.http.admin.exporter';

    public function testGenerate()
    {
        $manager = new ExporterManager();

        $result = $manager->create(ExporterFaker::make()->parameters());
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->calLAndTest('POST', $this->getResourceUrl().'/'.$resource->id.'/generate', ['data' => ['name' => $resource->name]], 200);
    }
}
