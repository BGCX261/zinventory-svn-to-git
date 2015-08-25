<script type="text/javascript">
    $(document).ready(function(){
        //INICIO VALIDADOR
        var validator = $("#frmMenu").validate({
            debug:true,
            rules: {
                menu_name: {
                    required: true,
                    minlength:3
                },
                menu_url:{
                    minlength:3,
                    required: true
                }
            },
            messages:{
                menu_name: {
                    required: 'El nombre del menú es obligatorio',
                    minlength:jQuery.format('Se requiere al menos {0} caracteres para el nombre del menú')
                },
                menu_url:{
                    minlength:jQuery.format('Se requiere al menos {0} caracteres para la ruta del menú'),
                    required: 'La ruta del menú es obligatoria'
                }
            },
            errorContainer:'#errorMessages',
            errorLabelContainer: "#errorMessages .content .text #messageField",
            wrapper: "p",
            highlight: function(element, errorClass) {
                $("#"+element.id).addClass('validation_error');
            },
            unhighlight:function(element, errorClass, validClass){
                $("#"+element.id).removeClass('validation_error');
            },
            success: function(label) {
                //label.text("ok!").addClass("success");
            }
        });
        //FIN VALIDADOR
        
        //INICIO DATATABLE MENU
        var tblMenu = $('#tblMenu').dataTable({
            "bJQueryUI": true,
            "bSort": false,
            "bPaginate": false,
            "bFilter":false
        });
        //FIN DATATABLE MENU

        var titlePopup = $('.titleForm').text();
        
        //INICIO POPUP NUEVA OPCION DE MENU
        $('#frmPopup').dialog({autoOpen:false,width:500,height:220,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmMenu').valid())){
                        return false;
                    }
                    var frm = $('#frmMenu').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/menu/createOrUpdateMenu',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/menu/listMenus',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblMenu.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblMenu(this);
                                            $('#frmPopup').dialog('close');
                                        });
                                    }

                                });
                                
                            } else {
                                alert(msg);
                            }
                        }
                    });
                },
                "<?php echo __("Cancelar"); ?>": function() {
                    $(this).dialog('close');
                    $('#errorMessages').hide();
                }
            }

        });
        //FIN POPUP NUEVA OPCION DE MENU

        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var idMenu = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "idMenu=" + idMenu;
                    $.ajax(
                    {
                        type: "POST",
                        url: url,
                        data:dataString,
                        dataType:'html',
                        success: function(response)
                        {
                            if (response == 1)
                            {
                                $.ajax(
                                {
                                    type: "POST",
                                    url: '/private/menu/listMenus',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {
                                        tblMenu.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblMenu(this);
                                        });
                                    }
                                });
                            }else{
                                alert('<?php echo __("No fue posible eliminar el registro"); ?>');
                            }
                        }
                    });
                }
            });
            return false;
        });
        //FIN ELIMINAR

        //INICIO CLICK NUEVO MENU
        $("#btnNewMenu").click(function(){
            validator.resetForm();
            $('.frmSubtitle').text('<?php echo __("Nuevo Menú"); ?>');
            $('.superMenu').hide();
            $("#menu_name").val('');
            $('#idMenu').val(0);
            $('#idSuperMenu').val(0);
            $('#menu_url').attr("readonly", true);
            $('#menu_url').val("***");
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK MENU NUEVO

        //INICIO CLICK EDITAR MENU
        $('a.edit').live('click',function(){
            validator.resetForm();
            var idMenu = $(this).attr("rel");
            var dataString = "idMenu=" + idMenu;
            var readOnly = ($("tr[id][id$='menu"+idMenu.toString()+"']").attr('title')=='');
            setSuperMenu(idMenu);
            $.ajax({
                type:"POST",
                url:'/private/menu/findMenuById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $('.frmSubtitle').text('<?php echo __("Editar Menú"); ?>');
                    $('#menu_name').val(r.name);
                    $('#idMenu').val(r.idMenu);
                    $('#menu_url').attr("readonly",readOnly);
                    $('#menu_url').val(r.url);
                    $('#menu_type').val(r.type);
                    $('#frmPopup').dialog('open');
                    
                }
            });
        });
        //FIN CLICK EDITAR MENU

        //INICIO CLICK NUEVO SUBMENU
        $('a.newSubMenu').live('click',function(){
            validator.resetForm();
            var idMenu = $(this).attr("rel");
            var text_superMenu = $("tr[id][id$='menu"+idMenu.toString()+"'] td:first-child").text();
            $('.frmSubtitle').text('<?php echo __("Nuevo Submenu"); ?>');
            $('.superMenu').show();
            $('#idSuperMenu').val(idMenu);
            $('.superMenu input').attr('value',jQuery.trim(text_superMenu));
            $("#menu_name").val('');
            $('#idMenu').val(0);
            $('#menu_url').attr("readonly", false);
            $('#menu_url').val("");
            $('#menu_type').val("M");
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVO SUBMENU
        
        //INICIO CLICK NUEVA ACCION PHP
        $('a.newPHPAction').live('click',function(){
            validator.resetForm();
            var idMenu = $(this).attr("rel");
            var text_superMenu = $("tr[id][id$='menu"+idMenu.toString()+"'] td:first-child").text();
            $('.frmSubtitle').text('<?php echo __("Nueva Acción PHP"); ?>');
            $('.superMenu').show();
            $('#idSuperMenu').val(idMenu);
            $('.superMenu input').attr('value',jQuery.trim(text_superMenu));
            $("#menu_name").val('');
            $('#menu_url').attr("readonly", false);
            $('#idMenu').val(0);
            $('#menu_url').val("");
            $('#menu_type').val("A");
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVA ACCION PHP
        
        function setSuperMenu(idMenu){
            var parents = $("tr[id][id$='menu"+idMenu.toString()+"']").attr('title');
            var parent_array = parents.split(';')
            var inmediate_idMenuParent = '0';
            if(parent_array.length>1){
                var idx_inmediate_idMenuParent = parent_array.length-2;
                inmediate_idMenuParent = parent_array[idx_inmediate_idMenuParent];
                $('.superMenu').show();
                $('#idSuperMenu').val(inmediate_idMenuParent);
                var text_superMenu = $("tr[id][id$='menu"+inmediate_idMenuParent.toString()+"'] td:first-child").text();
                $('.superMenu input').attr('value',jQuery.trim(text_superMenu));
            } else{
                $('#idSuperMenu').val(0);
                $('.superMenu').hide();
            }
        }

        //INICIO CLICK EN OPEN NODE
        $('div.open_node').live('click',function(){
            var idMenu =  $(this).attr("id");
            var dataString = 'idMenu='+idMenu;
            var marginLeft = $(this).css('margin-left');
            marginLeft = marginLeft.substr(0, (marginLeft.length-2));
            var styleClass = $(('#menu'+idMenu).toString()).attr('class');
            var titleParentNode =  $(('#menu'+idMenu).toString()).attr("title");
            if(titleParentNode==''){
                titleParentNode = ';'+idMenu;
            }
            var valMarginLeft = eval(marginLeft)+25;
            var idCurrentRow = idMenu;
            $.ajax({
                type:"POST",
                url:'/private/menu/listSubMenu',
                data:dataString,
                dataType:'json',
                success: function(response){

                    $.each(response, function(){
                        var thisRow = this;
                        var nodeClass = 'disable_node';
                        var dataString = 'idMenu='+this.idMenu;
                        if(thisRow.type=='M'){
                            $.ajax({
                                type:"POST",
                                url:'/private/menu/isSuperMenu',
                                data:dataString,
                                dataType:'html',
                                success: function(r){
                                    if(r==1){
                                        nodeClass = 'open_node';
                                    }
                                    addMenuChild(idCurrentRow,idMenu,thisRow,titleParentNode,nodeClass,styleClass,valMarginLeft);
                                }
                            });
                        } else {
                            nodeClass='php_action_node'
                            addMenuChild(idCurrentRow,idMenu,thisRow,titleParentNode,nodeClass,styleClass,valMarginLeft);
                        }

                        
                    });
                }
            });
            return false;
        });
        //FIN CLICK EN OPEN NODE

        //INICIO CLICK EN CLOSE NODE
        $('div.close_node').live('click',function(){
            var idMenu =  $(this).attr("id");
            $('tr[title*=;'+idMenu+';'+']').remove();
            $(('#'+idMenu).toString()).removeClass('close_node');
            $(('#'+idMenu).toString()).addClass('open_node');

        });
        //FIN CLICK EN CLOSE NODE

        //INICIO AGREGA FILA
        function subMenuOnlyOptions(obj){
            if(obj.type=='A'){
                return '';
            }
            return '<a  rel="'+obj.idMenu+'" class="newSubMenu" style="cursor: pointer;">\n\
                    <img border="0" src="/media/ico/new_menu.png" alt="[<?php echo __("Nuevo Submenu"); ?>]" title="[<?php echo __("Nuevo SubMenu"); ?>]"/>\n\
                 <a rel="'+obj.idMenu+'" class="newPHPAction" style="cursor: pointer;">\n\
                    <img border="0" src="/media/ico/php.png" alt="[<?php echo __("Nueva Acción PHP"); ?>]" title="[<?php echo __("Nueva Acción PHP"); ?>]"/>\n\
                                        </td>\n\</tr>';
                            }
                            //FIN ONLY
                            //INICIO AGREGA FILA
                            function addRowTblMenu(obj){
                                var subMenuOptions = subMenuOnlyOptions(obj);

                                tblMenu.fnAddData( [
                                    '<div id="'+obj.idMenu+'" class="open_node" style="margin-left:0px;"></div>'+obj.name,
                                    obj.url,
                                    '<a style="cursor: pointer;" class="edit" rel="'+obj.idMenu+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a rel="'+obj.idMenu+'" href="/private/menu/removeMenu" class="trash">\n\
                    <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>\n\
                 </a>'+subMenuOptions] );
                                var lastRowIndex= tblMenu.fnGetNodes().length;
                                $('#tblMenuContent tr:nth-child('+lastRowIndex+')').attr('id','menu'+obj.idMenu);
                            }
                            //FIN AGREGA FILA
                            function addMenuChild(idCurrentRow,idMenu,thisRow,titleParentNode,nodeClass,styleClass,valMarginLeft){
                                var subMenuOptions = subMenuOnlyOptions(thisRow);
                                $(('#menu'+idCurrentRow).toString()).after(
                                '<tr id="menu'+thisRow.idMenu+'" title="'+titleParentNode+';'+thisRow.idMenu+'" class="'+styleClass+'">\n\
                                            <td>\n\
                                                <div id="'+thisRow.idMenu+'" class="'+nodeClass+'" style="margin-left:'+valMarginLeft+'px;"></div>\n\
                                                '+thisRow.name+'</td>\n\
                                            <td>\n\
                                                '+thisRow.url+'\n\
                                            </td>\n\
                                            <td>\n\
                                                <a style="cursor: pointer;" class="edit" rel="'+thisRow.idMenu+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a rel="'+thisRow.idMenu+'" href="/private/menu/removeMenu" class="trash">\n\
                    <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>\n\
                 </a>'+subMenuOptions.toString());
                                idCurrentRow = thisRow.idMenu;
                                $(('#'+idMenu).toString()).removeClass('open_node');
                                $(('#'+idMenu).toString()).addClass('close_node');
                            }
                        });//FIN SCRIPT
    
</script>

<div id="errorMessages">
    <div class="header"></div>
    <div class="content">
        <div class="img">
            <img src="/media/images/msg_error.png" alt="[ERROR]"/>
        </div>
        <div class="text">
            <div class="title">
                <?php echo __("Error de Validación");?>
            </div>
            <div id="messageField" class="message">

            </div>
        </div>
    </div>
    <div class="footer"></div>
</div>

<input id="btnNewMenu" type="button" class="btn" value="<?php echo __("Nuevo Menú"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Menús"); ?></center>

<table id="tblMenu" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Nombre"); ?></th>
            <th><?php echo __("Ruta"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblMenuContent">
        <?php
        foreach ($menu_list as $c) {
        ?>
            <tr id="menu<?php echo $c->idMenu; ?>">
                <td>
                    <div id="<?php echo $c->idMenu; ?>" class="open_node" style="margin-left: 0px;"></div>
                <?php echo __($c->name); ?>
            </td>
            <td>
                <?php echo $c->url; ?>
            </td>
            <td>
                <a style="cursor: pointer;" class="edit" rel="<?php echo $c->idMenu; ?>" >
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                </a>
                <a class="trash" href="/private/menu/removeMenu" rel="<?php echo $c->idMenu; ?>" >
                    <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                </a>
                <a style="cursor: pointer;" class="newSubMenu" rel="<?php echo $c->idMenu; ?>" >
                    <img border="0" src="/media/ico/new_menu.png" alt="[<?php echo __("Nuevo Submenu"); ?>]" title="[<?php echo __("Nuevo SubMenu"); ?>]"/>
                </a>
                <a style="cursor: pointer;" class="newPHPAction" rel="<?php echo $c->idMenu; ?>" >
                    <img border="0" src="/media/ico/php.png" alt="[<?php echo __("Nueva Acción PHP"); ?>]" title="[<?php echo __("Nueva Acción PHP"); ?>]"/>
                </a>
            </td>
        </tr>
        <?php
            }
        ?>
        </tbody>
    </table>

    <span class="titleForm"><?php echo __("Menú"); ?></span>
    <span class="titleConfirmBox"><?php echo __("Eliminar Menú"); ?></span>
    <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar este menú?"); ?></span>

    <div id="frmPopup">
        <form id="frmMenu" method="post" action="" class="frm" >
            <fieldset>
                <legend class="frmSubtitle"></legend>
                <div class="label_output">
                    <div class="superMenu">
                        <label><?php echo __("Menu padre"); ?>:</label>
                        <input id="super_menu_name" name="super_menu_name" value="" type="text" maxlength="150" size="30" readonly="true" disabled="true"/>
                    </div>
                    <div>
                        <label><?php echo __("Nombre"); ?>:</label>
                        <input id="menu_name" name="menu_name" value="" type="text" maxlength="150" size="30" />
                    </div>
                    <div>
                        <label><?php echo __("Ruta"); ?>:</label>
                    <input id="menu_url" name="menu_url" value="" type="text" maxlength="250" size="40" />
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="idMenu" id="idMenu" value="0" />
        <input type="hidden" name="idSuperMenu" id="idSuperMenu" value="0" />
        <input type="hidden" name="menu_type" id="menu_type" value="M" />
    </form>
</div>