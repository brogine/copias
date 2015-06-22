<?php

/**
 * This is the model class for table "usuario".
 *
 * The followings are the available columns in table 'usuario':
 * @property integer $idUsuario
 * @property string $sNombre
 * @property string $sApellido
 * @property string $sEmail
 * @property string $sPassword
 * @property integer $sTelefono
 * @property string $sDomicilio
 * @property integer $bActivo
 * @property string $dUltimoLogin
 * @property integer $idRol
 * @property integer $nDocumento
 * @property integer $nMatricula
 * @property integer $idCarrera
 *
 * The followings are the available model relations:
 * @property Pedido[] $pedidos
 * @property Rol $rol
 * @property Carrera $carrera
 */
class Usuario extends CActiveRecord
{
	public $rePassword;
    public $newPassword;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sNombre, sEmail, nDocumento, idCarrera, idRol', 'required'),
			array('rePassword, newPassword', 'required', 'on'=>'register, login'),
			array('newPassword', 'compare', 'compareAttribute'=>'rePassword', 'on'=>'register, update'),
            array('newPassword, rePassword', 'safe', 'on'=>'register, update'),
			array('sTelefono, bActivo, idRol, nDocumento, nMatricula, idCarrera', 'numerical', 'integerOnly'=>true),
			array('sNombre, sApellido', 'length', 'max'=>50),
			array('sEmail', 'email'),
			array('sEmail', 'unique'),
			array('sEmail', 'length', 'max'=>100),
			array('sDomicilio', 'length', 'max'=>75),
			array('dUltimoLogin', 'safe'),
			array('sNombre, sApellido, sEmail, sTelefono, sDomicilio, bActivo, idRol', 'safe', 'on'=>'search'),
		);
	}

	public function beforeSave() {
	   if(parent::beforeSave()){
           if(!empty($this->newPassword) && $this->newPassword == $this->rePassword){
               $this->sPassword = CPasswordHelper::hashPassword($this->newPassword);
           }
	       return true;
	   }
	   return false;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pedidos' => array(self::HAS_MANY, 'Pedido', 'idUsuario'),
			'rol' => array(self::BELONGS_TO, 'Rol', 'idRol'),
			'carrera' => array(self::HAS_ONE, 'Carrera', 'idCarrera'),
			'permisos' => array(self::HAS_MANY, 'Permiso', array('idRol' => 'idRol')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idUsuario' => 'Cód. Usuario',
			'sNombre' => 'Nombre',
			'sApellido' => 'Apellido',
			'sEmail' => 'Email',
			'sPassword' => 'Password',
			'sTelefono' => 'Telefono',
			'sDomicilio' => 'Domicilio',
			'bActivo' => 'Activo',
			'dUltimoLogin' => 'Ultimo Ingreso',
			'idRol' => 'Rol',
			'rePassword' => 'Repetir password',
            'newPassword' => 'Password',
            'fullName' => 'Nombre Completo',
            'nDocumento' =>'Numero Documento',
			'nMatricula' =>'Matricula',
			'idCarrera' => 'Carrera',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('sNombre',$this->sNombre,true);
		$criteria->compare('sApellido',$this->sApellido,true);
		$criteria->compare('sEmail',$this->sEmail,true);
		$criteria->compare('sTelefono',$this->sTelefono);
		$criteria->compare('sDomicilio',$this->sDomicilio,true);
		$criteria->compare('bActivo',$this->bActivo);
		$criteria->compare('idRol',$this->idRol);

		$criteria->order = 'idUsuario DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Usuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getKey(){
		return md5($this->idUsuario.$this->sEmail);
	}

	public function verifyKey($key) {
		return ($key == md5($this->idUsuario.$this->sEmail));
	}

	public function getFullName() {
	    return $this->sNombre.' '.$this->sApellido;
	}

	public function getFullNameDni() {
	    return $this->sNombre.' '.$this->sApellido . ' ' .$this->nDocumento;
	}
}
