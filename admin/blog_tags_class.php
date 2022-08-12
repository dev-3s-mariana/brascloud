<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva blog_tags no banco</p>
	 */

	function cadastroBlog_tags($dados)
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
		$ordem = buscaBlog_tags(array('max'=>'ordem')); 
		
		if (!empty($ordem)){
			$ordem = $ordem[0];	
			$dados['ordem'] = (int)$ordem['max']+1;	
		}

		if(empty($dados['icone'])){
			$dados['icone'] = 1;
		}

		$sql = "INSERT INTO blog_tags(icone, titulo, ordem, prefixo, sufixo, numero, icone_name, home, imagem, urlrewrite, status) VALUES (
						'".$dados['icone']."',
						'".$dados['titulo']."',
                        '".$dados['ordem']."',
                        '".$dados['prefixo']."',
                        '".$dados['sufixo']."',
                        '".$dados['numero']."',
                        '".$dados['icone_name']."',
                        '".$dados['home']."',
						'".$dados['imagem']."',
                        '".$dados['urlrewrite']."',
                        '".$dados['status']."'
                    )";

		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita blog_tags no banco</p>
	 */
	function editBlog_tags($dados)
	{

		include "includes/mysql.php";
		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			$v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE blog_tags SET
                        titulo = '".$dados['titulo']."',
                        urlrewrite = '".$dados['urlrewrite']."',
                        status = '".$dados['status']."'
					WHERE idblog_tags = " . $dados['idblog_tags'];

	 	if (mysqli_query($conexao, $sql)) {
			return $dados['idblog_tags'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca blog_tags no banco</p>
	 */

	function buscaBlog_tags($dados = array())
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
		if (array_key_exists('idblog_tags',$dados) && !empty($dados['idblog_tags']) )
			$buscaId = ' and C.idblog_tags = '.intval($dados['idblog_tags']).' '; 

        //busca pelo id
        $buscaStatus = '';
        if (array_key_exists('status',$dados) && !empty($dados['status']) )
            $buscaStatus = ' and C.status = '.intval($dados['status']).' '; 

        //busca pelo id
        $buscanotId = '';
        if (array_key_exists('not_idblog_tags', $dados) && !empty($dados['not_idblog_tags']))
            $buscanotId = ' and NOT C.idblog_tags = ' . intval($dados['not_idblog_tags']) . ' ';

        //busca por urlrewrite
        $buscaUrlrewrite = '';
        if (array_key_exists('urlrewrite', $dados) && !empty($dados['urlrewrite']))
            $buscaUrlrewrite = ' and C.urlrewrite = "' . $dados['urlrewrite'] . '" ';

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
			$colsSql = ' count(idblog_tags) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql $buscaMax FROM blog_tags as C 
				WHERE 1  $buscaId $buscaHome $buscaNome $buscanotId $buscaStatus $buscaUrlrewrite $buscaOrdem $orderBy $buscaLimit ";
	 
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		$iAux = 1;        
		$tot =  mysqli_affected_rows($conexao);

		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r); 
			if (!array_key_exists('totalRecords',$dados)){
            	$r['ordemUp'] = "";
                $r['ordemDown'] = "";

                $r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
                    $r["status_icone"] = "<img src='images/estrela".($r["status"]=='1' ? "sim":"nao").".png' class='icone inverteStatus' codigo='".$r['idblog_tags']."' width='20px' />";

                if ($iAux != 1){
                        $r['ordemUp'] = '<img src="images/arrUp.png" codigo="'.$r['idblog_tags'].'" class="link ordemUp" />';
                }

                if ($iAux != $tot){
                        $r['ordemDown'] = '<img src="images/arrDown.png" codigo="'.$r['idblog_tags'].'" class="link ordemDown"/>';
				}
                $iAux++;
	        }  
			$resultado[] = $r;
		}
		return $resultado;
		
 	}

	/**
	 * <p>deleta blog_tags no banco</p>
	 */
	function deletaBlog_tags($dados)
	{
		include "includes/mysql.php";

		$blog_tags = buscaBlog_tags(array("idblog_tags"=>$dados));
		$ordem = $blog_tags[0]['ordem'];
		$imagem = $blog_tags[0]['imagem_icone']; 
		 
		$sql = "DELETE FROM blog_tags WHERE idblog_tags = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			$sql ="UPDATE blog_tags SET ordem = (ordem - 1) WHERE ordem > ".$ordem;
			mysqli_query($conexao, $sql);
			return $num;
		} else {
			return FALSE;
		}
	} 
 
 	function editarImagemBlog_tags($imgs) {
		$path = 'files/blog_tags/';

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

    function editOrdemBlog_tags($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE blog_tags SET					
						ordem = '".$dados['ordem']."'						
					WHERE idblog_tags = " . $dados['idblog_tags'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idblog_tags'];
	    } else {
	        return false;
	    }
	}


?>