<?php

namespace Lorry\Model;

use Lorry\Model;

class User extends Model {

	public function __construct() {
		parent::__construct('user', array(
			'username' => 'varchar(16)',
			'secret' => 'varchar(255)',
			'password' => 'varchar(255)',
			'email' => 'varchar(255)',
			'clonkforge' => 'int',
			'github' => 'string',
			'language' => 'varchar(5)'));
	}

	public function getUsername() {
		return $this->getValue('username');
	}

	public function setUsername($username) {
		return $this->setValue('username', $username);
	}

	public final function byUsername($username) {
		return $this->byValue('username', $username);
	}

	public function getEmail() {
		return $this->getValue('email');
	}

	public function setEmail($email) {
		return $this->setValue('email', $email);
	}

	public final function byEmail($email) {
		return $this->byValue('email', $email);
	}

	public final function regenerateSecret() {
		$secret = base64_encode(openssl_random_pseudo_bytes(64));
		return $this->setValue('secret', $secret);
	}

	public final function getSecret() {
		return $this->getValue('secret');
	}

	public final function matchSecret($secret) {
		if(empty($secret))
			return false;
		return $this->match('secret', $secret);
	}

	public final function setPassword($password) {
		$hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
		return $this->setValue('password', $hash);
	}

	public final function hasPassword() {
		return $this->getValue('password') != '';
	}

	public final function matchPassword($password) {
		if(empty($password))
			return false;
		return password_verify($password, $this->getValue('password')) === true;
	}

	public final function isAdministrator() {
		return $this->getId() == 1;
	}

	public final function isModerator() {
		return $this->getId() == 1;
	}

	public final function setClonkforge($clonkforge) {
		return $this->setValue('clonkforge', intval($clonkforge));
	}

	public final function getClonkforge() {
		return $this->getValue('clonkforge');
	}

	public final function setGithub($github) {
		return $this->setValue('github', $github);
	}

	public final function getGithub() {
		return $this->getValue('github');
	}

	public final function setLanguage($language) {
		return $this->setValue('language', $language);
	}

	public final function getLanguage() {
		return $this->getValue('language');
	}

	public function __toString() {
		return $this->getUsername().'';
	}

}