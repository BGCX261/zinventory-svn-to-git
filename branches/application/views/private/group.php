<script type="text/javascript">
    $(document).ready(function(){
        //INICIO VALIDADOR
        var validator = $("#frmGroup").validate({
            debug:true,
            rules: {
                group_name: {
                    required: true,
                    minlength:3
                }
            },
            messages:{
                group_name: {
                    required: 'El nombre del grupo es obligatorio',
                    minlength:jQuery.format('Se requiere al menos {0} caracteres para el nombre del grupo')
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
            }
        });
        //FIN VALIDADOR
        
        //INICIO DATATABLE GRUPO
        var tblGroup = $('#tblGroup').dataTable({
            "bJQueryUI": true,
            "bSort": true,
            "bPaginate": true,
            "bFilter":true
        });
        //FIN DATATABLE GRUPO

        var titlePopup = $('.titleForm').text();

        //INICIO POPUP NUEVO GRUPO
        $('#frmPopup').dialog({autoOpen:false,width:500,height:200,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmGroup').valid())){
                        return false;
                    }
                    var frm = $('#frmGroup').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/group/createOrUpdateGroup',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/group/listGroups',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblGroup.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblGroup(this);
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
        //FIN POPUP NUEVO GRUPO

        //INICIO CLICK NUEVO GRUPO
        $("#btnNewGroup").click(function(){
            validator.resetForm();
            $('.frmSubtitle').text('<?php echo __("Nuevo Grupo"); ?>');
            $("#group_name").val('');
            $('#group_id').val(0);
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK GRUPO NUEVO

        //INICIO CLICK EDITAR GRUPO
        $('a.edit').live('click',function(){
            validator.resetForm();
            var group_id = $(this).attr("rel");
            var dataString = "group_id=" + group_id;
            $.ajax({
                type:"POST",
                url:'/private/group/findGroupById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $('.frmSubtitle').text('<?php echo __("Editar Grupo"); ?>');
                    $('#group_name').val(r.name);
                    $('#group_id').val(r.idGroup);
                    $('#frmPopup').dialog('open');
                }
            });
        });
        //FIN CLICK EDITAR GRUPO

        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var group_id = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "group_id=" + group_id;
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
                                    url: '/private/group/listGroups',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {
                                        
                                        tblGroup.fnClearTable();
                                        $.each(response,function()
                                        {
                                            
                                            addRowTblGroup(this);
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
        
        //INICIO AGREGA FILA
        function addRowTblGroup(obj){
            tblGroup.fnAddData([
                obj.name,
                '<a class="edit" style="cursor: pointer;" rel="'+obj.idGroup+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a class="trash" href="/private/group/removeGroup" rel="'+obj.idGroup+'" >\n\
                    <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>\n\
                 </a>']);
                            }//FIN AGREGA FILA
                        });
</script>
<div id="errorMessages">
    <div class="header"></div>
    <div class="content">
        <div class="img">
            <img src="/media/images/msg_error.png" alt="[ERROR]"/>
        </div>
        <div class="text">
            <div class="title">
                <?php echo __("Error de Validación"); ?>
            </div>
            <div id="messageField" class="message">

            </div>
        </div>
    </div>
    <div class="footer"></div>
</div>

<input id="btnNewGroup" type="button" class="btn" value="<?php echo __("Nuevo Grupo"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Grupos"); ?></center>

<table id="tblGroup" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Nombre"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblGroupContent">
        <?php
                foreach ($group_list as $g) {
        ?>
                    <tr>
                        <td>
                <?php echo $g->name; ?>
                </td>
                <td>
                    <a style="cursor: pointer;" class="edit" rel="<?php echo $g->idGroup; ?>" >
                        <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                    </a>
                    <a class="trash" href="/private/group/removeGroup" rel="<?php echo $g->idGroup; ?>" >
                        <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                    </a>
                </td>
            </tr>
        <?php
                }
        ?>
            </tbody>
        </table>

        <span class="titleForm"><?php echo __("Grupo"); ?></span>
        <span class="titleConfirmBox"><?php echo __("Eliminar Grupo"); ?></span>
        <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar este grupo?"); ?></span>

        <div id="frmPopup">
            <form id="frmGroup" method="post" action="" class="frm" >
                <fieldset>
                    <legend class="frmSubtitle"></legend>
                    <div class="label_output">
                        <div>
                            <label><?php echo __("Nombre"); ?>:</label>
                    <input id="group_name" name="group_name" value="" type="text" maxlength="150" size="30" />
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="group_id" id="group_id" value="0" />
    </form>
</div>