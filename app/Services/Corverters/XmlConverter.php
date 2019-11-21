<?php
declare(strict_types=1);

namespace App\Services\Corverters;

use App\Entities\Account;
use App\Services\Corverters\Interfaces\ConverterInterface;

class XmlConverter implements ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(array $details): string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><users></users>');

        foreach ($details as $key => $value) {
            $xmlChild = $xml->addChild('user');
            $xmlChild->addChild('userid', strval($value->getUserId()));
            $xmlChild->addChild('firstname', $value->getFirstName());
            $xmlChild->addChild('surname', $value->getLastName());
            $xmlChild->addChild('username', $value->getUsername());
            $xmlChild->addChild('type', $value->getUserType());
            $xmlChild->addChild('lastlogintime', $value->getLastLoginTime()->format(DATE_ATOM));
        }

        return $xml->asXML();
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $string): array
    {
        $array = [];
        $simpleXml = simplexml_load_string($string);

        foreach ((array)$simpleXml as $key => $item) {
            foreach ($item as $value) {
                $content = (array)$value;
                $user = new Account();
                $user->setUserId((int)$content['userid']);
                $user->setFirstName($content['firstname']);
                $user->setLastName($content['surname']);
                $user->setUsername($content['username']);
                $user->setUserType($content['type']);

                $user->setLastLoginTime(
                    \DateTime::createFromFormat('d-m-Y H:i:s', $content['lastlogintime']) ?
                        \DateTime::createFromFormat('d-m-Y H:i:s', $content['lastlogintime']) :
                        new \DateTime($content['lastlogintime'])
                );

                $array[(int)$content['userid']] = $user;
            }
        }

        return $array;
    }
}
