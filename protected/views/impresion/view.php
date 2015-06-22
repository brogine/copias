<?php
/* @var $this ImpresionController */
/* @var $model Impresion */
?>

<?php
$this->breadcrumbs=array(
	'Impresions'=>array('index'),
	$model->idImpresion,
);

$this->menu=array(
	array('label'=>'Administrar Impresion', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Impresion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Impresion', 'url'=>array('editar', 'id'=>$model->idImpresion), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Impresion', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idImpresion),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Ver Impresion #<?php echo $model->idImpresion; ?></h1>
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
		'idImpresion',
		'fFecha',
		'idUsuario',
	),
)); ?>