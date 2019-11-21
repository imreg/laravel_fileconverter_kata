<?php

namespace App\Services\Files\Interfaces;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

interface FileInterface
{
    /**
     * @param string $string
     * @return bool
     */
    public function store(string $string): bool;

    /**
     * @return string|null
     * @throws FileNotFoundException
     */
    public function fetch(): string;
}
