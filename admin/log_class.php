<?php
	function converteArray($dados){
		$ret = '';
		foreach($dados as $k=>$v){
			if(is_array($v)){
				$ret .= '['.$k.'] (';
				foreach($v as $kk=>$vv){
					$ret .= "\r\n\t".'['.$kk.'] => '.$vv;
				}
				$ret .= "\r\n".')'."\r\n";
			}else{
				$ret .= 	'['.$k.'] => '.$v."\r\n";
			}
		}
		return $ret;
	}



	function novoLog($dados){
		include("includes/mysql.php");

		$dados['request'] = converteArray($dados['request']);
		$dados['idusuario'] = ($dados['idusuario'] == '0')?('NULL'):($dados['idusuario']);

		foreach ($dados AS $k => &$v) {
			if (is_array($v)) continue;
			$v = mysqli_real_escape_string($conexao, $v);
		}

		$sql= "insert into log (idusuario, modulo, descricao, request) values ($dados[idusuario],'$dados[modulo]', '$dados[descricao]', '$dados[request]')";

		@mysqli_query($conexao, $sql);

	}



	function buscaLog($dados){
		include("includes/mysql.php");

                        //valida busca por id
			$buscaIdLog = "";
			if(array_key_exists("idlog", $dados)){
				$buscaIdLog = " and L.idlog = '$dados[idlog]' ";
			}


                        //valida busca por usuario
			$buscaIdUsuario = "";
			if(array_key_exists("idusuario", $dados)){
				$buscaIdUsuario = " and L.idusuario LIKE \"$dados[idusuario]\"";
			}

                        //valida busca por modulo
			$buscaModulo = "";
			if(array_key_exists("nomemodulo", $dados) && !empty($dados['nomemodulo'])){
				$buscaModulo = " and L.modulo LIKE '%".$dados['nomemodulo']."%'";
			}

            //valida busca por usuario
			$buscaUsuario = "";
			if(array_key_exists("nome", $dados) && !empty($dados['nome'])){
				$buscaUsuario = " and U.nome LIKE '%".$dados['nome']."%'";
			}

			$buscaDescricao = "";
			if(array_key_exists("descricao", $dados) && !empty($dados['descricao'])){
				$buscaDescricao = " and L.descricao LIKE '%".$dados['descricao']."%'";
			}

            $buscaData = "";
            if(array_key_exists('data_inicio',$dados) && !empty($dados['data_inicio'])){
                $tmpData = explode('/', stripslashes($dados['data_inicio']));
                $buscaData = $tmpData[2]."-".$tmpData[1]."-".$tmpData[0];

                if(array_key_exists('data_fim',$dados) && !empty($dados['data_fim'])){
                     $tmpData_fim = explode('/', stripslashes($dados['data_fim']));
                     $data_fim = $tmpData_fim[2]."-".$tmpData_fim[1]."-".$tmpData_fim[0];
                     $buscaData = "and L.datahora BETWEEN '". $buscaData ."' AND '". $data_fim."'";
                }else{
                     $buscaData = "and L.datahora = '". $buscaData ."'";
                }
            }

	        //ordem
	        $orderBy = "order by datahora desc";
	        if (array_key_exists('ordem',$dados) && !empty($dados['ordem'])){
	                $orderBy = ' ORDER BY L.'.$dados['ordem'] ." ".$dados['dir'];
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
	        $colsSql = "L.*, date_format(L.datahora, '%d/%m/%Y - %H:%i') as datahora";
	        if (array_key_exists('totalRecords',$dados)){
	                $colsSql = ' count(L.idlog) as totalRecords';
	                $buscaLimit = '';
	                $orderBy = '';
	        }

	        $bd_yg = "select $colsSql from log as L
	                  left join usuario as U on L.idusuario = U.idusuario
	                  where 1 $buscaIdLog $buscaIdUsuario $buscaUsuario $buscaDescricao $buscaModulo $buscaData $orderBy $buscaLimit";

			$bd_exe = mysqli_query($conexao, $bd_yg) or print mysqli_error($conexao);
			$dados = array();
			//if(mysqli_num_rows($bd_exe) > 0)
			  while($resultado = mysqli_fetch_assoc($bd_exe)){
				  $dados[] = $resultado;
			  }

			return $dados;
	}


?>
