<?php
// Versao do modulo: 3.00.010416

    include_once "depoimento_class.php";
    if (!isset($_REQUEST['acao']))
        $_REQUEST['acao'] = "";

    $width = 61;
    $height = 61;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="depoimento_css.css" />
<script type="text/javascript" src="depoimento_js.js"></script>

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
<?php if ($_REQUEST['acao'] == "formDepoimento") {
    if ($_REQUEST['met'] == "cadastroDepoimento") {
        if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_criar', $MODULOACESSO['usuario'])) {
            header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
            exit;
        }
        $action = "depoimento_script.php?opx=cadastroDepoimento";
        $metodo_titulo = "Cadastro Depoimento";
        $idDepoimento = 0;

        // dados para os campos
        $depoimento['nome'] = "";
        $depoimento['depoimento'] = "";
        $depoimento['subtitulo'] = "";
        $depoimento['ordem'] = "";
        $depoimento['status'] = "";
        $depoimento['imagem'] = "";

        $NovaOrdem = buscaDepoimento(array('ordem' => 'ordem', 'dir' => 'desc', 'limit' => 1));
        if (!empty($NovaOrdem)) {
            $NovaOrdem = $NovaOrdem[0];
            $depoimento['ordem'] = $NovaOrdem['ordem'] + 1;
        }
        else {
            $depoimento['ordem'] = 1;
        }

    } else {
        if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_editar', $MODULOACESSO['usuario'])) {
            header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
            exit;
        }
        $action = "depoimento_script.php?opx=editDepoimento";
        $metodo_titulo = "Editar Depoimento";
        $idDepoimento = (int) $_GET['idu'];
        $depoimento = buscaDepoimento(array('iddepoimento' => $idDepoimento));
        if (count($depoimento) != 1) exit;
        $depoimento = $depoimento[0];
    }
?>

    <div id="titulo">
        <!-- <img src="images/modulos/depoimento_preto.png" height="24" width="24" alt="ico" /> -->
        <i class="fas fa-plus fa-2x"></i>
        <span><?php print $metodo_titulo; ?></span>
        <ul class="other_abs">
            <li class="other_abs_li"><a href="index.php?mod=depoimento&acao=listarDepoimento">Listagem</a></li>
            <li class="others_abs_br"></li>
            <li class="other_abs_li"><a href="index.php?mod=depoimento&acao=formDepoimento&met=cadastroDepoimento">Cadastro</a></li>
        </ul>
    </div>

    <div id="principal">
        <form class="form" name="formDepoimento" id="formDepoimento" enctype="multipart/form-data" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
            <div id="informacaoDepoimento" class="content">
                <div class="content_tit">Dados Depoimento:</div>
                <div class="box_ip box_txt">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" size="30" maxlength="100" value="<?php echo $depoimento['nome']; ?>" class=""/>
                </div>
                <div class="box_ip box_txt">
                    <label for="depoimento">Depoimento</label>
                    <textarea class="" name="depoimento" id="depoimento" cols="34" rows="4"><?php echo $depoimento['depoimento'];?></textarea>
                </div>
                <div class="box_ip">
                    <label for="subtitulo">Subtítulo</label>
                    <input type="text" name="subtitulo" id="subtitulo" size="30" maxlength="100" value="<?php echo $depoimento['subtitulo']; ?>" />
                </div>
                <div class="box_ip">
                    <div class="box_sel box_txt">
                        <label for="">Status</label>
                        <div class="box_sel_d">
                            <select name="status" id="status" class="">
                                <!-- <option value=''></option> -->
                                <option value="1" <?php print($depoimento['status'] == "1" ? ' selected="selected" ' : ''); ?>> Ativo </option>
                                <option value="0" <?php print($depoimento['status'] == "0" ? ' selected="selected" ' : ''); ?>> Inativo </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- CROPPER IMG -->
                <?php $caminho = 'files/depoimento/'; ?>
                <div class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Imagem</div>
                                <?php if ($depoimento['imagem'] != '') { ?>
                                    <div class="box_ip imagem-atual">
                                        <a data-tipo="imagem" data-img="<?=$depoimento['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                        <img style="width: <?=$width?>px; height: <?=$height?>px;" src="<?=$caminho.$depoimento['imagem'];?>" class="img-depoimento-form" alt=""/>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <input type="hidden" value="" name="coordenadas" id="coordenadas" />
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <!--input FILE -->
                                    <input id="" class="cropped-image" name="imagemCadastrar" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width?>x<?=$height?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que: <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div><!-- /.docs-buttons -->
                            <div class="img-container" id="img-container" style="display:none;">
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
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="ordem" id='ordem' value="<?php echo $depoimento['ordem']; ?>" />
            <input type="hidden" name="imagem" value="<?php echo $depoimento['imagem']; ?>" />
            <input type="hidden" name="iddepoimento" value="<?php echo $idDepoimento; ?>" />
            <input type="submit" value="Salvar" class="bt_save salvar" />
            <input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
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


<?php if ($_REQUEST['acao'] == "listarDepoimento") { ?>
    <?php
        if (!verificaPermissaoAcesso($MODULOACESSO['modulo'] . '_visualizar', $MODULOACESSO['usuario']))
            header('Location:index.php?mod=home&mensagemalerta=' . urlencode('Voce nao tem privilegios para acessar este modulo!'));
    ?>
    <div id="titulo">
        <!-- <img src="images/modulos/depoimento_preto.png" height="22" width="24" alt="ico" /> -->
        <i class="fas fa-comment fa-2x"></i>
        <span>Listagem de Depoimento</span>
        <ul class="other_abs">
            <li class="other_abs_li"><a href="index.php?mod=depoimento&acao=listarDepoimento">Listagem</a></li>
            <li class="others_abs_br"></li>
            <li class="other_abs_li"><a href="index.php?mod=depoimento&acao=formDepoimento&met=cadastroDepoimento">Cadastro</a></li>
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
            
            <div class="box_ip">
                <div class="box_sel">
                  <label for="status">Status</label>
                  <div class="box_sel_d">
                        <select name="status" id="status" class=''>
                            <option></option>
                            <option value="1"> Ativo </option>
                            <option value="0"> Inativo </option>
                        </select>
                  </div>
               </div>
            </div>
            <a href="" class="advanced_bt" id="filtrar">Filtrar</a>
        </form>
    </div>

    <div id="principal">
        <div id="abas">
            <ul class="abas_list">
                <li class="abas_list_li action"><a href="javascript:void(0)">Depoimento</a></li>
            </ul>
            <ul class="abas_bts">
                <li class="abas_bts_li"><a href="index.php?mod=depoimento&acao=formDepoimento&met=cadastroDepoimento"><img src="images/novo.png" alt="Cadastro Depoimento" title="Cadastrar Depoimento" /></a></li>
                <li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=depoimento&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
                <li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=depoimento&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"></a></li>
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
            requestInicio = "tipoMod=depoimento&p=" + preventCache + "&";
            ordem = "ordem";
            dir = "asc";
            $(document).ready(function() {
                preTableDepoimento();
            });
            dataTableDepoimento('<?php print $buscar; ?>');
            columnDepoimento();
        </script>
    </div>
<?php } ?>

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>