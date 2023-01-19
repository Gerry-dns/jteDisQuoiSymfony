<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Lieu;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Length;

use function PHPSTORM_META\map;

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

        //setting admin
        $admin = new User();
        $admin->setFullName('Administrateur')
            ->setPseudo(null)
            ->setEmail('admin@jtedisquoi.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');
        $user[] = $admin;
        $manager->persist($admin);
        // creating 10 users
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            // using faker to generate a name
            $user->setFullName($this->faker->name())
            // mt_rand generate a random value (between o or 1 in this example) if = 1 then faker generate a nickname esle null
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null )
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');
            $users[] = $user;
            $manager->persist($user);
        }
        // creating users varialbe into an empty array
        $lieux = [];
        $typeLieu = ['Bar', 'Restaurant', 'Musée', 'Salle de concert', 'Hôtel', 
        'Jardins', 'Parc', 'Association' ];
        $nomLieu = ['Le Palais Clair', 'Le Château de la Plage', 'La Fable de Bronze', 'Le Mélange du Quai',
        'La Légende', 'Le Colibri','Le Mur','Séduction','la Niche','Lueur des Étoiles',
        "Le Nuage d'Orange","La Table Chaude","Le Lieu de Sarriette","Le Nuage Privé",
        "Le Balcon de Cuisson","Le GastroGnome", "Le Calme", "Le Saphir", "L'Amusement", "Le Lis",
        "La Capture Rose", "La Cabane Italienne", "La Vallée Ovale", "Le Morceau du Canal",
        "Le Hall Solaire", "Le Dépôt", "Élémentaire", "La Tulipe", "La Gemme", "L'Échange de Cannelle",
        "Le Balcon de la Plage", "Le Pétale Violet", "L'Usine Argentée", "Le Boulevard Lunaire",
        "La Perle rare", "Bambino", "L'Île", "Lueur des Songes", "La Caverne", "Le Lis de Paume",
        "La Saveur Thaïlandaise","Le Piment Doré","Le Moulin d'Hiver","Le Sanglier Silencieux",
        "La Chance","Le Coquin","le Marmonnement","Piccolo", "Révélations", 'Chez Anouk'];

        for ($i=0; $i < 50 ; $i++) { 
            $lieu = new Lieu();
            $lieu->setTypeLieu($typeLieu[mt_rand(0, count($typeLieu) -1)]);
            $lieu->setNumeroTelLieu($this->faker->randomNumber(9, true));
            $lieu->setNomLieu($nomLieu[$i]);
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
