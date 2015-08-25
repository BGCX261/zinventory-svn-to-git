/**********************************
 *Función para cambiar el idioma de las páginas
 **********************************/


$(document).ready(function(){
    $("#idioma_es").click(function(){
        $.ajax(
        {
            type: "POST",
            url: '/public/internationalization/change2Es',
            data:"",
            dataType:'html',
            success: function(r)
            {
                if(r==1){
                    alert("Idioma Español Activado");
                    location.reload();
                }

            }
        });

    });
    $("#idioma_en").click(function(){
        $.ajax(
        {
            type: "POST",
            url: '/public/internationalization/change2En',
            data:"",
            dataType:'html',
            success: function(r)
            {
                if(r==1){
                    alert("On English Language");
                    location.reload();
                }
            }
        });
    });


});