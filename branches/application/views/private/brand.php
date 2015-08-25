<script type="text/javascript">
    $(document).ready(function(){
        //INICIO VALIDADOR
        var validator = $("#frmBrand").validate({
            debug:true,
            rules: {
                brand_full_name: {
                    required: true,
                    minlength:3
                },
                brand_short_name: {
                    required: true,
                    minlength:3
                }
            },
            messages:{
                brand_full_name: {
                    required: 'El nombre de la marca obligatorio.',
                    minlength:jQuery.format('Se requiere al menos {0} caracteres para el nombre de la marca.')
                },
                brand_short_name: {
                    required: 'El nombre corto de la marca es obligatorio.',
                    minlength:jQuery.format('Se requiere al menos {0} caracteres para el nombre corto de la marca.')
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

        //INICIO DATATABLE MARCA
        var tblBrand = $('#tblBrand').dataTable({
            "bJQueryUI": true,
            "bSort": true,
            "bPaginate": true,
            "bFilter":true
        });
        //FIN DATATABLE MARCA

        var titlePopup = $('.titleForm').text();

        //INICIO POPUP NUEVA MARCA
        $('#frmPopup').dialog({autoOpen:false,width:500,height:210,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmBrand').valid())){
                        return false;
                    }
                    var frm = $('#frmBrand').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/brand/createOrUpdateBrand',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/brand/listBrands',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblBrand.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblBrand(this);
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
        //FIN POPUP NUEVA MARCA

        //INICIO CLICK NUEVA MARCA
        $("#btnNewBrand").click(function(){
            validator.resetForm();
            $('.frmSubtitle').text('<?php echo __("Nueva Marca"); ?>');
            $("#brand_full_name").val('');
            $("#brand_short_name").val('');
            $('#brand_id').val(0);
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVA MARCA

        //INICIO CLICK EDITAR MARCA
        $('a.edit').live('click',function(){
            validator.resetForm();
            var brand_id = $(this).attr("rel");
            var dataString = "brand_id=" + brand_id;
            $.ajax({
                type:"POST",
                url:'/private/brand/findBrandById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $('.frmSubtitle').text('<?php echo __("Editar Marca"); ?>');
                    $("#brand_full_name").val(r.fullName);
                    $("#brand_short_name").val(r.shortName);
                    $('#brand_id').val(r.idBrand);
                    $('#frmPopup').dialog('open');
                }
            });
        });
        //FIN CLICK EDITAR MARCA

        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var brand_id = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "brand_id=" + brand_id;
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
                                    url: '/private/brand/listBrands',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {

                                        tblBrand.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblBrand(this);
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
        function addRowTblBrand(obj){
            tblBrand.fnAddData([
                obj.fullName,
                obj.shortName,
                '<a class="edit" style="cursor: pointer;" rel="'+obj.idBrand+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a class="trash" href="/private/brand/removeBrand" rel="'+obj.idBrand+'" >\n\
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

<input id="btnNewBrand" type="button" class="btn" value="<?php echo __("Nueva Marca"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Marcas"); ?></center>

<table id="tblBrand" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Nombre"); ?></th>
            <th><?php echo __("Nombre corto"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblBrandContent">
        <?php
                foreach ($brand_list as $b) {
        ?>
                    <tr>
                        <td>
                <?php echo $b->fullName; ?>
                </td>
                <td>
                <?php echo $b->shortName; ?>
                </td>
                <td>
                    <a style="cursor: pointer;" class="edit" rel="<?php echo $b->idBrand; ?>" >
                        <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                    </a>
                    <a class="trash" href="/private/brand/removeBrand" rel="<?php echo $b->idBrand; ?>" >
                        <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                    </a>
                </td>
            </tr>
        <?php
                }
        ?>
            </tbody>
        </table>

        <span class="titleForm"><?php echo __("Brand"); ?></span>
        <span class="titleConfirmBox"><?php echo __("Eliminar Marca"); ?></span>
        <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar esta marca?"); ?></span>

        <div id="frmPopup">
            <form id="frmBrand" method="post" action="" class="frm" >
                <fieldset>
                    <legend class="frmSubtitle"></legend>
                    <div class="label_output">
                        <div>
                            <label><?php echo __("Nombre"); ?>:</label>
                            <input id="brand_full_name" name="brand_full_name" value="" type="text" maxlength="100" size="30" />
                        </div>
                        <div>
                            <label><?php echo __("Nombre corto"); ?>:</label>
                    <input id="brand_short_name" name="brand_short_name" value="" type="text" maxlength="10" size="30" />
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="brand_id" id="brand_id" value="0" />
    </form>
</div>