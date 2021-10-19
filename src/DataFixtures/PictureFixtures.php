<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Picture;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    private $width = [118.5, 105.7, 79.3, 79.3, 137.1, 105.8, 94.8, 103.3, 137.1, 137.1, 106.6, 80, 119, 80, 80, 216.7, 114.5, 137.1, 99, 99, 91.2, 88.6, 115.3, 126.7, 118.9, 129.5];
    private $height = [177.8, 79.4, 105.8, 105.8, 102.8, 70.5, 142.2, 154.9, 102.8, 102.8, 80, 106.6, 79.3, 106.6, 106.6, 144.5, 171.7, 91.4, 148.5, 148.5, 115.5, 122.1, 83.6, 88.1, 81.6, 86.3];

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slugify = New Slugify();
        $categories = $manager->getRepository(Category::class)->findAll();
        $artist = $manager->getRepository(Artist::class)->findAll();

        $nbrWidth = count($this->width);
        $nbrHeigth = count($this->height);
        $nbrCat = count($categories);
        $nbrArtist = count($artist);

        for($i = 1; $i<= 26; $i++) {
            $picture = new Picture();
            $picture->setArtist($artist[$faker->numberBetween(0, $nbrArtist -1)])
                ->setCategory($categories[$faker->numberBetween(0, $nbrCat -1)])
                ->setTitle($faker->sentence(2, true))
                ->setWidth($this->width[$faker->numberBetween(0, $nbrWidth -1)])
                ->setHeight($this->height[$faker->numberBetween(0, $nbrHeigth -1)])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setImage($i.'.jpg')
                ->setSmalldescript($faker->paragraph(2, true))
                ->setFulldescript($faker->paragraph(8, true))
                ->setSlug($slugify->slugify($picture->getTitle()))
                ->setIsPulished($faker->boolean(90));
            $manager->persist($picture);

        }

        $manager->flush();
    }


    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @psalm-return array<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [
            CategoryFixtures::class,
            ArtistFixtures::class
        ];
    }
}
