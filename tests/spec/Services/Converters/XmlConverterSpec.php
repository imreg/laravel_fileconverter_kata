<?php

namespace spec\App\Services\Converters;

use App\Entities\Account;
use App\Services\Converters\XmlConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XmlConverterSpec extends ObjectBehavior
{
    /**
     * @var Account
     */
    private $user1 = null;

    /**
     * @var Account
     */
    private $user9 = null;

    function let()
    {
        $this->user1 = new Account();
        $this->user1->setUserId(1);
        $this->user1->setFirstName('John');
        $this->user1->setLastName('Doe');
        $this->user1->setUsername('John1');
        $this->user1->setUserType('Employee');
        $this->user1->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '12-01-2015 12:01:34')
        );

        $this->user9 = new Account();
        $this->user9->setUserId(9);
        $this->user9->setFirstName('Shaun');
        $this->user9->setLastName('Stevens');
        $this->user9->setUsername('shaun');
        $this->user9->setUserType('Employee');
        $this->user9->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '01-02-2015 13:00:00')
        );
    }

    function it_returns_array_after_xml_string_unserialized()
    {
        $xmlString = <<<EOT
<?xml version='1.0' encoding='utf-8'?>
<users>
    <user>
        <userid>1</userid>
        <firstname>John</firstname>
        <surname>Doe</surname>
        <username>John1</username>
        <type>Employee</type>
        <lastlogintime>12-01-2015 12:01:34</lastlogintime>
    </user>
    <user>
        <userid>9</userid>
        <firstname>Shaun</firstname>
        <surname>Stevens</surname>
        <username>shaun</username>
        <type>Employee</type>
        <lastlogintime>01-02-2015 13:00:00</lastlogintime>
    </user>
</users>
EOT;
        $this->unserialize($xmlString)->shouldHaveCount(2);
        $this->unserialize($xmlString)->shouldBeLike([
            1 => $this->user1,
            9 => $this->user9
        ]);
    }

    function it_returns_xml_string_after_array_serialized_()
    {
        $xmlString = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<users><user><userid>1</userid><firstname>John</firstname><surname>Doe</surname><username>John1</username><type>Employee</type><lastlogintime>2015-01-12T12:01:34+00:00</lastlogintime></user></users>\n
EOT;
        $this->serialize([1 => $this->user1])->shouldBeLike($xmlString);
    }
}
