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
        $role = new Role();
        $role->setName('ROLE_AUTHENTICATED_USER');

        $manager->persist($role);

        $manager->flush();
    }
}