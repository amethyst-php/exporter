<?php

namespace Railken\LaraOre\Exporter\Exceptions;

class ExporterNotFoundException extends ExporterException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'REPORT_NOT_FOUND';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'Not found';
}
