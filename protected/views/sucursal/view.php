<?php
/* @var $this SucursalController */
/* @var $model Sucursal */
?>

<?php
$this->breadcrumbs=array(
	'Sucursales'=>array('index'),
	$model->idSucursal,
);

$this->menu=array(
	array('label'=>'Administrar Sucursales', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Sucursal', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Sucursal', 'url'=>array('editar', 'id'=>$model->idSucursal), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Sucursal', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idSucursal),'confirm'=>'Seguro desea eliminar este item?')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Ver Sucursal #<?php echo $model->idSucursal . ' - ' . $model->sNombreSucursal; ?></h1>
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
		'idSucursal',
		'sNombreSucursal',
		'sDomicilio',
		'sTelefono',
	),
)); ?>