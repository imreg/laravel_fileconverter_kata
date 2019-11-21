<?php
declare(strict_types=1);

namespace App\Services\Containers;

use App\Services\Containers\Interfaces\DetailsInterface;
use App\Services\FileManagers\Interfaces\ReaderInterface;
use App\Services\FileManagers\Interfaces\WriterInterface;

class Details implements DetailsInterface
{
    /**
     * @var array
     */
    private $details = [];

    /**
     * @inheritDoc
     */
    public function addDetails(ReaderInterface $reader)
    {
        $this->details += $reader->read();
    }

    /**
     * @inheritDoc
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @inheritDoc
     */
    public function sortDataById(): bool
    {
        return ksort($this->details);
    }

    /**
     * @inheritDoc
     */
    public function saveDetails(WriterInterface $write): bool
    {
        return $write->write($this->details);
    }
}
