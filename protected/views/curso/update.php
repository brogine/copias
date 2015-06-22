<?php
/* @var $this CursoController */
/* @var $model Curso */
?>

<?php
$this->breadcrumbs=array(
	'Cursos'=>array('index'),
	$model->idCurso=>array('view','id'=>$model->idCurso),
	'Editar',
);

$this->menu=array(
	array('label'=>'Administrar Curso', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Curso', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Ver Curso', 'url'=>array('ver', 'id'=>$model->idCurso), 'linkOptions' => array('class'=>'text-info')),
);
?>

<div class="row">
	<div class="span5">
		<h1>Editar Curso <?php echo $model->idCurso; ?></h1>
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