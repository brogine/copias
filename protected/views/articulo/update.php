<?php
/* @var $this ArticuloController */
/* @var $model Articulo */
?>

<?php
$this->breadcrumbs=array(
	'Articulos'=>array('index'),
	$model->idArticulo=>array('view','id'=>$model->idArticulo),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Articulos', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Articulo', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Articulo', 'url'=>array('ver', 'id'=>$model->idArticulo), 'linkOptions' => array('class'=>'text-info')),
);
?>
<div class="row">
	<div class="span5">
		<h1>Editar Articulo <?php echo $model->idArticulo; ?></h1>
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