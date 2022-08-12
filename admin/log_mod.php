<?php
	include_once("includes/verifica.php");
	include_once("includes/basic.php");
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>

<style type="text/css">
	@import url(usuario_css.css);
</style>
<script type="text/javascript" src="log_js.js"></script>

  

<?php if($_REQUEST['acao'] == 'listarLog' || empty($_REQUEST['acao'])){ ?>   



<div id="titulo"> 
  <span>Log</span> 
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
                <div class="box_ip"><label for="nome">Usuário</label><input type="text"  name="nome" id="nome"></div>
                <div class="box_ip"><label for="nomemodulo">Módulo</label><input type="text" name="nomemodulo" id="nomemodulo"></div> 
                <div class="box_ip"><label for="data_inicio">Início</label><input type="text" name="data_inicio" id="data_inicio"></div>
                <div class="box_ip"><label for="data_fim">Fim</label><input type="text" name="data_fim" id="data_fim"></div>
                 <div class="box_ip"><label for="descricao">Descrição</label><input type="text" name="descricao" id="descricao"></div> 
                <a href="" class="advanced_bt" id="filtrar">Filtrar</a>
    </form>   
</div>
<div id="principal"  class="yui-skin-sam">
    <div id="abas">
      <ul class="abas_list">
        <li class="abas_list_li action"><a href="#">Log</a></li>
      </ul>
      <ul class="abas_bts">
              <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=log&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem" /></a></li>
            <li class="abas_bts_li"><a href="#" onClick="popUp('relatorio_class.php?modulo=log&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel" /></a></li>
      </ul>
    </div> 
    <table class="table" cellspacing="0" cellpadding="0" id="listagem" name="permissao">
      <thead>
        <tr> </tr>
      </thead>
      <tbody>  </tbody> 
   </table>   
    <?php include_once("paginacao/paginacao.php"); ?> 

    <script type="text/javascript">
          dataTableLog();
          columnLog();
    </script>
</div>




   
<?php } ?> 