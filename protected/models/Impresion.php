<?php

/**
 * This is the model class for table "impresion".
 *
 * The followings are the available columns in table 'impresion':
 * @property integer $idImpresion
 * @property string $fFecha
 * @property integer $idUsuario
 *
 * The followings are the available model relations:
 * @property Usuario $usuario
 * @property Impresiondetalle[] $impresiondetalles
 */
class Impresion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'impresion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fFecha, idUsuario', 'required'),
			array('idUsuario', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idImpresion, fFecha, idUsuario', 'safe', 'on'=>'search'),
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
			'usuario' => array(self::BELONGS_TO, 'Usuario', 'idUsuario'),
			'impresiondetalles' => array(self::HAS_MANY, 'Impresiondetalle', 'idImpresion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idImpresion' => 'Cod. Impresi&oacute;n',
			'fFecha' => 'Fecha impresi&oacute;n',
			'idUsuario' => 'Usuario',
		);
	}

	public function defaultScope()
    {
        return array(
            'order'=>'idImpresion DESC',
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

		$criteria->compare('idImpresion',$this->idImpresion);
		$criteria->compare('DATE_FORMAT(fFecha, "%d/%m/%Y")',$this->fFecha);
		$criteria->compare('idUsuario',$this->idUsuario);

		$criteria->order = 'idImpresion DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Impresion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
