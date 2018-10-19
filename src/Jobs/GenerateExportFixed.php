<?php

namespace Railken\Amethyst\Jobs;

class GenerateExportFixed extends GenerateExportCommon
{
    public function write($writer, $value)
    {
        fwrite($writer, implode('', array_map(function ($r) {
            return $this->pad($r['value'], $r['length'], ' ');
        }, $value)));
    }

    public function shouldWriteHead()
    {
        return false;
    }

    public function pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $diff = strlen($input) - mb_strlen($input);

        return mb_substr(str_pad($input, $pad_length + $diff, $pad_string, $pad_type), 0, $pad_length);
    }
}
