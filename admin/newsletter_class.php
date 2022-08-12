<?php 
	 // Versao do modulo: 3.00.010416


	/**
	 * <p>salva newsletter no banco</p>
	 */
	$str = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoO1234567890pqrstuv";
    $rand = str_shuffle($str);
 
	function cadastroNewsletter($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "INSERT INTO newsletter(email, nome) VALUES (
						'".$dados['email']."',
						'".$dados['nome']."')";

		if (mysqli_query($conexao, $sql)) {
			$resultado = mysqli_insert_id($conexao);
			return $resultado;
		} else {
			return false;
		}
	}

	/**
	 * <p>edita newsletter no banco</p>
	 */
	function editNewsletter($dados)
	{
		include "includes/mysql.php";

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		$sql = "UPDATE newsletter SET
						email = '".$dados['email']."',
						nome = '".$dados['nome']."'	
					WHERE idnewsletter = " . $dados['idnewsletter'];

		if (mysqli_query($conexao, $sql)) {
			return $dados['idnewsletter'];
		} else {
			return false;
		}
	}

	

	/**
	 * <p>busca newsletter no banco</p>
	 */
	function buscaNewsletter($dados = array()){
		include "includes/mysql.php";
		foreach ($dados AS $k => &$v) {
			if (is_array($v) || $k == "colsSql") continue;
			// if (get_magic_quotes_gpc()) 
         $v = stripslashes($v);
			$v = mysqli_real_escape_string($conexao, utf8_decode($v));
		}

		//busca pelo id
		$buscaId = '';
		if (array_key_exists('idnewsletter',$dados) && !empty($dados['idnewsletter']) )
			$buscaId = ' and C.idnewsletter = '.intval($dados['idnewsletter']).' '; 

		//busca pela extencao
		$buscaNome = '';
		if (array_key_exists('nome',$dados) && !empty($dados['nome']) )
			$buscaNome =' and C.nome LIKE "%'.$dados['nome'].'%" '; 

			//busca pela extencao
		$buscaEmail = '';
		if (array_key_exists('email',$dados) && !empty($dados['email']) )
			$buscaEmail =' and C.email LIKE "%'.$dados['email'].'%" '; 

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
		$colsSql = 'C.*';
		if (array_key_exists('totalRecords',$dados)){
			$colsSql = ' count(idnewsletter) as totalRecords';
            $buscaLimit = '';
            $orderBy = '';
        } elseif (array_key_exists('colsSql',$dados)) {
			$colsSql = ' '.$dados['colsSql'].' ';
		}

		$sql = "SELECT DISTINCT $colsSql
				FROM newsletter as C
				WHERE 1 $buscaId $buscaEmail $buscaNome  $orderBy $buscaLimit ";
				
		$query = mysqli_query($conexao, $sql);
		$resultado = array();
		while ($r = mysqli_fetch_assoc($query)){
			$r = array_map('utf8_encode', $r);
			//$r['nomecliente'] = buscaAcesso(array('idacesso'=>$r['idcliente']));
			$resultado[] = $r;
		}
					
		return $resultado; 
 	}

	/**
	 * <p>deleta newsletter no banco</p>
	 */
	function deletaNewsletter($dados)
	{
		include "includes/mysql.php";
		
		$arquivo = buscaNewsletter(array("idnewsletter"=>$dados));
		$arquivo = $arquivo[0]; 
		$caminho = $arquivo['arquivo'];

		$sql = "DELETE FROM newsletter WHERE idnewsletter = $dados";
		if (mysqli_query($conexao, $sql)) {
			$num = mysqli_affected_rows($conexao);
			unlink($caminho);
			return $num;

			
		} else {
			return FALSE;
		}
	}

//PUXA SELECT DE CLIENTES \\
	//include "includes/mysql.php";
	//$query="SELECT idacessos, nome FROM acessos WHERE status = 1";
	//$newsletter2 = mysqli_query( $conexao,$query) or trigger_error(mysqli_error($conexao));

function sizeFilter( $bytes ){
    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $label[$i] );
}

function tipoArquivo($dados){
	if(!($dados['campo']==".txt" || $dados['campo']==".pdf"|| $dados['campo']==".xls"|| $dados['campo']==".docx" || $dados['campo']==".png" || $dados['campo']==".jpeg")){
		$def = ".default";
	}
	else{
		$def = $dados['campo'];
		return $def;
	}
}





