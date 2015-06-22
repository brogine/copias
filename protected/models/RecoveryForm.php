<?php

/**
 * RecoveryForm class.
 * RecoveryForm is the data structure for keeping
 * password recovery form data. It is used by the 'recovery' action of 'DefaultController'.
 */
class RecoveryForm extends CFormModel {
	public $email;
	public $activationKey;
	public $newPassword;
	public $newVerify;
	public $idUsuario;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
		return array(
			array('email', 'required', 'on'=>'recovery'),
			array('email', 'email'),
			array('newPassword, newVerify, idUsuario', 'required', 'on'=>'reset'),
			array('newPassword, newVerify', 'length', 'min'=>4),
			array('newPassword', 'compare', 'compareAttribute'=>'newVerify', 'on'=>'reset'),
			array('idUsuario', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
			'email'			=> 'Email',
			'newPassword'	=> 'Nuevo password',
			'newVerify'		=> 'Reingresar password',
		);
	}
}