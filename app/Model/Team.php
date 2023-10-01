<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'teams')]
class Team
{
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'teams')]
    private Collection $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

	#[ORM\Column(type: "integer", nullable: FALSE)]
	#[ORM\GeneratedValue]
	#[ORM\Id]
    public int $id;

    #[ORM\Column(type: "string", nullable: FALSE)]
    public $name;

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        $user->addTeam($this);
        $this->users[] = $user;
    }

    public function getUsersByRole(string $role)
    {
        $users = [];
        
        foreach($this->users as $user) {
            if ($user->getRole() === $role) {
                $users[] = $user;
            }
        }

        return $users;
    }
}
