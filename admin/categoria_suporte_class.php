<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva categoria_suporte no banco</p>
	 */

	function cadastroCategoria_suporte($dados)
	{
		include "includes/mysql.php";
		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			$v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);
      $dados['prefixo'] = trim($dados['prefixo']);
      $dados['sufixo'] = trim($dados['sufixo']);

		$dados['ordem'] = 1;
		$ordem = buscaCategoria_suporte(array('max'=>'ordem')); 
		
		if (!empty($ordem)){
			$ordem = $ordem[0];	
			$dados['ordem'] = (int)$ordem['max']+1;	
		}

		if(empty($dados['icone'])){
			$dados['icone'] = 1;
		}

		$sql = "INSERT INTO categoria_suporte(icone, titulo, ordem, prefixo, sufixo, numero, icone_name, home, imagem) VALUES (
						'".$dados['icone']."',
						'".$dados['titulo']."',
                        '".$dados['ordem']."',
                        '".$dados['prefixo']."',
                        '".$dados['sufixo']."',
                        '".$dados['numero']."',
                        '".$dados['icone_name']."',
                        '".$dados['home']."',
						'".$dados['imagem']."'
                    )";

		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita categoria_suporte no banco</p>
	 */
	function editCategoria_suporte($dados)
	{

		include "includes/mysql.php";
		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			$v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE categoria_suporte SET
                        icone = '".$dados['icone']."',
                        titulo = '".$dados['titulo']."',
                        icone_name = '".$dados['icone_name']."',
                        imagem = '".$dados['imagem']."'
					WHERE idcategoria_suporte = " . $dados['idcategoria_suporte'];

	 	if (mysqli_query($conexao, $sql)) {
			return $dados['idcategoria_suporte'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca categoria_suporte no banco</p>
	 */

	function buscaCategoria_suporte($dados = array())
	{
		include "includes/mysql.php";
		include_once "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			$v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idcategoria_suporte',$dados) && !empty($dados['idcategoria_suporte']) )
			$buscaId = ' and C.idcategoria_suporte = '.intval($dados['idcategoria_suporte']).' '; 

      //busca pelo id
      $buscaHome = '';
      if (array_key_exists('home',$dados) && !empty($dados['home']) )
         $buscaHome = ' and C.home = '.intval($dados['home']).' || C.home = 0 '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('titulo',$dados) && !empty($dados['titulo']) )
			$buscaNome = ' and C.titulo LIKE "%'.$dados['titulo'].'%" '; 

		//busca pelo imagem_icone
		$buscaNumero = '';
		if (array_key_exists('numero',$dados) && !empty($dados['numero']) )
			$buscaNumero = ' and C.numero = "'.$dados['numero'].'" ';

		//busca pelo descricao
		$buscaDescricao = '';
		if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
			$buscaDescricao = ' and C.descricao = "'.$dados['descricao'].'" ';

		$buscaOrdem = '';
			if (array_key_exists('order',$dados) && !empty($dados['order']) )
				$buscaOrdem = ' and C.ordem = "'.$dados['order'].'" ';

		$buscaPagina = '';
			if (array_key_exists('pagina',$dados) && !empty($dados['pagina']))
				$buscaPagina = ' and C.pagina = "'.$dados['pagina'].'" ';

         //ordem
        $orderBy = "";                   
        if (array_key_exists('ordem',$dados) && !empty($dados['ordem'])){ 
	    	$orderBy = ' ORDER BY '.$dados['ordem']; 
	    	if (array_key_exists('dir',$dados) && !empty($dados['dir'])){ 
		    	$orderBy .= " ". $dados['dir']; 
	        } 
        }

        //busca pelo limit
		$buscaLimit = '';
		if (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados)) {
            $buscaLimit = ' LIMIT '.($dados['limit'] * $dados['pagina']).','.$dados['limit'].' ';
        } elseif (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('inicio',$dados)){
            $buscaLimit = ' LIMIT '.$dados['limit'].','.$dados['inicio'].' ';
        } elseif (array_key_exists('limit',$dados) && !empty($dados['limit'])){
            $buscaLimit = ' LIMIT '.$dados['limit'];
        }


		$buscaMax = '';
		if(array_key_exists('max',$dados))
			$buscaMax = ', max('.$dados['max'].') as max ';

		//colunas que serão buscadas
		$colsSql = 'C.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idcategoria_suporte) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql, C.home as home_nome $buscaMax FROM categoria_suporte as C 
				WHERE 1  $buscaId $buscaHome $buscaNome $buscaOrdem $orderBy $buscaLimit ";
	 
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		// $iAux = 1;        
		// $tot =  mysqli_affected_rows($conexao);

		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r); 
			if (!array_key_exists('totalRecords',$dados)){
            // if($r['home']==1){
            //    $r['home_nome'] = 'Quero Trabalhar';
            // }elseif($r['home']==2){
            //    $r['home_nome'] = 'Quero Contratar';
            // }elseif($r['home']==3){
            //    $r['home_nome'] = 'Nossos Cases';
            // }else{
            //    $r['home_nome'] = 'Todas';
            // }

    //         	$r['ordemUp'] = "";
    //             $r['ordemDown'] = "";

    //             if ($iAux != 1){
    //                     $r['ordemUp'] = '<img src="images/arrUp.png" codigo="'.$r['idcategoria_suporte'].'" class="link ordemUp" />';
    //             }

    //             if ($iAux != $tot){
    //                     $r['ordemDown'] = '<img src="images/arrDown.png" codigo="'.$r['idcategoria_suporte'].'" class="link ordemDown"/>';
				// }
    //             $iAux++;
	        }  
			$resultado[] = $r;
		}
		return $resultado;
		
 	}

	/**
	 * <p>deleta categoria_suporte no banco</p>
	 */
	function deletaCategoria_suporte($dados)
	{
		include "includes/mysql.php";

		$categoria_suporte = buscaCategoria_suporte(array("idcategoria_suporte"=>$dados));
		$ordem = $categoria_suporte[0]['ordem'];
		$imagem = $categoria_suporte[0]['imagem_icone']; 
		 
		$sql = "DELETE FROM categoria_suporte WHERE idcategoria_suporte = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			$sql ="UPDATE categoria_suporte SET ordem = (ordem - 1) WHERE ordem > ".$ordem;
			mysqli_query($conexao, $sql);
			return $num;
		} else {
			return FALSE;
		}
	} 
 
 	function editarImagemCategoria_suporte($imgs) {
		$path = 'files/categoria_suporte/';

		$nameArquivo = array();
		$nameArquivo[] = "";
		$nameArquivo[] = "thumb_";
		$nameArquivo[] = "original_";

		if(file_exists($path)){
			if(is_array($imgs)){
				foreach ($imgs as $img) {
					foreach ($nameArquivo as $key => $_name) {
						$arquivo = $_name.$img['nome_imagem'];
						if(file_exists($path.$arquivo)){
							unlink($path.$arquivo);
						}
					}
				}
			}else{
				foreach ($nameArquivo as $key => $_name) {
					$arquivo = $_name.$imgs;
					if(file_exists($path.$arquivo)){
						unlink($path.$arquivo);
					}
				}
			}
    	}
		return true;
	}

    function editOrdemCategoria_suporte($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE categoria_suporte SET					
						ordem = '".$dados['ordem']."'						
					WHERE idcategoria_suporte = " . $dados['idcategoria_suporte'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idcategoria_suporte'];
	    } else {
	        return false;
	    }
	}


?>