<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.12.2018
 * Time: 20:01
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\SerializerService;
use AppBundle\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class ApiController extends AbstractController
{
    /**
     * @Route(
     *     "/api/user/login", name="api_login",
     *     defaults={"_format": "json"}
     * )
     */
    public function loginAction()
    {
        return new Response();
    }

    /**
     * @Route(
     *     "/api/user/{id}/edit",
     *     name="user_edit",
     *     defaults={"_format": "json"},
     * )
     *
     */
    public function editAction(User $user, Request $request, SerializerService $serializerService, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $serializerService->getSerializer()->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("user_info", ['id' => $user->getId()]);

    }

    /**
     * @Route(
     *     "/api/user/{id}/info",
     *      name="user_info",
     *     defaults={"_format": "json"}
     * )
     */
    public function infoAction(User $user, SerializerInterface $serializer)
    {
        return new Response($serializer->serialize($user, 'json', ['groups' => ['user_info']]));
    }


    /**
     * @Route(
     *     "/api/user/create",
     *     name="user_create",
     *     defaults={"_format": "json"}
     * )
     */
    public function createAction(Request $request, SerializerService $serializerService, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $serializerService->getSerializer()->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

        $user->setPassword($password);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("user_info", ['id' => $user->getId()]);

    }

    /**
     * @Route(
     *     "/api/users/age",
     *      name="average_users_age",
     *     defaults={"_format": "json"}
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function averageUsersAgeAction(Request $request, UserManager $userManager, UserRepository $userRepository, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $data = $serializer->decode($request->getContent(), 'json');

        $errors = $validator->validate($data['from'], new Date());

        if (count($errors) > 0)
        {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $users = $userRepository->findAllUsersWithBirthDateByDate($data['from']);

        $averageUsersAge = $userManager->calculateUsersAverageAge($users);

        return new JsonResponse(['result' => $averageUsersAge]);
    }
}
