<?php
   // Versao do modulo: 3.00.010416

    include_once "solucoes_class.php";
    include_once "includes/functions.php";

    if (!isset($_REQUEST['acao']))
   	    $_REQUEST['acao'] = "";

    $width = 300;
    $height = 600;

    $width2 = 1920;
    $height2 = 1163;

    $width3 = 96;
    $height3 = 96;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="solucoes_css.css" />
<script type="text/javascript" src="solucoes_js.js"></script>

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


<?php if ($_REQUEST['acao'] == "formSolucoes") {
	if ($_REQUEST['met'] == "cadastroSolucoes") {
		if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "solucoes_script.php?opx=cadastroSolucoes";
		$metodo_titulo = "Cadastro Soluções";
		$idSolucoes = 0;

        $FontAwesome = false;

		// dados para os campos
		$solucoes['nome'] = "";
        $solucoes['titulo'] = "";
		$solucoes['status'] = "";
		$solucoes['urlrewrite'] = "";
        $solucoes['icone_name'] = "";
        $solucoes['icone'] = "";
        $solucoes['imagem'] = "";
        $solucoes['banner_topo'] = "";
        $solucoes['resumo'] = "";
        $solucoes['descricao'] = "";
        $solucoes_imagens = array();
	} else {
		if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "solucoes_script.php?opx=editSolucoes";
		$metodo_titulo = "Editar Soluções";
		$idSolucoes = (int) $_GET['idu'];
		$solucoes = buscaSolucoes(array('idsolucoes' => $idSolucoes));

        $recursos = buscaRecursos(array('idsolucoes'=>$idSolucoes, 'ordem'=>'ordem', 'dir'=>'asc'));
        $testes = buscaTestes(array('idsolucoes'=>$idSolucoes, 'ordem'=>'ordem', 'dir'=>'asc'));
        $diferenciais = buscaDiferenciais(array('idsolucoes'=>$idSolucoes, 'ordem'=>'ordem', 'dir'=>'asc'));

		if (count($solucoes) != 1) exit;
		$solucoes = $solucoes[0];

        $solucoes_imagens = buscaSolucoes_imagem(array("idsolucoes"=>$solucoes['idsolucoes'],"ordem"=>'posicao_imagem',"dir"=>'ASC'));

        $StringIcone = strlen($solucoes['icone']);
        if ($StringIcone > 3) {
            $FontAwesome = false;
            $solucoes['icone_name'] = '';
        } else {
            $FontAwesome = true;
            // $icones_Edit = buscaFW3(array('idfw' => $solucoes['icone']));
            // $icones_Edit = $icones_Edit[0];
        }
	}
	?>

	<div id="titulo">
		<i class='fa fa-list' aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=solucoes&acao=listarSolucoes">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=solucoes&acao=formSolucoes&met=cadastroSolucoes">Cadastro</a></li>
		</ul>
	</div>




	<div id="principal">
		<form class="form" name="formSolucoes" id="formSolucoes" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));" enctype="multipart/form-data">

			<div id="informacaoSolucoes" class="content">
				<div class="content_tit">Dados Soluções:</div>

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
                            <?php if ($_GET['met'] == 'editSolucoes') : ?>
                                <div id="mostrar_icone">
                                    <i id="current_icon" class='fas fa-<?=$solucoes['icone_name'];?> fa-2x'></i>
                                    <input type="hidden" name="icone" value="<?= $solucoes['icone']; ?>" id="imagem_icone">
                                    <input type="hidden" name="icone_name" value="<?= $solucoes['icone_name']; ?>" id="icone_name">
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
                            <?php $caminho = "files/solucoes/"; ?>
                            <div class="content_tit">Ícone</div>

                            <div class="botaoArquivo" id="inputArquivoBotao">
                                <input class="btn" type="button" value="Anexar Ícone">
                                <!-- <i class="fas fa-paperclip" aria-hidden="true"></i> -->
                            </div>

                            <img class="pump" src="<?= $caminho . $solucoes['icone']; ?>" width='<?=$width3?>' id="icone" style="display: <?= $_GET['met'] == 'editSolucoes' ? (!empty($solucoes['icone'] && !$FontAwesome) ? 'block' : 'none') : 'none'; ?>">
                            <p class="pre">Tamanho recomendado: <?=$width3?>x<?=$height3?>px (ou maior proporcional) - Extensão recomendada: jpg, png</p>
                            <span>O arquivo não pode ser maior que: <?=$tamanho?></span>
                            <input type="file" name="icone_upload" id="icone_upload" class="all_imagens" data-tipo='1'>
                            <input type="hidden" id="imagem_value">
                            <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                        </div>
                    </div>
                <!-- ========== Fim Upload Icone ========== -->

				<div class="box_ip">
					<label for="nome">Nome</label>
					<input type="text" class="" name="nome" id="nome" value="<?php echo $solucoes['nome']; ?>" />
				</div>
				<div class="box_ip">
					<label for="urlrewrite">Url</label>
					<input type="text" name="urlrewrite" class="" id="urlrewrite" value="<?php echo $solucoes['urlrewrite']; ?>" />
				</div>

                <div class="box_ip">
                    <label for="titulo_solucao">Título</label>
                    <input type="text" class="" name="titulo" id="titulo_solucao" value="<?php echo $solucoes['titulo']; ?>" />
                </div>

                <!-- <div class="box_ip box_txt">
                   <label for="resumo">Resumo</label>
                   <textarea name="resumo" id="resumo" class=""><?php echo $solucoes['resumo']; ?></textarea>
                </div> -->

                <div class="box_ip box_txt">
                   <label for="descricao">Descrição</label>
                   <textarea name="descricao" id="descricao" class=""><?php echo $solucoes['descricao']; ?></textarea>
                </div>

    			<div class="box_ip">
    				<label for="status">Status</label>
    				<div class="box_sel box_txt">
    					<label for>Status</label>
    					<div class="box_sel_d">
    						<select name="status" id="status">
    							<option value="1" <?=(($solucoes['status'] == '1') ? 'SELECTED' : '')?>>Ativo</option>
                                <option value="0" <?=(($solucoes['status'] == '0') ? 'SELECTED' : '')?>>Inativo</option>
    						</select>
    					</div>
    				</div>
    			</div>

                <!-- CROPPER IMG -->
                <?php $caminho = 'files/solucoes/'; ?>
                <!-- <div id="select-image-1" class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Imagem</div>
                                <div class="box_ip imagem-atual" style="<?=empty($solucoes['imagem'])?'display: none;':''?>">
                                    <a data-tipo="imagem" data-img="<?=$solucoes['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img width="120" src="<?=empty($solucoes['imagem'])?'images/cliente/logo.png':$caminho.$solucoes['imagem']?>" class="img-solucoes-form" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <input id="inputImage" class="cropped-image" name="imagemCadastrar2" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width?>x<?=$height?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que:
                                        <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div id="select-image-2" class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Banner</div>
                                <div class="box_ip imagem-atual" style="<?=empty($solucoes['banner_topo'])?'display: none;':''?>">
                                    <a data-tipo="banner_topo" data-img="<?=$solucoes['banner_topo']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img width="120" src="<?=empty($solucoes['banner_topo'])?'images/cliente/logo.png':$caminho.$solucoes['banner_topo']?>" class="img-solucoes-form" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <input id="inputImage2" class="cropped-image" name="imagemCadastrar" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width2?>x<?=$height2?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que:
                                        <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize2" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div id="cropper-modal">
                    <!-- /.docs-buttons -->
                    <div class="img-container" id="img-container">
                        <img alt="">
                    </div>
                    <!-- Show the cropped image in modal -->
                    <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button class="close" data-dismiss="modal" type="button" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div><!-- /.modal -->
                    <div class="div-save-cropped-image">
                        <input data-image-type='' type="button" value="Salvar" class="save-cropped-image">
                    </div>
                </div>

            <!-- =======================Recursos========================== -->
                <div class="listaRecursos box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Características</div>
                        <a class="btn btn-recursos"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridRecursos">
                        <table class="table" id="tableRecursos">
                            <thead>
                                <tr>
                                    <th align="center">Imagem/Ícone</th>
                                    <th align="center">Descrição</th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="recursos">
                                <?php if(isset($recursos) && !empty($recursos)):?>
                                    <?php foreach($recursos as $key => $rec):?>
                                        <tr class="box-recursos removeRecursos-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-recursos-<?=$key;?>" data-key="<?=$key;?>" data-grid="recursos" />
                                                <?php else:?>
                                                    <img src="files/recursos/<?=$rec['imagem'];?>" width="50"  class="img-upload img-recursos-<?=$key;?>" data-key="<?=$key;?>" data-grid="recursos" />
                                                <?php endif;?>

                                                <input type="file" name="recursos[<?=$key;?>][imagem]" class="file-upload upload-recursos-<?=$key;?>" data-key="<?=$key;?>" data-grid="recursos">
                                                <span class="fs-11">Tamanho recomendado 81x81px </span>
                                                <input type="hidden" class="nome-img-cadastrada" name="recursos[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <br/><span><b>OU</b></span>

                                                <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-recursos-<?=$key?>" data-grid="recursos" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i>
                                                    <input type="hidden" name="recursos[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-recursos-<?=$key;?>">
                                                    <input type="hidden" name="recursos[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-recursos-<?=$key;?>">
                                                </div>
                                                <input type="button" value="Escolher ícone" data-grid="recursos" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>">

                                                <input type="hidden" name="recursos[<?=$key;?>][idrecursos]" value="<?=$rec['idrecursos'];?>">
                                                <input id='excluirRecursoRecursos-<?=$key;?>' type="hidden" name="recursos[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputRecursos w-100" name="recursos[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputRecursos w-100" name="recursos[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea>
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirRecursos" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerRecursos" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirRecursos" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="recursos[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeRecursos-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="recursos" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Recursos========================== -->

            <!-- =======================Testes========================== -->
                <div class="listaTestes box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Aplicações</div>
                        <a class="btn btn-testes"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridTestes">
                        <table class="table" id="tableTestes">
                            <thead>
                                <tr>
                                    <th align="center"></th>
                                    <th align="center">Descrição</th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="testes">
                                <?php if(isset($testes) && !empty($testes)):?>
                                    <?php foreach($testes as $key => $rec):?>
                                        <tr class="box-testes removeTestes-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <!-- <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-testes-<?=$key;?>" data-key="<?=$key;?>" data-grid="testes"/> -->
                                                <?php else:?>
                                                    <!-- <img src="files/testes/<?=$rec['imagem'];?>" width="50"  class="img-upload img-testes-<?=$key;?>" data-key="<?=$key;?>" data-grid="testes"/> -->
                                                <?php endif;?>

                                                <!-- <input type="file" name="testes[<?=$key;?>][imagem]" class="file-upload upload-testes-<?=$key;?>" data-key="<?=$key;?>" data-grid="testes"> -->
                                                <!-- <span class="fs-11">Tamanho recomendado 50x50px </span> -->
                                                <input type="hidden" class="nome-img-cadastrada" name="testes[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <!-- <br/><span><b>OU</b></span> -->

                                                <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-testes-<?=$key?>" data-grid="testes" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i>
                                                    <input type="hidden" name="testes[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-testes-<?=$key;?>">
                                                    <input type="hidden" name="testes[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-testes-<?=$key;?>">
                                                </div>
                                                <!-- <input type="button" value="Escolher ícone" data-grid="testes" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>"> -->

                                                <input type="hidden" name="testes[<?=$key;?>][idtestes]" value="<?=$rec['idtestes'];?>">
                                                <input id='excluirRecursoTestes-<?=$key;?>' type="hidden" name="testes[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputTestes w-100" name="testes[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputTestes w-100" name="testes[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea>
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirTestes" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerTestes" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirTestes" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="testes[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeTestes-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="testes" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Testes========================== -->

            <!-- =======================Diferenciais========================== -->
                <div class="listaDiferenciais box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Diferenciais</div>
                        <a class="btn btn-diferenciais"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridDiferenciais">
                        <table class="table" id="tableDiferenciais">
                            <thead>
                                <tr>
                                    <th align="center"></th>
                                    <th align="center">Descrição</th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="diferenciais">
                                <?php if(isset($diferenciais) && !empty($diferenciais)):?>
                                    <?php foreach($diferenciais as $key => $rec):?>
                                        <tr class="box-diferenciais removeDiferenciais-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <!-- <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-diferenciais-<?=$key;?>" data-key="<?=$key;?>" data-grid="diferenciais" /> -->
                                                <?php else:?>
                                                    <!-- <img src="files/diferenciais/<?=$rec['imagem'];?>" width="50"  class="img-upload img-diferenciais-<?=$key;?>" data-key="<?=$key;?>" data-grid="diferenciais" /> -->
                                                <?php endif;?>

                                                <!-- <input type="file" name="diferenciais[<?=$key;?>][imagem]" class="file-upload upload-diferenciais-<?=$key;?>" data-key="<?=$key;?>" data-grid="diferenciais"> -->
                                                <!-- <span class="fs-11">Tamanho recomendado 50x50px </span> -->
                                                <input type="hidden" class="nome-img-cadastrada" name="diferenciais[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <!-- <br/><span><b>OU</b></span> -->

                                                <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-diferenciais-<?=$key?>" data-grid="diferenciais" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i>
                                                    <input type="hidden" name="diferenciais[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-diferenciais-<?=$key;?>">
                                                    <input type="hidden" name="diferenciais[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-diferenciais-<?=$key;?>">
                                                </div>
                                                <!-- <input type="button" value="Escolher ícone" data-grid="diferenciais" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>"> -->

                                                <input type="hidden" name="diferenciais[<?=$key;?>][iddiferenciais]" value="<?=$rec['iddiferenciais'];?>">
                                                <input id='excluirRecursoDiferenciais-<?=$key;?>' type="hidden" name="diferenciais[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputDiferenciais w-100" name="diferenciais[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <!-- <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputDiferenciais w-100" name="diferenciais[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea> -->
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirDiferenciais" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerDiferenciais" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirDiferenciais" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="diferenciais[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeDiferenciais-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="diferenciais" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Diferenciais========================== -->

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

            <input type="hidden" id="mod" name="mod" value="<?= ($idSolucoes == 0)? "cadastro":"editar"; ?>" />
			<input type="hidden" name="idsolucoes" id="idsolucoes" value="<?php echo $idSolucoes; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
            <input type='hidden' name='aspectRatioW2' id='aspectRatioW2' value='<?=$width2?>'>
            <input type='hidden' name='aspectRatioH2' id='aspectRatioH2' value='<?=$height2?>'>
            <input type='hidden' name='imagem' id='imagem-value' value='<?=$solucoes['imagem']?>'>
            <input type='hidden' name='banner_topo' id='imagem_2-value' value='<?=$solucoes['banner_topo']?>'>
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


<?php if ($_REQUEST['acao'] == "listarSolucoes") { ?><?php
if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_visualizar', $MODULOACESSO['usuario']))
	header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
<div id="titulo">
	<i class='fa fa-list' aria-hidden="true"></i>
	<span>Listagem de Soluções</span>
	<ul class="other_abs">
		<li class="other_abs_li"><a href="index.php?mod=solucoes&acao=listarSolucoes">Listagem</a></li>
		<li class="others_abs_br"></li>
		<li class="other_abs_li"><a href="index.php?mod=solucoes&acao=formSolucoes&met=cadastroSolucoes">Cadastro</a></li>
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
			<li class="abas_list_li action"><a href="javascript:void(0)">Soluções</a></li>
		</ul>
		<ul class="abas_bts">
			<li class="abas_bts_li"><a href="index.php?mod=solucoes&acao=formSolucoes&met=cadastroSolucoes"><img src="images/novo.png" alt="Cadastro Soluções" title="Cadastrar Soluções" /></a></li>
			<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=solucoes&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
			<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=solucoes&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"></a></li>
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
		requestInicio = "tipoMod=solucoes&p=" + preventCache + "&";
		ordem = "idsolucoes";
		dir = "desc";
		$(document).ready(function() {
			preTableSolucoes();
		});
		dataTableSolucoes('<?php print $buscar; ?>');
		columnSolucoes();
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