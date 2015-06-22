<?php
/* @var $this CarreraController */
/* @var $model Carrera */
?>

<?php
$this->breadcrumbs=array(
	'Carreras'=>array('index'),
	$model->idCarrera=>array('view','id'=>$model->idCarrera),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Carreras', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Carrera', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Carrera', 'url'=>array('ver', 'id'=>$model->idCarrera), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Editar Carrera <?php echo $model->idCarrera; ?></h1>
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