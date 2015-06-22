<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Empresa';
$this->breadcrumbs=array(
    'Sobre nosotros',
);
?>
<div class="row-fluid">
  	<div class="span9">
    	<img src="<?=Yii::app()->createUrl('images/foto-maquinaria-slider.jpg')?>" alt="" />
		<hr />
        <h1>Calidad. Rapidez. Experiencia. Piezas gráficas exclusivas, desarrolladas de principio a fin en un mismo lugar.</h1>    	
   	  	<p>Impresión de alta calidad de todo tipo de piezas gráficas. Con maquinaria de gran producción de la línea Konica-Minolta y más de 30 años de trayectoria en el rubro, habiendo pasado por los distintos sistemas de impresión, hoy concentramos nuestras energías en la producción de pequeñas tiradas exclusivas, incluyendo la personalización y la edición de revistas y libros por demanda. Fotolibros, etiquetas, catálogos, material para congresos, encuadernaciones especiales de presentaciones o balances, y todo otro producto que exija un acabado especial, son algunos de los muchos artículos que podemos producir.</p>
    </div>
	<div class="span3">
     	<a href='<?=Yii::app()->createUrl('site/register')?>'><img src="<?=Yii::app()->createUrl('images/registrate.jpg')?>" alt="" /></a>
    	<ul class="unstyled empresa-info">
        	<li>
            	<h2>Impresión general y diseño</h2>
            </li>
        	<li>
            	<h2>Impresión digital comercial</h2>
                <p>Afiches / Folletos / Volantes / Etiquetas / Papelería Institucional / Impresión Full Color y B&N - Sin necesidad de establecer un mínimo por tirada.</p>
            </li>
            <li>
            	<h2>Servicios de postimpresión</h2>
                <p>Anillados / Laminados / Encuadernación</p>
            </li>
            <li>
            	<h2>Impresión de datos variables</h2>
         		<p>Impresión de una pieza gráfica con personalización de datos. Ej.: cuadernos de comunicación con nombre de cada alumno</p>   
            </li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>