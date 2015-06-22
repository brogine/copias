<?php
/* @var $this InstitucionController */
/* @var $model Institucion */
?>

<?php
$this->breadcrumbs=array(
	'Instituciones'=>array('index'),
	$model->idInstitucion=>array('view','id'=>$model->idInstitucion),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Instituciones', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nueva Institucion', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Institucion', 'url'=>array('ver', 'id'=>$model->idInstitucion), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Editar Institucion <?php echo $model->idInstitucion; ?></h1>
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