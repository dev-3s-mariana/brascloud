<?php
	 // Versao do modulo: 2.20.130114

	/**
	 * <p>salva banner no banco</p>
	 */
	function cadastroBanner($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

        $dados['ordem'] = 1;
		$ordem = buscaBanner(array('max'=>'ordem'));

		if (!empty($ordem)){
			$ordem = $ordem[0];
			$dados['ordem'] = $ordem['max']+1;
		}

		$sql = "INSERT INTO banner(nome, ordem, status, link,banner_full,banner_mobile, dinamico, subtitulo, titulo_botao) VALUES (
						'".$dados['nome']."',
						'".$dados['ordem']."',
                        '".$dados['status']."',
                        '".$dados['link']."',
                        '".$dados['banner_full']."',
                        '".$dados['banner_mobile']."',
                        '".$dados['dinamico']."',
                        '".$dados['subtitulo']."',
                        '".$dados['titulo_botao']."'
                    )";

		if (mysqli_query($conexao, $sql)) {
			return mysqli_insert_id($conexao);
		} else {
			return FALSE;
		}
	}

	/**
	 * <p>edita banner no banco</p>
	 */
	function editBanner($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE banner SET
                    nome = '".$dados['nome']."',
                    ordem = '".$dados['ordem']."',
                    status = '".$dados['status']."',
                    banner_full = '".$dados['banner_full']."',
                    banner_mobile = '".$dados['banner_mobile']."',
                    link = '".$dados['link']."',
                    dinamico = '".$dados['dinamico']."',
                    subtitulo = '".$dados['subtitulo']."',
                    titulo_botao = '".$dados['titulo_botao']."'
                WHERE idbanner = " . $dados['idbanner'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idbanner'];
		} else {
			return FALSE;
		}
	}

	/**
	 * <p>busca banner no banco</p>
	 */
	function buscaBanner($dados = array())
	{
		include "includes/mysql.php";
		include_once "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idbanner',$dados) && !empty($dados['idbanner']) )
			$buscaId = ' and B.idbanner = '.intval($dados['idbanner']).' ';

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and B.nome LIKE "%'.$dados['nome'].'%" ';

		//busca pelo link
		$buscaLink = '';
		if (array_key_exists('link',$dados) && !empty($dados['link']) )
			$buscaLink = ' and B.link LIKE "%'.$dados['link'].'%" ';

		$buscaOrdem = '';
		if (array_key_exists('order',$dados) && !empty($dados['order']) )
			$buscaOrdem = ' and B.ordem = '.$dados['order'].' ';

      	//busca pelo status
		$buscaStatus = '';
		if(array_key_exists('status',$dados))
			$buscaStatus = ' and B.status LIKE "%'.$dados['status'].'%" ';

		//Busca pelo tipoBanner
		$buscaDinamico = '';
		if(array_key_exists('dinamico',$dados))
			$buscaDinamico = ' and B.dinamico LIKE "%'.$dados['dinamico'].'%" ';

        //ordem
        $orderBy = "";
        if (isset($dados['ordem']) && !empty($dados['ordem'])){
			$orderBy = ' ORDER BY '.$dados['ordem'];
			if (isset($dados['dir']) && !empty($dados['dir'])){
				$orderBy .= ' '.$dados['dir'];
	        }
        }

        //busca pelo limit
        $buscaLimit = '';
        if(array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados)){
            $buscaLimit = ' LIMIT '.($dados['limit'] * $dados['pagina']).','.$dados['limit'].' ';
        }else if(array_key_exists('limit',$dados) && !empty($dados['limit'])){
            $buscaLimit = ' LIMIT '.$dados['limit'];
        }

        //colunas que serão buscadas
        $colsSql = 'B.*';
        if (array_key_exists('totalRecords',$dados)){
            $colsSql = ' count(B.idbanner) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        }

        $buscaMax = '';
		if(array_key_exists('max',$dados))
			$buscaMax = ', max('.$dados['max'].') as max ';

		$sql = "SELECT $colsSql $buscaMax FROM banner as B
				WHERE 1  $buscaId  $buscaNome $buscaLink $buscaStatus $buscaDinamico $buscaOrdem  $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
        $iAux = 1;
		$tot =  mysqli_affected_rows($conexao);

		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
                if (!array_key_exists('totalRecords',$dados)){

                    $r['ordemUp'] = "";
                    $r['ordemDown'] = "";

                    if ($iAux != 1){
                            $r['ordemUp'] = '<img src="images/arrUp.png" codigo="'.$r['idbanner'].'" class="link ordemUp" />';
                    }

                    if ($iAux != $tot){
                            $r['ordemDown'] = '<img src="images/arrDown.png" codigo="'.$r['idbanner'].'" class="link ordemDown"/>';
                    }

                    $r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
					$r["status_icone"] = "<img src='images/estrela".($r["status"]=='1' ? "sim":"nao").".png' class='icone inverteStatus' codigo='".$r['idbanner']."' width='20px' />";

                    $r["_link"] = linkBanner($r["link"]);

                    if(!empty($r['banner_full'])){
                        $r['_bannerfull'] = "<img src='files/banner/".$r['banner_full']."' width='150px'>";
                    }else{
                        $r['_bannerfull'] = 'SEM IMAGEM';
                    }
                    $r['bannerfull'] = "admin/files/banner/".$r['banner_full'];
                    $r['bannermobile'] = "admin/files/banner/".$r['banner_mobile'];

                    $iAux++;
                }
			$resultado[] = $r;
		}

		return $resultado;
 	}

	/**
	 * <p>deleta banner no banco</p>
	 */
	function deletaBanner($dados)
	{
		include "includes/mysql.php";

        $banner = buscaBanner(array("idbanner"=>$dados));
        $ordem = $banner[0]['ordem'];

		$sql = "DELETE FROM banner WHERE idbanner = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
            $sql = "UPDATE banner SET ordem = (ordem - 1) WHERE ordem > ".$ordem;
            $query = mysqli_query($conexao, $sql);
			return $num;
		} else {
			return FALSE;
		}
	}

    function linkBanner($link){
	 	if($link == "#" || empty($link)){
	 		return "";
	 	}
	 	else{
			if((strripos($link,"http://") === false) && (strripos($link,"https://") === false)){
				$link = str_replace("www.","",str_replace("http://", "", str_replace("https://","",$link)));
		        $end = ENDERECO;
		        $end = str_replace("https://","", $end);
		        $end = str_replace("http://","", $end);
		        $end = str_replace("www.","", $end);
				$_link = str_replace($end,"", $link);
				if(substr($_link, 0, 1) == "/"){
					$_link =  substr($_link, 1);
				}
				$end = str_replace("admin/","", ENDERECO);
		        $link = $end.$_link;
		        return $link;
		    }else{
		    	return $link;
		    }
	    }
     }
?>
