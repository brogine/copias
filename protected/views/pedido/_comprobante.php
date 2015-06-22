<?php
$totalFinal = 0;
$cobrado = isset($pedido->nCobrado) ? $pedido->nCobrado : 0;
$cantElementos = count($pedido->documentos) + count($pedido->articulos);
$cantPaginas = ceil($cantElementos / 10);
$currValue = 0;
$checkingDocs = true; // Start with docs
$fillBlank = false;

for ($i=0; $i < $cantPaginas; $i++) {
?>

<table style="page-break-inside: avoid;font-size: 12pt;font-family:Arial, Helvetica, sans-serif;width:86.8mm">
	<tr><td style="width:57.8mm;border:none;" colspan="4"></td><td colspan="2" class="center" style="width:28.9mm;border:none;height:13mm; font-size:14pt"><?=(isset($pedido->dFechaPedido) && strtotime($pedido->dFechaPedido) > 0 ? date("d.m.Y", strtotime($pedido->dFechaPedido)) : date("d.m.Y"))?></td></tr>
	<tr><td style="border:none;" colspan="4"></td><td colspan="2" class="center" style="border:none;height:16mm;font-size:24pt;font-weight:bold;"><?=$pedido->idPedido?></td></tr>
	<tr><td style="border:none;" colspan="6" style="border:none;height:15mm;font-size:9pt" class="left"><?=(isset($pedido->usuario) ? $pedido->usuario->fullNameDni : "Cliente Mostrador")?></td></tr>
	
	<?php

	for ($j=(0 + ($i * 10)); $j < (($i + 1) * 10); $j++) {
		if($checkingDocs && $j == count($pedido->documentos))
			$checkingDocs = false;
		if(!$fillBlank && $j == $cantElementos)
			$fillBlank = true;

		if(!$fillBlank) {
			if($checkingDocs) {
				$totalParcial = $pedido->documentos[$j]->nValorNeto + $pedido->documentos[$j]->nValorAnillados;
				echo '<tr>';
				echo '<td style="border:none;height:5.58mm;" class="center">' . $pedido->documentos[$j]->idDocumento . '</td>';
				echo '<td style="border:none;padding-left:4mm;" class="center">' . $pedido->documentos[$j]->nCopias . '</td>';
				echo '<td style="padding-left:3mm;border:none;" class="center">' . $pedido->documentos[$j]->nAnillado . '</td>';
				echo '<td style="border:none;" class="center">' . ($pedido->documentos[$j]->bEntregado == 1 ? 'Si' : 'No') . '</td>';
				echo '<td class="center" style="border:none;padding-left:2mm">$' . number_format($totalParcial, 2, ",", ".") . '</td>';
				echo '</tr>';
				$totalFinal += $totalParcial;
			} else {
				echo '<tr>';
				echo '<td style="border:none;height:5.58mm;" class="center">a' . $pedido->articulos[$j]->idArticulo . '</td>';
				echo '<td style="border:none;padding-left:4mm;" class="center">' . $pedido->articulos[$j]->nCantidad . '</td>';
				echo '<td style="padding-left:3mm;border:none;" class="center">0</td>';
				echo '<td style="border:none;" class="center">' . ($pedido->articulos[$j]->bEntregado == 1 ? 'Si' : 'No') . '</td>';
				echo '<td class="center" style="border:none;padding-left:2mm">$' . number_format($pedido->articulos[$j]->nValorNeto, 2, ",", ".") . '</td>';
				echo '</tr>';
				$totalFinal += $pedido->articulos[$j]->nValorNeto;
			}
		} else {
			echo '<tr><td colspan="6" style="border:none;height:5.58mm;"></td></tr>';
		}
	}

	?>
	<tr>
		<td colspan="2" style="border:none;height:18mm;padding-left:2mm;vertical-align:middle;"><?=(isset($pedido->dFechaEntrega) && strtotime($pedido->dFechaEntrega) > 0 ? date("d.m.Y", strtotime($pedido->dFechaEntrega)) : date("d.m.Y"))?></td>
		<td colspan="2" class="center" style="border:none;font-size:16pt;font-weight:bold;"><?=$cobrado?></td>
		<td colspan="2" class="center" style="border:none;font-size:16pt;font-weight:bold;"><?=$totalFinal?></td>
	</tr>
	<tr>
		<td colspan="4" class="center" style="border:none;font-size:14pt"><?=$pedido->sucursal->sDomicilio?></td>
		<td colspan="2" class="center" style="border:none;font-size:16pt;font-weight:bold;"><?=(isset($pedido->nFaltante) && $pedido->nFaltante != -1) ? $pedido->nFaltante : ($totalFinal - $cobrado) ?></td>
	</tr>
</table>

<!--<page_footer>
	P&aacute;gina [[page_cu]] de [[page_nb]]
</page_footer>-->

<?php } ?>