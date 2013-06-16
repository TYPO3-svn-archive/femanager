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
class GeneralController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * userRepository
	 *
	 * @var \In2\Femanager\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * userGroupRepository
	 *
	 * @var \In2\Femanager\Domain\Repository\UserGroupRepository
	 * @inject
	 */
	protected $userGroupRepository;

	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Content Object
	 *
	 * @var object
	 */
	public $cObj;

	/**
	 * Former known as piVars
	 *
	 * @var array
	 */
	public $pluginVariables;

	/**
	 * Misc Functions
	 *
	 * @var object
	 */
	public $div;

	/**
	 * TypoScript
	 *
	 * @var object
	 */
	public $config;

	/**
	 * Current logged in user object
	 *
	 * @var object
	 */
	public $user;

	/**
	 * All available usergroups
	 *
	 * @var object
	 */
	public $allUserGroups;

	/**
	 * Init for User creation
	 *
	 * @return void
	 */
	public function initializeCreateAction() {
		if ($this->pluginVariables['user']['usergroup'][0] < 1) {
			unset($this->pluginVariables['user']['usergroup']);
		}
		$this->request->setArguments($this->pluginVariables);
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->div = $this->objectManager->get('In2\Femanager\Utility\Div');
		$this->user = $this->div->getCurrentUser();
		$this->cObj = $this->configurationManager->getContentObject();
		$this->pluginVariables = $this->request->getArguments();
		$config = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$this->config = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$this->config = $this->config['plugin.']['tx_femanager.']['settings.'];
		$this->allUserGroups = $this->userGroupRepository->findAll();

		// check if ts is included
		if ($this->settings['_TypoScriptIncluded'] != 1) {
			$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error_no_typoscript', 'femanager'));
		}

		// check if storage pid was set
		if (intval($config['persistence']['storagePid']) === 0) {
			$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error_no_storagepid', 'femanager'));
		}
	}

	/**
	 * Login FE-User after creation
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @return void
	 */
	protected function loginAfterCreate($user) {
		if ($this->config['new.']['login'] != 1) {
			return;
		}

		$GLOBALS['TSFE']->fe_user->checkPid = '';
		$info = $GLOBALS['TSFE']->fe_user->getAuthInfoArray();
		$user = $GLOBALS['TSFE']->fe_user->fetchUserRecord($info['db_user'], $user->getUsername());
		$GLOBALS['TSFE']->fe_user->createUserSession($user);
	}

	/**
	 * Redirect
	 *
	 * @param \array $config		TypoScript
	 * @return void
	 */
	protected function redirectAfterCreate() {
		$target = null;
		// redirect from TypoScript cObject
		if ($this->cObj->cObjGetSingle($this->config['new.']['redirect'], $this->config['new.']['redirect.'])) {
			$target = $this->cObj->cObjGetSingle($this->config['new.']['redirect'], $this->config['new.']['redirect.']);
		}

		// if redirect target
		if ($target) {
			$this->uriBuilder->setTargetPageUid($target);
			$link = $this->uriBuilder->build();
			$this->redirectToUri($link);
		}
	}

	/**
	 * Deactivate errormessages in flashmessages
	 *
	 * @return bool
	 */
	protected function getErrorFlashMessage() {
		return false;
	}

















	/**
	 * action edit
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @return void
	 */
	public function editAction(\In2\Femanager\Domain\Model\User $user) {
		$this->view->assign('user', $user);
	}

	/**
	 * action update
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @return void
	 */
	public function updateAction(\In2\Femanager\Domain\Model\User $user) {
		$this->userRepository->update($user);
		$this->flashMessageContainer->add('Your User was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \In2\Femanager\Domain\Model\User $user
	 * @return void
	 */
	public function deleteAction(\In2\Femanager\Domain\Model\User $user) {
		$this->userRepository->remove($user);
		$this->flashMessageContainer->add('Your User was removed.');
		$this->redirect('list');
	}

}
?>