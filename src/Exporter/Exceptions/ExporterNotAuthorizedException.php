<?php

namespace Railken\LaraOre\Exporter\Exceptions;

class ExporterNotAuthorizedException extends ExporterException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'REPORT_NOT_AUTHORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
