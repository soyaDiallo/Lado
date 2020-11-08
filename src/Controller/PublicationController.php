<?php

namespace App\Controller;

use App\Entity\CompteBancaire;
use App\Entity\Beneficiaire;
use App\Entity\Cheque;
use App\Entity\PiecesJointes;
use App\Entity\Chequier;
use App\Form\BeneficiaireType;
use App\Form\ChequeType;
use App\Repository\AgenceRepository;
use App\Repository\BanqueRepository;
use App\Repository\BeneficiaireRepository;
use App\Repository\ChequeRepository;
use App\Repository\ChequierRepository;
use App\Repository\CompteBancaireRepository;
use App\Repository\DeclarationTrouveRepository;
use App\Repository\PiecesJointesRepository;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        SluggerInterface $slugger,
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

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //dd($request->files->get('myfile'));

            $compte = new CompteBancaire();
            $agence = $agenceRepository->find($request->request->get('agence'));
            $compte->setAgence($agence);
            $compte->setNumCompte($request->request->get('numeroCompte'));
            $entityManager->persist($compte);
            $entityManager->flush();

            $chequier = new Chequier();
            $chequier->setCompteBancaire($compte);
            $chequier->setNumSerie($request->request->get('cheque')['chequier']['numSerie']);
            $chequier->setDateRemise($form->get('chequier')->getData()->getDateRemise());
            $entityManager->persist($chequier);
            $entityManager->flush();

            $cheque->setDateDeclaration(new \DateTime());
            $cheque->setChequier($chequier);
            $cheque->setNum(strtoupper($cheque->getNum()));
            $cheque->setBeneficiaire($this->getUser());
            $entityManager->persist($cheque);
            $entityManager->flush();

            $pieces = $request->files->get('myfile');
            $mesPieces = [];
            foreach ($pieces as $key => $piece) {
                $mesPieces[$key] = new PiecesJointes();
                if ($piece) {
                    $originalFilename = pathinfo($piece->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $piece->guessExtension();
                    try {
                        $piece->move(
                            $this->getParameter('pieces_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $mesPieces[$key]->setNomFichier("Fichier-" . $key . "-" . $this->getUser()->getNom());
                    $mesPieces[$key]->setUrlFichier($newFilename);
                    $mesPieces[$key]->setCheque($cheque);
                }
                $entityManager->persist($mesPieces[$key]);
                $entityManager->flush();
            }

            return $this->redirectToRoute('beneficiaire_index');
        }

        return $this->render('publication/new.html.twig', [
            'banquesAgences' => $banquesAgences,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/detail/{id}", name="publication_show", methods={"GET","POST"})
     */
    public function show(
        Request $request,
        ChequeRepository $chequeRepository,
        DeclarationTrouveRepository $declarationTrouveRepository,
        PiecesJointesRepository $piecesJointesRepository,
        int $id = 0
    ): Response {
        $cheque = $chequeRepository->find($id);
        $form = $this->createForm(ChequeType::class, $cheque);
        $form->handleRequest($request);

        return $this->render('publication/show.html.twig', [
            'form' => $form->createView(),
            'cheque' => $cheque,
            'declaration' => $declarationTrouveRepository->findBy(['cheque' => $cheque]),
            'piecesJointes' => $piecesJointesRepository->findBy(['cheque' => $cheque])
        ]);
    }

    /**
     * @Route("/valide/{id}", name="publication_valid", methods={"GET","POST"})
     */
    public function launch_publication(ChequeRepository $chequeRepository, int $id = 0)
    {
        $cheque = $chequeRepository->find($id);
        $cheque->setDatePublication(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cheque);
        $entityManager->flush();

        if ($this->getUser()->getRoles()[0] == "ROLE_ADMINISTRATEUR")
            return $this->redirectToRoute('administrateur_index');

        return $this->redirectToRoute('moderateur_index');
    }

    /**
     * @Route("/refuse/{id}", name="publication_refuse", methods={"GET","POST"})
     */
    public function refuse_publication(ChequeRepository $chequeRepository, int $id = 0)
    {
        $cheque = $chequeRepository->find($id);
        $cheque->setDateRefus(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cheque);
        $entityManager->flush();

        if ($this->getUser()->getRoles()[0] == "ROLE_ADMINISTRATEUR")
            return $this->redirectToRoute('administrateur_index');

        return $this->redirectToRoute('moderateur_index');
    }

    /**
     * @Route("/refuse/admin/{id}", name="publication_admin_refuse", methods={"GET","POST"})
     */
    public function refuse_admin_publication(ChequeRepository $chequeRepository, int $id = 0)
    {
        $cheque = $chequeRepository->find($id);
        $cheque->setDateRefusAdmin(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cheque);
        $entityManager->flush();

        if ($this->getUser()->getRoles()[0] == "ROLE_ADMINISTRATEUR")
            return $this->redirectToRoute('administrateur_index');

        return $this->redirectToRoute('moderateur_index');
    }
}
