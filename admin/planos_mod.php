<?php
   // Versao do modulo: 3.00.010416

    include_once "planos_class.php";
    include_once "includes/functions.php";

    if (!isset($_REQUEST['acao']))
   	    $_REQUEST['acao'] = "";

    $width = 300;
    $height = 600;

    $width2 = 1920;
    $height2 = 1080;

    $width3 = 70;
    $height3 = 70;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="planos_css.css" />
<script type="text/javascript" src="planos_js.js"></script>

<!-- CROPPER-->
<link href="css/cropper-padrao.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">

<script src="js/bootstrap.min.js"></script>
<script src="js/cropper.js"></script>
<script src="js/main.js"></script>
<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if ($_REQUEST['acao'] == "formPlanos") {
	if ($_REQUEST['met'] == "cadastroPlanos") {
		if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "planos_script.php?opx=cadastroPlanos";
		$metodo_titulo = "Cadastro Planos";
		$idPlanos = 0;

        $FontAwesome = false;

		// dados para os campos
		$planos['nome'] = "";
		$planos['status'] = "";
		$planos['urlrewrite'] = "";
        $planos['icone_name'] = "";
        $planos['icone'] = "";
        $planos['imagem'] = "";
        $planos['banner_topo'] = "";
        $planos['resumo'] = "";
        $planos['descricao'] = "";

        $planos['velocidade'] = "";
        $planos['vcpu'] = "";
        $planos['memoria'] = "";
        $planos['iops'] = "";
        $planos['disco'] = "";
        $planos['preco_hora'] = "";
        $planos['preco_mes'] = "";
        $planos['zona_sp01'] = "0";
        $planos['zona_sp02'] = "0";
        $planos['zona_rs01'] = "0";
        $planos['windows'] = "0";
        $planos['linux'] = "0";

        $planos_imagens = array();
	} else {
		if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "planos_script.php?opx=editPlanos";
		$metodo_titulo = "Editar Planos";
		$idPlanos = (int) $_GET['idu'];
		$planos = buscaPlanos(array('idplanos' => $idPlanos));

        $itns = buscaItns(array('idplanos'=>$idPlanos, 'ordem'=>'ordem', 'dir'=>'asc'));

		if (count($planos) != 1) exit;
		$planos = $planos[0];

        $StringIcone = strlen($planos['icone']);
        if ($StringIcone > 3) {
            $FontAwesome = false;
            $planos['icone_name'] = '';
        } else {
            $FontAwesome = true;
            // $icones_Edit = buscaFW3(array('idfw' => $planos['icone']));
            // $icones_Edit = $icones_Edit[0];
        }
	}
	?>

	<div id="titulo">
		<i class='fa fa-list' aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=planos&acao=listarPlanos">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=planos&acao=formPlanos&met=cadastroPlanos">Cadastro</a></li>
		</ul>
	</div>




	<div id="principal">
		<form class="form" name="formPlanos" id="formPlanos" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));" enctype="multipart/form-data">

			<div id="informacaoPlanos" class="content">
				<div class="content_tit">Dados Planos:</div>

                <!-- ========== Upload Icone ========== -->
                    <div class="box_ip box_txt">
                        <ul class="tabs">
                            <li class="tab-link <?= @$FontAwesome ? 'current' : ''; ?> btn-choose-icon" data-tab="tab-1-not-grid">Escolher um Ícone</li>
                            <li class="tab-link <?= !@$FontAwesome ? 'current' : ''; ?>" data-tab="tab-2">Anexar um Ícone</li>
                        </ul>
                        <div id="tab-1-not-grid" class="box_ip box_txt tab-content <?= $FontAwesome ? 'current' : ''; ?>">
                            <span id="icone-titulo" class='labeltxt' for="pesquisar_icone">
                                <strong>Ícone</strong>
                            </span>
                            <?php if ($_GET['met'] == 'editPlanos') : ?>
                                <div id="mostrar_icone">
                                    <i id="current_icon" class='fas fa-<?=$planos['icone_name'];?> fa-2x'></i>
                                    <input type="hidden" name="icone" value="<?= $planos['icone']; ?>" id="imagem_icone">
                                    <input type="hidden" name="icone_name" value="<?= $planos['icone_name']; ?>" id="icone_name">
                                </div>
                            <?php else : ?>
                                <div id="mostrar_icone">
                                    <i id="current_icon" class=''></i>
                                    <input type="hidden" name="icone" id="imagem_icone">
                                    <input type="hidden" name="icone_name" id="icone_name">
                                </div>
                            <?php endif; ?>

                        </div>
                        <div id="tab-2" class="tab-content <?= !$FontAwesome ? 'current' : ''; ?>">
                            <?php $caminho = "files/planos/"; ?>
                            <div class="content_tit">Ícone</div>

                            <div class="botaoArquivo" id="inputArquivoBotao">
                                <input class="btn" type="button" value="Anexar Ícone">
                                <!-- <i class="fas fa-paperclip" aria-hidden="true"></i> -->
                            </div>

                            <img class="pump" src="<?= $caminho . $planos['icone']; ?>" width='<?=$width3?>' id="icone" style="display: <?= $_GET['met'] == 'editPlanos' ? (!empty($planos['icone'] && !$FontAwesome) ? 'block' : 'none') : 'none'; ?>">
                            <p class="pre">Tamanho recomendado: <?=$width3?>x<?=$height3?>px (ou maior proporcional) - Extensão recomendada: jpg, png</p>
                            <span>O arquivo não pode ser maior que: <?=$tamanho?></span>
                            <input type="file" name="icone_upload" id="icone_upload" class="all_imagens" data-tipo='1'>
                            <input type="hidden" id="imagem_value">
                            <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                        </div>
                    </div>
                <!-- ========== Fim Upload Icone ========== -->

				<div class="box_ip">
					<label for="nome">Título</label>
					<input type="text" class="" name="nome" id="nome" value="<?php echo $planos['nome']; ?>" />
				</div>

                <div class="box_ip">
                    <label for="velocidade">Velocidade</label>
                    <input type="text" class="" name="velocidade" id="velocidade" value="<?php echo $planos['velocidade']; ?>" />
                </div>

                <div class="box_ip">
                    <label for="vcpu">VCPU</label>
                    <input type="text" class="" name="vcpu" id="vcpu" value="<?php echo $planos['vcpu']; ?>" />
                </div>

                <div class="box_ip">
                    <label for="memoria">Memória</label>
                    <input type="text" class="" name="memoria" id="memoria" value="<?php echo $planos['memoria']; ?>" />
                </div>

                <div class="box_ip">
                    <label for="iops">IOPS</label>
                    <input type="text" class="" name="iops" id="iops" value="<?php echo $planos['iops']; ?>" />
                </div>

                <div class="box_ip">
                    <label for="disco">Disco</label>
                    <input type="text" class="" name="disco" id="disco" value="<?php echo $planos['disco']; ?>" />
                </div>

                <div class="box_ip">
                    <label for="preco_hora">Preço/Hora</label>
                    <input type="text" class="money" name="preco_hora" id="preco_hora" value="<?php echo $planos['preco_hora']; ?>" />
                </div>

                <div class="box_ip">
                    <label for="preco_mes">Preço/Mês</label>
                    <input type="text" class="money" name="preco_mes" id="preco_mes" value="<?php echo $planos['preco_mes']; ?>" />
                </div>

                <div class="box_cr">
                    <label>
                        <input name="zona_sp01" id="zona_sp01" value="1" type="checkbox" <?=$planos['zona_sp01'] == 1?'checked':''?>>
                        <span>Zona SP01</span>
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

                <div class="box_cr">
                    <label>
                        <input name="zona_sp02" id="zona_sp02" value="1" type="checkbox" <?=$planos['zona_sp02'] == 1?'checked':''?>>
                        <span>Zona SP02</span>
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

                <div class="box_cr">
                    <label>
                        <input name="zona_rs01" id="zona_rs01" value="1" type="checkbox" <?=$planos['zona_rs01'] == 1?'checked':''?>>
                        <span>Zona RS01</span>
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

                <div class="box_cr">
                    <label>
                        <input name="windows" id="windows" value="1" type="checkbox" <?=$planos['windows'] == 1?'checked':''?>>
                        <span>Windows</span>
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

                <div class="box_cr">
                    <label>
                        <input name="linux" id="linux" value="1" type="checkbox" <?=$planos['linux'] == 1?'checked':''?>>
                        <span>Linux</span>
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                </div>

    			<div class="box_ip">
    				<label for="status">Status</label>
    				<div class="box_sel box_txt">
    					<label for>Status</label>
    					<div class="box_sel_d">
    						<select name="status" id="status">
    							<option value="1" <?=(($planos['status'] == '1') ? 'SELECTED' : '')?>>Ativo</option>
                                <option value="0" <?=(($planos['status'] == '0') ? 'SELECTED' : '')?>>Inativo</option>
    						</select>
    					</div>
    				</div>
    			</div>

            <!-- =======================Itns========================== -->
                <div class="listaItns box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Itens</div>
                        <a class="btn btn-itns"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridItns">
                        <table class="table" id="tableItns">
                            <thead>
                                <tr>
                                    <th align="center">Imagem/Ícone</th>
                                    <th></th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="itns">
                                <?php if(isset($itns) && !empty($itns)):?>
                                    <?php foreach($itns as $key => $rec):?>
                                        <tr class="box-itns removeItns-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <img src="https://via.placeholder.com/30?text=Upload+Foto" width="30"  class="img-upload img-<?=$key;?>" data-key="<?=$key;?>" />
                                                <?php else:?>
                                                    <img src="files/itns/<?=$rec['imagem'];?>" width="30"  class="img-upload img-<?=$key;?>" data-key="<?=$key;?>" />
                                                <?php endif;?>

                                                <input type="file" name="itns[<?=$key;?>][imagem]" class="file-upload upload-<?=$key;?>" data-key="<?=$key;?>" data-grid="itns">
                                                <span class="fs-11">Tamanho recomendado 30x30px </span>
                                                <input type="hidden" class="nome-img-cadastrada" name="itns[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <br/><span><b>OU</b></span>

                                                <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-itns-<?=$key?>" data-grid="itns" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i>
                                                    <input type="hidden" name="itns[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-itns-<?=$key;?>">
                                                    <input type="hidden" name="itns[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-itns-<?=$key;?>">
                                                </div>
                                                <input type="button" value="Escolher ícone" data-grid="itns" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>">

                                                <input type="hidden" name="itns[<?=$key;?>][iditns]" value="<?=$rec['iditns'];?>">
                                                <input id='excluirRecurso-<?=$key;?>' type="hidden" name="itns[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputItns w-100" name="itns[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <!-- <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputItns w-100" name="itns[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea> -->
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirItns" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerItns" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirItns" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="itns[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeItns-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="itns" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Itns========================== -->

            <div class="div-aux" hidden></div>

            <!-- ====== Ícones ===== -->
                <div id="box_icons" class="box_ip box_txt">
                   <div id="tab-1" class="tab-content-grid current">
                      <input type="text" name="pesquisar_icone" id="pesquisar_icone" placeholder="Pesquise um icone">
                      <div id="icone_pai">
                      </div>
                      <div id="div-page-icon">
                      </div>
                   </div>
                </div>
    
                <div id="box_icons-not-grid" class="box_ip box_txt">
                    <input type="text" name="pesquisar_icone" id="pesquisar_icone-not-grid" placeholder="Pesquise um icone">
                    <div id="icone_pai-not-grid"></div>
                    <div id="div-page-icon-not-grid"></div>
                </div>
            <!-- ===== Fim ícones ===== -->

			</div>

            <input type="hidden" id="mod" name="mod" value="<?= ($idPlanos == 0)? "cadastro":"editar"; ?>" />
			<input type="hidden" name="idplanos" id="idplanos" value="<?php echo $idPlanos; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
            <input type='hidden' name='aspectRatioW2' id='aspectRatioW2' value='<?=$width2?>'>
            <input type='hidden' name='aspectRatioH2' id='aspectRatioH2' value='<?=$height2?>'>
            <input type='hidden' name='imagem' id='imagem-value' value='<?=$planos['imagem']?>'>
            <input type='hidden' name='banner_topo' id='imagem_2-value' value='<?=$planos['banner_topo']?>'>
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


<?php if ($_REQUEST['acao'] == "listarPlanos") { ?><?php
if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_visualizar', $MODULOACESSO['usuario']))
	header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
<div id="titulo">
	<i class='fa fa-list' aria-hidden="true"></i>
	<span>Listagem de Planos</span>
	<ul class="other_abs">
		<li class="other_abs_li"><a href="index.php?mod=planos&acao=listarPlanos">Listagem</a></li>
		<li class="others_abs_br"></li>
		<li class="other_abs_li"><a href="index.php?mod=planos&acao=formPlanos&met=cadastroPlanos">Cadastro</a></li>
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
		<div class="box_ip"><label for="adv_status">Status</label><input type="text" name="status" id="adv_status"></div>
		<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
	</form>
</div>




<div id="principal">
	<div id="abas">
		<ul class="abas_list">
			<li class="abas_list_li action"><a href="javascript:void(0)">Planos</a></li>
		</ul>
		<ul class="abas_bts">
			<li class="abas_bts_li"><a href="index.php?mod=planos&acao=formPlanos&met=cadastroPlanos"><img src="images/novo.png" alt="Cadastro Planos" title="Cadastrar Planos" /></a></li>
			<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=planos&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
			<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=planos&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"></a></li>
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
		foreach ($dados as $k => $v) {
			if (!empty($v))
				$buscar .= $k . '=' . $v . '&';
		}
		?>


	<script type="text/javascript">
		queryDataTable = '<?php print $buscar; ?>';
		requestInicio = "tipoMod=planos&p=" + preventCache + "&";
		ordem = "idplanos";
		dir = "desc";
		$(document).ready(function() {
			preTablePlanos();
		});
		dataTablePlanos('<?php print $buscar; ?>');
		columnPlanos();
	</script>




</div>

<?php } ?>


<!--/////////////////////////////////////////////////////////-->
<!--////////////// FORMULARIOS PARA A GALERIA ////////////////-->
<!--////////////////////////////////////////////////////////-->

<!--data dialog descrição-->
<div id="boxDescricao" style="display:none;">                                                   
    <div id="principal">
        <form class="form" name="formDescricaoImagem" id="formDescricaoImagem" method="post" action="">
            <div id="informacaoGaleria" class="content">                                
                <div class="content_tit"></div>     
                <div class="box_ip" >
                    <label  for="descricao_imagem">Descrição</label>
                    <input type="text" name="descricao_imagem" id="descricao_imagem"   />
                    <input type="hidden" id="idImagem" value="" /> 
                    <input type="hidden" id="posImagem" value="" />
                </div>
                <input type="submit" value="Salvar" class="btSaveDescricao button" />
            </div>
        </form>
    </div>
</div>  
<!--Fim dialog descrição--> 

<!--data dialog exclusão de imagem-->
<div id="excluirImagem" style="display:none;">                                                  
    <div id="principal">
        <form class="form" name="formDeleteImagem" id="formDeleteImagem" method="post" action="">
            <div id="informacaoGaleria" class="content">                                
                <div class="content_tit"></div>  
                <input type="hidden" id="idPosicao" value="" />  
                <input type="button" value="NÃO" id="cancelar" class="btCancelarExclusao button cancel" />                              
                <input type="submit" value="SIM" class="btExcluirImagem button"/>
            </div>
        </form>
    </div>
</div>  
<input type="hidden" value="<?=ENDERECO?>" name="_endereco" id="_endereco" />
<!--Fim dialog exclusão de imagem-->

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>