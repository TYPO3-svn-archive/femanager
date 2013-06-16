<?php
namespace In2\Femanager\Domain\Model;

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
 * User Model
 *
 * @package femanager
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class User extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser {

	/**
	 * usergroups
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2\Femanager\Domain\Model\UserGroup>
	 */
	protected $usergroup;

	/**
	 * Get usergroup
	 *
	 * @return \In2\Femanager\Domain\Model\UserGroup
	 */
	public function getUsergroup() {
		return $this->usergroup;
	}

	/**
	 * Set usergroup
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $usergroup
	 */
	public function setUsergroup(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $usergroup) {
		$this->usergroup = $usergroup;
	}

	/**
	 * Add usergroup
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup $usergroup
	 */
	public function addUsergroup(\In2\Femanager\Domain\Model\UserGroup $usergroup) {
		$this->usergroup->attach($usergroup);
	}

	/**
	 * Remove usergroup
	 *
	 * @param UserGroup $usergroup
	 */
	public function removeUsergroup(\In2\Femanager\Domain\Model\UserGroup $usergroup) {
		$this->usergroup->detach($usergroup);
	}

	/**
	 * Remove all usergroups
	 */
	public function removeAllUsergroups() {
		$this->usergroup = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}
}
?>