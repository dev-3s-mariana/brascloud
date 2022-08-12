<?php
	 // Versao do modulo: 3.00.010416
	/**
	 * <p>salva blog_post no banco</p>
	 */
	function cadastroBlog_post($dados)
	{
		include "includes/mysql.php";
		include_once "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$data = explode(" ",$dados['data_hora']);
		$hora = $data[1];
		$data = explode("/",$data[0]);
		$data = $data[2]."-".$data[1]."-".$data[0];
		$data = $data." ".$hora.":00";

        foreach ($dados['tags'] as $key => $tag) {
            if($key == 0){
                $tags .= $tag;
            }else{
                $tags .= ','.$tag;
            }
        }

        $dados['tags'] = $tags;

		$dados['urlrewrite'] = converteUrl(str_replace("-", " ", $dados['urlrewrite']));
		$sql = "INSERT INTO blog_post(idblog_categoria, nome, autor, resumo, descricao, imagem, contador, data_hora, status, description, keywords, title, link_video, urlrewrite, tags) VALUES (
						'".$dados['idblog_categoria']."',
						'".$dados['nome']."',
						'".$dados['autor']."',
						'".$dados['resumo']."',
						'".$dados['descricao']."',
						'".$dados['imagem']."',
						'".$dados['contador']."',
						'".$data."',
						'".$dados['status']."',
						'".$dados['description']."',
						'".$dados['keywords']."',
						'".$dados['title']."',
						'".$dados['link_video']."',
						'".$dados['urlrewrite']."',
                        '".$dados['tags']."'
                    )";

		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita blog_post no banco</p>
	 */
	function editBlog_post($dados)
	{
		include "includes/mysql.php";
		include_once "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$data = explode(" ",$dados['data_hora']);
		$hora = $data[1];
		$data = explode("/",$data[0]);
		$data = $data[2]."-".$data[1]."-".$data[0];
		$data = $data." ".$hora.":00";

        foreach ($dados['tags'] as $key => $tag) {
            if($key == 0){
                $tags .= $tag;
            }else{
                $tags .= ','.$tag;
            }
        }

        $dados['tags'] = $tags;

		$dados['urlrewrite'] = converteUrl(str_replace("-", " ", $dados['urlrewrite']));

		$sql = "UPDATE blog_post SET
						resumo = '".$dados['resumo']."',
						idblog_categoria = '".$dados['idblog_categoria']."',
						nome = '".$dados['nome']."',
						autor = '".$dados['autor']."',
						descricao = '".$dados['descricao']."',
						imagem = '".$dados['imagem']."',
						contador = '".$dados['contador']."',
						data_hora = '".$data."',
						status = '".$dados['status']."',
						urlrewrite = '".$dados['urlrewrite']."',
						description = '".$dados['description']."',
						title = '".$dados['title']."',
						link_video = '".$dados['link_video']."',
						keywords = '".$dados['keywords']."',
                        tags = '".$dados['tags']."'
					WHERE idblog_post = " . $dados['idblog_post'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idblog_post'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca blog_post no banco</p>
	 */
	function buscaBlog_post($dados = array())
	{
		include "includes/mysql.php";
		include_once "includes/functions.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idblog_post',$dados) && !empty($dados['idblog_post']) )
			$buscaId = ' and P.idblog_post = '.intval($dados['idblog_post']).' ';

		//busca pelo id
		$buscaIdNot = '';
		if (array_key_exists('not_idblog_post',$dados) && !empty($dados['not_idblog_post']) )
			$buscaIdNot = ' and P.idblog_post != '.intval($dados['not_idblog_post']).' ';

		//busca pelo id
		$buscaIdIn = '';
		if (array_key_exists('in_idblog_post',$dados) && !empty($dados['in_idblog_post']) )
			$buscaIdIn = ' and P.idblog_post in ('.$dados['in_idblog_post'].')';

	 	//busca pelo idblog_categoria
		$buscaIdBlog_Categoria = '';
		if (array_key_exists('idblog_categoria',$dados) && !empty($dados['idblog_categoria']) )
			$buscaIdBlog_Categoria = ' and P.idblog_categoria = "'.$dados['idblog_categoria'].'" ';

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and P.nome LIKE "%'.$dados['nome'].'%" ';

        //busca pelo tags
        $buscaTags = '';
        if (array_key_exists('tags',$dados) && !empty($dados['tags']) )
            $buscaTags = ' and FIND_IN_SET("'.$dados['tags'].'", P.tags) ';

		//busca pelo resumo
		$buscaResumo = '';
		if (array_key_exists('resumo',$dados) && !empty($dados['resumo']) )
			$buscaResumo = ' and P.resumo LIKE "%'.$dados['resumo'].'%" ';

		//busca pelo descricao
		$buscaDescricao = '';
		if (array_key_exists('descricao',$dados) && !empty($dados['descricao']) )
			$buscaDescricao = ' and P.descricao LIKE "%'.$dados['descricao'].'%" ';

		//busca pelo descricao
		$buscaDestaque = '';
		if (array_key_exists('destaque',$dados) && !empty($dados['destaque']) )
			$buscaDestaque = ' and P.destaque = '.$dados['destaque'].' ';

		//busca pelo imagem
		$buscaImagem = '';
		if (array_key_exists('imagem',$dados) && !empty($dados['imagem']) )
			$buscaImagem = ' and imagem LIKE "%'.$dados['imagem'].'%" ';

		//busca pelo contador
		$buscaContador = '';
		if (array_key_exists('contador',$dados) && !empty($dados['contador']) )
			$buscaContador = ' and P.contador = "'.$dados['contador'].'" ';

		//busca pelo contador_mais
		$buscaContadorNotNull = '';
		if (array_key_exists('contador_notnull',$dados) && !empty($dados['contador_notnull']) )
			$buscaContadorNotNull = ' and P.contador > 0';


		$buscaData = '';
		if (array_key_exists('data_hora',$dados) && !empty($dados['data_hora'])){
			$tmp = explode('/', stripslashes($dados['data_hora']));
			$data_hora = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			$buscaData = ' and data_hora = "'.$data_hora.'"';
		}
		if (array_key_exists('data_inicio',$dados) && !empty($dados['data_inicio'])){
			$tmp = explode('/', stripslashes($dados['data_inicio']));
			$data_hora = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			$buscaData = ' and data_hora >= "'.$data_hora.'"';

			if (array_key_exists('data_fim',$dados) && !empty($dados['data_fim'])){
				$tmp = explode('/', stripslashes($dados['data_fim']));
				$data_horaFim = $tmp[2]."-".$tmp[1]."-".$tmp[0];
				$buscaData = ' and date(data_hora) BETWEEN "'.$data_hora.'" and "'.$data_horaFim.'"' ;
			}
		}
		else if (array_key_exists('data_fim',$dados) && !empty($dados['data_fim'])){
			$tmp = explode('/', stripslashes($dados['data_fim']));
			$data_horaFim = $tmp[2]."-".$tmp[1]."-".$tmp[0];
			$buscaData = ' and data_hora <= "'.$data_horaFim.'"';
		}
		else if (array_key_exists('ultimos_dias',$dados) && !empty($dados['ultimos_dias'])){
			$buscaData = ' and data_hora <= now() and data_hora >= ADDDATE(now(),INTERVAL -'.$dados['ultimos_dias'].' day)';
		}
		else if (array_key_exists('dataNow',$dados)){
			$buscaData = ' and data_hora <= now()';
		}

		//para arquivos.
		if(array_key_exists('dataBusca',$dados) && !empty($dados['dataBusca'])){
			$buscaData = ' and date_format(data_hora, "%Y-%m") = "'.$dados['dataBusca'].'"';
		}

		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && $dados['status'] != '')
			$buscaStatus = ' and P.status = "'.$dados['status'].'" ';


		//busca pelo status categoria
		$buscaStatusCategoria = '';
		if (array_key_exists('status_categoria',$dados) && !empty($dados['status_categoria']) )
			$buscaStatusCategoria = ' and C.status = "'.$dados['status_categoria'].'" ';


		//busca pelo urlrewrite
		$buscaUrlrewrite = '';
		if (array_key_exists('urlrewrite',$dados) && !empty($dados['urlrewrite']) )
			$buscaUrlrewrite = ' and P.urlrewrite = "'.$dados['urlrewrite'].'" ';


			//busca pelo urlrewrite
		$buscaUrlrewrite2 = '';
		if (array_key_exists('urlrewrite2',$dados) && !empty($dados['urlrewrite2']) )
			$buscaUrlrewrite2 = ' and P.urlrewrite = "'.$dados['urlrewrite2'].'" ';



    	//busca pelo urlrewrite
		$busca4data_group = '';
		$totalPosts = '';
		if (array_key_exists('busca4data',$dados) && !empty($dados['busca4data']) ){
            $busca4data_group   = ' GROUP BY date_format(P.data_hora, "%Y-%m")';
            $totalPosts         = ' , count(idblog_post) as qtd_post';
            $dados['ordem']     = 'date_format(P.data_hora, "%Y-%m")';
            $dados['dir']       = 'asc';
        }



        //busca pelo urlrewrite
        $busca4periodo = '';
        if (array_key_exists('busca4periodo',$dados) && !empty($dados['busca4periodo']) ){
            $busca4periodo   = 'and date_format(P.data_hora, "%Y-%m") = "'.$dados['busca4periodo'].'"';
        }



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
		if (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados)) {
            $buscaLimit = ' LIMIT '.($dados['pagina'] * $dados['limit']).','.$dados['limit'].' ';
        } elseif (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('data_hora',$dados)){
            $buscaLimit = ' LIMIT '.$dados['limit'].','.$dados['data_hora'].' ';
        } elseif (array_key_exists('limit',$dados) && !empty($dados['limit'])){
            $buscaLimit = ' LIMIT '.$dados['limit'];
        }


		//colunas que serão buscadas
		$colsSql = "
		    P.*,
		    C.nome as nome_categoria,
		    C.urlrewrite as url_categoria,
		    date_format(P.data_hora, '%d/%m/%Y %H:%i') as data_hora_formatado,
		    date_format(P.data_hora, '%d.%m.%Y') as data_formatado_pointer,
		    date_format(P.data_hora, '%d/%m/%Y') as data_formatado, date(P.data_hora) as data,
		    (select count(idblog_comentarios) from blog_comentarios as BC where BC.idblog_post = P.idblog_post and BC.status = 2) as total_comentarios,

		    (CASE monthname(P.data_hora)
                 when 'January' then 'Janeiro'
                 when 'February' then 'Fevereiro'
                 when 'March' then 'Março'
                 when 'April' then 'Abril'
                 when 'May' then 'Maio'
                 when 'June' then 'Junho'
                 when 'July' then 'Julho'
                 when 'August' then 'Agosto'
                 when 'September' then 'Setembro'
                 when 'October' then 'Outubro'
                 when 'November' then 'Novembro'
                 when 'December' then 'Dezembro'
                 END) AS mes_form,
            (CASE monthname(P.data_hora)
                 when 'January' then 'Jan'
                 when 'February' then 'Fev'
                 when 'March' then 'Mar'
                 when 'April' then 'Abr'
                 when 'May' then 'Mai'
                 when 'June' then 'Jun'
                 when 'July' then 'Jul'
                 when 'August' then 'Ago'
                 when 'September' then 'Set'
                 when 'October' then 'Out'
                 when 'November' then 'Nov'
                 when 'December' then 'Dez'
                 END) AS mes_nome,
                 date_format(P.data_hora, '%Y') as ano_,
                 date_format(P.data_hora, '%d') as dia_,
                 date_format(P.data_hora, '%m') as mes_
                 ".$totalPosts."
                 ";


		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(P.idblog_post) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql
				FROM blog_post as P
				INNER JOIN blog_categoria as C on P.idblog_categoria = C.idblog_categoria
				WHERE 1  $buscaId $buscaIdNot $buscaIdIn $buscaContadorNotNull $buscaIdBlog_Categoria $buscaNome  $buscaResumo
				$buscaDescricao $buscaTags $buscaImagem $buscaContador $buscaData $buscaStatus $buscaStatusCategoria $buscaDestaque $buscaUrlrewrite $busca4periodo
				$busca4data_group $orderBy $buscaLimit";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);

		 	if (!array_key_exists('totalRecords',$dados) && !array_key_exists('colsSql',$dados)){
				$r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
				$r["status_icone"] = "<img src='images/estrela".($r["status"]=='1' ? "sim":"nao").".png' class='estacao inverteStatus' codigo='".$r['idblog_post']."' width='20px' />";

               	$r["_resumo"] = resumo($r['resumo'], 155);
               	$r["_titulo"] = resumo($r['nome'], 55);
           	}

			$resultado[] = $r;
		}
		return $resultado;
 	}

	/**
	 * <p>deleta blog_post no banco</p>
	*/

	function deletaBlog_post($dados)
	{
		include "includes/mysql.php";

		$imgs = buscaBlog_post_imagem(array("idblog_post"=>$dados));
		$blog = buscaBlog_post(array("idblog_post"=>$dados));

		$num = 0;
		if(count($imgs) > 0){
			$num = count($imgs);
		}
		$imgs[$num]['nome_imagem'] = $blog[0]['imagem'];

		$sql = "DELETE FROM blog_post WHERE idblog_post = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			apagarImagemBlog($imgs);
			return $num;
		} else {
			return FALSE;
		}
	}


	/**
	 * <p>incrementa blog_post no banco</p>
	*/
	function incrementaBlog_post($dados)
	{
		include "includes/mysql.php";

		$sql = "UPDATE blog_post set contador = (contador + 1) WHERE idblog_post = $dados";
	  	if (mysqli_query($conexao, $sql)) {
			return true;
		} else {
			return FALSE;
		}
	}


