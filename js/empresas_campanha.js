$(function(){
    
    var $selecionados;
    var id_campanha = $('#modal-ca').attr('data-item');
    
    $(document).on('click', 'a.item-pesquisa', function (){
       
        var id = $(this).attr('data-item');
        var texto = $(this).find('p').text();
        if($('.escolhidos ul li').length > 0)
        {
            var tem = $('.escolhidos ul .li-escolhido[data-item="' + id + '"]').html();
            if(tem === undefined)
            {
                $('.escolhidos ul').append('<li class="li-escolhido" data-item="'+id+'"><a href="#" class="btn btn-primary" ><span class="text-pesquisa">'+texto+'</span> <span class="glyphicon glyphicon-remove excluir-filtro"></span></a></li>');
            }
        }
        else
        {
            $('.escolhidos ul').append('<li class="li-escolhido" data-item="'+id+'"><a href="#" class="btn btn-primary" ><span class="text-pesquisa">'+texto+'</span> <span class="glyphicon glyphicon-remove excluir-filtro"></span></a></li>');
        }
    });
    
    $(document).on('click','.excluir-filtro', function(){
       
        var id = $(this).parent().parent().attr('data-item');
        $('.escolhidos ul .li-escolhido[data-item="' + id + '"]').remove();
        
    });
    
    $(document).on('click', '#pesquisar-modal', function(){
        
        var filtro = '';
        var contagem = 0;
        
        if($(this).hasClass('pesquisar-categorias'))
        {
            $('.escolhidos ul .li-escolhido').each(function(){

                    if(contagem == 0)
                    {
                        filtro += 'categoria[]='+$(this).attr('data-item');
                    }
                    else
                    {
                        filtro += '&categoria[]='+$(this).attr('data-item');
                    }
                    contagem++;
            });
            gets.get_subcategoria(filtro);
        }
        else
        {
            $('.escolhidos ul .li-escolhido').each(function(){

                    if(contagem == 0)
                    {
                        filtro += 'b[subcategorias][]='+$(this).attr('data-item');
                    }
                    else
                    {
                        filtro += '&b[subcategorias][]='+$(this).attr('data-item');
                    }
                    contagem++;
            });
            gets.filtrar(id_campanha, filtro);
        }
       
    });
    
    $('#modal-categorias').on('hidden.bs.modal', function () {
        
        gets.get_categoria();
        $('.li-escolhido').remove();
    });
    
    $('.add-campanha').on('click', function(){
       
        $selecionados = get_selecionados();
        gets.get_setor('ca');
        
        if($selecionados.length > 0)
        {
            $('#qtde-empresas').html('<h3>Quantidade de empresas selecionadas : '+$selecionados.length+'</h3>')
        }
        else
        {
            $('#qtde-empresas').html('<h3>Nenhuma empresa selecionada.</h3>')
        }
        
        $('#modal-ca').modal('show');
        
    });
    
    $(document).on('change', '#setores-ca', function(){
       
        var setor = $(this).val();
        
        if(setor != '')
        {
            gets.get_usario(setor, 'ca', true);
        }
        
    });
    
    $('#save-empresas-campanha').on('click', function(){
        
        var texto = $('#texto-campanha').attr('data-item');
        var usuarios = []; 
        
        $('#usuario-retorno-ca :selected').each(function(k, v){ 
          
            if( ($(v).val() !== '') && ($(v).val() !== undefined) )
            {
                usuarios[k] = $(v).val(); 
            }
        
        });
        
        var erro = '';
        if($selecionados > 0)
        {
            $('.error-ca').html('');
            $.post(URI+'empresas/add_empresas_campanha/',{

                empresas : $selecionados, 
                usuarios: usuarios, 
                campanha: id_campanha,
                texto : texto 
            }, 

            function (data){

                console.log(data);
                if(data)
                {
                    alert('Inclusão realizada com sucesso.');
                    location.reload(true); 
                }

            });
        }
        else
        {
            erro += '<h4 class="alert alert-danger">Não há empresas selecionadas. </h4>';
        }
        
        $('.error-ca').html(erro);
    });
    
    $('#modal-ca').on('hidden.bs.modal', function () {
        
        $('.error-ca').html('');
        
    });
    
});

var gets = {
    
    get_categoria : function(){
        
        $.get(URI+'empresas/get_categorias', function(data){

            if(data)
            {
                //console.log(data);
                $('#modal-categorias .itens .list-group').remove();
                $('#modal-categorias .itens').html(data);
            }
        });
        
    },
    
    get_subcategoria : function(categoria){
        
      
        $.getJSON(URI+'empresas/get_subcategorias?'+categoria, function(data){
           
            if(data)
            {
                $('.li-escolhido').remove();
                $('#pesquisar-modal').removeClass('pesquisar-categorias');
                $('#pesquisar-modal').addClass('pesquisar-subcategorias');
                
                var html = '';
                $.each(data, function(k, v){
                
                    html += '<a class="list-group-item subcategorias item-pesquisa col-lg-4 col-sm-6 col-md-6 col-xs-12 " data-item="'+v.id+'" tabindex="1">';
                    html += '<p class="list-group-item-text">'+v.descricao+'</p>';
                });
                $('.list-group').html(html);
            }
        });
    },
    
    get_setor : function (sufix){
        
        $.get(URI+'empresas/get_setor/'+sufix, function (data){
            
            $('.setor-'+sufix).empty().append(data);
            
        });
    },
    
    get_usario: function(setor, sufix, multi){
        
        $.get(URI+'empresas/get_usuario/'+setor+'/'+sufix+'/'+multi, function (data){
            
            if(data)
            {
                $('.usuario-setor-'+sufix).empty().append(data);
            }
            else
            {
                $('.usuario-setor-'+sufix).empty();
            }
            
        });
    },
    
    filtrar : function(campanha, filtro){
        
        window.location.href = URI+'empresas/listar_empresas_campanha/'+campanha+'?'+filtro;
        
    }
}