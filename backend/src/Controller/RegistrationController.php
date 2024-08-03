<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register', methods: ['GET'])]
    public function registerForm(): Response
    {
        return $this->render('registration/register.html.twig');
    }

    #[Route('/register/create', name: 'app_register_create', methods: ['POST'])]
public function registerCreate(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
{
    // Récupération des données envoyées par le formulaire
    $username = $request->request->get('username');
    $email = $request->request->get('email');
    $plainPassword = $request->request->get('plainPassword');
    $city = $request->request->get('city');
    $country = $request->request->get('country');

    // Exemple de traitement spécial pour le fichier profilePicture
    $profilePictureFile = $request->files->get('profilePicture');
    $profilePicturePath = null;

    if ($profilePictureFile instanceof UploadedFile) {
        // Générez un nom de fichier unique
        $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();

        // Vérifiez et créez le répertoire si nécessaire
        $uploadDir = $this->getParameter('profile_picture_directory');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Déplacez le fichier dans le répertoire souhaité (par exemple, public/uploads/profile_picture)
        try {
            $profilePictureFile->move(
                $uploadDir,
                $newFilename
            );
            // Stockez le chemin relatif dans la base de données
            $profilePicturePath = 'uploads/profile_picture/' . $newFilename;
            // Log the path to verify
            error_log('Profile picture path: ' . $profilePicturePath);
        } catch (FileException $e) {
            // Gestion de l'erreur si nécessaire
            return $this->json(['message' => 'Failed to upload profile picture', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Création d'une nouvelle instance d'utilisateur
    $user = new User();
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setCity($city);
    $user->setCountry($country);
    $user->setProfilePicture($profilePicturePath); // Enregistrez le chemin relatif dans la base de données

    // Hashage du mot de passe
    $encodedPassword = $passwordEncoder->hashPassword($user, $plainPassword);
    $user->setPassword($encodedPassword);

    // Persiste l'utilisateur en base de données
    $entityManager->persist($user);
    $entityManager->flush();

    // Retournez une réponse JSON par exemple
    return $this->json(['message' => 'User registered successfully'], Response::HTTP_CREATED);
}
}