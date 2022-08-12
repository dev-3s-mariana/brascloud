<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva parceiros no banco</p>
	 */
	function cadastroParceiros($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "INSERT INTO parceiros(titulo, imagem, status) VALUES (
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
	 * <p>edita parceiros no banco</p>
	 */
	function editParceiros($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE parceiros SET
						titulo = '".$dados['titulo']."',
                  imagem = '".$dados['imagem']."',
						status = '".$dados['status']."'
					WHERE idparceiros = " . $dados['idparceiros'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idparceiros'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca parceiros no banco</p>
	 */
	function buscaParceiros($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idparceiros',$dados) && !empty($dados['idparceiros']) )
			$buscaId = ' and idparceiros = '.intval($dados['idparceiros']).' '; 

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
			$colsSql = ' count(idparceiros) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM parceiros WHERE 1  $buscaId $buscaStatus $buscaTitulo  $buscaImagem  $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				!empty($r['imagem']) ? $r['imagem-caminho'] = '<img class="card-autor-adm" src="files/parceiros/'.$r['imagem'].'" class="img-depoimento"/>' : $r['imagem-caminho'] = 'SEM IMAGEM';

		 		$r["status_nome"] = ($r["status"]=='A' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='A' ? "<img src='images/estrelasim.png' class='icone inverteStatus' codigo='".$r['idparceiros']."' width='20px' />":"<img src='images/estrelanao.png' class='icone inverteStatus' codigo='".$r['idparceiros']."' width='20px'/>"); 				
 			}
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta parceiros no banco</p>
	 */
	function deletaParceiros($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM parceiros WHERE idparceiros = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemParceiros($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE parceiros SET					
						ordem = '".$dados['ordem']."'						
					WHERE idparceiros = " . $dados['idparceiros'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idparceiros'];
	    } else {
	        return false;
	    }
	}
?>