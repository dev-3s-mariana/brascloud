<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva produtos no banco</p>
	 */
	function cadastroProdutos($dados)
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

		$sql = "INSERT INTO produtos(nome, descricao, idcategoria) VALUES (
						'".$dados['nome']."',
						'".$dados['descricao']."',
                        '".$dados['idcategoria']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita produtos no banco</p>
	 */
	function editProdutos($dados)
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

		$sql = "UPDATE produtos SET
						nome = '".$dados['nome']."',
						descricao = '".$dados['descricao']."',
                        idcategoria = '".$dados['idcategoria']."'
					WHERE idprodutos = " . $dados['idprodutos'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idprodutos'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca produtos no banco</p>
	 */
	function buscaProdutos($dados = array())
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
		if (array_key_exists('idprodutos',$dados) && !empty($dados['idprodutos']) )
			$buscaId = ' and produtos.idprodutos = '.intval($dados['idprodutos']).' '; 

        //busca pelo id
        $buscaIdcategoria = '';
        if (array_key_exists('idcategoria',$dados) && !empty($dados['idcategoria']) )
            $buscaIdcategoria = ' and produtos.idcategoria = '.intval($dados['idcategoria']).' '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and produtos.nome = '.$dados['nome'].' ';

        //busca pelo respota
		$buscaDescricao = '';
		if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
			$buscaDescricao = ' and produtos.descricao = '.$dados['descricao'].' '; 

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
		$colsSql = 'produtos.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idprodutos) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql, categoria.nome as nome_categoria $buscaMax FROM produtos LEFT JOIN categoria ON produtos.idcategoria = categoria.idcategoria WHERE 1  $buscaId $buscaIdcategoria $buscaNome $buscaDescricao $orderBy $buscaLimit ";

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
	 * <p>deleta produtos no banco</p>
	 */
	function deletaProdutos($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM produtos WHERE idprodutos = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	// function inverteStatus($dados)
	// {
	//     include "includes/mysql.php";
	   
	//     $sql = "UPDATE produtos SET					
	// 					status = '".$dados['status']."'						
	// 				WHERE idprodutos = " . $dados['idprodutos'];
	    
	//     if (mysqli_query($conexao, $sql)) {
	//         return $dados['idprodutos'];
	//     } else {
	//         return false;
	//     }
	// }

?>