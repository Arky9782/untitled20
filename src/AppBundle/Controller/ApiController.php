<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.12.2018
 * Time: 20:01
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login")
     */
    public function loginAction()
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201);

    }

    /**
 * @Route("/api/{id}/info", name="user_info")
 */
    public function infoAction(User $user)
    {
        return $this->json([
            'username' => $user->getUsername(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'about' => $user->getAbout(),
            'birth_date' => $user->getBirthDate()
        ]);
    }


    /**
     * @Route("/api/create", name="user_create")
     */
    public function createAction(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

        $user->setPassword($password);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201);

    }

    /**
     * @Route("/api/users/age", name="average_users_age")
     */
    public function averageUsersAgeAction(UserManager $userManager)
    {
        try {
            $averageUsersAge = $userManager->calculateUsersAverageAge();
        }
        catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage());
        }

        return new JsonResponse($averageUsersAge);
    }
}