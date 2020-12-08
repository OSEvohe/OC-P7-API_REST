<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\HAL\UserHAL;
use App\Service\ManageEntities;
use Exception;
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
    /** @var UserHAL */
    private $userHAL;

    /** @var ManageEntities */
    private $manageEntities;

    /**
     * UserController constructor.
     * @param UserHAL $userHAL
     * @param ManageEntities $manageEntities
     */
    public function __construct(UserHAL $userHAL, ManageEntities $manageEntities)
    {
        $this->userHAL = $userHAL;
        $this->manageEntities = $manageEntities;
    }


    /**
     * List Users
     * @Route("/users/{page}/{limit}", name="users_list", methods={"GET"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     * @throws Exception
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $data = $this->manageEntities->list(User::class,$page, $limit);
        return $this->json($this->userHAL->getEntityListHAL($data), Response::HTTP_OK, [], ['groups' => ['list_users', 'index']]);
    }


    /**
     * Show user details
     * @Route("/user/{id}", name="user_read", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function read(User $user): JsonResponse
    {
        return $this->json($this->userHAL->getHAL($user), Response::HTTP_OK, [], ['groups' => 'show_user']);
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
