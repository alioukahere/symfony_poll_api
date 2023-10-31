<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function login(#[CurrentUser()] ?User $user): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Authentication failed',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'message' => 'Authentication successful',
        ]);
    }

    #[Route('/api/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): never
    {
        throw new LogicException('Don\'t forget to activate logout in security.yaml');
    }
}
