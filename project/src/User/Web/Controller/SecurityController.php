<?php
declare(strict_types=1);

namespace App\User\Web\Controller;

use App\User\Application\UserService;
use App\User\Domain\Model\UserVO;
use App\User\Infrastructure\Persistence\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(): JsonResponse
    {
        // will not be called because of the LoginFormAuthenticator
        return new JsonResponse(['status' => 'success']);
    }

    #[Route(path: '/current-user', name: 'app_current_user')]
    public function currentUser(#[CurrentUser] User $user = null): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'status' => 'success',
            'user' => [
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'phone' => $user->getPhone(),
                'roles' => $user->getRoles(),
            ]
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
        Request $request,
        UserService $userService,
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
