<?php

namespace spec\App\Services\Converters;

use App\Entities\Account;
use App\Services\Converters\JsonConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonConverterSpec extends ObjectBehavior
{
    private $user4 = null;

    function let()
    {
        $this->user4 = new Account();
        $this->user4->setUserId(4);
        $this->user4->setFirstName('Joe');
        $this->user4->setLastName('Public');
        $this->user4->setUsername('joey99');
        $this->user4->setUserType('Employee');
        $this->user4->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '22-09-2014 08:23:54')
        );
    }

    function it_returns_array_after_json_string_unserialized()
    {
        $jsonString = <<<EOT
[
  {
    "user_id": 4,
    "first_name": "Joe",
    "last_name": "Public",
    "username": "joey99",
    "user_type": "Employee",
    "last_login_time": "22-09-2014 08:23:54"
  }
]
EOT;
        $this->unserialize($jsonString)->shouldHaveCount(1);
        $this->unserialize($jsonString)->shouldBeLike([4 => $this->user4]);
    }

    function it_returns_json_string_after_array_serialized_()
    {
        $jsonString = '[{"user_id":4,"first_name":"Joe","last_name":"Public","username":"joey99","user_type":"Employee","last_login_time":"2014-09-22T08:23:54+00:00"}]';
        $this->serialize([4 => $this->user4])->shouldBeLike($jsonString);
    }
}
