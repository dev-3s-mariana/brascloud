<?php
// Versao do modulo: 3.00.010416


/**
 * <p>salva planos no banco</p>
 */
function cadastroPlanos($dados)
{
	include "includes/mysql.php";

	foreach ($dados as $k => &$v) {
		if (is_array($v)) continue;
		// if (get_magic_quotes_gpc()) $v = stripslashes($v);
		$v = mysqli_real_escape_string($conexao, utf8_decode($v));
	}

   $dados['nome'] = trim($dados['nome']);

	$sql = "INSERT INTO planos(nome, urlrewrite, status, imagem, banner_topo, resumo, icone_name, icone, descricao, velocidade, vcpu, memoria, iops, disco, preco_hora, preco_mes, zona_sp01, zona_sp02, zona_rs01, windows, linux) VALUES (
						'" . $dados['nome'] . "',
						'" . $dados['urlrewrite'] . "',
                        '" . $dados['status'] . "',
                        '" . $dados['imagem'] . "',
                        '" . $dados['banner_topo'] . "',
                        '" . $dados['resumo'] . "',
                        '" . $dados['icone_name'] . "',
                        '" . $dados['icone'] . "',
                        '" . $dados['descricao'] . "',
                        '" . $dados['velocidade'] . "',
                        '" . $dados['vcpu'] . "',
                        '" . $dados['memoria'] . "',
                        '" . $dados['iops'] . "',
                        '" . $dados['disco'] . "',
                        '" . $dados['preco_hora'] . "',
                        '" . $dados['preco_mes'] . "',
                        '" . $dados['zona_sp01'] . "',
                        '" . $dados['zona_sp02'] . "',
                        '" . $dados['zona_rs01'] . "',
                        '" . $dados['windows'] . "',
                        '" . $dados['linux'] . "'
                    )";

	if (mysqli_query($conexao, $sql)) {
		$resultado = mysqli_insert_id($conexao);
		return $resultado;
	} else {
		return false;
	}
}

/**
 * <p>edita planos no banco</p>
 */
function editPlanos($dados)
{
	include "includes/mysql.php";

	foreach ($dados as $k => &$v) {
		if (is_array($v)) continue;
		// if (get_magic_quotes_gpc()) $v = stripslashes($v);
		$v = mysqli_real_escape_string($conexao, utf8_decode($v));
	}

   $dados['nome'] = trim($dados['nome']);
   $dados['title'] = trim($dados['title']);
   $dados['description'] = trim($dados['description']);
   $dados['keywords'] = trim($dados['keywords']);

	$sql = "UPDATE planos SET
						nome = '" . $dados['nome'] . "',
						status = '" . $dados['status'] . "',
                        icone_name = '" . $dados['icone_name'] . "',
                        icone = '" . $dados['icone'] . "',
                        velocidade = '" . $dados['velocidade'] . "',
                        vcpu = '" . $dados['vcpu'] . "',
                        memoria = '" . $dados['memoria'] . "',
                        disco = '" . $dados['disco'] . "',
                        iops = '" . $dados['iops'] . "',
                        preco_hora = '" . $dados['preco_hora'] . "',
                        preco_mes = '" . $dados['preco_mes'] . "',
                        zona_sp01 = '" . $dados['zona_sp01'] . "',
                        zona_sp02 = '" . $dados['zona_sp02'] . "',
                        zona_rs01 = '" . $dados['zona_rs01'] . "',
                        windows = '" . $dados['windows'] . "',
                        linux = '" . $dados['linux'] . "'
					WHERE idplanos = " . $dados['idplanos'];

	if (mysqli_query($conexao, $sql)) {
		return $dados['idplanos'];
	} else {
		return false;
	}
}

/**
 * <p>busca planos no banco</p>
 */
