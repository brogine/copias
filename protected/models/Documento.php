<?php

/**
 * This is the model class for table "documento".
 *
 * The followings are the available columns in table 'documento':
 * @property integer $idDocumento
 * @property string $dFechaAlta
 * @property string $sTitulo
 * @property integer $nStock
 * @property integer $bActivo
 * @property string $sSobre
 * @property string $sObservaciones
 * @property integer $nPaginas
 * @property string $sNombreArchivo
 * @property string $sAutor
 * @property double $nPrecioEspecial
 *
 * The followings are the available model relations:
 * @property Institucion[] $instituciones
 * @property Materia[] $materias
 * @property Profesor[] $profesors
 * @property Pedido[] $pedidos
 * @property Carrera[] $carreras
 * @property Curso[] $cursos
 */
class Documento extends CActiveRecord implements IECartPosition
{
	public $institucionesRelated;
	public $profesoresRelated;
	public $materiasRelated;
	public $carrerasRelated;
	public $cursosRelated;
    public $keyword;
    public $filtroInstituciones;
    public $filtroProfesores;
    public $filtroMaterias;
    public $filtroCarreras; 
    public $filtroCursos;

    private $pedidoAnilladosTmp = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'documento';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sTitulo, nPaginas', 'required'),
			array('nStock, bActivo, nPaginas', 'numerical', 'integerOnly'=>true),
			array('nPrecioEspecial', 'numerical'),
			array('sTitulo', 'length', 'max'=>500),
			array('sSobre, sObservaciones', 'length', 'max'=>255),
			array('sNombreArchivo, sAutor', 'length', 'max'=>45),
			array('dFechaAlta, filtroInstituciones, filtroProfesores, filtroMaterias, filtroCarreras, filtroCursos, institucionesRelated, profesoresRelated, materiasRelated, carrerasRelated, cursosRelated', 'safe'),
            array('keyword','safe', 'on'=>'search'),
			array('idDocumento, sTitulo', 'safe', 'on'=>'search'),
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
			'instituciones' => array(self::MANY_MANY, 'Institucion', 'documentoinstitucion(idDocumento, idInstitucion)'),
			'materias' => array(self::MANY_MANY, 'Materia', 'documentomateria(idDocumento, idMateria)'),
			'profesors' => array(self::MANY_MANY, 'Profesor', 'documentoprofesor(idDocumento, idProfesor)'),
			'pedidos' => array(self::MANY_MANY, 'Pedido', 'pedidodocumento(idDocumento, idPedido)'),
			'carreras' => array(self::MANY_MANY, 'Carrera', 'documentocarrera(idDocumento, idCarrera)'),
			'cursos' => array(self::MANY_MANY, 'Curso', 'documentocurso(idDocumento, idCurso)'),
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
			'idDocumento' => 'Código',
			'dFechaAlta' => 'Fecha de Creacion',
			'sTitulo' => 'Titulo',
			'nStock' => 'Stock',
			'bActivo' => 'Activo',
			'sSobre' => 'Sobre el libro',
			'sObservaciones' => 'Observaciones',
			'nPaginas' => 'Cant. Pág.',
			'sNombreArchivo' => 'Nombre Archivo',
			'sAutor' => 'Autor',
			'institucionesRelated' => 'Instituciones Relacionadas',
			'profesoresRelated' => 'Profesores Relacionados',
			'materiasRelated' => 'Materias Relacionadas',
			'carrerasRelated' => 'Carreras Relacionadas',
			'cursosRelated' => 'Cursos Relacionados',
            'price'=>'Precio',
            'nPrecioEspecial'=>'Precio Especial',
            'cantidad'=>'Cant.'
		);
	}

    public function getId(){
        return 'd'.$this->idDocumento;
    }

    public function getPrice(){
    	if($this->nPrecioEspecial > 0){
    		return $this->nPrecioEspecial;
    	}
    	else{
    		$promocion = Promocion::model()->activas()->aplica($this->nPaginas)->find();
	        if(isset($promocion)) {
	            return $promocion->dPrecio * $this->nPaginas;
	        }
	    }
	    return 0;
    }

    public function getExtra() {
    	$valorAnillado=Configuracion::PrecioAnillado();
    	return $this->pedidoAnilladosTmp * $valorAnillado;
    }

    public function getAnilladosTmp() {
    	return $this->pedidoAnilladosTmp;
    }

    public function setAnilladosTmp($value) {
    	$this->pedidoAnilladosTmp = (int) $value;
    }

    public function getProfesores(){
    	$profesores = $this->profesors;
    	$result = '';
    	foreach ($profesores as $profesor) {
    		$result .= $profesor->sNombre . ' ' . $profesor->sApellido . ', ';
    	}
    	return $result;
    }

    public function getPhysicalLocation()
    {
    	$dependency = new CDbCacheDependency('SELECT sValue FROM configuracion WHERE sKey = "sPath"');
    	return Configuracion::model()->cache(7200, $dependency)->findByAttributes(array('sKey'=>'sPath'))->sValue . ($this->idDocumento . ".pdf");
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

		$criteria->compare('idDocumento',$this->idDocumento);
		$criteria->compare('sTitulo',$this->sTitulo, true);
		$criteria->compare('nPaginas',$this->nPaginas);
		$criteria->compare('sObservaciones',$this->sObservaciones,true);

		$criteria->order = 'idDocumento DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getCssClass()
    {
    	return ($this->bActivo == 0 ? 'error' : '');
    }

    public function scopes() {
        return array(
            'activos'=>array(
                'condition'=>'bActivo = 1',
            ),
        );
    }

    public function searchTextLive()
    {
	    $criteria = new CDbCriteria;
        $criteria->select = 't.*';

        if(count($this->filtroMaterias) > 0)
        	$criteria->join = ' INNER JOIN documentomateria dm ON t.`idDocumento` = dm.idDocumento AND dm.idMateria IN (' . implode(',', $this->filtroMaterias) . ')';
    	if(count($this->filtroInstituciones) > 0)
        	$criteria->join .= ' INNER JOIN documentoinstitucion di ON t.`idDocumento` = di.idDocumento AND di.idInstitucion IN (' . implode(',', $this->filtroInstituciones) . ')';
    	if(count($this->filtroProfesores) > 0)
        	$criteria->join .= ' INNER JOIN documentoprofesor dp ON t.`idDocumento` = dp.idDocumento AND dp.idProfesor IN (' . implode(',', $this->filtroProfesores) . ')';
        if(count($this->filtroCarreras) > 0)
        	$criteria->join .= ' INNER JOIN documentocarrera dc ON t.`idDocumento` = dc.`idDocumento` AND dc.idCarrera IN (' . implode(',', $this->filtroCarreras) . ')';
        if(count($this->filtroCursos) > 0)
        	$criteria->join .= ' INNER JOIN documentocurso dcc ON t.`idDocumento` = dcc.`idDocumento` AND dcc.idCurso IN (' . implode(',', $this->filtroCursos) . ')';
        
        if(!empty($this->keyword)) {

        	if (preg_match ("/^([0-9]+)$/", $this->keyword)) {
        		$criteria->compare('t.`idDocumento`', $this->keyword);
        	} else {
        		//$keywordMatchExploded = explode(' ', trim($this->keyword));

    			$condition = '(';

        		/*for ($i=0; $i < count($keywordMatchExploded); $i++) {
        			$condition .= "t.`sTitulo` LIKE '%" . $keywordMatchExploded[$i] . "%'";
        			
        			if($i < (count($keywordMatchExploded) - 1)) {
        				$condition .= ' AND ';
        			}
        		}*/
        		$condition .= "t.`sTitulo` LIKE '%" . $this->keyword . "%'";

	        	$condition .= ") OR t.`sAutor` LIKE '%" . $this->keyword . "%'";

				$criteria->condition = $condition;
        	}
        }

        $criteria->compare('t.idDocumento',$this->idDocumento);
		$criteria->compare('t.sTitulo',$this->sTitulo, true);
		$criteria->compare('t.nPaginas',$this->nPaginas);
		$criteria->compare('t.sObservaciones',$this->sObservaciones,true);

	    $criteria->group='t.idDocumento';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Documento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getFullDescripcion()
    {
        return $this->idDocumento.' - '.$this->sTitulo.' ( '.$this->sAutor.' )';
    }
}
