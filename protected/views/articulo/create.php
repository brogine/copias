<?php
/* @var $this ArticuloController */
/* @var $model Articulo */
?>

<?php
$this->breadcrumbs=array(
	'Articulos'=>array('index'),
	'Nuevo',
);

$this->menu=array(
	array('label'=>'Administrar Articulos', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
);
?>
<div class="row">
	<div class="span5">
		<h1>Nuevo Articulo</h1>
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

<?php $this->renderPartial('_form', array('model'=>$model, 'sucursales' => $sucursales)); ?>