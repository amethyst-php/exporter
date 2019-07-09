<?php

namespace Amethyst\Tests\Managers;

use Amethyst\Fakers\ExporterFaker;
use Amethyst\Managers\ExporterManager;
use Amethyst\Tests\BaseTest;
use Railken\Lem\Support\Testing\TestableBaseTrait;
use Symfony\Component\Yaml\Yaml;

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

    /**
     * Testing with generation.
     */
    public function testGenerate()
    {
        $manager = $this->getManager();
        $result = $manager->create(ExporterFaker::make()->parameters());
        $this->assertEquals(1, $result->ok());

        // CSV
        $resource = $result->getResource();
        $resource->class_name = \Amethyst\Jobs\GenerateExportCsv::class;
        $resource->filename = 'generated.csv';
        $resource->save();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());

        // Excel
        $resource->class_name = \Amethyst\Jobs\GenerateExportXls::class;
        $resource->filename = 'generated.xls';
        $resource->save();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());

        // POS
        $resource->class_name = \Amethyst\Jobs\GenerateExportFixed::class;
        $resource->filename = 'generated.txt';
        $resource->body = Yaml::dump([
            'name' => [
                'value'  => '{{ record.name }}',
                'length' => 255,
            ],
            'flag' => [
                'value'  => 2,
                'length' => 3,
            ],
        ]);
        $resource->save();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());
    }
}
