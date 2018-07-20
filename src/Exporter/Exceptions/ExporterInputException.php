<?php

namespace Railken\LaraOre\Exporter\Exceptions;

class ExporterInputException extends ExporterException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'REPORT_INPUT_INVALID';

    /**
     * Construct.
     *
     * @param string $key
     * @param mixed  $message
     * @param mixed  $value
     */
    public function __construct($key, $message = null, $value = null)
    {
        $this->value = $value;
        $this->label = $key;
        $this->message = $message;

        parent::__construct();
    }
}
