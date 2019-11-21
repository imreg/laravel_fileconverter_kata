<?php
declare(strict_types=1);

namespace App\Services\Files;

use App\Services\Files\Interfaces\FileInterface;
use \Illuminate\Contracts\Filesystem\Filesystem;

class File implements FileInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $file;

    /**
     * Json constructor.
     * @param Filesystem $filesystem
     * @param string $file
     */
    public function __construct(Filesystem $filesystem, string $file)
    {
        $this->filesystem = $filesystem;
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function store(string $string): bool
    {
        if (!$this->file) {
            return false;
        }

        return $this->filesystem->put($this->file, $string);
    }

    /**
     * @inheritDoc
     */
    public function fetch(): string
    {
        return $this->filesystem->get($this->file);
    }
}
