<?php

namespace Obokaman\Infrastructure\Repository\Doctrine\User;

use Doctrine\ORM\EntityManager;
use OboBundle\Entity\User as DoctrineUser;
use OboBundle\Repository\UserRepository as DoctrineUserRepository;
use Obokaman\Domain\User\Email;
use Obokaman\Domain\User\User;
use Obokaman\Domain\User\UserId;
use Obokaman\Domain\Infrastructure\Repository\User\UserRepository as UserRepositoryContract;

class UserRepository implements UserRepositoryContract
{
    /** @var EntityManager */
    private $em;

    /** @var DoctrineUserRepository */
    private $repo;

    public function __construct(EntityManager $an_entity_manager)
    {
        $this->em   = $an_entity_manager;
        $this->repo = $this->em->getRepository(DoctrineUser::class);
    }

    public function find(UserId $a_user_id)
    {
        $result = $this->repo->find((string) $a_user_id);

        return $this->hydrateItem($result);
    }

    public function findByEmail(Email $an_user_email)
    {
        $result = $this->repo->findOneBy(['email' => (string) $an_user_email]);

        return $this->hydrateItem($result);
    }

    public function findAll()
    {
        $results = $this->repo->findAll();

        return $this->hydrateItems($results);
    }

    public function persist(User $a_user, $flush = true)
    {
        $user = $this->repo->find((string) $a_user->userId());

        if (null === $user)
        {
            $user = new DoctrineUser();
        }

        $user->setId((string) $a_user->userId());
        $user->setEmail((string) $a_user->email());
        $user->setName($a_user->name());

        $this->em->persist($user);

        if (true === $flush)
        {
            $this->flush();
        }
    }

    public function remove(UserId $a_user_id, $flush = true)
    {
        $user = $this->repo->find((string) $a_user_id);

        if (null === $user)
        {
            return;
        }

        $this->em->remove($user);

        if (true === $flush)
        {
            $this->flush();
        }
    }

    public function flush()
    {
        $this->em->flush();
    }

    /** @return User[] */
    private function hydrateItems($results)
    {
        if (empty($results))
        {
            return [];
        }

        $users = [];
        foreach ($results as $result)
        {
            $user = $this->hydrateItem($result);
            array_push($users, $user);
        }

        return $users;
    }

    private function hydrateItem(DoctrineUser $result = null)
    {
        if (empty($result))
        {
            return null;
        }

        $user = new User(
            new UserId($result->getId()), $result->getName(), new Email($result->getEmail())
        );

        return $user;
    }
}
