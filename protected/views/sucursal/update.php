<?php
/* @var $this SucursalController */
/* @var $model Sucursal */
?>

<?php
$this->breadcrumbs=array(
	'Sucursales'=>array('index'),
	$model->idSucursal=>array('view','id'=>$model->idSucursal),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Sucursales', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Sucursal', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Sucursal', 'url'=>array('ver', 'id'=>$model->idSucursal), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Editar Sucursal <?php echo $model->idSucursal; ?></h1>
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

<?php $this->renderPartial('_form', array('model'=>$model)); ?>