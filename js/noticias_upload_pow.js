$(function(){
   var data = new Array();
        data.classe = '.upload';
        data.acao = URI + 'anexos/upload_temporario/';
        data.input = 'upload';
        data.multiple = true;
        data.resposta_classe = '.status';
        data.resposta_type = 'json';
        data.pasta = '/admin2_0/js/upload/';
        data.extra = '';
        data.limite_kb = '1048576';
        data.type = 'jpeg|jpg|png|gif';
        upload.inicia(data);
    $('.status').on('click','.deleta-image', function(){
        var item = $(this).attr('data-item');
        arquivo.deleta_temporario(item);
    });
   
});
var arquivo = {
    acao: function( data ){
        $.each(data,function( k, v ){
            console.log(k,v);
            if ( v.erro )
            {
                $('.arquivo.elemento-' + v.chave ).append(v.mensagem);
                $('.arquivo.elemento-' + v.chave + ' .espaco-carregando' ).remove();
            }
            else
            {
                var conta = $('.arquivo-carregado').length;
                var html = '';
                $('.arquivo.elemento-' + v.chave ).addClass('col-lg-4 col-md-4 col-sm-4 col-xs-6');
                html += '<center><img src="' + URL_RAIZ + 'images/upload/' + v.arquivo + '" class="img-responsive arquivo-exibe-upload"></center>';
                html += '<input type="hidden" name="image[' + conta + '][nome]" value="' + v.arquivo + '" class="arquivo">';
                html += '<div class="form-group">';
                html += '<label>Titulo da imagem:</label>';
                html += '<input type="text" name="image[' + conta + '][descricao]" value="" class="form-control"><br>';
                html += '<div class="deleta-image btn btn-danger" data-item="' + conta + '">Remover esta imagem</div>';
                html += '</div>';
                
                $('.arquivo.elemento-' + v.chave ).html(html);
                $('.arquivo.elemento-' + v.chave ).removeClass('arquivo').addClass('arquivo-carregado').removeClass('elemento-' + v.chave).addClass('elemento-' + conta).attr('data-item',conta);
                
            }
        });
    },
    deleta_temporario: function(sequencia){
        var arquivo = $('.elemento-' + sequencia + ' .arquivo').val();
        var post_ = {'arquivo':arquivo,'sequencia':sequencia};
        var url = URI + 'anexos/deleta_temporario';
        $.post(url,post_,function(data){
            console.log(data);
            if ( data.erro )
            {
                alert(data.mensagem);
            }
            else
            {
                $('.status .elemento-' + data.id).remove();
            }
        },'json');
    },
};

