<?php

namespace App\Controller;

use App\Repository\ChequeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/administrateur")
 * @Security("is_granted('ROLE_ADMINISTRATEUR')")
 */
class AdministrateurController extends AbstractController
{
    /**
     * @Route("/", name="administrateur_index", methods={"GET"})
     */
    public function index(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('administrateur/index.html.twig', [
            'cheques' => $chequeRepository->findBy(['dateRefus' => null], ['dateDeclaration' => "DESC"]),
            'active' => "no-valid"
        ]);
    }

    /**
     * @Route("/valide", name="administrateur_index_valid", methods={"GET"})
     */
    public function index_valid(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('administrateur/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.datePublication IS NOT NULL')->getQuery()->getResult(),
            'active' => "valid"
        ]);
    }

    /**
     * @Route("/refuse", name="administrateur_index_refus", methods={"GET"})
     */
    public function index_refus(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('administrateur/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.dateRefus IS NOT NULL')->getQuery()->getResult(),
            'active' => "refus"
        ]);
    }

    /**
     * @Route("/refuse/admin", name="administrateur_index_refus_admin", methods={"GET"})
     */
    public function index_refus_admin(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('administrateur/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.dateRefusAdmin IS NOT NULL')->getQuery()->getResult(),
            'active' => "refus-admin"
        ]);
    }
}
