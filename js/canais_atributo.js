$(function(){
    
    var canal = $('#id_canais').val();
    var checks = new Array('a');
    
    camadas.get_camadas(canal, checks);
    
    $('.tipo').on('click',function(){
        
        var tipo = $(this).val();
        
        $.getJSON(URI+'canais_atributo/get_tipos/'+tipo, function(data){
           
            console.log(data);
            var option = '<label for="id_canais_tipo_'+tipo+'">Tipo</label>';
            option += '<select name="id_canais_tipo_'+tipo+'" class="form-control" id="id_canais_tipo">';
            $('#resposta-tipo').html('');
            $(data).each(function(i){
                option += '<option value="'+data[i].id+'">'+data[i].descricao+'</option>'
            });
            option += '</select>';
            $('#resposta-tipo').html(option);
            
        });
        
    });
    
    $('.camada').on('click', function(){
        
        $('.elementos').hide();
        
        if($(this).is(':checked'))
        {
           checks.push($(this).val()); 
        }
        else
        {
            var retira = checks.indexOf($(this).val());
            checks.splice(retira,1);
        }
        camadas.get_camadas(canal, checks);
    });
   
    
    $(document).on('click', '.temp_elemento', function(){
       
        var id = $(this).attr('data-item');
        
        $.getJSON(URI+'canais_atributo/get_elementos/'+id, function(data){
            
            $('.elementos').find('input').attr('data-item', id);
            $('.elementos').val(data.qtde);
            $('.elementos #qtde').val(data.qtde);
            $('.elementos #ordem').val(data.ordem);
            $('.elementos #n_coluna_lg_sm').val(data.n_coluna_lg_sm);
            $('.elementos #n_coluna_md').val(data.n_coluna_md);
            $('.elementos #n_coluna_xs').val(data.n_coluna_xs);
            $('.elementos #tipo_ordem').val(data.tipo_ordem);
            $('.elementos #titulo').val(data.titulo);
            $('.elementos #qtde_caracteres_descricao').val(data.qtde_caracteres_descricao);
            $('.elementos #qtde_colunas').val(data.qtde_colunas);
            $('.elementos #classe').val(data.classe);
            $('.elementos #classe_master').val(data.classe_master);
            $('.elementos #posicao_image').val(data.posicao_image);
            if(data.link_mais == 1)
            {
                $('.elementos #link_mais').prop('checked', true);
            }
            else
            {
                 $('.elementos #link_mais').prop('checked', false);
            }
            if(data.titulo_exibe == 1)
            {
                $('.elementos #titulo_exibe').prop('checked', true);
            }
            else
            {
                 $('.elementos #titulo_exibe').prop('checked', false);
            }
            if(data.mostra_estrela == 1)
            {
                $('.elementos #mostra_estrela').prop('checked', true);
            }
            else
            {
                 $('.elementos #mostra_estrela').prop('checked', false);
            }
            $('.elementos').show();
           
        });
        
    });
    
    $('#btn-submit').on('click', function(){
       
        var id;
       
        var campo = [];
        var valor = [];
        
        $('.submit-form').each(function(k, v){
            
            id = $(this).attr('data-item');
            
            campo.push($(this).attr('name'));
            
            if($(this).attr('type') == 'checkbox')
            {
                if($(this).is(':checked'))
                {
                    valor.push($(this).val());
                }
                else
                {
                    valor.push('0');
                }
            }
            else
            {
               valor.push($(this).val());
            }
            
        });
        //console.log(id);
        
        
        $.post(URI+'canais_atributo/editar_atributo/', { id: id, campo : campo, valor: valor }, function(data){
                
                camadas.get_camadas(canal, checks);
            
        });
       
        
       
        /*
        if(valor != null)
        {
            $.post(URI+'canais_atributo/editar_atributo/', { id: id, valor: valor, name : name }, function(data){

                camadas.get_camadas(canal, checks);
            });
        }*/
        
    });
    
    /*
    $('.submit-form').on('blur', function(){
       
        var valor = null;
        var name = $(this).attr('name');
        var id = $(this).attr('data-item');
        
        if($(this).attr('type') == 'checkbox')
        {
            if($(this).is(':checked'))
            {
                valor = $(this).val();
            }
            else
            {
                valor = '0';
            }
        }
        else
        {
            valor = $(this).val();
        }
        
        if(valor != null)
        {
            $.post(URI+'canais_atributo/editar_atributo/', { id: id, valor: valor, name : name }, function(data){

                camadas.get_camadas(canal, checks);
            });
        }
        
    });*/
    
});

var camadas = {
    
    retorno : '',
    
    get_camadas: function(canal, camada)
    {
        if(canal != '' && camada != '')
        {
            $.post(URI+'canais_atributo/get_camadas/', { canal: canal, camada : camada}, function(data){
                
                $('.preview').html('');
                
                var preview = '';
                $(data).each(function(i)
                {
                    //var largura = 'width:'+data[i].n_coluna_lg_sm * 50+'px;';
                    preview += '<div class=" redimensionar col-lg-'+data[i].n_coluna_lg_sm+' col-sm-'+data[i].n_coluna_lg_sm+' col-md-'+data[i].n_coluna_md+' col-xs-'+((data[i].n_coluna_lg_sm == 12) ? '11' : data[i].n_coluna_xs )+'">';
                    //preview += '<div class="ui-widget-content redimensionar" style="'+largura+'; height:38px; max-height:38px; display:inline-block;">';
                    preview += '<div class="atributos">';
                    preview += '<h4 class="temp_elemento ui-widget-content" data-item="'+data[i].id+'">'+data[i].titulo+'</h4>';
                    preview += '</div>';
                    preview += '</div>';
                });
                $('.preview').html(preview);
                
            }, 'json');
        }
        else
        {
            $('.preview').html('');
        }
    }
}