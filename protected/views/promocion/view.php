<?php
/* @var $this PromocionController */
/* @var $model Promocion */
?>

<?php
$this->breadcrumbs=array(
	'Promocions'=>array('index'),
	$model->idPromocion,
);

$this->menu=array(
	array('label'=>'Administrar Promociones', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Promocion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Promocion', 'url'=>array('editar', 'id'=>$model->idPromocion), 'linkOptions' => array('class'=>'text-warning')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Ver Promocion #<?php echo $model->idPromocion; ?></h1>
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
		'idPromocion',
		'nCantidad:stock',
		'dPrecio:currency',
		'bActivo:boolean',
	),
)); ?>