<?php
/* @var $this InstitucionController */
/* @var $model Institucion */
?>

<?php
$this->breadcrumbs=array(
	'Instituciones'=>array('index'),
	$model->idInstitucion,
);

$this->menu=array(
	array('label'=>'Administrar Institucion', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Institucion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Institucion', 'url'=>array('editar', 'id'=>$model->idInstitucion), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Institucion', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idInstitucion),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Ver Institucion #<?php echo $model->idInstitucion . ' - ' . $model->sDescripcion; ?></h1>
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
		'idInstitucion',
		'sDescripcion',
	),
)); ?>