<?php
declare(strict_types=1);

namespace App\User\Web\Controller;

use App\User\Application\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/test', name: 'app_user_web_controller_user')]
    public function index(UserService $user): Response
    {
        $user->addUser('test@test.pl', 'test', [2]);

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/addUser', name: 'app_add_user')]
    public function addUser(Request $request): Response
    {

        return $this->render('user/addUser.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
