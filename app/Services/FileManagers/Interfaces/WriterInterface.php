<?php


namespace App\Services\FileManagers\Interfaces;


interface WriterInterface extends FileManagerInterface
{
    /**
     * @param array $details
     * @return bool
     */
    public function write(array $details): bool;
}
