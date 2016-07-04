$(function(){
        
        var status = $('.status');
        var id_image_tipo;
        var id_pai = $('#id_pai').val();
        
        $('#fileupload').fileupload({
            
            dataType: 'json',
            
            submit: function (e, data){
            
                $.each(data.files, function (index, file) {

                    var ext = file.type.split('/');
                    if (! (ext[1] && /^(jpg|png|jpeg|gif|pdf|doc|docx)$/.test(ext[1])))
                    { 
                        status.show();
                        status.addClass('alert alert-danger');
                        status.text('Somente JPG, PNG, GIF, PDF e DOCX são permitidas');
                        return false;
                    }
                    else
                    {
                        status.hide();
                    }
                });
            },
            
            done: function (e, data) {
                
                //Adicionar arquivo carregado na lista
                var r = data.result.split("-");
                var div_img;
                
                if(r[0]==="success")    
                {
                    var url = 'http://www.guiasjp.com/';
                    div_img = '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 img-temp-' + r[1] + '">';
                    div_img += '<input type="hidden" name="id_image_arquivo" class="id_image_arquivo" value="' + r[1] + '" />';
                    
                    var src = '';
                    
                    if(r[3].search('.pdf') !== -1)
                    {
                        src += 'images/icone-pdf.png';
                    }
                    else if(r[3].search('.doc') !== -1)
                    {
                         src += 'images/icone-doc.png';
                    }
                    else
                    {
                        src = (r[2].replace('[id]', id_pai)) + r[3];
                    }
                    //div_img += '<img class="img-responsive" style="max-height:100px;" src="'+url+r[2]+r[3]+'">';
                    div_img += '<img class="img-responsive" style="max-height:100px;" src="'+url+src+'">';
                    div_img += '<div class="row">'
//                    div_img += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">'
//                    div_img += '<label for="titulo-' + r[1] + '">Titulo</label>';
//                    div_img += '<input type="text" class="form-control" name="titulo-' + r[1] + '" id="titulo-' + r[1] + '">';
//                    div_img += '</div>';
                    div_img += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">'
                    div_img += '<label for="descricao-' + r[1] + '">Descrição</label>';
                    div_img += '<input type="text" class="form-control" name="descricao-' + r[1] + '" id="descricao-' + r[1] + '">';
                    div_img += '</div>';
                    div_img += '</div>';
                    div_img += '<button class="btn btn-primary salvar" data-id="' + r[1] + '" type="button" >Salvar</button> ';
                    div_img += '<button class="btn btn-danger deleta_image" data-id="' + r[1] + '" type="button" >Deletar</button>';
                    div_img += '</div>';
                    $('#img_files').append(div_img).addClass('success');
                    $('#progress').html('');
                }
                else
                {
                    $('#img_files').html('Erro ao Inserir o arquivo tente novamente').addClass('error');
                }
            },
            
            progressall: function(e, data){

                var progress = parseInt(data.loaded / data.total * 100, 10);
                var div_progress = '<div class="progress">';
                div_progress += '<div class="progress-bar progress-bar-striped active"  role="progressbar" aria-valuenow="'+data.loaded+'" aria-valuemin="0" aria-valuemax="100" style="width: '+progress+'%">';
                div_progress += '<span class="sr-only">'+progress+'% Complete</span>';
                div_progress += '</div>';
                div_progress += '</div>';
                $('#progress').html(div_progress);
            }
        });
   
        $('#id_image_tipo').on('change', function()
        {
            if($(this).val() != '')
            {

                $('.opcoes-form').show();
                $('.images').show();

                id_image_tipo = $('#id_image_tipo option:selected').val();
                
                thumbnail.getThumb(id_image_tipo, id_pai);
            }
            else
            {
                $('.opcoes-form').hide();
                $('.images').hide();
                id_image_tipo = '';
            }
        });

        $('.opcoes-form').hide();
        $('.images').hide();
});

$(document).on('click','.deleta_image', function(){
    
        var id_image_arquivo = $(this).attr('data-id');
        var familia = $('input[type=hidden][name=familia]').val();
        var id_pai = $('#id_pai').val();
        var url = URI + 'anexos/deleta_image';

        $.post(url, { 'id_image_arquivo': id_image_arquivo, id_pai: id_pai, familia : familia}, function(data){
            if ( data > 0 )
            {
                $('.img-temp-'+id_image_arquivo).remove('');
                $('#progress').html('');
            }
            else
            {
                alert('problemas ao deletar o arquivo, tente novamente.');
            }
        });
});

