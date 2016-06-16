<?php

namespace Obokaman\Application\Service\User;

use Obokaman\Application\Service\ApplicationService;
use Obokaman\Domain\Infrastructure\Repository\User\UserRepository;
use Obokaman\Domain\Model\User\Email;
use Obokaman\Domain\Model\User\UserId;

class EditUser implements ApplicationService
{
    /** @var UserRepository */
    private $user_repo;

    public function __construct(UserRepository $a_user_repository)
    {
        $this->user_repo = $a_user_repository;
    }

    public function __invoke(EditUserRequest $an_edit_user_request)
    {
        $user_id = new UserId($an_edit_user_request->user_id);
        $user    = $this->user_repo->find($user_id);

        $user->changeName($an_edit_user_request->name);

        $email = new Email($an_edit_user_request->email);
        $user->changeEmail($email);

        $this->user_repo->persist($user);
    }
}
