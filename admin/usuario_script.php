<?php  

$opx = $_REQUEST["opx"];

if ($opx !== 'login') {
    require_once 'includes/verifica.php'; // checa user logado
}else{
	session_start();
}  
switch ( $opx ) {

  case "novoUsuario" :

		include_once("usuario_class.php");

		$dados = $_REQUEST;

		$permissoes = '';
		foreach($dados as $k=>$v){
			if($v == 'permitido')
			$permissoes .= ' '.$k;
		}
		$dados['permissoes'] = trim($permissoes);


		$idusuario = novaUsuario($dados);


		//salva log
		 include_once('log_class.php');
		 $log['idusuario'] = $_SESSION['sgc_idusuario'];
		 $log['modulo'] = 'usuario';
		 $log['descricao'] = 'Criou usuario ('.$dados["nome"].'.'.$dados["sobrenome"].')email ('.$dados["email"].')';
		 $log['request'] = $dados;
		 novoLog($log);

		 header('Location: index.php?mod=usuario&acao=listarUsuario&mensagemalerta='.urlencode('Usuário Cadastrado com Sucesso!'));

  break;
  case "editUsuario":
		include_once("usuario_class.php");

		$dados = $_REQUEST;

		$permissoes = '';
		foreach($dados as $k=>$v){
			if($v == 'permitido')
			$permissoes .= ' '.$k;
		}

		$dados['permissoes'] = trim($permissoes); 

		$resultado = buscaUsuario(array('idusuario'=>$dados['idu']));
		$usuario = $resultado[0]; 
		$idUsuario = editUsuario($dados);  
        if ($idUsuario) {  
            if($dados['foto'] != $usuario['foto']){
                unlink("files/images/".$usuario['foto']);
                unlink("files/images/thumbs/".$usuario['foto']);
                unlink("files/images/thumbs2/".$usuario['foto']);
            } 
            if($dados['idusuario'] == $_SESSION['sgc_idusuario']){ 
            	$_SESSION['sgc_foto'] = $dados['foto'];
        	}
        }

		//salva log
		include_once('log_class.php');
		$log['idusuario'] = $_SESSION['sgc_idusuario'];
		$log['modulo'] = 'usuario';
		$log['descricao'] = 'Editou usuario ('.$usuario['nome'].'.'.$usuario['sobrenome'].') para ('.$dados["nome"].'.'.$dados["sobrenome"].'), email ('.$dados["email"].')';
		$log['request'] = $dados;
		novoLog($log);

		if($_SESSION['sgc_idusuario'] == $dados['idu'])
			header('Location: index.php?mod=home&mensagemalerta='.urlencode('Usuário Salvo com Sucesso!'));
		else
			header('Location: index.php?mod=usuario&acao=listarUsuario&mensagemalerta='.urlencode('Usuário Salvo com Sucesso!'));
  break;
  
  case "deletaUsuario":
		include_once("usuario_class.php");

		$dados = $_REQUEST;


		$resultado = buscaUsuario(array('idusuario'=>$dados['idu']));
		$usuario = $resultado[0];


		deletaUsuario($dados['idu']);


		//salva log
		include_once('log_class.php');
		 $log['idusuario'] = $_SESSION['sgc_idusuario'];
		 $log['modulo'] = 'usuario';
		 $log['descricao'] = 'Excluiu usuario ('.$usuario['nome'].'.'.$usuario['sobrenome'].') id ('.$dados['idu'].')';
		 $log['request'] = $dados;
		 novoLog($log);

		 header('Location: index.php?mod=usuario&acao=listarUsuario&mensagemalerta='.urlencode('Usuário Deletado com Sucesso!'));

  break;


  case "login":
		include_once("usuario_class.php");
		$dados = $_POST;

		$retorno = login($dados);
		 
		switch($retorno['tipo']){
			case 'bloqueado':

				include_once('log_class.php');
				$log['idusuario'] = 0;
				$log['modulo'] = 'login';
				$log['descricao'] = 'Usuario ('.$retorno['usuario'].') tentou acessar conta bloqueada. Usando IP('.$retorno['ip'].')';
				$log['request'] = $dados;
				novoLog($log);

				header("location:login.php?msg=".urlencode('Usuario Bloqueado!'));
			break;
			case 'sucesso':

				include_once('log_class.php');
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'login';
				$log['descricao'] = 'Usuario ('.$retorno['usuario'].') acessou admin. Usando IP('.$retorno['ip'].')';
				$log['request'] = $dados;
				novoLog($log);

				header("location:index.php");
			break;
			case 'falha':

				include_once('log_class.php');
				$log['idusuario'] = 0;
				$log['modulo'] = 'login';
				$log['descricao'] = 'Usuario ('.$retorno['usuario'].') tentou acessar com algum dado incorreto. Usando IP('.$retorno['ip'].')';
				$log['request'] = $dados;
				novoLog($log);

				header("location:login.php?msg=".urlencode('Entrada Invalida!'));
			break;
		}
  break;
  case "logout":
		include_once("usuario_class.php");
		$dados = $_POST;
		$idusuario = $_SESSION['sgc_idusuario'];

		logout();

		include_once('log_class.php');
		$log['idusuario'] = $idusuario;
		$log['modulo'] = 'logout';
		$log['descricao'] = 'Usuario ('.$idusuario.') fez logout';
		$log['request'] = $dados;
		novoLog($log);

		header("location:login.php?msg=".urlencode('Logout Efetuado!'));
  break;
  case 'validaUsuario':
	  	include_once("usuario_class.php");
	  	include_once("includes/functions.php");

		$dados = $_REQUEST;   
		$dados['nome'] =  criaConsulta(trim(utf8_encode($dados['nome']))); 
		$dados['sobrenome'] = criaConsulta(trim(utf8_encode($dados['sobrenome']))); 
		$usuario['usuario_exato'] = $dados['nome'].".".$dados['sobrenome'];
	 	$usuario['semStatus'] = true; 
		$allRecords =  buscaUsuario($usuario); 
      	echo json_encode($allRecords);

  break;

  case 'validaEmail':
	  include_once("usuario_class.php");

	  $dados = $_REQUEST;

	  $idusuario = $dados['idusuario'];
	  unset($dados['idusuario']);
	  $usuario['email'] = trim($dados['email']); 	  
	  $allRecords =  buscaUsuario($dados);
	  
	  $resultado['status'] = true;	
	  if((!empty($allRecords) && !empty($idusuario) && $allRecords[0]['idusuario'] != $idusuario)
	  	|| (!empty($allRecords) && empty($idusuario))){
	  	  $resultado['status'] = false;
	  }  

	  echo json_encode($resultado); 

  break;

  case 'gravarMenuLateral':
	  if(!isset($_SESSION['lateral'])){
	  	$_SESSION['lateral'] = 'close';
	  }else{
	  	if($_SESSION['lateral'] == 'close'){
	  		$_SESSION['lateral'] = 'open';
	  	}else{
	  		$_SESSION['lateral'] = 'close';
	  	}
	  }
  break;

   case 'formAlterarImagem':
           include_once("usuario_class.php");
           $dados =  buscaUsuario(array("idusuario"=>$_SESSION['sgc_idusuario']));
           $html = <<<EOD

           <script src='usuario_js.js'></script>
           <div class="content" style="margin: 0;">
                <div class="content_tit">Imagem:</div>
                    <div class="box_img">
                        <img src="files/images/thumbs/{$dados[0]['foto']}" alt="img" id="img_imagem" name="img_imagem" />
                        <input type="hidden" name="fotoUsuario" id="fotoUsuario"  value="{$dados[0]['foto']}">
                        <input type="hidden" name="apagarFoto" id="apagarFoto" value="">
                    </div>
                    <input type="file" id="updloadImagem" name="files[]" >
                    <!-- The global progress bar -->
                    <div id="progress" class="progress" style="display:none;">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>
                    <br />
                    <p class="pre">Tamanho mínimo recomendado: 190x142 px (ou maior proporcional)  <br />  Extensão recomendada: jpg, png</p>
              </div>
EOD;
           print $html;
  break;

  case 'updateImagem':
          include_once("usuario_class.php");
          $dados = $_REQUEST;
          $dados['idusuario'] = $_SESSION['sgc_idusuario'];

          $resultado = buscaUsuario(array('idusuario'=>$dados['idusuario']));
	  	  $usuario = $resultado[0];
          $alteraFoto = editFotoUsuario($dados);

          if ($alteraFoto != FALSE) {
               if($dados['foto'] != $usuario['foto']){
                   unlink("files/images/".$usuario['foto']);
                   unlink("files/images/thumbs/".$usuario['foto']);
                   unlink("files/images/thumbs2/".$usuario['foto']);
               }

               $_SESSION['sgc_foto'] = $dados['foto'];
           }

          $_SESSION['sgc_foto'] = $dados['foto'];
          header('Location: index.php?mensagemalerta='.urlencode('Foto alterada com suceesso!'));
  break;


  default:
  	header("location:../../login.php?msgerro=3");
  break;
}

?>
