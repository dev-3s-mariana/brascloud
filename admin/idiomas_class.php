<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva idiomas no banco</p>
	 */
	function cadastroIdiomas($dados)
	{
		include "includes/mysql.php";
		include_once "idiomas_traducao_class.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}
		$sql = "INSERT INTO idiomas( idioma, bandeira, urlamigavel, status) VALUES (
						'".$dados['idioma']."', 
						'".$dados['bandeira']."',
						'".$dados['urlamigavel']."',
						'".$dados['status']."')";
		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			cadastrarTagsIdioma($resultado);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita idiomas no banco</p>
	 */
	function editIdiomas($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE idiomas SET
						idioma = '".$dados['idioma']."', 
						bandeira = '".$dados['bandeira']."',
						urlamigavel = '".$dados['urlamigavel']."',
						status = '".$dados['status']."'
					WHERE ididiomas = " . $dados['ididiomas'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['ididiomas'];
		} else {
			return false;
		}
	}

	/**
	 * <p>busca idiomas no banco</p>
	 */
	function buscaIdiomas($dados = array())
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
		if (array_key_exists('ididiomas',$dados) && !empty($dados['ididiomas']) )
			$buscaId = ' and ididiomas = '.intval($dados['ididiomas']).' '; 

		//busca pelo id
		$buscaIdNot = '';
		if (array_key_exists('not_ididiomas',$dados) && !empty($dados['not_ididiomas']) )
			$buscaIdNot = ' and ididiomas != '.intval($dados['not_ididiomas']).' '; 


		//busca pelo idioma
		$buscaIdioma = '';
		if (array_key_exists('idioma',$dados) && !empty($dados['idioma']) )
			$buscaIdioma = ' and idioma LIKE "%'.$dados['idioma'].'%" '; 


		//busca pelo bandeira
		$buscaBandeira = '';
		if (array_key_exists('bandeira',$dados) && !empty($dados['bandeira']) )
			$buscaBandeira = ' and bandeira LIKE "%'.$dados['bandeira'].'%" '; 


		//busca pelo urlamigavel
		$buscaUrlamigavel = '';
		if (array_key_exists('urlamigavel',$dados) && !empty($dados['urlamigavel']) )
			$buscaUrlamigavel = ' and urlamigavel LIKE "%'.$dados['urlamigavel'].'%" '; 

		//busca pelo principal
		$buscaPrincipal = '';
		if (array_key_exists('principal',$dados) && !empty($dados['principal']) )
			$buscaPrincipal = ' and principal = "'.$dados['principal'].'" '; 


		//busca pelo status
		$buscaStatus = '';
		if (array_key_exists('status',$dados) && is_numeric($dados['status']) )
			$buscaStatus = ' and status = '.$dados['status'].' '; 

        //ordem
        $orderBy = "";
        if (isset($dados['ordem']) && !empty($dados['ordem'])){
			$orderBy = ' ORDER BY '.$dados['ordem'];
			if (isset($dados['dir']) && !empty($dados['dir'])){
				$orderBy .= " ". $dados['dir'];
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
			$colsSql = ' count(ididiomas) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT $colsSql FROM idiomas WHERE 1  $buscaId $buscaIdNot $buscaIdioma $buscaPrincipal $buscaBandeira  $buscaUrlamigavel  $buscaStatus  $orderBy $buscaLimit ";
		 
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			if (!array_key_exists('totalRecords',$dados) && !array_key_exists('colsSql',$dados)){
				$r['iconbandeira'] = "admin/files/idiomas/".$r['bandeira'];
				$r['_bandeira'] = "<img src='files/idiomas/".$r['bandeira']."'>";
				$r["status_nome"] = ($r["status"]=='1' ? "Ativo":"Inativo");
                $r["status_icone"] = ($r["status"]=='1' ? "<img src='images/estrelasim.png' width='20px' />":"<img src='images/estrelanao.png' width='20px'/>"); 				
			}
			$resultado[] = $r;
		}
		return $resultado; 
 	}

	/**
	 * <p>deleta idiomas no banco</p>
	 */
	function deletaIdiomas($dados)
	{
		include "includes/mysql.php";

		$sql = "UPDATE idiomas SET status = '0' WHERE ididiomas = $dados";
		if (mysqli_query($conexao, $sql)) {
			return mysqli_affected_rows($conexao);
		} else {
			return FALSE;
		}
	}

//APAGAR IMAGENS DA PASTA
function deletaImagemIdiomas($id, $notarquivo, $_pasta, $notpasta = array(), $arquivoApagar = '') { 
	$caminho = "files/idiomas/"; 
	if(empty($notpasta)){
		$notpasta = array();
	} 

	if(!empty($id)){ 
		$caminho .= $id; 
		if(!empty($_pasta)){
			$caminho .= "/".$_pasta; 
		} 	 
		$dir = $caminho;   
		$pastas = array_diff(scandir($dir), array('.','..')); 
		
		foreach ($pastas as $pasta) {   
			if(in_array($pasta, $notpasta) != 1){ 
				$p = $dir."/".$pasta; 
				if(is_dir($p)){  
					$files = array_diff(scandir($p), array('.','..'));
					foreach ($files as $file) {  
						if(is_dir("$p/$file")){  
							deletaImagemIdiomas($id, $notarquivo, $pasta, $notpasta, $arquivoApagar);
						}else{ 
							if(!empty($arquivoApagar)){ 
								//apaga um arquivo espeficio da pasta  
								if($arquivoApagar == $file){
									if(file_exists("$p/$file")){
										unlink("$p/$file");
									}
								}
							}else{ 
								//apaga todos menos o arquivo $notarquivo, se passado
								if(empty($notarquivo) || $notarquivo != $file){
									if(file_exists("$p/$file")){
										unlink("$p/$file");
									}
								}
							}	
						}	
					}  
					if(is_dir($p)){ 
						$pT = array_diff(scandir($p), array('.','..')); 
						if(empty($pT)){
							 rmdir($p);
						}
					}	
				}else{
					if(!empty($arquivoApagar)){ 
						//apaga um arquivo espeficio da pasta  
						if($arquivoApagar == $pasta){
							if(file_exists($p)){
								unlink($p);
							}
						}
					}else{ 
						//apaga todos menos o arquivo $notarquivo, se passado
						if(empty($notarquivo) || $notarquivo != $pasta){ 
							if(file_exists($p)){
								unlink($p);
							}
						}
					}
				} 
			}		 
		}  

		$pT = array_diff(scandir("files/idiomas/".$id), array('.','..')); 
		if(empty($pT)){
			 rmdir("files/idiomas/".$id);
		}
	}	 
	 
	return true; 
}

?>