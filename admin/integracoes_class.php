<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva integracoes no banco</p>
	 */
	function cadastroIntegracoes($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "INSERT INTO integracoes(integracao, usuario, senha, token) VALUES (
						'".$dados['integracao']."',
						'".$dados['usuario']."',
						'".$dados['senha']."',
						'".$dados['token']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita integracoes no banco</p>
	 */
	function editIntegracoes($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE integracoes SET
						usuario = '".$dados['usuario']."',
						senha = '".$dados['senha']."',
						token = '".$dados['token']."'
					WHERE idintegracoes = " . $dados['idintegracoes'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idintegracoes'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca integracoes no banco</p>
	 */
	function buscaIntegracoes($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idintegracoes',$dados) && !empty($dados['idintegracoes']) )
			$buscaId = ' and idintegracoes = '.intval($dados['idintegracoes']).' '; 

        //busca pelo id
        $buscaIdNot = '';
        if (array_key_exists('not_idintegracoes',$dados) && !empty($dados['not_idintegracoes']) )
            $buscaIdNot = ' and idintegracoes != '.intval($dados['not_idintegracoes']).' '; 

		//busca pelo title
		$buscaTitle = '';
		if (array_key_exists('title',$dados) && !empty($dados['title']) )
			$buscaTitle = ' and title LIKE "%'.$dados['title'].'%" ';

        //busca pelo urlrewrite
        $buscaUrlrewrite = '';
        if (array_key_exists('urlrewrite',$dados) && !empty($dados['urlrewrite']) )
            $buscaUrlrewrite = ' and urlrewrite = "'.$dados['urlrewrite'].'" ';

        //busca pelo respota
		$buscaDescription = '';
		if (array_key_exists('description',$dados) && !empty($dados['description']) )
			$buscaDescription = ' and description = "'.$dados['description'].'" '; 


		//busca pelo keywords
		$buscaKeywords = '';
		if (array_key_exists('keywords',$dados) && !empty($dados['keywords']) )
			$buscaKeywords = ' and keywords = "'.$dados['keywords'].'" ';

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
			$colsSql = ' count(idintegracoes) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql $buscaMax FROM integracoes WHERE 1  $buscaId $buscaIdNot $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		$iAux = 1;
		$tot =  mysqli_affected_rows($conexao);
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){

         }
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta integracoes no banco</p>
	 */
	function deletaIntegracoes($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM integracoes WHERE idintegracoes = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

?>