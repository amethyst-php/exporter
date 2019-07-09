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
            'model'      => Amethyst\Models\Exporter::class,
            'schema'     => Amethyst\Schemas\ExporterSchema::class,
            'repository' => Amethyst\Repositories\ExporterRepository::class,
            'serializer' => Amethyst\Serializers\ExporterSerializer::class,
            'validator'  => Amethyst\Validators\ExporterValidator::class,
            'authorizer' => Amethyst\Authorizers\ExporterAuthorizer::class,
            'faker'      => Amethyst\Fakers\ExporterFaker::class,
            'manager'    => Amethyst\Managers\ExporterManager::class,
            'attributes' => [
                'class_name' => [
                    'options' => [
                        Amethyst\Jobs\GenerateExportCsv::class,
                        Amethyst\Jobs\GenerateExportFixed::class,
                        Amethyst\Jobs\GenerateExportXls::class,
                    ],
                ],
            ],
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
                'controller' => Amethyst\Http\Controllers\Admin\ExportersController::class,
                'router'     => [
                    'as'     => 'exporter.',
                    'prefix' => '/exporters',
                ],
            ],
        ],
    ],
];
