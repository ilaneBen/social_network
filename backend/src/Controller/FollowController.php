<?php
// src/Controller/FollowController.php

namespace App\Controller;

use App\Entity\Follow;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FollowRepository;

class FollowController extends AbstractController
{
    #[Route('/api/follow', name: 'follow_user', methods: ['POST'])]
    public function followUser(Request $request, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Ajouter des logs pour vérifier le contenu des données reçues
        error_log(print_r($data, true));

        if (!isset($data['followerId']) || !isset($data['followingId'])) {
            return new JsonResponse(['message' => 'Invalid data: missing followerId or followingId'], 400);
        }

        $followerId = $data['followerId'];
        $followingId = $data['followingId'];
       
        // // Logs pour vérifier les valeurs reçues
        error_log("Follower ID: " . $followerId);
        error_log("Following ID: " . $followingId);

        $follower = $userRepository->find($followerId);
        $following = $userRepository->find($followingId);

        if (!$follower || !$following) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }
        if ($followerId === $followingId) {
            return new JsonResponse(['error' => 'vous pouvez pas vous suivre vous même !'], 400);
        }else{
        $follow = new Follow();
        $follow->setFollower($follower);
        $follow->setFollowing($following);

        $em->persist($follow);
        $em->flush();

        return new JsonResponse(['message' => 'Followed successfully']);
        }
    }

    #[Route('/api/unfollow', name: 'unfollow_user', methods: ['POST'])]
    public function unfollowUser(Request $request, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Ajouter des logs pour vérifier le contenu des données reçues
        error_log(print_r($data, true));

        if (!isset($data['followerId']) || !isset($data['followingId'])) {
            return new JsonResponse(['message' => 'Invalid data: missing followerId or followingId'], 400);
        }

        $followerId = $data['followerId'];
        $followingId = $data['followingId'];

        // Logs pour vérifier les valeurs reçues
        error_log("Follower ID: " . $followerId);
        error_log("Following ID: " . $followingId);

        $follower = $userRepository->find($followerId);
        $following = $userRepository->find($followingId);

        if (!$follower || !$following) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }

        $follow = $em->getRepository(Follow::class)->findOneBy(['follower' => $follower, 'following' => $following]);

        if ($follow) {
            $em->remove($follow);
            $em->flush();

            return new JsonResponse(['message' => 'Unfollowed successfully']);
        }

        return new JsonResponse(['message' => 'Follow relation not found'], 404);
    }

    
    #[Route('/api/follows/{followerId}/followers', name: 'get_followers', methods: ['GET'])]
    public function getFollowers(int $followerId, FollowRepository $followRepository): JsonResponse
    {
        $followers = $followRepository->findFollowersByUserId($followerId);
        return $this->json(['count' => count($followers)]);
    }

    #[Route('/api/follows/{followerId}/following', name: 'get_following', methods: ['GET'])]
    public function getFollowing(int $followerId, FollowRepository $followRepository): JsonResponse
    {
        $following = $followRepository->findFollowingByUserId($followerId);
        return $this->json(['count' => count($following)]);
    }
    #[Route('/api/follows/{followerId}/{followingId}', name: 'is_following', methods: ['GET'])]
    public function isFollowing(int $followerId, int $followingId, FollowRepository $followRepository): JsonResponse
    {
        $isFollowing = $followRepository->isUserFollowing($followerId, $followingId);
        return $this->json(['isFollowing' => $isFollowing]);
    }
}
