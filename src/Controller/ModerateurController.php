<?php

namespace App\Controller;

use App\Entity\Moderateur;
use App\Form\ModerateurType;
use App\Repository\ChequeRepository;
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
    public function index(
        ChequeRepository $chequeRepository
    ): Response {
        if ($this->getUser()->getRoles()[0] == "ROLE_MODERATEUR")
            return $this->render('moderateur/index.html.twig', [
                'cheques' => $chequeRepository->findBy(['dateRefus' => null], ['dateDeclaration' => "DESC"]),
                'active' => "no-valid"
            ]);
    }

    /**
     * @Route("/valide", name="moderateur_index_valid", methods={"GET"})
     */
    public function index_valid(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('moderateur/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.datePublication IS NOT NULL')->getQuery()->getResult(),
            'active' => "valid"
        ]);
    }

    /**
     * @Route("/refuse", name="moderateur_index_refus", methods={"GET"})
     */
    public function index_refus(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('moderateur/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.dateRefus IS NOT NULL')->getQuery()->getResult(),
            'active' => "refus"
        ]);
    }
}
