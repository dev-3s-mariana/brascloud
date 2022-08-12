<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva tecnologias no banco</p>
	 */
	function cadastroTecnologias($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "INSERT INTO tecnologias(titulo, imagem, status) VALUES (
						'".$dados['titulo']."',
                  '".$dados['imagem']."',
						'".$dados['status']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita tecnologias no banco</p>
	 */
	function editTecnologias($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE tecnologias SET
						titulo = '".$dados['titulo']."',
                  imagem = '".$dados['imagem']."',
						status = '".$dados['status']."'
					WHERE idtecnologias = " . $dados['idtecnologias'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idtecnologias'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca tecnologias no banco</p>
	 */
	function buscaTecnologias($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idtecnologias',$dados) && !empty($dados['idtecnologias']) )
			$buscaId = ' and idtecnologias = '.intval($dados['idtecnologias']).' '; 

		//busca pelo titulo
		$buscaTitulo = '';
		if (array_key_exists('titulo',$dados) && !empty($dados['titulo']) )
			$buscaTitulo = ' and titulo LIKE "%'.$dados['titulo'].'%" '; 

      //busca pelo status
      $buscaStatus = '';
      if (array_key_exists('status',$dados) && !empty($dados['status']) )
         $buscaStatus = ' and status = "'.$dados['status'].'" '; 

		//busca pelo imagem
		$buscaImagem = '';
		if (array_key_exists('imagem',$dados) && !empty($dados['imagem']) )
			$buscaImagem = ' and imagem LIKE "%'.$dados['imagem'].'%" '; 

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
		$colsSql = '*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idtecnologias) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM tecnologias WHERE 1  $buscaId $buscaStatus $buscaTitulo  $buscaImagem  $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				!empty($r['imagem']) ? $r['imagem-caminho'] = '<img class="card-autor-adm" src="files/tecnologias/'.$r['imagem'].'" class="img-depoimento"/>' : $r['imagem-caminho'] = 'SEM IMAGEM';

		 		$r["status_nome"] = ($r["status"]=='A' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='A' ? "<img src='images/estrelasim.png' class='icone inverteStatus' codigo='".$r['idtecnologias']."' width='20px' />":"<img src='images/estrelanao.png' class='icone inverteStatus' codigo='".$r['idtecnologias']."' width='20px'/>"); 				
 			}
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta tecnologias no banco</p>
	 */
	function deletaTecnologias($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM tecnologias WHERE idtecnologias = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemTecnologias($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE tecnologias SET					
						ordem = '".$dados['ordem']."'						
					WHERE idtecnologias = " . $dados['idtecnologias'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idtecnologias'];
	    } else {
	        return false;
	    }
	}
?>