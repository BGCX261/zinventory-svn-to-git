<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Language" content="en-us" />
        <title><?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $meta_keywords; ?>" />
        <meta name="description" content="<?php echo $meta_description; ?>" />
        <meta name="copyright" content="<?php echo $meta_copyright; ?>" />
        <link rel="shortcut icon" href="/media/ico/favicon.png"/>
        <link type="text/css" rel="stylesheet" href="/media/css/redmond/jquery-ui-1.8.6.custom.css" />
        <link type="text/css" rel="stylesheet" href="/media/css/default_theme.css" />
        <link type="text/css" rel="stylesheet" href="/media/css/default_theme_forms.css" />
        <link type="text/css" rel="stylesheet" href="/media/css/default_theme_menu.css" />
        <?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type), NULL, TRUE), "\n"; }?>
        <script type="text/javascript" language="javascript" src="/media/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/jquery-ui-1.8.6.custom.min.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/jquery.dropdown.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/jquery.changeLanguage.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/jquery.ui.zinventory.theme.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/jquery.validate.min.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/zinventory.functions.js"></script>
        <script type="text/javascript" language="javascript" src="/media/js/jquery.maskedinput-1.2.2.js"></script>
        <?php foreach($scripts as $file) { echo HTML::script($file,array('language' =>'javascript')), "\n"; }?>
        <!--script type="text/javascript" language="javascript" src="/media/js/jquery.progressbar.js"></script-->
        <script type="text/javascript">
            $(document).ready(function(){
                $('#user_info').dialog({
                    autoOpen:false,
                    closeOnEscape:true,
                    draggable:true,
                    width:400,
                    height:220,
                    modal:true,
                    resizable:false,
                    title:'<?php echo __("Información del Usuario"); ?>',
                    buttons:{
                        "<?php echo __("Aceptar") ?>":function(){
                            $(this).dialog('close');
                        }
                    }
                });

                $('#accountInfo').live('click',function(){
                    $.ajax({
                        type:'POST',
                        url:'/private/index/userInfo',
                        data:'ajax',
                        dataType:'json',
                        success:function(response){
                            $('#user_fName').text(response.user_fName);
                            $('#user_lName').text(response.user_lName);
                            $('#user_group_name').text(response.user_group_name);
                            $('#user_name_info').text(response.user_name_info);
                            $('#user_email').text(response.user_email);
                        }

                    });
                $('#user_info').dialog('open');
                    
                
            });
        });
        </script>
    </head>
    <body id="admin_bg">
        <div id="header">
            <img id="admin_mini_logo" src="/media/images/mini_logo.png" width="188" height="78" alt="[Zeratul Inventory]" title="[Zeratul Inventory]"/>
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
            <div id="sessionActions">
                <center>
                    <a id="accountInfo">
                        <?php echo __("Usuario"); ?>:
                        <?php echo $username; ?>
                    </a>
                    <a id="logout" href="/private/authentication/logout">
                        <img src="/media/ico/logout.png" alt="[<?php echo __("Cerrar Sesión"); ?>]" title="[<?php echo __("Cerrar Sesión"); ?>]"/>
                        <?php echo __("Cerrar Sesión"); ?>
                    </a>
                </center>
            </div>
        </div>
        <!--El menú se ingresa en el controller /private/index-->
        <div id="wrapper">
            <ul class="dropdown">
                <?php foreach ($menu as $mitem) {
                ?>
                            <li><a href="#"><?php echo __($mitem->name); ?></a>
                    <?php
                            if (!empty($mitem->items)) {
                                echo $mitem->printMenu($mitem);
                            } ?>
                        </li>
                <?php } ?>
                    </ul>
                </div>
                <div id="content">
                    <div id="subcontent">
                        <div id="dynamic_content">
                    <?php echo $content; ?>
                    </div>
                </div>
            </div>
            <!--div id="loader">
                <div id="loaderImg"><?php echo __("Cargando..."); ?><img src="/media/images/ajax-loader.gif" alt="[Loading_image]"/> </div>
                <br/>
                <span class="progressBar" id="pb1">0</span>
            </div-->
            <div id="subfooter">
            <?php echo __("IBusPlusPerú Todos los derechos Reservados. 2010"); ?>&nbsp;&nbsp;
        </div>

        <div id="user_info" >
            <div class="label_output">
                <div>
                    <label><?php echo __("Nombre(s)");?>:</label>
                    <div id="user_fName"></div>
                </div>
                <div>
                    <label><?php echo __("Apellidos");?>:</label>
                    <div id="user_lName"></div>
                </div>
                <div>
                    <label><?php echo __("Grupo");?>:</label>
                    <div id="user_group_name"></div>
                </div>
                <div>
                    <label><?php echo __("Usuario");?>:</label>
                    <div id="user_name_info"></div>
                </div>
                <div>
                    <label><?php echo __("Correo-e");?>:</label>
                    <div id="user_email"></div>
                </div>
            </div>


        </div>
<!--        <script type="text/javascript" language="javascript">
        pagina_activa="/private/admin/index";
        $("#pb1").progressBar();

        $("ul.sub_menu li a").live('click',function(){

            ruta = $(this).attr("rel");
            if(pagina_activa != ruta){
                $("#pb1").progressBar(0);
                $("#loader").fadeIn(300, function() {
                    $("#pb1").progressBar(100);
                    loadURL(ruta);
                    pagina_activa = ruta;
                });
            }
        });

        function loadURL(url){
            //$("#mm").remove();
            //$(".MarginTable").remove();

            //$("a.trash").die();
            //$("a.modificar").die();
            //$("#newUser").unbind("click");
            //$("#newCategory").unbind("click");


            $("#dynamic_content").load(url,function(){
                $("#loader").fadeOut(1500,function(){

                });
            });
        }
    </script>-->
    </body>
</html>