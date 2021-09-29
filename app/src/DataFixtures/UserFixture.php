<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    private $faker;

    public function __construct() 
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; $i++) 
        {
            $manager->persist($this->getUser());
        }
        $manager->flush();
    }
    private function getUser() 
    {
        $user = new User();
        $user->setName($this->faker->name());
        $user->setEmail($this->faker->email());
        $roles[] = 'ROLE_USER';
        $user->setRole($roles);
        return $user;
    }
}
