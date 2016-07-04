$(function(){
    
    $('.editar').on('click', function(){
        
        var url;

        if($('.valor_pai').length)
        {
            var master = window.location.href.split('/');
            var pai = ($('.valor_pai').length) ? $('.valor_pai').val() : '';
            url = URI+"canais_setor/editar_nivel_2/"+$(this).attr('data-item')+"/"+master[7]+"/"+pai;
        }
        else
        {
            url = URI+"canais_setor/editar/"+$(this).attr('data-item')+"/";
        }
        window.location.href = url;
    });

    $('.deletar').on('click',function(){
	var $selecionados = get_selecionados();
	if($selecionados.length > 0)
	{
		var $url = URI+"canais_setor/remover/";
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
    });
    
    $('#titulo').on('blur', function(){
        
        var titulo =  $(this).val();
        
        var url = URI+"canais_setor/gera_link_automatico/";
       
        $.post(url, { titulo: titulo } ,function(data){
            
            $.post(URI+'canais_setor/verifica_link/',{ link : data }, function(valor){
                
                if(valor == 0)
                {
                    $('#link').val(data);
                }
                else
                {
                    $('#link').val(valor);
                }
                console.log(valor);
                
            });
            
        });
        
    });
    
    $('#description').on('keyup',function(){

            var alvo  = $("#char-digitado");

            var max = 200;

            var digitados = $(this).val().length;

            var restante = max - digitados;

            if(digitados > max)
            {
                var val = $(this).val();
                $(this).val(val.substr(0, max));
                restante = 0;
            }

            alvo.html(restante);
    });
    
});

