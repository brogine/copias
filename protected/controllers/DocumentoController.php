<?php

class DocumentoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_DOCUMENTO)',
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionVer($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNuevo()
	{
		$model=new Documento;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Documento'])) {
			$model->attributes=$_POST['Documento'];
			$model->instituciones = ($model->institucionesRelated == null) ? array() : $model->institucionesRelated;
			$model->profesors = ($model->profesoresRelated == null) ? array() : $model->profesoresRelated;
			$model->materias = ($model->materiasRelated == null) ? array() : $model->materiasRelated;
			$model->carreras = ($model->carrerasRelated == null) ? array() : $model->carrerasRelated;
			$model->cursos = ($model->cursosRelated == null) ? array() : $model->cursosRelated;

			$model->dFechaAlta = new CDbExpression('NOW()');

			if ($model->saveWithRelated(array('instituciones', 'profesors', 'materias', 'carreras', 'cursos'))) {
				$model->sNombreArchivo = $model->idDocumento . ".pdf";
				$model->update(array('sNombreArchivo'));

				$this->redirect(array('ver','id'=>$model->idDocumento));
			}
		}

		$instituciones = CHtml::listData(Institucion::model()->findAll(), 'idInstitucion', 'sDescripcion');
		$materias = CHtml::listData(Materia::model()->findAll(), 'idMateria', 'sDescripcion');
		$profesores = CHtml::listData(Profesor::model()->findAll(), 'idProfesor', 'FullName');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
		$cursos = CHtml::listData(Curso::model()->findAll(), 'idCurso', 'sDescripcion');

		$model->bActivo = 0;

		$this->render('create',array(
			'model'=>$model, 'instituciones'=>$instituciones, 'materias' => $materias, 'profesores' => $profesores, 'carreras' => $carreras, 'cursos' => $cursos
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEditar($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Documento'])) {
			$model->attributes=$_POST['Documento'];
			if ($model->save()) {

                $model->deleteRelated(array('instituciones', 'profesors', 'materias', 'carreras', 'cursos'));
                $model->instituciones = ($model->institucionesRelated == null) ? array() : $model->institucionesRelated;
                $model->profesors = ($model->profesoresRelated == null) ? array() : $model->profesoresRelated;
                $model->materias = ($model->materiasRelated == null) ? array() : $model->materiasRelated;
                $model->carreras = ($model->carrerasRelated == null) ? array() : $model->carrerasRelated;
				$model->cursos = ($model->cursosRelated == null) ? array() : $model->cursosRelated;

                $model->saveRelated(array('instituciones', 'profesors', 'materias', 'carreras', 'cursos'));

				$this->redirect(array('ver','id'=>$model->idDocumento));
			}
		}

        foreach($model->profesors as $profesor) {
            $model->profesoresRelated[] = $profesor->idProfesor;
        }

        foreach($model->materias as $materia) {
            $model->materiasRelated[] = $materia->idMateria;
        }

        foreach($model->carreras as $carrera) {
            $model->carrerasRelated[] = $carrera->idCarrera;
        }

        foreach($model->cursos as $curso) {
            $model->cursosRelated[] = $curso->idCurso;
        }

        foreach ($model->instituciones as $institucion) {			
			$model->institucionesRelated[] = $institucion->idInstitucion;
		}

        $instituciones = CHtml::listData(Institucion::model()->findAll(), 'idInstitucion', 'sDescripcion');
		$materias = CHtml::listData(Materia::model()->findAll(), 'idMateria', 'sDescripcion');
		$profesores = CHtml::listData(Profesor::model()->findAll(), 'idProfesor', 'FullName');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
		$cursos = CHtml::listData(Curso::model()->findAll(), 'idCurso', 'sDescripcion');

		$this->render('update',array(
			'model'=>$model, 'instituciones' => $instituciones, 'materias' => $materias, 'profesores' => $profesores, 'carreras' => $carreras, 'cursos' => $cursos
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionEliminar($id)
	{
		if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			$model->deleteRelated(array('instituciones', 'profesors', 'materias', 'carreras', 'cursos'));
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
			}
		} else {
			throw new CHttpException(400,'Pedido invalido. Porfavor, no repita este pedido nuevamente.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Documento('search');

        $model->filtroInstituciones = isset($_POST['filtroInstituciones']) ? $_POST['filtroInstituciones'] : array();
        $model->filtroProfesores = isset($_POST['filtroProfesores']) ? $_POST['filtroProfesores'] : array();
        $model->filtroMaterias = isset($_POST['filtroMaterias']) ? $_POST['filtroMaterias'] : array();
        $model->filtroCarreras = isset($_POST['filtroCarreras']) ? $_POST['filtroCarreras'] : array();
        $model->filtroCursos = isset($_POST['filtroCursos']) ? $_POST['filtroCursos'] : array();
		
		if (isset($_GET['Documento'])) {
			$model->attributes=$_GET['Documento'];
		}

		$instituciones = CHtml::listData(Institucion::model()->findAll(), 'idInstitucion', 'sDescripcion');
        $materias = CHtml::listData(Materia::model()->findAll(), 'idMateria', 'sDescripcion');
        $profesores = CHtml::listData(Profesor::model()->findAll(), 'idProfesor', 'FullName');
        $carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
        $cursos = CHtml::listData(Curso::model()->findAll(), 'idCurso', 'sDescripcion');

		$this->render('index',array(
			'model'					=> $model,
			'filtroInstituciones' 	=> $model->filtroInstituciones,
        	'filtroProfesores' 		=> $model->filtroProfesores,
        	'filtroMaterias' 		=> $model->filtroMaterias,
        	'filtroCarreras' 		=> $model->filtroCarreras,
        	'filtroCursos' 			=> $model->filtroCursos,
        	'instituciones'			=> $instituciones,
        	'materias'				=> $materias,
        	'profesores'			=> $profesores,
        	'carreras'				=> $carreras,
        	'cursos'				=> $cursos
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Documento the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Documento::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'La pagina solicitada no existe.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Documento $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='documento-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionDatos() {
		if(isset($_POST['id'])) {
			$documento = $this->loadModel((int) str_replace('d', '', $_POST['id']));
			$body = "<p><strong>Código: </strong>" . $documento->idDocumento . "</p>";
			$body .="<p><strong>Precio unitario: </strong>$" . $documento->price. "</p>";
			$body .= "<p><strong>Autor: </strong>" . $documento->sAutor . "</p>";
			$body .= "<p><strong>Cant. Pág.: </strong>" . $documento->nPaginas . "</p>";
			$body .= "<p><strong>Sobre el libro: </strong>" . $documento->sSobre . "</p>";

			$body .= "<div class=\"row-fluid\">";
			if(count($documento->instituciones) > 0)
			{
				$body .= "<div class=\"span4\"><p><span class=\"label\">Instituciones:</span><ul>";
				foreach ($documento->instituciones as $inst) {
					$body .= "<li>" . $inst->sDescripcion . "</li>";
				}
				$body .= "</ul></p></div>";
			}
			if(count($documento->materias) > 0)
			{
				$body .= "<div class=\"span4\"><p><span class=\"label\">Materias:</span><ul>";
				foreach ($documento->materias as $mat) {
					$body .= "<li>" . $mat->sDescripcion . "</li>";
				}
				$body .= "</ul></p></div>";
			}
			if(count($documento->profesors) > 0)
			{
				$body .= "<div class=\"span4\"><p><span class=\"label\">Profesores:</span><ul>";
				foreach ($documento->profesors as $profe) {
					$body .= "<li>" . $profe->fullName . "</li>";
				}
				$body .= "</ul></p></div>";
			}
			if(count($documento->carreras) > 0)
			{
				$body .= "<div class=\"span4\"><p><span class=\"label\">Carreras:</span><ul>";
				foreach ($documento->carreras as $car) {
					$body .= "<li>" . $car->sDescripcion . "</li>";
				}
				$body .= "</ul></p></div>";
			}
			if(count($documento->cursos) > 0)
			{
				$body .= "<div class=\"span4\"><p><span class=\"label\">Cursos:</span><ul>";
				foreach ($documento->cursos as $cur) {
					$body .= "<li>" . $cur->sDescripcion . "</li>";
				}
				$body .= "</ul></p></div>";
			}
			$body .= "</div>";
			echo CJSON::encode(array("header"=>$documento->sTitulo,"body"=>$body));
		}
	}

	public function actionAbrir($ruta) {
		//header('Location: ' . $ruta);
		/*$nombre = substr($ruta, strripos($ruta, '/') + 1);
		header ("Content-Disposition: inline; filename=$nombre");
		header ("Content-Type: application/pdf");
		readfile($ruta);*/
		//$this->render('open', array('ruta'=>$ruta));
	}

}