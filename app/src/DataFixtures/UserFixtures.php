<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const USER_ADMIN = "user-admin";

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $object = (new User())
            ->setEmail('dev@technique')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('test');
        $manager->persist($object);
        $this->addReference(self::USER_ADMIN, $object);

        for ($i=0; $i<20; $i++) {
            $object = (new User())
                ->setEmail($faker->email)
                ->setRoles([])
                ->setPassword('test');
            $manager->persist($object);
        }

        $manager->flush();
    }
}
