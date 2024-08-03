<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    private $userRepository;
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/login_check', name: 'app_login_check', methods: ['POST'])]
    public function loginCheck(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['_username'] ?? null;
        $password = $data['_password'] ?? null;

        if (!$username || !$password) {
            return new JsonResponse(['message' => 'Missing username or password'], 400);
        }

        // Retrieve the user from the database
        $user = $this->userRepository->findOneBy(['email' => $username]);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        // Check if the password is valid
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid password'], 400);
        }

        // Return all user data in the response
        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'city' => $user->getCity(),
            'country' => $user->getCountry(),
            'profilePicture' => $user->getProfilePicture()
            // Include other necessary user data here
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // This method can be empty, it will be intercepted by the firewall logout
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
