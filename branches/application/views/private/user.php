<script type="text/javascript">
    $(document).ready(function(){

        //INICIO VALIDADOR
        var validator = $("#frmUser").validate({
            debug:true,
            rules: {
                person_fname: {
                    required: true
                },
                person_lname:{
                    required: true
                },
                person_email:{
                    required: true,
                    email: true
                },
                user_name:{
                    required: true,
                    minlength:4
                },
                user_password:{
                    required: true
                },
                user_password_validator:{
                    required: true
                },
                user_group:{
                    required: true,
                    min: 1
                },
                person_country:{
                    required: true,
                    min: 1
                },
                person_zipcode: {
                    required: true,
                    minlength: 5
                }
            },
            messages:{
                person_fname:'El nombre real del usuario es obligatorio.',
                person_lname:'El apellido del usuario es obligatorio.',
                person_email:{
                    required:'El email del usuario es obligatorio.',
                    email:'El email no tiene formato válido.'
                },
                user_name:{
                    required:'El nombre de usuario es obligatorio.',
                    minlength:'Se requiere al menos {0} caracteres para el nombre del usuario'},
                user_password:'La contraseña del usuario es obligatoria.',
                user_password_validator:'Debe escribir la conseña nuevamente.',
                user_group:'Debe seleccionar un grupo para el usuario.',
                person_country:'Debe seleccionar el país del usuario.',
                person_zipcode:{
                    required:'El zipcode es obligatorio.',
                    minlength:jQuery.format('Se requiere al menos {0} caracteres para el zipcode')
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

        $('.password').keypress(function(event){
            noTildesNoEmptySpace(event);
        });
        //FIN VALIDADOR
        
        //INICIO DATATABLE GRUPO
        var tblUser = $('#tblUser').dataTable({
            "bJQueryUI": true,
            "bSort": true,
            "bPaginate": true,
            "bFilter":true
        });
        //FIN DATATABLE GRUPO

        var titlePopup = $('.titleForm').text();

        //INICIO POPUP NUEVO GRUPO
        $('#frmPopup').dialog({autoOpen:false,width:500,height:490,autoSize:true,modal:true,resizable:false,closeOnEscape:true,title:titlePopup,
            buttons: {
                "<?php echo __("Aceptar"); ?>":function(){
                    if(!($('#frmUser').valid())){
                        return false;
                    }
                    
                    var frm = $('#frmUser').serialize();
                    $.ajax({
                        type: "POST",
                        url: '/private/user/createOrUpdateUser',
                        data:frm,
                        dataType:'html',
                        success: function(r){
                            var resp = r.split("|")[0];
                            var msg = r.split("|")[1];
                            if(resp==1){
                                $.ajax({
                                    type:'POST',
                                    url:'/private/user/listUsers',
                                    data:{ajax:'ajax'},
                                    dataType:'json',
                                    success:function(response){
                                        tblUser.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblUser(this);
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

        //INICIO CARGAR COUNTRY, GROUP COMBOBOX
        var groupArrayMap = new ArrayMap();
        var countryArrayMap = new ArrayMap();
        function loading_combobox(){
            fill_combobox(groupArrayMap, 'group', '/private/group/listGroups', 'idGroup', 'name', 'user_group');
            fill_combobox(countryArrayMap, 'country', '/private/admin/listCountries', 'idCountry', 'name', 'person_country');
            $('#user_group').css('display','none');
            selectGroup_temp = document.createElement('select');
            $(selectGroup_temp).attr('id','user_group_tmp');
            $(selectGroup_temp).attr('name','user_group_tmp');
            $(selectGroup_temp).css('width','230px');
            $(selectGroup_temp).append("<option value=\"0\"><?php echo __("Cargando..."); ?></option>");
            $('#user_group').before(selectGroup_temp);

            $('#person_country').css('display','none');
            selectCountry_temp = document.createElement('select');
            $(selectCountry_temp).attr('id','person_country_tmp');
            $(selectCountry_temp).attr('name','person_country_tmp');
            $(selectCountry_temp).css('width','230px');
            $(selectCountry_temp).append("<option value=\"0\"><?php echo __("Cargando..."); ?></option>");
            $('#person_country').before(selectCountry_temp);
        }
        //FIN CARGAR COUNTRY, GROUP COMBOBOX

        //INICIO CLICK NUEVO USUARIO
        
        $("#btnNewUser").click(function(){
            validator.resetForm();
            loading_combobox();
            $('.frmSubtitle').text('<?php echo __("Nuevo Usuario"); ?>');
            
            $("#person_fname").val('');
            $("#person_lname").val('');
            $("#person_phone1").val('');
            $("#person_phone2").val('');
            $("#person_cellphone").val('');
            $("#person_address1").val('');
            $("#person_address2").val('');
            $("#person_email").val('');
            $("#user_name").val('');
            $("#user_password").val('');
            $("#user_password_validator").val('');
            $("#user_group").val(0);
            $("#person_country").val(0);
            $("#person_city").val('');
            $("#person_zipcode").val('');
            $("#user_id").val(0);
            $('#frmPopup').dialog({open: function(event, ui) {
                    setTimeout(function(){
                        $('#user_group_tmp').remove();
                        $('#user_group').css('display','block');
                        $("#user_group").val(0);
                        $('#person_country_tmp').remove();
                        $('#person_country').css('display','block');
                        $("#person_country").val(0);
                    }, 1000);
                }});
            $('#frmPopup').dialog('open');
        });
        //FIN CLICK NUEVO USUARIO


        //INICIO ELIMINAR
        msgConfirmBox = $("span.msgConfirmBox").html();
        titleConfirmBox = $("span.titleConfirmBox").html();
        $("a.trash").live("click",function(){
            var url =  $(this).attr("href");
            var user_id = $(this).attr("rel");
            confirmBox(msgConfirmBox,titleConfirmBox,function(response){
                if(response == true)
                {
                    var dataString = "user_id=" + user_id;
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
                                    url: '/private/user/listUsers',
                                    data:{ajax:"ajax"},
                                    dataType:'json',
                                    success: function(response)
                                    {
                                        tblUser.fnClearTable();
                                        $.each(response,function()
                                        {
                                            addRowTblUser(this);
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

        //INICIO CLICK EDITAR USUARIO
        $("a.edit").live("click",function(){
            validator.resetForm();
            loading_combobox();
            var user_id = $(this).attr("rel");
            var dataString = "user_id=" + user_id;
            $.ajax({
                type:'POST',
                url:'/private/user/findUserById',
                data:dataString,
                dataType:'json',
                success: function(r){
                    $(".frmSubtitle").text('<?php echo __("Editar Usuario"); ?>');
                    $("#person_fname").val(r[0].person_fname);
                    $("#person_lname").val(r[0].person_lname);
                    $("#person_phone1").val(r[0].person_phone1);
                    $("#person_phone2").val(r[0].person_phone2);
                    $("#person_cellphone").val(r[0].person_cellphone);
                    $("#person_address1").val(r[0].person_address1);
                    $("#person_address2").val(r[0].person_address2);
                    $("#person_email").val(r[0].person_email);
                    $("#user_name").val(r[0].user_name);
                    $("#user_password").val(r[0].user_password);
                    $("#user_password_validator").val(r[0].user_password);
                    $("#person_city").val(r[0].person_city);
                    $("#person_zipcode").val(r[0].person_zipcode);
                    $("#user_id").val(r[0].user_id);
                    $('#frmPopup').dialog({open: function(event, ui) {
                            setTimeout(function(){
                                $('#user_group_tmp').remove();
                                $('#user_group').css('display','block');
                                $("#user_group").val(r[0].user_group_id);
                                $('#person_country_tmp').remove();
                                $('#person_country').css('display','block');
                                $("#person_country").val(r[0].person_country_id);
                            }, 1000);
                        }});
                    $('#frmPopup').dialog('open');
                }
            });
        });
        //FIN CLICK EDITAR USUARIO

        //INICIO AGREGA FILA
        function addRowTblUser(obj){
            tblUser.fnAddData([
                obj.user_name,
                obj.group_name,
                obj.person_fname,
                obj.person_lname,
                obj.person_email,
                obj.user_registrationDate,
                '<a class="edit" style="cursor: pointer;" rel="'+obj.user_id+'" >\n\
                    <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>\n\
                 </a>\n\
                 <a class="trash" href="/private/user/removeUser" rel="'+obj.user_id+'">\n\
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

<input id="btnNewUser" type="button" class="btn" value="<?php echo __("Nuevo Usuario"); ?>"/>

<center class="titleTables"><?php echo __("Administración de Usuarios"); ?></center>

<table id="tblUser" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Nombre"); ?></th>
            <th><?php echo __("Grupo"); ?></th>
            <th><?php echo __("Nombre(s)"); ?></th>
            <th><?php echo __("Apellidos"); ?></th>
            <th><?php echo __("Correo-e"); ?></th>
            <th><?php echo __("Fecha de Registro"); ?></th>
            <th><?php echo __("Acciones"); ?></th>
        </tr>
    </thead>
    <tbody id="tblUserContent">
        <?php
                foreach ($user_list as $u) {
        ?>
                    <tr>
                        <td>
                <?php echo $u->userName; ?>
                </td>
                <td>
                <?php echo $u->group->name; ?>
                </td>
                <td>
                <?php echo $u->person->fName; ?>
                </td>
                <td>
                <?php echo $u->person->lName; ?>
                </td>
                <td>
                <?php echo $u->person->email; ?>
                </td>
                <td>
                <?php echo $u->registrationDate; ?>
                </td>
                <td>
                    <a style="cursor: pointer;" class="edit" rel="<?php echo $u->idUser; ?>" >
                        <img border="0"  src="/media/ico/edit.png" alt="<?php echo __("Editar"); ?>" title="[<?php echo __("Editar"); ?>]"/>
                    </a>
                    <a class="trash" href="/private/user/removeUser" rel="<?php echo $u->idUser; ?>" >
                        <img border="0" src="/media/ico/trash.png" alt="[<?php echo __("Eliminar"); ?>]" title="[<?php echo __("Eliminar"); ?>]"/>
                    </a>
                </td>
            </tr>
        <?php
                }
        ?>
            </tbody>
        </table>

        <span class="titleForm"><?php echo __("Usuario"); ?></span>
        <span class="titleConfirmBox"><?php echo __("Eliminar Usuario"); ?></span>
        <span class="msgConfirmBox"><?php echo __("¿Está seguro de eliminar este usuario?"); ?></span>


        <div id="frmPopup">
            <form id="frmUser" method="post" action="" class="frm" >
                <fieldset>
                    <legend class="frmSubtitle"></legend>
                    <div class="label_output">

                        <div>
                            <label><?php echo __("Nombre(s)"); ?>:</label>
                            <input id="person_fname" name="person_fname" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Apellidos"); ?>:</label>
                            <input id="person_lname" name="person_lname" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Teléfono"); ?>&nbsp;1:</label>
                            <input id="person_phone1" name="person_phone1" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Teléfono"); ?>&nbsp;2:</label>
                            <input id="person_phone2" name="person_phone2" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Celular"); ?>:</label>
                            <input id="person_cellphone" name="person_cellphone" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Dirección"); ?>&nbsp;1:</label>
                            <input id="person_address1" name="person_address1" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Dirección"); ?>&nbsp;2:</label>
                            <input id="person_address2" name="person_address2" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Correo-e"); ?>:</label>
                            <input id="person_email" name="person_email" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Nombre de usuario"); ?>:</label>
                            <input id="user_name" name="user_name" value="" type="text" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Contraseña"); ?>:</label>
                            <input id="user_password" class="password" name="user_password" value="" type="password" maxlength="150" size="30" />
                        </div>
                        <div>
                            <label><?php echo __("Reingrese contraseña"); ?>:</label>
                            <input id="user_password_validator" class="password" name="user_password_validator" value="" type="password" maxlength="150" size="30" />
                        </div>

                        <div>
                            <label><?php echo __("Grupo"); ?>:</label>
                            <select id="user_group" name="user_group">
                                <option value="0"><?php echo __("Seleccione una opción"); ?></option>
                            </select>
                        </div>

                        <div>
                            <label><?php echo __("País"); ?>:</label>
                            <select id="person_country" name="person_country">
                                <option value="0"><?php echo __("Seleccione una opción"); ?></option>
                            </select>
                        </div>
                        <div>
                            <label><?php echo __("Ciudad"); ?>:</label>
                            <input id="person_city" name="person_city" value="" type="text" maxlength="150" size="30" />
                        </div>
                        <div>
                            <label><?php echo __("Zip code"); ?>:</label>
                    <input id="person_zipcode" name="person_zipcode" value="" type="text" maxlength="10" size="20" />
                </div>
            </div>
        </fieldset>
        <input type="hidden" name="user_id" id="user_id" value="0" />
    </form>
</div>