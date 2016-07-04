$(document).ready(function(){
    date.retorna_unix('.data-inicio','.data-inicio-unix');
    date.retorna_unix('.data-fim','.data-fim-unix');
});
var date = {
    retorna_unix: function(campo,hidden){
        var val = $(campo).val();
        if ( val != undefined )
        {
            val = val.replace('_','');
            if ( val.length == 16 )
            {
                var url = URI + 'noticias/get_timeunix/';
                var dados = {valor: val};
                $.post(url,dados,function(data){
                    $(hidden).val(data);
                });
            }
        }
    },
};

$(function(){
   
   $('.remover-image').on('click',function(){
       var confirma = confirm('Tem certeza que deseja deletar esta imagem?');
       if ( confirma === true )
       {
            var id = $(this).attr('data-item');
            var noticia = $(this).attr('data-noticia');
            var url = URI + 'noticias/deleta_image';
            $.post(url,{'noticia':noticia,'id':id},function(data){
                if( data.erro == false )
                {
                    $('.image-' + data.id).remove();
                }
                else
                {
                    alert(data.mensagem);
                }
            },'json');
        }
   });
   
    $('.data-inicio').on('keyup',function(){
        date.retorna_unix('.data-inicio','.data-inicio-unix');
    });
    $('.data-fim').on('keyup',function(){
        date.retorna_unix('.data-fim','.data-fim-unix');
    });
    
    $('.editar').on({
        click : function(){
            var url = URI+"noticias/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    
    //var id = $('input[name="id_pai"]').val();

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"noticias/remover/";
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
    
    /**
     * 
     */
    $('.marcar-vitrine').on({
        click : function(){
            var selecionados = get_selecionados();
            if(selecionados.length > 0)
            {
                    var $url = URI+"noticias/marcar_vitrine/";
                    if (window.confirm("deseja marcar os ("+selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': selecionados}, function(data){
                                     pop_up(data, setTimeout(function(){location.reload()}, 500));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
    $('.desmarcar-vitrine').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"noticias/desmarcar_vitrine/";
                    if (window.confirm("deseja desmarcar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                     pop_up(data, setTimeout(function(){location.reload()}, 500));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
    $('.marcar-vitrine-canal').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"noticias/marcar_vitrine_canal/";
                    if (window.confirm("deseja marcar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                     pop_up(data, setTimeout(function(){location.reload()}, 500));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
    $('.desmarcar-vitrine-canal').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"noticias/desmarcar_vitrine_canal/";
                    if (window.confirm("deseja desmarcar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                     pop_up(data, setTimeout(function(){location.reload()}, 500));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
});

    /* deprecated upload antigo
    $('#upload-form').hide();
    var dados = {};
    dados.elemento =  $('#fileupload');
    dados.status = $('#status');
    dados.resultado =  $('#img_files');
    dados.temporario =  $('#images_temp');
    dados.progresso =  $('#progresso');
    dados.id = id;
    dados.classe =  'noticias';
    
    imagens.upload(dados);
    $('#image_tipo').on('change', function(){
        
        var tipo = $(this).val();
        var item = { classe : 'noticias', tipo : tipo, id: id, resultado : '#img_files'};
        
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
    */