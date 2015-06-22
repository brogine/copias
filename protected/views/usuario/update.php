<?php
/* @var $this UsuarioController */
/* @var $model Usuario */
?>

<?php
$this->breadcrumbs=array(
	'Usuarios'=>array('index'),
	$model->idUsuario=>array('view','id'=>$model->idUsuario),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Usuarios', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Usuario', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Usuario', 'url'=>array('ver', 'id'=>$model->idUsuario), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Editar Usuario <?php echo $model->idUsuario; ?></h1>
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

<?php $this->renderPartial('_form', array('model'=>$model, 'roles' => $roles, 'carreras'=>$carreras)); ?>