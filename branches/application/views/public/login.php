<script type="text/javascript">
    $(document).mouseup(function() {

        
        
        $("#loginform").mouseup(function() {
            return false
        });

        $("a.close").click(function(e){
            e.preventDefault();
            $("#message").show();
            $("#loginform").hide();
            $(".lock").fadeIn();
        });

        if ($("#loginform").is(":hidden"))
        {
            $(".lock").fadeOut();
        }
        else{
            $(".lock").fadeIn();
        }

        $("#loginform").toggle();

        $("input#cancel_submit").click(function(e) {
            $("#message").show();
            $("#loginform").hide();
            $(".lock").fadeIn();
        });
        //FIN Animación del formulario de login

        $("#error").hide();

        //INICIO submit form
        $('form#frmLogin').submit(function(){
            //Validacón de usuario y password
            var username = $("input#username").val();
            if (username == "") {
                //$('#message').css("color","red").html("Todos los campos son Requeridos");
                //$("#message").hide().fadeIn(500);
                $("#message").hide();
                $("#error").hide().fadeIn(500);
                $("input#username").focus();
                return false;
            }
            var password = $("input#password").val();
            if (password == "") {
                //$('#message').css("color","red").html("Todos los campos son Requeridos");
                //$("#message").hide().fadeIn(1500);
                $("#message").hide();
                $("#error").hide().fadeIn(500);
                $("input#password").focus();
                return false;
            }
            
            var postFile = '/private/authentication/login';
            var dataString = "usernamePost="+username+"&passwordPost="+password;
            
            $.ajax({
                type: "POST",
                url: postFile,
                data:dataString,
                dataType:'html',
                success: function(rsp){
                    var status = rsp.split("|")[0];
                    var url = rsp.split("|")[1];
                    var msg = rsp.split("|")[2];
                    if(status==1){
                        var distance = 10;
                        var time = 500;
                        var myTimer = {};
                        $("#loginform").animate({
                            marginTop: '-='+ distance +'px',
                            opacity: 0
                        }, time, 'swing', function () {
                            $("#loginform").hide();
                        });

                        myTimer = $.timer(1000,function() {
                            window.location=url;
                        });
                    }else{
                        $("#message").hide();
                        $("#error").html(msg.toString());
                        $("#error").hide().fadeIn(1500);
                        $("input#username").val('');
                        $("input#password").val('');
                        $("input#username").focus();
                        
                    }
                }
            });
             return false;
        });
        //FIN submit form

    });
</script>

<div class="box lock"> </div>
<div id="loginform" class="box form">
    <h2><?php echo __("Autorización Zeratul Inventory"); ?> <a href="javascript:void(0)" class="close">Close it</a></h2>
    <div class="formcont">
        <fieldset id="signin_menu" >
            <span class="message" id="message"><?php echo __("Por favor ingrese sus datos"); ?></span>
            <span><label class="error" id="error"><?php echo __("Todos los campos son requeridos") ?></label></span>
            <form method="post" id="frmLogin" action="">
                <label for="username"><?php echo __("Nombre de usuario o email"); ?></label>
                <input id="username" name="username" value="" title="<?php echo __("Nombre de usuario o email"); ?>" class="required" tabindex="4" type="text">
                <p>
                    <label for="password"><?php echo __("Contraseña"); ?></label>
                    <input id="password" name="password" value="" title="<?php echo __("Contraseña"); ?>" class="required" tabindex="5" type="password">
                </p>
                <p class="clear"></p>
                <a href="#" class="forgot" id="resend_password_link"><?php echo __("¿Olvidó su contraseña?"); ?></a>
                <p class="remember">
                    <input id="signin_submit" value="<?php echo __("Iniciar sesión"); ?>" tabindex="6" type="submit">
                    <input id="cancel_submit" value="<?php echo __("Cancelar"); ?>" tabindex="7" type="button">
                </p>
            </form>
        </fieldset>
    </div>
    <div class="formfooter"></div>
</div>