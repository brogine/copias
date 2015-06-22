<?php
Yii::app()->bootstrap->register();
$cs = Yii::app()->clientScript;
$cs->coreScriptPosition = CClientScript::POS_END;
$cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.gritter.min.js', CClientScript::POS_END);
$cs->registerCssFile(Yii::app()->baseUrl . '/css/custom.css');
$cs->registerCssFile(Yii::app()->baseUrl . '/css/jquery.gritter.css');
$cs->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,300italic,600italic,700,700italic,800,800italic');
date_default_timezone_set('America/Argentina/Buenos_Aires');

$actualPage = Yii::app()->getController()->getAction()->controller->action->id; 
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="">
	<div class="container">
        <div class="head">
            <div class="row-fluid">
                <div class="span12">
                    <div class="span6">
                        <h1 id="mainlogo"><a href="<?=Yii::app()->createUrl('site');?>" title="Icono Ideas"></a></h1>
                    </div>
                    <div class="span6 text-right" style="margin-top:15px;">
                      <?php
                        if(Yii::app()->user->isGuest):
                      ?>
                      <a class="login pull-right action" href="<?=Yii::app()->createUrl('site/login')?>">
                        Ingresar
                      </a>
                      <a class="login pull-right action" href="<?=Yii::app()->createUrl('site/register')?>">
                        Registrarse
                      </a>
                      <?php
                        else:
                      ?>
                      <div class="btn-group pull-right">
                        <?php if(in_array(Permiso::MODULO_ADMIN, Yii::app()->user->permisos)): ?>
                        <a class="btn action" href="<?=Yii::app()->createUrl('admin/index')?>">
                          <i class="icon-wrench"></i> Ir al admin
                        </a>
                        <?php endif; ?>
                        <a class="btn action" href="<?=Yii::app()->createUrl('carrito/index')?>">
                          <i class="icon-shopping-cart"></i> <span id="totalCarrito">$<?=Yii::app()->shoppingCart->getCost()?></span>
                        </a>
                        <a class="btn dropdown-toggle action" data-toggle="dropdown" href="<?=Yii::app()->createUrl('site/index')?>">
                          <i class="icon-user"></i> <?=Yii::app()->user->getName()?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu text-center">
                          <li><a href="<?=Yii::app()->createUrl('site/misDatos')?>"><i class="icon-wrench"></i> Mis Datos</a></li>
                          <li class="divider"></li>
                          <li><a href="<?=Yii::app()->createUrl('site/misPedidos')?>"><i class="icon-wrench"></i> Mis Pedidos</a></li>
                          <li class="divider"></li>
                          <li><a href="<?=Yii::app()->createUrl('site/logout')?>"><i class="icon-share"></i> Salir</a></li>
                        </ul>
                      </div>
                      <?php
                        endif;
                      ?>
                    </div>
                </div>
            </div>
        
            <div class="navbar">
              <div class="navbar-inner">
                <div class="container">
                  <ul class="nav" role="menu">
                    <li role="menuitem"><a tabindex="-1" href="<?=Yii::app()->createUrl('site')?>" class="<?=(($actualPage=='index')?'active':'')?>">Inicio</a></li>
                    <li role="menuitem"><a tabindex="-1" href="<?=Yii::app()->createUrl('site/empresa')?>" class="<?=(($actualPage=='empresa')?'active':'')?>">Empresa</a></li>
                    <li role="menuitem"><a tabindex="-1" href="<?=Yii::app()->createUrl('site/contacto')?>" class="<?=(($actualPage=='contacto')?'active':'')?>">Contacto</a></li>
                    <?php if(!Yii::app()->user->isGuest): ?> 
                    <!--<li role="menuitem"><a tabindex="-1" href="<?php /*echo Yii::app()->createUrl('site/productos');*/ ?>">Productos de librería</a></li>-->
                    <li role="menuitem"><a tabindex="-1" href="<?=Yii::app()->createUrl('site/documentos')?>" class="<?=(($actualPage=='documentos')?'active':'')?>">Documentos</a></li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
        
        </div>
        
        <?php 
        if(isset($this->breadcrumbs) && count($this->breadcrumbs) > 0):
            echo TbHtml::breadcrumbs($this->breadcrumbs);
        endif;
        ?>
        
       	<div class="container" id="the_content"><?= $content ?></div> 
        
        <div class="footer">
			<div class="border-bottom"><img src="<?=Yii::app()->createUrl('images/konica-minolta.png')?>" alt="Impresión de calidad" /></div>
            <div class="contact-data"><i class="icon-map-marker icon-black"></i> Garay 3648 / Mar del Plata / Argentina  <i class="icon-phone icon-black"></i> Teléfono: [54] 223 457 2717  <i class="icon-envelope icon-black"></i>    info@iconoideas.com.ar    <i class="icon-facebook-sign"></i>  Icono Ideas Impresión de Alta Calidad  </div>
            <p class="copyright">
                <strong>Icono Ideas 2014.</strong> Todos los derechos reservados |  Desarrollado por <a href="http://nasoft.com.ar">NA Soft</a>
            </p>
        </div>
	</div>
</body>
</html>
