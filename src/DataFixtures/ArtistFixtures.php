<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArtistFixtures extends Fixture
{
    private $genders = ['male', 'female'];

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 1; $i <= 5; $i++) {
            $artist = new Artist();
            $gender = $faker->randomElement($this->genders);

            $artist->setFirstname($faker->firstName($gender))
                ->setLastname($faker->lastName)
                ->setDnais(new \DateTimeImmutable())
                ->setBiblio($faker->paragraph(5, true));
            $gender = $gender == 'male' ? 'm' : 'f';
            $artist->setImage($i . $gender . '.jpg');
            $artist->setIsDisabled(false);

            $manager->persist($artist);
        }

        $manager->flush();
    }
}
