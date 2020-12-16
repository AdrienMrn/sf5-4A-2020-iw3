<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /** @var string PWD = test (encoded password) */
    private const PWD = '$argon2id$v=19$m=65536,t=4,p=1$hx4D9VdVvH7t5J1skUMETQ$B8XT17eBayO7+r4pIaYGDCCq0YWXEZU1jX2HvM7PyMw';

    public const USER_ADMIN = "user-admin";

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $object = (new User())
            ->setEmail('dev@technique')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword(self::PWD);
        $manager->persist($object);
        $this->addReference(self::USER_ADMIN, $object);

        for ($i=0; $i<20; $i++) {
            $object = (new User())
                ->setEmail($faker->email)
                ->setRoles([])
                ->setPassword(self::PWD);
            $manager->persist($object);
        }

        $manager->flush();
    }
}
