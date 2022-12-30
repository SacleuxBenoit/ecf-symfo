<?php

namespace App\Controller;
use App\Entity\Emprunteur;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestDbUserController extends AbstractController
{
    #[Route('/test/db/user', name: 'app_test_db_user')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $userRepository = $doctrine->getRepository(User::class);
        /** @var App\Repository\EmprunteurRepository */
        $emprunteurRepository = $doctrine->getRepository(Emprunteur::class);

        $users = $userRepository->findAll();
        dump($users);

        $user = $userRepository->find(1);
        dump($user);

        $user = $userRepository->findOneBy([
            'email' => 'foo.foo@example.com',
        ]);
        dump($user);
        
        $users = $userRepository->findByRoleEmprunteur();
        dump($users);

        $user = $userRepository->find(2);
        $emprunteur = $emprunteurRepository->findByUser($user);
        dump($emprunteur);
        exit();
    }
}
