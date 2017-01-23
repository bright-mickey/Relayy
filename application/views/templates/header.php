<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      <title><?php if(isset($page_title)) echo $page_title; ?></title>
      <!--reset styles-->
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/bootstrap.min.css" type="text/css">
      <link rel="shortcut icon" href="<?= asset_base_url()?>/images/favicon.png">
   </head>
   <body class="<?php if(isset($body_class)) echo $body_class; ?>">
        <div id="color_bar"></div>
        <header id="header" class="container">
            <a href="<?php echo site_url() ?>"><img src="<?= asset_base_url()?>/images/logo.jpg" width="200" style="float:left;"/></a>
            <div class="tagline mod">
                <span class="text">Create workgroups.<br>Talk business.</span>
                <a class="button" href="<?php echo site_url('home') ?>/login">Sign In</a>
            </div>
        </header>
        <div class="shadow"></div>
      