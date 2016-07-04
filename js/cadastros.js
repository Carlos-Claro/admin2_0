$(function(){
   
    $('.editar').on({
        click : function(){
            
            var url = URI+"cadastros/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
            /*
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var url = "";
                    for ($i=0; $i < $selecionados.length; $i++)
                    {
                            url = URI+"cadastros/editar/"+$selecionados[$i];
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
                    var $url = URI+"cadastros/remover/";
                    if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                    pop_up(data, location.reload());
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
    $('#email').on('blur', function()
    {
        var email = $(this).val();
        var url = URI+'cadastros/pesquisar_cadastro';
        $.getJSON( url, { 'email': email } )
            .done(function( data ) {
                $('#nome').val(data.nome);
                $('#id_canal').val(data.id_canal);
                $('#nascimento').val(data.nascimento);
                $('#fone').val(data.fone);
                $('#cep').val(data.cep);
                $('#endereco').val(data.endereco);
                $('#complemento').val(data.complemento);
                $('#cidade').val(data.cidade);
                $('#estado').val(data.estado);
                $('#bairro').val(data.bairro);
                if(data.sexo == 'F')
                {
                    $('.feminimo').prop('checked', true);
                    $('.masculino').prop('checked', false);
                }
                else if(data.sexo == 'M')
                {
                    $('.feminimo').prop('checked', false);
                    $('.masculino').prop('checked', true);
                }
                
        })
            .fail(function( jqxhr, textStatus, error ) {
                //var err = textStatus + ", " + error;
                //console.log( "Request Failed: " + err );
                $('#nome').val('');
                $('#id_canal').val('');
                $('#nascimento').val('');
                $('#fone').val('');
                $('#cep').val('');
                $('#endereco').val('');
                $('#complemento').val('');
                $('#cidade').val('');
                $('#estado').val('');
                $('#bairro').val('');
                $('.feminimo').prop('checked', false);
                $('.masculino').prop('checked', false);
                alert('Email não cadastrado');
        });
        
    });
    
    $('#cep').on('blur', function(){
        
        var cep = $(this).val();
        var url = 'http://cep.republicavirtual.com.br/web_cep.php?cep='+cep+'&formato=json';
        
        $.getJSON(url, function(data)
        {
            $('#endereco').val(data.tipo_logradouro+' '+data.logradouro);
            $('#cidade').val(data.cidade);
            $('#estado').val(data.uf);
            $('#bairro').val(data.bairro);
        })
        
    })
    
});

jQuery(function($){
    $('#fone').mask('(99)9999-9999');
    $('#cep').mask('99999-999');
    $('#nascimento').mask('9999-99-99');
});