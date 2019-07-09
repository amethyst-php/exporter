<?php

namespace Amethyst\Authorizers;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class ExporterAuthorizer extends Authorizer
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
