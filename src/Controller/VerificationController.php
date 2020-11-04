<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController extends AbstractController
{
    /**
     * @Route("/verification", name="verification")
     */
    public function index(): Response
    {
        $role = $this->getUser()->getRoles();
        switch ($role[0]) {
            case "ROLE_MODERATEUR":
                return $this->redirectToRoute('moderateur_index');
                break;
            case 'ROLE_BENEFICIAIRE':
                return $this->redirectToRoute('beneficiaire_index');
                break;
            case 'ROLE_ADMINISTRATEUR':
                return $this->redirectToRoute('administrateur_index');
                break;
            default:
                return $this->redirectToRoute('app_login');
                break;
        }
    }
}
