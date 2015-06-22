<?php
/* @var $this MateriaController */
/* @var $model Materia */
?>

<?php
$this->breadcrumbs=array(
	'Materias'=>array('index'),
	$model->idMateria,
);

$this->menu=array(
	array('label'=>'Administrar Materias', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Materia', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Materia', 'url'=>array('editar', 'id'=>$model->idMateria), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Materia', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idMateria),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Ver Materia #<?php echo $model->idMateria . ' - ' . $model->sDescripcion; ?></h1>
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
		'idMateria',
		'sDescripcion',
	),
)); ?>