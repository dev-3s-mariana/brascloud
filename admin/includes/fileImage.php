<?php
require_once 'includes/wideimage/WideImage.php';

//$path = pasta do arquivo;
//$name = nome do arquivo - se vazio será criado o nome;
//$tipo = o tipo da imagem - thumb, thumb2 - estaram na mesma pasta, entao incluir no nome nomearquivothumb.png

function fileImage($path, $name, $tam = '', $fileImage = array(), $width, $height, $tipo = 'original', $coordenadas = ''){

	$path = "files/".$path;

	if (!file_exists($path)) {
		mkdir($path,0777,true);
	}

	$path = $path."/";

	if(empty($name)){
		$fileName = rand(0,6000).rand(0,1000).sanitizeString(substr($fileImage['name'], 0, strrpos($fileImage['name'], '.')));
		$extension = getExt($fileImage['tmp_name']);
		$name = $fileName.".".$extension;
		$fileName = $path.$fileName.".".$extension;
		$image = array();
	}else{
		//renomeia o nome do arquivo com thumb no inicio
	
		if(!empty($_SESSION['produto_auxiliar_session'])){
			$name = $tam."".$name;
		}else{
			$name = $tam."_".$name;
		}

	 	$_SESSION['produto_auxiliar_session'] = false;

		
		$fileName = $path.$name;
		$extension = getExt($fileImage['tmp_name']);
	}



	if(copy($fileImage['tmp_name'],$fileName)){

		$img = WideImage::load($fileName);

		if($tipo == "crop"){
			$img = $img->resize($width, $height, 'outside');
			$img = $img->crop("center", "center", $width, $height);
		}else if($tipo == "cropped"){

			$wOriginal = $img->getWidth();
			$hOriginal = $img->getHeight();

			if($wOriginal > 3000 || $hOriginal > 3000){
				$img = $img->resize($width, $height, 'outside');

				$wNew = $img->getWidth();
				$hNew = $img->getHeight();

				$pW = ($wNew * 100) /$wOriginal;
				$pH = ($hNew * 100) /$hOriginal;

				$coordenadas['x'] = ($coordenadas['x'] * $pW) /100;
				$coordenadas['y'] = ($coordenadas['y'] * $pH) /100;

				$coordenadas['width'] = ($coordenadas['width'] * $pW) /100;
				$coordenadas['height'] = ($coordenadas['height'] * $pH) /100;
			}

			$img = $img->crop($coordenadas['x'], $coordenadas['y'], $coordenadas['width'],$coordenadas['height']);
			$img = $img->resize($width, $height, 'outside');
			$w = $img->getWidth();
			$h = $img->getHeight();
			$x = 0;
			$y = 0;
			$crop = false;
			if($w > $width){
				$x = ($w - $width) / 2;
				$crop = true;
			}
			if($h > $height){
				$y = ($h - $height) / 2;
				$crop = true;
			}
			if($crop){
				$img = $img->crop($x, $y, $width, $height);
			}
		}
		else if($tipo == "resize"){
			$img = $img->resize($width, $height, 'outside');
		}
		else if($tipo == "inside"){
			$img = $img->resizeDown($width, $height, 'inside');
		}
		else if($tipo == "inside2"){
			$white = $img->allocateColor(255, 255, 255);
			$img = $img->resizeDown($width, $height, 'inside');
			if($extension == "png" || $extension == "gif"){
				$img = $img->resizeCanvas($width, $height, 'center', 'center');
			}else{
				$img = $img->resizeCanvas($width, $height, 'center', 'center', $white);
			}
		}

		if($extension == "jpg" || $extension == "jpeg"){
			$img->saveToFile($fileName,100);
		}else if($extension == "png"){
			$img->saveToFile($fileName, 8);
		}else{
			$img->saveToFile($fileName);
		}

		$img->destroy();

		return $name;
	}else{
		return false;
	}
}

