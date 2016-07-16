/**
 * 
 */

$(function(){
    $('.verificar').on({
        click : function(){
            var valor = $('#mail').val();
            console.log(valor);
            $.post(URI + 'login/esqueceu/', { 'email' : valor },  function(data){
                $('.verificado').html(data);
            });
        }
        
    });

});

