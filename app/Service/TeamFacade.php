<?php

namespace App\Service;

use App\Model\Team;
use Doctrine\ORM\EntityManagerInterface;

class TeamFacade
{
    public const ROLES_SETTINGS = [
        UserFacade::ROLE_ZAVODNIK => ['maxPlayers' => 3, 'minPlayers' => 1, 'label' => 'Závodník'],
        UserFacade::ROLE_SPOLUJEZDEC => ['maxPlayers' => 3, 'minPlayers' => 1, 'label' => 'Spolujezdec'],
        UserFacade::ROLE_TECHNIK => ['maxPlayers' => 2, 'minPlayers' => 1, 'label' => 'Technik'],
        UserFacade::ROLE_MANAGER => ['maxPlayers' => 1, 'minPlayers' => 1, 'label' => 'Manažer'],
        UserFacade::ROLE_FOTOGRAF => ['maxPlayers' => 1, 'minPlayers' => 0, 'label' => 'Fotograf'],
    ];

    public function __construct(
        private EntityManagerInterface $em,
        private UserFacade $userFacade, 
        private Team $team)
    {
    }

    public function addTeam($data)
    {
        $team = new Team();
        $team->setName($data->name);

        foreach(SELF::ROLES_SETTINGS as $role => $item) {
            foreach($data[$role] as $userId){
                if($userId === ''){
                    continue;
                }
                
                $user = $this->userFacade->findById($userId);

                if($user !== null) {
                    $team->addUser($user);
                }
            }
        }

        $this->em->persist($team);
        $this->em->flush();        
    }

    public function getTeams()
    {
        return $all =  $this->em->getRepository(Team::class)->findBy(
            [],
            ['id' => 'ASC'],
        );
    }

    public function getTeamNames()
    {
        $name = [];
        $all = $this->getTeams();
        foreach($all as $team) {
            array_push($name, $team->name);

        }
        return $name;
    }

    public function getUsersByTeams()
    {
        $all = $this->getTeams();
        $users = [];

        foreach($all as $team) {
            
            array_push($users, $this->em->getRepository(Team::class)->findOneBy(
                ['id' => $team->id]));
        }
        return $users;
    }
}
