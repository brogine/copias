<?php
/* @var $this CursoController */
/* @var $model Curso */
?>

<?php
$this->breadcrumbs=array(
	'Cursos'=>array('index'),
	$model->idCurso,
);

$this->menu=array(
	array('label'=>'Administrar Curso', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Curso', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Curso', 'url'=>array('editar', 'id'=>$model->idCurso), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Curso', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idCurso),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Ver Curso #<?php echo $model->idCurso; ?></h1>
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
		'idCurso',
		'sDescripcion',
	),
)); ?>