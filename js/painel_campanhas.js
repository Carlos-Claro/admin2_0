$(function(){
    
    $('.campanhas').hide();
    
    $('.campanha-plus').on('click', function(){
       
        var id  =  $(this).attr('data-item');
        
        if($(this).hasClass('glyphicon-plus'))
        {
            $(this).removeClass('glyphicon-plus');
            $(this).addClass('glyphicon-minus');
            $('#campanhas-painel-'+id).show();
        }
        else
        {
            $(this).removeClass('glyphicon-minus');
            $(this).addClass('glyphicon-plus');
            $('#campanhas-painel-'+id).hide();
        }
        
    });
    
});


