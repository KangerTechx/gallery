<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $picture = $manager->getRepository(Picture::class)->findAll();
        $user = $manager->getRepository(User::class)->findAll();

        $nbrPicture = count($picture);
        $nbrUser = count($user);

        for($i = 1; $i<= 30; $i++) {
            $comment = new Comment();
            $comment->setPicture($picture[$faker->numberBetween(0, $nbrPicture -1)])
                ->setPseudo($user[$faker->numberBetween($nbrUser -1)])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setStar($faker->numberBetween(0, 5))
                ->setDescript($faker->paragraph(2, true))
                ->setTitle($faker->sentence(2, true));
            $manager->persist($comment);
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
            UserFixtures::class,
            PictureFixtures::class
        ];
    }
}
