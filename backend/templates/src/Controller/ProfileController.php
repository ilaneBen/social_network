<?php
namespace App\Controller;

use App\Entity\User; // Assurez-vous d'importer correctement votre entité User
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur courant

        if (!$user) {
            // Gestion de l'utilisateur non connecté
            // Vous pouvez rediriger vers une page de connexion par exemple
            return $this->redirectToRoute('app_login');
        }

        // Accès aux méthodes getters de l'entité User
        $userId = $user->getId();
        $UserPost = $user -> getPosts();
        $userEmail = $user->getEmail();
        $userRoles = $user->getRoles();
        // ...

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'userId' => $userId,
            'userEmail' => $userEmail,
            'userRoles' => $userRoles,
            'posts' => $UserPost
        ]);
    }
}
