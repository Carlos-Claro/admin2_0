$(function(){
   
    var uf = $('#estados option:selected').val();
    var id_cidade = $('#cidade_hidden').val();
   
    $('.editar').on({
        click : function(){
            var url = URI+"imoveis_naoencontrei/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    get.estados(uf, id_cidade);
    
    $('#estados').on('change', function(){
        var valor = $(this).val();
        get.estados(valor);
    });

});

var get = {
    
    estados : function(uf, selecionado){
        
        $.post(URI+'imoveis_naoencontrei/get_cidade', { uf : uf } ,function(data){
           
            console.log(data);
            if(data){
                
                helper.select('Cidades', 'id_cidade', data, selecionado);
                $('#cidades_select').html(helper.montado);
            }
            
        }, 'json');
        
    }
}
