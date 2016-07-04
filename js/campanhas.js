$(function(){
    
    //$('#data_inicio').mask('99/99/9999 99:99:99');
    //$('#data_fim').mask('99/99/9999 99:99:99');
    
    $(document).on('keypress', function(e){
       
        if(e.which  == 13)
        {
            e.preventDefault();
        }
    });
    
    $('#data_inicio').setMask('99/99/9999');
    $('#data_fim').setMask('99/99/9999');
    
    gets.categorias();
    gets.estados();
    gets.status();
    
    $('.editar').on({
        click : function(){
            
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var url = "";
                    for ($i=0; $i < $selecionados.length; $i++)
                    {
                            url = URI+"campanhas/editar/"+$selecionados[$i];
                            window.open(url);
                    }
            }
            else
            {
                    alert('nenhum item selecionado');
            }
        }
    });
    
    $(document).on('change', '#categorias', function (){
       
        var id  = $(this).val();
        
        if(id !== '')
        {
            gets.subcategorias(id);
        }
        
    });
    
    $(document).on('change', '#estados', function (){
       
        var id  = $(this).val();
        
        if(id !== '')
        {
            gets.cidades(id);
        }
        
    });
    
    $(document).on('change', '#cidades', function (){
       
        var id  = $(this).val();
        
        if(id !== '')
        {
            gets.bairros(id);
        }
        
    });
    
    $('#pesquisar-empresas').on({
       
        click : function(){
            
            gets.empresas();
        }
        
    });
    
    $(document).on('click', '.empresas-selecionaveis', function (){
       
        var id  = $(this).attr('data-item');
        var texto  = $(this).find('small').text();
        var tem = $('.empresas_campanha[value="'+id+'"]').html();
        
        if(tem === undefined)
        {
            var html = '';

            html += '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
            html += '<li class="list-group-item selecionadas" data-item="'+id+'">';
            html += '<small>'+texto+'</small><span class="glyphicon glyphicon-remove pull-right remover-selecionados"></span>';
            html += '</li>';
            html += '</div>';

            $('#empresas-selecionadas #lista-empresas-selecionadas').append(html);
            $('#inputs-empresas-selecionadas').append('<input class="empresas_campanha" type="hidden" value="'+id+'" name="empresas_campanha[]">');
            $('.empresas-selecionaveis[data-item="'+id+'"]').hide();
        }
        else
        {
            $('.empresas-selecionaveis[data-item="'+id+'"]').show();
        }
        
    });
    
    $(document).on('click', '#sel_todos',function(){
        
        $('.empresas-selecionaveis').each(function(){
            
            var id = $(this).attr('data-item');
            var texto = $(this).find('small').text();
            var tem = $('.empresas_campanha[value="'+id+'"]').html();
            
            if(tem === undefined)
            {
                var html = '';

                html += '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                html += '<li class="list-group-item selecionadas" data-item="'+id+'">';
                html += '<small>'+texto+'</small><span class="glyphicon glyphicon-remove pull-right remover-selecionados"></span>';
                html += '</li>';
                html += '</div>';

                $('#empresas-selecionadas #lista-empresas-selecionadas').append(html);
                $('#inputs-empresas-selecionadas').append('<input class="empresas_campanha" type="hidden" value="'+id+'" name="empresas_campanha[]">');
                $('.empresas-selecionaveis[data-item="'+id+'"]').hide();
                //$('.empresas-selecionaveis[data-item="'+id+'"]').hide();
                //$('#empresas-selecionadas').parent().append('<input class="empresas_campanha" type="hidden" value="'+id+'" name="empresas_campanha[]">');
            }
            else
            {
                $('.empresas-selecionaveis[data-item="'+id+'"]').show();
            }
            
        });
        
    });
    
    $(document).on('click', '.remover-selecionados', function(){
       
        var id = $(this).parent().attr('data-item');
        $('#empresas-selecionadas .selecionadas[ data-item="'+id+'"] ').parent().remove();
        $('#resultado-empresas .empresas-selecionaveis[ data-item="'+id+'"] ').parent().show();
        
        $('.empresas_campanha[value="'+id+'"]').remove();
        $('.empresas-selecionaveis[data-item="'+id+'"]').show();
        
    });
    
    $(document).on('click','#del_todos', function(){
       
        $('#lista-empresas-selecionadas').html('');
        $('#inputs-empresas-selecionadas').html('');
        $('.empresas-selecionaveis').show();
        
    });
    
});

