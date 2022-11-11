function InputReset(div) {
    $('#file-' + div).val('');
    $('#codice-' + div).html('');
    $.ajax({
        url: 'static/php/inizializza.php?' + div,
        method: 'GET',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            console.log(data);
            response = JSON.parse(data);
            if (response.stato == 'errore') {
                console.log(response.messaggio);
            }
        }
    });
}