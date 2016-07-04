$(function(){
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"cadastro_promocao/editar/"+$(this).attr('data-item');
            window.location.href = url;
            
        }
    });
    
    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"cadastro_promocao/remover/";
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
    
    $('.sortear').on({
        click : function(){
           
            var url = URI+"cadastro_promocao/sortear/"+$(this).attr('data-item');
            window.location.href = url;
        }
    });
    
    $("#qtde").blur(function(){
        if($(this).val() == "")
        {
            $('.qtdeobrigatoria').html('Nenhuma quantidade informada');
        }
        else
        {
            $('.qtdeobrigatoria').html('');
        }
    });
    
    $('#gerar').on({
        click : function(){
            var id = $(this).attr('data-item');
            var qtde = $('#qtde').val();
            var $url = URI+"cadastro_promocao/gerar_vencedor/"+id+"/"+qtde;
           
            if(qtde == '')
            {
                $('.qtdeobrigatoria').html('Nenhuma quantidade informada');
            }
            $.post($url, function(data){
                var sorteio = '';
                $.each(data, function(chave, valor){
                    sorteio +='<div class="row elementos elemento-'+valor.id+'">';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="nome">Nome</label>';
                    sorteio +='<input name="nome" type="text" disabled="disabled" class="form-control" id="nome" placeholder="Nome"  value="'+valor.nome+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="email">E-mail</label>';
                    sorteio +='<input name="email" type="text" disabled="disabled" class="form-control" id="email" placeholder="E-mail"  value="'+valor.email+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="fone">Telefone</label>';
                    sorteio +='<input name="fone" type="text" disabled="disabled" class="form-control" id="fone" placeholder="Telefone"  value="'+valor.fone+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="data_cadastro">Data cadastro</label>';
                    sorteio +='<input name="data_cadastro" type="text"  disabled="disabled" class="form-control" id="data_cadastro" placeholder="Data cadastro"  value="'+valor.data_cadastro+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<button type="button" class="btn btn-warning confirmar" data-item="'+valor.id+'">Confirmar</button>';
                    sorteio +=' <button type="button" class="btn btn-warning cancelar" data-item="'+valor.id+'">Cancelar</button>';
                    sorteio +=' </div></div></div>';
                    
                });
                $('.esconde').html(sorteio);
            },'json');
        } 
    });
    
    var vencedores = {
        vencer : function (){
            var id = $('.idpromocao').attr('data-item');
            var $url2 = URI+"cadastro_promocao/vencedor/"+id;
            $.post($url2, function(data){
                var sorteio = '';
                $.each(data, function(chave, valor){
                    sorteio +='<div class="row elements element-'+valor.id+'">';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="nome">Nome</label>';
                    sorteio +='<input name="nome" type="text" disabled="disabled" class="form-control" id="nome" placeholder="Nome"  value="'+valor.nome+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="email">E-mail</label>';
                    sorteio +='<input name="email" type="text" disabled="disabled" class="form-control" id="email" placeholder="E-mail"  value="'+valor.email+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="fone">Telefone</label>';
                    sorteio +='<input name="fone" type="text" disabled="disabled" class="form-control" id="fone" placeholder="Telefone"  value="'+valor.fone+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<label for="data_cadastro">Data cadastro</label>';
                    sorteio +='<input name="data_cadastro" type="text"  disabled="disabled" class="form-control" id="data_cadastro" placeholder="Data cadastro"  value="'+valor.data_cadastro+'">';
                    sorteio +='</div></div>';
                    sorteio +='<div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    sorteio +='<div class="form-group">';
                    sorteio +='<button type="button" class="btn btn-warning cancela" data-item="'+valor.id+'">Cancelar</button>';
                    sorteio +='</div></div></div>';
                });
                $('.esconde2').html(sorteio);
                if($('.elements').length > 0)
                {
                    $('#gerar').text('Re-sortear');
                }
            }, 'json');
        }
    };
    
    $('.esconde2').html(vencedores.vencer());
    
    $('.esconde').on('click','.confirmar',function(){
        var id = $(this).attr('data-item');
        
        var $url = URI+"cadastro_promocao/confirmar_vencedor";
        $.post($url,{
           'id' : id 
        }, function(data){
            console.log(data);
        }, 'json');
        
        $('.elemento-'+id).remove();
        setTimeout(function(){
            $('.esconde2').html(vencedores.vencer());
            $('#gerar').text('Re-sortear');
        },100);
    });
    
    $('#limpar').on('click', function(){
       var sim = confirm('Tem certeza que deseja limpar todos os ganhadores ?'); 
       console.log(sim);
       if(sim)
       {
            var id = $(this).attr('data-item');
            $('.elements').remove();
            $('.elementos').remove();
            var $url = URI+"cadastro_promocao/limpar_vencedores";
            $.post($url,{
               'id' : id 
            }, function(data){
                console.log(data);
            },'json');
            $('#gerar').text('Sortear');
       }
    });
    
    $('.esconde2').on('click','.cancela', function(){
       var sim = confirm('Tem certeza que deseja limpar este ganhador ?'); 
       console.log(sim);
       if(sim)
       {
            var id = $(this).attr('data-item');
            $('.element-'+id).remove();
            var $url = URI+"cadastro_promocao/limpar_vencedor";
            $.post($url,{
               'id' : id 
            }, function(data){
                console.log(data);
            },'json');
            if($('.elements').length == 0)
            {
                $('#gerar').text('Sortear');
            }
       }
    });
    
    $('.esconde').on('click','.cancelar', function(){
        var id = $(this).attr('data-item');
        $('.elemento-'+id).remove();
    });

    $('#description').on('keyup',function(){

            var alvo  = $("#char-digitado");

            var max = 140;

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

    
    $(document).on('click','.deleta-image',function(){
        var campo = $(this).attr('data-id-pai-arquivo');
        var conf = confirm('Confirma a exclusão da imagem ');
        if ( conf )
        {
            var url = URL_HTTP + 'anexos/remover_image/json';
            
            $.post(url, {'id' : campo }, function(data){
                if ( data.status )
                {
                    
                    alert('Deletado com sucesso...');
                    $('.espaco-thumbs').html('');
                    $('.espaco-arquivos').removeClass('hide');
                    
                }
                else
                {
                    alert('Não conseguimos deletar... tente novamente.');
                }
            },'json');
        }
    });
    
    
});

