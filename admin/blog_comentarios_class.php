<?php
	 // Versao do modulo: 2.20.130114

	/**
	 * <p>salva blog_comentarios no banco</p>
	*/
	function cadastroBlog_comentarios($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "INSERT INTO blog_comentarios(nome, email, comentario, idblog_post, status, imagem, data) VALUES (
						'".$dados['nome']."',
						'".$dados['email']."',
						'".$dados['comentario']."',
						'".$dados['idblog_post']."',
						'".$dados['status']."',
                  '".$dados['imagem']."',
						CURRENT_TIMESTAMP())";

		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita blog_comentarios no banco</p>
	 */
	function editBlog_comentarios($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$tmpData = explode('/', stripslashes($dados['data']));
	 	$data= date('Ymd', mktime(0,0,0,$tmpData[1],$tmpData[0],$tmpData[2]));
		$data = $data . date('His');

		$sql = "UPDATE blog_comentarios SET
						nome = '".$dados['nome']."',
						email = '".$dados['email']."',
						comentario = '".$dados['comentario']."',
						idblog_post = '".$dados['idblog_post']."',
						status = '".$dados['status']."',
						data = '".$data."'
					WHERE idblog_comentarios = " . $dados['idblog_comentarios'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idblog_comentarios'];
		} else {
			return false;
		}
	}

	function alterarStatusBlog_comentarios($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$tmpData = explode('/', stripslashes($dados['data']));

		$sql = "UPDATE blog_comentarios SET
					   status = '".$dados['status']."'
					   WHERE idblog_comentarios = " . $dados['idblog_comentarios'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idblog_comentarios'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca blog_comentarios no banco</p>
	*/
	function buscaBlog_comentarios($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}


		$buscaId = '';
		if (array_key_exists('idblog_comentarios',$dados) && !empty($dados['idblog_comentarios']) )
			$buscaId = ' and C.idblog_comentarios = '.intval($dados['idblog_comentarios']).' ';

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and C.nome LIKE "%'.$dados['nome'].'%" ';


		//busca pelo email
		$buscaEmail = '';
		if (array_key_exists('email',$dados) && !empty($dados['email']) )
			$buscaEmail = ' and C.email LIKE "%'.$dados['email'].'%" ';


		//busca pelo comentario
		$buscaComentario = '';
		if (array_key_exists('comentario',$dados) && !empty($dados['comentario']) )
			$buscaComentario = ' and C.comentario LIKE "%'.$dados['comentario'].'%" ';
		
			//busca pelo comentario
		$buscaSubComent = '';
		if (array_key_exists('subcomentario',$dados) && !empty($dados['subcomentario']) )
			$buscaSubComent = ' and C.subcomentario = "'.$dados['subcomentario'].'" ';


		//busca pelo idblog_post
		$buscaIdpost = '';
		if (array_key_exists('idblog_post',$dados) && !empty($dados['idblog_post']) )
			$buscaIdpost = ' and C.idblog_post = '.$dados['idblog_post'].' ';


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && !empty($dados['status']) )
			$buscaStatus = ' and C.status = "'.$dados['status'].'"';

		//busca pelo status
		if (array_key_exists('status2',$dados) && !empty($dados['status2']) )
			$buscaStatus = ' and C.status = '.$dados['status2'].' ';

		$buscaData = '';
		//busca pelo data

		if (array_key_exists('data',$dados) && !empty($dados['data']) )
			$buscaData = ' and C.data = '.$dados['data'].' ';

        //ordem
        $orderBy = "";
        if (isset($dados['ordem']) && !empty($dados['ordem'])){
			$orderBy = ' ORDER BY '.$dados['ordem'];
			if (isset($dados['dir']) && !empty($dados['dir'])){
				$orderBy .= ' '.$dados['dir'] ;
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

        //se existe data de e data ate
        if(isset($dados['dataDe'] ) &&  ($dados['dataDe'] != '')){
        	$dataDe	=explode('/', $dados['dataDe']);
        	$dados['dataDe'] = $dataDe[2].'-'.$dataDe[1].'-'.$dataDe[0].' 00:00:00';

        }
        if(isset($dados['dataAte'] ) &&  ($dados['dataAte'] != '')){
        	$dataAte = explode('/', $dados['dataAte']);
        	$dados['dataAte'] = $dataAte[2].'-'.$dataAte[1].'-'.$dataAte[0].' 23:59:59';

        }

        $entreData="";
        if(array_key_exists('dataDe',$dados) && !empty($dados['dataDe']) && array_key_exists('dataAte',$dados) && empty($dados['dataAte'])){
			$entreData = " and C.data >  '".$dados['dataDe']."'";
        }else if(array_key_exists('dataDe',$dados) && empty($dados['dataDe']) && array_key_exists('dataAte',$dados) && !empty($dados['dataAte'])){
			$entreData = " and C.data <  '".$dados['dataAte']."'";
        }else if(array_key_exists('dataDe',$dados) && !empty($dados['dataDe']) && array_key_exists('dataAte',$dados) && !empty($dados['dataAte'])) {
			$entreData = " and C.data BETWEEN '".$dados['dataDe']."' AND '".$dados['dataAte']."'";
		}


		//colunas que serão buscadas
		$colsSql = 'C.*, date_format(C.data, "%d/%m/%Y") as data_formatado, date_format(C.data, "%d/%m") as data_formatado2, date_format(C.data, "%H:%i") as hora_formatado, P.nome as nomePost';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idblog_comentarios) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql
				FROM blog_comentarios as C
				INNER JOIN blog_post as P on C.idblog_post = P.idblog_post
				WHERE 1 $buscaId $buscaNome $buscaEmail
				$buscaComentario $buscaSubComent $buscaIdpost
				$buscaStatus  $buscaData $entreData $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		$iAux = 1;
		$tot =  mysqli_affected_rows($conexao);
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				if($r["status"] == "2"){$spanTxt = "Aprovado";}elseif($r["status"] == '3'){$spanTxt = "Reprovado";}else{$spanTxt = "Pendente";}
				$r["status_nome"] = $spanTxt;
                $r["status_icone"] = '<span>'.$spanTxt.'<span/>';
            }
			$resultado[] = $r;
		}
		return $resultado;
 	}

	/**
	 * <p>deleta blog_comentarios no banco</p>
	 */
	function deletaBlog_comentarios($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM blog_comentarios WHERE idblog_comentarios = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}


?>
