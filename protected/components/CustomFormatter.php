<?php
/**
 * CustomFormatter is the customised formatter class.
 */

date_default_timezone_set('America/Argentina/Buenos_Aires');
class CustomFormatter extends CFormatter
{
    public $gridTextLimit = 100;

	public function formatCurrency($value)
    {
        if(!is_numeric($value))
            return "No asignado";
        return "$" . number_format($value, 2);
    }

    public function formatStock($value)
    {
    	$string = '';
    	if($value == 1):
    		$string = ' unidad';
    	elseif ($value > 1 || $value = 0):
    		$string = ' unidades';
        elseif ($value < 0):
            $string = ' <span class="label label-important">Revisar Stock</span>';
    	endif;
    	return $value . $string;
    }

    public function formatFecha($value)
    {
        return (isset($value) && strtotime($value) > 0 ? date("d-m-Y", strtotime($value)) : "No hay");
    }

    public function formatFechaHora($value)
    {
        return (isset($value) && strtotime($value) > 0 ? date("d-m-Y H:i:s", strtotime($value)) : "No hay");
    }

    public function formatBool($value)
    {
        return ($value == 0 ? "No" : "Si");
    }

    public function formatGridText($value) {
        if(strlen($value)>$this->gridTextLimit) {
            $retval=CHtml::tag('span',array('title'=>$value, 'style'=>'font-size:70%;'),CHtml::encode($value));
        } else {
            $retval=CHtml::encode($value);
        }
        return $retval;
    }

}