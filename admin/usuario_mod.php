<?php
    include_once "usuario_class.php";
    include_once 'permissao_class.php';

  $_SESSION['titulo_relatorio'] = "Usuários";

    if(!isset($_REQUEST['acao']))
        $_REQUEST['acao'] = "";
?>

<style type="text/css">
    @import url(usuario_css.css);
</style>
<script type="text/javascript" src="usuario_js.js"></script>


<?php if($_REQUEST['acao'] == "formUsuario"){

    if($_REQUEST["met"] == "novaUsuario"){
        if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario']))
          header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));

        $action = "usuario_script.php?opx=novoUsuario";
        $metodo_titulo = "Cadastro Usuário";
        $_REQUEST['idu'] = 0;

        $usuario['nome'] = '';
        $usuario['sobrenome'] = '';
        $usuario['email'] = '';
        $usuario['senha'] = '';
        $usuario['nivel'] = '1';
    $idusuario = "";

    }else{
        if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario']) && $MODULOACESSO['usuario'] != $_GET['idu'])
          header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));

        $metodo_titulo = "Editar Usuário";
        $action = "usuario_script.php?opx=editUsuario&idu=$_REQUEST[idu]";
        $dados['idusuario'] = $_REQUEST['idu'];
        $resultado = buscaUsuario($dados);
        $usuario = $resultado[0];
    $idusuario = $usuario['idusuario'];
    }

    $permissoes = getPermissoes();
    $apelidos = buscaPermissao(array());
?>
<script type="text/javascript">
    var id = '<?php echo (array_key_exists('idusuario', $usuario))?($usuario['idusuario']):(''); ?>';
</script>

<div id="titulo">
  <span><?php print $metodo_titulo; ?></span>
  <ul class="other_abs">
            <li class="other_abs_li"><a href="index.php?mod=usuario&acao=listarUsuario">Listagem</a></li>
            <li class="others_abs_br"></li>
            <li class="other_abs_li"><a href="index.php?mod=usuario&acao=formUsuario&met=novaUsuario">Cadastro</a></li>
  </ul>
