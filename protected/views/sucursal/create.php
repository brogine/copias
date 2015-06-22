<?php
/* @var $this SucursalController */
/* @var $model Sucursal */
?>

<?php
$this->breadcrumbs=array(
	'Sucursales'=>array('index'),
	'Nuevo',
);

$this->menu=array(
	array('label'=>'Administrar Sucursales', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Nueva Sucursal</h1>
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