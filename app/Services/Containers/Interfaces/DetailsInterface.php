<?php

namespace App\Services\Containers\Interfaces;

use App\Services\FileManagers\Interfaces\ReaderInterface;
use App\Services\FileManagers\Interfaces\WriterInterface;

interface DetailsInterface
{
    /**
     * @param ReaderInterface $reader
     * @return mixed|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function addDetails(ReaderInterface $reader);

    /**
     * @return array
     */
    public function getDetails(): array;

    /**
     * @return bool
     */
    public function sortDataById(): bool;

    /**
     * @param WriterInterface $write
     * @return bool
     */
    public function saveDetails(WriterInterface $write): bool;
}
