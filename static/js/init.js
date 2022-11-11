$.ajax({
    url: 'static/php/inizializza.php',
    method: 'GET',
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
        console.log(data);
        response = JSON.parse(data);
        if (response.stato == 'errore') {
            alert(response.messaggio);
        } else if (response.stato == 'successo') {
            $('#codice-xml').html(response.codice_xml);
            $('#codice-verify').html(response.codice_verify);
        }
    }
});