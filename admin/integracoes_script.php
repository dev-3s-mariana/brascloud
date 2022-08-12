<?php 
     // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_INTEGRACOES") || define("CADASTRO_INTEGRACOES","cadastroIntegracoes");
defined("EDIT_INTEGRACOES") || define("EDIT_INTEGRACOES","editIntegracoes");
defined("DELETA_INTEGRACOES") || define("DELETA_INTEGRACOES","deletaIntegracoes");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");
defined("VERIFICAR_URLREWRITE") || define("VERIFICAR_URLREWRITE","verificarUrlRewrite");

switch ($opx) {

    case CADASTRO_INTEGRACOES:
        include_once 'integracoes_class.php';

        $dados = $_REQUEST;
        $idIntegracoes = cadastroIntegracoes($dados);
        
        if (is_int($idIntegracoes)) {
            //salva log
            include_once 'log_class.php';
            $log['idusuario'] = $_SESSION['sgc_idusuario'];
            $log['modulo'] = 'integracoes';
            $log['descricao'] = 'Cadastrou integracoes ID('.$idIntegracoes.') token ('.$dados['token'].') ';
            $log['request'] = $_REQUEST;
            novoLog($log);
            header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemalerta='.urlencode('Integracoes criado com sucesso!'));
        } else {
            header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemerro='.urlencode('ERRO ao criar novo Integracoes!'));
        }

    break;

    case EDIT_INTEGRACOES:
        include_once 'integracoes_class.php';

        $dados = $_REQUEST;
        $antigo = buscaIntegracoes(array('idintegracoes'=>$dados['idintegracoes']));
        $antigo = $antigo[0];

        $idIntegracoes = editIntegracoes($dados);

        if ($idIntegracoes != FALSE) {
            //salva log
            include_once 'log_class.php';
            $log['idusuario'] = $_SESSION['sgc_idusuario'];
            $log['modulo'] = 'integracoes';
            $log['descricao'] = 'Editou integracoes ID('.$idIntegracoes.') DE  token ('.$antigo['token'].')  PARA  token ('.$dados['token'].')';
            $log['request'] = $_REQUEST;
            novoLog($log);
            header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemalerta='.urlencode('Integracoes salvo com sucesso!'));
        } else {
            header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemerro='.urlencode('ERRO ao salvar Integracoes!'));
        }

    break;

    case DELETA_INTEGRACOES:
        include_once 'integracoes_class.php';
        include_once 'usuario_class.php';

        if (!verificaPermissaoAcesso('integracoes_deletar', $_SESSION['sgc_idusuario'])){
            header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
            exit;
        } else {
            $dados = $_REQUEST;
            $antigo = buscaIntegracoes(array('idintegracoes'=>$dados['idu']));

            if (deletaIntegracoes($dados['idu']) == 1) {
                //salva log
                include_once 'log_class.php';
                $log['idusuario'] = $_SESSION['sgc_idusuario'];
                $log['modulo'] = 'integracoes';
                $log['descricao'] = 'Deletou integracoes ID('.$dados['idu'].') ';
                $log['request'] = $_REQUEST;
                novoLog($log);
                header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemalerta='.urlencode('Integracoes deletado com sucesso!'));
            } else {
                header('Location: index.php?mod=integracoes&acao=listarIntegracoes&mensagemerro='.urlencode('ERRO ao deletar Integracoes!'));
            }
        }

    break;


    case ALTERA_ORDEM_BAIXO:
        include_once("integracoes_class.php");

        $dados = $_REQUEST;
        $resultado['status'] = 'sucesso';
        try {
            $integracoes = buscaIntegracoes(array('idintegracoes'=>$dados['idintegracoes']));
            $integracoes = $integracoes[0];

            $ordemAux = 0;
            $ordem = $integracoes['ordem'];
            
            while($ordemAux == 0){
                 $ordem = $ordem + 1;
                 $integracoesAux = buscaIntegracoes(array('ordenacao'=>$ordem));
                 if(!empty($integracoesAux)){
                    $integracoesAux = $integracoesAux[0];
                    $ordemAux = $integracoesAux['ordem'];
                 }
            }
            if(!empty($integracoesAux)){
                $integracoesAux['ordem'] = $integracoes['ordem'];
                $integracoes['ordem'] = $ordemAux;
                editIntegracoes($integracoes);
                editIntegracoes($integracoesAux);
             }

            print json_encode($resultado);

        } catch (Exception $e) {
            $resultado['status'] = 'falha';
            print json_encode($resultado);
        }
    break;
    
    case ALTERA_ORDEM_CIMA:
        include_once("integracoes_class.php");

        $dados = $_REQUEST; 
        $resultado['status'] = 'sucesso';
        try {
            $integracoes = buscaIntegracoes(array('idintegracoes'=>$dados['idintegracoes']));
            $integracoes = $integracoes[0];
            $ordemAux = 0;
            $ordem = $integracoes['ordem'];
            while($ordemAux == 0)
            {
                 $ordem = $ordem - 1;

                 $integracoesAux = buscaIntegracoes(array('ordenacao'=>$ordem));
                 if(!empty($integracoesAux)){
                    $integracoesAux = $integracoesAux[0];
                    $ordemAux = $integracoesAux['ordem'];
                 }
            }
            if(!empty($integracoesAux)){

                $integracoesAux['ordem'] = $integracoes['ordem'];
                $integracoes['ordem'] = $ordemAux;

                editIntegracoes($integracoes);
                editIntegracoes($integracoesAux);
             }

            print json_encode($resultado);

        } catch (Exception $e) {
            $resultado['status'] = 'falha';
            print json_encode($resultado);
        }
    break;

    case INVERTE_STATUS:
        include_once("integracoes_class.php");
        $dados = $_REQUEST;
        // inverteStatus($dados);
        $resultado['status'] = 'sucesso';

        try {
            $integracoes = buscaIntegracoes(array('idintegracoes' => $dados['idintegracoes']));
            $integracoes = $integracoes[0];

            // print_r($integracoes);
            if($integracoes['status'] == 'A'){
                $status = 'I';
            }
            else{
                $status = 'A';
            }

            $dadosUpdate = array();
            $dadosUpdate['idintegracoes'] = $dados['idintegracoes'];
            $dadosUpdate['status'] = $status;
            inverteStatus($dadosUpdate);

            print json_encode($resultado);
        } catch (Exception $e) {
            $resultado['status'] = 'falha';
            print json_encode($resultado);
        }
    break;

    case VERIFICAR_URLREWRITE:

        include_once('integracoes_class.php'); 
        include_once('includes/functions.php');
        
        $dados = $_POST;
         
        $urlrewrite = converteUrl(utf8_encode(str_replace("-", " ", $dados['urlrewrite'])));
        
        if($dados['idintegracoes'] && $dados['idintegracoes'] <= 0){
            $url = buscaIntegracoes(array("urlrewrite"=>$urlrewrite));    
        }else{ 
            $url = buscaIntegracoes(array("urlrewrite"=>$urlrewrite,"not_idintegracoes"=>$dados['idintegracoes'])); 
        } 

        if(empty($url)){ 
            print '{"status":true,"url":"'.$urlrewrite.'"}';
        }else{
            print '{"status":false}';
        } 

    break;

    default:
        if (!headers_sent() && (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
            header('Location: index.php?mod=home&mensagemerro='.urlencode('Nenhuma acao definida...'));
        } else {
            trigger_error('Erro...', E_USER_ERROR);
            exit;
        }

}
?>