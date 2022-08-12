<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva projeto no banco</p>
	 */
	function cadastroProjeto($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "INSERT INTO projeto(titulo, imagem, status, idcliente, descricao, texto, titulo_caixa) VALUES (
						'".$dados['titulo']."',
                        '".$dados['imagem']."',
						'".$dados['status']."',
                        '".$dados['idcliente']."',
                        '".$dados['descricao']."',
                        '".$dados['texto']."',
                        '".$dados['titulo_caixa']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita projeto no banco</p>
	 */
	function editProjeto($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE projeto SET
						titulo = '".$dados['titulo']."',
                        imagem = '".$dados['imagem']."',
						status = '".$dados['status']."',
                        idcliente = '".$dados['idcliente']."',
                        descricao = '".$dados['descricao']."',
                        texto = '".$dados['texto']."',
                        titulo_caixa = '".$dados['titulo_caixa']."'
					WHERE idprojeto = " . $dados['idprojeto'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idprojeto'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca projeto no banco</p>
	 */
	function buscaProjeto($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idprojeto',$dados) && !empty($dados['idprojeto']) )
			$buscaId = ' and c.idprojeto = '.intval($dados['idprojeto']).' '; 

        //busca pelo id
        $buscaIdcliente = '';
        if (array_key_exists('idcliente',$dados) && !empty($dados['idcliente']) )
            $buscaIdcliente = ' and c.idcliente = '.intval($dados['idcliente']).' '; 

		//busca pelo titulo
		$buscaTitulo = '';
		if (array_key_exists('titulo',$dados) && !empty($dados['titulo']) )
			$buscaTitulo = ' and c.titulo LIKE "%'.$dados['titulo'].'%" '; 

      //busca pelo status
      $buscaStatus = '';
      if (array_key_exists('status',$dados) && !empty($dados['status']) )
         $buscaStatus = ' and c.status = "'.$dados['status'].'" '; 

		//busca pelo imagem
		$buscaImagem = '';
		if (array_key_exists('imagem',$dados) && !empty($dados['imagem']) )
			$buscaImagem = ' and c.imagem LIKE "%'.$dados['imagem'].'%" '; 

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
		$colsSql = 'c.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idprojeto) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql, s.titulo as nome_cliente FROM projeto as c LEFT JOIN cliente as s ON c.idcliente = s.idcliente WHERE 1  $buscaId $buscaIdcliente $buscaStatus $buscaTitulo  $buscaImagem  $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				!empty($r['imagem']) ? $r['imagem-caminho'] = '<img class="card-autor-adm" src="files/projeto/'.$r['imagem'].'" class="img-depoimento"/>' : $r['imagem-caminho'] = 'SEM IMAGEM';

		 		$r["status_nome"] = ($r["status"]=='A' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='A' ? "<img src='images/estrelasim.png' class='icone inverteStatus' codigo='".$r['idprojeto']."' width='20px' />":"<img src='images/estrelanao.png' class='icone inverteStatus' codigo='".$r['idprojeto']."' width='20px'/>"); 				
 			}
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta projeto no banco</p>
	 */
	function deletaProjeto($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM projeto WHERE idprojeto = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemProjeto($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE projeto SET					
						ordem = '".$dados['ordem']."'						
					WHERE idprojeto = " . $dados['idprojeto'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idprojeto'];
	    } else {
	        return false;
	    }
	}
?>