<?php

$this->breadcrumbs=array(
    'Aquí puedes buscar y agregar productos para tu pedido! Ingresa un texto para buscar lo que quieras.',
);

Yii::app()->clientScript->registerScript('search', "
    function throttle(f, delay){
        var timer = null;
        return function(){
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = window.setTimeout(function(){
                f.apply(context, args);
            },
            delay || 500);
        };
    }
    $('input#artFilter').keyup(throttle(function(){
      $.fn.yiiGridView.update('gvArticulos', {
      data: $(this).serialize()
      });
      return false;
    }));
    ");

echo CHtml::textField('artFilter', '', array('placeholder'=>'Filtrar Código, Descripcion o Precio', 'class'=>'span6'));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'gvArticulos',
    'type' => TbHtml::GRID_TYPE_BORDERED,
    'dataProvider' => $articulos,
    'columns'=>array(
        array(
            'name'=>'idArticulo',
            'header'=>'Código',
            'value'=>'"a".$data->idArticulo',
            'htmlOptions'=>array('class'=>'codart')
        ),
        'sDescripcion',
        array(
            'name'=>'nPrecio',
            'type'=>'currency',
            'htmlOptions'=>array('class'=>'importe')
        ),
        array(
            'name'=>'cantidad',
            'type'=>'raw',
            'value'=>'CHtml::textField("quant", "", array("style"=>"width:40px"))',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width:25px'),
            'headerHtmlOptions'=>array('style'=>'width:25px'),
            'template'=>'{add}',
            'buttons'=>array(
                'add' => array(
                    'label'=>'Agregar',
                    'imageUrl'=>Yii::app()->baseUrl.'/images/add.png',
                    'url'=>'"javascript:void(0)"',
                    'click'=>'function() { var $datos = $(this).closest("tr").children(); if(!isNaN(parseInt($datos[3].children[0].value))) { $.post( "'.Yii::app()->baseUrl.'/carrito/agregar", { id: $datos[0].innerHTML.replace("a",""), type: "art", quant: $datos[3].children[0].value }).done(function(data) { $("#totalCarrito").html(data); }); $.gritter.add({ title: \'Agregado al carrito!\', text: \'Agregaste \' + $datos[3].children[0].value + \' unidad/es de \' + $datos[1].innerHTML }); $datos[3].children[0].value = ""; } }',
                ),
            )
        ),
    ),
));

?>
