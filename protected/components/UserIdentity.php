<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_NOT_VALIDATED=3;

	private $_id;
    private $_nameToShow;
    public function authenticate()
    {
        $record=Usuario::model()->with('permisos')->findByAttributes(array('sEmail'=>$this->username));

        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!CPasswordHelper::verifyPassword($this->password,$record->sPassword))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else if($record->bActivo != 1)
            $this->errorCode=self::ERROR_USER_NOT_VALIDATED;
        else
        {
            $this->_id=$record->idUsuario;
            $this->_nameToShow = $record->sNombre . ' ' . $record->sApellido;
            
            $this->setState('permisos', array_keys(CHtml::listData( $record->permisos, 'idModulo' , 'idModulo')));
            $this->errorCode=self::ERROR_NONE;

            $record->dUltimoLogin = new CDbExpression("NOW()");
            $record->update(array('dUltimoLogin'));
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return $this->_nameToShow;
    }

}