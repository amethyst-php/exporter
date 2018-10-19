<?php

namespace Railken\Amethyst\Jobs;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;

class GenerateExportCsv extends GenerateExportCommon
{
    public function getMimeType()
    {
        return 'text/csv';
    }

    public function newWriter($filename)
    {
        $writer = WriterFactory::create(Type::CSV);
        $writer->openToFile($filename);

        return $writer;
    }

    public function write($writer, $value)
    {
        $writer->addRow($value);
    }

    public function shouldWriteHead()
    {
        return true;
    }

    public function save($writer)
    {
        $writer->close();
    }
}
