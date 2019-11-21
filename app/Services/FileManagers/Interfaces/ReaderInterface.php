<?php


namespace App\Services\FileManagers\Interfaces;


interface ReaderInterface extends FileManagerInterface
{
    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function read(): array;
}
