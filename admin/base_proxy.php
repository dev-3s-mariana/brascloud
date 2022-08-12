<?php
	 // Versao do modulo: 3.0.010416

require_once 'includes/verifica.php'; // checa user logado

header('Content-type: application/json');

initArray();

function initArray()
{
    $tipo_mod = preg_replace('/[^a-zA-Z0-9_]/', '', $_REQUEST['tipoMod']);
    if(file_exists($tipo_mod . "_class.php"))
    {
	    include_once($tipo_mod . "_class.php");
	    if(!isset($_REQUEST['tipo']) || empty($_REQUEST['tipo'])) {
            $funcName = 'busca' . ucfirst($tipo_mod);
        } else {
            $funcName = 'busca' . ucfirst($_REQUEST['tipo']);
        }

        //Busca Registros
	    $aRetorno = $funcName($_REQUEST);

        //Busca total de registros
        $_REQUEST['totalRecords'] = true;
        $totalRecords = $funcName($_REQUEST);
        $totalRecords = $totalRecords[0]['totalRecords'];

        //Gera total de paginas
        $pageSize = 0;
        if(!empty($totalRecords) && $_REQUEST['limit'] > 0){
            $pageSize = ceil($totalRecords / $_REQUEST['limit']);
        }

        // Create return value
        $returnValue = array(
                        'totalRecords'=>$totalRecords,
                        'pageSize'=>$pageSize,
                        'records'=>$aRetorno
        );
        print json_encode($returnValue);
    } else {
       return FALSE;
    }
}
