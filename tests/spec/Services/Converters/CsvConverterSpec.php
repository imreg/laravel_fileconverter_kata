<?php

namespace spec\App\Services\Converters;

use App\Services\Converters\CsvConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CsvConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CsvConverter::class);
    }

    function it_returns_array_after_csv_string_unserialized()
    {

    }

    function it_returns_csv_string_after_array_serialized_()
    {

    }
}
