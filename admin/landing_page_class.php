<?php
// Versao do modulo: 3.00.010416


/**
 * <p>salva landing_page no banco</p>
 */
function cadastroLanding_page($dados)
{
	include "includes/mysql.php";

	foreach ($dados as $k => &$v) {
		if (is_array($v)) continue;
		// if (get_magic_quotes_gpc()) $v = stripslashes($v);
		$v = mysqli_real_escape_string($conexao, utf8_decode($v));
	}

   $dados['nome'] = trim($dados['nome']);

	$sql = "INSERT INTO landing_page(nome, urlrewrite, status, imagem, banner_topo, resumo, icone_name, icone, descricao, titulo, diferenciais_texto) VALUES (
						'" . $dados['nome'] . "',
						'" . $dados['urlrewrite'] . "',
                        '" . $dados['status'] . "',
                        '" . $dados['imagem'] . "',
                        '" . $dados['banner_topo'] . "',
                        '" . $dados['resumo'] . "',
                        '" . $dados['icone_name'] . "',
                        '" . $dados['icone'] . "',
                        '" . $dados['descricao'] . "',
                        '" . $dados['titulo'] . "',
                        '" . $dados['diferenciais_texto'] . "'
                    )";

	if (mysqli_query($conexao, $sql)) {
		$resultado = mysqli_insert_id($conexao);
		return $resultado;
	} else {
		return false;
	}
}

/**
 * <p>edita landing_page no banco</p>
 */
function editLanding_page($dados)
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

	$sql = "UPDATE landing_page SET
                        banner_topo = '" . $dados['banner_topo'] . "',
                        descricao = '" . $dados['descricao'] . "',
                        titulo = '" . $dados['titulo'] . "',
                        diferenciais_texto = '" . $dados['diferenciais_texto'] . "'
					WHERE idlanding_page = " . $dados['idlanding_page'];

	if (mysqli_query($conexao, $sql)) {
		return $dados['idlanding_page'];
	} else {
		return false;
	}
}

/**
 * <p>busca landing_page no banco</p>
 */
