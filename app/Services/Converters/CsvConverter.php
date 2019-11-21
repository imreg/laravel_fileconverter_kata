<?php
declare(strict_types=1);

namespace App\Services\Converters;

use App\Entities\Account;
use App\Services\Converters\Interfaces\ConverterInterface;

class CsvConverter implements ConverterInterface
{

    /**
     * @var array
     */
    public $header = [
        'User ID',
        'First Name',
        'Last Name',
        'Username',
        'User Type',
        'Last Login Time'
    ];

    /**
     * @inheritDoc
     */
    public function unserialize(string $string): array
    {
        $array = [];
        $csvArray = str_getcsv($string, PHP_EOL);
        $header = array_shift($csvArray);
        foreach ($csvArray as $row) {
            $value = explode(',', $row);
            $account = new Account();
            $account->setUserId((int)$value[0]);
            $account->setFirstName($value[1]);
            $account->setLastName($value[2]);
            $account->setUsername($value[3]);
            $account->setUserType($value[4]);

            $account->setLastLoginTime(
                \DateTime::createFromFormat('d-m-Y H:i:s', $value[5]) ?
                    \DateTime::createFromFormat('d-m-Y H:i:s', $value[5]) :
                    new \DateTime($value[5])
            );

            $array[(int)$value[0]] = $account;
        }

        return $array;
    }

    /**
     * @inheritDoc
     */
    public function serialize(array $details): string
    {
        $string = implode(',', $this->header) . PHP_EOL;

        foreach ($details as $key => $value) {
            $user = [
                $value->getUserId(),
                $value->getFirstName(),
                $value->getLastName(),
                $value->getUsername(),
                $value->getUserType(),
                $value->getLastLoginTime()->format(DATE_ATOM),
            ];
            $string .= implode(',', $user) . PHP_EOL;
        }

        return $string;
    }
}
