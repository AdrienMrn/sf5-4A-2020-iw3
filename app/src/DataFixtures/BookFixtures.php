<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();

        for ($i=0; $i<200; $i++) {
            $object = (new Book())
                ->setName($faker->name)
                ->setDescription($faker->paragraph)
                ->setAveragePrice($faker->numberBetween(5, 66))
                ->setPublicationDate($faker->dateTimeBetween('-150 years', 'now'))
                ->setUpdatedAt($faker->dateTime)
                //->setAuthor($this->getReference(UserFixtures::USER_ADMIN))
                ->setAuthor($users[array_rand($users)])
            ;

            shuffle($tags);
            for ($y=0; $y<$faker->numberBetween(1, 5); $y++) {
                $object->addTag($tags[$y]);
            }

            $manager->persist($object);

            if ($i % 50 === 1) {
                $manager->flush();
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class,
            UserFixtures::class
        ];
    }
}
