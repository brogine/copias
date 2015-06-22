<?php

/**
 * This is the model class for table "profesor".
 *
 * The followings are the available columns in table 'profesor':
 * @property integer $idProfesor
 * @property string $sNombre
 * @property string $sApellido
 * @property string $sTelefono
 * @property string $sDictar
 *
 * The followings are the available model relations:
 * @property Documento[] $documentos
 */
class Profesor extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profesor';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sNombre, sApellido, sTelefono', 'length', 'max'=>45),
			array('sDictar', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idProfesor, sNombre, sApellido, sTelefono', 'safe', 'on'=>'search'),
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
			'documentos' => array(self::MANY_MANY, 'Documento', 'documentoprofesor(idProfesor, idDocumento)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idProfesor' => 'Id Profesor',
			'sNombre' => 'Nombre',
			'sApellido' => 'Apellido',
			'sTelefono' => 'Telefono',
			'sDictar' => 'Materias a Dictar'
		);
	}

	public function getFullName(){
        return $this->sApellido . ' ' . $this->sNombre;
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

		$criteria->compare('idProfesor',$this->idProfesor);
		$criteria->compare('sNombre',$this->sNombre,true);
		$criteria->compare('sApellido',$this->sApellido,true);
		$criteria->compare('sTelefono',$this->sTelefono,true);

		$criteria->order = 'idProfesor DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profesor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
