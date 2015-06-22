<?php

/**
 * This is the model class for table "rol".
 *
 * The followings are the available columns in table 'rol':
 * @property integer $idRol
 * @property string $sDescripcion
 * @property integer $bPermitidoFront
 *
 * The followings are the available model relations:
 * @property Usuario[] $usuarios
 */
class Rol extends CActiveRecord
{
	public $permiso;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rol';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sDescripcion', 'length', 'max'=>45),
			array('bPermitidoFront', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idRol, sDescripcion, bPermitidoFront', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'usuarios' => array(self::HAS_MANY, 'Usuario', 'idRol'),
			'permisos' => array(self::MANY_MANY, 'Modulo', 'permiso(idRol, idModulo)'),
		);
	}

	public function scopes() {
        return array(
            'front'=>array(
                'condition'=>'bPermitidoFront = 1',
            ),
        );
    }

	public function behaviors(){
        return array('ESaveRelatedBehavior' => array(
	        'class' => 'application.components.ESaveRelatedBehavior')
	    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idRol' => 'Id Rol',
			'sDescripcion' => 'Descripcion',
			'bPermitidoFront' => 'Permitido para Registro de Usuarios'
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

		$criteria->compare('idRol',$this->idRol);
		$criteria->compare('sDescripcion',$this->sDescripcion,true);
		$criteria->compare('bPermitidoFront',$this->bPermitidoFront);

		$criteria->order = 'idRol DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rol the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