$(document).on('click', '.deleta',function(){
    
        var id_image_tipo = $('#id_image_tipo option:selected').val();
        var id_pai = $('#id_pai').val();
        var id_image_arquivo = $(this).attr('id');
        
        
	if (window.confirm("deseja apagar o vinculo com o item selecionado? obs: para deletar permanentemente a imagem da lista, execute esta tarefa pelo gerenciador de imagens."))
	{
            $.post(URI+'anexos/remover_image/', {'id_image_arquivo': id_image_arquivo, id_pai : id_pai }, function(data){

                if(data)
                {
                    thumbnail.getThumb(id_image_tipo, id_pai);
                    $('#progress').html('');
                }
                else
                {
                    alert('Erro ao excluir. Tente novamente');
                }
                    //pop_up(data, location.reload());
            });
	}

});

$(document).on('click', '.salvar',function(){
    
        var id_image_tipo = $('#id_image_tipo option:selected').val();
        var id_image_arquivo = $(this).attr('data-id');
        var id_pai = $('#id_pai').val();
        //var titulo = $('#titulo-'+id_image_arquivo).val();
        var descricao = $('#descricao-'+id_image_arquivo).val();
	var url = URI+"anexos/adicionar_image/";
        
        $.post(url, 
        {
            'id_image_tipo': id_image_tipo,
            'id_image_arquivo': id_image_arquivo,
            'id_pai': id_pai,
            //'titulo': titulo,
            'descricao': descricao,
        },  
        function(data){
            
            if(data)
            {
                console.log(data);
                thumbnail.getThumb(id_image_tipo, id_pai);
                $('.img-temp-'+id_image_arquivo).remove();
                $('#progress').html('');
            }
            else
            {
                alert('Erro ao salvar registro. Tente Novamente.');
            }
        });
});

var thumbnail = {
    
    getThumb: function (id_image_tipo, id_pai) {
        
        $.post(URI+'anexos/get_images_por_tipo/', { id_image_tipo: id_image_tipo, id_pai: id_pai }, function(data){
            
            //console.log(data);
            
            if(data != '')
            {
                var thumb = '';
                var data_hora = new Date();
                
                $(data).each(function(k, v)
                {
                    var caminho = '';
                    var uri = URI;
                    caminho = uri.replace('admin2_0/', '');
                    
                    if(v.arquivo.search('.pdf') !== -1)
                    {
                        caminho += 'images/icone-pdf.png';
                    }
                    else if(v.arquivo.search('.doc') !== -1)
                    {
                         caminho += 'images/icone-doc.png';
                    }
                    else
                    {
                        caminho += (v.pasta.replace('[id]',id_pai)) +v.arquivo;
                    }
                    caminho = ( (caminho.search('[ano]') !== -1) ? caminho.replace('[ano]', data_hora.getFullYear() ) : caminho );
                    caminho = ( (caminho.search('[mes]') !== -1) ? caminho.replace('[mes]', '0'+(data_hora.getMonth() + 1) ) : caminho );
                    
                    /*
                    if(caminho.search('[ano]') !== -1)
                    {
                        caminho = (caminho.replace('[ano]', data_hora.getFullYear() ));
                    }
                    if(caminho.search('[mes]') !== -1)
                    {
                        caminho = (caminho.replace('[mes]', '0'+(data_hora.getMonth() + 1) ));
                    }*/
                    //caminho = caminho.replace('admin2_0/', '');
                    //caminho += data[i].pasta+data[i].arquivo;
                    //caminho = caminho.replace('[id]', id_pai);
                    thumb += '<li class="col-lg-4">';
                    thumb += '<div class="thumbnail">';
                    thumb += '<img src="'+caminho+'" style="width:50px; height:50px;">';
                    thumb += '<h3>'+v.descricao+'</h3>';
                    thumb += '<p>Descrição: '+v.descricao_pai+'</p>';
                    thumb += '<button class="btn btn-alert deleta" id="'+v.id_image+'">Deletar </button>';
                    //thumb += '<button class="btn btn-primary editar-desc" data-item="'+v.id_image+'"> Editar(Protótipo)</button>';
                    thumb += '</div>';
                    thumb += '</li>';
                });

                $('.images').html(thumb);
            }
            else
            {
                $('.images').html('');
            }
            
        }, 'json');
    }
}