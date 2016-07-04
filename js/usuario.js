$(function(){
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"usuario/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"usuario/remover/";
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
    
    
    $('.senha').on({
        blur : function(){
            var valor = $(this).val();
            console.log(valor);
            if ( valor != '' )
            {
                $(this).parent('.form-group').addClass('has-success');
                
            }
            else
            {
                $(this).parent('.form-group').addClass('has-warning');
            }
        }
    });
    $('.resenha').on({
        keyup : function(){
            
        }
    });
    
    var valor_sel = new Array();
    var valor_set = new Array();
    
    $('.sel').each(function(i){
         valor_sel[i] = $(this).attr('data-item');
    });
    
    $('.setores').each(function(i){
         valor_set = $(this).attr('data-item');
         if(jQuery.inArray(valor_set, valor_sel) ==-1)
         {
             $(this).show();
         }
         else
         {
             $(this).hide();
         }
    });
    
    
    $('.setores').on({
        click: function(e){
            $('.mensagens').html('').removeClass('text-warning');
            var data = {};
            data.id_setor = $(this).attr('data-item');
            data.id_usuario = $('.id').val();
            var titulo = $(this).html();
            var existe = $('.selecionados').html();
            var montado = monta(data.id_setor,titulo);
            var url = URI + 'usuario/has_setores/';
            $('.mensagens').html('').removeClass('text-warning');
            $.post(url, data, function(resposta){
                console.log(resposta);
                if ( resposta.erro.status )
                {
                    $('.mensagens').html(resposta.erro.message).addClass('text-danger');;
                    setTimeout(function(){
                        $('.mensagens').html('').removeClass('text-danger');
                    },5000);
                }
                else
                {
                    $('.selecionados').html(existe + montado);
                    $('.setores[data-item="' + data.id_setor + '"]').remove();
                }
            },'json');
        }
    });
    
    $('.selecionados').on('click', '.close', function(e){
            $('.mensagens').html('').removeClass('text-warning');
            var data = {};
            data.id_setor = $(this).attr('data-item');
            data.id_usuario = $('.id').val();
            var url = URI + 'usuario/deleta_has_setores/';
            $('.mensagens').html('').removeClass('text-warning');
            $.post(url, data, function(resposta){
                if ( resposta.erro.status )
                {
                    $('.mensagens').html(resposta.erro.message).addClass('text-danger');;
                    setTimeout(function(){
                        $('.mensagens').html('').removeClass('text-danger');
                    },5000);
                    
                }
                else
                {
                    $('.selecionado-' + data.id_setor ).remove();
                    //$('.setores[data-item="'+item+'"]').show();
                    
                }
            },'json');
    });
    
    $(document).on('click','.checkbox',function(){
        var data = {};
        data.id_setor = $(this).attr('data-setor');
        data.id_usuario = $('.id').val();
        data.edita =  ($( this ).is( ":checked" )) ? 1 : 0;
        var url = URI + 'usuario/has_setores/';
        $.post(url, data, function(resposta){
            if ( resposta.erro.status )
            {
                $('.mensagens').html(resposta.erro.message).addClass('text-danger');;
                setTimeout(function(){
                    $('.mensagens').html('').removeClass('text-danger');
                },5000);

            }
        },'json');
        
        var item = $(this).attr('data-item');
        
    });
    
    $('.cronograma-requisita').on('click', function(){
        var id = $('.id').val();
        var url = URI + 'usuario/monta_cronograma/' + id;
        $.post(url,function(data){
            if ( data.status )
            {
                $('.espaco-cronograma').html('').html(data.cronograma);
                $('.espaco-cronograma').addClass('show').removeClass('hide');
            }
            else
            {
                alert(data.mensagem);
            }
        },'json').fail(function(e,r){
            alert('Problemas ao adquirir tarefas, tente novamente. ' + e + r);
        });
    });
});



function monta ( id, titulo)
{
    var retorno = '<div class="row form-group alert alert-success selecionado-' + id + '" data-item="' + id + '" id="' + id + '">';
    retorno += '<label class="pull-left col-lg-7 col-md-7 col-sm-7 col-xs-7">';
    retorno += titulo;
    retorno += '</label>';
    retorno += '<button type="button" class="close pull-right col-lg-2 col-md-2 col-sm-2 col-xs-2" data-item="' + id + '" aria-hidden="true" >&times;</button>'; 
    retorno += '<input type="checkbox" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 checkbox pull-left form-group setor-' + id + '" data-setor="' + id + '" data-usuario="' + $('.id').val() + '" value="1"  title="Pode Editar">';
    retorno += '</div>\n'; 
    
                                        
                                        
    
    
    
    return retorno;
}