</div>
<div id="principal">
  <div class="form">
    <!--<form onSubmit="return (validaSenha('senha1','senha2') && verificarCampos(new Array('nome','sobrenome', 'email'))<?php print $_REQUEST["met"] == "novaUsuario" ? ' && validaUsuario()' : '' ?>);" id="form2" name="form2" method="post" action="<?php echo $action; ?>">-->
     <form id="form2" name="form2" method="post" action="<?php echo $action; ?>">
      <div class="content_left">
            <!--################################## INFORMAACOES #################################################
            ###############################################################################################-->
            <div id="informacaoUsuario" class="content">
              <div class="content_tit">Informa&ccedil;&otilde;es de login:</div>
              <div class="box_ip box_full"><label>Nome</label><input type="text" id='nome' name="nome" value="<?php echo $usuario['nome']; ?>" size="30" maxlength="20" tabindex="1" class="required" /></div>
              <div class="box_ip box_full"><label>Sobrenome</label><input type="text" name="sobrenome" id="sobrenome" value="<?php echo $usuario['sobrenome']; ?>" size="30" maxlength="20" tabindex="2" class="required" /></div>
              <div class="box_ip box_full"><label>Email</label><input name="email" type="text" id="email" autocomplete="off" value="<?php echo $usuario['email']; ?>" size="30" maxlength="50" tabindex="3" class="required" /><input name="emailantigo" type="hidden" id="emailantigo" value="<?php echo $usuario['email']; ?>"/></div>
              <div class="box_ip box_full"><label>Senha</label><input name="senha1" type="password" id="senha1" tabindex="4" value="" size="30" maxlength="100" autocomplete="off" class='<?= (empty($idusuario))?"required":"" ?>'/></div>
              <div class="box_ip box_full"><label>Confirma&ccedil;&atilde;o de senha</label><input name="senha2" type="password" id="senha2" autocomplete="off" value="" size="30" maxlength="100" tabindex="5" class='<?= (empty($idusuario))?"required":"" ?>'/> </div>
            </div>
            <!--################################## FIM INFORMACOES #################################################
            ###############################################################################################-->
      </div>
      <div class="content_right">

        <!--################################## IMAGEM #################################################
        ###############################################################################################-->
        <div class="content" style="margin: 0;">
          <div class="content_tit">Imagem:</div>
              <div class="box_img">
                  <span class="deleteBox" id="deleteImagem" <?= (isset($usuario['foto']) && empty($usuario['foto']))? "style='display:none'" : "" ?>></span>
                  <img src="<?= (isset($usuario['foto']) && !empty($usuario['foto']))? "files/images/thumbs/".$usuario['foto'] : "images/semimagem.png" ?>" alt="img" id="img_imagem" name="img_imagem" />
                  <input type="hidden" name="foto" id="foto" value="<?= (isset($usuario['foto']))? $usuario['foto'] : "" ?>" tipo="<?= (isset($usuario['foto']) && !empty($usuario['foto']))? "editar" : "cadastrar" ?>">
                  <input type="hidden" name="apagarFoto" id="apagarFoto" value="">
              </div>
              <input type="file" id="updloadImagem" name="files[]" >
              <!-- The global progress bar -->
              <div id="progress" class="progress" style="display:none;">
                  <div class="progress-bar progress-bar-success"></div>
              </div>
              <br />
              <p class="pre">Tamanho mínimo recomendado: 190x142 px (ou maior proporcional)  -  Extensão recomendada: jpg, png</p>
        </div>

        <!--################################## FIM IMAGEM #################################################
        ###############################################################################################-->

      </div>


      <div class="border_content"></div>
      <div class="content contentPermissao">
        <?php if(verificaPermissaoAcesso('usuario_editar', $MODULOACESSO['usuario'])){ ?>
        <!--################################## PERMISSOES #################################################
        ###############################################################################################-->
        <div class="content_tit">Permiss&otilde;es:</div>
        <div class="box_ip">
            <div class="box_sel box_txt">
              <label for="">Selecione uma Permissão</label>
              <div class="box_sel_d">
                <select name="apelido" class="permissoes">
                  <option></option>
                    <?php
                      if(!empty($apelidos)){
                        foreach($apelidos as $k=>$v){
                        ?>
                             <option  value="<?php echo $v['tags']; ?>"><?php echo $v['apelido']; ?></option>
                            <!--<option onClick="checaPermissoes('< ?php echo $v['tags']; ?>');">< ?php echo $v['apelido']; ?></option>-->
                        <?php
                        }
                      }
                    ?>
                  </select>;
                  <input type="hidden" id="permissoes_value" value="<?php print $v['tags'] ?>"  />
              </div>
            </div>
        </div>
        <div class="box_cr">
           <p>Selecionar</p>
           <label for="selecionar-todos">
              <input type="radio" name="selecionar-todos" value="1" id="selecionar-todos">
              <span>Todos</span>
           </label>
           <label for="desselecionar-todos">
              <input type="radio" name="selecionar-todos" value="2" id="desselecionar-todos">
              <span>Nenhum</span>
           </label>
        </div>
        <?php
            if(!empty($permissoes)){

                foreach($permissoes as $modulo=>$tags){
                    if($modulo != 'email' && $modulo != 'cep' && $modulo != 'consultas'){
                        print '<div class="box_cr box_cr_'.$modulo.'"><p>M&oacute;dulo '.$modulo.'</p>';
                        foreach($tags as $tag){
                            if(verificaPermissaoAcesso($modulo.'_'.$tag, $_REQUEST['idu'])){
                                $check = 'checked="checked"';
                            }else{
                                $check = '';
                            }

                            $name = $modulo.'_'.$tag;
                            print '<label><input data-modulo="'.$modulo.'" class="selecionar-todos-cb-md" name="'.$name.'" id="'.$name.'" value="permitido" type="checkbox" '.$check.' /><span>'.$tag.'</span></label>';
                            print '&nbsp;&nbsp;&nbsp;&nbsp;';
                        }

                  print '<label>';
                  print '<input type="checkbox" name="" id="" class="selecionar-todos-cb" data-modulo="'.$modulo.'">';
                  print '<span>Todos</span>';
                  print '</label>';

                        print '</div>';
                    }
                }
        $modulo = "configuracoes";
        print '<div class="box_cr"><p>M&oacute;dulo configurações</p>';
        $check = verificaPermissaoAcesso('configuracoes_listagem_usuarios', $_REQUEST['idu']) ? 'checked="checked"' : '';
        print '<label><input name="configuracoes_listagem_usuarios" id="configuracoes_listagem_usuarios" value="permitido" type="checkbox" '.$check.' /><span>Listagem usuários</span></label>';

        $check = verificaPermissaoAcesso('configuracoes_cadastro_usuarios', $_REQUEST['idu']) ? 'checked="checked"' : '';
        print '<label><input name="configuracoes_cadastro_usuarios" id="configuracoes_cadastro_usuarios" value="permitido" type="checkbox" '.$check.' /><span>Cadastro usuários</span></label>';

        $check = verificaPermissaoAcesso('configuracoes_permissao', $_REQUEST['idu']) ? 'checked="checked"' : '';
        print '<label><input name="configuracoes_permissao" id="configuracoes_permissao"         value="permitido" type="checkbox" '.$check.' /><span>Permissão</span></label>';

        $check = verificaPermissaoAcesso('configuracoes_log', $_REQUEST['idu']) ? 'checked="checked"' : '';
        print '<label><input name="configuracoes_log"  id="configuracoes_log" value="permitido" type="checkbox" '.$check.' /><span>Log</span></label>';
        print '&nbsp;&nbsp;&nbsp;&nbsp;';
        print '</div>';
            }
        ?>
</div>



 <!--################################## FIM PERMISSOES #################################################
###############################################################################################-->
 <?php } ?>

    <input type="hidden" id="vUser" value="" readonly="readonly" />
  <input type="hidden" id='idusuario' name='idusuario' value='<?= $idusuario ?>'>
  <input class="bt_save" type="submit" value="salvar" class="salvar" />
  <input class="bt_cancel" type="button" value="cancelar" onclick="history.go(-1);" class="cancelar"  />

