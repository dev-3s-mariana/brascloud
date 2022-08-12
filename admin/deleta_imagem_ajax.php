<?php
	$opx = $_REQUEST['opx'];
	
	$dados = $_REQUEST;
	
	switch($opx){ 
                case 'deletaImagem':  
                        $pastas = $dados['pastas'];			 
			$imagem = $dados['nomeimagem'];
                        $pastas = explode(",", $pastas); 
                        
                        foreach($pastas as $k => $v){                            
                            @unlink('files/'.$v.'/'.$imagem);
                        } 
		break;
	} 
        
?>