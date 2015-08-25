<script type="text/javascript">
    $(document).ready(function(){

        //INICIO VALIDADOR
        var validator = $("#frmWarehouse").validate({
            debug:true,
            rules: {
                warehouse_name: {
                    required: true
                },
                warehouse_short_name:{
                    required: true
                },
                warehouse_office_location:{
                    required: true,
                    min: 1
                }
            },
            messages:{
                warehouse_name:'El nombre del almacén.',
                warehouse_short_name:'El nombre corto del almacén es necesario.',
                warehouse_office_location:'Debe seleccionar la oficina para el almacén.'
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

        //INICIO DATATABLE ALMACÉN
        var tblWarehouse = $('#tblWarehouse').dataTable({
            "bJQueryUI": true,
            "bSort": true,
            "bPaginate": true,
            "bFilter":true
        });
        //FIN DATATABLE ALMACÉN

        var titlePopup = $('.titleForm').text();

        //INICIO POPUP NUEVA ALMACÉN
        $('#frmPopup').dialog({autoOpen:false,width:500,height:250,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmWarehouse').valid())){
                        return false;
                    }

                    var frm = $('#frmWarehouse').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/warehouse/createOrUpdateWarehouse',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/warehouse/listWarehouses',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblWarehouse.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblWarehouse(this);
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
        //FIN POPUP NUEVA ALMACÉN

        //INICIO CARGAR OFFICE LOCATION COMBOBOX
        var officeLocationArray = new ArrayMap();
        function loading_combobox(){
            fill_combobox(officeLocationArray, 'officelocation', '/private/officelocation/listOfficeLocations', 'office_location_id', 'office_location_name', 'warehouse_office_location');
            $('#warehouse_office_location').css('display','none');
            selectOfficeLocation_temp = document.createElement('select');
            $(selectOfficeLocation_temp).attr('id','warehouse_office_location_tmp');
            $(selectOfficeLocation_temp).attr('name','warehouse_office_location_tmp');
            $(selectOfficeLocation_temp).css('width','230px');
            $(selectOfficeLocation_temp).append("<option value=\"0\"><?php echo __("Cargando..."); ?></option>");
            $('#warehouse_office_location').before(selectOfficeLocation_temp);
        }
        //FIN CARGAR OFFICE LOCATION COMBOBOX

        //INICIO CLICK NUEVA ALMACÉN

        $("#btnNewWarehouse").click(function(){
            validator.resetForm();
            loading_combobox();
            $('.frmSubtitle').text('<?php echo __("Nuevo Almacén"); ?>');

            $("#warehouse_name").val('');
            $("#warehouse_short_name").val('');
            $("#warehouse_office_location").val(0);
            $('#frmPopup').dialog({open: function(event, ui) {
                    setTimeout(function(){
                        $('#warehouse_office_location_tmp').remove();
                        $('#warehouse_office_location').css('display','block');
                        $("#warehouse_office_location").val(0);
                    }, 1000);
                }});
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVA ALMACÉN

        //INICIO CLICK EDITAR ALMACÉN
        $("a.edit").live("click",function(){
            validator.resetForm();
            loading_combobox();
            var warehouse_id = $(this).attr("rel");
            var dataString = "warehouse_id=" + warehouse_id;
            $.ajax({
                type:'POST',
                url:'/private/warehouse/findWarehouseById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $(".frmSubtitle").text('<?php echo __("Editar Almacén"); ?>');
                    $("#warehouse_name").val(r.name);
                    $("#warehouse_short_name").val(r.shortName);
                    $("#warehouse_id").val(r.idWarehouse);

                    $('#frmPopup').dialog({open: function(event, ui) {
                            setTimeout(function(){
                                $('#warehouse_office_location_tmp').remove();
                                $('#warehouse_office_location').css('display','block');
                                $("#warehouse_office_location").val(r.idOfficeLocation);
                            }, 1000);
                        }});
                    $('#frmPopup').dialog('open');
                }
            });
        });
        //FIN CLICK EDITAR ALMACÉN

        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var warehouse_id = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "warehouse_id=" + warehouse_id;
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
                                    url: '/private/warehouse/listWarehouses',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {
                                        tblWarehouse.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblWarehouse(this);
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
        function addRowTblWarehouse(obj){
            tblWarehouse.fnAddData([
                obj.warehouse_name,
                obj.warehouse_short_name,
                obj.office_location_name,
                '<a class="edit" style="cursor: pointer;" rel="'+obj.warehouse_id+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a class="trash" href="/private/warehouse/removeWarehouse" rel="'+obj.warehouse_id+'">\n\
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

<input id="btnNewWarehouse" type="button" class="btn" value="<?php echo __("Nuevo Almacén"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Almacenes"); ?></center>

<table id="tblWarehouse" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Nombre"); ?></th>
            <th><?php echo __("Nombre corto"); ?></th>
            <th><?php echo __("Oficina"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblWarehouseContent">
        <?php
                foreach ($warehouse_list as $w) {
        ?>
                    <tr>
                        <td>
                <?php echo $w->name; ?>
                </td>
                <td>
                <?php echo $w->shortName; ?>
                </td>
                <td>
                <?php echo $w->officeLocation->name; ?>
                </td>
                <td>
                    <a style="cursor: pointer;" class="edit" rel="<?php echo $w->idWarehouse; ?>" >
                        <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                    </a>
                    <a class="trash" href="/private/warehouse/removeWarehouse" rel="<?php echo $w->idWarehouse; ?>" >
                        <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                    </a>
                </td>
            </tr>
        <?php
                }
        ?>
            </tbody>
        </table>

        <span class="titleForm"><?php echo __("Almacén"); ?></span>
        <span class="titleConfirmBox"><?php echo __("Eliminar Almacén"); ?></span>
        <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar este almacén?"); ?></span>

        <div id="frmPopup">
            <form id="frmWarehouse" method="post" action="" class="frm" >
                <fieldset>
                    <legend class="frmSubtitle"></legend>
                    <div class="label_output">

                        <div>
                            <label><?php echo __("Nombre"); ?>:</label>
                            <input id="warehouse_name" name="warehouse_name" value="" type="text" maxlength="50" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Nombre corto"); ?>:</label>
                            <input id="warehouse_short_name" name="warehouse_short_name" value="" type="text" maxlength="10" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Oficina"); ?>:</label>
                            <select id="warehouse_office_location" name="warehouse_office_location">
                                <option value="0"><?php echo __("Seleccione una opción"); ?></option>
                    </select>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="warehouse_id" id="warehouse_id" value="0" />
    </form>
</div>