<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\Beneficiaire;
use App\Entity\Moderateur;
use App\Entity\User;
use App\Form\ModificationCompteType;
use App\Form\ModificationInformationType;
use App\Form\UtilisateurType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/utilisateurs")
 * @Security("is_granted('ROLE_ADMINISTRATEUR')")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(
        Request $request,
        UserRepository $userRepository
    ): Response {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $userRepository->findBy([], ['nom' => 'ASC'])
        ]);
    }

    // /**
    //  * @Route("/nouveau", name="user_new", methods={"GET", "POST"})
    //  */
    // public function new(
    //     Request $request,
    //     UserRepository $userRepository
    // ): Response {
    //     $user = new User();
    //     $form = $this->createForm(UtilisateurType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // $em = $this->getDoctrine()->getManager();
    //         // $em->persist($user);
    //         // $em->flush();
    //     }

    //     return $this->render('utilisateur/new.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/nouveau", name="user_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        //  UsersAuthenticathorAuthenticator $authenticator
        UserAuthenticator $authenticator
    ): Response {
        $user = new User();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $type = $form->get('roles')->getData();
            switch ($type[0]) {
                case "ROLE_BENEFICIAIRE":
                    $agent = new Beneficiaire();
                    $agent->setNom($user->getNom());
                    $agent->setPrenom($user->getPrenom());
                    $agent->setEmail($user->getEmail());
                    $agent->setAdresse($user->getAdresse());
                    $agent->setTel($user->getTel());
                    $agent->setPassword($user->getPassword());
                    $user = $agent;
                    $role = ['ROLE_BENEFICIAIRE'];
                    break;
                case 'ROLE_MODERATEUR':
                    $back = new Moderateur();
                    $back->setNom($user->getNom());
                    $back->setPrenom($user->getPrenom());
                    $back->setEmail($user->getEmail());
                    $back->setAdresse($user->getAdresse());
                    $back->setTel($user->getTel());
                    $back->setPassword($user->getPassword());
                    $user = $back;
                    $role = ['ROLE_BACKOFFICE'];
                    break;
                case 'ROLE_MODERATEUR':
                    $admin = new Administrateur();
                    $admin->setNom($user->getNom());
                    $admin->setPrenom($user->getPrenom());
                    $admin->setEmail($user->getEmail());
                    $admin->setAdresse($user->getAdresse());
                    $admin->setTel($user->getTel());
                    $admin->setPassword($user->getPassword());
                    $user = $admin;
                    $role = ['ROLE_ADMINISTRATEUR'];
                    break;

                default:
                    return $this->redirectToRoute('app_login');
                    break;
            }

            $user->setRoles($role);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('utilisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
