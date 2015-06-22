<?php
/* @var $this RolController */
/* @var $model Rol */
?>

<?php
$this->breadcrumbs=array(
	'Roles'=>array('index'),
	$model->idRol=>array('view','id'=>$model->idRol),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Roles', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Rol', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Rol', 'url'=>array('ver', 'id'=>$model->idRol), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Editar Rol <?php echo $model->idRol; ?></h1>
    </div>
    <div class="span7">
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items'=>$this->menu,
            'htmlOptions'=>array('class'=>'nav nav-pills'),
        ));
        ?>
    </div>
</div>

<?php $this->renderPartial('_form', array('model'=>$model, 'modulos'=>$modulos,	'permisos'=>$permisos)); ?>