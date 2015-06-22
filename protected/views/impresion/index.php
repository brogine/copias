<?php
/* @var $this ImpresionController */
/* @var $model Impresion */

$this->breadcrumbs=array(
	'Impresiones'=>array('index'),
	'Administrar',
);

$this->menu=array(
	array('label'=>'Nueva Impresion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
);

?>

<div class="row">
	<div class="span5">
		<h1>Administrar Impresiones</h1>
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

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'impresion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'afterAjaxUpdate' => 'reinstallDatePicker',
	'columns'=>array(
		'idImpresion',
		array(
			'name'=>'fFecha',
			'type'=>'fechaHora',
			'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model'=>$model, 
                'attribute'=>'fFecha', 
                'language' => 'es',
                'htmlOptions' => array(
                    'id' => 'datepicker_for_due_date',
                    'size' => '10',
                ),
                'defaultOptions' => array(
                    'showOn' => 'focus', 
                    'dateFormat' => 'dd/mm/yy',
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => true
                )
            ), 
            true),
		),
		array(
			'header'=>'Usuario',
			'name'=>'usuario.fullName'
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{view} {delete}',
		),
	),
)); 

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_for_due_date').datepicker($.datepicker.regional['es']);
}
");

?>