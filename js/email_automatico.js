$(function(){
    
    $('.editar').on({
        click : function(){
            var url = URI+"email_automatico/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"email_automatico/remover/";
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
   
    $('.testar').on({
       
        click : function(){
            
            var id = $(this).attr('data-item');
            
            $.post(URI+'email_automatico/enviar_email_teste', {id: id}, function(data){
               
                console.log(data);
                if(data == 1)
                {
                    alert('Email de teste encaminhado com sucesso.'); 
                }
                else
                {
                    alert('Erro ao encaminhar email de teste.'); 
                }
                
            });
            
        }
        
    });
    
});


