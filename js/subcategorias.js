$(function(){
   
   
    $('#data').mask('99-99-9999');
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"subcategorias/editar/"+$(this).attr('data-item')+"/"+$('.valor_pai').val();
            window.location.href = url;
            /*
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var url = "";
                    for ($i=0; $i < $selecionados.length; $i++)
                    {
                            url = URI+"subcategorias/editar/"+$selecionados[$i];
                            window.open(url);
                    }
            }
            else
            {
                    alert('nenhum item selecionado');
            }*/
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"subcategorias/remover/";
                    if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                    pop_up(data, setTimeout(function(){location.reload()}, 100));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
     $('.titulo').on('blur',function(){
         
        var valor = $(this).val();
	var id = $('.id').val();
        
        $.post(URI +'subcategorias/gera_link/',{ 'valor' : valor, 'id' : id}, function(data)
        {
                $('.link').val(data);
        });
    });
    
});


