<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Service\TeamFacade;
use App\Service\UserFacade;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;


class HomePresenter extends Presenter
{
    public function __construct(
        private TeamFacade $teamFacade,
        private UserFacade $userFacade
    )
    {
    }

    protected function beforeRender()
	{
        // vytiahneme teamy z databazy a posleme do sablony
        $this->template->dbData = $this->userFacade->findAll();
        $teams = $this->teamFacade->getTeams();
        $this->template->teams = $teams;
	}


    // formular pre registraciu clena
    protected function createComponentRegistrationFormUser(): Form
	{
        $roles = [];
        foreach(TeamFacade::ROLES_SETTINGS as $role => $settings) {
            $roles[$role] = $settings['label'];
        }

		$form = new Form;
		$form->addText('name', 'Jméno:')->setRequired('Zadejte prosím jméno');
		$form->addText('surname', 'Příjmení:')->setRequired('Zadejte prosím příjmení');
        $form->addSelect('role', 'Typ:', $roles);
		$form->addSubmit('send', 'Registrovat');
		$form->onSuccess[] = [$this, 'formUserSucceeded'];
		return $form;
	}

	public function formUserSucceeded(Form $form, $data): void
	{
        $this->userFacade->addUser($data->name, $data->surname, $data->role);
        $this->flashMessage('Byl jste úspěšně registrován.');
		$this->redirect('Home:');
	}

    // formular pre registraci teamu

    protected function createComponentRegistrationFormTeam(): Form
	{
        $options = $this->userFacade->getFormOptions();

        $form = new Form;
		$form->addText('name', 'Jméno Týmu:')->setRequired('Zadejte prosím jméno Týmu');

        foreach (TeamFacade::ROLES_SETTINGS as $role => $settings) {
            $roleContainer = $form->addContainer($role);

            for ($i=0; $i<$settings['maxPlayers']; $i++) {
                $select = $roleContainer->addSelect((string) $i, $settings['label'] . ' ' . ($i+1), $options[$role]);
                $select->setHtmlAttribute('data-role-type', $role);

                if ($i < $settings['minPlayers']) {
                    $select->setRequired();
                }
            }
        }
        
		$form->addSubmit('send', 'Registrovat');
		$form->onSuccess[] = [$this, 'formTeamSucceeded'];
		return $form;
	}

    public function formTeamSucceeded(Form $form, $data): void
	{
        $this->teamFacade->addTeam($data);
        $this->flashMessage('Tým byl úspěšně registrován.');
		$this->redirect('Home:');
	}
}
