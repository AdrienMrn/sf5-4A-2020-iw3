<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $colors = [];
        for ($i=0; $i<20; $i++) {
            $colors[] = $faker->safeColorName;
        }
        $colors = array_unique($colors);

        foreach ($colors as $color) {
            $object = (new Tag())
                ->setName($color);
            $manager->persist($object);
        }

        $manager->flush();
    }
}
