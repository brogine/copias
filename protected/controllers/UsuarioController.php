<?php

class UsuarioController extends Controller
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
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_USUARIO)',
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
		$model=new Usuario('register');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Usuario'])) {
			$model->attributes=$_POST['Usuario'];
			if ($model->save()) {
				$this->redirect(array('ver','id'=>$model->idUsuario));
			}
		}

		$roles = CHtml::listData(Rol::model()->findAll(), 'idRol', 'sDescripcion');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');

		$this->render('create',array(
			'model'=>$model, 'roles' => $roles, 'carreras'=>$carreras
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
        $model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Usuario'])) {
			$model->attributes=$_POST['Usuario'];
			if ($model->save()) {
				$this->redirect(array('ver','id'=>$model->idUsuario));
			}
		}

        $model->newPassword = '';

		$roles = CHtml::listData(Rol::model()->findAll(), 'idRol', 'sDescripcion');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');

		$this->render('update',array(
			'model'=>$model, 'roles' => $roles, 'carreras'=>$carreras
		));
	}

	public function actionEditarLocal()
	{
		$model=$this->loadModel(Yii::app()->user->id);

		$model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Usuario'])) {
			$model->attributes=$_POST['Usuario'];
			if ($model->save()) {
				$this->redirect(array('ver','id'=>$model->idUsuario));
			}
		}

        $model->newPassword = '';

		$roles = CHtml::listData(Rol::model()->findAll(), 'idRol', 'sDescripcion');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');

		$this->render('update',array(
			'model'=>$model, 'roles' => $roles, 'carreras'=>$carreras
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
		$model=new Usuario('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Usuario'])) {
			$model->attributes=$_GET['Usuario'];
		}

		$roles = CHtml::listData(Rol::model()->findAll(), 'idRol', 'sDescripcion');

		$this->render('index',array(
			'model'=>$model, 'roles'=>$roles
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Usuario the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Usuario::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Usuario $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='usuario-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionEnviarEmailConfirmacion($id) {

		$model = $this->loadModel($id);

		$mailer = Yii::createComponent('application.extensions.mailer.EMailer');
		$mailer->Host = 'localhost';
		$mailer->IsSMTP();
		$mailer->From = Yii::app()->params['adminEmail'];
		$mailer->AddReplyTo(Yii::app()->params['adminEmail']);

		$mailer->AddAddress($model->sEmail, $model->sNombre);

		$mailer->FromName = Yii::app()->name;
		$mailer->CharSet = 'UTF-8';
		$mailer->Subject = 'Verifica tu email';

		$params = array(
			'siteUrl' => $this->createAbsoluteUrl('/'), 
			'actionUrl' => $this->createAbsoluteUrl('site/verificar', array(
				'activationKey'=>$model->getKey(),
				'id'=>$model->idUsuario,
			)),
		);
		$body = $this->renderPartial('application.views.emails.verificar', $params, true);
		$full = $this->renderPartial('application.views.layouts.email', array('content'=>$body), true);
		$mailer->MsgHTML($full);

		if ($mailer->Send()) {
			Yii::app()->user->setFlash('success', 'Email enviado con éxito.');
		} else {
			Yii::app()->user->setFlash('error', 'No se pudo enviar el email. ' . $mailer->ErrorInfo);
		}

		$model->scenario = 'update';
        $model->newPassword = '';

		$roles = CHtml::listData(Rol::model()->findAll(), 'idRol', 'sDescripcion');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');

		$this->render('update',array(
			'model'=>$model, 'roles' => $roles, 'carreras'=>$carreras
		));
	}
}