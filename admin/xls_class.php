<?php
function exportaXls($dados){
	if(!empty($dados)){

		header("Content-type: application/vnd.ms-excel");
		header("Content-type: application/force-download");
		header("Content-Disposition: attachment; filename=export.xls");
		header("Pragma: no-cache");
		echo "\xEF\xBB\xBF"; // UTF-8 BOM - necessario para aparecer acentos correctamente tanto em Excel Windows quanto OSX

		print '<table>';
		$dados = $dados['valores'];
		foreach($dados as $k=>$v){

			if($k == 0){
				print '<tr>';
				foreach($v as $titulos=>$vv){
					print '<td><b>'.mb_strtoupper($titulos, 'UTF-8').'</b></td>';
				}
				print '</tr>';
			}

			print '<tr>';
			foreach($v as $kk=>$campos){
				print '<td>'.$campos.'</td>';
			}
			print '</tr>';

		}
		print '</table>';
	}
}
