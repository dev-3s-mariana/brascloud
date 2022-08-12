<?php
	if(@extension_loaded('zlib') && !headers_sent()){
	   ob_start('ob_gzhandler');
	   header("Content-Encoding: gzip,deflate");
	}
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include_once("includes/basic.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex, nofollow" />
        <title><?php print $info['tituloPaginaLogin']; ?></title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <link type="text/css" rel="stylesheet" href="css/login.css" />
        <link type="text/css" rel="stylesheet" href="css/fonts.css" />
    </head>
    <body class="bg" onload="document.getElementById('usuario').focus();">
        <div id="tudo">
            <div class="login" id="login">
                <div class="login_logo">
                    <img src="images/cliente/logo.png" alt="Logo" />
                </div>
                <form class="login_inner bg2" action="usuario_script.php?opx=login" method="post">
                    <div class="login_inner_tit"><img src="images/set_login.png" height="7" width="13" alt="ico" />Sistema de Gestão Web</div>
                    <input type="text" placeholder="Login" class="login_inner_ip" name="usuario" autocomplete="off" id="usuario" />
                    <input type="password" placeholder="Senha" class="login_inner_ip" name="senha" autocomplete="off" id="senha" />
                    <input type="submit" id="botao" class="login_inner_bt" value="Entrar" />
                    <span href="" class="login_inner_fg"><?php print isset($_GET['msg']) ? $_GET['msg'] : ''; ?></span>
                </form>
            </div>
            <small>Desenvolvido por <a href="http://www.agencia.red" target="_blank">Agência Red</a></small>
        </div>
    </body>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
</html>
<?php
	ob_end_flush();
?>