<?php @session_start();
   $opx = $_REQUEST["opx"];

   $destinatario = 'comercial@brascloud.com.br';
      
   switch ($opx) {
      case 'newsletter':
         include_once 'includes/functions.php';
         // 
         include_once 'newsletter_class.php';
         
         $dados = $_POST;

         $idcontato = cadastroNewsletter($dados);

         $texto = '<p>Solicitou receber novidades</p>';
         // $texto .= '<p>Nome: '.ucwords(strtolower($dados['nome'])).'</p>';
         $texto .= '<p>E-mail: '.strtolower($dados['email']).'</p>';
         
         /*******************************************************
         ****         Script de Envio de Email        ****
         ********************************************************/
         
          $email = array();
                  
          $email['nome_remetente'] = ucwords(strtolower($dados['nome']));
          $email['email_remetente'] = strtolower($dados['email']);      
         
          $email['destinatario'] = array(
            'Brascloud' => $destinatario
           );
         
          $email['assunto'] = 'E-mail vindo do site';
         
          $email['texto'] = $texto;    
         
          if(enviaEmail($email)){
            echo '{"status" : true}';
          }else{
            echo '{"status" : false}';
          }
      break;

      case 'contato':
         include_once 'includes/functions.php';
         
         include_once 'contatos_class.php';
         
         $dados = $_POST;

         $idcontato = cadastroContatos($dados);

         $texto = '<p>'.$dados['assunto'].'</p>';
         $texto .= '<p>Nome: '.ucwords(strtolower($dados['nome'])).'</p>';
         $texto .= '<p>E-mail: '.ucwords(strtolower($dados['email'])).'</p>';
         $texto .= '<p>Telefone: '.strtolower($dados['telefone']).'</p>';
         if(!empty($dados['resumo_pedido'])){
            $arrResumoPedido = explode(';', $dados['resumo_pedido']);
            $texto .= '<table id="resumo-pedido" border="1">';
            $texto .= '    <tr>';
            $texto .= '        <th>Nome</th>';
            $texto .= '        <th>Velocidade</th>';
            $texto .= '        <th>Preço/h</th>';
            $texto .= '    </tr>';
            foreach($arrResumoPedido as $key => $arp){
                $arrPedido = explode('--', $arp);
                $texto .= '    <tr>';
                $texto .= '        <td>'.$arrPedido[0].'</td>';
                $texto .= '        <td>'.$arrPedido[1].'</td>';
                $texto .= '        <td>'.$arrPedido[2].'</td>';
                $texto .= '    </tr>';
            }
            $texto .= '</table>';
            $texto .= '<p>Total:'.$dados['total_pedido'].'</p>';
         }
         $texto .= '<p>Mensagem: </p>';
         $texto .= '<p>'.nl2br($dados['mensagem']).'</p>';
            
         /*******************************************************
         ****         Script de Envio de Email        ****
         ********************************************************/
         
         $email = array();
                 
         $email['nome_remetente'] = ucwords(strtolower($dados['nome']));
         $email['email_remetente'] = $destinatario;
         
         $email['destinatario'] = array(
           'Brascloud' => $destinatario
          );
         
         $email['assunto'] = 'E-mail vindo do site';
         
         $email['texto'] = $texto;    
         
         if(enviaEmail($email)){
           echo '{"status" : true}';
         }else{
           echo '{"status" : false}';
         }
      break;

      case 'contato2':
         include_once 'includes/functions.php';
         
         include_once 'contatos_class.php';
         
         $dados = $_POST;

         $idcontato = cadastroContatos($dados);

         $texto = '<p>'.$dados['assunto'].'</p>';
         $texto .= '<p>Nome: '.ucwords(strtolower($dados['nome'])).'</p>';
         $texto .= '<p>E-mail: '.ucwords(strtolower($dados['email'])).'</p>';
         $texto .= '<p>Telefone: '.strtolower($dados['telefone']).'</p>';
         $texto .= '<p>Mensagem: </p>';
         $texto .= '<p>'.nl2br($dados['mensagem']).'</p>';
            
         /*******************************************************
         ****         Script de Envio de Email        ****
         ********************************************************/
         
         $email = array();
                 
         $email['nome_remetente'] = ucwords(strtolower($dados['nome']));
         $email['email_remetente'] = $destinatario;
         
         $email['destinatario'] = array(
           'Brascloud' => $destinatario
          );
         
         $email['assunto'] = 'E-mail vindo do site';
         
         $email['texto'] = $texto;    
         
         if(enviaEmail($email)){
           echo '{"status" : true}';
         }else{
           echo '{"status" : false}';
         }
      break;

      case 'duvida':
         include_once 'includes/functions.php';
         
         include_once 'contatos_class.php';
         
         $dados = $_POST;

         $idcontato = cadastroContatos($dados);

         $texto = '<p>'.$dados['assunto'].'</p>';
         $texto .= '<p>Nome: '.ucwords(strtolower($dados['nome'])).'</p>';
         $texto .= '<p>E-mail: '.ucwords(strtolower($dados['email'])).'</p>';
         $texto .= '<p>Telefone: '.strtolower($dados['telefone']).'</p>';
         $texto .= '<p>Mensagem: </p>';
         $texto .= '<p>'.nl2br($dados['mensagem']).'</p>';
            
         /*******************************************************
         ****         Script de Envio de Email        ****
         ********************************************************/
         
         $email = array();
                 
         $email['nome_remetente'] = ucwords(strtolower($dados['nome']));
         $email['email_remetente'] = $destinatario;
         
         $email['destinatario'] = array(
           'Brascloud' => $destinatario
          );
         
         $email['assunto'] = 'E-mail vindo do site';
         
         $email['texto'] = $texto;    
         
         if(enviaEmail($email)){
           echo '{"status" : true}';
         }else{
           echo '{"status" : false}';
         }
      break;

      case 'trabalhe-conosco':
         include_once 'includes/functions.php';
         include_once 'area_pretendida_class.php';
         
         $dados = $_POST;

         $texto = '<p>Trabalhe conosco </p>';
         $texto .= '<p>Nome: '.ucwords(strtolower($dados['nome'])).'</p>';
         $texto .= '<p>E-mail: '.strtolower($dados['email']).'</p>';
         $texto .= '<p>Telefone: '.strtolower($dados['telefone']).'</p>';
         $texto .= '<p>Baixar Curriculum: '.strtolower($link).'</p>';
         $texto .= '<p>Mensagem: </p><br/>';
         $texto .= '<p>'.nl2br($dados['mensagem']).'</p>';

         /*******************************************************
         ****         Script de Envio de Email        ****
         ********************************************************/

         $email = array(); 

         $email['nome_remetente'] = ucwords(strtolower($dados['nome']));
         $email['email_remetente'] = strtolower($dados['email']);    

         $email['destinatario'] = array('Brascloud' => $destinatario);

         $email['assunto'] = 'E-mail vindo do site';

         $email['texto'] = $texto;     

         if(enviaEmail($email)){
            echo '{"status" : true}';
         }else{
            echo '{"status" : false}'; 
         }
      break;
   }
   
?>