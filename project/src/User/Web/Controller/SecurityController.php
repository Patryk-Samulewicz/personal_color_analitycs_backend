<?php
declare(strict_types=1);

namespace App\User\Web\Controller;

use App\User\Application\UserService;
use App\User\Domain\Model\UserVO;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private UserService              $userService
    )
    {
    }

    #[Route(path: '/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->login(
                $request->request->get('email', ''),
                $request->request->get('plainPassword', '')
            );
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    #[Route('/register', name: 'app_register_user')]
    public function register(
        Request            $request,
        UserService        $userService,
        ValidatorInterface $validator,
        TranslatorInterface $translator
    ): Response {
        try {
            $email = $request->request->get('email');
            $password = $request->request->get('plainPassword');
            $name = $request->request->get('name');
            $surname = $request->request->get('surname');
            $phone = $request->request->get('phone');
            $rules = $request->request->get('rules');

            $userVO = new UserVO($email, $name, $surname, $phone, $password);
            $errors = $validator->validate($userVO);

            if (count($errors) > 0) {
                $errorsMessages = [];
                foreach ($errors as $violation) {
                    $errorsMessages[$violation->getPropertyPath()] = $violation->getMessage();
                }
            }

            if (!$rules) {
                $errorsMessages['rules'] = $translator->trans('You must accept the rules');
            }

            if (!empty($errorsMessages)) {
                return new JsonResponse(
                    ['status' => 'error', 'errors' => $errorsMessages],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $userService->addUser($userVO);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['status' => 'success']);
    }
}
