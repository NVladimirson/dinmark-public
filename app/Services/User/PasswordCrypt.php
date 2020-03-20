<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 20.03.2020
 * Time: 9:22
 */

namespace App\Services\User;


class PasswordCrypt
{
	public static function getPassword($id, $email, $password, $sequred = false)
	{
		if(!$sequred) $password = md5($password);
		return sha1($email . $password . SYS_PASSWORD . $id);
	}

	private static $instance;
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
}