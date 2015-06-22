<?php

$this->breadcrumbs=array(
    'Aquí puedes buscar y agregar documentos para tu pedido! Ingresa un texto para buscar lo que quieras.',
);

$this->widget( 'ext.EChosen.EChosen' );

Yii::app()->clientScript->registerScript('search', "
function throttle(f, delay){ var timer = null; return function(){ var context = this, args = arguments; clearTimeout(timer); timer = window.setTimeout(function(){ f.apply(context, args); }, delay || 500); }; }
$('input#docFilter').keyup(throttle(function(){
  $.fn.yiiGridView.update('gvDocumentos', {
    data: $('#docFilter, #filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos').serialize()
  });
  return false;
}));
$('.chosen').chosen().change(function(){
    var finalData = $('#docFilter, #filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos').serialize();
    $.fn.yiiGridView.update('gvDocumentos', {
      data: finalData,
      url: '" . Yii::app()->baseUrl . "/site/documentos'
    });
    return false;
});
$('#reset').click(function(){
    $('#docFilter').val('');
    $('#filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos').val('').trigger('chosen:updated');
    var finalData = $('#docFilter, #filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos').serialize();
    $.fn.yiiGridView.update('gvDocumentos', {
      data: finalData,
      url: '" . Yii::app()->baseUrl . "/site/documentos'
    });
    return false;
});
");

?>
<div class="span3">

<?php
echo CHtml::textField('docFilter', '', array('placeholder'=>'Filtrar Código, Titulo o Autor', 'class'=>'span12'));

echo CHtml::label('Buscá por Institución:', 'filtroInstituciones');
echo CHtml::dropDownList('filtroInstituciones', $filtroInstituciones, $instituciones, array('multiple' => true, 'class'=>'span12 chosen'));

echo CHtml::label('Buscá por Profesor:', 'filtroProfesores');
echo CHtml::dropDownList('filtroProfesores', $filtroProfesores, $profesores, array('multiple' => true, 'class'=>'span12 chosen'));

echo CHtml::label('Buscá por Materias:', 'filtroMaterias');
echo CHtml::dropDownList('filtroMaterias', $filtroMaterias, $materias, array('multiple' => true, 'class'=>'span12 chosen'));

echo CHtml::label('Buscá por Carreras:', 'filtroCarreras');
echo CHtml::dropDownList('filtroCarreras', $filtroCarreras, $carreras, array('multiple' => true, 'class'=>'span12 chosen'));

echo CHtml::label('Buscá por Cursos:', 'filtroCursos');
echo CHtml::dropDownList('filtroCursos', $filtroCursos, $cursos, array('multiple' => true, 'class'=>'span12 chosen'));

?>
<hr>
<button type="reset" id="reset">Nueva B&uacute;squeda</button>

</div>
<div class="span9">
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'gvDocumentos',
    'type' => TbHtml::GRID_TYPE_BORDERED,
    'dataProvider' => $documentos,
    'columns'=>array(
        array(
            'name'=>'idDocumento',
            'header'=>'Código',
            'htmlOptions'=>array('class'=>'coddoc')
        ),
        'sTitulo',
        'profesores',
        'nPaginas',
        array(
            'name'=>'price',
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
            'htmlOptions'=>array('style'=>'width:50px'),
            'headerHtmlOptions'=>array('style'=>'width:50px'),
            'template'=>'{add}{details}',
            'buttons'=>array(
                'add' => array(
                    'label'=>'Agregar',
                    'imageUrl'=>Yii::app()->baseUrl.'/images/add.png',
                    'url'=>'"javascript:void(0)"',
                    'click'=>'function() { var $datos = $(this).closest("tr").children(); if(!isNaN(parseInt($datos[5].children[0].value))) { $.post( "'.Yii::app()->baseUrl.'/carrito/agregar", { id: $datos[0].innerHTML, type: "doc", quant: $datos[5].children[0].value } ).done(function(data) { $("#totalCarrito").html(data); }); $.gritter.add({ title: \'Agregado al carrito!\', text: \'Agregaste \' + $datos[5].children[0].value + \' copia/s de \' + $datos[1].innerHTML }); $datos[5].children[0].value = ""; } }',
                ),
                'details'=>array(
                    'label'=>'Ver Detalles',
                    'imageUrl'=>Yii::app()->baseUrl.'/images/mas.png',
                    'url'=>'"javascript:void(0)"',
                    'click'=> 'js: function(){ $.post( "'.Yii::app()->request->baseUrl.'/documento/datos", { id: $(this).closest("tr").children()[0].innerHTML }).done(function( data ) { var data = $.parseJSON(data); $(".modal-header h3").html(data.header); $(".modal-body").html(data.body); $("#documentosModal").modal(); }); }',
                )
            )
        ),
    ),
));

?>
</div>

<?php $this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'documentosModal',
    'header' => '',
    'content' => '',
    'footer' => array(
        TbHtml::button('Cerrar', array('data-dismiss' => 'modal')),
     ),
)); ?>