$(document).ready(function(){
    // Turn on/off shaker
    var shaker = $('[data-shaker]').attr('data-shaker');
    if( shaker == 'false' ){
        $('.blog-post').removeClass('shake-freeze');
    }else{
        $('.blog-post').addClass('shake-freeze');
    }

    $('[data-shaker]').click(function(){
        let shaker = $(this).attr('data-shaker');
        if( shaker == 'true' ){
            $('.blog-post').removeClass('shake-freeze');
            $(this).removeClass('hollow');
            $(this).attr('data-shaker','false');
        }else{
            $('.blog-post').addClass('shake-freeze');
            $(this).attr('data-shaker','true');
            $(this).addClass('hollow');
        }
    });
});