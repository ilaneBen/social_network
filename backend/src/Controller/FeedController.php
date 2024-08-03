<?php 
// src/Controller/Api/FeedController.php

namespace App\Controller\Api;

use App\Repository\PostRepository;
use App\Repository\FollowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/feed')]
class FeedController extends AbstractController
{
    #[Route('/', name: 'api_feed', methods: ['GET'])]
    public function index(PostRepository $postRepository, FollowRepository $followRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Récupérer les utilisateurs suivis par l'utilisateur connecté
        $followings = $followRepository->findBy(['follower' => $user]);

        $followingIds = array_map(fn($follow) => $follow->getFollowing()->getId(), $followings);

        // Récupérer les publications des utilisateurs suivis
        $posts = $postRepository->findBy(['author' => $followingIds], ['createdAt' => 'DESC']);

        // Sérialisation en JSON en utilisant le groupe "post"
        $json = $serializer->serialize($posts, 'json', ['groups' => 'post']);

        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }
}
