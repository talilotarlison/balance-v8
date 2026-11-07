<?php
	/**
	 * Script que cria o calendário do mês.
	 * contato: rodrigo.amora@globo.com
	 * site: https://www.codigofonte.com.br/codigos/calendario-do-mes-corrente-em-php
	 * @author Rodrigo Amora
	 * @created 07/02/2009
	 * @version 1.0, 07/02/2009
	 */

	$ano = date("Y");
	$cont = 0;
	$dia = date("d");
	$dias = array();
	$mes = date("m");
	$totalDias = date("t");
	$primeiroDia = date("D", mktime(0, 0, 0, $mes, 1, $ano));

	for($d = 0; $d < $totalDias; $d++) {
		$dias[$d] = $d + 1;
	}

	switch($primeiroDia) {
		case "Sun":
			$pos = 0;
			break;
		case "Mon":
			$pos = 1;
			break;
		case "Tue":
			$pos = 2;
			break;
		case "Wed":
			$pos = 3;
			break;
		case "Thu":
			$pos = 4;
			break;
		case "Fri":
			$pos = 5;
			break;
		case "Sat":
			$pos = 6;
			break;
	}

	switch($mes) {
		case 1:
			$mes2 = "Janeiro";
			break;
		case 2:
			$mes2 = "Fevereiro";
			break;
		case 3:
			$mes2 = "Março";
			break;
		case 4:
			$mes2 = "Abril";
			break;
		case 5:
			$mes2 = "Maio";
			break;
		case 6:
			$mes2 = "Junho";
			break;
		case 7:
			$mes2 = "Julho";
			break;
		case 8:
			$mes2 = "Agosto";
			break;
		case 9:
			$mes2 = "Setembro";
			break;
		case 10:
			$mes2 = "Outubro";
			break;
		case 11:
			$mes2 = "Novembro";
			break;
		case 12:
			$mes2 = "Dezembro";
			break;
	}
?>

<table class="calendar-table" aria-label="Calendário de <?=$mes2 . ' ' . $ano?>">
	<tr>
		<th class="calendar-month" colspan="7"><?=$mes2 . " " . $ano?></th>
	</tr>
	<tr>
		<th scope="col" title="Domingo">Dom</th>
		<th scope="col" title="Segunda-feira">Seg</th>
		<th scope="col" title="Terça-feira">Ter</th>
		<th scope="col" title="Quarta-feira">Qua</th>
		<th scope="col" title="Quinta-feira">Qui</th>
		<th scope="col" title="Sexta-feira">Sex</th>
		<th scope="col" title="Sábado">Sáb</th>
	</tr>

<?php
	for($linha = 0; $linha < 6; $linha++) {
		echo "<tr>";
		for($coluna = 0; $coluna < 7; $coluna++) {
			$pos2 = $cont - $pos;
			if(empty($dias[$pos2])) {
				echo "<td><center>-</center></td>";
			} else {
				if($dias[$pos2] == $dia) {
					echo "<td class='calendar-today'><strong>" . $dias[$pos2] . "</strong></td>";
				} else {
					echo "<td>" . $dias[$pos2] . "</td>";
				}
			}
			$cont++;
		}
		echo "</tr>";
	}
?>
</table>