function buscaPlanos($dados = array())
{
	include "includes/mysql.php";

	foreach ($dados as $k => &$v) {
		if (is_array($v) || $k == "colsSql") continue;
		// if (get_magic_quotes_gpc()) $v = stripslashes($v);
		$v = mysqli_real_escape_string($conexao, utf8_decode($v));
	}

	//busca pelo id
	$buscaId = '';
	if (array_key_exists('idplanos', $dados) && !empty($dados['idplanos']))
		$buscaId = ' and planos.idplanos = ' . intval($dados['idplanos']) . ' ';

	//busca pelo nome
	$buscaNome = '';
	if (array_key_exists('nome', $dados) && !empty($dados['nome']))
		$buscaNome = ' and planos.nome LIKE "%' . $dados['nome'] . '%" ';

	//busca pelo id
	$buscanotId = '';
	if (array_key_exists('not_idplanos', $dados) && !empty($dados['not_idplanos']))
		$buscanotId = ' and NOT planos.idplanos = ' . intval($dados['not_idplanos']) . ' ';

	//busca por urlrewrite
	$buscaUrlrewrite = '';
	if (array_key_exists('urlrewrite', $dados) && !empty($dados['urlrewrite']))
		$buscaUrlrewrite = ' and planos.urlrewrite = "' . $dados['urlrewrite'] . '" ';

	//busca pelo status
    $buscaStatus = '';
    if (array_key_exists('status', $dados) && !empty($dados['status']))
        $buscaStatus = ' and planos.status = ' . $dados['status'] . ' ';

    $buscaCount = '';
    if(array_key_exists('count', $dados)){
        $buscaCount = ", (SELECT COUNT(*) FROM planos WHERE planos.idplanos = planos.idplanos) as qtd_planos";
    }

	//ordem
	$orderBy = "";
	if (isset($dados['ordem']) && !empty($dados['ordem']) && isset($dados['dir'])) {
		$orderBy = ' ORDER BY ' . $dados['ordem'] . " " . $dados['dir'];
	}

	//busca pelo limit
	$buscaLimit = '';
	if (array_key_exists('limit', $dados) && !empty($dados['limit']) && array_key_exists('pagina', $dados)) {
		$buscaLimit = ' LIMIT ' . ($dados['limit'] * $dados['pagina']) . ',' . $dados['limit'] . ' ';
	} elseif (array_key_exists('limit', $dados) && !empty($dados['limit']) && array_key_exists('inicio', $dados)) {
		$buscaLimit = ' LIMIT ' . $dados['limit'] . ',' . $dados['inicio'] . ' ';
	} elseif (array_key_exists('limit', $dados) && !empty($dados['limit'])) {
		$buscaLimit = ' LIMIT ' . $dados['limit'];
	}

	//colunas que serão buscadas
	$colsSql = 'planos.*';
	if (array_key_exists('totalRecords', $dados)) {
		$colsSql = ' count(idplanos) as totalRecords';
		$buscaLimit = '';
		$orderBy = '';
	} elseif (array_key_exists('colsSql', $dados)) {
		$colsSql = ' ' . $dados['colsSql'] . ' ';
	}

	$sql = "SELECT $colsSql FROM planos WHERE 1  $buscaId $buscanotId $buscaUrlrewrite $buscaNome $buscaStatus $orderBy $buscaLimit ";

	$query = mysqli_query($conexao, $sql);
	$resultado = array();
	while ($r = mysqli_fetch_assoc($query)) {
		$r = array_map('utf8_encode', $r);
		if (!array_key_exists('totalRecords', $dados)) {
			$r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
            $r["status_icone"] = "<img src='images/estrela".($r["status"]=='1' ? "sim":"nao").".png' class='icone inverteStatus' codigo='".$r['idplanos']."' width='20px' />";
		}
		$resultado[] = $r;
	}
	return $resultado;
}

/**
 * <p>deleta planos no banco</p>
 */
function deletaPlanos($dados)
{
	include "includes/mysql.php";

	$sql = "DELETE FROM planos WHERE idplanos = $dados";
	if (mysqli_query($conexao, $sql)) {
		return mysqli_affected_rows($conexao);
	} else {
		return FALSE;
	}
}

