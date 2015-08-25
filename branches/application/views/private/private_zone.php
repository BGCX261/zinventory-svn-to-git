<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
        <meta name="description" content="<?php echo $meta_description; ?>" />
        <meta name="copyright" content="<?php echo $meta_copyright; ?>" />
        <link rel="shortcut icon" href="/media/ico/favicon.png"/>
        <link type="text/css" rel="stylesheet" href="/media/css/default_theme.css"/>
        <link type="text/css" rel="stylesheet" href="/media/css/default_theme_forms.css"/>
        <link type="text/css" rel="stylesheet" href="/media/css/screen.css"/>
        <script type="text/javascript" src="/media/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="/media/js/jquery.changeLanguage.js"></script>
        <script type="text/javascript" src="/media/js/jquery.timer.js"></script>
    </head>
    <body>
        <img src="/media/images/bg.jpg" alt="[Zeratul Inventory]" class="private_zone_bg"/>
        <div class="private_zone_content">
            <img src="/media/images/logo.png" width="470" height="195" alt="[Zeratul Inventory]"/>
        </div>
        <div class="private_zone_form">
            <div class="private_zone_translate">
                <label class="language_label">
                    <?php echo __("Select your language"); ?>
                </label>
                <a id="idioma_en" style="cursor: pointer;" >
                    <img src="/media/ico/en.png" alt="[<?php echo __("Inglés"); ?>]" title="[<?php echo __("Inglés"); ?>]"/>
                </a>
                <a id="idioma_es" style="cursor: pointer;">
                    <img src="/media/ico/es.png" alt="[<?php echo __("Español"); ?>]" title="[<?php echo __("Español"); ?>]"/>
                </a>
            </div>
            <?php echo $content; ?>
        </div>

    </body>
</html>
