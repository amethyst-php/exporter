<?php

namespace Amethyst\Tests\Http\Admin;

use Amethyst\Core\Support\Testing\TestableBaseTrait;
use Amethyst\Fakers\ExporterFaker;
use Amethyst\Managers\ExporterManager;
use Amethyst\Tests\BaseTest;

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
    protected $route = 'admin.exporter';

    public function testGenerate()
    {
        $manager = new ExporterManager();

        $result = $manager->create(ExporterFaker::make()->parameters());
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->callAndTest('POST', route('admin.exporter.execute', ['id' => $resource->id]), ['data' => ['name' => $resource->name]], 200);
    }
}
