/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){
    $(document).on('click','.ver',function(){
        var item = $(this).attr('data-item');
        var item_sem_barra = item.split('/').join('-');
        if ( item != undefined )
        {
            $('.elemento-' + item_sem_barra + ' .espaco-proximo').html('<center><img src="http://www.guiasjp.com/admin2_0/images/loader_azul.gif"></center>');
            
            var url = URL_HTTP + 'estatisticas/listar_dia/' + item;
            $.get(url, function(data){
                $('.elemento-' + item_sem_barra + ' .espaco-proximo').html(data);
            });
            $(this).html('Fechar').removeClass('ver').addClass('fechar');
        }
        
    });
    $(document).on('click','.fechar',function(){
        var item = $(this).attr('data-item');
        var item_sem_barra = item.split('/').join('-');
        $('.elemento-' + item_sem_barra + ' .espaco-proximo').html('');
        $(this).html('Ver').removeClass('fechar').addClass('ver');
    });
    $(document).on('click','.expandir',function(){
        var item = $(this).attr('data-item');
        $('.elemento-' + item_sem_barra + ' .espaco-proximo').html('<center><img src="http://www.guiasjp.com/admin2_0/images/loader_azul.gif"></center>');
        var item_sem_barra = item.split('/').join('-');
        if ( item != undefined )
        {
            var url = URL_HTTP + 'estatisticas/listar_dia_por_locais/' + item;
            $.get(url, function(data){
                $('.elemento-' + item_sem_barra + ' .espaco-proximo').html(data);
            });
            $(this).html('Fechar').removeClass('expandir').addClass('fechar-expandir');
            
        }
        
    });
    $(document).on('click','.fechar-expandir',function(){
        var item = $(this).attr('data-item');
        var item_sem_barra = item.split('/').join('-');
        $('.elemento-' + item_sem_barra + ' .espaco-proximo').html('');
        $(this).html('Expandir').removeClass('fechar-expandir').addClass('expandir');
    });
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
});

var plot = {
    set : function(elemento){
        var d1 = [];
        for (var i = 0; i < 14; i += 0.5) {
                d1.push([i, Math.sin(i)]);
        }

        var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];

        // A null signifies separate line segments

        var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];

        $.plot(".espaco-plot", [ d1, d2, d3 ]);
    },
};