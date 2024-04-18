<?php
declare(strict_types=1);

namespace App\User\Web\Controller;

use App\User\Infrastructure\Persistence\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    #[Route('/test', name: 'app_user_web_controller_user')]
    public function index(#[CurrentUser] User $user = null): JsonResponse
    {
        //$user->addUser('test@test.pl', 'test', [2]);
        // Tylko dla zalogowanych
        if (null === $user) {
            return $this->json([
                'message' => 'unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'status' => 'Success',
            'user' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'userEmail' => $user->getEmail(),
            'userPassword' => $user->getPassword(),
        ]);
    }
}
