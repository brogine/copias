<?php
/* @var $this UsuarioController */
/* @var $model Usuario */
?>

<?php
$this->menu=array(
	array('label'=>'Administrar Usuarios', 'url'=>array('index'), 'linkOptions' => array('class'=>'muted')),
	array('label'=>'Nuevo Usuario', 'url'=>array('nuevo'), 'linkOptions' => array('class'=>'text-success')),
	array('label'=>'Editar Usuario', 'url'=>array('editar', 'id'=>$model->idUsuario), 'linkOptions' => array('class'=>'text-warning')),
	array('label'=>'Eliminar Usuario', 'url'=>'#', 'linkOptions'=>array('class'=>'text-error', 'submit'=>array('eliminar','id'=>$model->idUsuario),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<div class="row">
    <div class="span5">
        <h1>Ver Usuario #<?php echo $model->idUsuario . ' - ' . $model->sNombre . ' ' . $model->sApellido; ?></h1>
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

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'idUsuario',
		'sNombre',
		'sApellido',
		'sEmail',
		'nDocumento',
		'sTelefono',
		'sDomicilio',
		'bActivo:bool',
		'dUltimoLogin:fechaHora',
		array('name'=>'rol.sDescripcion', 'label'=>'Rol'),
	),
)); ?>