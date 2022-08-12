<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

   if(@extension_loaded('zlib') && !headers_sent()){
      ob_start('ob_gzhandler');
      header("Content-Encoding: gzip,deflate");
   }

   include("includes/verifica.php");   
   include("includes/basic.php");
   if(isset($_GET['mod']))
      $mod = $_GET['mod'];
   else
      $mod = "home";

?>
<!DOCTYPE HTML>
<html lang="en-US">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
<head>
<meta charset="UTF-8">
<title><?php print $info['tituloPagina']; ?></title>

<link rel="shortcut icon" href="images/cliente/favicon.ico" />
<link rel="shortcut icon" href="images/cliente/favicon.png" />
<link type="text/css" rel="stylesheet" href="js/jqueryuired/jquery-ui.min.css" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
<link type="text/css" rel="stylesheet" href="css/fonts.css" />
<link type="text/css" rel="stylesheet" href="css/form.css" />
<link type="text/css" rel="stylesheet" href="css/list.css" />
<link type="text/css" rel="stylesheet" href="css/timepicker.css" />
<link type="text/css" rel="stylesheet" href="js/jnotify/jquery/jNotify.jquery.css" media="screen" />

<!-- FANCYBOX 3 -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" /> -->
<link type="text/css" rel="stylesheet" href="js/fancyapps/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link type="text/css" rel="stylesheet" href="js/fancyapps/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" media="screen" />
<link type="text/css" rel="stylesheet" href="js/fancyapps/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" media="screen" />

<!-- CUSTOM MOBILE -->
<link type="text/css" rel="stylesheet" href="css/custom-mobile.css" /> 
<!--FONTAWESOME-->
<link type="text/css" rel="stylesheet" href="css/fontawesome/all.min.css" media="screen" />

<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-3.3.2.min.js"></script> -->
<!-- <script type="text/javascript" src="js/jquery-2.2.2.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script> -->

<script type="text/javascript" src="js/jnotify/jquery/jNotify.jquery.js"></script>

<script type="text/javascript" src="js/fancyapps/lib/jquery.mousewheel.pack.js"></script>
<script type="text/javascript" src="js/fancyapps/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="js/fancyapps/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="js/fancyapps/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript" src="js/fancyapps/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<script type="text/javascript" src="tinymce/tinymce.min.js"></script>

<script src="https://maps.google.com/maps/api/js" type="text/javascript"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript" src="js/script.js"></script>
<!-- <script type="text/javascript" src="js/jqueryui/jquery-ui.min.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/timepicker.js"></script>
<script type="text/javascript" src="paginacao/paginacao.js"></script>

<!--SCRIPT PARA UPLOAD DOS ARQUIVOS-->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/jquery_upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="js/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="js/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery_upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery_upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery_upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery_upload/js/jquery.fileupload-image.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="js/jquery_upload/js/bootstrap.min.js"></script>