</form>
</div>


</div>


<?php } ?>







<?php if($_REQUEST['acao'] == "relatorioAcesso"){ ?>
<?php
      if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
          header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));

      $_SESSION['titulo_relatorio'] = "de Acessos";

?>

<div id="titulo">
  <span>Relat&oacute;rio de Acessos</span>
</div>

<div class="search">
  <form name="formbusca" method="post" action="#" onSubmit="return false;">
    <input type="text" name="buscarapida" value="Buscar" onblur="campoBuscaEscreve(this);" onfocus="campoBuscaLimpa(this);" id="buscarapida"  />
  </form>
</div>

<div id="principal" class="yui-skin-sam">

   <div id="abas">
        <ul class="abas_list">
          <li class="abas_list_li action"><a href="#">Relat&oacute;rio Usuários</a></li>
        </ul>
        <ul class="abas_bts">
          <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=relatorioacesso&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem" /></a></li>
          <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=relatorioacesso&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel" /></a></li>
        </ul>
  </div>

   <table class="table" cellspacing="0" cellpadding="0" id="listagem" name="relatorio">
        <thead>
            <tr>
<!--                <th><a href="" class="action">ID Usuário</a></th>
                <th><a href="">Nome Completo</a></th>
                <th><a href="">Data/Hora acesso</a></th>
                <th><a href="">IP</a></th> -->
            </tr>
        </thead>
        <tbody></tbody>
  </table>

  <? include_once("paginacao/paginacao.php"); ?>

  <script type="text/javascript">
        <?php
               $dados = isset($_POST) ? $_POST : array();
               $buscar = '';
               foreach($dados as $k=>$v){
                       if(!empty($v))
                               $buscar .= $k.'='.$v.'&';
               }
               if(isset($_REQUEST['idu'])){
                   $buscar .= "&idusuario=".$_REQUEST['idu'];
               }
        ?>
        dataTableRelatorioAcesso('<?php echo $buscar ?>');
        columnRelatorioAcesso();
  </script>

