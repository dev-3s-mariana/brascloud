<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Previas de Arte</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- Save for Web Styles (index.psd) -->
<style type="text/css">

body{
	
	<?php 
	
	if(isset($_REQUEST["imagem"])){
		list($largura, $altura) = getimagesize("images/".$_REQUEST["imagem"]);
		
		echo "
	background-color:000000;
	background-image:url('images/".$_REQUEST["imagem"]."');
	background-position: center 0;
	background-repeat:no-repeat;
	
	height: ".$altura."px;
	";
	
	}
	
	?>
	

}


</style>


</head>
<body>
<?php

	if(@!isset($_REQUEST["imagem"])){
		
	   $path = "images/";

	   $diretorio = dir($path);
		
	   echo "Previas de Arte <br/><br/>";     
	   while($arquivo = $diretorio -> read()){
		
			$nome = explode(".",($arquivo));
		   	if( (!is_dir($arquivo)) && ($nome[1] == "jpg" || $nome[1] == "png")  ){
		   		$files[] = $arquivo;
			} 
	   }

	   $diretorio -> close();
 		asort($files);
	   foreach($files as $arquivo){
	   			$nome = explode(".",($arquivo));
				echo "<a href='?imagem=".$arquivo."'>".ucfirst($nome[0])." </a> - ".date ("d/m/Y  - H:i:s", filemtime($path.$arquivo))."<br />";
		
	   }
  
	}
?>


</body>
</html>