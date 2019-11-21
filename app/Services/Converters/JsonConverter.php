<?php
declare(strict_types=1);

namespace App\Services\Converters;

use App\Entities\Account;
use App\Services\Converters\Interfaces\ConverterInterface;

class JsonConverter implements ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $details): string
    {
        $array = [];

        foreach ($details as $key => $value) {
            $user = [
                'user_id' => $value->getUserId(),
                'first_name' => $value->getFirstName(),
                'last_name' => $value->getLastName(),
                'username' => $value->getUsername(),
                'user_type' => $value->getUserType(),
                'last_login_time' => $value->getLastLoginTime()->format(DATE_ATOM),
            ];
            $array[] = $user;
        }

        return json_encode($array);
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $string): array
    {
        $array = [];
        $jsonArray = json_decode($string);

        foreach ($jsonArray as $key => $value) {
            $user = new Account();

            $user->setUserId((int)$value->user_id);
            $user->setFirstName($value->first_name);
            $user->setLastName($value->last_name);
            $user->setUsername($value->username);
            $user->setUserType($value->user_type);

            $user->setLastLoginTime(
                \DateTime::createFromFormat('d-m-Y H:i:s', $value->last_login_time) ?
                    \DateTime::createFromFormat('d-m-Y H:i:s', $value->last_login_time) :
                    new \DateTime($value->last_login_time)

            );

            $array[(int)$value->user_id] = $user;
        }

        return $array;
    }
}
