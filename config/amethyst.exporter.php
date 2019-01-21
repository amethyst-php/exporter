<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    |
    | Here you can change the table name and the class components.
    |
    */
    'data' => [
        'exporter' => [
            'table'      => 'amethyst_exporters',
            'comment'    => 'Exporter',
            'model'      => Railken\Amethyst\Models\Exporter::class,
            'schema'     => Railken\Amethyst\Schemas\ExporterSchema::class,
            'repository' => Railken\Amethyst\Repositories\ExporterRepository::class,
            'serializer' => Railken\Amethyst\Serializers\ExporterSerializer::class,
            'validator'  => Railken\Amethyst\Validators\ExporterValidator::class,
            'authorizer' => Railken\Amethyst\Authorizers\ExporterAuthorizer::class,
            'faker'      => Railken\Amethyst\Fakers\ExporterFaker::class,
            'manager'    => Railken\Amethyst\Managers\ExporterManager::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Http configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the routes
    |
    */
    'http' => [
        'admin' => [
            'exporter' => [
                'enabled'    => true,
                'controller' => Railken\Amethyst\Http\Controllers\Admin\ExportersController::class,
                'router'     => [
                    'as'     => 'exporter.',
                    'prefix' => '/exporters',
                ],
            ],
        ],
    ],
];
