<?php


	function getPermissoes(){
		$tmp = explode('/', str_replace('/index.php', '', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']));
		$local = '../'.end($tmp);

		$diretorio = scandir($local);

		$arquivos = array();
		foreach($diretorio as $k=>$v){
			if(strpos($v, '_script.php') !== false){
				$tmp = explode('_', $v);
				array_pop($tmp);

				$nome = '';
				if(count($tmp) == 1){
					$nome = $tmp[0];
				}else{
					$nome = '';
					for($i=0; $i< count($tmp); $i++){
						$nome .= $i == (count($tmp)-1) ? $tmp[$i] : $tmp[$i].'_';
					}
				}
				$arquivos[$nome] = array('criar', 'editar', 'visualizar', 'deletar');
			}
		}


		return $arquivos;
	}






	function novaPermissao($dados){
		include "includes/mysql.php";


		$sql = "INSERT INTO permissoes(apelido, tags) VALUES (\"$dados[apelido]\",\"$dados[permissoes]\")";
		$query = mysqli_query($conexao, $sql);

		$sql = "SELECT * FROM permissoes WHERE apelido LIKE \"$dados[apelido]\" AND tags LIKE \"$dados[permissoes]\" ";
		$query = mysqli_query($conexao, $sql);

		$r = mysqli_fetch_assoc($query);
		$idApelido = $r['idpermissao']; 

		return $idApelido;

	}






	function editPermissao($dados){
		include "includes/mysql.php";

		$sql = "UPDATE permissoes SET
					apelido = \"$dados[apelido]\",
					tags =\"$dados[permissoes]\"
				  WHERE idpermissao = $dados[idpermissao] ";

		mysqli_query($conexao, $sql);
	}














	function buscaPermissao($dados){

		include "includes/mysql.php";

		//valida busca por id
		$buscaIdPermissao = "";
		if(array_key_exists("idpermissao", $dados)){
			$buscaIdPermissao = " and idpermissao = '$dados[idpermissao]' ";
		}

		//valida busca por nome
		$buscaApelido = "";
		if(array_key_exists("apelido", $dados)){
			$buscaApelido = " and apelido LIKE '%".$dados['apelido']."%'";
		}

		//valida busca por usuario
		$buscaTags = "";
		if(array_key_exists("tags", $dados)){
			$buscaTags = " and tags LIKE '%$dados[tags]%' ";
		}


        //ordem
        $orderBy = "ORDER BY apelido ASC";
        if (array_key_exists('ordem',$dados) && !empty($dados['ordem'])){
                $orderBy = ' ORDER BY '.$dados['ordem'] ." ".$dados['dir'];
        }

        //busca pelo limit
        $buscaLimit = '';
        if(array_key_exists('limit',$dados) && !empty($dados['limit']) && array_key_exists('pagina',$dados)){
              $buscaLimit = ' LIMIT '.($dados['limit'] * $dados['pagina']).','.$dados['limit'].' ';
        }
        else if(array_key_exists('limit',$dados) && !empty($dados['limit'])){
                $buscaLimit = ' LIMIT '.$dados['limit'];
        }

        //colunas que serão buscadas
        $colsSql = "*";
        if (array_key_exists('totalRecords',$dados)){
                $colsSql = ' count(idpermissao) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
        }


		$sql = "SELECT $colsSql FROM permissoes WHERE 1 $buscaIdPermissao $buscaApelido $buscaTags $orderBy $buscaLimit";
		$bd_exe = mysqli_query($conexao, $sql);

		//if(mysqli_num_rows($bd_exe))
		$listaPermissao = array();
		while($resultado = mysqli_fetch_assoc($bd_exe)){
			$listaPermissao[] = $resultado;
		}

		return $listaPermissao;
	}
 



	function deletaPermissao($id){

		include "includes/mysql.php";

		$sql = "DELETE FROM permissoes WHERE idpermissao = $id";

		$query = mysqli_query($conexao, $sql);


	}

 



	function verificaPermissao($consulta, $idApelido){
		  include "includes/mysql.php";


		  $idpermissao = empty($idApelido) ? 0 : $idApelido;

		  $sql = "SELECT * FROM permissoes WHERE idpermissao = $idpermissao AND tags LIKE '%$consulta%'";
		  $bd_exe = mysqli_query($conexao, $sql);

		  if(mysqli_num_rows($bd_exe) == 1){
			  return true;
		  }else{
			  return false;
		  }

	}




?>
