$(function(){
    
    $('#empresa_cnpj').mask('99.999.999/9999-99');
    $('#data-retorno-inicio-in').mask('99/99/9999 99:99');
    $('#data-retorno-fim-in').mask('99/99/9999 99:99');
    
    $('#upload-form').hide();
    var id = $('input[name="id_pai"]').val();
    /*
    $('.descricao').on({
        focus : function(){
            descricao.testa();
        },
        blur : function(){
            descricao.testa();
        },
        keypress : function(){
            descricao.testa();
        },
        
    });
    
    $('.cep').on({
        keypress : function(){
            var v = $(this).val().replace('_','');
            if (v.length == 9)
            {
                $.getJSON( URI + 'empresas/get_cep/' + v ).done( function(data){
                    if ( data.length > 0 )
                    {
                        retorno = '<ul>';
                        for ( var i = 0; i < data.length; i++)
                        {
                            retorno += monta.lista(data[i]);
                        }
                        retorno += '</ul>';
                    }
                    else
                    {
                        retorno = '<p class="alert">Sem registro para este endereço, preencha manualmente<p>';
                    }
                    $('.help-endereco').html( retorno );
                }).fail(function(){ 
                    $('.help-endereco').html( '<p>Problemas para se comunicar com o servidor.</p>'  );  
                });
            }
        },
    });
    $('.endereco').on({
        keypress : function(){
            var v = $(this).val().replace(' ','_');
            
            $.getJSON( URI + 'empresas/get_endereco/' + v ).done( function(data){
                if ( data.length > 0 )
                {
                    retorno = '<ul>';
                    for ( var i = 0; i < data.length; i++)
                    {
                        retorno += monta.lista(data[i]);
                    }
                    retorno += '</ul>';
                }
                else
                {
                    retorno = '<p class="alert">Sem registro para este endereço, preencha manualmente<p>';
                    $('.id_logradouro').val(0);
                }
                $('.help-endereco').html( retorno );
                
            }).fail(function(){ 
                $('.id_logradouro').val(0);
                $('.help-endereco').html( '<p>Problemas para se comunicar com o servidor.</p>'  );  
            });
        },
    });
    $('.help-endereco').on('click', '.resposta', function(){
        var logradouro = $(this).attr('data-logradouro');
        var id = $(this).attr('data-id');
        var bairro = $(this).attr('data-bairro');
        var cidade = $(this).attr('data-cidade');
        var cep = $(this).attr('data-cep');
        $('.id_logradouro').val(id);
        $('.endereco').val(logradouro);
        $('.bairro').val(bairro);
        $('.cidade').val(cidade);
        $('.cep').val(cep);
        $('.help-endereco').html('');
        $('.numero').focus();
        
    });
    
    $('#telefone_contato').on({
        
        blur : function(){
            
            var tamanho = $(this).val().length;
            
            switch(tamanho)
            {
                case 11:
                     $(this).mask('(999)9999-9999');
                    break;
                case 12:
                    $(this).mask('(999)99999-9999');
                    break;
                default:
                    $(this).unmask();
                    break;
            }
        },
        
        focus : function(){
            
            $(this).unmask();
            
        }
        
    });
    */
   
    var dados = {};
    dados.elemento =  $('#fileupload');
    dados.status = $('#status');
    dados.resultado =  $('#img_files');
    dados.temporario =  $('#images_temp');
    dados.progresso =  $('#progresso');
    dados.id = id;
    dados.classe =  'empresas_direto';
    
    imagens.upload(dados);
    
    $('#image_tipo').on('change', function(){
        
        var tipo = $(this).val();
        var item = { classe : 'empresas_direto', tipo : tipo, id: id, resultado : '#img_files'};
        
        if(tipo != ''){
            $('#upload-form').show();
            if($('#lixo').val() == 'n'){
                imagens.getThumb(item);
            }
        }
        else{
            $('#upload-form').hide();
        }
        
    });
    
    $(document).on('click', '.deletar-upload', function(){
       
        var arquivo = $(this).attr('data-item');
        var deletar = $(this).attr('data-del');
        var path = $(this).attr('data-path');
        var lixo = $('#lixo').val();
        
        dados.arquivo = arquivo;
        dados.deletar = deletar;
        dados.lixo = lixo;
        dados.path = path;
        
        imagens.deletar(dados);
    });
    
    $(document).on('click', '.remover-upload', function(){
       
        var tipo = $('#image_tipo option:selected').val();
        var arquivo = $(this).attr('id');
        
        dados.tipo = tipo;
        dados.arquivo = arquivo;
        
        imagens.remover(dados);
    });
    
    
});

/*
var monta = {
    
    lista : function( item ){
        retorno = '<li class="resposta btn btn-info" data-logradouro="'+item.logradouro+'" data-id="'+item.id+'" data-bairro="'+item.bairro+'" data-cidade="'+item.cidade+'" data-cep="'+item.cepi+'" >';
        retorno += item.logradouro + ' - n: ' + item.n_inicio + ' até ' + item.n_final + ' - baiiro: ' + item.bairro + ' - cidade: ' + item.cidade + ' - CEP: ' + item.cepi;
        retorno += '</li>';
        return retorno;
    },
    
};


var descricao = {
    testa : function(){
        var d = $('.descricao').val().length;
            $('.qtde-caracteres').html(d);
            if ( d > 60 )
            {
                $('.qtde-caracteres').removeClass('text-success').addClass('text-danger');
                $('.mensagem-erro').html('Qtde de caracteres maior que o suportado, favor resuma o texto para aparecer inteiro.').removeClass('text-success').addClass('text-danger');
            }
            else
            {
                $('.qtde-caracteres').addClass('text-success').removeClass('text-danger');
                $('.mensagem-erro').html('Qtde válida').addClass('text-success').removeClass('text-danger');
            }
    }
};
*/