function fileImageNew($path, $id, $newFolder = false, $newFolder2 = false, $name = '', $fileImage = array(), $width, $height, $tipo = 'original', $coordenadas = ''){

	$path = "files/".$path;
	$pathThumb = "{$path}/{$id}/";

	if (!file_exists($path)) {
		mkdir($path,0777);
	}

	if (!file_exists($pathThumb)) {
		mkdir($pathThumb,0777);
	}

	$path = $pathThumb;

	if (!empty($newFolder)) {
		$path = "{$path}{$newFolder}/";
		if (!file_exists($path)) {
			mkdir($path,0777);
		}
	}

	if (!empty($newFolder2)) {
		$path = "{$path}{$newFolder2}/";
		if (!file_exists($path)) {
			mkdir($path,0777);
		}
	}

	if(empty($name)){
		$fileName = rand(0,1000).sanitizeString(substr($fileImage['name'], 0, strrpos($fileImage['name'], '.')));
		$extension = getExt($fileImage['tmp_name']);
		$name = $fileName.".".$extension;
		$fileName = $path.$fileName.".".$extension;
		$image = array();
	}else{
		$fileName = $path.$name;
		$extension = getExt($fileImage['tmp_name']);
	}

	if(copy($fileImage['tmp_name'],$fileName)){

		$img = WideImage::load($fileName);

		if($tipo == "crop"){
			$img = $img->resize($width, $height, 'outside');
			$img = $img->crop("center", "center", $width, $height);
		}
		else if($tipo == "cropped"){

			$wOriginal = $img->getWidth();
			$hOriginal = $img->getHeight();

			if($wOriginal > 3000 || $hOriginal > 3000){
				$img = $img->resize($width, $height, 'outside');

				$wNew = $img->getWidth();
				$hNew = $img->getHeight();

				$pW = ($wNew * 100) /$wOriginal;
				$pH = ($hNew * 100) /$hOriginal;

				$coordenadas['x'] = ($coordenadas['x'] * $pW) /100;
				$coordenadas['y'] = ($coordenadas['y'] * $pH) /100;

				$coordenadas['width'] = ($coordenadas['width'] * $pW) /100;
				$coordenadas['height'] = ($coordenadas['height'] * $pH) /100;
			}

			$img = $img->crop($coordenadas['x'], $coordenadas['y'], $coordenadas['width'],$coordenadas['height']);
			$img = $img->resize($width, $height, 'outside');
			$w = $img->getWidth();
			$h = $img->getHeight();
			$x = 0;
			$y = 0;
			$crop = false;
			if($w > $width){
				$x = ($w - $width) / 2;
				$crop = true;
			}
			if($h > $height){
				$y = ($h - $height) / 2;
				$crop = true;
			}
			if($crop){
				$img = $img->crop($x, $y, $width, $height);
			}
		}
		else if($tipo == "resize"){
			$img = $img->resize($width, $height, 'outside');
		}
		else if($tipo == "inside"){
			$img = $img->resizeDown($width, $height, 'inside');
		}

		if($extension == "jpg" || $extension == "jpeg"){
			$img->saveToFile($fileName,100);
		}else if($extension == "png"){
			$img->saveToFile($fileName, 8);
		}else{
			$img->saveToFile($fileName);
		}

		$img->destroy();

		return $name;
	}else{
		return false;
	}
}

function fileImageCropGaleria($path, $fileImage, $name = '', $width, $height, $coordenadas = ''){

	$fileName = $path.$name;
	if(file_exists($fileName)){
		unlink($fileName);
	}
	if(copy($fileImage, $fileName)){

		$img = WideImage::load($fileName);

		$wOriginal = $img->getWidth();
		$hOriginal = $img->getHeight();

		/*$img = $img->resize($width, $height, 'outside');

		$wNew = $img->getWidth();
		$hNew = $img->getHeight();

		$pW = ceil(($wNew * 100) /$wOriginal);
		$pH = ceil(($hNew * 100) /$hOriginal);

		$coordenadas['x'] = ($coordenadas['x'] * $pW) /100;
		$coordenadas['y'] = ($coordenadas['y'] * $pH) /100;

		$coordenadas['width'] = ($coordenadas['width'] * $pW) /100;
		$coordenadas['height'] = ($coordenadas['height'] * $pH) /100;*/

		$img = $img->crop($coordenadas['x'], $coordenadas['y'], $coordenadas['width'],$coordenadas['height']);
		$img = $img->resize($width, $height, 'outside');
		$w = $img->getWidth();
		$h = $img->getHeight();
        
        $x = 0;
        $y = 0;
        
		$crop = false;
		if($w > $width){
			$x = ($w - $width) / 2;
			$crop = true;
		}
		if($h > $height){
			$y = ($h - $height) / 2;
			$crop = true;
		}
		if($crop){
			$img = $img->crop($x, $y, $width, $height);
		}

		$img->saveToFile($fileName);
		$img->destroy();
		return $name;
	}else{
		return false;
	}
}


function sanitizeString($str) {
	$str = md5($str);
	return $str;
}

function getExt($dir)
{
	$fileImage = getimagesize($dir);
	$extension = array('image/gif' => 'gif', 'image/png' => 'png', 'image/jpg' => 'jpg', 'image/jpeg' => 'jpg');
	return $extension[$fileImage['mime']];
}

function getDataImageCrop($files = array(), $coordenadas = array()){

	if($coordenadas){
		$coordenadas = explode(',',$coordenadas);
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
		}
		return $coordenada;
	}

}

?>	