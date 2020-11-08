<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ChequeRepository;
use App\Repository\DeclarationTrouveRepository;
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
        ChequeRepository $chequeRepository,
        DeclarationTrouveRepository $declarationTrouveRepository
    ): Response {
        $cheques = $chequeRepository->createQueryBuilder('c')->where('c.datePublication IS NOT NULL')->where('c.dateRefusAdmin IS NULL')->orderBy('c.dateDeclaration', 'DESC')->getQuery()->getResult();
        $chequesDeclarations = [];

        foreach ($cheques as $key => $value) {
            $chequesDeclarations[$key][0] = $value;
            $chequesDeclarations[$key][1] = $declarationTrouveRepository->findBy(['cheque' => $value]);
        }

        return $this->render('beneficiaire/index.html.twig', [
            'cheques' => $chequesDeclarations
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
            $cheque = $chequeRepository->findBy(['num' => $num], null, 1);
            if ($cheque) {
                return $this->redirectToRoute('publication_show', ['id' => $cheque[0]->getId()]);
            } else {
                $this->addFlash('error_beneficiaire', 'Le chèque que vous cherchez n\'est pas présent sur plateforme.');
                return $this->redirect($request->getUri());
            }
        } catch (Exception $e) {
            $this->addFlash('error_beneficiaire', 'Le chèque que vous cherchez n\'est pas présent sur plateforme.');
            return $this->redirect($request->getUri());
        }
    }
}