<script type="text/javascript">
     $(document).ready(function(){

      $('.fancybox').fancybox();
      $('.wDate').mask('00/00/0000');
      $('.time').mask('00:00:00');
      $('.date_time').mask('00/00/0000 00:00:00');
      $('.cep').mask('00000-000');
      $('.phone').mask('0000-0000');
      $('.phone_with_ddd').mask('(00) 0000-0000');
      $('.phone_br').mask('(00) 0 0000-0000');
      $('.phone_us').mask('(000) 000-0000');
      $('.mixed').mask('AAA 000-S0S');
      $('.cpf').mask('000.000.000-00', {reverse: true});
      $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
      $('.money').mask('000.000.000.000.000,00', {reverse: true});
      $('.money2').mask("#.##0,00", {reverse: true});
      $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
          'Z': {
            pattern: /[0-9]/, optional: true
          }
        }
      });
      $('.ip_address').mask('099.099.099.099');
      $('.percent').mask('##0,00%', {reverse: true});
      $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
      $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
      $('.fallback').mask("00r00r0000", {
          translation: {
            'r': {
              pattern: /[\/]/,
              fallback: '/'
            },
            placeholder: "__/__/____"
          }
        });
      $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
      // jQuery(".money").maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: false});
      // jQuery(".moneyZero").maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: false, allowZero: true});
      // jQuery(".moneyZeroNegative").maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: false, allowZero: true, allowNegative: true});
      // jQuery(".percent").maskMoney({suffix:' %', thousands:'.', decimal:',', affixesStay: false});
      // jQuery(".percentZero").maskMoney({suffix:' %', thousands:'.', decimal:',', affixesStay: false, allowZero: true});
      // jQuery(".telefone").mask("(99) 9999.9999?9",{placeholder: " "}).on("keyup", function() {
      //    var elemento = $(this);
      //    var valor = elemento.val().replace(/\D/g, '');
      //    if (valor.length > 10) {
      //       elemento.mask("(99) 99999.9999",{placeholder: " "});
      //    } else if (valor.length > 9) {
      //       elemento.mask("(99) 9999.9999?9",{placeholder: " "});
      //    }
      // }).trigger('keyup');
      // jQuery(".data, .wDate").mask("99/99/9999");
      // jQuery(".datahora, .wDatetime").mask("99/99/9999 99:99");
      // jQuery(".hora, .wTime").mask("99:99",{completed: function() {
      //    var elemento = $(this);
      //    var valor = elemento.val();
      //    var valor_array = valor.split(":");
      //    var horas = valor_array[0];
      //    var minutos = valor_array[1];
      //    if ((horas > 24) || (minutos > 59)) {
      //       alert("Hora inválida!");
      //       elemento.val("");
      //    }
      // }});
      <?php
         if(isset($_GET['mensagemalerta'])){
      ?>
            jSuccess('<?php print $_GET['mensagemalerta']; ?>',
                  {
                     autoHide : true, // added in v2.0
                    clickOverlay : true, // added in v2.0
                     TimeShown : 3000,
                     HorizontalPosition : 'center',
                     VerticalPosition : 'top',
                     MinWidth : 940
                   }
               );
      <?php
         } elseif(isset($_GET['mensagemerro'])) {
      ?>
            jError('<?php print $_GET['mensagemerro']; ?>',
                  {
                     autoHide : true, // added in v2.0
                    clickOverlay : true, // added in v2.0
                     TimeShown : 3000,
                     HorizontalPosition : 'center',
                     VerticalPosition : 'top',
                     MinWidth : 940
                   }
         );
      <?php
         }
      ?>
   });
   tinymce.init({
      language : 'pt_BR',
      selector:'.mceAdvanced',
      width:'unset',
      plugins : 'advlist autolink link image lists charmap print preview code media',
      theme: "modern", 
      plugins: [
         "advlist autolink lists link image charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code fullscreen",
         "insertdatetime media nonbreaking save table contextmenu directionality",
         "emoticons template paste textcolor imagetools"
      ],
      object_resizing : "img",
      toolbar1: "insertfile undo redo insert| styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontsizeselect",
      toolbar2: "print preview media | forecolor backcolor emoticons",
      textcolor_map: [
            "C4372B", "Vermelho site",
            "000000", "Black",
            "993300", "Burnt orange",
            "333300", "Dark olive",
            "003300", "Dark green",
            "003366", "Dark azure",
            "000080", "Navy Blue",
            "333399", "Indigo",
            "333333", "Very dark gray",
            "800000", "Maroon",
            "FF6600", "Orange",
            "808000", "Olive",
            "008000", "Green",
            "008080", "Teal",
            "0000FF", "Blue",
            "666699", "Grayish blue",
            "808080", "Gray",
            "FF0000", "Red",
            "FF9900", "Amber",
            "99CC00", "Yellow green",
            "339966", "Sea green",
            "33CCCC", "Turquoise",
            "3366FF", "Royal blue",
            "800080", "Purple",
            "999999", "Medium gray",
            "FF00FF", "Magenta",
            "FFCC00", "Gold",
            "FFFF00", "Yellow",
            "00FF00", "Lime",
            "00FFFF", "Aqua",
            "00CCFF", "Sky blue",
            "993366", "Red violet",
            "FFFFFF", "White",
            "FF99CC", "Pink",
            "FFCC99", "Peach",
            "FFFF99", "Light yellow",
            "CCFFCC", "Pale green",
            "CCFFFF", "Pale cyan",
            "99CCFF", "Light sky blue",
            "CC99FF", "Plum"
      ]
   });
</script>

</head>
<body onload="startTime()">
   <input type="hidden" id="localizacao" value="<?=((isset($_SESSION['localizacao']))? "S" : "N")?>" />
   <div id="dialog-confirm"></div>
   <div class="loader"><div class="loader__content"></div>
  </div>
<!--[if lte IE 8]>
   <script>
      document.createElement('header');
      document.createElement('figure');
      document.createElement('hgroup');
      document.createElement('nav');
      document.createElement('section');
      document.createElement('article');
      document.createElement('aside');
      document.createElement('footer');
   </script>
<![endif]-->
   <div class="box_main">
      <?php include_once 'includes/menu.php' ?>
      <ul class="migalha">
         <li class="migalha_home"><a href="<?=ENDERECO?>" target='_blank'><i class="breadcumb-icon fas fa-home"></i></a></li>
         <li class="migalha_li"><a href=""><?php echo isset($_GET['mod']) ?  ucwords(str_replace("_"," ",$_GET['mod'])) : 'Home'?></a></li>
         <?php if(isset($_GET['acao'])){?>
            <li class="migalha_set"><img src="images/ico_migalha.png" height="8" width="7" alt="ico" /></li>
            <li class="migalha_txt">
               <?php
                  if(isset($_GET['met']) and (strpos($_GET['met'],"cadas") !== FALSE or strpos($_GET['met'],"nova") !== FALSE) )
                     echo "Cadastro";
                  else if(isset($_GET['met']) and strpos($_GET['met'],"edit") !== FALSE)
                     echo "Alterar cadastro";
                  else if(isset($_GET['acao']) and strpos($_GET['acao'], "list") !==  FALSE)
                     echo "Consulta";
                  else if(isset($_GET['acao']) and strpos($_GET['acao'], "relatorioAcesso") !==  FALSE)
                     echo "Relatório Acessos";
               ?></li>
         <?php } ?>
      </ul>
      <section>
         <?php
              include $mod."_mod.php";
         ?>
       </section>
    </div>
    <footer class="<?=(!isset($_SESSION['lateral']) || $_SESSION['lateral'] == "open" ? "" : 'footer_small' );?>">
      <small>v3.0 - Copyright © <?php echo date('Y');?> Agência Red. Todos os direitos Reservado.</small>
      <a class="ftop" href="">Topo</a>
    </footer>
</body>
</html>
<?php
   ob_end_flush();
?>
