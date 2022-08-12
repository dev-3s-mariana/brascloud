<?php include 'includes/topo.php';?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?=$seo['title']?></title>
        <meta name="author" content="<?php print $head['author']; ?>" />
        <meta name="description" content="<?=$seo['description']?>" />
        <meta name="keywords" content="<?=$seo['keywords']?>" />
        <meta name="copyright" content="<?php print $head['copyright']; ?>" />
        <meta name="title" content="<?=$seo['title']?>" />
        <meta name="robots" content="index,follow" />
        
        <base href="<?php print ENDERECO; ?>" property="og:title" />
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo ENDERECO.$_REQUEST['p']; ?>"/>
        <meta property="og:title" content="<?=$seo['title']?>"/>
        <meta property="og:image" content="<?php print ENDERECO; ?><?php echo !empty($seo['imagem']) ? $seo['imagem'] : "images/  assets/ bg-logo.png"?>"/>
        <meta property="og:description" content="<?=$seo['description']?>" />
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="./imagens/fav.png" type="image/x-icon">
        
        <!-- Fontes -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="      sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous"      referrerpolicy="no-referrer" />
        
        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=ENDERECO?>css/style.css">
        <?php $filename = $endereco.".css";
            if(file_exists("css/".$filename)) { ?>
                <link rel="stylesheet" href="<?=ENDERECO?>css/<?php echo $endereco ?>.css">
        <?php } ?>
        
        <!-- Plugins -->
        <link type="text/css" rel="stylesheet" href="js/libs/jnotify/jquery/jNotify.jquery.css" property="stylesheet" media="screen"  />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" integrity="sha512-nNlU0WK2QfKsuEmdcTwkeh+lhGs6uyOxuUs+n+0oXSYDok5qy0EI0lt01ZynHq6+p/tbgpZ7P+yUb+r71wqdXg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
        <link type="text/css" rel="stylesheet" href="js/libs/jnotify/jquery/jNotify.jquery.css" property="stylesheet" media="screen" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.7.1/slick.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.7.1/slick-theme.css">

        <?php include 'seo/analytics.php'; ?>
    </head>
    <body>
        <div class="loader">
            <div class="loader__content"></div>
        </div>