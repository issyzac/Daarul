<?php
	function getColumn($num){
		/*kwa column A to ZZ, column zaidi ya hapo kicheche*/
		$diff = $num - 65;
		
		if($diff > 25){
			$quotient = floor($diff / 26);
			$first = $quotient + 64;
			$second = $num - (26 * $quotient);
			
			return chr($first).chr($second);
		}else{
			return chr($num);
		}
	}
?>