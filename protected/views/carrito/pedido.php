<?php 
if(Yii::app()->user->hasFlash('success')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_SUCCESS, Yii::app()->user->getFlash('success'));
} elseif (Yii::app()->user->hasFlash('error')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_ERROR, Yii::app()->user->getFlash('error'));
} elseif(Yii::app()->user->hasFlash('info')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_INFO, Yii::app()->user->getFlash('info'));
} else { ?>

<h5><?php echo TbHtml::i('Cada anillado tiene un valor de $<span id="vAnillado">' . $valorAnillado . '</span>'); ?></h5>

<?php
if(Yii::app()->user->hasFlash('warning')) {
    echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, Yii::app()->user->getFlash('warning'));
}
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'carrito-form',
    'enableAjaxValidation'=>false,
)); ?>

<table id="pedido-detalle" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripcion</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th style="width: 50px;">Anillados</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<?php
$total = 0;
foreach(Yii::app()->shoppingCart as $key => $item) {

    if($item instanceof Documento) {
        $subtotal = (($item->quantity * $item->price) + ($valorAnillado * $item->anilladosTmp));
        echo "<td class=\"coddoc\">" . $item->idDocumento . "</td>";
        echo "<td>$item->sTitulo</td>";
        echo "<td class=\"cant\">" . $item->quantity. "</td>";
        echo "<td class=\"importe\">$" . $item->price . "</td>";
        echo "<td class=\"ianill\">" . TbHtml::textField('anill', $item->anilladosTmp, array('placeholder' => '0', 'class'=>'span12 anill', 'id'=>$item->idDocumento)) . "</td>";
        echo "<td class=\"subtotal\">$" . $subtotal . "</td>";
        $imagen = CHtml::image(Yii::app()->baseUrl . '/images/remove.png', 'Quitar');
        echo "<td style='width:17px'>" . CHtml::ajaxLink($imagen, array('carrito/quitar'),array('type'=>'post','data'=>array('id'=>$item->idDocumento, 'type'=>'doc'), 'success' =>'function() { location.reload(); }'), array('rel'=>'tooltip', 'data-original-title' => 'Quitar')) . "</td>";
        $total += $subtotal;
    } elseif ($item instanceof Articulo) {
        $subtotal = ($item->quantity * $item->price);
        echo "<td class=\"codart\">$key</td>";
        echo "<td>$item->sDescripcion</td>";
        echo "<td>" . $item->quantity . "</td>";
        echo "<td class=\"importe\">$" . $item->price . "</td>";
        echo "<td>-</td>";
        echo "<td>$" . $subtotal . "</td>";
        $imagen = CHtml::image(Yii::app()->baseUrl . '/images/remove.png', 'Quitar');
        echo "<td style='width:17px'>" . CHtml::ajaxLink($imagen, array('carrito/quitar'),array('type'=>'post','data'=>array('id'=>$item->idArticulo, 'type'=>'art'), 'success' =>'function() { location.reload(); }'), array('rel'=>'tooltip', 'data-original-title' => 'Quitar')) . "</td>";
        $total += $subtotal;
    }
    echo "</tr>";
}
?>
    </tbody>
</table>


    <div class="well extended-summary span4">
        <h3>Total Pedido: $<span id="total"><?=$total?></span></h3>
        <span>Donde retirar</span>
        <p>
            <?php echo TbHtml::dropDownList('idSucursal', Yii::app()->shoppingCart->sucursal, $sucursales, array('span'=>12, 'empty'=>'Elige una')); ?>
        </p>
    </div>
    <div class="span7">
        <h4>¿C&oacute;mo abonas?</h4>
        <p><strong>Online con tarjeta de cr&eacute;dito o d&eacute;bito:</strong> el sistema te redireccionar&aacute; a otra p&aacute;gina donde deber&aacute;s completar tus datos. Tu pedido quedar&aacute; pago autom&aacute;ticamente excepto que el pago sea rechazado. Podr&aacute;s pasar a retirarlo por la sucursal que hayas seleccionado luego de 48 hs. de realizado el pago.</p>
        <p><input class="btn btn-primary" type="submit" name="dineromail" value="Pulsa aquí"></p>
        <p><strong>Personalmente en Efectivo:</strong> tu pedido quedar&aacute; en espera hasta que te acerques a alguno de nuestros puntos de venta para pagarlo total o parcialmente. <strong>En ese momento comenzar&aacute; la impresi&oacute;n y acordar&aacute;s la fecha y el lugar de entrega.</strong> Si son pocas impresiones, y de ser posible, te lo prepararemos en el momento.</p>
        <p><input class="btn btn-inverse" type="submit" name="retiro" value="Pulsa aquí"></p>
    </div>


<?php 
$this->endWidget(); 
}
?>