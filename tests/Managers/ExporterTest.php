<?php

namespace Railken\Amethyst\Tests\Managers;

use Railken\Amethyst\Fakers\ExporterFaker;
use Railken\Amethyst\Managers\ExporterManager;
use Railken\Amethyst\Tests\BaseTest;
use Railken\Lem\Support\Testing\TestableBaseTrait;

class ExporterTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = ExporterManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = ExporterFaker::class;

    public function testGenerate()
    {
        $manager = $this->getManager();

        $result = $manager->create(ExporterFaker::make()->parameters());
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());
    }
}
