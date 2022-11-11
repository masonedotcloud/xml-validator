<?php

include('funzioni.php');

//variabili di base per il ritorno su ajax
$codice = '';
$stato = '';
$messaggio = '';

//blocco di instaurazione della sessione
try {
    session_start();
} catch (\Throwable $th) {
    $stato = "errore";
    $messaggio = "Errore durante la generazione della sessione. ->" . $th->getMessage();
    $ajax_return = array(
        "stato" => $stato,
        "messaggio" => $messaggio
    );
    echo json_encode($ajax_return);
    exit();
}


//blocco di upload del file dalla zona temporanea alla zona privata dell utente
try {
    //controllo se la cartella dell'utente esiste oppure va creata
    $cartella = "../../uploads/" . md5(strval(get_client_ip())) . "/";
    if (!folder_exist($cartella)) {
        mkdir($cartella, 0777, true);
    }
    //dati del file caricato
    $nome_completo = explode('.', $_FILES['file']['name']);
    $estensione = end($nome_completo);

    //controllo della tipologia di upload
    if ($_POST['tipo'] == 'xml') {
        //controllo estensione sia conforme alla tiopologia
        if ($estensione != 'xml') {
            $stato = "errore";
            $messaggio = "Estensione non supportata";
            $ajax_return = array(
                "stato" => $stato,
                "messaggio" => $messaggio
            );
            echo json_encode($ajax_return);
            exit();
        }
    } else if ($_POST['tipo'] == 'verify') {
        //se viene caricato un file xsd si deve procedere con il rimuovere quello precedente, soltanto nel caso sia dtd perchè per l'xsd si effettua una sovrescrittura del file stesso.
        if ($estensione == 'xsd') {
            if (file_exists($cartella . 'file.dtd')) {
                unlink($cartella . 'file.dtd');
                unset($_SESSION['file-verify']);
            }
        }
        //stesso ragionamento del file xsd cambiando le parti
        if ($estensione == 'dtd') {
            if (file_exists($cartella . 'file.xsd')) {
                unlink($cartella . 'file.xsd');
                unset($_SESSION['file-verify']);
            }
        }
        //se l'estensione non è valida
        if ($estensione != 'xsd' && $estensione != 'dtd') {
            $stato = "errore";
            $messaggio = "Estensione non supportata";
            $ajax_return = array(
                "stato" => $stato,
                "messaggio" => $messaggio
            );
            echo json_encode($ajax_return);
            exit();
        }
    } else { //tipoloia del file non identificata perch+ js non ha mandato il dato
        $stato = "errore";
        $messaggio = "Tipologia file non identificata";
        $ajax_return = array(
            "stato" => $stato,
            "messaggio" => $messaggio
        );
        echo json_encode($ajax_return);
        exit();
    }

    //salvataggio del nome del file nella sessione dell'utente e upload nella sua posizione
    $nome = 'file.' . $estensione;
    $posizione = $cartella . $nome;
    if ($_POST['tipo'] == 'xml') {
        $_SESSION['file-xml'] = $posizione;
    }
    if ($_POST['tipo'] == 'verify') {
        $_SESSION['file-verify'] = $posizione;
    }
    move_uploaded_file($_FILES['file']['tmp_name'], $posizione);

    //messaggi per ajax
    $stato = "successo";
    $messaggio = "Caricamente effettuato con successo";
    $codice_raw = file_get_contents($posizione);
    $codice = "<xmp>" . $codice_raw . "</xmp>";
} catch (\Throwable $th) {
    //gestione dell'errore
    $stato = "errore";
    $messaggio = "Errore durante il caricamento dei file. ->" . $th->getMessage();
    $ajax_return = array(
        "stato" => $stato,
        "messaggio" => $messaggio
    );
    echo json_encode($ajax_return);
    exit();
}

$ajax_return = array(
    "stato" => $stato,
    "messaggio" => $messaggio,
    "codice" => $codice
);
echo json_encode($ajax_return);
exit();
