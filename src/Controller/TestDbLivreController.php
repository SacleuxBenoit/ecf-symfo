<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestDbLivreController extends AbstractController
{
    #[Route('/test/db/livre', name: 'app_test_db_livre')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $livreRepository = $doctrine->getRepository(Livre::class);
        $auteurRepository = $doctrine->getRepository(Auteur::class);

        $livres = $livreRepository->findByTitle('lorem');
        dump($livres);

        $auteur = $auteurRepository->find(1);
        $livres = $livreRepository->findByAuteur($auteur);
        dump($auteur);

        $livres = $livreRepository->findByNomGenre('roman');

        foreach($livres as $livre){
            dump($livre->getTitre());
            foreach($livre->getGenres() as $genre){
                dump($genre->getNom());
            }
            dump($livre->getGenres());
        }
        dump($livres);
        exit();
    }
}
