<?php
   // Versao do modulo: 3.00.010416

    include_once "landing_page_class.php";
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
<link rel="stylesheet" type="text/css" href="landing_page_css.css" />
<script type="text/javascript" src="landing_page_js.js"></script>

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


<?php if ($_REQUEST['acao'] == "formLanding_page") {
	if ($_REQUEST['met'] == "cadastroLanding_page") {
		if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "landing_page_script.php?opx=cadastroLanding_page";
		$metodo_titulo = "Cadastro Landing Page";
		$idLanding_page = 0;

        $FontAwesome = false;

		// dados para os campos
		$landing_page['nome'] = "";
        $landing_page['titulo'] = "";
		$landing_page['status'] = "";
		$landing_page['urlrewrite'] = "";
        $landing_page['icone_name'] = "";
        $landing_page['icone'] = "";
        $landing_page['imagem'] = "";
        $landing_page['banner_topo'] = "";
        $landing_page['resumo'] = "";
        $landing_page['descricao'] = "";
        $landing_page['diferenciais_texto'] = "";
        $landing_page_imagens = array();
	} else {
		if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "landing_page_script.php?opx=editLanding_page";
		$metodo_titulo = "Editar Landing Page";
		$idLanding_page = (int) $_GET['idu'];
		$landing_page = buscaLanding_page(array('idlanding_page' => $idLanding_page));

        $itens = buscaItens(array('idlanding_page'=>$idLanding_page, 'ordem'=>'ordem', 'dir'=>'asc'));
        $difs = buscaDifs(array('idlanding_page'=>$idLanding_page, 'ordem'=>'ordem', 'dir'=>'asc'));
        $indicacoes = buscaIndicacoes(array('idlanding_page'=>$idLanding_page, 'ordem'=>'ordem', 'dir'=>'asc'));
        $abrangencia = buscaAbrangencia(array('idlanding_page'=>$idLanding_page, 'ordem'=>'ordem', 'dir'=>'asc'));

		if (count($landing_page) != 1) exit;
		$landing_page = $landing_page[0];

        $StringIcone = strlen($landing_page['icone']);
        if ($StringIcone > 3) {
            $FontAwesome = false;
            $landing_page['icone_name'] = '';
        } else {
            $FontAwesome = true;
            // $icones_Edit = buscaFW3(array('idfw' => $landing_page['icone']));
            // $icones_Edit = $icones_Edit[0];
        }
	}
	?>

	<div id="titulo">
		<i class='fa fa-laptop-house' aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=landing_page&acao=listarLanding_page">Listagem</a></li>
			<!-- <li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=landing_page&acao=formLanding_page&met=cadastroLanding_page">Cadastro</a></li> -->
		</ul>
	</div>




	<div id="principal">
		<form class="form" name="formLanding_page" id="formLanding_page" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));" enctype="multipart/form-data">

			<div id="informacaoLanding_page" class="content">
				<div class="content_tit">Dados Landing Page:</div>

                <div class="box_ip box_txt">
                    <label for="titulo_solucao">Título</label>
                    <input type="text" class="" name="titulo" id="titulo_solucao" value="<?php echo $landing_page['titulo']; ?>" />
                </div>

                <div class="box_ip box_txt">
                   <label for="descricao">Descrição</label>
                   <textarea name="descricao" id="descricao" class=""><?php echo $landing_page['descricao']; ?></textarea>
                </div>

                <div class="box_ip box_txt">
                   <label for="diferenciais_texto">Diferenciais Descrição</label>
                   <textarea name="diferenciais_texto" id="diferenciais_texto" class=""><?php echo $landing_page['diferenciais_texto']; ?></textarea>
                </div>

                <!-- CROPPER IMG -->
                <?php $caminho = 'files/landing_page/'; ?>
                <!-- <div id="select-image-1" class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Imagem</div>
                                <div class="box_ip imagem-atual" style="<?=empty($landing_page['imagem'])?'display: none;':''?>">
                                    <a data-tipo="imagem" data-img="<?=$landing_page['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img width="120" src="<?=empty($landing_page['imagem'])?'images/cliente/logo.png':$caminho.$landing_page['imagem']?>" class="img-landing_page-form" alt=""/>
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
                                <div class="box_ip imagem-atual" style="<?=empty($landing_page['banner_topo'])?'display: none;':''?>">
                                    <a data-tipo="banner_topo" data-img="<?=$landing_page['banner_topo']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img width="120" src="<?=empty($landing_page['banner_topo'])?'images/cliente/logo.png':$caminho.$landing_page['banner_topo']?>" class="img-landing_page-form" alt=""/>
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

            <!-- =======================Itens========================== -->
                <div class="listaItens box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Itens</div>
                        <a class="btn btn-itens"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridItens">
                        <table class="table" id="tableItens">
                            <thead>
                                <tr>
                                    <th align="center">Imagem/Ícone</th>
                                    <th></th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="itens">
                                <?php if(isset($itens) && !empty($itens)):?>
                                    <?php foreach($itens as $key => $rec):?>
                                        <tr class="box-itens removeItens-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-itens-<?=$key;?>" data-key="<?=$key;?>" data-grid="itens" />
                                                <?php else:?>
                                                    <img src="files/itens/<?=$rec['imagem'];?>" width="50"  class="img-upload img-itens-<?=$key;?>" data-key="<?=$key;?>" data-grid="itens" />
                                                <?php endif;?>

                                                <input type="file" name="itens[<?=$key;?>][imagem]" class="file-upload upload-itens-<?=$key;?>" data-key="<?=$key;?>" data-grid="itens">
                                                <span class="fs-11">Tamanho recomendado 70x70px </span>
                                                <input type="hidden" class="nome-img-cadastrada" name="itens[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <br/><span><b>OU</b></span>

                                                <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-itens-<?=$key?>" data-grid="itens" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i>
                                                    <input type="hidden" name="itens[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-itens-<?=$key;?>">
                                                    <input type="hidden" name="itens[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-itens-<?=$key;?>">
                                                </div>
                                                <input type="button" value="Escolher ícone" data-grid="itens" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>">

                                                <input type="hidden" name="itens[<?=$key;?>][iditens]" value="<?=$rec['iditens'];?>">
                                                <input id='excluirRecursoItens-<?=$key;?>' type="hidden" name="itens[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputItens w-100" name="itens[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputItens w-100" name="itens[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea>
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirItens" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerItens" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirItens" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="itens[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeItens-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="itens" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Itens========================== -->

            <!-- =======================Difs========================== -->
                <div class="listaDifs box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Diferenciais</div>
                        <a class="btn btn-difs"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridDifs">
                        <table class="table" id="tableDifs">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th align="center">Informações</th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="difs">
                                <?php if(isset($difs) && !empty($difs)):?>
                                    <?php foreach($difs as $key => $rec):?>
                                        <tr class="box-difs removeDifs-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <!-- <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-difs-<?=$key;?>" data-key="<?=$key;?>" data-grid="difs"/> -->
                                                <?php else:?>
                                                    <!-- <img src="files/difs/<?=$rec['imagem'];?>" width="50"  class="img-upload img-difs-<?=$key;?>" data-key="<?=$key;?>" data-grid="difs"/> -->
                                                <?php endif;?>

                                                <!-- <input type="file" name="difs[<?=$key;?>][imagem]" class="file-upload upload-difs-<?=$key;?>" data-key="<?=$key;?>" data-grid="difs"> -->
                                                <!-- <span class="fs-11">Tamanho recomendado 50x50px </span> -->
                                                <input type="hidden" class="nome-img-cadastrada" name="difs[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <!-- <br/><span><b>OU</b></span> -->

                                                <!-- <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-difs-<?=$key?>" data-grid="difs" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i> -->
                                                    <input type="hidden" name="difs[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-difs-<?=$key;?>">
                                                    <input type="hidden" name="difs[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-difs-<?=$key;?>">
                                                <!-- </div> -->
                                                <!-- <input type="button" value="Escolher ícone" data-grid="difs" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>"> -->

                                                <input type="hidden" name="difs[<?=$key;?>][iddifs]" value="<?=$rec['iddifs'];?>">
                                                <input id='excluirRecursoDifs-<?=$key;?>' type="hidden" name="difs[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputDifs w-100" name="difs[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <!-- <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputDifs w-100" name="difs[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea> -->
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirDifs" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerDifs" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirDifs" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="difs[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeDifs-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="difs" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Difs========================== -->

            <!-- =======================Indicacoes========================== -->
                <div class="listaIndicacoes box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Indicações</div>
                        <a class="btn btn-indicacoes"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridIndicacoes">
                        <table class="table" id="tableIndicacoes">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th align="center">Informações</th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="indicacoes">
                                <?php if(isset($indicacoes) && !empty($indicacoes)):?>
                                    <?php foreach($indicacoes as $key => $rec):?>
                                        <tr class="box-indicacoes removeIndicacoes-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <!-- <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-indicacoes-<?=$key;?>" data-key="<?=$key;?>" data-grid="indicacoes" /> -->
                                                <?php else:?>
                                                    <!-- <img src="files/indicacoes/<?=$rec['imagem'];?>" width="50"  class="img-upload img-indicacoes-<?=$key;?>" data-key="<?=$key;?>" data-grid="indicacoes" /> -->
                                                <?php endif;?>

                                                <!-- <input type="file" name="indicacoes[<?=$key;?>][imagem]" class="file-upload upload-indicacoes-<?=$key;?>" data-key="<?=$key;?>" data-grid="indicacoes"> -->
                                                <!-- <span class="fs-11">Tamanho recomendado 50x50px </span> -->
                                                <input type="hidden" class="nome-img-cadastrada" name="indicacoes[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <!-- <br/><span><b>OU</b></span> -->

                                                <!-- <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-indicacoes-<?=$key?>" data-grid="indicacoes" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i> -->
                                                    <input type="hidden" name="indicacoes[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-indicacoes-<?=$key;?>">
                                                    <input type="hidden" name="indicacoes[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-indicacoes-<?=$key;?>">
                                                <!-- </div>
                                                <input type="button" value="Escolher ícone" data-grid="indicacoes" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>"> -->

                                                <input type="hidden" name="indicacoes[<?=$key;?>][idindicacoes]" value="<?=$rec['idindicacoes'];?>">
                                                <input id='excluirRecursoIndicacoes-<?=$key;?>' type="hidden" name="indicacoes[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputIndicacoes w-100" name="indicacoes[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputIndicacoes w-100" name="indicacoes[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea>
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirIndicacoes" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerIndicacoes" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirIndicacoes" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="indicacoes[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeIndicacoes-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="indicacoes" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Indicacoes========================== -->

            <!-- =======================Abrangencia========================== -->
                <div class="listaAbrangencia box_ip box_txt">
                    <div class="content_tit">
                        <div class="content_tit">Abrangência</div>
                        <a class="btn btn-abrangencia"><i class="fas fa-plus"></i> Adicionar</a>
                    </div>
                    <div class="gridLista" id="gridAbrangencia">
                        <table class="table" id="tableAbrangencia">
                            <thead>
                                <tr>
                                    <th align="center">Imagem/Ícone</th>
                                    <th></th>
                                    <th></th>
                                    <th align="center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="abrangencia">
                                <?php if(isset($abrangencia) && !empty($abrangencia)):?>
                                    <?php foreach($abrangencia as $key => $rec):?>
                                        <tr class="box-abrangencia removeAbrangencia-<?=$key;?>" data-key="<?=$key?>">
                                            <td align="center" class="td-padding">

                                                <?php if(empty($rec['imagem'])):?>
                                                    <img src="https://via.placeholder.com/50?text=Upload+Foto" width="50"  class="img-upload img-abrangencia-<?=$key;?>" data-key="<?=$key;?>" data-grid="abrangencia" />
                                                <?php else:?>
                                                    <img src="files/abrangencia/<?=$rec['imagem'];?>" width="50"  class="img-upload img-abrangencia-<?=$key;?>" data-key="<?=$key;?>" data-grid="abrangencia" />
                                                <?php endif;?>

                                                <input type="file" name="abrangencia[<?=$key;?>][imagem]" class="file-upload upload-abrangencia-<?=$key;?>" data-key="<?=$key;?>" data-grid="abrangencia">
                                                <span class="fs-11">Tamanho recomendado 47x47px </span>
                                                <input type="hidden" class="nome-img-cadastrada" name="abrangencia[<?=$key;?>][imagem]" value="<?=$rec['imagem'];?>">

                                                <br/><span><b>OU</b></span>

                                                <div id="mostrar_icone-<?=$key;?>" class="m-15">
                                                    <i id="current-icon-abrangencia-<?=$key?>" data-grid="abrangencia" class='current-icon fas fa-<?=$rec['nome_icone'];?> fa-2x '></i>
                                                    <input type="hidden" name="abrangencia[<?=$key;?>][icone]" value="<?=$rec['icone'];?>" id="imagem_icone-abrangencia-<?=$key;?>">
                                                    <input type="hidden" name="abrangencia[<?=$key;?>][nome_icone]" value="<?=$rec['nome_icone'];?>" id="nome_icone-abrangencia-<?=$key;?>">
                                                </div>
                                                <input type="button" value="Escolher ícone" data-grid="abrangencia" class="btn-choose-icon btn button-escolher-icone" data-key="<?=$key;?>">

                                                <input type="hidden" name="abrangencia[<?=$key;?>][idabrangencia]" value="<?=$rec['idabrangencia'];?>">
                                                <input id='excluirRecursoAbrangencia-<?=$key;?>' type="hidden" name="abrangencia[<?=$key;?>][excluirRecurso]" value="1">
                                            </td>
                                            <td colspan="2">
                                                <input type="text" class="box_txt inputAbrangencia w-100" name="abrangencia[<?=$key;?>][nome]" value="<?=$rec['nome'];?>" placeholder="Nome">
                                                <textarea rows="6" type="text" style="resize: vertical" class="box_txt inputAbrangencia w-100" name="abrangencia[<?=$key;?>][descricao]" placeholder="Descrição"><?=$rec['descricao'];?></textarea>
                                            </td>
                                            <td align="center">
                                                <span class="td-flex">
                                                    <span class="subirAbrangencia" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-up"></b>
                                                    </span>
                                                    <span class="descerAbrangencia" data-key="<?=$key;?>">
                                                        <b class="fas fa-arrow-down"></b>
                                                    </span>
                                                    <span class="excluirAbrangencia" data-key="<?=$key;?>">
                                                        <b class="fas fa-trash"></b>
                                                    </span>
                                                    <input type="hidden" name="abrangencia[<?=$key?>][ordem]" value="<?=$rec['ordem']?>">
                                                </span>
                                            </td>
                                        </tr>

                                        <tr class="removeAbrangencia-<?=$key;?>">
                                            <td colspan="4">
                                                <!-- <div id="escolha-icone-<?=$key;?>"><div class="box_ip div-icones" style="width: 100% !important;"></div></div> -->
                                                <div data-grid="abrangencia" data-key="<?=$key?>" class="div-show-icons div-mostra-icones div-icones">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- =======================Fim Abrangencia========================== -->

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
            <!-- ===== Fim ícones ===== -->

			</div>

            <input type="hidden" id="mod" name="mod" value="<?= ($idLanding_page == 0)? "cadastro":"editar"; ?>" />
			<input type="hidden" name="idlanding_page" id="idlanding_page" value="<?php echo $idLanding_page; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
            <input type='hidden' name='aspectRatioW2' id='aspectRatioW2' value='<?=$width2?>'>
            <input type='hidden' name='aspectRatioH2' id='aspectRatioH2' value='<?=$height2?>'>
            <input type='hidden' name='imagem' id='imagem-value' value='<?=$landing_page['imagem']?>'>
            <input type='hidden' name='banner_topo' id='imagem_2-value' value='<?=$landing_page['banner_topo']?>'>
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


<?php if ($_REQUEST['acao'] == "listarLanding_page") { ?><?php
if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_visualizar', $MODULOACESSO['usuario']))
	header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
<div id="titulo">
	<i class='fa fa-laptop-house' aria-hidden="true"></i>
	<span>Listagem de Landing Page</span>
	<ul class="other_abs">
		<li class="other_abs_li"><a href="index.php?mod=landing_page&acao=listarLanding_page">Listagem</a></li>
		<!-- <li class="others_abs_br"></li>
		<li class="other_abs_li"><a href="index.php?mod=landing_page&acao=formLanding_page&met=cadastroLanding_page">Cadastro</a></li> -->
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
			<li class="abas_list_li action"><a href="javascript:void(0)">Landing Page</a></li>
		</ul>
		<ul class="abas_bts">
			<!-- <li class="abas_bts_li"><a href="index.php?mod=landing_page&acao=formLanding_page&met=cadastroLanding_page"><img src="images/novo.png" alt="Cadastro Landing Page" title="Cadastrar Landing Page" /></a></li> -->
			<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=landing_page&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
			<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=landing_page&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"></a></li>
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
		requestInicio = "tipoMod=landing_page&p=" + preventCache + "&";
		ordem = "idlanding_page";
		dir = "desc";
		$(document).ready(function() {
			preTableLanding_page();
		});
		dataTableLanding_page('<?php print $buscar; ?>');
		columnLanding_page();
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