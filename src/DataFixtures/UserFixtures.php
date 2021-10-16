<?php

namespace App\DataFixtures;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $lastName = ['mathus', 'doe', 'blache', 'moemars', 'mayard', 'master'];
    private $firstName = ['patrick', 'john', 'sébastien', 'corentin', 'amaury', 'kyllian'];
    private $level = [['ROLE_ADMIN'],['ROLE_ADMIN'],['ROLE_ADMIN'],['ROLE_USER'], ['ROLE_USER'], ['ROLE_USER']];
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $slug = new Slugify();

        $nbrFirst = count($this->firstName);
        $nbrLast = count($this->lastName);
        $nbrLevel = count($this->level);

        for($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setFirstname($this->firstName[$faker->numberBetween(0, $nbrFirst -1)])
                ->setLastname($this->lastName[$faker->numberBetween(0, $nbrLast -1)])
                ->setEmail($slug->slugify(($user->getFirstname()).'.'. $slug->slugify($user->getLastname())).'@gmail.com')
                ->setPseudo($slug->slugify(substr($user->getFirstname(),0,3)) .'.'. $slug->slugify(substr($user->getLastname(),0,3)))
                ->setImage($i.'.jpg')
                ->setPassword($this->hasher->hashPassword($user,'password'))
                ->setRoles($this->level[$faker->numberBetween(0, $nbrLevel -1)]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