$(document).ready(function(){
    var id = $('.id').val();
    if ( id !== undefined )
    {
        arquivo.carrega_form();
    }
});

var retorno_image = {
    acao : function(data){
        var id = $('.id').val();
            var caminho = URL_IMAGES + data.pasta + data.arquivo;
            var html = '<center><img src="' + caminho + '" class="image" data-id-pai="' + data.id_pai + '" >';
            html += '</center>';
            html += '<button type="button" class="btn btn-danger form-control deleta-image" data-id-pai="' + data.id_pai + '" >Deletar</button>';
            $('.espaco-thumbs').html(html);
            $('.espaco-arquivos').addClass('hide');
    },
};
var arquivo = {
    acao: function( data ){
        console.log(data);
        if ( ! data.erro )
        {
            window.parent.retorno_image.acao(data);
            
        }
        else
        {
            alert(data.message);
            $('.espaco-carregando').html('Um erro ocorreu, tente novamente.');
            setTimeout(function(){
                $('.status').html('');

            },5000);
        }
    },
    carrega_form : function(){
        /**
        * @version 1.1
        * @since 06/04/2015
        * processo de upload
        * @type Array
        */
        var data = new Array();
            data.funcao_acao = 'arquivo';
            data.classe = '.upload';
            data.acao = URL_HTTP + 'anexos/upload_image_com_resposta';
            data.input = 'upload';
            data.multiple = false;
            data.resposta_classe = '.status';
            data.resposta_type = 'json';
            data.pasta = URL_HTTP + 'js/upload/';
            data.extra = '<input type="hidden" name="id_pai" value="' + $('.espaco-arquivos').attr('data-id-pai') + '">';
            data.extra += '<input type="hidden" name="tipo" value="' + $('.espaco-arquivos').attr('data-tipo') + '">';
            data.limite_kb = '1886080';
            data.type = $('.espaco-arquivos').attr('data-formatos');
            upload.inicia(data);

    },
};
    
    
    