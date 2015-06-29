<?php

namespace ShortenerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
//use Company\BlogBundle\Entity\Category;
//use Company\BlogBundle\Entity\Post;
//use Company\BlogBundle\Entity\Tag;
use ShortenerBundle\Entity\User;
use ShortenerBundle\Entity\Role;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class FixtureLoader implements FixtureInterface {
    public function load(ObjectManager $manager) {
////         создание роли ROLE_ADMIN
        $role = new Role();
        $role->setName('ROLE_AUTHENTICATED_USER');

        $manager->persist($role);

//        // создание пользователя
//        $user = new User();
//        $user->setEmail('john@example.com');
//        $user->setUsername('john');
//        $user->setSalt(md5(time()));
//
//        // шифрует и устанавливает пароль для пользователя,
//        // эти настройки совпадают с конфигурационными файлами
//        $encoder = new MessageDigestPasswordEncoder('sha512', TRUE, 10);
//        $password = $encoder->encodePassword('admin', $user->getSalt());
//        $user->setPassword($password);
//
//        $user->getUserRoles()->add($role);
//
//        $manager->persist($user);
        $manager->flush();
    }
}