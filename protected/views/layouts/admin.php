<?php 
Yii::app()->bootstrap->register(); 
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/custom.admin.css');
date_default_timezone_set('America/Argentina/Buenos_Aires');
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

<body>

<div id="topbar" class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a href="<?=$this->createUrl('admin/index')?>" class="brand"><?=Yii::app()->name?></a>
            <ul class="pull-right nav" id="yw4">
                <li>
                    <a href="<?=$this->createUrl('admin/index')?>">
                        <i class="icon-home"></i> Inicio
                    </a>
                </li>
                <li>
                    <a href="<?=Yii::app()->homeUrl?>">
                        <i class="icon-home"></i> Ir al sitio
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" id="drop-config" data-toggle="dropdown" href="<?=$this->createUrl('admin/configuracion')?>">
                        <i class="icon-tasks"></i> Configuraci&oacute;n <span class="caret"></span>
                    </a>
                    <ul id="drop-configuracion" class="dropdown-menu" aria-labelledby="drop-config">
                        <li><a tabindex="-1" href="<?=$this->createUrl('admin/configuracion')?>"> Configuraci&oacute;n</a></li>
                        <?php if(in_array(Permiso::MODULO_PROMOCION, Yii::app()->user->permisos)) {?><li><a tabindex="-1" href="<?=$this->createUrl('promocion/index');?>"> Promociones</a></li><?php }?>
                        <?php if(in_array(Permiso::MODULO_ARTICULO, Yii::app()->user->permisos)) {?><li><a tabindex="-1" href="<?=$this->createUrl('articulo/index');?>"> Art&iacute;culos</a></li><?php }?>
                        <?php if(in_array(Permiso::MODULO_USUARIO, Yii::app()->user->permisos)) {?><li><a tabindex="-1" href="<?=$this->createUrl('usuario/index');?>"> Usuarios</a></li><?php }?>
                        <?php if(in_array(Permiso::MODULO_ROL, Yii::app()->user->permisos)) {?><li><a tabindex="-1" href="<?=$this->createUrl('rol/index');?>"> Roles de Usuarios</a></li><?php }?>
                        <?php if(in_array(Permiso::MODULO_SUCURSAL, Yii::app()->user->permisos)) {?><li><a tabindex="-1" href="<?=$this->createUrl('sucursal/index');?>"> Sucursales</a></li><?php }?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" id="drop-cuenta" data-toggle="dropdown" href="<?=$this->createUrl('usuario/editar',array('id'=>Yii::app()->user->Id));?>">
                        <i class="icon-user"></i> Mi cuenta <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="drop-cuenta">
                        <li><a tabindex="-1" href="<?=$this->createUrl('usuario/editarLocal');?>"><i class="icon-user"></i> Perfil</a></li>
                        <li><a tabindex="-1" href="<?=$this->createUrl('site/logout');?>"><i class="icon-off"></i> Salir (<?=Yii::app()->user->name?>)</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'brandLabel' => '',
    'htmlOptions'=>array('id'=>'main-menu'),
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbNav',
            'items' => array(
                //array('label'=>'Inicio', 'url'=>array('admin/index'), 'active'=>$this->id=='admin', 'visible'=>in_array(Permiso::MODULO_ADMIN, Yii::app()->user->permisos)),
                array('label'=>'Impresión', 'url'=>array('impresion/nuevo'), 'active'=>$this->id=='impresion', 'visible'=>in_array(Permiso::MODULO_IMPRESION, Yii::app()->user->permisos)),
                array('label'=>'Pedidos', 'url'=>array('pedido/index'), 'active'=>$this->id=='pedido', 'visible'=>in_array(Permiso::MODULO_PEDIDO, Yii::app()->user->permisos)),
                //array('label'=>'Promociones', 'url'=>array('promocion/index'), 'active'=>$this->id=='promocion', 'visible'=>in_array(Permiso::MODULO_PROMOCION, Yii::app()->user->permisos)),
                array('label'=>'Documentos', 'url'=>array('documento/index'), 'active'=>$this->id=='documento', 'visible'=>in_array(Permiso::MODULO_DOCUMENTO, Yii::app()->user->permisos)),
                //array('label'=>'Artículos', 'url'=>array('articulo/index'), 'active'=>$this->id=='articulo', 'visible'=>in_array(Permiso::MODULO_ARTICULO, Yii::app()->user->permisos)),
                /*array('label'=>'Administrar Usuarios', 'items'=>array(
                    array('label'=>'Usuarios', 'url'=>array('usuario/index'), 'active'=>$this->id=='usuario', 'visible'=>in_array(Permiso::MODULO_USUARIO, Yii::app()->user->permisos)),
                    array('label'=>'Roles de usuarios', 'url'=>array('rol/index'), 'active'=>$this->id=='rol', 'visible'=>in_array(Permiso::MODULO_ROL, Yii::app()->user->permisos)),
                )),*/
                array('label'=>'Administrar Instituciones', 'items'=>array(
                    array('label'=>'Instituciones', 'url'=>array('institucion/index'), 'active'=>$this->id=='institucion', 'visible'=>in_array(Permiso::MODULO_INSTITUCION, Yii::app()->user->permisos)),
                    array('label'=>'Cursos', 'url'=>array('curso/index'), 'active'=>$this->id=='curso', 'visible'=>in_array(Permiso::MODULO_CURSO, Yii::app()->user->permisos)),
                    array('label'=>'Carreras', 'url'=>array('carrera/index'), 'active'=>$this->id=='carrera', 'visible'=>in_array(Permiso::MODULO_CARRERA, Yii::app()->user->permisos)),
                    array('label'=>'Materia', 'url'=>array('materia/index'), 'active'=>$this->id=='materia', 'visible'=>in_array(Permiso::MODULO_MATERIA, Yii::app()->user->permisos)),
                    array('label'=>'Profesor', 'url'=>array('profesor/index'), 'active'=>$this->id=='profesor', 'visible'=>in_array(Permiso::MODULO_PROFESOR, Yii::app()->user->permisos)),
                )),
                array('label'=>'Reportes', 'url'=>array('reportes/index'), 'active'=>$this->id=='reportes', 'visible'=>in_array(Permiso::MODULO_REPORTES, Yii::app()->user->permisos)),
                //array('label'=>'Sucursales', 'url'=>array('sucursal/index'), 'active'=>$this->id=='sucursal', 'visible'=>in_array(Permiso::MODULO_SUCURSAL, Yii::app()->user->permisos)),
            ),
        ),
    ),
)); ?>
<div class="container content">
    <div class="row">
        <div class="span12">
            <?php
            echo $content;
            ?>
        </div>
    </div>

    <footer class="footer" style="text-align: center;">
        <hr/>
        <p class="text-right">Todos los derechos reservados | <a href="http://nasoft.com.ar">NA Soft</a></p>
    </footer>
</div>

</body>
</html>