</div>

<div id="auxiliar" style='margin-top:20px;'>
   <p>Está listagem mostra o acesso do usuário específico, mostrando seu ID, Nome, Data/Hora do acesso e IP de onde acessou.</p>
</div> 

<?php } ?> 


<!--################################## LISTAGEM USUARIOS #######################################
###############################################################################################-->

<?php if($_REQUEST['acao'] == 'listarUsuario' || empty($_REQUEST['acao'])){ ?>
<?php
      if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
          header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));

?>

  
<div id="titulo">
  <img src="images/ico_users2.png" height="22" width="24" alt="ico" />
  <span>Listagem de Usu&aacute;rios</span>
  <ul class="other_abs">
      <li class="other_abs_li"><a href="index.php?mod=usuario&acao=listarUsuario">Listagem</a></li>
      <li class="others_abs_br"></li>
      <li class="other_abs_li"><a href="index.php?mod=usuario&acao=formUsuario&met=novaUsuario">Cadastro</a></li>
  </ul>
</div>
<div class="search">
  <form name="formbusca" method="post" action="#" onSubmit="return false;">
    <input type="text" name="buscarapida" value="Buscar" onblur="campoBuscaEscreve(this);" onfocus="campoBuscaLimpa(this);" id="buscarapida"  />
  </form>
  <a href="" class="search_bt">Busca Avançada</a>
</div>
<div class="advanced">
    <form name="formAvancado" id="formAvancado" method="post" action="#" onsubmit="return false;">
        <p class="advanced_tit">Busca Avançada</p>
        <img class="advanced_close" src="images/ico_close.png" height="10" width="11" alt="ico" />
        <div class="box_ip"><label for="nome">Nome</label><input type="text"  name="nome" id="nome"></div>
        <div class="box_ip"><label for="sobrenome">Sobrenome</label><input type="text" name="sobrenome" id="sobrenome"></div>
        <div class="box_ip"><label for="email">Email</label><input type="text" name="email" id="email"></div>
        <div class="box_sel"><label for="logado">Logado</label>
            <div class="box_sel_d">
                <select name="logado" id="logado">
                    <option></option>
                    <option value="1">Logado</option>
                    <option value="2">Deslogado</option>
                </select>
            </div>
        </div>
        <a href="" class="advanced_bt" id="filtrar">Filtrar</a>
    </form>
</div>
<div id="principal"  class="yui-skin-sam">
  <div id="abas">
    <ul class="abas_list">
      <li class="abas_list_li action"><a href="#">Usuários</a></li>
    </ul>
    <ul class="abas_bts">
      <li class="abas_bts_li"><a href="index.php?mod=usuario&acao=formUsuario&met=novaUsuario"><img src="images/novo.png" height="16" width="17" alt="Cadastrar Usuário" title="Cadastrar Usuário" /></a></li>
      <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=usuario&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem" /></a></li>
      <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=usuario&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel" /></a></li>
    </ul>
  </div>
  <table class="table" cellspacing="0" cellpadding="0" id="listagem" name="usuario">
    <thead> </thead>
    <tbody>  </tbody>
  </table>
  <?php include_once("paginacao/paginacao.php"); ?>

  <script type="text/javascript">
        dataTable('');
        columnUsuario();
  </script>
</div>


<?php } ?>
