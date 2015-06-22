<?php
/* @var $this ProfesorController */
/* @var $model Profesor */


$this->breadcrumbs=array(
	'Profesors'=>array('index'),
	'Administrar',
);

$this->menu=array(
	array('label'=>'Nuevo Profesor', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#profesor-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row">
    <div class="span5">
        <h1>Administrar Profesores</h1>
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
	'id'=>'profesor-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'idProfesor',
		'sNombre',
		'sApellido',
		'sTelefono',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>