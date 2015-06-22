<?php
/* @var $this PromocionController */
/* @var $model Promocion */


$this->breadcrumbs=array(
	'Promocions'=>array('index'),
	'Administrar',
);

$this->menu=array(
	array('label'=>'Nueva Promocion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#promocion-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row">
    <div class="span5">
        <h1>Administrar Promociones</h1>
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

<p>
	Puedes opcionalmente ingresar un operador de comparaci&oacute;n (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
        &lt;&gt;</b>
o <b>=</b>) al comienzo de cada uno de los valores de b&uacute;squeda para especificar como debe hacerse la comparaci&oacute;n.
</p>

<?php echo CHtml::link('Busqueda avanzada','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'promocion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'idPromocion',
		'nCantidad:stock',
		'dPrecio:currency',
		array(
			'name'=>'bActivo',
			'type'=>'boolean',
			'filter' => array('0' => 'No', '1' => 'Si'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn'
		),
	),
)); ?>