<?php
/* @var $this ProfesorController */
/* @var $model Profesor */
?>

<?php
$this->breadcrumbs=array(
	'Profesors'=>array('index'),
	$model->idProfesor,
);

$this->menu=array(
	array('label'=>'Administrar Profesores', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Profesor', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Profesor', 'url'=>array('editar', 'id'=>$model->idProfesor), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Profesor', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idProfesor),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Ver Profesor #<?php echo $model->idProfesor . ' - ' . $model->sNombre . ' ' . $model->sApellido; ?></h1>
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
		'idProfesor',
		'sNombre',
		'sApellido',
		'sTelefono',
	),
)); ?>