<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva suporte no banco</p>
	 */
	function cadastroSuporte($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['nome'] = trim($dados['nome']);
		$dados['descricao'] = trim($dados['descricao']);

		$sql = "INSERT INTO suporte(nome, descricao, idcategoria_suporte, titulo, texto, titulo_faq, texto_faq, titulo_caixa, texto_caixa) VALUES (
						'".$dados['nome']."',
						'".$dados['descricao']."',
                        '".$dados['idcategoria_suporte']."',
                        '".$dados['titulo']."',
                        '".$dados['texto']."',
                        '".$dados['titulo_faq']."',
                        '".$dados['texto_faq']."',
                        '".$dados['titulo_caixa']."',
                        '".$dados['texto_caixa']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita suporte no banco</p>
	 */
	function editSuporte($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['nome'] = trim($dados['nome']);
		$dados['descricao'] = trim($dados['descricao']);

		$sql = "UPDATE suporte SET
						descricao = '".$dados['descricao']."',
                        titulo = '".$dados['titulo']."',
                        titulo_faq = '".$dados['titulo_faq']."',
                        texto_faq = '".$dados['texto_faq']."',
                        titulo_caixa = '".$dados['titulo_caixa']."',
                        texto_caixa = '".$dados['texto_caixa']."',
                        idcategoria_suporte = '".$dados['idcategoria_suporte']."'
					WHERE idsuporte = " . $dados['idsuporte'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idsuporte'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca suporte no banco</p>
	 */
	function buscaSuporte($dados = array())
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
		if (array_key_exists('idsuporte',$dados) && !empty($dados['idsuporte']) )
			$buscaId = ' and suporte.idsuporte = '.intval($dados['idsuporte']).' '; 

        //busca pelo id
        $buscaIdcategoria_suporte = '';
        if (array_key_exists('idcategoria_suporte',$dados) && !empty($dados['idcategoria_suporte']) )
            $buscaIdcategoria_suporte = ' and suporte.idcategoria_suporte = '.intval($dados['idcategoria_suporte']).' '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and suporte.nome = '.$dados['nome'].' ';

        //busca pelo respota
		$buscaDescricao = '';
		if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
			$buscaDescricao = ' and suporte.descricao = '.$dados['descricao'].' '; 

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
		
		$buscaMax = '';
		if(array_key_exists('max',$dados))
			$buscaMax = ', max('.$dados['max'].') as max ';
			
		//colunas que serão buscadas
		$colsSql = 'suporte.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idsuporte) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql, categoria_suporte.titulo as nome_categoria_suporte $buscaMax FROM suporte LEFT JOIN categoria_suporte ON suporte.idcategoria_suporte = categoria_suporte.idcategoria_suporte WHERE 1  $buscaId $buscaIdcategoria_suporte $buscaNome $buscaDescricao $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){

            }
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta suporte no banco</p>
	 */
	function deletaSuporte($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM suporte WHERE idsuporte = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	// function inverteStatus($dados)
	// {
	//     include "includes/mysql.php";
	   
	//     $sql = "UPDATE suporte SET					
	// 					status = '".$dados['status']."'						
	// 				WHERE idsuporte = " . $dados['idsuporte'];
	    
	//     if (mysqli_query($conexao, $sql)) {
	//         return $dados['idsuporte'];
	//     } else {
	//         return false;
	//     }
	// }

?>