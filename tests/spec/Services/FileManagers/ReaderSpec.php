<?php

namespace spec\App\Services\FileManagers;

use App\Entities\Account;
use App\Services\Converters\CsvConverter;
use App\Services\Converters\JsonConverter;
use App\Services\Converters\XmlConverter;
use App\Services\FileManagers\Reader;
use App\Services\Files\Interfaces\FileInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReaderSpec extends ObjectBehavior
{
    function it_return_array_when_json_string_was_read(FileInterface $file)
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
  },
  {
    "user_id": 6,
    "first_name": "Jessica",
    "last_name": "James",
    "username": "jj56",
    "user_type": "Employee",
    "last_login_time": "13-01-2015 08:56:12"
  }
]
EOT;

        $user4 = new Account();
        $user4->setUserId(4);
        $user4->setFirstName('Joe');
        $user4->setLastName('Public');
        $user4->setUsername('joey99');
        $user4->setUserType('Employee');
        $user4->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '22-09-2014 08:23:54')
        );

        $user6 = new Account();
        $user6->setUserId(6);
        $user6->setFirstName('Jessica');
        $user6->setLastName('James');
        $user6->setUsername('jj56');
        $user6->setUserType('Employee');
        $user6->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '13-01-2015 08:56:12')
        );

        $file->fetch()->willReturn($jsonString);
        $this->beConstructedWith($file, new JsonConverter());
        $this->read()->shouldHaveCount(2);

        $this->read()->shouldBeLike([
            4 => $user4,
            6 => $user6
        ]);
    }

    function it_return_array_when_csv_string_was_read(FileInterface $file)
    {
        $csvString = <<<EOT
User ID,First Name,Last Name,Username,User Type,Last Login Time
3,David,Payne,Dpayne,Manager,23-09-2014 09:35:02
EOT;
        $file->fetch()->willReturn($csvString);
        $this->beConstructedWith($file, new CsvConverter());

        $user3 = new Account();
        $user3->setUserId(3);
        $user3->setFirstName('David');
        $user3->setLastName('Payne');
        $user3->setUsername('Dpayne');
        $user3->setUserType('Manager');
        $user3->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '23-09-2014 09:35:02')
        );

        $this->read()->shouldHaveCount(1);

        $this->read()->shouldBeLike([
            3 => $user3
        ]);
    }

    function it_return_array_when_xml_string_was_read(FileInterface $file)
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

        $file->fetch()->willReturn($xmlString);
        $this->beConstructedWith($file, new XmlConverter());

        $user1 = new Account();
        $user1->setUserId(1);
        $user1->setFirstName('John');
        $user1->setLastName('Doe');
        $user1->setUsername('John1');
        $user1->setUserType('Employee');
        $user1->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '12-01-2015 12:01:34')
        );

        $user9 = new Account();
        $user9->setUserId(9);
        $user9->setFirstName('Shaun');
        $user9->setLastName('Stevens');
        $user9->setUsername('shaun');
        $user9->setUserType('Employee');
        $user9->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '01-02-2015 13:00:00')
        );

        $this->read()
            ->shouldHaveCount(2);

        $this->read()->shouldBeLike([
            1 => $user1,
            9 => $user9
        ]);
    }
}
