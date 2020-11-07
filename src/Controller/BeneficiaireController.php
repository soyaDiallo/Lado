<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ChequeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Exception;
/**
 * @Route("/beneficiaire")
 * @Security("is_granted('ROLE_BENEFICIAIRE')")
 */
class BeneficiaireController extends AbstractController
{
    /**
     * @Route("/", name="beneficiaire_index", methods={"GET"})
     */
    public function index(
        ChequeRepository $chequeRepository
    ): Response {
        return $this->render('beneficiaire/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.datePublication IS NOT NULL')->getQuery()->getResult(),
        ]);
    }

    /**
     * @Route("/recherche", name="recherche", methods={"GET","POST"})
     */
    public function recherche(
        ChequeRepository $chequeRepository,
        Request $request
    ): Response {
        $num = strtoupper($request->request->get('recherche'));
        try {
            $cheque=$chequeRepository->findByNum($num);
        } catch (Exception $e) {
            $this->addFlash('error_beneficiaire', 'Le beneficiaire que vous cherchez n\'existe pas');
            return $this->redirect($request->getUri());
        }
        //dd($cheque);
        return $this->render('beneficiaire/index.html.twig', [
            'cheques' => $chequeRepository->createQueryBuilder('c')->where('c.datePublication IS NOT NULL')->getQuery()->getResult(),
        ]);
    }

}
