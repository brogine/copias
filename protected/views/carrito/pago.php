<h3>Espera mientras te redireccionamos..</h3>
<div class="progress progress-striped active">
  <div class="bar" style="width: 100%;"></div>
</div>

<form action="https://checkout.dineromail.com/CheckOut" method="post" id="pagoForm" >
    <!-- VARIABLES DEL VENDEDOR -->
    <input type="hidden" name="merchant" value="<?=Yii::app()->params['dmMerchant']?>" />
    <input type="hidden" name="country_id" value="1" />
    <input type="hidden" name="seller_name" value="IconoIdeas" />
    <input type="hidden" name="language" value="es" />
    <input type="hidden" name="transaction_id" value="<?=$pago?>" />
    <input type="hidden" name="currency" value="ars" />
    <input type="hidden" name="ok_url" value="<?=str_replace(":", "%3A", Yii::app()->createAbsoluteUrl('/carrito/realizado', array('id'=>$pedido)))?>" />
    <input type="hidden" name="error_url" value="<?=str_replace(":", "%3A", Yii::app()->createAbsoluteUrl('/carrito/error', array('id'=>$pedido)))?>" />
    <input type="hidden" name="pending_url" value="<?=str_replace(":", "%3A", Yii::app()->createAbsoluteUrl('/carrito/pendiente', array('id'=>$pedido)))?>" />
    <input type="hidden" name="buyer_message" value="0" />
    <input type="hidden" name="change_quantity" value="0" />
    <input type="hidden" name="display_shipping" value="0" />
    <input type="hidden" name="display_additional_charge" value="0" />
    <!-- VARIABLES DE MEDIOS DE PAGO-->
    <input type="hidden" name="payment_method_available" value="4,13" />
    <input type="hidden" name="payment_method_1" value="" />
    <!-- VARIABLES DEL PRODUCTO/ITEM -->
    <input type="hidden" name="item_name_1" value="Servicios de Impresi&oacute;n" />
    <input type="hidden" name="item_quantity_1" value="1" />
    <input type="hidden" name="item_ammount_1" value="<?=number_format($total, 2)?>" />
    <input type="hidden" name="item_currency_1" value="ars" />
</form>

<?php
Yii::app()->clientScript->registerScript('pago', "$('#pagoForm').submit();", CClientScript::POS_END);
?>