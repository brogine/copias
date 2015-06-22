<?php

class RolController extends Controller
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
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_ROL)',
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
		$model=new Rol;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Rol'])) {
			$model->attributes=$_POST['Rol'];
			$model->permisos = ($_POST['permiso'] == null) ? array() : $_POST['permiso'];

			if ($model->saveWithRelated(array('permisos'))) {
				$this->redirect(array('ver','id'=>$model->idRol));
			}
		}

		$modulos = CHtml::listData(Modulo::model()->findAll(), 'idModulo', 'sDescripcion');

		$this->render('create',array(
			'model'=>$model,
			'modulos'=>$modulos
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

		if (isset($_POST['Rol'])) {
			$model->attributes=$_POST['Rol'];
			if ($model->save()) {
				$model->deleteRelated(array('permisos'));

				$model->permisos = ($_POST['permiso'] == null) ? array() : $_POST['permiso'];
				$model->saveRelated(array('permisos'));

				$this->redirect(array('ver','id'=>$model->idRol));
			}
		}

		$modulos = CHtml::listData(Modulo::model()->findAll(), 'idModulo', 'sDescripcion');
		$permisos = array_keys(CHtml::listData( $model->permisos, 'idModulo' , 'idModulo'));

		$this->render('update',array(
			'model'=>$model,
			'modulos'=>$modulos,
			'permisos'=>$permisos
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
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
			}
		} else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Rol('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Rol'])) {
			$model->attributes=$_GET['Rol'];
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Rol the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Rol::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Rol $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='rol-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}