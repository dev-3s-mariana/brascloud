<?php
	function getDataImage($debug=false, $files = array(), $post = array()){
		// ini_set('memory_limit','1000MB'); // set memory to prevent fatal errors
		//Se passar $debug true na chamada da função mostra todos os arrays		
		if($debug){
			echo'<pre>';print_r($files['imagem']);echo '</pre>';
			echo'<pre>';print_r($post);echo '</pre>';
		}	
		if($post['coordenadas']){	
			$coordenadas = explode(',',$post['coordenadas']);				
			for($i =0; $i<=3; $i++){
				$coord[$i] = explode(':', $coordenadas[$i]);
				switch ($i) {
				    case 0:
				        $coordenada['x'] = $coord[$i][1];
				    break;
				    case 1:
				        $coordenada['y'] = $coord[$i][1];;
				   	break;
				    case 2:
				        $coordenada['width'] = $coord[$i][1];;
				    break;
				    case 3:
				        $coordenada['height'] = $coord[$i][1];;
				    break;
				}
				if($debug){
					echo'<pre>';print_r($coord[$i]).'<br />';echo'</pre>';
				}
			}
			if($debug){
			echo'<pre>';print_r($coordenada);echo '</pre>';
			}		
			return $coordenada;	
		}
			
	}	
	function resizeImage($filepathR, $nw, $nh, $typeR){
		//ini_set('memory_limit','1000MB'); // set memory to prevent fatal errors
		ini_set('max_execution_time','600'); 
		switch($typeR){
			case 'image/gif':
				$image_CreateR = "imagecreatefromgif";
				$imageR = "imagegif";
			break;
			//resize and crop image by center
			case 'image/png':
				$image_CreateR = "imagecreatefrompng";
				$imageR = "imagepng";
				//$qualityR = 10;
			break;
			//resize and crop image by center
			case 'image/jpeg':
				$image_CreateR = "imagecreatefromjpeg";
				$imageR = "imagejpeg";
				//$qualityR = 100;
			break;
		
		}
		$filename = $filepathR;
		list($width, $height) = getimagesize($filename);
		$new_width = $nw;
		$new_height = $nh;
		// Resample

		$image_p = imagecreatetruecolor($new_width, $new_height);
		imagealphablending($image_p, false);
		imagesavealpha($image_p, true);
		$image = $image_CreateR($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		// Output
		switch($typeR){
			case 'image/gif':
				$imageR($image_p, $filename);
				
			break;
			//resize and crop image by center
			case 'image/png':
				$imageR($image_p, $filename, 9);
			break;
			//resize and crop image by center
			case 'image/jpeg':
				$imageR($image_p, $filename, 92);
			break;
		
		}
		return;
		
	}
	function cropImage($type, $arquivo, $w, $h, $x, $y){
		switch($type){
		case 'image/gif':
			$image_create = "imagecreatefromgif";
			$image = "imagegif";
		break;
		//resize and crop image by center
		case 'image/png':			
		
			$image_create = "imagecreatefrompng";
			$image = "imagepng";
			$quality = 10;
		break;
		//resize and crop image by center
		case 'image/jpeg':
			$image_create = "imagecreatefromjpeg";
			$image = "imagejpeg";
			$quality = 92;
		break;
		
		}
		// Original image
		$filename = $arquivo;

		// Get dimensions of the original image
		list($current_width, $current_height) = getimagesize($filename);

		// The x and y coordinates on the original image where we
		// will begin cropping the image
		$left = $x;
		$top = $y;

		// This will be the final size of the image (e.g. how many pixels
		// left and down we will be going)
		$crop_width = $w;
		$crop_height = $h;

		// Resample the image			
		$canvas = imagecreatetruecolor($crop_width, $crop_height);
		$current_image = $image_create($filename);
		imagealphablending($canvas, false);
		imagesavealpha($canvas, true);
		imagecopy($canvas, $current_image, 0, 0, $left, $top, $current_width, $current_height);
		switch($type){
		case 'image/gif':
			$image($canvas, $filename);

		break;
		//resize and crop image by center
		case 'image/png':
			$image($canvas, $filename, 9);
		break;
		//resize and crop image by center
		case 'image/jpeg':
			$image($canvas, $filename, 92);
		break;
		
		}
		return;

	}
	function dynamicSize($w, $h, $iw, $ih, $type){
		if($type == 'height'){//configura o novo height
			$newheight = ($w*$ih/$iw);
			return $newheight;

		}else{//configura o novo width
			$new_width = ($iw*$h/$ih);
			return $new_width;
		}
		

	}

	function coordCenter($wcoord, $hcoord, $iwcoord, $ihcoord, $typecoord){
		
		if($typecoord == 'height'){			
			$y = $hcoord/2;			
			$centroY = $ihcoord/2;
			$coord = array();
			$y = $centroY-$y;
			return $y;


		}else{
			$x = $wcoord/2;	
			//echo $x.'<br>';		
			$centroX = $iwcoord/2;
			//echo $centroX.'<br>';				
			$x = $centroX-$x;
			return $x;
		}
		
		//return $coord;

	}
	function createThumb($modulo, $pasta, $pastaopcao, $coordenadas = array(), $imagem = array(), $width, $height, $cropAuto = false, 
		$nomeUnico = false, $tamanhoOriginal = false, $apagaArquivoTemporario = false, $nomeImagem = '', $resizeAll = false){		

	 
		//$pasta = 'files/depoimentos/'.$id.'/';
		$pastamodulo = 'files/'.$modulo.'/';
		$caminho = $pastamodulo.$pasta.'/';
		$caminhofinal = $caminho.$pastaopcao.'/'; 
 
		
		 
		if($nomeImagem != ''){
			$imagem['name'] = $nomeImagem;	
		}else{
			$name = $imagem['name'];

			$ext = explode(".", $name);
			$ext = $ext[count($ext) - 1];
			$imagem['name'] = md5($imagem['name']) . ".".$ext;
			$nomeImagem = $imagem['name'];
		}	

		if($nomeUnico){
			$imagem['name'] = md5(uniqid(rand(), true)).".".$ext;	
		}
		
		
		if(!file_exists($pastamodulo )){
			mkdir($pastamodulo, 0777, true);
		}
		
		if(!file_exists($caminho)){
			mkdir($caminho, 0777, true);
		}
		if(!file_exists($caminhofinal)){
			mkdir($caminhofinal, 0777, true);
		}
		
		//echo $imagem['tmp_name'].' ';
		if(copy($imagem['tmp_name'], $caminhofinal.$imagem['name'])){			
			if($tamanhoOriginal == false){
				//Lê a imagem na pasta que foi salva
				$readimage = getimagesize($caminhofinal.$imagem['name']);
				//width
				$imagew = $readimage[0];
				//heigth 
				$imageh = $readimage[1]; 
						
				if($imagew<$width  && $resizeAll==false  || $imageh<$height && $resizeAll==false){
					unlink($caminhofinal.$imagem['name']);
					//echo 'oi';
					return false;

				}else{
					if($cropAuto){ //CROPAUTO	
						if($imagew<$width && $resizeAll==true || $imageh<$height && $resizeAll==true){
							//Redimensiona para um tamanho maior se for necessário
							$readimage = getimagesize($caminhofinal.$imagem['name']);				
							//width
							$imagew = $readimage[0];
							//heigth 
							$imageh = $readimage[1]; 
							//imagem quadrada
							if($imagew==$imageh){

								if($height>$width){
									resizeImage($caminhofinal.$imagem['name'], $height, $height, $imagem['type']);
								}else{
									resizeImage($caminhofinal.$imagem['name'], $width, $width, $imagem['type']);
								}								
							}								
						}

						if($imagew<$imageh){//se a largura for menor que a altura, vai ser dimensionado pela largura 
							$nheight = dynamicSize($width, $height, $imagew, $imageh, 'height');					
							if($nheight<$height){
								$new_width = $width*$height/$nheight ;
								resizeImage($caminhofinal.$imagem['name'], $new_width, $height, $imagem['type']);
								//Lê a imagem redimensionadas
								//Lê a imagem redimensionadas
								$readimage = getimagesize($caminhofinal.$imagem['name']);				
								//width
								$imagew = $readimage[0];
								//heigth 
								$imageh = $readimage[1]; 					
								//Coordenada y para o corte da imagem redimensionada	
								$x = coordCenter($width, $height,$imagew,$imageh,'width');
								//CROP
								cropImage($readimage['mime'],$caminhofinal.$imagem['name'], $width, $height, $x, 0);
							}else{
								resizeImage($caminhofinal.$imagem['name'], $width, $nheight, $imagem['type']);				
								//Lê a imagem redimensionadas
								$readimage = getimagesize($caminhofinal.$imagem['name']);				
								//width
								$imagew = $readimage[0];
								//heigth 
								$imageh = $readimage[1]; 					
								//Coordenada y para o corte da imagem redimensionada	
								$y = coordCenter($width, $height,$imagew,$imageh,'height');
								//CROP
								cropImage($readimage['mime'],$caminhofinal.$imagem['name'], $width, $height, 0, $y);
							}			
							
						}else if($imagew>$imageh){//se a altura for menor que a largura, vai ser dimensionado pela altura					
							$nwidth = dynamicSize($width, $height, $imagew, $imageh, 'width');					
							if($nwidth<$width){
								$new_height = $width*$height/$nwidth ;
								resizeImage($caminhofinal.$imagem['name'], $width, $new_height, $imagem['type']);
								//Lê a imagem redimensionadas
								//Lê a imagem redimensionadas
								$readimage = getimagesize($caminhofinal.$imagem['name']);				
								//width
								$imagew = $readimage[0];
								//heigth 
								$imageh = $readimage[1]; 					
								//Coordenada y para o corte da imagem redimensionada	
								$y = coordCenter($width, $height,$imagew,$imageh,'height');
								//CROP
								cropImage($readimage['mime'],$caminhofinal.$imagem['name'], $width, $height, 0, $y);	

								//echo 'outro resize';
							}else{
								resizeImage($caminhofinal.$imagem['name'], $nwidth , $height, $imagem['type']);
								//Lê a imagem redimensionadas
								//Lê a imagem redimensionadas
								$readimage = getimagesize($caminhofinal.$imagem['name']);				
								//width
								$imagew = $readimage[0];
								//heigth 
								$imageh = $readimage[1]; 					
								//Coordenada y para o corte da imagem redimensionada	
								$x = coordCenter($width, $height,$imagew,$imageh,'width');
								//CROP
								cropImage($readimage['mime'],$caminhofinal.$imagem['name'], $width, $height, $x, 0);	
							}	
							
						}else{
							if($width>$height){		
							//echo 'oi';					
								resizeImage($caminhofinal.$imagem['name'], $width, $width, $imagem['type']);
								//Lê a imagem redimensionadas
								//Lê a imagem redimensionadas
								$readimage = getimagesize($caminhofinal.$imagem['name']);				
								//width
								$imagew = $readimage[0];
								//heigth 
								$imageh = $readimage[1]; 					
								//Coordenada y para o corte da imagem redimensionada	
								$y = coordCenter($width, $height,$imagew,$imageh,'height');
								//CROP
								cropImage($readimage['mime'],$caminhofinal.$imagem['name'], $width, $height, 0, $y);
							}else{
								//echo '2';
								resizeImage($caminhofinal.$imagem['name'], $height, $height, $imagem['type']);
								//Lê a imagem redimensionadas
								//Lê a imagem redimensionadas
								$readimage = getimagesize($caminhofinal.$imagem['name']);				
								//width
								$imagew = $readimage[0];
								//heigth 
								$imageh = $readimage[1]; 					
								//Coordenada y para o corte da imagem redimensionada	
								$x = coordCenter($width, $height,$imagew,$imageh,'width');
								//CROP
								cropImage($readimage['mime'],$caminhofinal.$imagem['name'], $width, $height, $x, 0);	
							}
						}
					}else{//CROP COM COORDENADAS FIXAS
						
						cropImage($imagem['type'],$caminhofinal.$imagem['name'], $coordenadas['width'], $coordenadas['height'], $width, $height);			
						resizeImage($caminhofinal.$imagem['name'], $width, $height, $imagem['type']);
					}
					
				}
			}
			if($apagaArquivoTemporario){
				unlink($imagem['tmp_name']);
			}
			return $imagem['name'];					
		}else{ 
			return false;
		}		
	}


?>