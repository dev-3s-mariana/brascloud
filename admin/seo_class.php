<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva seo no banco</p>
	 */
	function cadastroSeo($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "INSERT INTO seo(title, description, keywords, urlrewrite) VALUES (
						'".$dados['title']."',
						'".$dados['description']."',
						'".$dados['keywords']."',
						'".$dados['urlrewrite']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita seo no banco</p>
	 */
	function editSeo($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE seo SET
						title = '".$dados['title']."',
						description = '".$dados['description']."',
						keywords = '".$dados['keywords']."',
						urlrewrite = '".$dados['urlrewrite']."'
					WHERE idseo = " . $dados['idseo'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idseo'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca seo no banco</p>
	 */
	function buscaSeo($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idseo',$dados) && !empty($dados['idseo']) )
			$buscaId = ' and idseo = '.intval($dados['idseo']).' '; 

        //busca pelo id
        $buscaIdNot = '';
        if (array_key_exists('not_idseo',$dados) && !empty($dados['not_idseo']) )
            $buscaIdNot = ' and idseo != '.intval($dados['not_idseo']).' '; 

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
			$colsSql = ' count(idseo) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql $buscaMax FROM seo WHERE 1  $buscaId $buscaIdNot $buscaTitle $buscaUrlrewrite $buscaDescription $buscaKeywords $orderBy $buscaLimit ";

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
	 * <p>deleta seo no banco</p>
	 */
	function deletaSeo($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM seo WHERE idseo = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

?>