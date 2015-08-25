<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="/media/js/jquery-1.4.2.min.js"></script>
        <title></title>
        <script type="text/javascript">
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
            
        </script>
    </head>
    <body>
        <?php echo $content; ?>
        <a id="idioma_en" style="cursor: pointer;" >Inglés</a>
        <a id="idioma_es" style="cursor: pointer;">Español</a>
    </body>
</html>
