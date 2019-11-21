<?php
declare(strict_types=1);

namespace App\Services\FileManagers;

use App\Services\Converters\Interfaces\ConverterInterface;
use App\Services\FileManagers\Interfaces\WriterInterface;
use App\Services\Files\Interfaces\FileInterface;

class Writer implements WriterInterface
{

    /**
     * @var FileInterface
     */
    private $file;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * Writer constructor.
     * @param FileInterface $file
     * @param ConverterInterface $converter
     */
    public function __construct(FileInterface $file, ConverterInterface $converter)
    {
        $this->file = $file;
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function write(array $details): bool
    {
        $string = $this->converter->serialize($details);
        return $this->file->store($string);
    }
}