function apagarImagemPlanos($imgs) {
   $path = 'files/planos/';
   if(file_exists($path)){
      //apaga os arquivos que foram salvos
      if(is_array($imgs)){
         foreach ($imgs as $img) {
            //imagem fundo
            $arquivo = $img['imagem_old'];
            $arquivo2 = str_replace('_', '', $arquivo);
            $medium = "medium_".$arquivo;
            $tall = "tall_".$arquivo;
            $long = "long_".$arquivo;
            $small = "small_".$arquivo;
            $outros = "outros_".$arquivo;
            if(file_exists($path.$arquivo)){
               unlink($path.$arquivo);
            }
            if(file_exists($path.$arquivo2)){
               unlink($path.$arquivo2);
            }
            if(file_exists($path.$medium)){
               unlink($path.$medium);
            }if(file_exists($path.$tall)){
               unlink($path.$tall);
            }if(file_exists($path.$long)){
               unlink($path.$long);
            }if(file_exists($path.$small)){
               unlink($path.$small);
            }if(file_exists($path.$outros)){
               unlink($path.$outros);
            }
            //imagem fundo
            $arquivo = $img['banner_topo_old'];
            $medium = "medium_".$arquivo;
            if(file_exists($path.$arquivo)){
               unlink($path.$arquivo);
            }
            if(file_exists($path.$medium)){
               unlink($path.$medium);
            }
         }
      }else{
         $arquivo = $imgs;
         $arquivo2 = str_replace('_', '', $arquivo);
         $medium = "medium_".$arquivo;
            $tall = "tall_".$arquivo;
            $long = "long_".$arquivo;
            $small = "small_".$arquivo;
            $outros = "outros_".$arquivo;
         if(file_exists($path.$arquivo)){
            unlink($path.$arquivo);
         }
         if(file_exists($path.$arquivo2)){
            unlink($path.$arquivo2);
         }
         if(file_exists($path.$medium)){
            unlink($path.$medium);
         }if(file_exists($path.$tall)){
            unlink($path.$tall);
         }if(file_exists($path.$long)){
            unlink($path.$long);
         }if(file_exists($path.$small)){
            unlink($path.$small);
         }if(file_exists($path.$outros)){
            unlink($path.$outros);
         }
      }
   }
   return true;
}

    /*===============================================Itns===================================================*/

        function cadastroItns($dados)
        {

            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v)) continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            if(!empty($dados['nome']) || !empty($dados['descricao']) || !empty($dados['icone'])){
                if(empty($dados['icone'])){
                    $dados['icone'] = 1;
                }

                $dados['nome'] = trim($dados['nome']);
                $dados['descricao'] = trim($dados['descricao']);

                $sql = "INSERT INTO itns(idplanos, nome, descricao, icone, nome_icone, imagem, ordem) VALUES (
                                '".$dados['idplanos']."',
                                '".$dados['nome']."',
                                '".$dados['descricao']."',
                                '".$dados['icone']."',
                                '".$dados['nome_icone']."',
                                '".$dados['imagem']."',
                                '".$dados['ordem']."'
                            )";
                if (mysqli_query($conexao, $sql)) {
                    $resultado = mysqli_insert_id($conexao);
                    return $resultado;
                } else {
                    return false;
                }
            }
        }

        /**
         * <p>edita itns no banco</p>
         */
        function editItns($dados)
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v)) continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            $dados['nome'] = trim($dados['nome']);
            $dados['descricao'] = trim($dados['descricao']);

            $sql = "UPDATE itns SET
                            nome = '".$dados['nome']."',
                            descricao = '".$dados['descricao']."',
                            icone = '".$dados['icone']."',
                            nome_icone = '".$dados['nome_icone']."',
                            imagem = '".$dados['imagem']."',
                            ordem = '".$dados['ordem']."'
                        WHERE idplanos = ".$dados['idplanos']."
                        AND iditns = ".$dados['iditns'];

            if (mysqli_query($conexao, $sql)) {
                return $dados['iditns'];
            } else {
                return false;
            }
        }

        /**
         * <p>busca itns no banco</p>
         */
        function buscaItns($dados = array())
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v) || $k == "colsSql") continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            //busca pelo id
            $buscaId = '';
            if (array_key_exists('iditns',$dados) && !empty($dados['iditns']) )
                $buscaId = ' and iditns = '.intval($dados['iditns']).' '; 

            //busca pelo nome
            $buscaNome = '';
            if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
                $buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 

            //busca pelo descricao
            $buscaDescricao = '';
            if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
                $buscaDescricao = ' and descricao LIKE "%'.$dados['descricao'].'%" '; 

            //busca pelo idplanos
            $buscaIdPlanos = '';
            if (array_key_exists('idplanos',$dados) && !empty($dados['idplanos']) )
                $buscaIdPlanos = ' and idplanos = '.$dados['idplanos'].' '; 

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
                $colsSql = ' count(iditns) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
            } elseif (array_key_exists('colsSql',$dados)) {
                $colsSql = ' '.$dados['colsSql'].' ';
            }

            $sql = "SELECT $colsSql FROM itns WHERE 1 $buscaId $buscaIdPlanos $buscaNome $buscaDescricao $buscaImagem $orderBy $buscaLimit ";

            $query = mysqli_query($conexao, $sql);
            $resultado = array();
            while ($r = mysqli_fetch_assoc($query)){
                $r = array_map('utf8_encode', $r);
                if (!array_key_exists('totalRecords',$dados)){

                }
                $resultado[] = $r;
            }
            return $resultado; 
        }

        /**
         * <p>deleta itns no banco a partir da edição</p>
         */
        function deletaItns2($dados,$dados2)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM itns WHERE idplanos = $dados AND iditns = $dados2";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        /**
         * <p>deleta itns no banco</p>
         */
        function deletaItns($dados)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM itns WHERE idplanos = $dados";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        function editOrdemItns($dados)
        {
            include "includes/mysql.php";
           
            $sql = "UPDATE itns SET                 
                            ordem = '".$dados['ordem']."'                       
                        WHERE iditns = " . $dados['iditns'];
            
            if (mysqli_query($conexao, $sql)) {
                return $dados['iditns'];
            } else {
                return false;
            }
        }

        function apagarImagemItns($imgs) {
            $path = 'files/itns/';
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

                    if(!empty($arquivo)){
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
            }
            return true;
        }

    // Fim Itns
?>