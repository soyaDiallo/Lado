<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Cheque;
use App\Form\BeneficiaireType;
use App\Form\ChequeType;
use App\Repository\AgenceRepository;
use App\Repository\BanqueRepository;
use App\Repository\BeneficiaireRepository;
use App\Repository\ChequeRepository;
use App\Repository\ChequierRepository;
use App\Repository\CompteBancaireRepository;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/publication")
 * @Security("is_granted('ROLE_USER')")
 */
class PublicationController extends AbstractController
{
    /**
     * @Route("/nouvelle", name="publication_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        BanqueRepository $banqueRepository,
        AgenceRepository $agenceRepository,

        CompteBancaireRepository $compteBancaireRepository,
        ChequierRepository $chequierRepository,
        ChequeRepository $chequeRepository
    ): Response {
        $banques = $banqueRepository->findAll();
        $banquesAgences = [];

        $cheque = new Cheque();
        $form = $this->createForm(ChequeType::class, $cheque);
        $form->handleRequest($request);

        foreach ($banques as $key => $value) {
            $banquesAgences[$key][0] = $value;
            $banquesAgences[$key][1] = $agenceRepository->findBy(['banque' => $value]);
        }

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($beneficiaire);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('beneficiaire_index');
        // }

        return $this->render('publication/new.html.twig', [
            'banquesAgences' => $banquesAgences,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/valide/{id}", name="publication_valid", methods={"GET","POST"})
     */
    public function launch_publication(int $id = 0)
    {
    }

    /**
     * @Route("/refuse/{id}", name="publication_refuse", methods={"GET","POST"})
     */
    public function refuse_publication(int $id = 0)
    {
    }

    /**
     * @Route("/refuse/admin/{id}", name="publication_admin_refuse", methods={"GET","POST"})
     */
    public function refuse_admin_publication(int $id = 0)
    {
    }
}
