<?php

namespace Railken\Amethyst\Fakers;

use Faker\Factory;
use Railken\Bag;
use Railken\Lem\Faker;

class ExporterFaker extends Faker
{
    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $faker = Factory::create();

        $bag = new Bag();
        $bag->set('name', $faker->name);
        $bag->set('description', $faker->text);
        $bag->set('data_builder', DataBuilderFaker::make()->parameters()->toArray());
        $bag->set('filename', 'exporters-{{ "now"|date("Ymd") }}');
        $bag->set('class_name', \Railken\Amethyst\Jobs\GenerateExportCsv::class);
        $bag->set('body', ''.
            "name: '{{ record.name }}'\n".
            'flag: 2'
        );

        return $bag;
    }
}
