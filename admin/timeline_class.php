<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva timeline no banco</p>
	 */
	function cadastroTimeline($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "INSERT INTO timeline(titulo, status, ano, texto, imagem, imagem_2) VALUES (
    					'".$dados['titulo']."',
    					'".$dados['status']."',
                        '".$dados['ano']."',
                        '".$dados['texto']."',
                        '".$dados['imagem']."',
    					'".$dados['imagem_2']."'
                    )";
                    
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita timeline no banco</p>
	 */
	function editTimeline($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE timeline SET
						titulo = '".$dados['titulo']."',
						ano = '".$dados['ano']."',
                        status = '".$dados['status']."',
						texto = '".$dados['texto']."',
                        imagem = '".$dados['imagem']."',
                        imagem_2 = '".$dados['imagem_2']."'
					WHERE idtimeline = " . $dados['idtimeline'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idtimeline'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca timeline no banco</p>
	 */
	function buscaTimeline($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idtimeline',$dados) && !empty($dados['idtimeline']) )
			$buscaId = ' and idtimeline = '.intval($dados['idtimeline']).' '; 

		//busca pelo titulo
		$buscaTitulo = '';
		if (array_key_exists('titulo',$dados) && !empty($dados['titulo']) )
			$buscaTitulo = ' and titulo LIKE "%'.$dados['titulo'].'%" ';

		$buscaData = '';
		if (array_key_exists('data',$dados) && !empty($dados['data']) )
			$buscaData = ' and data LIKE "%'.$dados['data'].'%" ';

		//busca pelo imagem
		$buscaImagem = '';
		if (array_key_exists('imagem',$dados) && !empty($dados['imagem']) )
			$buscaImagem = ' and imagem LIKE "%'.$dados['imagem'].'%" '; 

      //busca pelo texto
      $buscaTexto = '';
      if (array_key_exists('texto',$dados) && !empty($dados['texto']) )
         $buscaTexto = ' and texto LIKE "%'.$dados['texto'].'%" '; 

		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && $dados['status'] != '')
			$buscaStatus = ' and status LIKE "%'.$dados['status'].'%" '; 

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
			$colsSql = ' count(idtimeline) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM timeline WHERE 1 $buscaId $buscaTitulo $buscaStatus $buscaData $buscaTexto $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				$r["status_nome"] = ($r["status"] == '1' ? "Ativo":"Inativo");
                $r["status_icone"] = '<img src="images/estrela'.($r["status"] =="1" ? "sim":"nao").'.png" class="icone inverteStatus" codigo="'.$r['idtimeline'].'" width="20px" />';
            }
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta timeline no banco</p>
	 */
	function deletaTimeline($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM timeline WHERE idtimeline = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemTimeline($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE timeline SET					
						ordem = '".$dados['ordem']."'						
					WHERE idtimeline = " . $dados['idtimeline'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idtimeline'];
	    } else {
	        return false;
	    }
	}
?>