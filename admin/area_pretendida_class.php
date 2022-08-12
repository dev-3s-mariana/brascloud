<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva area_pretendida no banco</p>
	 */
	function cadastroArea_pretendida($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "INSERT INTO area_pretendida(nome, email, status) VALUES (
						'".$dados['nome']."',
						'".$dados['email']."',
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
	 * <p>edita area_pretendida no banco</p>
	 */
	function editArea_pretendida($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE area_pretendida SET
						nome = '".$dados['nome']."',
						email = '".$dados['email']."',
						status = '".$dados['status']."',
						ididiomas = '".$dados['ididiomas']."'
					WHERE idarea_pretendida = " . $dados['idarea_pretendida'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idarea_pretendida'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca area_pretendida no banco</p>
	 */
	function buscaArea_pretendida($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idarea_pretendida',$dados) && !empty($dados['idarea_pretendida']) )
			$buscaId = ' and A.idarea_pretendida = '.intval($dados['idarea_pretendida']).' '; 



		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and A.nome LIKE "%'.$dados['nome'].'%" '; 


		//busca pelo email
		$buscaEmail = '';
		if (array_key_exists('email',$dados) && !empty($dados['email']) )
			$buscaEmail = ' and A.email LIKE "%'.$dados['email'].'%" '; 


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && !empty($dados['status']) )
			$buscaStatus = ' and A.status LIKE "%'.$dados['status'].'%" '; 

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
		$colsSql = 'A.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(A.idarea_pretendida) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM area_pretendida as A
				WHERE 1	$buscaId  $buscaNome $buscaEmail  $buscaStatus  
				$orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
			 	$r["status_nome"] = ($r["status"]=='A' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='A' ? "<img src='images/estrelasim.png' width='20px' />":"<img src='images/estrelanao.png' width='20px'/>");
 	 		}
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta area_pretendida no banco</p>
	 */
	function deletaArea_pretendida($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM area_pretendida WHERE idarea_pretendida = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}


?>