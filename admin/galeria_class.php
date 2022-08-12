<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva galeria no banco</p>
	 */
	function cadastroGaleria($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		
		$sql = "INSERT INTO galeria( nome, status) VALUES (
						'".$dados['nome']."',
						'".$dados['status']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita galeria no banco</p>
	 */
	function editGaleria($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE galeria SET
						nome = '".$dados['nome']."',
						status = '".$dados['status']."'
					WHERE idgaleria = " . $dados['idgaleria'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idgaleria'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca galeria no banco</p>
	 */
	function buscaGaleria($dados = array())
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
		if (array_key_exists('idgaleria',$dados) && !empty($dados['idgaleria']) )
			$buscaId = ' and idgaleria = '.intval($dados['idgaleria']).' '; 

		//busca pelo id
		$buscaIdIN = '';
		if (array_key_exists('idgaleria_in',$dados) && !empty($dados['idgaleria_in']) )
			$buscaIdIN = ' and idgaleria in ('.$dados['idgaleria_in'].') '; 

		//busca pelo nome
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome = ' and nome LIKE "%'.$dados['nome'].'%" '; 


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && !empty($dados['status']) )
			$buscaStatus = ' and status = "'.$dados['status'].'" '; 

 

		//busca pelo imagem
		$buscaFoto = '';
		if (array_key_exists('imagem',$dados) && !empty($dados['imagem']) )
			$buscaFoto = ' and imagem LIKE "%'.$dados['imagem'].'%" '; 

        //ordem
        $orderBy = "";
        if (isset($dados['ordem']) && !empty($dados['ordem']) && isset($dados['dir'])){
			$orderBy = ' ORDER BY '.$dados['ordem'] ." ". $dados['dir'];
        }

        //busca pelo limit
		$buscaLimit = '';
		if (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados) && array_key_exists('inicio',$dados)) {
            $buscaLimit = ' LIMIT '.($dados['inicio'] * $dados['pagina']).','.$dados['limit'].' ';
        }
		else if (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados)) {
            $buscaLimit = ' LIMIT '.($dados['limit'] * $dados['pagina']).','.$dados['limit'].' ';
        } elseif (array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('inicio',$dados)){
            $buscaLimit = ' LIMIT '.($dados['limit']).','.$dados['inicio'].' ';
        } elseif (array_key_exists('limit',$dados) && !empty($dados['limit'])){
            $buscaLimit = ' LIMIT '.$dados['limit'];
        }

		//colunas que serão buscadas
		$colsSql = '*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idgaleria) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM galeria WHERE 1  $buscaId $buscaIdIN $buscaNome  $buscaStatus  $buscaFoto  $orderBy $buscaLimit ";
	 	 
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);

			if (!array_key_exists('totalRecords',$dados)){
		 		$r["status_nome"] = ($r["status"]=='A' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='A' ? "<img src='images/estrelasim.png' class='icone inverteStatus' codigo='".$r['idgaleria']."' width='20px' />":"<img src='images/estrelanao.png' class='icone inverteStatus' codigo='".$r['idgaleria']."' width='20px'/>"); 				
 			}        
			$resultado[] = $r;
		} 

		return $resultado; 

 	}

	/**
	 * <p>deleta galeria no banco</p>
	 */
	function deletaGaleria($dados)
	{
		include "includes/mysql.php";

		$imgs = buscaGaleria_imagem(array("idgaleria"=>$dados));

		$sql = "DELETE FROM galeria WHERE idgaleria = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			$imagens = apagarPastaImagemGaleria($imgs); 
			return $num;
		} else {
			return FALSE;
		}
	}  

	function apagarPastaImagemGaleria($imgs){ 
		 
        if(file_exists('files/galeria/')){   
        	//apaga os arquivos que foram salvos   
        	$path = 'files/galeria/'; 
			foreach ($imgs as $arquivo) {  
                $arquivo = $arquivo['nome_imagem'];
	        	$thumb = "thumb_".$arquivo;
	        	$original = "original_".$arquivo;

                if(file_exists($path.$arquivo)){  
					unlink($path.$arquivo);
				}  
	        	if(file_exists($path.$thumb)){  
					unlink($path.$thumb);
				}  
	        	if(file_exists($path.$original)){  
					unlink($path.$original);
				}
			}   
        }
        return true;		
    }   

