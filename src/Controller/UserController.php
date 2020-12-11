<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use App\Security\UserVoter;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\UserHAL;
use App\Service\ManageCompany;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route ("/api")
 */
class UserController extends AbstractController
{
    const DENIED = 'Access Denied to users registered by another company';

    /** @var UserHAL */
    private $userHAL;

    /** @var ManageCompany */
    private $manageCompany;

    /**
     * UserController constructor.
     * @param UserHAL $userHAL
     * @param ManageCompany $manageCompany
     */
    public function __construct(UserHAL $userHAL, ManageCompany $manageCompany)
    {
        $this->userHAL = $userHAL;
        $this->manageCompany = $manageCompany;
    }


    /**
     * List users
     * @Route("/users/{page}/{limit}", name="users_list", methods={"GET"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     * @throws Exception
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $data = $this->manageCompany->listUsers($page, $limit);
        return $this->json($this->userHAL->getEntityListHAL($data, 'users'), Response::HTTP_OK, [], ['groups' => ['list_users', 'index']]);
    }


    /**
     * Show user details
     * @Route("/user/{id}", name="user_read", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function read(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::ACCESS_USER, $user, self::DENIED);

        return $this->json($this->userHAL->getHAL($user), Response::HTTP_OK, [], ['groups' => 'show_user']);
    }


    /**
     * Create a new user
     * @Route ("/user", name="user_create", methods={"POST"} )
     *
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @return JsonResponse
     */
    public function create(Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        /** @var Company $loggedCompany */
        $loggedCompany = $this->getUser();

        $user = new User();
        $user->setCompany($loggedCompany);

        $form = $this->createForm(UserType::class, $user);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageCompany->createUser($user);
        return $this->json($this->userHAL->getHAL($user), Response::HTTP_CREATED, [], ['groups' => 'show_user']);
    }


    /**
     * Update a user
     * @Route ("/user/{id}", name="user_update", methods={"PUT", "PATCH"} )
     *
     * @param User $user
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @return JsonResponse
     */
    public function update(User $user, Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::ACCESS_USER, $user, self::DENIED);

        $form = $this->createForm(UserType::class, $user);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageCompany->save($user);
        return $this->json($this->userHAL->getHAL($user), Response::HTTP_OK, [], ['groups' => 'show_user']);
    }


    /**
     * @Route ("/user/{id}", name="user_delete", methods={"DELETE"} )
     *
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::ACCESS_USER, $user, self::DENIED);
        $id = $user->getId();
        $this->manageCompany->delete($user);

        return $this->json(['message' => "User #".$id." deleted!"],Response::HTTP_OK);
    }
}
