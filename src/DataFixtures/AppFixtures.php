<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Lieu;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * Faker Generator
     *
     * @var Generator
     */
    private Generator $faker;

    
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // creating users varialbe into an empty array
        $users = [];
        // creating 10 users
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            // using faker to generate a name
            $user->setFullName($this->faker->name())
            // mt_rand generate a random value (between o or 1 in this example) if = 1 then faker generate a nickname esle null
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null )
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPassword('password');
            $users[] = $user;
            $manager->persist($user);
        }
        // creating users varialbe into an empty array
        $lieux = [];
        for ($i=0; $i < 50 ; $i++) { 
            $lieu = new Lieu();
            $lieu->setNomLieu($this->faker->word());
            $lieu->setNumeroTelLieu($this->faker->randomNumber(5, true));
            $lieu->setEmailLieu($this->faker->email());
            $lieu->setUrlLieu($this->faker->domainName());
            // a user will be assigned to a random user
            $lieu->setUser($users[mt_rand(0, count($users) -1)]);
            $lieu->setDescription($this->faker->sentence(mt_rand(10, 30)));

            $lieux[] = $lieu;
            $manager->persist($lieu);
        }

        // Likes
        // look for all 'lieux'
      
        foreach ($lieux as $lieu) {
            // giving bewteen 0 and 4 marks 
            for ($i=0; $i < mt_rand(0, 4) ; $i++) { 
                $mark = new Mark();
                // mark will be bewteen 1 and 5
                $mark->setMark(mt_rand(1, 5))
                // by random users
                    ->setUser($users[mt_rand(0, count($users) -1)])
                // to lieu
                    ->setLieu($lieu);

                $manager->persist($mark);
            }
        }
        $manager->flush();
    }
}
