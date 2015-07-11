<?php
namespace AgoraTeam\Agora\Domain\Repository;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2015 Philipp Thiele <philipp.thiele@phth.de>
	 *           Björn Christopher Bresser <bjoern.bresser@gmail.com>
	 *  All rights reserved
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

/**
 * Class Repository
 *
 * @package AgoraTeam\Agora\Domain\Repository
 */
class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * UserRepository
	 *
	 * @var \AgoraTeam\Agora\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * ForumRepository
	 *
	 * @var \AgoraTeam\Agora\Domain\Repository\ForumRepository
	 * @inject
	 */
	protected $forumRepository;

	/**
	 * The logged in frontend user, if there is any
	 *
	 * @var \AgoraTeam\Agora\Domain\Model\User
	 */
	protected $user;

	/**
	 * Initialize object
	 *
	 * @return void
	 */
	public function initializeObject() {
	}

	/**
	 * Returns the user
	 *
	 * @return mixed
	 */
	public function getUser() {
		if (!is_a($this->user, '\AgoraTeam\Agora\Domain\Model\User')) {
			$userFromTsfe = $GLOBALS['TSFE']->fe_user->user;
			if ($userFromTsfe['uid']) {
				$user = $this->userRepository->findByUid($userFromTsfe['uid']);
				if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
					$this->setUser($user);
				}
			}
		}

		return $this->user;
	}

	/**
	 * Set User
	 *
	 * @param \AgoraTeam\Agora\Domain\Model\User $user
	 *
	 * @return void
	 */
	public function setUser(\AgoraTeam\Agora\Domain\Model\User $user) {
		$this->user = $user;
	}
}
