<?php

namespace Lorry\Model;

use Lorry\Model;
use Lorry\ModelFactory;

class Release extends Model {

	public function __construct() {
		parent::__construct('release', array(
			'addon' => 'int',
			'version' => 'string',
			'timestamp' => 'datetime',
			'assetsecret' => 'string',
			'changelog' => 'text',
			'whatsnew' => 'text'));
	}

	public final function setAddon($addon) {
		return $this->setValue('addon', $addon);
	}

	public final function byAddon($addon) {
		return $this->byValue('addon', $addon);
	}

	public final function getAddon() {
		return $this->getValue('addon');
	}

	/**
	 * @return Addon
	 */
	public final function fetchAddon() {
		return $this->fetch('Addon', 'addon');
	}

	public final function byGame($game) {
		$addons = ModelFactory::build('Addon')->all()->byGame($game);
		$releases = array();
		foreach($addons as $addon) {
			$release = ModelFactory::build('Release')->latest($addon->getId());
			if($release) $releases[] = $release;
		}
		return $releases;
	}

	public final function byOwner($owner) {
		$addons = ModelFactory::build('Addon')->all()->byOwner($owner);
		$releases = array();
		foreach($addons as $addon) {
			$release = ModelFactory::build('Release')->latest($addon->getId());
			if($release) $releases[] = $release;
		}
		return $releases;
	}

	public final function setVersion($version) {
		$version = trim($version);
		$this->validateString($version, 1, 20);
		$this->validateRegexp($version, '/^([a-zA-Z0-9-][a-zA-Z0-9-.]*)$/');
		return $this->setValue('version', $version);
	}

	public final function byVersion($version, $addon) {
		return $this->byValues(array('addon' => $addon, 'version' => $version));
	}

	public final function getVersion() {
		return $this->getValue('version');
	}

	public final function latest($addon) {
		$releases = $this->order('timestamp', true)->all()->byValue('addon', $addon);
		foreach($releases as $release) {
			if(!$release->isReleased()) {
				continue;
			}
			return $release;
		}
		return null;
	}

	public final function isReleased() {
		if($this->getTimestamp() === null) {
			return false;
		}

		if($this->getTimestamp() > time()) {
			return false;
		}
		return true;
	}

	public function setTimestamp($timestamp) {
		return $this->setValue('timestamp', $timestamp);
	}

	public function getTimestamp() {
		return $this->getValue('timestamp');
	}

	public function isScheduled() {
		return $this->getTimestamp() !== null;
	}

	public function setWhatsnew($whatsnew) {
		if($whatsnew) {
			$this->validateString($whatsnew, 5, 512);
		} else {
			$whatsnew = null;
		}
		return $this->setValue('whatsnew', $whatsnew);
	}

	public function getWhatsnew() {
		return $this->getValue('whatsnew');
	}

	public function setChangelog($changelog) {
		if($changelog) {
			$this->validateString($changelog, 5, 65536);
		} else {
			$changelog = null;
		}
		return $this->setValue('changelog', $changelog);
	}

	public function getChangelog() {
		return $this->getValue('changelog');
	}

	public function fetchRequirements() {
		return ModelFactory::build('Dependency')->all()->byRelease($this->getId());
	}

	public function fetchDependencies() {
		return ModelFactory::build('Dependency')->all()->byRequired($this->getId());
	}

	public function onSave() {
		$this->setValue('assetsecret', md5($this->getAddon().time().uniqid()));
	}

	public function getAssetSecret() {
		return $this->getValue('assetsecret');
	}

}
