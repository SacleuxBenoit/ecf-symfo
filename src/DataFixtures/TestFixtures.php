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

class TestFixtures extends Fixture
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
        $this->loadEmprunteurs($manager);
        $this->loadLivres($manager);
    }

    public function loadEmprunteurs(ObjectManager $manager): void
    {
        $dataEmprunteurs = [
            [
                'email' => 'foo.foo@example.com',
                'password' => '123',
                'roles' => ['ROLE_EMPRUNTEUR'],
                'nom' => 'Foo',
                'prenom' => 'Foo',
                'tel' => '123456789',
                'actif' => true,
            ],
            [
                'email' => 'bar.bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_EMPRUNTEUR'],
                'nom' => 'Bar',
                'prenom' => 'Bar',
                'tel' => '123456789',
                'actif' => false,
            ],
            [
                'email' => 'baz.baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_EMPRUNTEUR'],
                'nom' => 'Baz',
                'prenom' => 'Baz',
                'tel' => '123456789',
                'actif' => true,
            ],
        ];

        foreach($dataEmprunteurs as $dataEmprunteur){
            $user = new User();
            $user->setEmail($dataEmprunteur['email']);
            $password = $this->hasher->hashPassword($user, $dataEmprunteur['password']);
            $user->setPassword($password);
            $user->setRoles($dataEmprunteur['roles']);

            $manager->persist($user);

            $emprunteur = new Emprunteur();
            $emprunteur->setUser($user);
            $emprunteur->setNom($dataEmprunteur['nom']);
            $emprunteur->setPrenom($dataEmprunteur['prenom']);
            $emprunteur->setTel($dataEmprunteur['tel']);
            $emprunteur->setActif($dataEmprunteur['actif']);

            $manager->persist($emprunteur);
        }

        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setEmail($this->faker->safeEmail());
            $password = $this->hasher->hashPassword($user, $dataEmprunteur['password']);
            $user->setPassword($password);
            $user->setRoles($dataEmprunteur['roles']);

            $manager->persist($user);

            $emprunteur = new Emprunteur();
            $emprunteur->setUser($user);
            $emprunteur->setNom($this->faker->lastName());
            $emprunteur->setPrenom($this->faker->firstName());
            $emprunteur->setTel($this->faker->phoneNumber());
            $emprunteur->setActif(true);

            $manager->persist($emprunteur);
        }
        $manager->flush();
    }

    public function loadLivres(ObjectManager $manager): void{
        $repository = $this->doctrine->getRepository(Auteur::class);
        $auteurs = $repository->findAll();

        $repository = $this->doctrine->getRepository(Genre::class);
        $genres = $repository->findAll();

        for ($i=0; $i < 10 ; $i++) { 
            $auteur = $this->faker->randomElement($auteurs);

            $count = random_int(1,3);
            $livreGenres = $this->faker->randomElements($genres, $count);

            $livre = new Livre();
            $livre->setTitre($this->faker->words(3 , true));
                $livre->setAnneeEdition($this->faker->year());
                $livre->setNombrePages($this->faker->randomNumber(3, true));
                $livre->setCodeIsbn($this->faker->isbn13());

            $livre->setAuteur($auteur);

            foreach($livreGenres as $genre){
                $livre->addGenre($genre);
            }

            $manager->persist($livre);
        }

        $manager->flush();
    }
}
