<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva politicas no banco</p>
	 */
	function cadastroPoliticas($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['pergunta'] = trim($dados['pergunta']);
		$dados['resposta'] = trim($dados['resposta']);

		$dados['ordem'] = 1;
		$ordem = buscaPoliticas(array('max'=>'ordem'));
		if (!empty($ordem)){
			$ordem = $ordem[0];
			$dados['ordem'] = $ordem['max']+1;
        }
		$sql = "INSERT INTO politicas(pergunta, resposta, status, ordem, idsuporte) VALUES (
						'".$dados['pergunta']."',
						'".$dados['resposta']."',
						'".$dados['status']."',
						'".$dados['ordem']."',
                        '".$dados['idsuporte']."'
                    )";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita politicas no banco</p>
	 */
	function editPoliticas($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$dados['pergunta'] = trim($dados['pergunta']);
		$dados['resposta'] = trim($dados['resposta']);

		$sql = "UPDATE politicas SET
						pergunta = '".$dados['pergunta']."',
						resposta = '".$dados['resposta']."',
						status = '".$dados['status']."',
						ordem = '".$dados['ordem']."',
                        idsuporte = '".$dados['idsuporte']."'
					WHERE idpoliticas = " . $dados['idpoliticas'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idpoliticas'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca politicas no banco</p>
	 */
	function buscaPoliticas($dados = array())
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
		if (array_key_exists('idpoliticas',$dados) && !empty($dados['idpoliticas']) )
			$buscaId = ' and f.idpoliticas = '.intval($dados['idpoliticas']).' '; 

        //busca pelo id
        $buscaIdsuporte = '';
        if (array_key_exists('idsuporte',$dados) && !empty($dados['idsuporte']) )
            $buscaIdsuporte = ' and f.idsuporte = '.intval($dados['idsuporte']).' '; 

		//busca pelo pergunta
		$buscaPergunta = '';
		if (array_key_exists('pergunta',$dados) && !empty($dados['pergunta']) )
			$buscaPergunta = ' and f.pergunta = '.$dados['pergunta'].' ';

        //busca pelo respota
		$buscaRespota = '';
		if (array_key_exists('resposta',$dados) && !empty($dados['resposta']) )
			$buscaRespota = ' and f.resposta = '.$dados['respota'].' '; 


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && !empty($dados['status']) )
			$buscaStatus = ' and f.status = "'.$dados['status'].'" ';

		//ordem
        $buscaOrdem = "";
        if (isset($dados['ordenacao']) && !empty($dados['ordenacao'])){
			$buscaOrdem = ' and f.ordem = '.$dados['ordenacao'] .' ';
        }

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
		
		$buscaMax = '';
		if(array_key_exists('max',$dados))
			$buscaMax = ', max('.$dados['max'].') as max ';
			
		//colunas que serão buscadas
		$colsSql = 'f.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idpoliticas) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql $buscaMax FROM politicas as f WHERE 1  $buscaId $buscaIdsuporte $buscaPergunta $buscaOrdem $buscaRespota $buscaStatus $orderBy $buscaLimit ";

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
                        $r['ordemUp'] = '<img src="images/arrUp.png" codigo="'.$r['idpoliticas'].'" class="link ordemUp" />';
                }
                if ($iAux != $tot){
                        $r['ordemDown'] = '<img src="images/arrDown.png" codigo="'.$r['idpoliticas'].'" class="link ordemDown"/>';
                }
				$r["status_nome"] = ($r["status"] == 'A' ? "Ativo":"Inativo");
                $r["status_icone"] = '<img src="images/estrela'.($r["status"] =="A" ? "sim":"nao").'.png" class="icone inverteStatus" codigo="'.$r['idpoliticas'].'" width="20px" />';
                $iAux++;  
            }
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta politicas no banco</p>
	 */
	function deletaPoliticas($dados)
	{
		include "includes/mysql.php";

		$sql = "DELETE FROM politicas WHERE idpoliticas = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

	// function inverteStatus($dados)
	// {
	//     include "includes/mysql.php";
	   
	//     $sql = "UPDATE politicas SET					
	// 					status = '".$dados['status']."'						
	// 				WHERE idpoliticas = " . $dados['idpoliticas'];
	    
	//     if (mysqli_query($conexao, $sql)) {
	//         return $dados['idpoliticas'];
	//     } else {
	//         return false;
	//     }
	// }

?>