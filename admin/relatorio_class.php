<?php
	 // Versao do modulo: 2.3


	if(!empty($_GET)){
		geraRelatorioQuery($_GET['modulo'], $_GET, $_GET['output']);
	}



	//GERA ARRAY RELATORIO
	function geraRelatorioQuery($modulo, $query='', $output){

                $colunas = "";
                if(array_key_exists("colunas", $query)){
                      $colunas = json_decode($query["colunas"]);
                      unset($query['colunas']);
                }

		$resultado = array();

		//retorna o resultado
		$resultado = retornaResultado($modulo, $query);

		//prapara array os campos
		$resultado['valores'] = preparaArray($resultado, configuraCampos($modulo, $colunas));
                $resultado['modulo'] = $modulo;
		//configura a saida
		if($output == "xls"){
			include_once("xls_class.php");
			exportaXls($resultado);
		}else if($output == "print"){
			include_once("print_class.php");
			exportaTela($resultado);
		}
	}



	//GERA ARRAY RELATORIO
	function retornaResultado($modulo, $query=''){

		$dados = array();

		// modulo de usu�rios
		if($modulo == "usuario"){
			include_once 'usuario_class.php';
			$dados = buscaUsuario($query);
		}
		// modulo de acessos de usu�rio
		else if($modulo == "relatorioacesso"){
			include_once 'usuario_class.php';
			$dados = buscaRelatorioAcesso($query);
		}
		// m�dulo de log
		else if($modulo == "log"){
			include_once 'log_class.php';
			unset($query['modulo']);
			$dados = buscaLog($query);
		}
		// m�dulo de permiss�es
		else if($modulo == "permissao"){
			include_once 'permissao_class.php';
			$dados = buscaPermissao($query);
		}
		else{
			$modulo = preg_replace('/[^a-zA-Z0-9_]/', '', $modulo);
			if(file_exists($modulo  . "_class.php"))
		    {
			   include_once($modulo . "_class.php");
			   $funcName = 'busca' . ucfirst($modulo);
			   //Busca Registros
       		   $dados = $funcName($query);
			}
		}

        return $dados;

	}



	//prepara o array
	function preparaArray($arrayInteiro, $campos){

		$dados = array();
		if(!empty($arrayInteiro)){
            if(isset($campos['print'])){
               foreach($arrayInteiro as $k=>$v){
                        foreach($campos['print'] as $kk => $vv){
                                  $key = $campos['key'][$kk];
                                  $dados[$k][$vv] = (trim($v[$key]) == '') ? "&nbsp;" : ($v[$key]);
//                                             $dados[$k][$vv] = empty($v[$campos['key'][$kk]]) ? "&nbsp;" : ($v[$campos['key'][$kk]]);
                        }
                }
            }else{
                foreach($arrayInteiro as $k=>$v){
                        foreach($campos as $vv){
                                $dados[$k][$vv] = (trim($v[$vv]) == '') ? "&nbsp;" : ($v[$vv]);
                        }
                }
            }
		}
		return $dados;

	}

	//confira os campos que serao exibidos
	function configuraCampos($modulo, $colunas){

        if(!empty($colunas)){
            $dados = "";
            $print = array();
            $key = array();
            foreach ($colunas as $k => $v){
                $dados = (array) $v;
                if($dados['print']){
                    $print[] = $dados['label'];
                    $key[] = $dados['key'];
                }
            }
            $campos["print"] = $print;
            $campos["key"] = $key;
            return $campos;
        }else{
            if($modulo == "usuario"){
                    return $campos = array("idusuario","nomecompleto","usuario","email", "ultima_acao", "logado");
            }
            else if($modulo == "relatorioacesso"){
                    return $campos = array("idusuario","usuario", "data", "ip");
            }
            else if($modulo == "log"){
                    return $campos = array("idusuario","datahora", "modulo", "descricao");
            }
            else if($modulo == "permissao"){
                    return $campos = array("idpermissao","apelido","tags");
            }
            else if($modulo == "newsletter"){
                    return $campos = array( "nome", "email");
            }
            else if($modulo == "cliente"){
                    return $campos = array("idcliente", "nome_razao", "pessoa_tipo", "nome_fantasia", "descricao", "ramo_empresa", "unidades");
            }
        }
		return array();
	}


?>
