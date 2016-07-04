/*
 * Classe destinada a gerenciar as tarefas, inserir, deletar, editar.
 * Tarefas é destinada a uso interno e gerenciamento de tempo 
 */

$(function(){
    
    $('.ver_todos').on({
        click : function(){
            var url = URI+"tarefas_projetos/listar/"+$(this).attr('data-item');
            window.location.href = url;
        }
    });
    $('.ver_aprovados').on({
        click : function(){
            var url = URI+"tarefas_projetos/listar/"+$(this).attr('data-item') + "?&b[status]=2";
            window.location.href = url;
        }
    });
    $('.ver_andamento').on({
        click : function(){
            var url = URI+"tarefas_projetos/listar/"+$(this).attr('data-item') + "?&b[status]=4";
            window.location.href = url;
        }
    });
    
    $('.editar').on({
        click : function(){
            var url = URI+"tarefas_portfolio/editar/" + $(this).attr('data-item');
            window.location.href = url;
        }
    });
    
    $('#tem_data').on('click',function(){
        console.log($('#tem_data').is( ":checked" ));
        if ( $('#tem_data').is( ":checked" ) )
        {
            $('.caixa_data_inicio').removeClass('hide').addClass('show');
            $('.caixa_data_fim').removeClass('hide').addClass('show');
            $('.caixa_tem_data').removeClass('col-lg-12 col-md-12 col-sm-12 col-xs-12').addClass('col-lg-2 col-md-2 col-sm-2 col-xs-2');
            $('#data_inicio').removeAttr('disabled');
            $('#data_fim').removeAttr('disabled');
        }
        else
        {
            $('.caixa_data_inicio').removeClass('show').addClass('hide');
            $('.caixa_data_fim').removeClass('show').addClass('hide');
            $('.caixa_tem_data').removeClass('col-lg-2 col-md-2 col-sm-2 col-xs-2').addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12');
            $('#data_inicio').attr('disabled','disabled');
            $('#data_fim').attr('disabled','disabled');
            
        }
    });
    
});