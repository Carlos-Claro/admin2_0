/**
 * 
 */

$(function(){
    $('.verificar').on({
        click : function(){
            var valor = $('#cnpj').val();
            console.log(valor);
            $.post(URI + 'login/get_senha_empresa/', { 'cnpj' : valor },  function(data){
                $('.verificado').html(data);
            });
        }
        
    });

});

