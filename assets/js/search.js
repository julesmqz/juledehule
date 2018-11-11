function sendToSearch(text){
    window.location.href = "/busqueda/texto/" + encodeURI(text.toLowerCase().split(' ').join('-'));
}

$(document).ready(function(){
    $('[data-search-btn]').click(function(){
        var txt = $('[data-search-text]').val();
        sendToSearch(txt);
    });

    $('[data-search-text]').keypress(function(e){

        var code = e.which; // recommended to use e.which, it's normalized across browsers
        if(code==13){
            var txt = $('[data-search-text]').val();
            sendToSearch(txt);
        }

        
    });
});