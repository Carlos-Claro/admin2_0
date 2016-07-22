/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


console.log(document.baseURI);

$(function(){
    var url_pagina = document.baseURI;
    var atividade = url_pagina.split('#');
    if ( atividade[1] != undefined )
    {
        $('.elemento-' + atividade[1]).removeClass('alert-info').addClass('alert-success');
    }
    //console.log(atividade[1]);
});