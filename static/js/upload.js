$(document).ready(function () {
    $(document).on('change', '#file-xml', function () {
        var preferenze = document.getElementById('file-xml').files[0];
        var nome_file = preferenze.name;
        var estenzione_file = nome_file.split('.').pop().toLowerCase();
        if (jQuery.inArray(estenzione_file, ['xml']) == -1) {
            alert("Estensione non valida");
            exit();
        }
        var form_data = new FormData();
        form_data.append("file", preferenze);
        form_data.append("tipo", "xml");
        $.ajax({
            url: 'static/php/upload.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                console.log(data);
                response = JSON.parse(data);
                if (response.stato == 'errore') {
                    console.log(data.messaggio);
                } else if (response.stato == 'successo') {
                    $('#codice-xml').html(response.codice);
                }
            }
        });
    });

    $(document).on('change', '#file-verify', function () {
        var preferenze = document.getElementById('file-verify').files[0];
        var nome_file = preferenze.name;
        var estenzione_file = nome_file.split('.').pop().toLowerCase();

        if (!jQuery.inArray(estenzione_file, ['xsd', 'dtd']) == -1) {
            alert("Estensione non valida");
            exit();
        }


        var form_data = new FormData();
        form_data.append("file", preferenze);
        form_data.append("tipo", "verify");
        $.ajax({
            url: 'static/php/upload.php',
            method: 'POST',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
            },
            success: function (data) {
                console.log(data);
                response = JSON.parse(data);
                if (response.stato == 'errore') {
                    console.log(data.messaggio);
                } else if (response.stato == 'successo') {
                    $('#codice-verify').html(response.codice);
                }
            }
        });



    });


});