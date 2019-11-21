<?php
declare(strict_types=1);

namespace Tests\UnitIntegration;

use App\Entities\Account;
use App\Services\Containers\Details;
use App\Services\Converters\CsvConverter;
use App\Services\Converters\JsonConverter;
use App\Services\Converters\XmlConverter;
use App\Services\FileManagers\Reader;
use App\Services\FileManagers\Writer;
use App\Services\Files\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileConverterTest extends TestCase
{
    /**
     * @var Details
     */
    private $detailsContainer = null;

    /**
     * @var string
     */
    private $csvString;

    /**
     * @var string
     */
    private $jsonString;

    /**
     * @var string
     */
    private $xmlString;

    /**
     * @var string
     */
    private $csvFileName;

    /**
     * @var string
     */
    private $jsonFileName;

    /**
     * @var string
     */
    private $xmlFileName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->detailsContainer = new Details();

        $this->csvString = $this->getCsvString();
        $this->jsonString = $this->getJsonString();
        $this->xmlString = $this->getXmlString();

        $this->csvFileName = 'testOutput/integration/user.csv';
        $this->xmlFileName = 'testOutput/integration/user.xml';
        $this->jsonFileName = 'testOutput/integration/user.json';
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function assertPreConditions(): void
    {
        $json = $this->createMock(File::class);
        $json->expects($this->any())
            ->method('fetch')
            ->willReturn($this->jsonString);

        $this->detailsContainer->addDetails(new Reader(
            $json,
            new JsonConverter()
        ));

        $csv = $this->createMock(File::class);
        $csv->expects($this->any())
            ->method('fetch')
            ->willReturn($this->csvString);

        $this->detailsContainer->addDetails(new Reader(
            $csv,
            new CsvConverter()
        ));

        $xml = $this->createMock(File::class);
        $xml->expects($this->any())
            ->method('fetch')
            ->willReturn($this->xmlString);

        $this->detailsContainer->addDetails(new Reader(
            $xml,
            new XmlConverter()
        ));
    }

    public function testDetails()
    {
        $result = $this->detailsContainer->getDetails();
        $this->assertEquals(6, count($result));
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     */
    public function testDetailsOrderedById($data)
    {
        $this->detailsContainer->sortDataById();
        $result = $this->detailsContainer->getDetails();
        $this->assertEquals($data, $result);
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWriteCsvMethod($data)
    {
        $this->detailsContainer->saveDetails(new Writer(
            new File(Storage::fake('public'), $this->csvFileName),
            new CsvConverter()
        ));

        Storage::disk('public')->assertExists($this->csvFileName);
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWriteJsonMethod($data)
    {
        $this->detailsContainer->saveDetails(new Writer(
            new File(Storage::fake('public'), $this->jsonFileName),
            new CsvConverter()
        ));

        Storage::disk('public')->assertExists($this->jsonFileName);
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWriteXmlMethod($data)
    {
        $this->detailsContainer->saveDetails(new Writer(
            new File(Storage::fake('public'), $this->xmlFileName),
            new CsvConverter()
        ));

        Storage::disk('public')->assertExists($this->xmlFileName);
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWriteCsv($data)
    {
        $fake = Storage::fake('public');
        $this->detailsContainer->saveDetails(new Writer(
            new File($fake, $this->csvFileName),
            new CsvConverter()
        ));

        $detailsContainer = new Details();
        $detailsContainer->addDetails(new Reader(
            new File(Storage::disk('public'), $this->csvFileName),
            new CsvConverter()
        ));

        $result = $detailsContainer->getDetails();
        $this->assertEquals($data, $result);
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWriteJson($data)
    {
        $fake = Storage::fake('public');
        $this->detailsContainer->saveDetails(new Writer(
            new File($fake, $this->jsonFileName),
            new JsonConverter()
        ));

        $detailsContainer = new Details();
        $detailsContainer->addDetails(new Reader(
            new File(Storage::disk('public'), $this->jsonFileName),
            new JsonConverter()
        ));

        $result = $detailsContainer->getDetails();
        $this->assertEquals($data, $result);
    }

    /**
     * @dataProvider additionProvider
     * @param $data
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testWriteXml($data)
    {
        $fake = Storage::fake('public');
        $this->detailsContainer->saveDetails(new Writer(
            new File($fake, $this->xmlFileName),
            new XmlConverter()
        ));

        $detailsContainer = new Details();
        $detailsContainer->addDetails(new Reader(
            new File(Storage::disk('public'), $this->xmlFileName),
            new XmlConverter()
        ));

        $result = $detailsContainer->getDetails();
        $this->assertEquals($data, $result);
    }


    /**
     * @return string
     */
    protected function getCsvString(): string
    {
        return <<<EOT
User ID,First Name,Last Name,Username,User Type,Last Login Time
3,David,Payne,Dpayne,Manager,23-09-2014 09:35:02
10,Ruby,Wax,ruby,Employee,12-12-2014 08:09:13
EOT;
    }

    /**
     * @return string
     */
    protected function getJsonString(): string
    {
        return <<<EOT
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
    }

    /**
     * @return string
     */
    protected function getXmlString(): string
    {
        return <<<EOT
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
    }

    public function additionProvider()
    {

        $account4 = new Account();
        $account4->setUserId(4);
        $account4->setFirstName('Joe');
        $account4->setLastName('Public');
        $account4->setUsername('joey99');
        $account4->setUserType('Employee');
        $account4->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '22-09-2014 08:23:54')
        );

        $account1 = new Account();
        $account1->setUserId(1);
        $account1->setFirstName('John');
        $account1->setLastName('Doe');
        $account1->setUsername('John1');
        $account1->setUserType('Employee');
        $account1->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '12-01-2015 12:01:34')
        );

        $account3 = new Account();
        $account3->setUserId(3);
        $account3->setFirstName('David');
        $account3->setLastName('Payne');
        $account3->setUsername('Dpayne');
        $account3->setUserType('Manager');
        $account3->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '23-09-2014 09:35:02')

        );

        $account9 = new Account();
        $account9->setUserId(9);
        $account9->setFirstName('Shaun');
        $account9->setLastName('Stevens');
        $account9->setUsername('shaun');
        $account9->setUserType('Employee');
        $account9->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '01-02-2015 13:00:00')
        );

        $account10 = new Account();
        $account10->setUserId(10);
        $account10->setFirstName('Ruby');
        $account10->setLastName('Wax');
        $account10->setUsername('ruby');
        $account10->setUserType('Employee');
        $account10->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '12-12-2014 08:09:13')
        );

        $account6 = new Account();
        $account6->setUserId(6);
        $account6->setFirstName('Jessica');
        $account6->setLastName('James');
        $account6->setUsername('jj56');
        $account6->setUserType('Employee');
        $account6->setLastLoginTime(
            \DateTime::createFromFormat('d-m-Y H:i:s', '13-01-2015 08:56:12')
        );

        return [
            [
                [
                    1 => $account1,
                    3 => $account3,
                    4 => $account4,
                    6 => $account6,
                    9 => $account9,
                    10 => $account10
                ]
            ]
        ];
    }
}
