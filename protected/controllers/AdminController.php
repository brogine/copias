<?php

class AdminController extends Controller
{
	public $layout='//layouts/admin';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
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
                'expression'=>'Yii::app()->user->hasAccessTo(Permiso::MODULO_ADMIN)',
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionConfiguracion(){

		if(isset($_POST['Configuracion'])) {
			foreach ($_POST['Configuracion'] as $key => $value) {
				$config = $this->loadConfig($key);
				$validation = false;

				switch ($config->sKey) {
					case 'sPath':
						$ultimo = substr($value['sValue'], -1);
						if($ultimo != "\\" && $ultimo != "/") {
							if(strpos($value['sValue'], "/")) {
								$value['sValue'] .= "/";
							} else {
								$value['sValue'] .= "\\";
							}
						}
						$validation = true;
						break;
					case 'vAnillado':
						if($this->isCurrency($value['sValue'])) {
							$validation = true;
						} else {
							Yii::app()->user->setFlash('error', 'El valor de anillado no es un precio válido.');
						}
						break;
					default:
						break;
				}

				if($validation){
					$config->sValue = $value['sValue'];
					$config->save();
					Yii::app()->cache->delete('vAnillado');
				}
			}
		}

		$configuraciones = Configuracion::model()->findAll();
		$this->render('configuracion', array('data'=>$configuraciones));
	}

	protected function loadConfig($id)
	{
		$model=Configuracion::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'La pagina solicitada no existe.');
		}
		return $model;
	}

	function isCurrency($number)
	{
		return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $number);
	}

	function is_filepath($path)
	{
	    $path = trim($path);
	    $path = str_replace('file:///', '', $path);
	    if(preg_match('/^[^*?"<>|:]*$/',$path)) return true;

        if(strpos($path, ":") == 1 && preg_match('/[a-zA-Z]/', $path[0]))
        {
            $tmp = substr($path,2);
            $bool = preg_match('/^[^*?"<>|:]*$/',$tmp);
            return ($bool == 1);
        }
        return false;
	    
	}
}
