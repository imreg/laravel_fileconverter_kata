<?php

namespace spec\App\Services\Converters;

use App\Services\Converters\JsonConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(JsonConverter::class);
    }

    function it_returns_array_after_json_string_unserialized()
    {

    }

    function it_returns_json_string_after_array_serialized_()
    {

    }
}
