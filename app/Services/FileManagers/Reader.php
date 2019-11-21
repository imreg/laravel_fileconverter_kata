<?php
declare(strict_types=1);

namespace App\Services\FileManagers;

use App\Services\Corverters\Interfaces\ConverterInterface;
use App\Services\FileManagers\Interfaces\ReaderInterface;
use App\Services\Files\Interfaces\FileInterface;

class Reader implements ReaderInterface
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
     * Reader constructor.
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
    public function read(): array
    {
        $content = $this->file->fetch();
        return $this->converter->unserialize($content);
    }
}
