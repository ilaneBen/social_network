<?php

// src/Controller/UserController.php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/api/users/search', name: 'user_search', methods: ['GET'])]
    public function searchUsers(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('q', '');
         $term = $request->query->get('term');
         $userId = $request->query->get('userId'); 
        $users = $userRepository->searchUsersByTerm($term, $userId);
       

        $result = [];
        foreach ($users as $user) {
            $result[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'profilePicture' => $user->getProfilePicture(),
            ];
        }

        return $this->json($result);
    }

    #[Route('/api/users/{id}', name: 'get_user_profile', methods: ['GET'])]
    public function getUserProfile(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }
        $followersCount = $user->getFollowers()->count();
        $followingCount = $user->getFollowing()->count();
        // Example: Return user data
        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'city' => $user->getCity(),
            'country' => $user->getCountry(),
            'profilePicture' => $user->getProfilePicture(),
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            // Add more fields as needed
        ]);
    }
}
