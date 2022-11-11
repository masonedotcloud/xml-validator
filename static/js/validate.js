function ValidateXML() {
    $.ajax({
        url: 'static/php/controllo.php',
        method: 'GET',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            console.log(data);
            response = JSON.parse(data);
            alert(response.messaggio);
        }
    });
}