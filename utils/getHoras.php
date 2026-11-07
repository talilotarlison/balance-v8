<?php
	/** 
	 * Class que pega hora de acordo com o fuso horário de São Paulo. 
	 * Site: https://pt.stackoverflow.com/questions/31493/como-importar-classes-a-partir-de-suas-subclasses-em-php
	 * @author Talilo Tarlison
	 * @description Ajuste para incluir data também
	 * @created 07/02/2025
	 * @version 1.0, 07/02/2025
	 */

	class Horas{
		static public function getHora(){
			date_default_timezone_set('America/Sao_Paulo');
			$date = date('d/m/Y H:i');
			return $date;
		}

		static public function getHoraData(){
			$datamensagem = 'America/Sao_Paulo';
			$date = (new DateTime($datamensagem))->format('d/m/Y H:i:s');
			return $date;
		}
	}

?>