var gets = {
    
    categorias : function(){
        
        $.getJSON(URI+'campanhas/get_categorias', function(data){
            
                if(data)
                {
                    helper.select('Categorias', 'categorias', data);
                    $('.filtro-categoria').html(helper.montado);
                }
            
        });
    },
    
    subcategorias : function(id){
        
        $.getJSON(URI+'campanhas/get_subcategorias/'+id, function(data){
            
                if(data)
                {
                    helper.select('Subcategorias', 'subcategorias', data);
                    $('.filtro-subcategoria').html(helper.montado);
                }
            
        });
    },
    
    estados : function() {
        
        $.getJSON(URI+'campanhas/get_estados', function(data){
            
                if(data)
                {
                    helper.select('Estados', 'estados', data);
                    $('.filtro-estado').html(helper.montado);
                }
            
        });
    },
    
    cidades : function (id) {
        
        $.getJSON(URI+'campanhas/get_cidades/'+id, function(data){
            
                if(data)
                {
                    helper.select('Cidades', 'cidades',data);
                    $('.filtro-cidade').html(helper.montado);
                }
            
        });
    },
    
    bairros : function(id){
        
        $.getJSON(URI+'campanhas/get_bairros/'+id, function(data){
            
                if(data)
                {
                    helper.select('Bairros', 'bairros', data);
                    $('.filtro-bairro').html(helper.montado);
                }
            
        });
    },
    
    status : function(){
        
        $.getJSON(URI+'campanhas/get_status/', function(data){
            
                if(data)
                {
                    helper.select('Status', 'status', data);
                    $('.filtro-status').html(helper.montado);
                }
            
        });
    },
    
    empresas : function(){
        
        var categoria = $('#categorias option:selected').val();
        var subcategoria = $('#subcategorias option:selected').val();
        var estado = $('#estados option:selected').val();
        var cidade = $('#cidades option:selected').val();
        var bairro = $('#bairros option:selected').val();
        var status = $('#status option:selected').val();
        var nome_empresa = $('#nome_empresa').val();
        var logradouro = $('#logradouro').val();
        
        $.post(URI+'campanhas/get_empresas/', {
            
            itens : [
                {campo :'categorias.id', tipo : 'int', valor : categoria},
                {campo :'subcategorias.id', tipo : 'int', valor : subcategoria},
                {campo :'cidades.uf', tipo : 'text', valor : estado},
                {campo :'logradouros.id_cidade', tipo : 'int', valor : cidade},
                {campo :'logradouros.bairro', tipo : 'text', valor : bairro},
                {campo :'logradouros.logradouro', tipo : 'text', valor : logradouro},
                {campo :'status_atualizada.id', tipo : 'int', valor : status},
                {campo :'empresas.empresa_nome_fantasia', tipo : 'text', valor : nome_empresa},
                ],
            
        },function(data){
                
                console.log(data);
                var html = '';
                html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                html += '<h4>Empresas Disponiveis</h4>';
                html += '<div class="row">';
                html += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                html += '<div class="form-group">';
                html += '<button name="sel_todos" id="sel_todos" type="button" class="btn btn-info">Selecionar Todas</button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '<ul class="list-group">';
                $.each(data.itens, function(k, v){
                    html += '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                    html += '<li class="list-group-item empresas-selecionaveis" data-item="'+v.id_empresa+'">';
                    html += '<small>'+v.empresa.toUpperCase()+'</small>';
                    html += '</li>';
                    html += '</div>';
                    
                });
                html += '</ul> ';
                $('#resultado-empresas').html(html);
                
        }, 'json');
    }
    
}