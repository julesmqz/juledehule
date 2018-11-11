// Secuencial
$(document).ready(function(){
    $(".instagram-pic").each(function(){
        // Set same height as width in an effort to make this pic square
        $(this).css('height',Math.round($(this).width()));
    });
});