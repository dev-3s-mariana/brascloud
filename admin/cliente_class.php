<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva cliente no banco</p>
	 */
	function cadastroCliente($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "INSERT INTO cliente(titulo, imagem, status, idsegmento) VALUES (
						'".$dados['titulo']."',
                        '".$dados['imagem']."',
						'".$dados['status']."',
                        '".$dados['idsegmento']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita cliente no banco</p>
	 */
	function editCliente($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE cliente SET
						titulo = '".$dados['titulo']."',
                        imagem = '".$dados['imagem']."',
						status = '".$dados['status']."',
                        idsegmento = '".$dados['idsegmento']."'
					WHERE idcliente = " . $dados['idcliente'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idcliente'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca cliente no banco</p>
	 */
	function buscaCliente($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idcliente',$dados) && !empty($dados['idcliente']) )
			$buscaId = ' and c.idcliente = '.intval($dados['idcliente']).' '; 

        //busca pelo id
        $buscaIdsegmento = '';
        if (array_key_exists('idsegmento',$dados) && !empty($dados['idsegmento']) )
            $buscaIdsegmento = ' and c.idsegmento = '.intval($dados['idsegmento']).' '; 

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
			$colsSql = ' count(idcliente) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql, s.titulo as nome_segmento FROM cliente as c LEFT JOIN segmento as s ON c.idsegmento = s.idsegmento WHERE 1  $buscaId $buscaIdsegmento $buscaStatus $buscaTitulo  $buscaImagem  $orderBy $buscaLimit ";

        // if (array_key_exists('teste',$dados)){
        //     print_r($sql);
        // }

		$query = mysqli_query($conexao, $sql);
		$resultado = array();

		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
		 		$r["status_nome"] = ($r["status"]=='A' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='A' ? "<img src='images/estrelasim.png' class='icone inverteStatus' codigo='".$r['idcliente']."' width='20px' />":"<img src='images/estrelanao.png' class='icone inverteStatus' codigo='".$r['idcliente']."' width='20px'/>"); 				
 			}
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta cliente no banco</p>
	 */
	function deletaCliente($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM cliente WHERE idcliente = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemCliente($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE cliente SET					
						ordem = '".$dados['ordem']."'						
					WHERE idcliente = " . $dados['idcliente'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idcliente'];
	    } else {
	        return false;
	    }
	}
?>