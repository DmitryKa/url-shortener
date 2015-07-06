<?php

namespace AppBundle\Tests\Entity;

use ShortenerBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_Testcase
{
    public function testEquals()
    {
        $user = new User();
        $user2 = new User();
        $user3 = new User();

        $user->setUsername('user');
        $user2->setUsername('user');
        $user3->setUsername('user 3');

        $equals = $user->equals($user2);
        $this->assertEquals(true, $equals);

        $equals = $user->equals($user3);
        $this->assertEquals(false, $equals);
    }

    public function testSerialize()
    {
        $user = new User();
        $user->setUsername('John Smith');
        $user->setEmail('john.smith@mail.com');
        $user->setPassword('strong_password');
        $user->setSalt('table_salt');
        $serializedUser = $user->serialize();
        $this->assertEquals('["John Smith","john.smith@mail.com","strong_password","table_salt",{},null]', $serializedUser);
    }

    public function testUnserialize()
    {
        $serializedUser = '["John Smith","john.smith@mail.com","strong_password","table_salt",{},null]';
        $user = new User();
        $user->unserialize($serializedUser);
        $this->assertEquals('John Smith', $user->getUsername());
        $this->assertEquals('john.smith@mail.com', $user->getEmail());
        $this->assertEquals('strong_password', $user->getPassword());
        $this->assertEquals('table_salt', $user->getSalt());
    }
}