<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva contatos no banco</p>
	 */
	function cadastroContatos($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['nome'] = trim($dados['nome']);
		$dados['email'] = trim($dados['email']);
		$dados['mensagem'] = trim($dados['mensagem']);
		$dados['assunto'] = trim($dados['assunto']);

		date_default_timezone_set('America/Sao_Paulo');
		$data = date("Y-m-d H:i:s"); 
		
		$sql = "INSERT INTO contatos(nome, email, telefone, data_hora, mensagem, assunto, resumo_pedido, total_pedido) VALUES (
				'".$dados['nome']."',
				'".((isset($dados['email']))?$dados['email']:'')."',
				'".((isset($dados['telefone']))?$dados['telefone']:'')."',
				'".$data."',
				'".((isset($dados['mensagem']))?$dados['mensagem']:'')."',
				'".((isset($dados['assunto']))?$dados['assunto']:'')."',
                '".((isset($dados['resumo_pedido']))?$dados['resumo_pedido']:'')."',
                '".((isset($dados['total_pedido']))?$dados['total_pedido']:'')."'
            )";
 	  
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao); 
			return $resultado;
		} else {
			return false;
		}
	} 

	/**
	 * <p>edita contatos no banco</p>
	 */
	function editContatos($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['nome'] = trim($dados['nome']);
		$dados['email'] = trim($dados['email']);
		$dados['mensagem'] = trim($dados['mensagem']);
		$dados['assunto'] = trim($dados['assunto']);
		$dados['observacao'] = trim($dados['observacao']);

		$sql = "UPDATE contatos SET
						nome = '".$dados['nome']."', 
						email = '".$dados['email']."', 
						telefone = '".$dados['telefone']."', 
						mensagem = '".$dados['mensagem']."', 
						assunto = '".$dados['assunto']."',
						observacao = '".$dados['observacao']."',
                        total_pedido = '".$dados['total_pedido']."'
					WHERE idcontatos = " . $dados['idcontatos'];
		 
		if (mysqli_query($conexao, $sql)) {
			return $dados['idcontatos'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca contatos no banco</p>
	 */
	function buscaContatos($dados = array())
	{
		include "includes/mysql.php";
		include_once "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idcontatos',$dados) && !empty($dados['idcontatos']) )
			$buscaId = ' and C.idcontatos = '.intval($dados['idcontatos']).' '; 

		//busca pelo id
		$buscaIdIdiomas = '';
		if (array_key_exists('ididiomas',$dados) && !empty($dados['ididiomas']) )
			$buscaIdIdiomas = ' and C.ididiomas = '.intval($dados['ididiomas']).' '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and C.nome LIKE "%'.$dados['nome'].'%" '; 


		//busca pelo email
		$buscaEmail = '';
		if (array_key_exists('email',$dados) && !empty($dados['email']) )
			$buscaEmail = ' and C.email LIKE "%'.$dados['email'].'%" ';

	 
	 	//busca pelo email
		$buscaTelefone = '';
		if (array_key_exists('telefone',$dados) && !empty($dados['telefone']) )
			$buscaTelefone = ' and C.telefone = "'.$dados['telefone'].'" '; 

		//busca pelo email
		$buscaMensagem = '';
		if (array_key_exists('mensagem',$dados) && !empty($dados['mensagem']) )
			$buscaMensagem = ' and C.mensagem = "'.$dados['mensagem'].'" '; 

		//busca pelo email
		$buscaAssunto = '';
		if (array_key_exists('assunto',$dados) && !empty($dados['assunto']) )
			$buscaAssunto = ' and C.assunto = "'.$dados['assunto'].'" '; 
			

	 	//busca pelo email
		$buscaObservacao = '';
		if (array_key_exists('observacao',$dados) && !empty($dados['observacao']) )
			$buscaObservacao = ' and C.observacao = "'.$dados['observacao'].'" '; 

		//busca pelo email
		$buscaData = '';
		if (array_key_exists('data',$dados) && !empty($dados['data']) )
			$buscaData = ' and C.data_hora = "'.$dados['data'].'" '; 

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
		// $colsSql = 'C.*, I.idioma'; 
		$colsSql = 'C.*'; 

		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(C.idcontatos) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		// $sql = "SELECT $colsSql, date_format(C.data_hora, '%d/%m/%Y') as data_formatada
		// 		FROM contatos as C  
		// 		INNER JOIN idiomas as I on C.ididiomas = I.ididiomas
		// 		WHERE 1 $buscaId $buscaIdIdiomas $buscaNome $buscaAssunto $buscaMensagem $buscaEmail $buscaTelefone $buscaObservacao $buscaData $orderBy $buscaLimit ";
		$sql = "SELECT $colsSql, date_format(C.data_hora, '%d/%m/%Y') as data_formatada
				FROM contatos as C WHERE 1 $buscaId $buscaIdIdiomas $buscaNome $buscaAssunto $buscaMensagem $buscaEmail $buscaTelefone $buscaObservacao $buscaData $orderBy $buscaLimit ";
		
		
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			$resultado[] = $r;
		} 
		return $resultado;  
 	}

	/**
	 * <p>deleta contatos no banco</p>
	 */
	function deletaContatos($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM contatos WHERE idcontatos = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	} 
	 
?>