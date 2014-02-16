<?php

namespace Lorry\Presenter\User;

use Lorry\Presenter;
use Lorry\ModelFactory;
use Lorry\Exception\FileNotFoundException;

class Table extends Presenter {

	public function get() {
		$this->offerIdentification();
		$this->security->requireModerator(); //@TODO rate limiting

		$users = ModelFactory::build('User');

		$filter = filter_input(INPUT_GET, 'filter');
		if($filter) {
			switch($filter) {
				case 'bans':
					$this->security->requireModerator();
					//@TODO filter bans
					break;
				case 'moderators':
					$this->security->requireAdministrator();
					//@TODO filter privileges
					break;
				default:
					throw new FileNotFoundException();
					break;
			}
		} else {
			$users = $users->byAnything();
		}
		foreach($users as $user) {
			$this->context['users'][] = $user->getUsername();
		}


		$this->display('user/table.twig');
	}

}