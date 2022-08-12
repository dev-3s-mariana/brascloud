<?php 
	include_once 'permissao_class.php';

	$_SESSION['titulo_relatorio'] = "Permissões";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>

<style type="text/css">
	@import url(usuario_css.css);
</style>
<script type="text/javascript" src="permissao_js.js"></script>
 
<?php if($_REQUEST['acao'] == "formPermissao"){
 

	if($_GET["met"] == "novaPermissao"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario']))
		  header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este Módulo!'));

		$action = "permissao_script.php?opx=novaPermissao";
		$metodo_titulo = "Cadastro de Permissão";
		$_GET['idu'] = 0;

		$permissao['apelido'] = '';


	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario']))
		  header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este Módulo!'));

		$metodo_titulo = "Editar Permissão";
		$action = "permissao_script.php?opx=editPermissao&idu=$_GET[idu]";
		$resultado = buscaPermissao(array('idpermissao'=>$_GET['idu']));
		$permissao = $resultado[0];


	}
	$permissoes = getPermissoes();

?>


<div id="titulo">
	<span><?php print $metodo_titulo; ?></span>
</div>
<div id="principal"> 


<form name="form2" id="form2" method="post" action="<?php echo $action; ?>">
    <div class="content_left">
        	 <div id="informacaoUsuario" class="content">
              <div class="content_tit">Permiss&otilde;es:</div>
              		<div class="box_ip box_full">
              			<label>Apelido</label>
              			<input type="text" name="apelido" id="apelido" value="<?php echo $permissao['apelido']; ?>" size="30" maxlength="20" class='required'/>
              		</div>

              		<?php
						if(!empty($permissoes)){

							foreach($permissoes as $modulo=>$tags){
								if($modulo != 'email' && $modulo != 'cep' && $modulo != 'consultas'){
									print '<label style="margin-left: 15px;">M&oacute;dulo '.$modulo.'</label><span><br />';
									foreach($tags as $tag){
										if(verificaPermissao($modulo.'_'.$tag, $_GET['idu'])){
											$check = 'checked="checked"';
										}else{
											$check = '';
										}

										$name = $modulo.'_'.$tag;
										print '<div class="box_cr" style="float:left;clear: inherit;">';
											print '<label><input name="'.$name.'" id="'.$name.'" value="permitido" type="checkbox" '.$check.' />'.$tag."</label>";
										print '</div>';
									}
									print '</span><br/><br/>';
								}
							}
						}
					?>

			 <!--################################## FIM PERMISSOES #################################################
			###############################################################################################-->
 			<input type="hidden" name="idpermissao" value="<?php print $_GET['idu']; ?>" />
            <input type="submit" value="salvar" class="salvar bt_save" />
            <input type="button" value="cancelar" class="cancelar bt_cancel"  />


		 </div>

</form>



</div>


<?php } ?>






<?php if($_REQUEST['acao'] == "listarPermissao"){ ?>

<?php
	  if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		  header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce não tem privilegios para acessar este modulo!'));
?>


<div id="titulo">
  <span>Listagem de Permissões</span>
  <ul class="other_abs">
	    <li class="other_abs_li"><a href="index.php?mod=permissao&acao=listarPermissao">Listagem</a></li>
	    <li class="others_abs_br"></li>
	    <li class="other_abs_li"><a href="index.php?mod=permissao&acao=formPermissao&met=novaPermissao">Cadastro</a></li>
  </ul>
</div>

<div class="search">
  <form name="formbusca" method="post" action="#" onSubmit="return false;">
    <input type="text" name="buscarapida" value="Buscar" onblur="campoBuscaEscreve(this);" onfocus="campoBuscaLimpa(this);" id="buscarapida"  />
  </form>
</div>

<div id="principal"  class="yui-skin-sam">
    <div id="abas">
      <ul class="abas_list">
        <li class="abas_list_li action"><a href="#">Permissões</a></li>
      </ul>
      <ul class="abas_bts">
        <li class="abas_bts_li"><a href="index.php?mod=permissao&acao=formPermissao&met=novaPermissao"><img src="images/novo.png" height="16" width="17" alt="Cadastrar Permissão" title="Cadastrar Permissão" /></a></li>
        <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=permissao&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem" /></a></li>
        <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=permissao&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel" /></a></li>
      </ul>
    </div>
    <table class="table" cellspacing="0" cellpadding="0" id="listagem" name="permissao">
      <thead>
        <tr>
        </tr>
      </thead>
      <tbody>  </tbody>
   </table>
    <?php include_once("paginacao/paginacao.php"); ?>

    <script type="text/javascript">
          dataTablePermissao('');
          columnPermissao();
    </script>
</div>

<?php } ?>
