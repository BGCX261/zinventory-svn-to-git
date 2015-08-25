<script type="text/javascript">
    $(document).ready(function(){
        //INICIO DATATABLE MENU
        var tblPrivilege = $('#tblPrivilege').dataTable({
            "bJQueryUI": true,
            "bSort": false,
            "bPaginate": false,
            "bFilter":false,
            "aoColumns":[
                { "sWidth": "40%" },
                null
            ]
        });
        //FIN DATATABLE MENU

        $(".check_link").live('click',function(){
            var data ="menu_id="+$(this).attr('rel').split(":")[0]+"&group_id="+$(this).attr('rel').split(":")[1];
            var html_check =(this);
            $.ajax({
                type: "POST",
                url: '/private/privilege/createOrDeleteAccess',
                data:data,
                dataType:'html',
                success:function(response){
                    var r=response.split("|")[0];
                    var msg=response.split("|")[1];
                    if(r==1){
                        if($(html_check).hasClass('check_true')){
                            $(html_check).removeClass('check_true');
                            $(html_check).addClass('check_false');
                        } else {
                            $(html_check).removeClass('check_false');
                            $(html_check).addClass('check_true');
                        }
                    } else {
                        alert(msg);
                    }
                }
            });
            
        });
       
        $(".check_link[name][name$='0']").addClass('check_false');
        $(".check_link[name][name$='1']").addClass('check_true');
        
    });
    


</script>
<style type="text/css">
    #format { margin-top: 2em; }
</style>

<center class="titleTables"><?php echo __("Administración de Privilegios de Grupo"); ?></center>

<table id="tblPrivilege" class="display" cellpadding="0" cellspacing="0" border="0" >
    <thead>
        <tr>
            <th><?php echo __("Menú"); ?>&nbsp;
                <?php echo __("o"); ?>&nbsp;
                <?php echo __("Acciones"); ?>
            </th>
            <th>
                <?php echo __("Grupos"); ?>
            </th>
        </tr>
    </thead>
    <tbody id="tblPtivilegeContent">
    <td></td>
    <td><?php
                if (!empty($groups)) {
                    foreach ($groups as $g) {
                ?>
                        <label class="label_privilege"><?php echo $g->name; ?></label>
        <?php
                    }
                }
        ?>
            </td>
    <?php
                foreach ($privilegeRows as $pr) {
                    echo $pr->printMenuItemRow($pr, 0, $pr->groups);
                }
    ?>
</tbody>
</table>