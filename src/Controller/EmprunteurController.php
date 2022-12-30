<?php

namespace App\Controller;

use App\Entity\Emprunteur;
use App\Form\EmprunteurType;
use App\Repository\EmprunteurRepository;
use App\Repository\EmpruntRepository;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException; 

#[Route('/emprunteur')]
class EmprunteurController extends AbstractController
{
    #[Route('/', name: 'app_emprunteur_index', methods: ['GET'])]
    public function index(EmprunteurRepository $emprunteurRepository): Response
    {
        return $this->render('emprunteur/index.html.twig', [
            'emprunteurs' => $emprunteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emprunteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmprunteurRepository $emprunteurRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $emprunteur = new Emprunteur();
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunteurRepository->save($emprunteur, true);

            return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunteur/new.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunteur_show', methods: ['GET'])]
    public function show(Emprunteur $emprunteur, EmpruntRepository $emprunteurRepository): Response
    {
        if($this->isGranted(["ROLE_EMPRUNTEUR"])){
            // l'utilisateur est un emprunteur
            $user = $this->getUser();
            $userEmprunteur = $emprunteurRepository->findByUser($user);

            if($emprunteur->getId() != $userEmprunteur->getId()){
                throw new NotFoundHttpException();
            }
        }
        return $this->render('emprunteur/show.html.twig', [
            'emprunteur' => $emprunteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunteur $emprunteur, EmprunteurRepository $emprunteurRepository): Response
    {
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunteurRepository->save($emprunteur, true);

            return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunteur/edit.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunteur_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunteur $emprunteur, EmprunteurRepository $emprunteurRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$emprunteur->getId(), $request->request->get('_token'))) {
            $emprunteurRepository->remove($emprunteur, true);
        }

        return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
