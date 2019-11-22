<?php

namespace spec\App\Services\Converters;

use App\Entities\Account;
use App\Services\Converters\CsvConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CsvConverterSpec extends ObjectBehavior
{
    private $csvString = null;
    private $user3 = null;

    function let()
    {
        $this->user3 = new Account();
        $this->user3->setUserId(3);
        $this->user3->setFirstName('David');
        $this->user3->setLastName('Payne');
        $this->user3->setUsername('Dpayne');
        $this->user3->setUserType('Manager');
        $this->user3->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '23-09-2014 09:35:02')
        );
    }

    function it_returns_array_after_csv_string_unserialized()
    {
        $csvString = <<<EOT
User ID,First Name,Last Name,Username,User Type,Last Login Time
3,David,Payne,Dpayne,Manager,23-09-2014 09:35:02
EOT;
        $this->unserialize($csvString)->shouldHaveCount(1);
        $this->unserialize($csvString)->shouldBeLike([3 => $this->user3]);
    }

    function it_returns_csv_string_after_array_serialized_()
    {
        $csvString = <<<EOT
User ID,First Name,Last Name,Username,User Type,Last Login Time
3,David,Payne,Dpayne,Manager,2014-09-23T09:35:02+00:00\n
EOT;
        $this->serialize([3 => $this->user3])->shouldBeLike($csvString);
    }
}
