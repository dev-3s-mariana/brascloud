<?php 
     // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_SEO") || define("CADASTRO_SEO","cadastroSeo");
defined("EDIT_SEO") || define("EDIT_SEO","editSeo");
defined("DELETA_SEO") || define("DELETA_SEO","deletaSeo");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");
defined("VERIFICAR_URLREWRITE") || define("VERIFICAR_URLREWRITE","verificarUrlRewrite");

switch ($opx) {

    case CADASTRO_SEO:
        include_once 'seo_class.php';

        $dados = $_REQUEST;
        $idSeo = cadastroSeo($dados);
        
        if (is_int($idSeo)) {
            //salva log
            include_once 'log_class.php';
            $log['idusuario'] = $_SESSION['sgc_idusuario'];
            $log['modulo'] = 'seo';
            $log['descricao'] = 'Cadastrou seo ID('.$idSeo.') title ('.$dados['title'].') description ('.$dados['description'].')';
            $log['request'] = $_REQUEST;
            novoLog($log);
            header('Location: index.php?mod=seo&acao=listarSeo&mensagemalerta='.urlencode('Seo criado com sucesso!'));
        } else {
            header('Location: index.php?mod=seo&acao=listarSeo&mensagemerro='.urlencode('ERRO ao criar novo Seo!'));
        }

    break;

    case EDIT_SEO:
        include_once 'seo_class.php';

        $dados = $_REQUEST;
        $antigo = buscaSeo(array('idseo'=>$dados['idseo']));
        $antigo = $antigo[0];

        $idSeo = editSeo($dados);

        if ($idSeo != FALSE) {
            //salva log
            include_once 'log_class.php';
            $log['idusuario'] = $_SESSION['sgc_idusuario'];
            $log['modulo'] = 'seo';
            $log['descricao'] = 'Editou seo ID('.$idSeo.') DE  title ('.$antigo['title'].') description ('.$antigo['description'].') PARA  title ('.$dados['title'].') description ('.$dados['description'].')';
            $log['request'] = $_REQUEST;
            novoLog($log);
            header('Location: index.php?mod=seo&acao=listarSeo&mensagemalerta='.urlencode('Seo salvo com sucesso!'));
        } else {
            header('Location: index.php?mod=seo&acao=listarSeo&mensagemerro='.urlencode('ERRO ao salvar Seo!'));
        }

    break;

    case DELETA_SEO:
        include_once 'seo_class.php';
        include_once 'usuario_class.php';

        if (!verificaPermissaoAcesso('seo_deletar', $_SESSION['sgc_idusuario'])){
            header('Location: index.php?mod=seo&acao=listarSeo&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
            exit;
        } else {
            $dados = $_REQUEST;
            $antigo = buscaSeo(array('idseo'=>$dados['idu']));

            if (deletaSeo($dados['idu']) == 1) {
                //salva log
                include_once 'log_class.php';
                $log['idusuario'] = $_SESSION['sgc_idusuario'];
                $log['modulo'] = 'seo';
                $log['descricao'] = 'Deletou seo ID('.$dados['idu'].') ';
                $log['request'] = $_REQUEST;
                novoLog($log);
                header('Location: index.php?mod=seo&acao=listarSeo&mensagemalerta='.urlencode('Seo deletado com sucesso!'));
            } else {
                header('Location: index.php?mod=seo&acao=listarSeo&mensagemerro='.urlencode('ERRO ao deletar Seo!'));
            }
        }

    break;


    case ALTERA_ORDEM_BAIXO:
        include_once("seo_class.php");

        $dados = $_REQUEST;
        $resultado['status'] = 'sucesso';
        try {
            $seo = buscaSeo(array('idseo'=>$dados['idseo']));
            $seo = $seo[0];

            $ordemAux = 0;
            $ordem = $seo['ordem'];
            
            while($ordemAux == 0){
                 $ordem = $ordem + 1;
                 $seoAux = buscaSeo(array('ordenacao'=>$ordem));
                 if(!empty($seoAux)){
                    $seoAux = $seoAux[0];
                    $ordemAux = $seoAux['ordem'];
                 }
            }
            if(!empty($seoAux)){
                $seoAux['ordem'] = $seo['ordem'];
                $seo['ordem'] = $ordemAux;
                editSeo($seo);
                editSeo($seoAux);
             }

            print json_encode($resultado);

        } catch (Exception $e) {
            $resultado['status'] = 'falha';
            print json_encode($resultado);
        }
    break;
    
    case ALTERA_ORDEM_CIMA:
        include_once("seo_class.php");

        $dados = $_REQUEST; 
        $resultado['status'] = 'sucesso';
        try {
            $seo = buscaSeo(array('idseo'=>$dados['idseo']));
            $seo = $seo[0];
            $ordemAux = 0;
            $ordem = $seo['ordem'];
            while($ordemAux == 0)
            {
                 $ordem = $ordem - 1;

                 $seoAux = buscaSeo(array('ordenacao'=>$ordem));
                 if(!empty($seoAux)){
                    $seoAux = $seoAux[0];
                    $ordemAux = $seoAux['ordem'];
                 }
            }
            if(!empty($seoAux)){

                $seoAux['ordem'] = $seo['ordem'];
                $seo['ordem'] = $ordemAux;

                editSeo($seo);
                editSeo($seoAux);
             }

            print json_encode($resultado);

        } catch (Exception $e) {
            $resultado['status'] = 'falha';
            print json_encode($resultado);
        }
    break;

    case INVERTE_STATUS:
        include_once("seo_class.php");
        $dados = $_REQUEST;
        // inverteStatus($dados);
        $resultado['status'] = 'sucesso';

        try {
            $seo = buscaSeo(array('idseo' => $dados['idseo']));
            $seo = $seo[0];

            // print_r($seo);
            if($seo['status'] == 'A'){
                $status = 'I';
            }
            else{
                $status = 'A';
            }

            $dadosUpdate = array();
            $dadosUpdate['idseo'] = $dados['idseo'];
            $dadosUpdate['status'] = $status;
            inverteStatus($dadosUpdate);

            print json_encode($resultado);
        } catch (Exception $e) {
            $resultado['status'] = 'falha';
            print json_encode($resultado);
        }
    break;

    case VERIFICAR_URLREWRITE:

        include_once('seo_class.php'); 
        include_once('includes/functions.php');
        
        $dados = $_POST;
         
        $urlrewrite = converteUrl(utf8_encode(str_replace("-", " ", $dados['urlrewrite'])));
        
        if($dados['idseo'] && $dados['idseo'] <= 0){
            $url = buscaSeo(array("urlrewrite"=>$urlrewrite));    
        }else{ 
            $url = buscaSeo(array("urlrewrite"=>$urlrewrite,"not_idseo"=>$dados['idseo'])); 
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