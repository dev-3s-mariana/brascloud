<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva contratar no banco</p>
	 */
	function cadastroContratar($dados)
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

		$sql = "INSERT INTO contratar(nome, descricao, preco, texto) VALUES (
						'".$dados['nome']."',
						'".$dados['descricao']."',
                        '".$dados['preco']."',
                        '".$dados['texto']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita contratar no banco</p>
	 */
	function editContratar($dados)
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

		$sql = "UPDATE contratar SET
						nome = '".$dados['nome']."',
                        preco = '".$dados['preco']."',
                        descricao = '".$dados['descricao']."',
                        texto = '".$dados['texto']."'
					WHERE idcontratar = " . $dados['idcontratar'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idcontratar'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca contratar no banco</p>
	 */
	function buscaContratar($dados = array())
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
		if (array_key_exists('idcontratar',$dados) && !empty($dados['idcontratar']) )
			$buscaId = ' and idcontratar = '.intval($dados['idcontratar']).' '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and nome = '.$dados['nome'].' ';

        //busca pelo respota
		$buscaDescricao = '';
		if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
			$buscaDescricao = ' and descricao = '.$dados['descricao'].' '; 

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
		$colsSql = '*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idcontratar) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql $buscaMax FROM contratar WHERE 1  $buscaId $buscaNome $buscaDescricao $orderBy $buscaLimit ";

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
	 * <p>deleta contratar no banco</p>
	 */
	function deletaContratar($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM contratar WHERE idcontratar = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	// function inverteStatus($dados)
	// {
	//     include "includes/mysql.php";
	   
	//     $sql = "UPDATE contratar SET					
	// 					status = '".$dados['status']."'						
	// 				WHERE idcontratar = " . $dados['idcontratar'];
	    
	//     if (mysqli_query($conexao, $sql)) {
	//         return $dados['idcontratar'];
	//     } else {
	//         return false;
	//     }
	// }

?>