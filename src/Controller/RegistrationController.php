<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Moderateur;
use App\Entity\Administrateur;
use App\Entity\Beneficiaire;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
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
                case "ROLE_MODERATEUR":
                    $moderateur = new Moderateur();
                    $moderateur->setNom($user->getNom());
                    $moderateur->setPrenom($user->getPrenom());
                    $moderateur->setEmail($user->getEmail());
                    $moderateur->setTel($user->getTel());
                    $moderateur->setAdresse($user->getAdresse());
                    $moderateur->setPassword($user->getPassword());
                    $user = $moderateur;
                    $role = ['ROLE_MODERATEUR'];
                    break;
                case 'ROLE_BENEFICIAIRE':
                    $beneficiaire = new Beneficiaire();
                    $beneficiaire->setNom($user->getNom());
                    $beneficiaire->setPrenom($user->getPrenom());
                    $beneficiaire->setEmail($user->getEmail());
                    $beneficiaire->setTel($user->getTel());
                    $beneficiaire->setAdresse($user->getAdresse());
                    $beneficiaire->setPassword($user->getPassword());
                    $user = $beneficiaire;
                    $role = ['ROLE_BENEFICIAIRE'];
                    break;
                case 'ROLE_ADMINISTRATEUR':
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
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
