<?php
/**
 * @author Viktar Dubiniuk <dubiniuk@owncloud.com>
 *
 * @copyright Copyright (c) 2018, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\FederatedFileSharing;

/**
 * Class Address
 *
 * @package OCA\FederatedFileSharing
 */
class Address {
	/**
	 * @var string
	 */
	protected $cloudId;

	/**
	 * @var string
	 */
	protected $displayName;

	/**
	 * Address constructor.
	 *
	 * @param string $cloudId
	 * @param string $displayName
	 */
	public function __construct($cloudId, $displayName = '') {
		$this->cloudId = $cloudId;
		$this->displayName = $displayName;
	}

	/**
	 * Get user federated id
	 *
	 * @return string
	 */
	public function getCloudId() {
		$hostName = $this->getHostName();
		$userId = $this->getUserId();
		return "{$userId}@{$hostName}";
	}

	/**
	 * Get user id
	 *
	 * @return string
	 */
	public function getUserId() {
		// userId is everything except the last part
		$parts = \explode('@', $this->cloudId);
		\array_pop($parts);
		return \implode('@', $parts);
	}

	/**
	 * Get user host without protocol and trailing slash
	 *
	 * @return string
	 */
	public function getHostName() {
		// hostname is the last part
		$parts = \explode('@', $this->cloudId);
		$rawHostName = \array_pop($parts);
		if ($fileNamePosition = \strpos($rawHostName, '/index.php')) {
			$rawHostName = \substr($rawHostName, 0, $fileNamePosition);
		}

		$normalizedHostname = \rtrim(
			\strtolower($rawHostName),
			'/'
		);

		// replace all characters before :// and :// itself
		return \preg_replace('|^(.*?://)|', '', $normalizedHostname);
	}

	/**
	 * Get user display name, fallback to userId if it is empty
	 *
	 * @return string
	 */
	public function getDisplayName() {
		return ($this->displayName !== '') ? $this->displayName : $this->getUserId();
	}

	/**
	 * Checks if the user and host is the same with another address
	 *
	 * @param Address $address
	 *
	 * @return bool
	 */
	public function equalTo(Address $address) {
		$thisUserId = $this->translateUid($this->getUserId());
		$otherUserId = $this->translateUid($address->getUserId());
		return $this->getHostName() === $address->getHostName()
			&& $thisUserId === $otherUserId;
	}

	/**
	 * @param string $uid
	 * @return mixed
	 */
	protected function translateUid($uid) {
		// FIXME this should be a method in the user management instead
		// Move to a helper instead of C&P meanwhile?
		\OCP\Util::emitHook(
			'\OCA\Files_Sharing\API\Server2Server',
			'preLoginNameUsedAsUserName',
			['uid' => &$uid]
		);
		return $uid;
	}
}
