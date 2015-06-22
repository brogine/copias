<?php
/* @var $this ProfesorController */
/* @var $model Profesor */
?>

<?php
$this->breadcrumbs=array(
	'Profesors'=>array('index'),
	'Nuevo',
);

$this->menu=array(
	array('label'=>'Administrar Profesores', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Nuevo Profesor</h1>
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