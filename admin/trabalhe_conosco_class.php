<?php @session_start();
	 // Versao do modulo: 3.00.010416

	/**
	 * <p>salva trabalhe_conosco no banco</p>
	 */
	function cadastroTrabalhe_conosco($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "INSERT INTO trabalhe_conosco(nome, email, telefone, idarea_pretendida, arquivo, data_hora, ip, mensagem) VALUES (
						'".$dados['nome']."',
						'".$dados['email']."',
						'".$dados['telefone']."',
						'".$dados['idarea_pretendida']."',
						'".$dados['arquivo']."',
						NOW(),
						'".$_SERVER['REMOTE_ADDR']."',
                  '".$dados['mensagem']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita trabalhe_conosco no banco</p>
	 */
	function editTrabalhe_conosco($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		 
		$sql = "UPDATE trabalhe_conosco SET
						nome = '".$dados['nome']."',
						email = '".$dados['email']."',
						telefone = '".$dados['telefone']."',
						idarea_pretendida= '".$dados['idarea_pretendida']."',
                  arquivo= '".$dados['arquivo']."',
						mensagem = '".$dados['mensagem']."'
					WHERE idtrabalhe_conosco = " . $dados['idtrabalhe_conosco'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idtrabalhe_conosco'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca trabalhe_conosco no banco</p>
	 */
	function buscaTrabalhe_conosco($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

	 	if(isset($_SESSION['sgc_tipouser']) && $_SESSION['sgc_tipouser'] != "A"){ 
		 		$dados['interesse'] = "L"; 
		}
 
		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idtrabalhe_conosco',$dados) && !empty($dados['idtrabalhe_conosco']) )
			$buscaId = ' and T.idtrabalhe_conosco = '.intval($dados['idtrabalhe_conosco']).' '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and T.nome LIKE "%'.$dados['nome'].'%" '; 

		//busca pelo email
		$buscaEmail = '';
		if (array_key_exists('email',$dados) && !empty($dados['email']) )
			$buscaEmail = ' and T.email LIKE "%'.$dados['email'].'%" '; 


		//busca pelo telefone
		$buscaTelefone = '';
		if (array_key_exists('telefone',$dados) && !empty($dados['telefone']) )
			$buscaTelefone = ' and telefone LIKE "%'.$dados['telefone'].'%" '; 


		//busca pelo interesse
		$buscaAreaPretendida = '';
		if (array_key_exists('idarea_pretendida',$dados) && !empty($dados['idarea_pretendida']) )
			$buscaAreaPretendida = ' and T.idarea_pretendida = "'.$dados['idarea_pretendida'].'" '; 


		//busca pelo arquivo
		$buscaArquivo = '';
		if (array_key_exists('arquivo',$dados) && !empty($dados['arquivo']) )
			$buscaArquivo = ' and T.arquivo LIKE "%'.$dados['arquivo'].'%" '; 

      //busca pelo mensagem
      $buscaMensagem = '';
      if (array_key_exists('mensagem',$dados) && !empty($dados['mensagem']) )
         $buscaMensagem = ' and T.mensagem LIKE "%'.$dados['mensagem'].'%" '; 


		//busca pelo data_hora
		$buscaData_hora = '';
		if (array_key_exists('data_hora',$dados) && !empty($dados['data_hora']) )
			$buscaData_hora = ' and data_hora = '.$dados['data_hora'].' '; 


		//busca pelo ip
		$buscaIp = '';
		if (array_key_exists('ip',$dados) && !empty($dados['ip']) )
			$buscaIp = ' and ip LIKE "%'.$dados['ip'].'%" '; 

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
		$colsSql = "T.*, A.nome as nome_area";
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(T.idtrabalhe_conosco) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM trabalhe_conosco as T 
				LEFT JOIN area_pretendida as A on T.idarea_pretendida = A.idarea_pretendida
				WHERE 1  $buscaId $buscaNome $buscaEmail $buscaTelefone  $buscaAreaPretendida  $buscaArquivo $buscaMensagem $buscaData_hora  $buscaIp $orderBy $buscaLimit ";
 	 	 
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);  
			$resultado[] = $r;
		}
	 
		return $resultado; 
 	}

	/**
	 * <p>deleta trabalhe_conosco no banco</p>
	 */
	function deletaTrabalhe_conosco($dados)
	{
		include "includes/mysql.php";

		$arquivo = buscaTrabalhe_conosco(array('idtrabalhe_conosco'=>$dados));
		$arquivo = $arquivo[0]['arquivo'];
		
		$sql = "DELETE FROM trabalhe_conosco WHERE idtrabalhe_conosco = $dados";		
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			if(file_exists($arquivo)){
				unlink($arquivo);
			}
			return $num;
		} else {
			return FALSE;
		}
	}


?>