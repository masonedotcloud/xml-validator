<?php

include('funzioni.php');

//messaggi per ajax
$stato = '';
$messaggio = '';

//controllo della sessione e inizializzane della vista errori per il file html
try {
    session_start();
    libxml_use_internal_errors(true);
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

//controllo della presenza dei file nella sessione
if (!(isset($_SESSION['file-xml']) && isset($_SESSION['file-verify']))) {
    $stato = "errore";
    $messaggio = "Errore durante la ricerca dei file";
    $ajax_return = array(
        "stato" => $stato,
        "messaggio" => $messaggio
    );
    echo json_encode($ajax_return);
    exit();
}

//analisi ddel file xml e della verifica a seconda della tipologia di verifica
try {
    //verifica con xsd
    if (pathinfo($_SESSION['file-verify'], PATHINFO_EXTENSION) == 'xsd') {
        //presa del codice xml
        $xml = new DOMDocument();
        $xml->load($_SESSION['file-xml']);
        //verifca effettiva con il file xsd
        if (!$xml->schemaValidate($_SESSION['file-verify'])) {
            $stato = "errore";
            $messaggio = "Errore generato: " . libxml_display_errors();
        } else {
            $stato = "successo";
            $messaggio = "Il file risultato verificato con la validazione xsd";
        }
    }
    //verifica con dtd
    if (pathinfo($_SESSION['file-verify'], PATHINFO_EXTENSION) == 'dtd') {
        //creazione di un codice xml che comprenda la verifica dtd
        $dtd = file_get_contents($_SESSION['file-verify']);
        $total_xml = file_get_contents($_SESSION['file-xml']);
        $raw_xml = new DOMDocument();
        $raw_xml->loadXML($total_xml);
        $clear_xml = $raw_xml->saveXML($raw_xml->documentElement);
        $xml = $dtd . $clear_xml;
    
        //analisi del singolo file con controllo dtd e codice xml
        $dom = new DOMDocument;
        $dom->LoadXML($xml);
        $dom->saveXML();
        if ($dom->validate()) {
            $stato = "successo";
            $messaggio = "Il file risulta verificato con la validazione dtd";
        } else {
            $stato = "errore";
            $messaggio = "Errore generato: " . libxml_display_errors();
        }
    }
} catch (\Throwable $th) {
    //gestione degli errori
    $stato = "errore";
    $messaggio = "Errore durante l'esecuzione della verifica. ->" . $th->getMessage();
}

//ritorno ajax
$ajax_return = array(
    "stato" => $stato,
    "messaggio" => $messaggio
);
echo json_encode($ajax_return);
exit();
