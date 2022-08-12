<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva relatorios no banco</p>
	 */
	function cadastroRelatorios($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "INSERT INTO relatorios(titulo, status, ano, texto, imagem, arquivo) VALUES (
						'".$dados['titulo']."',
						'".$dados['status']."',
                        '".$dados['ano']."',
                        '".$dados['texto']."',
                        '".$dados['imagem']."',
                        '".$dados['arquivo']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita relatorios no banco</p>
	 */
	function editRelatorios($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['titulo'] = trim($dados['titulo']);

		$sql = "UPDATE relatorios SET
						titulo = '".$dados['titulo']."',
						ano = '".$dados['ano']."',
                        status = '".$dados['status']."',
						texto = '".$dados['texto']."',
                        imagem = '".$dados['imagem']."',
                        arquivo = '".$dados['arquivo']."'
					WHERE idrelatorios = " . $dados['idrelatorios'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idrelatorios'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca relatorios no banco</p>
	 */
	function buscaRelatorios($dados = array())
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
		if (array_key_exists('idrelatorios',$dados) && !empty($dados['idrelatorios']) )
			$buscaId = ' and idrelatorios = '.intval($dados['idrelatorios']).' '; 

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
			$colsSql = ' count(idrelatorios) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM relatorios WHERE 1 $buscaId $buscaTitulo $buscaStatus $buscaData $buscaTexto $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados)){
				$r["status_nome"] = ($r["status"] == '1' ? "Ativo":"Inativo");
                $r["status_icone"] = '<img src="images/estrela'.($r["status"] =="1" ? "sim":"nao").'.png" class="icone inverteStatus" codigo="'.$r['idrelatorios'].'" width="20px" />';
            }
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta relatorios no banco</p>
	 */
	function deletaRelatorios($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM relatorios WHERE idrelatorios = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemRelatorios($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE relatorios SET					
						ordem = '".$dados['ordem']."'						
					WHERE idrelatorios = " . $dados['idrelatorios'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['idrelatorios'];
	    } else {
	        return false;
	    }
	}

	function apagarImagemRelatorios($imgs) {
        $path = 'files/relatorios/';
        if(file_exists($path)){
            //apaga os arquivos que foram salvos
            if(is_array($imgs)){
                foreach ($imgs as $img) {
                    //imagem fundo
                    $arquivo = $img['imagem_old'];
                    $arquivo2 = str_replace('_', '', $arquivo);
                    $original = "original_".$arquivo;

                    if(file_exists($path.$arquivo)){
                        unlink($path.$arquivo);
                    }
                    if(file_exists($path.$arquivo2)){
                        unlink($path.$arquivo2);
                    }
                    if(file_exists($path.$original)){
                        unlink($path.$original);
                    }

                    //imagem fundo
                    $arquivo = $img['imagem_old'];
                    $original = "original_".$arquivo;

                    if(file_exists($path.$arquivo)){
                        unlink($path.$arquivo);
                    }
                    if(file_exists($path.$original)){
                        unlink($path.$original);
                    }
                }
            }else{
                $arquivo = $imgs;

                $arquivo2 = str_replace('_', '', $arquivo);
                $original = "original_".$arquivo;

                if(file_exists($path.$arquivo)){
                    unlink($path.$arquivo);
                }
                if(file_exists($path.$arquivo2)){
                    unlink($path.$arquivo2);
                }
                if(file_exists($path.$original)){
                    unlink($path.$original);
                }
            }
        }
        return true;
    }

    function excluirArquivoRelatorios($dados)
    {
        include "includes/mysql.php";
        if (!empty($dados)) {
             $sql = "UPDATE relatorios SET
                      arquivo = ''
                   WHERE idrelatorios = " . $dados['idrelatorios'];

           if (mysqli_query($conexao, $sql)) {
              $num = mysqli_affected_rows($conexao);

              $caminho = "files/relatorios/arquivos/".$dados['arquivo'];
                 
             if (file_exists($caminho)) {
                unlink($caminho);
             }
              
              return true;
           } else {
              return FALSE;
           } 
        }
    }

?>