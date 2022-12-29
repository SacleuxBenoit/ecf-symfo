<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $doctrine;
    private $faker;
    private $hasher;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;

    }
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadAuteurs($manager);
        $this->loadGenres($manager);
    }

    public function loadUsers(ObjectManager $manager): void{
        $dateUsers = [
            [
                'email' => 'admin@example.com',
                'password' => '123',
                'roles' => ['ROLE_ADMIN'],
            ],
        ];

        foreach ($dateUsers as $dataUser){
            $user = new User();
            $user->setEmail($dataUser['email']);
            $password = $this->hasher->hashPassword($user, $dataUser['password']);
            $user->setPassword($password);
            $user->setRoles($dataUser['roles']);

            $manager->persist($user);
        }
        $manager->flush();
    }
    public function loadAuteurs(ObjectManager $manager): void{
        $dataAuteurs = [
            [
                'nom' => 'auteur inconnu',
                'prenom' => '',
            ],
        ];

        foreach ($dataAuteurs as $dataAuteur){
            $auteur = new Auteur();
            $auteur->setNom($dataAuteur['nom']);
            $auteur->setPrenom($dataAuteur['prenom']);

            $manager->persist($auteur);
        }

        $manager->flush();
    }

    public function loadGenres(ObjectManager $manager): void{
        $dataGenres = [
            [
                'nom' => 'poésie',
                'description' => null
            ],
            [   'nom' => 'nouvelle',
                'description' => null
            ],
            [   'nom' => 'roman historique',
                'description' => null
            ],
            [   'nom' => "roman d'amour",
                'description' => null
            ],
            [   'nom' => "roman d'aventure",
                'description' => null
            ],
            [   'nom' => 'science-fiction',
                'description' => null
            ],
            [   'nom' => 'fantasy',
                'description' => null
            ],
            [   'nom' => 'biographie',
                'description' => null
            ],
            [   'nom' => 'conte',
                'description' => null
            ],
            [   'nom' => 'témoignage',
                'description' => null
            ],
            [   'nom' => 'théâtre',
                'description' => null
            ],
            [   'nom' => 'essai',
                'description' => null
            ],
            [   'nom' => 'journal intime',
                'description' => null
            ],
        ];

        foreach ($dataGenres as $dataGenre){
            $genre = new Genre();
            $genre->setNom($dataGenre['nom']);
            $genre->setDescription($dataGenre['description']);
            $manager->persist($genre);
        }

        $manager->flush();
    }
}
