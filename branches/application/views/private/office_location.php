<script type="text/javascript">
    $(document).ready(function(){

        //INICIO VALIDADOR
        var validator = $("#frmOfficeLocation").validate({
            debug:true,
            rules: {
                office_location_name: {
                    required: true
                },
                office_location_address:{
                    required: true
                },
                office_location_country:{
                    required: true,
                    min: 1
                }
            },
            messages:{
                office_location_name:'El nombre de la oficina es necesario.',
                office_location_address:'La dirección de la oficina es necesaria.',
                office_location_country:'Debe seleccionar el país de la oficina.'
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

        //INICIO DATATABLE UBICACIÓN DE OFICINA
        var tblOfficeLocation = $('#tblOfficeLocation').dataTable({
            "bJQueryUI": true,
            "bSort": true,
            "bPaginate": true,
            "bFilter":true
        });
        //FIN DATATABLE UBICACIÓN DE OFICINA

        var titlePopup = $('.titleForm').text();

        //INICIO POPUP NUEVA UBICACIÓN DE OFICINA
        $('#frmPopup').dialog({autoOpen:false,width:500,height:250,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmOfficeLocation').valid())){
                        return false;
                    }

                    var frm = $('#frmOfficeLocation').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/officelocation/createOrUpdateOfficeLocation',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/officelocation/listOfficeLocations',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblOfficeLocation.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblOfficeLocation(this);
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
        //FIN POPUP NUEVA UBICACIÓN DE OFICINA

        //INICIO CARGAR COUNTRY COMBOBOX
        var countryArrayMap = new ArrayMap();
        function loading_combobox(){
            fill_combobox(countryArrayMap, 'country', '/private/admin/listCountries', 'idCountry', 'name', 'office_location_country');
            $('#office_location_country').css('display','none');
            selectCountry_temp = document.createElement('select');
            $(selectCountry_temp).attr('id','office_location_country_tmp');
            $(selectCountry_temp).attr('name','office_location_country_tmp');
            $(selectCountry_temp).css('width','230px');
            $(selectCountry_temp).append("<option value=\"0\"><?php echo __("Cargando..."); ?></option>");
            $('#office_location_country').before(selectCountry_temp);
        }
        //FIN CARGAR COUNTRY COMBOBOX

        //INICIO CLICK NUEVA UBICACIÓN DE OFICINA

        $("#btnNewOfficeLocation").click(function(){
            validator.resetForm();
            loading_combobox();
            $('.frmSubtitle').text('<?php echo __("Nueva Ubicación de Oficina"); ?>');

            $("#office_location_name").val('');
            $("#office_location_address").val('');
            $("#office_location_id").val(0);
            $('#frmPopup').dialog({open: function(event, ui) {
                    setTimeout(function(){
                        $('#office_location_country_tmp').remove();
                        $('#office_location_country').css('display','block');
                        $("#office_location_country").val(0);
                    }, 1000);
                }});
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVA UBICACIÓN DE OFICINA
        
        //INICIO CLICK EDITAR UBICACIÓN DE OFICINA
        $("a.edit").live("click",function(){
            validator.resetForm();
            loading_combobox();
            var office_location_id = $(this).attr("rel");
            var dataString = "office_location_id=" + office_location_id;
            $.ajax({
                type:'POST',
                url:'/private/officelocation/findOfficeLocationById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $(".frmSubtitle").text('<?php echo __("Editar Ubicación de Oficina"); ?>');
                    $("#office_location_name").val(r.name);
                    $("#office_location_address").val(r.address);
                    
                    $("#office_location_id").val(r.idOfficeLocation);

                    $('#frmPopup').dialog({open: function(event, ui) {
                            setTimeout(function(){
                                $('#office_location_country_tmp').remove();
                                $('#office_location_country').css('display','block');
                                $("#office_location_country").val(r.idCountry);
                            }, 1000);
                        }});
                    $('#frmPopup').dialog('open');
                }
            });
        });
        //FIN CLICK EDITAR UBICACIÓN DE OFICINA

        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var office_location_id = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "office_location_id=" + office_location_id;
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
                                    url: '/private/officelocation/listOfficeLocations',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {
                                        tblOfficeLocation.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblOfficeLocation(this);
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
        function addRowTblOfficeLocation(obj){
            tblOfficeLocation.fnAddData([
                obj.office_location_name,
                obj.office_location_address,
                obj.country_name,
                '<a class="edit" style="cursor: pointer;" rel="'+obj.office_location_id+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a class="trash" href="/private/officelocation/removeOfficeLocation" rel="'+obj.office_location_id+'">\n\
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

<input id="btnNewOfficeLocation" type="button" class="btn" value="<?php echo __("Nueva Ubicación de Oficina"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Ubicación de Oficinas"); ?></center>

<table id="tblOfficeLocation" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Nombre"); ?></th>
            <th><?php echo __("Dirección"); ?></th>
            <th><?php echo __("País"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblOfficeLocationContent">
        <?php
                foreach ($office_location_list as $o) {
        ?>
                    <tr>
                        <td>
                <?php echo $o->name; ?>
                </td>
                <td>
                <?php echo $o->address; ?>
                </td>
                <td>
                <?php echo $o->country->name; ?>
                </td>
                <td>
                    <a style="cursor: pointer;" class="edit" rel="<?php echo $o->idOfficeLocation; ?>" >
                        <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                    </a>
                    <a class="trash" href="/private/officelocation/removeOfficeLocation" rel="<?php echo $o->idOfficeLocation; ?>" >
                        <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                    </a>
                </td>
            </tr>
        <?php
                }
        ?>
            </tbody>
        </table>

        <span class="titleForm"><?php echo __("Ubicación de Oficina"); ?></span>
        <span class="titleConfirmBox"><?php echo __("Eliminar Ubicación de Oficina"); ?></span>
        <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar esta ubicación de oficina?"); ?></span>

        <div id="frmPopup">
            <form id="frmOfficeLocation" method="post" action="" class="frm" >
                <fieldset>
                    <legend class="frmSubtitle"></legend>
                    <div class="label_output">

                        <div>
                            <label><?php echo __("Nombre"); ?>:</label>
                            <input id="office_location_name" name="office_location_name" value="" type="text" maxlength="100" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Dirección"); ?>:</label>
                            <input id="office_location_address" name="office_location_address" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("País"); ?>:</label>
                            <select id="office_location_country" name="office_location_country">
                                <option value="0"><?php echo __("Seleccione una opción"); ?></option>
                    </select>
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="office_location_id" id="office_location_id" value="0" />
    </form>
</div>