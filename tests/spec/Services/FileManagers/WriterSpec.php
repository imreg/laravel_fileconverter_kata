<?php

namespace spec\App\Services\FileManagers;

use App\Services\Converters\JsonConverter;
use App\Services\Files\Interfaces\FileInterface;
use PhpSpec\ObjectBehavior;

class WriterSpec extends ObjectBehavior
{
    function it_writes_content(FileInterface $file)
    {
        $file->store(json_encode([]))->willReturn(true);
        $this->beConstructedWith($file, new JsonConverter());
        $this->write([])->shouldBe(true);
    }
}
