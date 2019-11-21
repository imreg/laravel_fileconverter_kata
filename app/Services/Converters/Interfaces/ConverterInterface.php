<?php

namespace App\Services\Converters\Interfaces;

use Illuminate\Filesystem\Filesystem;

interface ConverterInterface
{
    /**
     * @param string $string
     * @return array
     */
    public function unserialize(string $string): array;

    /**
     * @param array $details
     * @return string
     */
    public function serialize(array $details): string;
}
