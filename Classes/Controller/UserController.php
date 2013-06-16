<?php
namespace In2\Femanager\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Alex Kellner <alexander.kellner@in2code.de>, in2code
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * User Controller
 *
 * @package femanager
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class UserController extends \In2\Femanager\Controller\GeneralController {

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$users = $this->userRepository->findByUsergroups($this->settings['list']['usergroup'], $this->settings);
		$this->view->assign('users', $users);
	}

	/**
	 * action show
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @dontvalidate $user
	 * @return void
	 */
	public function showAction(\In2\Femanager\Domain\Model\User $user = NULL) {
		if (!is_object($user)) {
			if (is_numeric($this->settings['show']['user'])) {
				$user = $this->userRepository->findByUid($this->settings['show']['user']);
			} elseif ($this->settings['show']['user'] == '[this]') {
				$user = $this->user;
			}
		}
		$this->view->assign('user', $user);
	}

	/**
	 * action new
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @dontvalidate $user
	 * @return void
	 */
	public function newAction(\In2\Femanager\Domain\Model\User $user = NULL) {
		$this->view->assign('user', $user);
		$this->view->assign('allUserGroups', $this->allUserGroups);
	}

	/**
	 * action create
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @validate $user In2\Femanager\Domain\Validator\MainValidator
	 * @validate $user In2\Femanager\Domain\Validator\PasswordValidator
	 * @return void
	 */
	public function createAction(\In2\Femanager\Domain\Model\User $user) {
		$user = $this->div->forceValues($user, $this->config['new.']['forceValues.'], $this->cObj);
		if ($this->settings['new']['fillEmailWithUsername'] == 1) {
			$user->setEmail($user->getUsername());
		}
		$this->userRepository->add($user);
		$this->persistenceManager->persistAll();

		$this->loginAfterCreate($user);
		$this->redirectAfterCreate();

		$this->redirect('createStatus');
	}

	/**
	 * Just for showing informations after user creation
	 *
	 * @return void
	 */
	public function createStatusAction() {
	}

}
?>