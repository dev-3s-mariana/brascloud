<?php
	
	clearstatcache();
	

	if(isset($_FILES['Filedata'])){
		
		$arquivo = $_FILES['Filedata'];
		
		$dirDestino = $_POST['dirdestino'];
		$dirThumbs = $dirDestino.'thumbs/';
		
		@$larguraOriginal = $_POST['largura'];
		@$alturaOriginal = $_POST['altura'];
		
		@$larguraThumb = $_POST['largurathumb'];
		@$alturaThumb = $_POST['alturathumb'];


		
	}else{
		$arquivo['error'] = 1;
	}
	
	
	
	
	if($arquivo['error'] == 0){
		$tmp = explode('.',$arquivo['name']);
		$extensao = end($tmp);		
		
		
		$para = true;
		$i=0;	
		while($para){
				$i++;
				$tmpNome = time().$i;
				if(!file_exists($dirDestino.md5($tmpNome).'.'.strtolower($extensao))){
					$nomeArquivo = md5($tmpNome).'.'.strtolower($extensao);
					$para = false;
				}				
		}
		
		
		
		if(move_uploaded_file($arquivo['tmp_name'], $dirDestino.$nomeArquivo)){
			include('includes/m2brimagem.php');			
			
			chmod($dirDestino.$nomeArquivo, 0777);
			
			$oImg = new m2brimagem();		
			
			
			// cria o thumb
			// se a largura for menor que a altura, vai ser dimensionado pela largura ou vice-versa
			$oImg->carrega($dirDestino.$nomeArquivo);
			$valida = $oImg->valida();			
			if ($valida == 'OK') {				
				$dimensoes = $oImg->getDimensoes();
				
				//faz regra de 3 pra verificar por onde que corta
				$testeDeCorte = ($larguraThumb * $dimensoes['altura']) / $dimensoes['largura'];
				
				if($testeDeCorte >= $alturaThumb){
					$oImg->redimensiona(0, $alturaThumb);		
				}else{
					$oImg->redimensiona($larguraThumb, 0);				
				}
											
				$oImg->grava($dirThumbs.$nomeArquivo,100);	
			} 
			

		
			
			
	
			// cria a imagem
			// se a largura for menor que a altura, vai ser dimensionado pela largura ou vice-versa
			$oImg->carrega($dirDestino.$nomeArquivo);
			$valida = $oImg->valida();			
			if ($valida == 'OK') {				
				$dimensoes = $oImg->getDimensoes();
				
				//faz regra de 3 pra verificar por onde que corta
				$testeDeCorte = ($larguraOriginal * $dimensoes['altura']) / $dimensoes['largura'];
				
				if($testeDeCorte >= $alturaOriginal){
					$oImg->redimensiona(0, $alturaOriginal);		
				}else{
					$oImg->redimensiona($larguraOriginal, 0);				
				}
				
		
				$oImg->grava($dirDestino.$nomeArquivo,100);	
			} 
			
				
			
			
			
			print $nomeArquivo;
				
				
		}
	}
	
	
?>