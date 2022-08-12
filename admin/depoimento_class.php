<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva depoimento no banco</p>
	 */
	function cadastroDepoimento($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "INSERT INTO depoimento( nome, depoimento, subtitulo, ordem, status, imagem) VALUES (
						'".$dados['nome']."',
						'".$dados['depoimento']."',
						'".$dados['subtitulo']."',
						'".$dados['ordem']."',
						'".$dados['status']."',
						'".$dados['imagem']."'
                    )";
                    
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita depoimento no banco</p>
	 */
	function editDepoimento($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['nome'] = trim($dados['nome']);
		$dados['depoimento'] = trim($dados['depoimento']);
		$dados['subtitulo'] = trim($dados['subtitulo']);

		$sql = "UPDATE depoimento SET
						nome = '".$dados['nome']."',
						depoimento = '".$dados['depoimento']."',
						subtitulo = '".$dados['subtitulo']."',
						ordem = '".$dados['ordem']."',
						status = '".$dados['status']."',
						imagem = '".$dados['imagem']."'
					WHERE iddepoimento = " . $dados['iddepoimento'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['iddepoimento'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca depoimento no banco</p>
	 */
	function buscaDepoimento($dados = array())
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
            $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('iddepoimento',$dados) && !empty($dados['iddepoimento']) )
			$buscaId = ' and iddepoimento = '.intval($dados['iddepoimento']).' '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 


		//busca pelo depoimento
		$buscaDepoimento = '';
		if (array_key_exists('depoimento',$dados) && !empty($dados['depoimento']) )
			$buscaDepoimento = ' and depoimento LIKE "%'.$dados['depoimento'].'%" '; 


		//busca pelo ordem
		$buscaOrdem = '';
		if (array_key_exists('order',$dados) && !empty($dados['order']) )
			$buscaOrdem = ' and ordem = '.$dados['order'].' '; 


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && $dados['status'] != '')
			$buscaStatus = ' and status = '.$dados['status'].' '; 


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
			$colsSql = ' count(iddepoimento) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM depoimento WHERE 1  $buscaId  $buscaNome  $buscaDepoimento  $buscaOrdem  $buscaStatus  $buscaImagem  $orderBy $buscaLimit ";

		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		$iAux = 1;
		$tot =  mysqli_affected_rows($conexao);
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r); 
			if (!array_key_exists('totalRecords',$dados)){ 
				$r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
				$r["status_icone"] = "<img src='images/estrela".($r["status"]=='1' ? "sim":"nao").".png' class='icone inverteStatus' codigo='".$r['iddepoimento']."' width='20px' />";
				
            	$r['ordemUp'] = "";
                $r['ordemDown'] = "";

                if ($iAux != 1){
                        $r['ordemUp'] = '<img src="images/arrUp.png" codigo="'.$r['iddepoimento'].'" class="link ordemUp" />';
                }

                if ($iAux != $tot){
                        $r['ordemDown'] = '<img src="images/arrDown.png" codigo="'.$r['iddepoimento'].'" class="link ordemDown"/>';
				}
				
				!empty($r['imagem']) ? $r['imagem-caminho'] = '<img src="files/depoimento/'.$r['imagem'].'" class="img-depoimento"/>' : $r['imagem-caminho'] = 'SEM IMAGEM';

                $iAux++;
	        }  
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta depoimento no banco</p>
	 */
	function deletaDepoimento($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM depoimento WHERE iddepoimento = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	function editOrdemDepoimento($dados)
	{
	    include "includes/mysql.php";
	   
	    $sql = "UPDATE depoimento SET					
						ordem = '".$dados['ordem']."'						
					WHERE iddepoimento = " . $dados['iddepoimento'];
	    
	    if (mysqli_query($conexao, $sql)) {
	        return $dados['iddepoimento'];
	    } else {
	        return false;
	    }
	}
?>