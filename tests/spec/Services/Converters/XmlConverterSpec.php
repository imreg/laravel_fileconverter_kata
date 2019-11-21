<?php

namespace spec\App\Services\Converters;

use App\Services\Converters\XmlConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XmlConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(XmlConverter::class);
    }

    function it_returns_array_after_xml_string_unserialized()
    {

    }

    function it_returns_xml_string_after_array_serialized_()
    {

    }
}
