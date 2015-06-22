<?php

class ArticuloController extends Controller
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
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_ARTICULO)',
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
		$model=new Articulo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Articulo'])) {
			$model->attributes=$_POST['Articulo'];
			if ($model->save()) {
				$this->redirect(array('ver','id'=>$model->idArticulo));
			}
		}

		$sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'sNombreSucursal');

		$this->render('create',array(
			'model'=>$model, 'sucursales' => $sucursales
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

		if (isset($_POST['Articulo'])) {
			$model->attributes=$_POST['Articulo'];
			if ($model->save()) {
				$this->redirect(array('ver','id'=>$model->idArticulo));
			}
		}

		$sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'sNombreSucursal');

		$this->render('update',array(
			'model'=>$model, 'sucursales' => $sucursales
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
			throw new CHttpException(400,'Pedido invalido. Porfavor, no repita este pedido nuevamente.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Articulo('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Articulo'])) {
			$model->attributes=$_GET['Articulo'];
		}

		$sucursales = CHtml::listData(Sucursal::model()->findAll(), 'idSucursal', 'sNombreSucursal');

		$this->render('index',array(
			'model'=>$model, 'sucursales'=>$sucursales
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Articulo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Articulo::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'La pagina solicitada no existe.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Articulo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='articulo-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}