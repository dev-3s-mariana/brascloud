<?php

    function login($dados){
        include("includes/mysql.login.php");

        $sql = "SELECT * FROM usuario WHERE usuario = '".mysqli_real_escape_string($conexao, $dados['usuario'])."' and senha = UNHEX(MD5('".mysqli_real_escape_string($conexao, $dados['senha'])."'))";
       
        $query = mysqli_query($conexao, $sql);
        $n = mysqli_num_rows($query);
        $ip = $_SERVER['REMOTE_ADDR'];
        if($n == 1){
            $r = mysqli_fetch_assoc($query);
            $r = array_map('utf8_encode', $r);

            if($r['status'] == 0){
                return array('tipo'=>'bloqueado', 'usuario'=>$dados['usuario'], 'ip'=>$ip);
            }else{

                $_SESSION["sgc_idusuario"] = $r['idusuario'];
                $_SESSION["sgc_usuario"] = $r['usuario'];
                $_SESSION["sgc_nome"] = $r['nome'];
                $_SESSION["sgc_sobre_nome"] = $r['sobrenome'];
                $_SESSION["sgc_site"] = "Rcenter"; // nome do site atual
                $_SESSION["sgc_foto"] = $r['foto'];
                $_SESSION["sgc_BRASCLOUDeRlE4QsKAEOr2KOFcUce"] = "BRASCLOUDZ7TEcCCseZMKLC6xpNxf";

                $_SESSION["sgc_ultimoacesso"] = (!isset($r['ultimoacesso']))?(''):($r['ultimoacesso']);

                //salva acesso
                $sql = "INSERT INTO usuario_acesso (idusuario , ip) VALUES ('{$r['idusuario']}','$ip')";
                mysqli_query($conexao, $sql); 

                return array('tipo'=>'sucesso', 'usuario'=>$dados['usuario'], 'ip'=>$ip);
            }
        }else{
            return array('tipo'=>'falha', 'usuario'=>$dados['usuario'], 'ip'=>$ip);
        }
    }


    function logout(){
        include("includes/mysql.login.php");


        salvaAcaoLogout($_SESSION['sgc_idusuario']);


        unset($_SESSION["sgc_idusuario"],
                $_SESSION["sgc_usuario"],
                $_SESSION["sgc_nome"],
                $_SESSION["sgc_sobre_nome"],
                $_SESSION["sgc_site"],
                $_SESSION["sgc_foto"],
                $_SESSION["sgc_ultimoacesso"],
                $_SESSION["localizacao"], // Variavel usada para manter dados de previsao meteorologica
                $_SESSION["sgc_BRASCLOUDeRlE4QsKAEOr2KOFcUce"]);
    }


    function novaUsuario($dados){

        include "includes/mysql.php";
        include_once "includes/functions.php"; 

        $nome =  criaConsulta(trim(utf8_encode($dados['nome']))); 
        $sobrenome = criaConsulta(trim(utf8_encode($dados['sobrenome'])));   
        $usuario = $nome.".".$sobrenome;

        foreach ($dados AS $k => &$v) {
            if (is_array($v)) continue;
            // if (get_magic_quotes_gpc()) 
            $v = stripslashes($v);
            $v = mysqli_real_escape_string($conexao, utf8_decode($v));
        }    
 
        $sql = "INSERT INTO usuario(nome, sobrenome, usuario, senha, email, foto, permissoes)
                        VALUES (
                        '".$dados['nome']."',
                        '".$dados['sobrenome']."',
                        '".$usuario."',
                        UNHEX(MD5('".$dados['senha1']."')),
                        '".$dados['email']."',
                        '".$dados['foto']."',
                        '".$dados['permissoes']."'
                        )"; 

        //print $sql;
        $query = mysqli_query($conexao, $sql) or print mysqli_error($conexao);


        //busca id do novo usuario
        $sql = "SELECT * FROM usuario WHERE usuario LIKE \"$usuario\" and senha LIKE UNHEX(MD5(\"$dados[senha1]\")) ";
        $query = mysqli_query($conexao, $sql);
        $r = mysqli_fetch_assoc($query);
        $idUsuario = $r['idusuario'];

        //manda o primeiro acesso
        $sql = "INSERT INTO into usuario_acesso(idusuario,ip) VALUES (\"$idUsuario\",'Nenhum')";
        mysqli_query($conexao, $sql);

        return $idUsuario;
    }


    function editUsuario($dados){
        include("includes/mysql.php"); 
      
        foreach ($dados AS $k => &$v) {
            if (is_array($v)) continue;
            // if (get_magic_quotes_gpc()) 
            $v = stripslashes($v);
            $v = mysqli_real_escape_string($conexao, utf8_decode($v));
        }

        $permissoes = empty($dados['permissoes']) ? '' : ' permissoes = "'.$dados['permissoes'].'", ';

        // Se senha estiver preenchida entao altera o seu valor, senao mantem o valor existente na BD
        $senha = ($dados['senha1'] === '')?(''):("senha = UNHEX(MD5(\"$dados[senha1]\")) ,");

        $sql = "UPDATE usuario set
                    nome = '".$dados['nome']."',
                    sobrenome = '".$dados['sobrenome']."' ,
                    $senha
                    email= '".$dados['email']."',
                    ".$permissoes."
                    foto= '".$dados['foto']."'
                 WHERE idusuario = ".$dados['idu'];

        if (mysqli_query($conexao, $sql)) { 
            return true;
        } else {
            return false;
        } 
    }


    function editFotoUsuario($dados){
        include("includes/mysql.php");

        $dados = array_map('utf8_decode', $dados);
        $dados = array_map('strtolower', $dados);
        // if(!get_magic_quotes_gpc()){
        //     $dados = array_map('addslashes', $dados);
        // }

        $sql = "UPDATE usuario set
                        foto= '".$dados['foto']."'
                WHERE idusuario = ".$dados['idusuario']; 
         
        if (mysqli_query($conexao, $sql)) {
            return $dados['idusuario'];
        } else {
            return FALSE;
        }

    }


    function salvaUltimaAcao($idusuario){
        include("includes/mysql.php");

        $sql = "UPDATE usuario set
                    ultimaacao = CURRENT_TIMESTAMP,
                    proximotime = DATE_ADD(NOW(), INTERVAL 5 MINUTE)
                 WHERE idusuario = $idusuario";
        //echo $mandar;
        mysqli_query($conexao, $sql);
    }


    function salvaAcaoLogout($idusuario){
        include("includes/mysql.php");


        $sql = "UPDATE usuario set
                    proximotime = CURRENT_TIMESTAMP
                 WHERE idusuario = $idusuario";
        //echo $mandar;
        mysqli_query($conexao, $sql);
    }


    function deletaUsuario($id){
        include "includes/mysql.php";

        $bd_yg = "UPDATE usuario SET status = '0' WHERE idusuario = $id";
        $bd_exe = mysqli_query($conexao, $bd_yg) or print mysqli_error($conexao);

    }

    function buscaUsuario($dados){

        include("includes/mysql.php");

        //valida busca por id
        $buscaIdUsuario = "";
        if(array_key_exists("idusuario", $dados) && !empty($dados['idusuario'])){
            $buscaIdUsuario = " and idusuario = '$dados[idusuario]' ";
        }

        //valida busca por email
        $buscaEmail = " ";
        if(array_key_exists("email", $dados)){
            $buscaEmail = " and email like '%$dados[email]%'";
        }

        //valida busca por nome
        $buscaNome = "";
        if(array_key_exists("nome", $dados)){
            $buscaNome = " and ( nome like '%$dados[nome]%' or sobrenome like '%$dados[nome]%' )";
        }

        //valida busca por sobrenome
        $buscaSobrenome = "";
        if(array_key_exists("sobrenome", $dados)){
            $buscaSobrenome = " and sobrenome like '%$dados[sobrenome]%'";
        }


        //valida busca por status
        $buscaStatus = "";
        if(array_key_exists("status", $dados)){
            $buscaStatus = " and status like '$dados[status]' ";
        }else{
            $buscaStatus = " and status like '1' ";
        }

        //buscar todos os usuarios ao validar
        if(array_key_exists("semStatus", $dados)){
             $buscaStatus = "";
        }


        //valida busca por logado
        $buscaLogado = "";
        if(array_key_exists("logado", $dados) && !empty($dados['logado'])){
            if($dados['logado'] == 1){
                $buscaLogado = "and proximotime >= CURRENT_TIMESTAMP ";
            }else if($dados['logado'] == 0 || $dados['logado'] == 2){
                $buscaLogado = "and proximotime <= CURRENT_TIMESTAMP ";
            }
        }

        //valida busca por usuario
        $buscaUsuario = "";
        if(array_key_exists("usuario", $dados)){
            $buscaUsuario = " and usuario like '%$dados[usuario]%' ";
        }

        //valida busca por usuario_exato
        $buscaUsuario_exato = "";
        if(array_key_exists("usuario_exato", $dados)){
            $buscaUsuario_exato = " and usuario = '$dados[usuario_exato]' ";
        }

        //ordem
        $orderBy = "ORDER BY usuario ASC";
        if (array_key_exists('ordem',$dados) && !empty($dados['ordem'])){
                if($dados['ordem'] == "nomecompleto"){
                     $dados['ordem'] = "usuario";
                }
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
        $colsSql = "* ,date_format(ultimaacao, '%d/%m/%Y %H:%i:%s') as ultima_acao,   IF(proximotime >= CURRENT_TIMESTAMP,'1','0') as logado ";
        if (array_key_exists('totalRecords',$dados)){
                $colsSql = ' count(idusuario) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
        }


        $sql = "SELECT $colsSql
                FROM usuario
                WHERE 1 $buscaIdUsuario $buscaSobrenome $buscaLogado $buscaUsuario $buscaUsuario_exato $buscaEmail $buscaNome  $buscaStatus $orderBy $buscaLimit";

        //echo $bd_yg;
        $query = mysqli_query($conexao, $sql);

        $resultado = array();
        while($r = mysqli_fetch_assoc($query)){
            $r = array_map('stripslashes', $r);
            $r = array_map('utf8_encode', $r);
            if (!array_key_exists('totalRecords',$dados)){
                                $r['nomecompleto'] = $r['nome']." ".$r['sobrenome'];
                                $r['logadoimg'] = '<center><img src="images/'.($r['logado'] == 1 ? 'logado' : 'naologado').'.png" alt=" " /></center>';
                            }
            $resultado[] = $r;
        }
        return $resultado;
    }



    function buscaRelatorioAcesso($dados){

        include("includes/mysql.php");

        $idUsuario = "";
        if(array_key_exists("idusuario", $dados)){
            $idUsuario = " and ua.idusuario = $dados[idusuario] ";
        }


        $usuario = "";
        if(array_key_exists("usuario", $dados)){
            $usuario = " and ua.idusuario = (select idusuario from usuario where usuario LIKE '$dados[usuario]') ";
        }

        $nome = "";
        if(array_key_exists("nome", $dados)){
            $nome = " and u.usuario LIKE '%".$dados['nome']."%'";
        }


        //ordem
        $orderBy = "ORDER BY ua.data DESC";
        if (array_key_exists('ordem',$dados) && !empty($dados['ordem'])){
                $orderBy = ' ORDER BY u.'.$dados['ordem'];
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
        $colsSql = "u.idusuario, DATE_FORMAT(ua.data,'%d/%m/%Y - %H:%i:%s') as data, ua.ip, u.usuario ";
        if (array_key_exists('totalRecords',$dados)){
                $colsSql = ' count(u.idusuario) as totalRecords';
                $buscaLimit = '';
                $orderBy = '';
        }

        $sql = "SELECT $colsSql
                        FROM usuario_acesso ua, usuario u
                        WHERE 1 $idUsuario $usuario and ua.idusuario = u.idusuario  $nome
                        $orderBy $buscaLimit";

        $query = mysqli_query($conexao, $sql);
        $resultado = array();

        while($r = mysqli_fetch_assoc($query)){
            $resultado[] = $r;
        }

        return $resultado;
    }


    function verificaPermissaoAcesso($modulo, $usuario){
          include("includes/mysql.php");

          $sql = "SELECT * FROM usuario WHERE idusuario = $usuario AND permissoes LIKE '%$modulo%'";
          $query = mysqli_query($conexao, $sql);

          if(mysqli_num_rows($query) == 1){
              return true;
          }else{
              return false;
          }

    }

    function buscaUltimoAcesso($idUsuario) {
        include("includes/mysql.php");

        $sql = "select date_format(datahora, '%d/%m/%Y - %H:%i') as datahora from log where idusuario = $idUsuario and modulo = 'login' order by idlog desc limit 1";

        $query = mysqli_query($conexao, $sql);
        $resultado = array();

        while($r = mysqli_fetch_assoc($query)){
            $resultado[] = $r;
        }

        return $resultado;
    }
?>
