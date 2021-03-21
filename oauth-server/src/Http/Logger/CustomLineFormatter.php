<?php

declare(strict_types=1);

namespace App\Http\Logger;

use Monolog\Formatter\LineFormatter;

class CustomLineFormatter extends LineFormatter
{
    public function format(array $record): string
    {
        $output = parent::format($record);
        return sprintf("%s\r\n", $output);
    }
}