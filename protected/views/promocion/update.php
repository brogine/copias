<?php
/* @var $this PromocionController */
/* @var $model Promocion */
?>

<?php
$this->breadcrumbs=array(
	'Promocions'=>array('index'),
	$model->idPromocion=>array('view','id'=>$model->idPromocion),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Promociones', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Promocion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Promocion', 'url'=>array('ver', 'id'=>$model->idPromocion), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Editar Promocion <?php echo $model->idPromocion; ?></h1>
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