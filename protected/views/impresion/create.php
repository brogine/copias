<?php
/* @var $this ImpresionController */
/* @var $model Impresion */
?>

<?php
$this->breadcrumbs=array(
	'Impresions'=>array('index'),
	'Nueva',
);

$this->menu=array(
	array('label'=>'Administrar Impresiones', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Nueva Impresion</h1>
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