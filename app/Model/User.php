<?php

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'users')]
    private Collection $teams;

    public function __construct() {
        $this->teams = new ArrayCollection();
    }

	#[ORM\Column(type: "integer", nullable: FALSE)]
	#[ORM\GeneratedValue]
	#[ORM\Id]
    private int $id;

    #[ORM\Column(type: "string", nullable: FALSE)]
    private $name;

    #[ORM\Column(type: "string", nullable: FALSE)]
    private $surname;

    #[ORM\Column(type: "string", nullable: FALSE)]
    private $role;


    public function getId(): int
    {
        return $this->id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): void
    {
        $this->teams[] = $team;
    }
}
