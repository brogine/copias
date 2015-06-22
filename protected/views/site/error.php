<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="hero-unit center">
	<h1>Ha ocurrido un error <small><font face="Tahoma" color="red">Error <?php echo $error['code']; ?></font></small></h1>
	<br />
	<p><?php echo CHtml::encode($error['message']); ?></p>
	<p><b>Ir al inicio:</b></p>
	<a href="<?=Yii::app()->baseUrl?>" class="btn btn-large btn-info"><i class="icon-home icon-white"></i> Inicio</a>
</div>