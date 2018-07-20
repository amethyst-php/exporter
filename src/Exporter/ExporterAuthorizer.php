<?php

namespace Railken\LaraOre\Exporter;

use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\Tokens;

class ExporterAuthorizer extends ModelAuthorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'exporter.create',
        Tokens::PERMISSION_UPDATE => 'exporter.update',
        Tokens::PERMISSION_SHOW   => 'exporter.show',
        Tokens::PERMISSION_REMOVE => 'exporter.remove',
    ];
}
