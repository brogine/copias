<?php
/* @var $this ProfesorController */
/* @var $model Profesor */
?>

<?php
$this->breadcrumbs=array(
	'Profesors'=>array('index'),
	$model->idProfesor=>array('view','id'=>$model->idProfesor),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Profesores', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Profesor', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Profesor', 'url'=>array('ver', 'id'=>$model->idProfesor), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Editar Profesor <?php echo $model->idProfesor; ?></h1>
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