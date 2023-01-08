<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use Faker\Factory;
use App\Entity\Lieu;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        for ($i=0; $i < 50 ; $i++) { 
            $lieu = new Lieu();
            $lieu->setNomLieu($this->faker->word());
            $lieu->setNumeroTelLieu($this->faker->randomNumber(5, true));
            $lieu->setEmailLieu($this->faker->email());
            $lieu->setUrlLieu($this->faker->domainName());

        }
        $manager->persist($lieu);
    

        for ($i=0; $i < 20; $i++) { 
            $adresse = new Adresse;
            $adresse->setNumRue($this->faker->numberBetween(0, 400));
            $adresse->setNomRue($this->faker->word());

        }
        $manager->persist($adresse);
    
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

            $manager->persist($user);
        }

        $manager->flush();
    }
}
