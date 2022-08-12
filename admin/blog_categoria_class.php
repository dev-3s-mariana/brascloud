<?php
	 // Versao do modulo: 2.20.130114


	/**
	 * <p>salva blog_categoria no banco</p>
	 */
	function cadastroBlog_categoria($dados)
	{
		include "includes/mysql.php";
		include "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		// $dados["urlrewrite"] = converteUrlBlogCategoria($dados["nome"]);

		$sql = "INSERT INTO blog_categoria( nome, status, urlrewrite, keywords,description, title) VALUES (
						'".$dados['nome']."',
						'".$dados['status']."',
						'".$dados["urlrewrite"]."',
						'".$dados['keywords']."',
						'".$dados['description']."',
						'".$dados['title']."')";

		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao); 
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita blog_categoria no banco</p>
	 */
	function editBlog_categoria($dados)
	{
		include "includes/mysql.php";
		include "includes/functions.php";
 
		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
 
		// $dados["urlrewrite"] = converteUrlBlogCategoria($dados["nome"]);

		$sql = "UPDATE blog_categoria SET
						nome = '".$dados['nome']."',
						urlrewrite = '".$dados['urlrewrite']."',
						status = '".$dados['status']."',
						keywords = '".$dados['keywords']."',
						description = '".$dados['description']."',
						title = '".$dados['title']."'
					WHERE idblog_categoria = " . $dados['idblog_categoria'];
 
		if (mysqli_query($conexao, $sql)) {
			return $dados['idblog_categoria'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca blog_categoria no banco</p>
	 */
	function buscaBlog_categoria($dados = array())
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
		if (array_key_exists('idblog_categoria',$dados) && !empty($dados['idblog_categoria']) )
			$buscaId = ' and C.idblog_categoria = '.intval($dados['idblog_categoria']).' ';

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and C.nome LIKE "%'.$dados['nome'].'%" ';

         //busca pelo id
        $buscanotId = '';
        if (array_key_exists('not_idblog_categoria', $dados) && !empty($dados['not_idblog_categoria']))
            $buscanotId = ' and NOT C.idblog_categoria = ' . intval($dados['not_idblog_categoria']) . ' ';

		//busca pelo urlrewrite
		$buscaUrlamigavel = '';
		if (array_key_exists('urlrewrite',$dados) && !empty($dados['urlrewrite']) )
			$buscaUrlamigavel = ' and urlrewrite = "'.$dados['urlrewrite'].'"';


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && $dados['status'] != '')
			$buscaStatus = ' and C.status = '.$dados['status'].' ';

		//busca com post
		$group_by = "";
		$buscaInnerPost = '';
		if (array_key_exists('inner_post',$dados) && !empty($dados['inner_post']) ){
			$buscaInnerPost = ' INNER JOIN blog_post as P on C.idblog_categoria = P.idblog_categoria and P.data_hora <= now()';
			$group_by = "GROUP BY C.idblog_categoria";
		}

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
		$colsSql = 'C.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(C.idblog_categoria) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql, (SELECT count(idblog_post) FROM blog_post WHERE blog_post.idblog_categoria = C.idblog_categoria) as qtd_count FROM blog_categoria as C
				$buscaInnerPost
				WHERE 1  $buscaId  $buscaNome $buscanotId $buscaUrlamigavel  
				$buscaStatus $group_by $orderBy $buscaLimit ";
 	 
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				$r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
				$r["status_icone"] = "<img src='images/estrela".($r["status"]=='1' ? "sim":"nao").".png' class='estacao inverteStatus' codigo='".$r['idblog_categoria']."' width='20px' />";
                
  			}
			$resultado[] = $r;
		}
		return $resultado;
 	}

	/**
	 * <p>deleta blog_categoria no banco</p>
	 */
	function deletaBlog_categoria($dados)
	{
		include "includes/mysql.php";  
 
		$sql = "DELETE FROM blog_categoria WHERE idblog_categoria = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			$sql = "UPDATE blog_post SET idblog_categoria = 0, status='I' WHERE idblog_categoria = $dados";
			mysqli_query($conexao, $sql);
			return $num;
		} else {
			return FALSE;
		}
	}

?>
