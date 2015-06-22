<?php

/**
 * This is the model class for table "promocion".
 *
 * The followings are the available columns in table 'promocion':
 * @property integer $idPromocion
 * @property integer $nCantidad
 * @property double $dPrecio
 * @property integer $bActivo
 * @property integer $bEliminado
 */
class Promocion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'promocion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nCantidad, dPrecio', 'required'),
			array('nCantidad', 'unique'),
			array('nCantidad, bActivo, bEliminado', 'numerical', 'integerOnly'=>true),
			array('dPrecio', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('idPromocion, nCantidad, dPrecio, bActivo', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPromocion' => 'Cod. Promocion',
			'nCantidad' => 'Cantidad',
			'dPrecio' => 'Precio por copia',
			'bActivo' => 'Activo',
		);
	}

    public function scopes() {
        return array(
            'activas'=>array(
                'condition'=>'bActivo = 1 && bEliminado = 0',
            ),
        );
    }

    public function defaultScope()
    {
        return array(
            'condition'=>'bEliminado = 0',
        );
    }

    public function aplica($cantidad)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'nCantidad<=:cantidad',
            'limit'=>1,
            'order'=>'ABS(nCantidad-:cantidad)',
            'params'=>array(':cantidad'=>$cantidad),
        ));
        return $this;
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

		$criteria->compare('idPromocion',$this->idPromocion);
		$criteria->compare('nCantidad',$this->nCantidad);
		$criteria->compare('dPrecio',$this->dPrecio);
		$criteria->compare('bActivo',$this->bActivo);

		$criteria->order = 'idPromocion DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Promocion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
