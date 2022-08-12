<?php 
	 // Versao do modulo: 3.00.010416 
	/**
	 * <p>salva idiomas_traducao no banco</p>
	 */
	function cadastroIdiomas_traducao($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "INSERT INTO idiomas_traducao( ididiomas, tag, texto) VALUES (
						'".$dados['ididiomas']."',
						'".$dados['tag']."',
						'".$dados['texto']."')";
		
		if (mysqli_query($conexao, $sql)) {
			$resultado = $dados['tag'];
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita idiomas_traducao no banco</p>
	 */
	function editIdiomas_traducao($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE idiomas_traducao SET  
						texto = '".$dados['texto']."'
					WHERE ididiomas = " . $dados['ididiomas'] . " and tag = '".$dados['tag']."' ";

		if (mysqli_query($conexao, $sql)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * <p>busca idiomas_traducao no banco</p>
	 */
	function buscaIdiomas_traducao($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('ididiomas_traducao',$dados) && !empty($dados['ididiomas_traducao']) )
			$buscaId = ' and ididiomas_traducao = '.intval($dados['ididiomas_traducao']).' '; 

		//busca pelo ididiomas
		$buscaIdidiomas = '';
		if (array_key_exists('ididiomas',$dados) && !empty($dados['ididiomas']) )
			$buscaIdidiomas = ' and ididiomas = '.$dados['ididiomas'].' '; 


		//busca pelo ididiomas
		$buscaIdidiomasIn = '';
		if (array_key_exists('in_ididiomas',$dados) && !empty($dados['in_ididiomas']) )
			$buscaIdidiomasIn = ' and ididiomas in ('.$dados['in_ididiomas'].') '; 


		//busca pelo tag
		$buscaTag = '';
		if (array_key_exists('tag',$dados) && !empty($dados['tag']) )
			$buscaTag = ' and tag = "'.$dados['tag'].'" '; 


		//busca pelo texto
		$buscaTexto = '';
		if (array_key_exists('texto',$dados) && !empty($dados['texto']) )
			$buscaTexto = ' and texto LIKE "%'.$dados['texto'].'%" '; 

        //ordem
        $orderBy = "";
        if (isset($dados['ordem']) && !empty($dados['ordem']) && isset($dados['dir'])){
			$orderBy = ' ORDER BY '.$dados['ordem'] ." ". $dados['dir'];
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

		//colunas que serão buscadas
		$colsSql = '*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(ididiomas_traducao) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM idiomas_traducao WHERE 1  $buscaId  $buscaIdidiomas $buscaIdidiomasIn $buscaTag  $buscaTexto  $orderBy $buscaLimit ";
		
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			$resultado[] = $r;
		}

		if(array_key_exists('texto_traduzido', $dados))
		{
			if(isset($resultado[0]))
			{
				return $resultado[0]['texto']; 
			}
			else
			{
				return '';
			};
		};
		
		return $resultado; 
 	}

	/**
	 * <p>deleta idiomas_traducao no banco</p>
	 */
	function deletaIdiomas_traducao($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM idiomas_traducao WHERE tag = '".$dados."'";
		 
		if (mysqli_query($conexao, $sql)) {
			return true;
		} else {
			return FALSE;
		}
	}


	function cadastrarTagsIdioma($idioma){
		//cadastrar as tags para o novo idioma
		$dados['ididiomas'] = $idioma;
		$tags = buscaIdiomas_traducao(array("ididiomas"=>1));
		foreach ($tags as $k => $v) {	
			$dados['tag'] = $v['tag'];
			$dados['texto']	= "";
			cadastroIdiomas_traducao($dados);
		} 
		return true;
	}

	function traduzir($idioma, $tag, $var = array()){
		$texto = buscaIdiomas_traducao(array("ididiomas"=>$idioma,"tag"=>$tag));
		if(empty($texto)){
			return "";
		}else{
			if(empty($var)){
				return $texto[0]['texto'];
			}else{
				$texto = $texto[0]['texto']; 
				foreach ($var as $key => $value) {					
					$_tag = "[tag".($key+1)."]";
					$texto = str_replace($_tag, $value, $texto); 
				}
				return $texto;
			}
		}
	} 
?>