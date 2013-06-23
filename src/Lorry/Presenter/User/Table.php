<?php

namespace Lorry\Presenter\User;

use Lorry\Presenter;
use Lorry\ModelFactory;

class Table extends Presenter {

	public function get() {

		$this->security->requireModerator(); //@TODO rate limiting

		$users = ModelFactory::build('User')->byAnything();
		foreach($users as $user) {
			$this->context['users'][] = $user->getUsername();
		}


		$this->display('user/table.twig');
	}

}