/*****************************************************/
/*************  ARQUIVOS **************************/
/*****************************************************/
	// busca arquivos de publicacao no banco
	function buscaPublicacaoArquivos($dados = array()){

		include "includes/mysql.php";

		$sql = "
			SELECT count(*) as qtde, date_format(data_hora,'%Y-%m') as data2
			  FROM blog_post
		     WHERE date_format(data_hora,'%Y%m') < date_format(now(),'%Y%m') and status = 'A'
		     GROUP BY data2
			 ORDER BY date_format(data_hora,'%Y%m') DESC
			 LIMIT 10";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while($r = mysqli_fetch_assoc($query)){
			$r = array_map('stripslashes', $r);
			$r = array_map('utf8_encode', $r);
			$resultado[] = $r;
		}

		return $resultado;
 	}



/*****************************************************/
/*************  GALERIA **************************/
/*****************************************************/

//////////////////////////////////////////////////////

	/**
	 * <p>busca blog_post_imagem no banco</p>
	 */
	function buscaBlog_post_imagem($dados = array())
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
		if (array_key_exists('idblog_post_imagem',$dados) && !empty($dados['idblog_post_imagem']) )
			$buscaId = ' and idblog_post_imagem = '.intval($dados['idblog_post_imagem']).' ';

		//busca pelo idblog_post
		$buscaIdblog_post = '';
		if (array_key_exists('idblog_post',$dados) && !empty($dados['idblog_post']) )
			$buscaIdblog_post = ' and idblog_post = "'.$dados['idblog_post'].'" ';


		//busca pelo descricao_imagem
		$buscaDescricao_imagem = '';
		if (array_key_exists('descricao_imagem',$dados) && !empty($dados['descricao_imagem']) )
			$buscaDescricao_imagem = ' and descricao_imagem LIKE "%'.$dados['descricao_imagem'].'%" ';


		//busca pelo urlrewrite_imagem
		$buscaUrlrewrite_imagem = '';
		if (array_key_exists('urlrewrite_imagem',$dados) && !empty($dados['urlrewrite_imagem']) )
			$buscaUrlrewrite_imagem = ' and urlrewrite_imagem LIKE "%'.$dados['urlrewrite_imagem'].'%" ';


		//busca pelo m2y_imagem
		$buscaM2y_imagem = '';
		if (array_key_exists('m2y_imagem',$dados) && !empty($dados['m2y_imagem']) )
			$buscaM2y_imagem = ' and m2y_imagem LIKE "%'.$dados['m2y_imagem'].'%" ';


		//busca pela posicao_imagem
		$buscaPosicao_imagem = '';
		if (array_key_exists('posicao_imagem',$dados) && !empty($dados['posicao_imagem']) )
			$buscaM2y_imagem = ' and posicao_imagem = '.$dados['posicao_imagem'].' ';


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
		if (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados)) {
	        $buscaLimit = ' LIMIT '.($dados['limit'] * $dados['pagina']).','.$dados['limit'].' ';
	    } elseif (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('data_hora',$dados)){
	        $buscaLimit = ' LIMIT '.$dados['limit'].','.$dados['data_hora'].' ';
	    } elseif (array_key_exists('limit',$dados) && !empty($dados['limit'])){
	        $buscaLimit = ' LIMIT '.$dados['limit'];
	    }

		//colunas que serão buscadas
		$colsSql = '*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idblog_post_imagem) as totalRecords';
	        $buscaLimit = '';
	        $orderBy = '';
	    } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM blog_post_imagem
				WHERE 1  $buscaId  $buscaIdblog_post
				$buscaDescricao_imagem  $buscaUrlrewrite_imagem
				$buscaPosicao_imagem  $buscaM2y_imagem  $orderBy $buscaLimit ";

		include_once('includes/functions.php');

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){

			if (!array_key_exists('totalRecords',$dados)){
				if(isset($dados['url'])){
					$r['descricao_imagem_url']= converteUrl($r['descricao_imagem']);
				}

				if(isset($dados['tamanho'])){
					$tamanhoImagem = getimagesize('files/blog_post/'.$r['idblog_post'].'/galeria/thumbnail/'.$r['nome_imagem']);
				    $r['w'] = $tamanhoImagem[0];
				    $r['h']= $tamanhoImagem[1];
				}

				$r['imgthumb'] = "admin/files/blog_post/".$r['idblog_post']."/galeria/thumb/".$r['nome_imagem'];
				$r['imgfull'] = "admin/files/blog_post/".$r['idblog_post']."/galeria/original/".$r['nome_imagem'];
			}

			$r = array_map('utf8_encode', $r);
			$resultado[] = $r;
		}

		return $resultado;
	}


	function cadastroBlog_post_imagem($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "INSERT INTO blog_post_imagem( idblog_post, nome_imagem, descricao_imagem, urlrewrite_imagem, posicao_imagem, m2y_imagem,is_default) VALUES (
					'".$dados['idblog_post']."',
					'".$dados['nome_imagem']."',
					'".$dados['descricao_imagem']."',
					'".$dados['urlrewrite_imagem']."',
					'".$dados['posicao_imagem']."',
					'".$dados['m2y_imagem']."',
					'".$dados['is_default']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita blog_post_imagem no banco</p>
	 */
	function editBlog_post_imagem($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE blog_post_imagem SET
						idblog_post = '".$dados['idblog_post']."',
						descricao_imagem = '".$dados['descricao_imagem']."',
						urlrewrite_imagem = '".$dados['urlrewrite_imagem']."',
						posicao_imagem = '".$dados['posicao_imagem']."',
						is_default = '".$dados['is_default']."',
						nome_imagem = '".$dados['nome_imagem']."',
						m2y_imagem = '".$dados['m2y_imagem']."'
					WHERE idblog_post_imagem = " . $dados['idblog_post_imagem'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idblog_post_imagem'];
		} else {
			return false;
		}
	}


	function salvaImagemBlog_post($dados){

		include_once "includes/functions.php";
		$dadosGravar = array();

		$idblog_post = $dados['idblog_post'];
		//urlrewrite
		$nomeimagem = explode('.', $dados['nome_imagem']);
		$nomeimagem = $nomeimagem[0];
		$dados['urlrewrite_imagem'] = converteUrl($dados['nome_imagem']);
		//atribuir m2y
		$urlrewrite = 'admin/files/blog_post/'.$idblog_post.'/galeria/thumbnail/'.$dados['nome_imagem'];

		$dados['m2y_imagem'] = '';
		$shorturl = ENDERECO.$urlrewrite;
		$authkey = "3H34kAfJ36c7VUR3oCqBR15R33P554V6";

		$returns = file_get_contents("http://www.m2y.me/webservice/create/?url=".$shorturl."&authkey=".$authkey);

		if($returns != -1 && $returns != -2){
			$dados['m2y_imagem'] = $returns;
		}

		if($dados['posicao_imagem'] == 1){
			$dados['is_default'] = 1;
		}else{
			$dados['is_default'] = 0;
		}

		return cadastroBlog_post_imagem($dados);
	}



    function alterarPosicaoImagemBlog_post($dados){

    	include "includes/mysql.php";

    	$imagens = $dados['idblog_post_imagem'];
    	$posicao = $dados['posicao_imagem'];
    	$idblog_post = $dados['idblog_post'];

		if(!empty($imagens)){

			foreach($imagens as $k => $v){
				$sql = 'UPDATE blog_post_imagem SET
						posicao_imagem = "'.$posicao[$k].'",
						is_default = 0
						WHERE idblog_post_imagem = '.$v;

				mysqli_query($conexao, $sql);
			}

			$sql = 'UPDATE blog_post_imagem SET is_default = 1 WHERE idblog_post = '.$idblog_post.' and posicao_imagem = 1';
		  			mysqli_query($conexao, $sql);
					return true;
		}else{
			return true;
		}
    }



  //APAGAR IMAGENS DA PASTA
	function apagarImagemBlog($imgs) {
		$path = 'files/blog/';

		$nameArquivo = array();
		$nameArquivo[] = "";
		$nameArquivo[] = "thumb_";

		if(file_exists($path)){
			if(is_array($imgs)){
				foreach ($imgs as $img) {
					foreach ($nameArquivo as $key => $_name) {
						$arquivo = $_name.$img['nome_imagem'];
						if(file_exists($path.$arquivo)){
							unlink($path.$arquivo);
						}
					}
				}
			}else{
				foreach ($nameArquivo as $key => $_name) {
					$arquivo = $_name.$imgs;
					if(file_exists($path.$arquivo)){
						unlink($path.$arquivo);
					}
				}
			}
    	}
		return true;
	}


	//APAGA UM IMAGEM ESPECIFICA DA GALERIA
	function deletarImagemBlogGaleria($idblog_imagem, $idpost){

		include "includes/mysql.php";
		$return = false;

		$imagem = buscaBlog_post_imagem(array("idblog_post_imagem"=>$idblog_imagem));

    	$posicao = $imagem[0]['posicao_imagem'];
    	$sql = 'DELETE from blog_post_imagem WHERE idblog_post_imagem = '.$idblog_imagem;

		if (mysqli_query($conexao, $sql)) {
    		//update nas posicao das imagens
			$sql = 'UPDATE blog_post_imagem SET posicao_imagem = (posicao_imagem - 1) WHERE idblog_post = '.$idpost.' and posicao_imagem > '.$posicao;
			if (mysqli_query($conexao, $sql)) {
				//marca a primeira posicao como default - caso apague q primeira imagem
				$sql = 'UPDATE blog_post_imagem SET is_default = 1 WHERE idblog_post = '.$idpost.' and posicao_imagem = 1';
				mysqli_query($conexao, $sql);
				$return = true;
			}
		} else {
			$return = false;
		}

        return $return;
    }

    function UpdateLike($dados){
    	include "includes/mysql.php";
    	foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "UPDATE blog_post SET
								likes = '".$dados['likes']."'
							WHERE idblog_post = " . $dados['idblog_post'];

				if (mysqli_query($conexao, $sql)) {
					return $dados['idblog_post'];
				} else {
					return false;
				}

    }


  function UpdateContador($dados){
    	include "includes/mysql.php";
    	foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "UPDATE blog_post SET
				contador = contador + 1
				WHERE idblog_post = " . $dados['idblog_post'];

        if (mysqli_query($conexao, $sql)) {
            return $dados['idblog_post'];
        } else {
            return false;
        }

    }
    
?>
