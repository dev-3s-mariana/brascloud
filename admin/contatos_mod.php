<?php 
	 // Versao do modulo: 3.00.010416

	include_once "contatos_class.php";
 
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = ""; 
	 
?>
<link rel="stylesheet" type="text/css" href="contatos_css.css" />
<script type="text/javascript" src="contatos_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formContatos"){
	if($_REQUEST['met'] == "cadastroContatos"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "contatos_script.php?opx=cadastroContatos";
		$metodo_titulo = "Cadastro Contatos";
		$idContatos = 0 ;

		// dados para os campos
		$contatos['nome'] = "";
		$contatos['email'] = "";
		$contatos['telefone'] = "";
		$contatos['assunto'] = "";
        $contatos['total_pedido'] = "";
		$contatos['mensagem'] = "";
        $contatos['resumo_pedido'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "contatos_script.php?opx=editContatos";
		$metodo_titulo = "Editar Contatos";
		$idContatos = (int) $_GET['idu'];
		$contatos = buscaContatos(array('idcontatos'=>$idContatos));
		if (count($contatos) != 1) exit;
		$contatos = $contatos[0];
	}

?>

	<div id="titulo">
		<i class="fas fa-user"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=contatos&acao=listarContatos">Listagem</a></li>
 		</ul>
	</div>  

	<div id="principal">
		<form class="form" name="formContatos" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome')); " >

			<div id="informacaoContatos" class="content">
				<div class="content_tit">Dados Contatos: </div>
				<div class='emails_contato'>
					<div class="box_ip">
						<label for="nome">Nome</label>
						<input type="text" name="nome" id="nome" size="30" maxlength="100" value="<?php echo $contatos['nome']; ?>"/>
					</div>
					 			
					<div class="box_ip">
						<label for="email">E-mail</label>
						<input type="text" name="email" id="email" size="30" maxlength="100" value="<?php echo $contatos['email']; ?>" />
					</div>  
					 
					<div class="box_ip">
						<label for="telefone">Telefone</label>
						<input type="text" name="telefone" id="telefone" class="phone_br" value="<?php echo $contatos['telefone']; ?>"/>
					</div> 

					<div class="box_ip">
						<label for="assunto">Assunto</label>
						<input type="text" name="assunto" id="assunto" size="30" value="<?php echo $contatos['assunto']; ?>"/>
					</div> 

                    <?php if(!empty($contatos['resumo_pedido'])):?>
                        <?php $arrResumoPedido = explode(';', $contatos['resumo_pedido']);?>
                        <div class="box_ip box_txt">
                            <div class="content_tit">Resumo pedido: </div>
                            <table id="resumo-pedido">
                                <tr>
                                    <th>Nome</th>
                                    <th>Velocidade</th>
                                    <th>Preço/h</th>
                                </tr>
                                <?php foreach($arrResumoPedido as $key => $arp):?>
                                    <?php $arrPedido = explode('--', $arp);?>
                                    <tr>
                                        <td><?=$arrPedido[0]?></td>
                                        <td><?=$arrPedido[1]?></td>
                                        <td><?=$arrPedido[2]?></td>
                                    </tr>
                                <?php endforeach;?>
                            </table>
                        </div> 
                        <div class="box_ip">
                            <label for="total_pedido">Total</label>
                            <input type="text" name="total_pedido" id="total_pedido" size="30" value="<?php echo $contatos['total_pedido']; ?>"/>
                        </div> 
                    <?php endif;?>
                
					<div class="box_ip" style='width:100%'>
						<span style='color:#333;font-family:"RobotoM"'>Mensagem</span><br/>
						<textarea name="mensagem" id="mensagem"><?php echo $contatos['mensagem']; ?></textarea>
					</div> 
					 
				<div class="box_ip" style='width:100%'>
					<span style='color:#333;font-family:"RobotoM"'>Observações</span><br/>
					<textarea name="observacao" id="observacao"><?php echo $contatos['observacao']; ?></textarea>
				</div> 
			</div> 

			<input type="hidden" name="idcontatos" id="idcontatos" value="<?php echo $idContatos; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
		
		</form>
	</div>

<?php } ?>



<!--************************************
     _       _        _        _     _
    | |     | |      | |      | |   | |
  __| | __ _| |_ __ _| |_ __ _| |__ | | ___
 / _` |/ _` | __/ _` | __/ _` | '_ \| |/ _ \
| (_| | (_| | || (_| | || (_| | |_) | |  __/
 \__,_|\__,_|\__\__,_|\__\__,_|_.__/|_|\___|
					*******************************-->


<?php if($_REQUEST['acao'] == "listarContatos"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
 
?>
	<div id="titulo">
		<i class="fas fa-user"></i>
		<span>Listagem de Contatos</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=contatos&acao=listarContatos">Listagem</a></li>
	 	</ul>
	</div>
	<div class="search">
		<form name="formbusca" method="post" action="#" onsubmit="return false">
			<input type="text" name="buscarapida" value="Buscar" onblur="campoBuscaEscreve(this);" onfocus="campoBuscaLimpa(this);" id="buscarapida" />
		</form>
		<a href="" class="search_bt">Busca Avançada</a>
	</div>
	<div class="advanced">
		<form name="formAvancado" id="formAvancado" method="post" action="#" onsubmit="return false">
			<p class="advanced_tit">Busca Avançada</p>
			<img class="advanced_close" src="images/ico_close.png" height="10" width="11" alt="ico" />
			<div class="box_ip"><label for="adv_nome">Nome</label><input type="text" name="nome" id="adv_nome"></div>
			<div class="box_ip"><label for="adv_email">E-mail</label><input type="text" name="email" id="adv_email"></div>
			<div class="box_ip"><label for="adv_telefone">Telefone</label><input type="text" name="telefone" id="adv_telefone" class="tel"></div>
			<div class="box_ip"><label for="adv_assunto">Assunto</label><input type="text" name="assunto" id="adv_assunto"></div>
			<div class="box_ip"><label for="adv_mensagem">Mensagem</label><input type="text" name="mensagem" id="adv_mensagem"></div>
			
			<!-- <div class="box_ip sel">
				<label for="idioma">Idioma</label> 
				<div class="box_sel" style='width:98%;margin:0;'>
					<label for="">Idioma</label>
					<div class="box_sel_d">
						<select name="ididiomas" id="ididiomas" class=''>
							<option></option> -->
							<?/* foreach($idiomas as $k => $v){ ?>
									<option value='<?= $v['ididiomas'] ?>'><?= $v['idioma'] ?></option>	
							<? } */?>
						<!-- </select>
					</div>
				</div>
			</div> -->
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>
	

	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Contatos</a></li>
			</ul>
			<ul class="abas_bts">
				 <li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=contatos&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=contatos&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
			</ul>
		</div>
		<table class="table" cellspacing="0" cellpadding="0" id="listagem">
			<thead>
			</thead>
			<tbody>
			</tbody>
		</table>
		<?php include_once("paginacao/paginacao.php"); ?> 




		<?php
			$dados = isset($_POST) ? $_POST : array();
			$buscar = '';
			foreach($dados as $k=>$v){
				if(!empty($v))
					$buscar .= $k.'='.$v.'&';
			}
		?>


		<script type="text/javascript">
			queryDataTable = '<?php print $buscar; ?>';
			requestInicio = "tipoMod=contatos&p="+preventCache+"&";
			ordem = "C.idcontatos";
			dir = "desc";
			$(document).ready(function(){
				preTableContatos();
			});
			dataTableContatos('<?php print $buscar; ?>');
			columnContatos();
		</script>
	</div>

<?php } ?>

