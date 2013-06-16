<?php
namespace In2\Femanager\Utility;

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
 * Misc Functions
 *
 * @package femanager
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Div {

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
	 * configurationManager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Content Object
	 *
	 * @var object
	 */
	public $cObj;

	/**
	 * Return current logged in fe_user
	 *
	 * @return query object
	 */
	public function getCurrentUser() {
		$user = $this->userRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
		return $user;
	}

	/**
	 * Set object properties from forceValues in TypoScript
	 *
	 * @param $object
	 * @param $settings
	 * @param $cObj
	 * @return $object
	 */
	public function forceValues($object, $settings, $cObj) {
		foreach ((array) $settings as $field => $config) {
			if (stristr($field, '.')) {
				continue;
			}

			// value to set
			$value = $cObj->cObjGetSingle($settings[$field], $settings[$field . '.']);

			if ($field == 'usergroup') {
				// need objectstorage for usergroup field
				$object->removeAllUsergroups();
				$values = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $value, 1);
				foreach ($values as $usergroupUid) {
					$usergroup = $this->userGroupRepository->findByUid($usergroupUid);
					$object->addUsergroup($usergroup);
				}
			} else {
				// set value
				if (method_exists($object, 'set' . ucfirst($field))) {
					$object->{'set' . ucfirst($field)}($value);
				}
			}
		}
		return $object;
	}
}
?>