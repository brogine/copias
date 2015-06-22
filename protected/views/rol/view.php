<?php
/* @var $this RolController */
/* @var $model Rol */
?>

<?php
$this->breadcrumbs=array(
	'Roles'=>array('index'),
	$model->idRol,
);

$this->menu=array(
	array('label'=>'Administrar Roles', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Rol', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Rol', 'url'=>array('editar', 'id'=>$model->idRol), 'linkOptions' => array('class'=>'text-warning')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Ver Rol #<?php echo $model->idRol . ' - ' . $model->sDescripcion; ?></h1>
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

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'idRol',
		'sDescripcion',
		'bPermitidoFront:bool',
	),
)); ?>