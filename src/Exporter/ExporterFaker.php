<?php

namespace Railken\LaraOre\Exporter;

use Faker\Factory;
use Railken\Bag;
use Railken\LaraOre\Repository\RepositoryFaker;
use Railken\Laravel\Manager\BaseFaker;

class ExporterFaker extends BaseFaker
{
    /**
     * @var string
     */
    protected $manager = ExporterManager::class;

    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $faker = Factory::create();

        $bag = new Bag();
        $bag->set('name', $faker->name);
        $bag->set('repository', RepositoryFaker::make()->parameters()->toArray());
        $bag->set('input', [
            'name' => ['type' => 'text', 'validation' => 'string'],
        ]);
        $bag->set('filename', 'users-{{ "now"|date("Ymd") }}');
        $bag->set('body', [
            'name' => '{{ resource.name }}',
            'flag' => 2,
        ]);

        return $bag;
    }
}
