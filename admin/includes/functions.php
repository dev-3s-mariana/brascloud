<?php
    include_once(__DIR__."/../integracoes_class.php");

   //Import PHPMailer classes into the global namespace
   //These must be at the top of your script, not inside a function
   include_once(__DIR__.'/../mail/src/PHPMailer.php');
   include_once(__DIR__.'/../mail/src/SMTP.php');
   include_once(__DIR__.'/../mail/src/Exception.php');
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;

    function compressImage($image){
        $tinyfiToken = buscaIntegracoes(array('idintegracoes'=>3));
        if(!empty($tinyfiToken)){
            $tinyfiToken = $tinyfiToken[0]['token'];
        }

        if(!empty($tinyfiToken)){
            require_once(__DIR__."/../tinify/lib/Tinify/Exception.php");
            require_once(__DIR__."/../tinify/lib/Tinify/ResultMeta.php");
            require_once(__DIR__."/../tinify/lib/Tinify/Result.php");
            require_once(__DIR__."/../tinify/lib/Tinify/Source.php");
            require_once(__DIR__."/../tinify/lib/Tinify/Client.php");
            require_once(__DIR__."/../tinify/lib/Tinify.php");
            \Tinify\setKey($tinyfiToken);

            $source = \Tinify\fromFile($image);
            $source->toFile($image);
            return $image;
        }
        else{
            return $image;
        }
    }

   // gera senha
   function geraChave($tamanho){      
      $codigo = "";   
         for($x=1; $x <= $tamanho; $x++){     
         $sec = rand(0,2);    
         switch($sec){    
            case 0: 
               $ri = 48; $rf = 57; 
            break;
            case 1: 
               $ri = 65; $rf = 90; 
            break;
            case 2: 
               $ri = 97; $rf = 122; 
            break;    
            default: 
               $ri = 48; $rf = 57; 
            break;    
         }    
         $codigo .= chr(rand($ri,$rf));   
      }   
      return $codigo;
   }

   function converteTag($texto){
      $texto = utf8_decode($texto);

      $acentos = array(
         'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
         'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
         'C' => '/&Ccedil;/',
         'c' => '/&ccedil;/',
         'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
         'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
         'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
         'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
         'N' => '/&Ntilde;/',
         'n' => '/&ntilde;/',
         'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
         'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
         'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
         'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
         'Y' => '/&Yacute;/',
         'y' => '/&yacute;|&yuml;/',
         '' => '/&ordf;/',
         '' => '/&ordm;/',
         '-' => '/&frasl;/',
         '' => '/&ordf;/'
      );
      $texto = htmlentities($texto,ENT_NOQUOTES);
      $texto = preg_replace($acentos, array_keys($acentos), $texto);

      $texto = html_entity_decode($texto, ENT_NOQUOTES);

      $texto = str_replace('/', '_', $texto);   
      $texto = str_replace('\\', '_', $texto);     

      $texto = str_replace(array('?' , '\\' , '"' , '(' , ')' , '!' , '$' , '%' , '[' , ']' , '{' , '}' , '\/' , '�' , '�' , '�' , '=' , '.' , ',' , ':' , ';' , '"', '\'', '#', '�', '<', '>', '^', '�', '`', '+', '�', '\'' ,'@', '�', '&', '*', '�', '�', '�', '�', '�', '_' ), '', $texto);

      $texto = trim($texto);
      $texto = str_replace(" ","_",$texto);
      $texto = strtolower($texto);

      $texto = utf8_encode($texto);

      return $texto;
   } 

   function converteUrl($texto){
      $texto = utf8_decode($texto);

      $acentos = array(
         'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
         'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
         'C' => '/&Ccedil;/',
         'c' => '/&ccedil;/',
         'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
         'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
         'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
         'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
         'N' => '/&Ntilde;/',
         'n' => '/&ntilde;/',
         'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
         'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
         'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
         'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
         'Y' => '/&Yacute;/',
         'y' => '/&yacute;|&yuml;/',
         '' => '/&ordf;/',
         '' => '/&ordm;/',
         '-' => '/&frasl;/',
         '' => '/&ordf;/'
      );
      $texto = htmlentities($texto,ENT_NOQUOTES);
      $texto = preg_replace($acentos, array_keys($acentos), $texto);

      $texto = html_entity_decode($texto, ENT_NOQUOTES);

      $texto = str_replace('/', '-', $texto);   
      $texto = str_replace('\\', '-', $texto);      

      $texto = str_replace(array('?' , '\\' , '"' , '(' , ')' , '!' , '$' , '%' , '[' , ']' , '{' , '}' , '\/' , '°' , 'º' , 'ª' , '=' , '.' , ',' , ':' , ';' , '"', '\'', '#', 'ª', '<', '>', '^', '´', '`', '+', '•', '\'' ,'@', '¨', '&', '*', '²', '³', '£', '¢', '¬', '_' ), '', $texto);

      $texto = trim($texto);
      $texto = str_replace(" ","-",$texto);
      $texto = strtolower($texto);

      $texto = utf8_encode($texto);

      return $texto;
   }

   function converteUrlBlogCategoria($texto){
      $texto = utf8_encode($texto);

      $acentos = array(
         'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
         'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
         'C' => '/&Ccedil;/',
         'c' => '/&ccedil;/',
         'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
         'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
         'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
         'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
         'N' => '/&Ntilde;/',
         'n' => '/&ntilde;/',
         'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
         'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
         'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
         'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
         'Y' => '/&Yacute;/',
         'y' => '/&yacute;|&yuml;/',
         '' => '/&ordf;/',
         '' => '/&ordm;/',
         '-' => '/&frasl;/',
         '' => '/&ordf;/'
      );
      $texto = htmlentities($texto,ENT_NOQUOTES);

      $texto = preg_replace($acentos, array_keys($acentos), $texto);

      $texto = html_entity_decode($texto, ENT_NOQUOTES);

      $texto = str_replace('/', '-', $texto);   
      $texto = str_replace('\\', '-', $texto);     

      $texto = str_replace(array('?' , '\\' , '"' , '(' , ')' , '!' , '$' , '%' , '[' , ']' , '{' , '}' , '\/' , '°' , 'º' , 'ª' , '=' , '.' , ',' , ':' , ';' , '"', '\'', '#', 'ª', '<', '>', '^', '´', '`', '+', '•', '\'' ,'@', '¨', '&', '*', '²', '³', '£', '¢', '¬', '_' ), '', $texto);

      $texto = trim($texto);
      $texto = str_replace(" ","-",$texto);
      $texto = strtolower($texto);

      $texto = utf8_encode($texto);

      return $texto;
   }

   /* limita o numero de caraceteres que sera exibido, ele verifica se nao esta cortando uma palavra */
   function limitaCaracter($entrada, $tamanho='', $pontuacao='...'){
      if(!empty($tamanho)){
         $tam = strlen($entrada)-1;
         $tamanho = $tamanho > $tam ? $tam : $tamanho;
         if($tam > $tamanho){
            while($entrada[$tamanho] != ' ' && $tamanho < $tam){
            $tamanho++; 
         }
         if($entrada[$tamanho-1] == ',' || $entrada[$tamanho-1] == '.')
            $tamanho--;
            return substr($entrada, 0, $tamanho).$pontuacao;
         }
         else{
            return $entrada;
         }  
      }else{
         return $entrada;   
      }
   }

   function enviaEmail($dados){ 
      // require '../vendor/autoload.php';
      //Instantiation and passing `true` enables exceptions
      $mail = new PHPMailer(true);
      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;              //Enable verbose debug output
      $mail->isSMTP();                                      //Send using SMTP
      $mail->Host       = 'mail.rcenter.com.br';            //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                             //Enable SMTP authentication
      $mail->Username   = 'enviosmtp@rcenter.com.br';       //SMTP username
      $mail->Password   = 'rcenter10';                      //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 587;                              //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      //Recipients
      $mail->setFrom($dados['email_remetente'], utf8_decode($dados['nome_remetente']));
      foreach ($dados['destinatario'] as $k => $v){
        $mail->addAddress($v, utf8_decode($k)); 
      }
      // $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
      // $mail->addAddress('ellen@example.com');               //Name is optional
      // $mail->addReplyTo('info@example.com', 'Information');
      // $mail->addCC('cc@example.com');
      // $mail->addBCC('bcc@example.com');

      //Attachments
      if(isset($dados['anexo']) && is_array($dados['anexo'])){
         $mail->addAttachment($dados['anexo']['pasta'], $dados['anexo']['arquivo']);
      }
      // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = utf8_decode($dados['assunto']);
      // $mail->AddEmbeddedImage("images/ababusca_direita.png", "my-attach", "rocks.png"); //Embedded image
      $mail->Body = utf8_decode($dados['texto']);
      // $mail->Body .= 'Embedded Image: <img alt="PHPMailer" src="cid:my-attach"> Here is an image!'; //Embedded image
      // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      try {
         $mail->send();
         // echo 'Message has been sent';
         return true;
      } catch (Exception $e) {
         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
         return false;
      }
   }

   function criaConsulta($texto){
      $texto = utf8_decode($texto);

      $acentos = array(
         'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
         'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
         'C' => '/&Ccedil;/',
         'c' => '/&ccedil;/',
         'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
         'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
         'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
         'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
         'N' => '/&Ntilde;/',
         'n' => '/&ntilde;/',
         'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
         'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
         'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
         'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
         'Y' => '/&Yacute;/',
         'y' => '/&yacute;|&yuml;/',
         '' => '/&ordf;/',
         '' => '/&ordm;/',
         '-' => '/&frasl;/',
         '' => '/&ordf;/'
      );

      $texto = htmlentities($texto,ENT_NOQUOTES);

      $texto = preg_replace($acentos, array_keys($acentos), $texto);

      $texto = html_entity_decode($texto, ENT_NOQUOTES);        

      $texto = trim($texto);

      $texto = utf8_encode($texto);

      return $texto;
   }

   function identifica_hyperlink($texto) {
      $texto = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/-])*", "<a href=\"\\0\" target='_blank'>\\0</a>", $texto);
      $texto = ereg_replace("(^| )(www([.]?[a-zA-Z0-9_/-])*)", "\\1<a href=\"http://\\2\" target='_blank'>\\2</a>", $texto);
      return $texto;
   }

   function get_likes($url) {
      // faz a requisição a API passando a URL como parametro
      $json_string = file_get_contents('http://graph.facebook.com/?ids='.$url);
      // usando a função json_decode e transformando em um array      
      $json = json_decode($json_string, true);
      // retorna o número de likes
      if(isset($json[$url]['shares'])){
         return intval( $json[$url]['shares'] );
      }else{
         return 0;
      }
   } 

   function mesNumerico($mes){
      switch($mes){
            case "01" : $mes = "Janeiro"; 
         break;
            case "02" : $mes = "Fevereiro"; 
         break;
            case "03" : $mes = "Março"; 
         break;
            case "04" : $mes = "Abril"; 
         break;
            case "05" : $mes = "Maio"; 
         break;
            case "06" : $mes = "Junho"; 
         break;
            case "07" : $mes = "Julho"; 
         break;
            case "08" : $mes = "Agosto"; 
         break;
            case "09" : $mes = "Setembro"; 
         break;
            case "10" : $mes = "Outubro"; 
         break;
            case "11" : $mes = "Novembro"; 
         break;
            case "12" : $mes = "Dezembro"; 
         break;
      }
      return $mes;
   }

   function ajustaData($data_geral){
      list($dia,$mes,$ano) = explode('/', $data_geral);

      switch($mes) {
         case '01': $mes_extenso = 'JAN'; break;
         case '02': $mes_extenso = 'FEV'; break;
         case '03': $mes_extenso = 'MAR'; break;
         case '04': $mes_extenso = 'ABR'; break;
         case '05': $mes_extenso = 'MAI'; break;
         case '06': $mes_extenso = 'JUN'; break;
         case '07': $mes_extenso = 'JUL'; break;
         case '08': $mes_extenso = 'AGO'; break;
         case '09': $mes_extenso = 'SET'; break;
         case '10': $mes_extenso = 'OUT'; break;
         case '11': $mes_extenso = 'NOV'; break;
         case '12': $mes_extenso = 'DEZ'; break;
      }

      $data_final = $dia." ".$mes_extenso." ".$ano;

      return($data_final);
   }

   function ajustaData2($data_geral){
      list($ano,$mes) = explode('-', $data_geral);

      switch($mes) {
         case '01': $mes_extenso = 'JANEIRO'; break;
         case '02': $mes_extenso = 'FEVEREIRO'; break;
         case '03': $mes_extenso = 'MAR&Ccedil;O'; break;
         case '04': $mes_extenso = 'ABRIL'; break;
         case '05': $mes_extenso = 'MAIO'; break;
         case '06': $mes_extenso = 'JUNHO'; break;
         case '07': $mes_extenso = 'JULHO'; break;
         case '08': $mes_extenso = 'AGOSTO'; break;
         case '09': $mes_extenso = 'SETEMBRO'; break;
         case '10': $mes_extenso = 'OUTUBRO'; break;
         case '11': $mes_extenso = 'NOVEMBRO'; break;
         case '12': $mes_extenso = 'DEZEMBRO'; break;
      }

      $data_final = $mes_extenso." ".$ano;

      return($data_final);
   }

   function resumo($texto, $tam){ 
      $tam = $tam;
      $total = strlen($texto);
      if($total > $tam){
         $palavras = explode(" ",$texto);
         $palavra = "";
         $texto = "";
         $aux = "";
         foreach($palavras as $k => $v){
            $palavra = $v;
            $aux .= " ".$palavra;
            $aux = trim($aux);
            if(strlen($aux) <= $tam){
               $texto = $aux;
            }else{
               $texto .= "...";
            break;
            }
         }  
      }
      return $texto;
   }

   function buscaFW3($dados = array()){
      include "mysql.php";
      // include_once "includes/functions.php";

      foreach ($dados as $k => &$v) {
         if (is_array($v) || $k == "colsSql") continue;
         $v = stripslashes($v);
         $v = mysqli_real_escape_string($conexao, utf8_decode($v));
      }

      //busca pelo id
      $buscaId = '';
      if (array_key_exists('idfw', $dados) && !empty($dados['idfw']))
         $buscaId = ' and C.idfw = ' . intval($dados['idfw']) . ' ';

      //busca pelo nome
      $buscaNome = '';
      if (array_key_exists('nome', $dados) && !empty($dados['nome']))
         $buscaNome = ' and C.nome LIKE "%' . $dados['nome'] . '%" ';

      //ordem
      $orderBy = "";
      if (array_key_exists('ordem', $dados) && !empty($dados['ordem'])) {
         $orderBy = ' ORDER BY ' . $dados['ordem'];
         if (array_key_exists('dir', $dados) && !empty($dados['dir'])) {
            $orderBy .= " " . $dados['dir'];
         }
      }

      //busca pelo limit
      $buscaLimit = '';
      if (array_key_exists('limit', $dados) && !empty($dados['limit']) && array_key_exists('pagina', $dados)) {
         $buscaLimit = ' LIMIT ' . ($dados['limit'] * $dados['pagina']) . ',' . $dados['limit'] . ' ';
      } elseif (array_key_exists('limit', $dados) && !empty($dados['limit']) && array_key_exists('inicio', $dados)) {
         $buscaLimit = ' LIMIT ' . $dados['limit'] . ',' . $dados['inicio'] . ' ';
      } elseif (array_key_exists('limit', $dados) && !empty($dados['limit'])) {
         $buscaLimit = ' LIMIT ' . $dados['limit'];
      }

      //colunas que serão buscadas
      $colsSql = 'C.*';
      if (array_key_exists('totalRecords', $dados)) {
         $colsSql = ' count(idservicos) as totalRecords';
         $buscaLimit = '';
         $orderBy = '';
      } elseif (array_key_exists('colsSql', $dados)) {
         $colsSql = ' ' . $dados['colsSql'] . ' ';
      }

      $buscaMax = '';
      if (array_key_exists('max', $dados))
         $buscaMax = ', max(' . $dados['max'] . ') as max ';


      $sql = "SELECT $colsSql FROM fw as C
      WHERE 1  $buscaId  $buscaNome $orderBy $buscaLimit ";

      $query = mysqli_query($conexao, $sql);
      $resultado = array();
      $iAux = 1;
      $tot =  mysqli_affected_rows($conexao);
      while ($r = mysqli_fetch_assoc($query)) {
         $r = array_map('utf8_encode', $r);
         $resultado[] = $r;
      }
      return $resultado;
   }

   function inverteStatus($dados,$tabela,$id)
   {
      include "includes/mysql.php";

      $sql = "UPDATE ".$tabela." SET status = '".$dados['status']."' WHERE ".$id." = " . $dados[$id]; 

      if (mysqli_query($conexao, $sql)) {
         return $dados[$id];
      } else {
         return false;
      }
   }

   function get_youtube_id_from_url($url){
      preg_match('/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $results);
      return $results[6];
   }

   function ConvertImage($str){
      $str = explode('.', $str);
      $str = $str[0];
      $str = str_replace(" ", "_", preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($str))));
      return mb_strtolower($str);
   }

   function ConvertToUrl($str){
      $str = str_replace(" ", "_", preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($str))));
      return mb_strtolower($str);
   }

   /**
   * @author Weiberlan
   * @param decimal $numeroFormatar vari�vel a ser formatada para gravar no banco
   * @return decimal n�mero corrigido para ser gravado no banco
   */
   function gCorrigeNumeroInverte($numeroFormatar, $tipo_moeda = ""){
      if ($tipo_moeda == "2") {
         $aux = array("R$" => "", "U$" => "", " " => "", "," => "");
      } else {
         $aux = array("R$" => "", "U$" => "", " " => "", "." => "", "," => ".");
      }
      $numeroCorrigido = strtr($numeroFormatar, $aux);
      return $numeroCorrigido;
   }

   /**
   * @author Weiberlan
   * @param decimal $numeroFormatar vari�vel para ser exibiddo ao usu�rio
   * @return decimal n�mero corrigido para ser exibiddo ao usu�rio
   */

   function gCorrigeNumero($numeroFormatar, $tipo_moeda = ""){
      if ($numeroFormatar == 0) {
         $numeroFormatar = 0;
      }
      if ($tipo_moeda == "2") {
         return number_format($numeroFormatar, 2, ".", ",");
      } else {
         return number_format($numeroFormatar, 2, ",", ".");
      }
   }


    function deleteFiles($path, $files, $names = array()) {
        if(!empty($files)){
            if(file_exists(__DIR__."/../".$path)){
                if(is_array($files)){
                    foreach ($files as $file) {
                        foreach ($names as $key => $_name) {
                            $fileToDelete = $_name.$file;
                            if(file_exists(__DIR__."/../".$path.$fileToDelete)){
                                unlink(__DIR__."/../".$path.$fileToDelete);
                            }
                        }
                    }
                }else{
                    foreach ($names as $key => $_name) {
                        $fileToDelete = $_name.$files;
                        if(file_exists(__DIR__."/../".$path.$fileToDelete)){
                            unlink(__DIR__."/../".$path.$fileToDelete);
                        }
                    }
                }
            }
        }
        return true;
    }
?>