//////////////////////////////////////////////////////

	/**
	 * <p>busca galeria_imagem no banco</p>
	 */
	function buscaGaleria_imagem($dados = array())
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
		if (array_key_exists('idgaleria_imagem',$dados) && !empty($dados['idgaleria_imagem']) )
			$buscaId = ' and GI.idgaleria_imagem = '.intval($dados['idgaleria_imagem']).' '; 

		//busca pelo idgaleria
		$buscaIdgaleria = '';
		if (array_key_exists('idgaleria',$dados) && !empty($dados['idgaleria']) )
			$buscaIdgaleria = ' and GI.idgaleria = "'.$dados['idgaleria'].'" '; 

		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && !empty($dados['status']) )
			$buscaStatus = ' and G.status = "'.$dados['status'].'" '; 

		//busca pelo descricao_imagem
		$buscaDescricao_imagem = '';
		if (array_key_exists('descricao_imagem',$dados) && !empty($dados['descricao_imagem']) )
			$buscaDescricao_imagem = ' and GI.descricao_imagem LIKE "%'.$dados['descricao_imagem'].'%" '; 


		//busca pelo urlrewrite_imagem
		$buscaUrlrewrite_imagem = '';
		if (array_key_exists('urlrewrite_imagem',$dados) && !empty($dados['urlrewrite_imagem']) )
			$buscaUrlrewrite_imagem = ' and GI.urlrewrite_imagem LIKE "%'.$dados['urlrewrite_imagem'].'%" '; 


		//busca pelo m2y_imagem
		$buscaM2y_imagem = '';
		if (array_key_exists('m2y_imagem',$dados) && !empty($dados['m2y_imagem']) )
			$buscaM2y_imagem = ' and GI.m2y_imagem LIKE "%'.$dados['m2y_imagem'].'%" ';


		//busca pela posicao_imagem
		$buscaPosicao_imagem = '';
		if (array_key_exists('posicao_imagem',$dados) && !empty($dados['posicao_imagem']) )
			$buscaM2y_imagem = ' and GI.posicao_imagem = '.$dados['posicao_imagem'].' '; 


	    //ordem
        $orderBy = "";
        if (isset($dados['ordem']) && !empty($dados['ordem'])){
			$orderBy = ' ORDER BY '.$dados['ordem'];
			if (isset($dados['dir']) && !empty($dados['dir'])){
				$orderBy .= ' '. $dados['dir'];
	        }
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
			$colsSql = ' count(GI.idgaleria_imagem) as totalRecords'; 
	        $buscaLimit = '';
	        $orderBy = '';
	    } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}  

		$sql = "SELECT $colsSql FROM galeria_imagem as GI
				INNER JOIN galeria as G on GI.idgaleria = G.idgaleria
				WHERE 1  $buscaId  $buscaIdgaleria  
				$buscaDescricao_imagem  $buscaUrlrewrite_imagem 
				$buscaPosicao_imagem $buscaStatus $buscaM2y_imagem  $orderBy $buscaLimit ";
   
		include_once('includes/functions.php');
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			if(isset($dados['url'])){
				$r['descricao_imagem_url']= converteUrl($r['descricao_imagem']);					
			} 			 
			$r = array_map('utf8_encode', $r);  
			$resultado[] = $r;
		}
		
		return $resultado; 
	}


	function cadastroGaleria_imagem($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "INSERT INTO galeria_imagem( idgaleria, nome_imagem, descricao_imagem, urlrewrite_imagem, posicao_imagem, m2y_imagem,is_default) VALUES (
					'".$dados['idgaleria']."',
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
	 * <p>edita galeria_imagem no banco</p>
	 */
	function editGaleria_imagem($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v); 
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE galeria_imagem SET
						idgaleria = '".$dados['idgaleria']."',
						descricao_imagem = '".$dados['descricao_imagem']."',
						urlrewrite_imagem = '".$dados['urlrewrite_imagem']."',
						posicao_imagem = '".$dados['posicao_imagem']."',
						is_default = '".$dados['is_default']."',
						nome_imagem = '".$dados['nome_imagem']."',
						m2y_imagem = '".$dados['m2y_imagem']."'
					WHERE idgaleria_imagem = " . $dados['idgaleria_imagem'];
  
		if (mysqli_query($conexao, $sql)) {
			return $dados['idgaleria_imagem'];
		} else {
			return false;
		}
	}


	function salvaImagemGaleria($dados){   

		include_once "includes/functions.php"; 
		$dadosGravar = array(); 

		$idgaleria = $dados['idgaleria'];
		//urlrewrite
		$nomeimagem = explode('.', $dados['nome_imagem']);
		$nomeimagem = $nomeimagem[0];
		$dados['urlrewrite_imagem'] = converteUrl($dados['nome_imagem']);	
		//atribuir m2y 
		$urlamigavel = 'admin/files/galeria/'.$idgaleria.'/thumb/'.$dados['nome_imagem'];
	 
		$dados['m2y_imagem'] = ''; 
		$shorturl = ENDERECO.$urlamigavel;
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

		return cadastroGaleria_imagem($dados); 
	}

	//APAGA UM IMAGEM ESPECIFICA DA GALERIA
	function apagarImagemGaleria($idgaleria, $imagem, $idgaleria_imagem){ 

		include "includes/mysql.php"; 
        if(!empty($idgaleria) && file_exists('files/galeria/')){ 
        	
        	//apaga a imagem da galeria  
        	$arquivo = $imagem;
        	$thumb = "thumb_".$imagem;
        	$original = "original_".$imagem;

        	$path = 'files/galeria/';
        	if(file_exists($path.$arquivo)){  
				unlink($path.$arquivo);
			}  
        	if(file_exists($path.$thumb)){  
				unlink($path.$thumb);
			}  
        	if(file_exists($path.$original)){  
				unlink($path.$original);
			} 

            if(!empty($idgaleria_imagem)){
            	//APAGAR IMAGEM DO BANCO
            	$imagem = buscaGaleria_imagem(array("idgaleria_imagem"=>$idgaleria_imagem));
            	 
            	$posicao = $imagem[0]['posicao_imagem'];
            	$sql = 'DELETE from galeria_imagem WHERE idgaleria_imagem = '.$idgaleria_imagem;
            	 
            	if (mysqli_query($conexao, $sql)) {
            		//update nas posicao das imagens
					$sql = 'UPDATE galeria_imagem SET posicao_imagem = (posicao_imagem - 1) WHERE idgaleria = '.$idgaleria.' and posicao_imagem > '.$posicao;
					if (mysqli_query($conexao, $sql)) {
						//marca a primeira posicao como default - caso apague q primeira imagem
						$sql = 'UPDATE galeria_imagem SET is_default = 1 WHERE idgaleria = '.$idgaleria.' and posicao_imagem = 1';
						mysqli_query($conexao, $sql);
						return true;
					}
				} else {
					return false;
				}
            }else{ 
            	return true;
            }
        }
        return false;		
    }   

    function alterarPosicaoImagemGaleria($dados){

    	include "includes/mysql.php"; 	

    	$imagens = $dados['idgaleria_imagem'];
    	$posicao = $dados['posicao_imagem'];
    	$idgaleria = $dados['idgaleria'];

		if(!empty($imagens)){
			 
			foreach($imagens as $k => $v){
				$sql = 'UPDATE galeria_imagem SET 
						posicao_imagem = "'.$posicao[$k].'",
						is_default = 0
						WHERE idgaleria_imagem = '.$v;

				mysqli_query($conexao, $sql);		
			} 

			$sql = 'UPDATE galeria_imagem SET is_default = 1 WHERE idgaleria = '.$idgaleria.' and posicao_imagem = 1';
		  			mysqli_query($conexao, $sql);
					return true;  
		}else{ 
			return true;
		}
    }
?>