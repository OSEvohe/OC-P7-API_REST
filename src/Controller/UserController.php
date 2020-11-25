<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * @Route ("/api")
 */
class UserController extends AbstractController
{
    /**
     * List Users
     * @Route("/users", name="users_list", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(User::class);
        $users = $er->findAll();

        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'list_users']);
    }


    /**
     * Show user details
     * @Route("/user/{id}", name="user_read", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function read(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'show_user']);
    }


    /**
     * Create a new user
     * @Route ("/user", name="user_create", methods={"POST"} )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse(["TODO" => "Create a new user in database"]);
    }


    /**
     * Update a user
     * @Route ("/user/{id}", name="user_update", methods={"PUT", "PATCH"} )
     *
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function update(User $user, Request $request): JsonResponse
    {
        return new JsonResponse(["TODO" => "Update user in database"]);
    }


    /**
     * @Route ("/user/{id}", name="user_delete", methods={"DELETE"} )
     *
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        return new JsonResponse(["TODO" => 'delete user from database']);
    }
}
