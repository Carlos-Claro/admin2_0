$(function(){
    
    var btnUpload = $('#upload');
    var status = $('.status');
    
    new AjaxUpload(btnUpload, {
            // Arquivo que fará o upload
            action: URI+'facebooks/upload_facebook_image',

            //Nome da caixa de entrada do arquivo 
            name: 'file',
            onSubmit: function(file, ext){
                     // verificar a extensão de arquivo válido
                     if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                            status.text('Somente JPG, PNG ou GIF são permitidas');
                            return false;
                    }
                    status.html('<img src="'+URI+'images/carregando.gif">');
            },
            onComplete: function(file, response){
                    //Limpamos o status
                    status.html('');
                    //Adicionar arquivo carregado na lista
                    var r = response.split("-");
                    console.log(response);
                    if(r[0]==="success")    
                    {
                        var url = 'http://www.rededeportais.com.br/admin';
                        $('#files').html('<input type="hidden" name="id_image_arquivo" class="id_image_arquivo" value="' + r[1] + '" /><img src="'+url+r[2]+r[3]+'"><br><button class="btn deleta_image" type="button" >Deletar Imagem</button>').addClass('success');
                    }
                    else
                    {
                        $('#files').html('Erro ao Inserir o arquivo tente novamente').addClass('error');
                    }
            }
    });
    
});
