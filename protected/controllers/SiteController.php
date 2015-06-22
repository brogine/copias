<?php

class SiteController extends Controller
{
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

    public function filters()
    {
        return array(
            'accessControl',
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
            /*array('allow',
                'actions'=>array('index', 'empresa', 'error', 'contacto', 'login', 'register', 'recuperar', 'verificar', 'logout' ),
                'users'=>array('?'),
            ),*/
            array('deny',
                'actions'=>array('productos', 'documentos'),
                'users'=>array('?'),
            ),
            array('allow',
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
		$this->pageTitle = 'Bienvenidos!';
		$this->render('index');
	}

    public function actionEmpresa() {
    	$this->pageTitle = 'Sobre nosotros';
        $this->render('pages/empresa');
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', array('error'=>$error));
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContacto()
	{
		$this->pageTitle = 'Contactanos';
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$mailer = Yii::createComponent('application.extensions.mailer.EMailer');
				$mailer->Host = 'localhost';
				$mailer->IsSMTP();
				$mailer->From = $model->email;
				$mailer->AddReplyTo(Yii::app()->params['adminEmail']);

				$mailer->AddAddress(Yii::app()->params['adminEmail'], 'admin');

				$mailer->FromName = Yii::app()->name;
				$mailer->CharSet = 'UTF-8';
				$mailer->Subject = base64_encode($model->subject);

				$mailer->MsgHTML($model->body);

				$mailer->Send();

				Yii::app()->user->setFlash('contact',' Gracias por contactarnos. Nos pondremos en contacto contigo lo antes posible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->baseUrl);

		$this->pageTitle = 'Ingresar';
		$model=new LoginForm;

		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			
			if($model->validate() && $model->login()){
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		
		$this->render('login',array('model'=>$model));
	}

	public function actionRegister()
	{
		if(!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->baseUrl);
		
		$this->pageTitle = 'Registrate';
		$model=new Usuario('register');

	    if(isset($_POST['Usuario']))
	    {
	        $model->attributes=$_POST['Usuario'];
	        $model->idRol = 2;
	        if($model->validate())
	        {
	            if($model->save()){
	            	if($this->sendEmail($model, 'verificar')) {
	            		Yii::app()->user->setFlash('success', 'Se ha enviado un email a tu correo con las instrucciones para activarlo. Revisa tus correos no deseados, puede que llegue ahí.');
	            	} else {
	            		Yii::app()->user->setFlash('error', 'Sus datos fueron guardados pero no se pudo enviar el email de confirmación. Hemos avisado a nuestro personal y se pondrá en contacto contigo para solucionar el inconveniente. Muchas gracias.');
	            	}
	            }
	        }
	    }

	    $roles = CHtml::listData(Rol::model()->front()->findAll(), 'idRol', 'sDescripcion');
	    $carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
	    
	    $this->render('register',array('model'=>$model, 'carreras'=>$carreras, 'roles'=>$roles));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionRecuperar()
	{
		if(!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->baseUrl);

		$this->pageTitle = 'Recuperar clave';
		$model=new RecoveryForm;

		if(isset($_GET['id']) && isset($_GET['activationKey'])) {
			$usuario = Usuario::model()->findByPk((int) $_GET['id']);
			if($usuario->verifyKey($_GET['activationKey'])){
				$model->scenario = 'reset';
				$model->idUsuario = $usuario->idUsuario;
			}
		}
		else {
			$model->scenario = 'recovery';
		}

		if(isset($_POST['RecoveryForm'])) {
			$model->attributes=$_POST['RecoveryForm'];

			if($model->validate()) {
				if ($model->scenario !== 'reset') {
                    $usuario = Usuario::model()->findByAttributes( array('sEmail' => $model->email));
                    if($usuario instanceof Usuario) {
                        if ($this->sendEmail($usuario, 'recuperar')) {
                            Yii::app()->user->setFlash('success', 'Se envio un email a tu casilla de correo para recuperar tu password.');
                            $this->redirect(array('login'));
                        } else {
                            Yii::app()->user->setFlash('error', 'No se pudo enviar el email. Prueba nuevamente o contacta al administrador.');
                        }
                    } else {
                        Yii::app()->user->setFlash('error', 'El email ingresado no se corresponde con un usuario registrado. Intente nuevamente.');
                    }
				} elseif (isset($_GET['id'])) {
					$usuario = Usuario::model()->findByPk((int) $_GET['id']);
					if($usuario instanceof Usuario) {
						$usuario->sPassword = CPasswordHelper::hashPassword($model->newPassword);
						$usuario->update(array('sPassword'));
	                    Yii::app()->user->setFlash('success', 'Se cambio su password correctamente.');
						$this->redirect(array('login'));
					}
				}
				$this->redirect(array('recuperar'));
			}
		}

		$this->render('recovery',array('model'=>$model));
	}

    public function actionReenviar() {
        if(!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->baseUrl);

        $this->pageTitle = 'Reenviar Email de confirmación';
        if(isset($_POST) && isset($_POST['email'])) {

            $model = Usuario::model()->findByAttributes( array('sEmail' => $_POST['email']));

            if($model instanceof Usuario) {
                if($this->sendEmail($model, 'verificar'))
                    Yii::app()->user->setFlash('success', 'Se envio un email a tu casilla de correo para verificar tu cuenta.');
                else
                    Yii::app()->user->setFlash('error', 'No se pudo enviar el email. Prueba nuevamente o contacta al administrador.');
            } else {
                Yii::app()->user->setFlash('error', 'El email ingresado no se corresponde con un usuario registrado. Intente nuevamente.');
            }
        }

        $this->render('reenviar');
    }

	public function actionVerificar()
	{
		if(!Yii::app()->user->isGuest)
			$this->redirect(Yii::app()->baseUrl);
		
		if(!isset($_GET['id']) || !isset($_GET['activationKey']))
			throw new CHttpException(400, 'Faltan datos para realizar su verificacion.');

		$this->pageTitle = 'Verificar Email';
		$model = Usuario::model()->findByPk((int) $_GET['id']);
		if($model->verifyKey($_GET['activationKey'])){
			$model->bActivo = true;
			$model->update(array('bActivo'));
			Yii::app()->user->setFlash('success', 'Tu email fue verificado correctamente.');
		}
		else {
			Yii::app()->user->setFlash('error', 'Tu email no pudo ser verificado porque los datos no corresponden.');
		}

		$this->redirect(array(Yii::app()->user->isGuest ? 'login' : 'admin/index'));
	}

	protected function sendEmail(Usuario $model, $mode) {

		$mailer = Yii::createComponent('application.extensions.mailer.EMailer');
		$mailer->Host = 'localhost';
		$mailer->IsSMTP();
		$mailer->From = Yii::app()->params['adminEmail'];
		$mailer->AddReplyTo(Yii::app()->params['adminEmail']);

        /*
         * Test
        $mailer->SMTPDebug = 1;
        $mailer->IsSMTP();
        $mailer->SMTPSecure = "ssl";
        $mailer->Host       = "smtp.gmail.com";
        $mailer->Port       = 465;
        $mailer->SMTPAuth   = true;
        $mailer->Username   = "cuenta@degmail.com";
        $mailer->Password   = "password";*/

        $mailer->AddAddress($model->sEmail, $model->sNombre);

		$mailer->FromName = Yii::app()->name;
		$mailer->CharSet = 'UTF-8';
		$params = null;
		$body = null;

		if ($mode == 'recuperar') {
			$mailer->Subject = 'Recupera tu password';
			$params = array(
				'siteUrl' => $this->createAbsoluteUrl('/'), 
				'actionUrl' => $this->createAbsoluteUrl('site/recuperar', array(
					'activationKey'=>$model->getKey(),
					'id'=>$model->idUsuario,
				)),
			);
			$body = $this->renderPartial('application.views.emails.recuperar', $params, true);
		} elseif ($mode == 'verificar') {
			$mailer->Subject = 'Verifica tu email';
			$params = array(
				'siteUrl' => $this->createAbsoluteUrl('/'), 
				'actionUrl' => $this->createAbsoluteUrl('site/verificar', array(
					'activationKey'=>$model->getKey(),
					'id'=>$model->idUsuario,
				)),
			);
			$body = $this->renderPartial('application.views.emails.verificar', $params, true);
		}
		else {
			return false;
		}
		
		$full = $this->renderPartial('application.views.layouts.email', array('content'=>$body), true);
		$mailer->MsgHTML($full);

		if ($mailer->Send()) {
			return true;
		} else {
			Yii::log($mailer->ErrorInfo, 'error');
			return false;
		}

	}

    public function actionProductos($artFilter = '') {
        if(Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->baseUrl);

        $this->pageTitle = 'Artículos de librería';
        $articulos = new Articulo('search');
        $articulos->unsetAttributes();
        $articulos->keyword = $artFilter;

        $this->render('productos', array('articulos' => $articulos->searchTextLive()));
    }

    public function actionDocumentos() {
        if(Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->baseUrl);

        $this->pageTitle = 'Documentos';
        $documentos = new Documento('search');
        $documentos->unsetAttributes();
        
    	$documentos->keyword = isset($_GET['docFilter']) ? $_GET['docFilter'] : '';
        $documentos->filtroInstituciones = isset($_GET['filtroInstituciones']) ? $_GET['filtroInstituciones'] : array();
        $documentos->filtroProfesores = isset($_GET['filtroProfesores']) ? $_GET['filtroProfesores'] : array();
        $documentos->filtroMaterias = isset($_GET['filtroMaterias']) ? $_GET['filtroMaterias'] : array();
        $documentos->filtroCarreras = isset($_GET['filtroCarreras']) ? $_GET['filtroCarreras'] : array();
        $documentos->filtroCursos = isset($_GET['filtroCursos']) ? $_GET['filtroCursos'] : array();

        $instituciones = CHtml::listData(Institucion::model()->findAll(), 'idInstitucion', 'sDescripcion');
		$materias = CHtml::listData(Materia::model()->findAll(), 'idMateria', 'sDescripcion');
		$profesores = CHtml::listData(Profesor::model()->findAll(), 'idProfesor', 'FullName');
		$carreras = CHtml::listData(Carrera::model()->findAll(), 'idCarrera', 'sDescripcion');
		$cursos = CHtml::listData(Curso::model()->findAll(), 'idCurso', 'sDescripcion');

        $this->render('documentos', 
        	array(
        		'documentos' => $documentos->activos()->searchTextLive(), 
        		'materias' => $materias, 
        		'profesores' => $profesores, 
        		'carreras' => $carreras, 
        		'cursos' => $cursos,
        		'instituciones' => $instituciones,
        		'filtroProfesores' => $documentos->filtroProfesores,
        		'filtroMaterias' => $documentos->filtroMaterias,
        		'filtroCarreras' => $documentos->filtroCarreras,
        		'filtroCursos' => $documentos->filtroCursos,
        		'filtroInstituciones' => $documentos->filtroInstituciones
        	)
        );
    }

    public function actionMisDatos() {
        if(Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->baseUrl);

        $this->pageTitle = 'Mis Datos';
        $model=Usuario::model()->findByPk(Yii::app()->user->getId());
        $model->scenario = 'update';

        if (isset($_POST['Usuario'])) {
            $model->attributes=$_POST['Usuario'];
            $model->save();
        }
        $model->newPassword = "";
        $model->rePassword = "";

        $this->render('datos',array(
            'model'=>$model
        ));
    }

    public function actionMisPedidos() {
        if(Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->baseUrl);

        $this->pageTitle = 'Mis Pedidos';
        $pedidos = new Pedido('search');
        $pedidos->unsetAttributes();
        $pedidos->idUsuario = Yii::app()->user->getId();

        $this->render('pedidos',array(
            'pedidos'=>$pedidos
        ));
    }

}