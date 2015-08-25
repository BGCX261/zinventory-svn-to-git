$(document).ready(function(){
    confirmBox = function(message, title,fn)
    {

        $("div.confirmBox").remove();
        $("body").append("<div class=\"confirmBox\" >"+message+"</div>");
        $("div.confirmBox").dialog({
            autoOpen: false,
            modal:true,
            title:title,
            buttons:
            {
                "OK":function(){
                    $(this).remove();
                    fn(true);
                },
                "CANCEL":function(){
                    $(this).remove();
                    fn(false);
                }
            },
            close: function(event, ui)
            {
                fn(false);
                $(this).remove();
            },

            open: function(event, ui)
            {
                $(this).focus();
            }
        });

        $("div.confirmBox").dialog('open');
    }
});

function fill_combobox(arrayObj,index,url_function,id_value,field_value,div_id){
    if(arrayObj.get(index)==null){
        $.ajax(
        {
            type: "POST",
            url: url_function,
            data:{
                ajax:"ajax"
            },
            dataType:'json',
            success: function(datos)
            {
                i=0;
                for(i=0;i<datos.length;i++){
                    $('#'+div_id).append("<option value='"+datos[i][id_value]+"'>"+datos[i][field_value]+"</option>");
                //debug(datos[i]);
                }
                arrayObj.put(index,1);
            }
        });
    }
}

function noTildesNoEmptySpace(event){
    if(event.which=='32' ||
        event.which=='180' ||
        event.which=='241' ||
        event.which=='225' ||
        event.which=='233' ||
        event.which=='237' ||
        event.which=='243' ||
        event.which=='250'){
        event.preventDefault();
    }
    
}


