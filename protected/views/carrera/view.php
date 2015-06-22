<?php
/* @var $this CarreraController */
/* @var $model Carrera */
?>

<?php
$this->breadcrumbs=array(
	'Carreras'=>array('index'),
	$model->idCarrera,
);

$this->menu=array(
	array('label'=>'Administrar Carreras', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Carrera', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Carrera', 'url'=>array('editar', 'id'=>$model->idCarrera), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Carrera', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idCarrera),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Ver Carrera #<?php echo $model->idCarrera; ?></h1>
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
		'idCarrera',
		'sDescripcion',
	),
)); ?>