<?php

include('funzioni.php');

//messaggi di default per ajax
$stato = '';
$messaggio = '';
$codice_xml = '';
$codice_verify = '';

//controllo se bisogna eliminare il file xml presente attualmente
if (isset($_GET['xml'])) {
    try {
        //ricerca ed eliminazione se presente
        session_start();
        $cartella = "../../uploads/" . md5(strval(get_client_ip())) . "/";
        if (folder_exist($cartella)) {
            if (file_exists($cartella . 'file.xml')) {
                unlink($cartella . 'file.xml');
            }
      
        }
        unset($_SESSION['file-xml']);
        $stato = "successo";
    } catch (\Throwable $th) {
        $stato = "errore";
        $messaggio = "Errore nella cancellazione del file. ->" . $th->getMessage();
    }
    
    //ritorno ajax
    $ajax_return = array(
        "stato" => $stato,
        "messaggio" => $messaggio
    );
    echo json_encode($ajax_return);
    exit();
}

//controllo se bisogna eliminare il file di verifica presente attualmente
if (isset($_GET['verify'])) {
    try {
        //ricerca ed eliminazione se presente
        session_start();
        $cartella = "../../uploads/" . md5(strval(get_client_ip())) . "/";
        if (folder_exist($cartella)) {
            if (file_exists($cartella . 'file.xsd')) {
                unlink($cartella . 'file.xsd');
            }
            if (file_exists($cartella . 'file.dtd')) {
                unlink($cartella . 'file.dtd');
            }
        }
        unset($_SESSION['file-verify']);
        $stato = "successo";
    } catch (\Throwable $th) {
        $stato = "errore";
        $messaggio = "Errore nella cancellazione del file. ->" . $th->getMessage();
    }

    //ritorno ajax
    $ajax_return = array(
        "stato" => $stato,
        "messaggio" => $messaggio
    );
    echo json_encode($ajax_return);
    exit();
}

try {
    //inizializzazione dei file se presenti nella cartella univoca del client
    session_start();
    unset($_SESSION['file-xml']);
    unset($_SESSION['file-verify']);

    //cartella univoca utente
    $cartella = "../../uploads/" . md5(strval(get_client_ip())) . "/";

    //controllo esistenza e presa dei file all'interno
    if (folder_exist($cartella)) {
        if (file_exists($cartella . 'file.xml')) {
            $_SESSION['file-xml'] = $cartella . 'file.xml';
        }
        if (file_exists($cartella . 'file.xsd')) {
            $_SESSION['file-verify'] = $cartella . 'file.xsd';
        }
        if (file_exists($cartella . 'file.dtd')) {
            $_SESSION['file-verify'] = $cartella . 'file.dtd';
        }
    }
    $stato = "successo";

    //se sono presenti dei file visualizza il codice al client
    if (isset($_SESSION['file-xml'])) {
        $codice_xml = "<xmp>" . file_get_contents($_SESSION['file-xml']) . "</xmp>";
    }
    if (isset($_SESSION['file-verify'])) {
        $codice_verify = "<xmp>" . file_get_contents($_SESSION['file-verify']) . "</xmp>";
    }
} catch (\Throwable $th) {
    $stato = "errore";
    $messaggio = "Errore nell'inizializzazione della sessione. ->" . $th->getMessage();
}

//ritorno ajax
$ajax_return = array(
    "stato" => $stato,
    "messaggio" => $messaggio,
    "codice_xml" => $codice_xml,
    "codice_verify" => $codice_verify
);
echo json_encode($ajax_return);
exit();
