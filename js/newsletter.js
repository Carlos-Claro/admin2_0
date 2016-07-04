$(function(){
    
    $("#open-nw").on('click',function(){
         $(".nw-passo2").toggle('slow'); 
         $(this).toggle('slow');
    });

    $(".btn-close").on('click', function(){
        $(".nw-passo2").hide('slow');   
        $("#open-nw").show('slow');
    });
    
    $(".nw-save-button").on('click', function(){

       var nw_email = $('input[type="email"][name="nw-email"]').val();
       var empresa = '1412368565';
       var url = URI+'newsletter/';
       var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
       if(filter.test(nw_email))
       {
           $.post(url+'cadastrar_newsletter/', {email : nw_email, empresa : empresa}, function (data){
               
               if(data > 0)
               {
                   alert('Por favor, para validar seu cadastro, confirme a mensagem enviada para seu e-mail.');
                   $('input[type="email"][name="nw-email"]').val('');
                   
                   $.post(url+'confirmar_news/' , { email : nw_email, empresa : empresa }, function(data){
                       
                       console.log(data);
                   })
                   
               }
               else if(data == 0)
               {
                   alert('E-mail já cadastrado.');
               }
               else
               {
                   alert('Erro ao realizar cadastro.');
               }
           });
       }
       else
       {
           alert('Email inválido');
           $('input[type="email"][name="nw-email"]').focus();
       }
    });
    
   
});


