<?php

namespace App\Service;

use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;

class UserFacade
{
    public const ROLE_ZAVODNIK = 'zavodnik';

    public const ROLE_FOTOGRAF = 'fotograf';

    public const ROLE_MANAGER = 'manager';

    public const ROLE_SPOLUJEZDEC = 'spolujezdec';

    public const ROLE_TECHNIK = 'technik';

    public const ROLES = [self::ROLE_ZAVODNIK, self::ROLE_FOTOGRAF, self::ROLE_MANAGER, self::ROLE_SPOLUJEZDEC, self::ROLE_TECHNIK];

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addUser($name, $surname, $role): void
    {
        $user = new User();
        $user->setName($name);
        $user->setSurname($surname);
        $user->setRole($role);

        $this->em->persist($user);
        $this->em->flush();
    }

    public function findById($id): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(
            ['id' => $id]
        ) ?? null;
    }

    public function findByRole($role)
    {
        return $this->em->getRepository(User::class)->findBy(
            ['role' => $role],
            ['id' => 'ASC'],
        );
    }

    public function findAll()
    {
        return $this->em->getRepository(User::class)->findBy(
            [],
            ['role' => 'ASC', 'id' => 'ASC'],
        );
    }

    public function getFormOptions()
    {
        $users = $this->findAll();
        $output = array_fill_keys(self::ROLES, ['' => '-- select your player-- ']);

        foreach($users as $user) {
            $output[$user->getRole()][$user->getId()] = $user->getName() . ' ' . $user->getSurname();
        }

        return $output;
    }
}
