<?php
/* @var $this MateriaController */
/* @var $model Materia */
?>

<?php
$this->breadcrumbs=array(
	'Materias'=>array('index'),
	$model->idMateria=>array('view','id'=>$model->idMateria),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Materia', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Materia', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Materia', 'url'=>array('ver', 'id'=>$model->idMateria), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Editar Materia <?php echo $model->idMateria; ?></h1>
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