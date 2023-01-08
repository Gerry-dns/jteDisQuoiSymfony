<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use Faker\Factory;
use App\Entity\Lieu;
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
        for ($i=0; $i < 50 ; $i++) { 
            $lieu = new Lieu();
            $lieu->setNomLieu($this->faker->word());
            $lieu->setNumeroTelLieu($this->faker->randomNumber(5, true));
            $lieu->setEmailLieu($this->faker->email());
            $lieu->setUrlLieu($this->faker->domainName());

            $manager->persist($lieu);
        }

        for ($i=0; $i < 20; $i++) { 
            $adresse = new Adresse;
            $adresse->setNumRue($this->faker->numberBetween(0, 400));
            $adresse->setNomRue($this->faker->word());

            $manager->persist($adresse);

        
        $manager->flush();
        }
    }
}
