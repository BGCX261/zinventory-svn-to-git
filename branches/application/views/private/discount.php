<script type="text/javascript">
    $(document).ready(function(){
        //INICIO VALIDADOR
        $("#discount_value").mask("9.99");
        var validator = $("#frmDiscount").validate({
            debug:true,
            rules: {
                discount_value: {
                    required: true,
                    max:1,
                    min:0.05
                }
            },
            messages:{
                discount_value: {
                    required: 'El valor del descuento es obligatorio.'
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

        //INICIO DATATABLE DESCUENTO
        var tblDiscount = $('#tblDiscount').dataTable({
            "bJQueryUI": true,
            "bSort": true,
            "bPaginate": true,
            "bFilter":true
        });
        //FIN DATATABLE DESCUENTO

        var titlePopup = $('.titleForm').text();

        //INICIO POPUP NUEVO DESCUENTO
        $('#frmPopup').dialog({autoOpen:false,width:500,height:210,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmDiscount').valid())){
                        return false;
                    }
                    var frm = $('#frmDiscount').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/discount/createOrUpdateDiscount',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/discount/listDiscounts',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblDiscount.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowtblDiscount(this);
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
        //FIN POPUP NUEVO DESCUENTO

        //INICIO CLICK NUEVO DESCUENTO
        $("#btnNewDiscount").click(function(){
            validator.resetForm();
            $('.frmSubtitle').text('<?php echo __("Nuevo Descuento"); ?>');
            $("#discount_value").val('');
            $('#discount_id').val(0);
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVO DESCUENTO

        //INICIO CLICK EDITAR DESCUENTO
        $('a.edit').live('click',function(){
            validator.resetForm();
            var discount_id = $(this).attr("rel");
            var dataString = "discount_id=" + discount_id;
            $.ajax({
                type:"POST",
                url:'/private/discount/findDiscountById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $('.frmSubtitle').text('<?php echo __("Editar Marca"); ?>');
                    $("#discount_value").val(r.discount);
                    $('#discount_id').val(r.idDiscount);
                    $('#frmPopup').dialog('open');
                }
            });
        });
        //FIN CLICK EDITAR DESCUENTO

        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var discount_id = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "discount_id=" + discount_id;
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
                                    url: '/private/discount/listDiscounts',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {

                                        tblDiscount.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowtblDiscount(this);
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
        function addRowtblDiscount(obj){
            tblDiscount.fnAddData([
                obj.discount,
                obj.registrationDate,
                '<a class="edit" style="cursor: pointer;" rel="'+obj.idDiscount+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a class="trash" href="/private/discount/removeDiscount" rel="'+obj.idDiscount+'" >\n\
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

<input id="btnNewDiscount" type="button" class="btn" value="<?php echo __("Nuevo Descuento"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Descuentos"); ?></center>

<table id="tblDiscount" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Valor del Descuento"); ?></th>
            <th><?php echo __("Fecha de Registro"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblDiscountContent">
        <?php
                foreach ($discount_list as $d) {
        ?>
                    <tr>
                        <td>
                <?php echo $d->discount; ?>
                </td>
                <td>
                <?php echo $d->registrationDate; ?>
                </td>
                <td>
                    <a style="cursor: pointer;" class="edit" rel="<?php echo $d->idDiscount; ?>" >
                        <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                    </a>
                    <a class="trash" href="/private/discount/removeDiscount" rel="<?php echo $d->idDiscount; ?>" >
                        <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                    </a>
                </td>
            </tr>
        <?php
                }
        ?>
            </tbody>
        </table>

        <span class="titleForm"><?php echo __("Descuento"); ?></span>
        <span class="titleConfirmBox"><?php echo __("Eliminar Descuento"); ?></span>
        <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar este descuento?"); ?></span>

        <div id="frmPopup">
            <form id="frmDiscount" method="post" action="" class="frm" >
                <fieldset>
                    <legend class="frmSubtitle"></legend>
                    <div class="label_output">
                        <div>
                            <label><?php echo __("Descuento"); ?>:</label>
                    <input id="discount_value" name="discount_value" value="" type="text" maxlength="100" size="30" />
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="discount_id" id="discount_id" value="0" />
    </form>
</div>