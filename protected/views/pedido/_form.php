<?php
/* @var $this PedidoController */
/* @var $model Pedido */
/* @var $form TbActiveForm */

?>

<div class="form">

    <div class="row">

        <div class="span6">

        <?php 

        if(Yii::app()->user->hasFlash('error')) {
            echo TbHtml::alert(TbHtml::ALERT_COLOR_ERROR, Yii::app()->user->getFlash('error'));
        }

        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'pedido-form',
            'enableAjaxValidation'=>false,
        )); ?>

        <p class="help-block">Los campos con <span class="required">*</span> son requeridos.</p>

            <input type="hidden" name="id" id="id" value="<?=$model->isNewRecord ? 0 : $model->idPedido?>" />

            <?php echo $form->errorSummary($model); ?>

            <?php $this->widget( 'ext.EChosen.EChosen' ); ?>

            <?php echo $form->dropDownListControlGroup($model,'idUsuario', $usuarios,array('span'=>5, 'empty'=>'Cliente Mostrador', 'class'=>'chosen')); ?>

            <?php
            if(!$model->isNewRecord):
            ?>

            <div class="control-group">
                <?php echo $form->labelEx($model,'dFechaPedido', array('class'=>'control-label')); ?>
                <div class="controls">
                    <span class="span5"><b><?=$model->dFechaPedido?></b></span>
                </div>
            </div>
            <br>

            <div class="control-group">
                <?php echo $form->labelEx($model,'idUsuarioAlta', array('class'=>'control-label')); ?>
                <div class="controls">
                    <span class="span5"><b><?=isset($model->usuario) ? $model->usuario->fullName : $model->creador->fullName?></b></span>
                </div>
            </div>
            <br>

            <?php
            endif;
            ?>

            <div class="control-group">
                <label class="control-label" for="Pedido_dFechaEntrega">Fecha de Entrega</label>
                <div class="controls">
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'value'=>isset($model->dFechaEntrega) && (int)$model->dFechaEntrega ? date('d-m-Y', strtotime($model->dFechaEntrega)) : '',
                        'name'=>'Pedido[dFechaEntrega]',
                        'language' => 'es',
                        'options'=>array(
                            'dateFormat' => 'dd-mm-yy',
                            'showAnim'=>'fadeIn',
                            'minDate' => date('d-m-Y'),
                        ),
                        'htmlOptions'=>array(
                            'class'=>'span5',
                            'readonly'=>'true'
                        ),
                    ));
                    ?>
                </div>
            </div>

            <?php

            $options = array();
            if(!$model->isNewRecord) {
                $totalfinal = $model->faltante + $model->nCobrado;
                $porc = $totalfinal == 0 ? 100 : ($model->nCobrado * 100) / $totalfinal;
                if($model->idEstado == 1 && $porc < 30) {
                    for ($i=2; $i <= count($estados); $i++) { 
                        $options[$i] = array('disabled'=>'disabled');
                    }
                } else {
                    foreach ($estados as $key => $value) {
                        if($key < $model->idEstado) {
                            $options[$key] = array('disabled'=>'disabled');
                        }
                    }
                }
            } else {
                for ($i=2; $i <= count($estados); $i++) { 
                    $options[$i] = array('disabled'=>'disabled');
                }
            }

            echo $form->dropDownListControlGroup($model,'idEstado',$estados,array('span'=>5, 'options'=>$options)); ?>

            <?php echo $form->textAreaControlGroup($model,'sObservaciones',array('span'=>5,'maxlength'=>300)); ?>

            <hr>
            <label>Resumen del pedido: (Cada anillado vale $<?=$valorAnillado?>)</label>
            <input type="hidden" id="valorAnillado" value="<?=$valorAnillado?>" />

            <table class="table table-hover table-bordered table-condensed" id="pedido-detalle" name="pedido-detalle">
                <thead>
                    <tr>
                        <th>C&oacute;d.</th>
                        <th>Tipo</th>
                        <th>C&oacute;digo</th>
                        <th>Descripcion</th>
                        <th>Cant.</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th data-toggle="tooltip" title="Anillado"><span>Anill</th>
                        <th data-toggle="tooltip" title="Entregado">Ent</th>
                        <th class="removeGrid"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right;">Valor Anillados:</th>
                        <th id="totalAnillados">$0.00</th>
                        <th colspan="2">Total:</th>
                        <th colspan="3" id="totalFooterFinal">$0.00</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <hr>

            <div class="control-group">

            <?php 
            if(isset($model->pago) && $model->pago->idFormaPago == 1) {
                ?>
                <label class="control-label text-success"><strong>Pago por DineroMail</strong></label>
                <?php
            } else {
                ?>
                    <label class="control-label" for="Pedido_nCobrado">Seña</label>
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on">$</span>
                            <input name="Pedido[nCobrado]" id="Pedido_nCobrado" autocomplete="off" class="span1" type="text" value="<?=$model->nCobrado?>">
                            <span style="margin-left:15px" class="add-on">Restante:</span>
                            <input id="restante" class="span1" type="text" disabled>
                        </div>
                    </div>
                <?php
            }
            ?>

            </div>

            <?php echo $form->dropDownListControlGroup($model,'idSucursal',$sucursales,array('span'=>5,'empty'=>'Elija una sucursal')); ?>

            <div class="form-actions">
                <?php echo TbHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar',array(
                    'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                    'size'=>TbHtml::BUTTON_SIZE_LARGE,
                )); 

                if($model->idEstado == 4 || // Estado para entregar o
                    (!$model->isNewRecord && $model->todoDetalleEntregado)) { // todos documentos entregados
                ?>
                    <a href="<?=Yii::app()->createUrl('pedido/terminar', array('id'=>$model->idPedido))?>" class="btn btn-danger btn-large">Terminar Pedido</a>
                <?php
                }

                ?>
            </div>

            <?php $this->endWidget(); ?>

        </div>
        <div class="span6">
            <?php
            Yii::app()->clientScript->registerScript('search', 'function throttle(e,t){var n=null;return function(){var r=this,i=arguments;clearTimeout(n);n=window.setTimeout(function(){e.apply(r,i)},t||500)}}$("input#docFilter").keyup(throttle(function(){$.fn.yiiGridView.update("gvDocumentos",{data:$("#id, #docFilter, #filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos").serialize()});return false}));$(".chosen").chosen().change(function(){var e=$("#id, #docFilter, #filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos").serialize();$.fn.yiiGridView.update("gvDocumentos",{data:e,url:"' . Yii::app()->baseUrl . '/pedido/' . $this->action->id . '"});return false});$("input#artFilter").keyup(throttle(function(){$.fn.yiiGridView.update("gvArticulos",{data:$("#artFilter").serialize()});return false}));$("#reset").click(function(){$("#docFilter").val("");$("#filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos").val("").trigger("chosen:updated");var e=$("#id, #docFilter, #filtroInstituciones, #filtroProfesores, #filtroMaterias, #filtroCarreras, #filtroCursos").serialize();$.fn.yiiGridView.update("gvDocumentos",{data:e,url:"' . Yii::app()->baseUrl . '/pedido/' . $this->action->id . '"});return false})');
            if($model->isNewRecord) {
                Yii::app()->clientScript->registerScript('enter', '$("body").on("keyup","input.agregardoc",function(e){if(e.keyCode==13){var t=$(this).closest("tr").children();if(!isNaN(parseInt(t[5].children[0].value))){var n=parseInt(t[5].children[0].value);var r=parseInt(t[4].innerHTML);if(r>0){alert("ALERTA!!!\nTiene "+r+" unidades disponibles de:\n "+t[1].childNodes[0].nodeValue)}if(n>0){fnClickAddRow("doc",t[0].innerHTML,t[1].innerHTML,n,t[3].innerHTML.replace("$",""));t[5].children[0].value=""}}}}).on("keyup","input.agregarart",function(e){if(e.keyCode==13){var t=$(this).closest("tr").children();if(!isNaN(parseInt(t[4].children[0].value))){var n=parseInt(t[4].children[0].value);var r=parseInt(t[4].innerHTML);if(r<=0){alert("ALERTA!!!\nTiene "+r+" unidades disponibles de:\n "+t[1].childNodes[0].nodeValue)}if(n>0){fnClickAddRow("art",t[0].innerHTML,t[1].innerHTML,n,t[2].innerHTML.replace("$",""));t[4].children[0].value=""}}}});');
            }

            ?>

            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Agregar Documentos al pedido</a></li>
                    <li><a href="#tab2" data-toggle="tab">Agregar Art&iacute;culos al pedido</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        <div class="row">
                            <div class="span3">
                                <?php

                                echo CHtml::label('Filtrar Código, Título o Autor:', 'docFilter');
                                echo CHtml::textField('docFilter', '', array('placeholder'=>'Filtrar Código, Título o Autor'));

                                echo CHtml::label('Buscá por Institución:', 'filtroInstituciones');
                                echo CHtml::dropDownList('filtroInstituciones', $filtroInstituciones, $instituciones, array('multiple' => true, 'class'=>'chosen'));

                                echo CHtml::label('Buscá por Profesor:', 'filtroProfesores');
                                echo CHtml::dropDownList('filtroProfesores', $filtroProfesores, $profesores, array('multiple' => true, 'class'=>'chosen'));

                                ?>

                                <hr>
                                <a href="javascript:void(0)" id="reset">Nueva b&uacute;squeda</a>
                            </div>
                            <div class="span3">
                                <?php

                                echo CHtml::label('Buscá por Materias:', 'filtroMaterias');
                                echo CHtml::dropDownList('filtroMaterias', $filtroMaterias, $materias, array('multiple' => true, 'class'=>'chosen'));

                                echo CHtml::label('Buscá por Carreras:', 'filtroCarreras');
                                echo CHtml::dropDownList('filtroCarreras', $filtroCarreras, $carreras, array('multiple' => true, 'class'=>'chosen'));

                                echo CHtml::label('Buscá por Cursos:', 'filtroCursos');
                                echo CHtml::dropDownList('filtroCursos', $filtroCursos, $cursos, array('multiple' => true, 'class'=>'chosen'));
                                ?>
                            </div>
                        </div>

                        <?php

                        $this->widget('bootstrap.widgets.TbGridView', array(
                            'id' => 'gvDocumentos',
                            'type' => TbHtml::GRID_TYPE_BORDERED,
                            'rowCssClassExpression' => '$data->getCssClass()',
                            'dataProvider' => $documentos,
                            'columns'=>array(
                                array(
                                    'name'=>'idDocumento',
                                    'htmlOptions'=>array('class'=>'coddoc')
                                ),
                                'sTitulo:gridText',
                                'nPaginas',
                                'price:currency',
                                array(
                                    'name'=>'nStock',
                                    'htmlOptions'=>array(
                                        'style'=>'display:none'
                                    ),
                                    'headerHtmlOptions'=>array(
                                        'style'=>'display:none'
                                    )
                                ),
                                array(
                                    'name'=>'cantidad',
                                    'type'=>'raw',
                                    'value'=>'CHtml::textField("quant", "", array("style"=>"width:25px", "class"=>"agregardoc"))',
                                ),
                                array(
                                    'class'=>'bootstrap.widgets.TbButtonColumn',
                                    'htmlOptions'=>array('style'=>'width:25px'),
                                    'headerHtmlOptions'=>array('style'=>'width:25px'),
                                    'template'=>'{add}{details}{file}',
                                    'buttons'=>array(
                                        'add' => array(
                                            'label'=>'Agregar',
                                            'imageUrl'=>Yii::app()->baseUrl.'/images/add.png',
                                            'url'=>'"javascript:void(0)"',
                                            'click'=>'function() { var $datos = $(this).closest("tr").children(); if(!isNaN(parseInt($datos[5].children[0].value))) { var cantidad = parseInt($datos[5].children[0].value); var stock = parseInt($datos[4].innerHTML); if(stock > 0) { alert("ALERTA!!!\nTiene " + stock + " unidades disponibles de:\n " + $datos[1].childNodes[0].nodeValue); } if(cantidad > 0) { fnClickAddRow("doc",$datos[0].innerHTML,$datos[1].innerHTML,cantidad,$datos[3].innerHTML.replace("$","")); $datos[5].children[0].value = ""; } } }',
                                            'visible'=>(string)$model->isNewRecord,
                                        ),
                                        'details'=>array(
                                            'label'=>'Ver Detalles',
                                            'imageUrl'=>Yii::app()->baseUrl.'/images/mas.png',
                                            'url'=>'"javascript:void(0)"',
                                            'click'=> 'js: function(){ $.post( "'.Yii::app()->request->baseUrl.'/documento/datos", { id: $(this).closest("tr").children()[0].innerHTML }).done(function( data ) { var data = $.parseJSON(data); $(".modal-header h3").html(data.header); $(".modal-body").html(data.body); $("#documentosModal").modal(); }); }',
                                        ),
                                        'file'=> array(
                                            'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => 'Archivo', 'target'=>'_blank'),
                                            'label' => '<i class="icon-file"></i>',
                                            'imageUrl' => false,
                                            'url' => '$data->physicalLocation',
                                        ),
                                    )
                                ),
                            ),
                        ));
                        ?>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <?php
                        echo CHtml::textField('artFilter', '', array('placeholder'=>'Filtrar ID, Descripcion o Precio'));

                        $this->widget('bootstrap.widgets.TbGridView', array(
                            'id' => 'gvArticulos',
                            'type' => TbHtml::GRID_TYPE_BORDERED,
                            'dataProvider' => $articulos,
                            'columns'=>array(
                                array(
                                    'name'=>'idArticulo',
                                    'value'=>'"a".$data->idArticulo',
                                    'htmlOptions'=>array('class'=>'codart')
                                ),
                                'sDescripcion:gridText',
                                'nPrecio:currency',
                                'nStock',
                                array(
                                    'name'=>'cantidad',
                                    'type'=>'raw',
                                    'value'=>'CHtml::textField("quant", "", array("style"=>"width:40px", "class"=>"agregarart"))',
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
                                            'visible'=>(string)$model->isNewRecord,
                                            'click'=>'function() { var $datos = $(this).closest("tr").children(); if(!isNaN(parseInt($datos[4].children[0].value))) { var cantidad = parseInt($datos[4].children[0].value); var stock = parseInt($datos[3].innerHTML); if(cantidad > stock) { alert("ALERTA!!!\nAgregó " + cantidad + " unidades al pedido teniendo " + stock + " unidades de:\n " + $datos[1].childNodes[0].nodeValue); } if(cantidad > 0) { fnClickAddRow("art",$datos[0].innerHTML,$datos[1].innerHTML,$datos[4].children[0].value,$datos[2].innerHTML.replace("$","")); $datos[3].innerHTML = stock - cantidad; $datos[4].children[0].value = ""; } } }',
                                        ),
                                    )
                                ),
                            ),
                        ));

                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div><!-- form -->

<?php $this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'documentosModal',
    'header' => '',
    'content' => '',
    'footer' => array(
        TbHtml::button('Cerrar', array('data-dismiss' => 'modal')),
     ),
)); ?>