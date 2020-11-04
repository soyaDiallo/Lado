<?php

namespace App\Controller;

use App\Entity\Moderateur;
use App\Form\ModerateurType;
use App\Repository\ModerateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/moderateur")
 * @Security("is_granted('ROLE_MODERATEUR')")
 */
class ModerateurController extends AbstractController
{
    /**
     * @Route("/", name="moderateur_index", methods={"GET"})
     */
    public function index(ModerateurRepository $moderateurRepository): Response
    {
        return $this->render('moderateur/index.html.twig', [
            'moderateurs' => $moderateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="moderateur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $moderateur = new Moderateur();
        $form = $this->createForm(ModerateurType::class, $moderateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($moderateur);
            $entityManager->flush();

            return $this->redirectToRoute('moderateur_index');
        }

        return $this->render('moderateur/new.html.twig', [
            'moderateur' => $moderateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="moderateur_show", methods={"GET"})
     */
    public function show(Moderateur $moderateur): Response
    {
        return $this->render('moderateur/show.html.twig', [
            'moderateur' => $moderateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="moderateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Moderateur $moderateur): Response
    {
        $form = $this->createForm(ModerateurType::class, $moderateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('moderateur_index');
        }

        return $this->render('moderateur/edit.html.twig', [
            'moderateur' => $moderateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="moderateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Moderateur $moderateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moderateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($moderateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('moderateur_index');
    }
}