function buscaLanding_page($dados = array())
{
	include "includes/mysql.php";

	foreach ($dados as $k => &$v) {
		if (is_array($v) || $k == "colsSql") continue;
		// if (get_magic_quotes_gpc()) $v = stripslashes($v);
		$v = mysqli_real_escape_string($conexao, utf8_decode($v));
	}

	//busca pelo id
	$buscaId = '';
	if (array_key_exists('idlanding_page', $dados) && !empty($dados['idlanding_page']))
		$buscaId = ' and landing_page.idlanding_page = ' . intval($dados['idlanding_page']) . ' ';

	//busca pelo nome
	$buscaNome = '';
	if (array_key_exists('nome', $dados) && !empty($dados['nome']))
		$buscaNome = ' and landing_page.nome LIKE "%' . $dados['nome'] . '%" ';

	//busca pelo id
	$buscanotId = '';
	if (array_key_exists('not_idlanding_page', $dados) && !empty($dados['not_idlanding_page']))
		$buscanotId = ' and NOT landing_page.idlanding_page = ' . intval($dados['not_idlanding_page']) . ' ';

	//busca por urlrewrite
	$buscaUrlrewrite = '';
	if (array_key_exists('urlrewrite', $dados) && !empty($dados['urlrewrite']))
		$buscaUrlrewrite = ' and landing_page.urlrewrite = "' . $dados['urlrewrite'] . '" ';

	//busca pelo status
    $buscaStatus = '';
    if (array_key_exists('status', $dados) && !empty($dados['status']))
        $buscaStatus = ' and landing_page.status = ' . $dados['status'] . ' ';

    $buscaCount = '';
    if(array_key_exists('count', $dados)){
        $buscaCount = ", (SELECT COUNT(*) FROM landing_page WHERE landing_page.idlanding_page = landing_page.idlanding_page) as qtd_landing_page";
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
	$colsSql = 'landing_page.*';
	if (array_key_exists('totalRecords', $dados)) {
		$colsSql = ' count(idlanding_page) as totalRecords';
		$buscaLimit = '';
		$orderBy = '';
	} elseif (array_key_exists('colsSql', $dados)) {
		$colsSql = ' ' . $dados['colsSql'] . ' ';
	}

	$sql = "SELECT $colsSql FROM landing_page WHERE 1  $buscaId $buscanotId $buscaUrlrewrite $buscaNome $buscaStatus $orderBy $buscaLimit ";

	$query = mysqli_query($conexao, $sql);
	$resultado = array();
	while ($r = mysqli_fetch_assoc($query)) {
		$r = array_map('utf8_encode', $r);
		if (!array_key_exists('totalRecords', $dados)) {

		}
		$resultado[] = $r;
	}
	return $resultado;
}

/**
 * <p>deleta landing_page no banco</p>
 */
function deletaLanding_page($dados)
{
	include "includes/mysql.php";

	$sql = "DELETE FROM landing_page WHERE idlanding_page = $dados";
	if (mysqli_query($conexao, $sql)) {
		return mysqli_affected_rows($conexao);
	} else {
		return FALSE;
	}
}

function apagarImagemLanding_page($imgs) {
   $path = 'files/landing_page/';
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

    /*===============================================Itens===================================================*/

        function cadastroItens($dados)
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

                $sql = "INSERT INTO itens(idlanding_page, nome, descricao, icone, nome_icone, imagem, ordem) VALUES (
                                '".$dados['idlanding_page']."',
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
         * <p>edita itens no banco</p>
         */
        function editItens($dados)
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v)) continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            $dados['nome'] = trim($dados['nome']);
            $dados['descricao'] = trim($dados['descricao']);

            $sql = "UPDATE itens SET
                            nome = '".$dados['nome']."',
                            descricao = '".$dados['descricao']."',
                            icone = '".$dados['icone']."',
                            nome_icone = '".$dados['nome_icone']."',
                            imagem = '".$dados['imagem']."',
                            ordem = '".$dados['ordem']."'
                        WHERE idlanding_page = ".$dados['idlanding_page']."
                        AND iditens = ".$dados['iditens'];

            if (mysqli_query($conexao, $sql)) {
                return $dados['iditens'];
            } else {
                return false;
            }
        }

        /**
         * <p>busca itens no banco</p>
         */
        function buscaItens($dados = array())
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v) || $k == "colsSql") continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            //busca pelo id
            $buscaId = '';
            if (array_key_exists('iditens',$dados) && !empty($dados['iditens']) )
                $buscaId = ' and iditens = '.intval($dados['iditens']).' '; 

            //busca pelo nome
            $buscaNome = '';
            if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
                $buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 

            //busca pelo descricao
            $buscaDescricao = '';
            if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
                $buscaDescricao = ' and descricao LIKE "%'.$dados['descricao'].'%" '; 

            //busca pelo idlanding_page
            $buscaIdLanding_page = '';
            if (array_key_exists('idlanding_page',$dados) && !empty($dados['idlanding_page']) )
                $buscaIdLanding_page = ' and idlanding_page = '.$dados['idlanding_page'].' '; 

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
                $colsSql = ' count(iditens) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
            } elseif (array_key_exists('colsSql',$dados)) {
                $colsSql = ' '.$dados['colsSql'].' ';
            }

            $sql = "SELECT $colsSql FROM itens WHERE 1 $buscaId $buscaIdLanding_page $buscaNome $buscaDescricao $buscaImagem $orderBy $buscaLimit ";

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
         * <p>deleta itens no banco a partir da edição</p>
         */
        function deletaItens2($dados,$dados2)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM itens WHERE idlanding_page = $dados AND iditens = $dados2";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        /**
         * <p>deleta itens no banco</p>
         */
        function deletaItens($dados)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM itens WHERE idlanding_page = $dados";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        function editOrdemItens($dados)
        {
            include "includes/mysql.php";
           
            $sql = "UPDATE itens SET                 
                            ordem = '".$dados['ordem']."'                       
                        WHERE iditens = " . $dados['iditens'];
            
            if (mysqli_query($conexao, $sql)) {
                return $dados['iditens'];
            } else {
                return false;
            }
        }

        function apagarImagemItens($imgs) {
            $path = 'files/itens/';
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

    // Fim Itens

    /*===============================================Difs===================================================*/

        function cadastroDifs($dados)
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

                $sql = "INSERT INTO difs(idlanding_page, nome, descricao, icone, nome_icone, imagem, ordem) VALUES (
                                '".$dados['idlanding_page']."',
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
         * <p>edita difs no banco</p>
         */
        function editDifs($dados)
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v)) continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            $dados['nome'] = trim($dados['nome']);
            $dados['descricao'] = trim($dados['descricao']);

            $sql = "UPDATE difs SET
                            nome = '".$dados['nome']."',
                            descricao = '".$dados['descricao']."',
                            icone = '".$dados['icone']."',
                            nome_icone = '".$dados['nome_icone']."',
                            imagem = '".$dados['imagem']."',
                            ordem = '".$dados['ordem']."'
                        WHERE idlanding_page = ".$dados['idlanding_page']."
                        AND iddifs = ".$dados['iddifs'];

            if (mysqli_query($conexao, $sql)) {
                return $dados['iddifs'];
            } else {
                return false;
            }
        }

        /**
         * <p>busca difs no banco</p>
         */
        function buscaDifs($dados = array())
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v) || $k == "colsSql") continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            //busca pelo id
            $buscaId = '';
            if (array_key_exists('iddifs',$dados) && !empty($dados['iddifs']) )
                $buscaId = ' and iddifs = '.intval($dados['iddifs']).' '; 

            //busca pelo nome
            $buscaNome = '';
            if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
                $buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 

            //busca pelo descricao
            $buscaDescricao = '';
            if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
                $buscaDescricao = ' and descricao LIKE "%'.$dados['descricao'].'%" '; 

            //busca pelo idlanding_page
            $buscaIdLanding_page = '';
            if (array_key_exists('idlanding_page',$dados) && !empty($dados['idlanding_page']) )
                $buscaIdLanding_page = ' and idlanding_page = '.$dados['idlanding_page'].' '; 

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
                $colsSql = ' count(iddifs) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
            } elseif (array_key_exists('colsSql',$dados)) {
                $colsSql = ' '.$dados['colsSql'].' ';
            }

            $sql = "SELECT $colsSql FROM difs WHERE 1 $buscaId $buscaIdLanding_page $buscaNome $buscaDescricao $buscaImagem $orderBy $buscaLimit ";

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
         * <p>deleta difs no banco a partir da edição</p>
         */
        function deletaDifs2($dados,$dados2)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM difs WHERE idlanding_page = $dados AND iddifs = $dados2";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        /**
         * <p>deleta difs no banco</p>
         */
        function deletaDifs($dados)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM difs WHERE idlanding_page = $dados";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        function editOrdemDifs($dados)
        {
            include "includes/mysql.php";
           
            $sql = "UPDATE difs SET                 
                            ordem = '".$dados['ordem']."'                       
                        WHERE iddifs = " . $dados['iddifs'];
            
            if (mysqli_query($conexao, $sql)) {
                return $dados['iddifs'];
            } else {
                return false;
            }
        }

        function apagarImagemDifs($imgs) {
            $path = 'files/difs/';
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

    // Fim Difs

    /*===============================================Indicacoes===================================================*/

        function cadastroIndicacoes($dados)
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

                $sql = "INSERT INTO indicacoes(idlanding_page, nome, descricao, icone, nome_icone, imagem, ordem) VALUES (
                                '".$dados['idlanding_page']."',
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
         * <p>edita indicacoes no banco</p>
         */
        function editIndicacoes($dados)
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v)) continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            $dados['nome'] = trim($dados['nome']);
            $dados['descricao'] = trim($dados['descricao']);

            $sql = "UPDATE indicacoes SET
                            nome = '".$dados['nome']."',
                            descricao = '".$dados['descricao']."',
                            icone = '".$dados['icone']."',
                            nome_icone = '".$dados['nome_icone']."',
                            imagem = '".$dados['imagem']."',
                            ordem = '".$dados['ordem']."'
                        WHERE idlanding_page = ".$dados['idlanding_page']."
                        AND idindicacoes = ".$dados['idindicacoes'];

            if (mysqli_query($conexao, $sql)) {
                return $dados['idindicacoes'];
            } else {
                return false;
            }
        }

        /**
         * <p>busca indicacoes no banco</p>
         */
        function buscaIndicacoes($dados = array())
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v) || $k == "colsSql") continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            //busca pelo id
            $buscaId = '';
            if (array_key_exists('idindicacoes',$dados) && !empty($dados['idindicacoes']) )
                $buscaId = ' and idindicacoes = '.intval($dados['idindicacoes']).' '; 

            //busca pelo nome
            $buscaNome = '';
            if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
                $buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 

            //busca pelo descricao
            $buscaDescricao = '';
            if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
                $buscaDescricao = ' and descricao LIKE "%'.$dados['descricao'].'%" '; 

            //busca pelo idlanding_page
            $buscaIdLanding_page = '';
            if (array_key_exists('idlanding_page',$dados) && !empty($dados['idlanding_page']) )
                $buscaIdLanding_page = ' and idlanding_page = '.$dados['idlanding_page'].' '; 

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
                $colsSql = ' count(idindicacoes) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
            } elseif (array_key_exists('colsSql',$dados)) {
                $colsSql = ' '.$dados['colsSql'].' ';
            }

            $sql = "SELECT $colsSql FROM indicacoes WHERE 1 $buscaId $buscaIdLanding_page $buscaNome $buscaDescricao $buscaImagem $orderBy $buscaLimit ";

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
         * <p>deleta indicacoes no banco a partir da edição</p>
         */
        function deletaIndicacoes2($dados,$dados2)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM indicacoes WHERE idlanding_page = $dados AND idindicacoes = $dados2";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        /**
         * <p>deleta indicacoes no banco</p>
         */
        function deletaIndicacoes($dados)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM indicacoes WHERE idlanding_page = $dados";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        function editOrdemIndicacoes($dados)
        {
            include "includes/mysql.php";
           
            $sql = "UPDATE indicacoes SET                 
                            ordem = '".$dados['ordem']."'                       
                        WHERE idindicacoes = " . $dados['idindicacoes'];
            
            if (mysqli_query($conexao, $sql)) {
                return $dados['idindicacoes'];
            } else {
                return false;
            }
        }

        function apagarImagemIndicacoes($imgs) {
            $path = 'files/indicacoes/';
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

    // Fim Indicacoes

    /*===============================================Abrangencia===================================================*/

        function cadastroAbrangencia($dados)
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

                $sql = "INSERT INTO abrangencia(idlanding_page, nome, descricao, icone, nome_icone, imagem, ordem) VALUES (
                                '".$dados['idlanding_page']."',
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
         * <p>edita abrangencia no banco</p>
         */
        function editAbrangencia($dados)
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v)) continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            $dados['nome'] = trim($dados['nome']);
            $dados['descricao'] = trim($dados['descricao']);

            $sql = "UPDATE abrangencia SET
                            nome = '".$dados['nome']."',
                            descricao = '".$dados['descricao']."',
                            icone = '".$dados['icone']."',
                            nome_icone = '".$dados['nome_icone']."',
                            imagem = '".$dados['imagem']."',
                            ordem = '".$dados['ordem']."'
                        WHERE idlanding_page = ".$dados['idlanding_page']."
                        AND idabrangencia = ".$dados['idabrangencia'];

            if (mysqli_query($conexao, $sql)) {
                return $dados['idabrangencia'];
            } else {
                return false;
            }
        }

        /**
         * <p>busca abrangencia no banco</p>
         */
        function buscaAbrangencia($dados = array())
        {
            include "includes/mysql.php";

            foreach ($dados AS $k => &$v) {
                if (is_array($v) || $k == "colsSql") continue;
                $v = stripslashes($v);
                $v = mysqli_real_escape_string($conexao, utf8_decode($v));
            }

            //busca pelo id
            $buscaId = '';
            if (array_key_exists('idabrangencia',$dados) && !empty($dados['idabrangencia']) )
                $buscaId = ' and idabrangencia = '.intval($dados['idabrangencia']).' '; 

            //busca pelo nome
            $buscaNome = '';
            if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
                $buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 

            //busca pelo descricao
            $buscaDescricao = '';
            if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
                $buscaDescricao = ' and descricao LIKE "%'.$dados['descricao'].'%" '; 

            //busca pelo idlanding_page
            $buscaIdLanding_page = '';
            if (array_key_exists('idlanding_page',$dados) && !empty($dados['idlanding_page']) )
                $buscaIdLanding_page = ' and idlanding_page = '.$dados['idlanding_page'].' '; 

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
                $colsSql = ' count(idabrangencia) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
            } elseif (array_key_exists('colsSql',$dados)) {
                $colsSql = ' '.$dados['colsSql'].' ';
            }

            $sql = "SELECT $colsSql FROM abrangencia WHERE 1 $buscaId $buscaIdLanding_page $buscaNome $buscaDescricao $buscaImagem $orderBy $buscaLimit ";

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
         * <p>deleta abrangencia no banco a partir da edição</p>
         */
        function deletaAbrangencia2($dados,$dados2)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM abrangencia WHERE idlanding_page = $dados AND idabrangencia = $dados2";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        /**
         * <p>deleta abrangencia no banco</p>
         */
        function deletaAbrangencia($dados)
        {
            include "includes/mysql.php";

            $sql = "DELETE FROM abrangencia WHERE idlanding_page = $dados";
            if (mysqli_query($conexao, $sql)) {
                return mysqli_affected_rows($conexao);
            } else {
                return FALSE;
            }
        }

        function editOrdemAbrangencia($dados)
        {
            include "includes/mysql.php";
           
            $sql = "UPDATE abrangencia SET                 
                            ordem = '".$dados['ordem']."'                       
                        WHERE idabrangencia = " . $dados['idabrangencia'];
            
            if (mysqli_query($conexao, $sql)) {
                return $dados['idabrangencia'];
            } else {
                return false;
            }
        }

        function apagarImagemAbrangencia($imgs) {
            $path = 'files/abrangencia/';
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

    // Fim Abrangencia
?>