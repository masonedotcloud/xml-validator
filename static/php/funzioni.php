<?php

//contorllo errori nel file xml
function libxml_display_error($error)
{
    $return = "";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " in " . pathinfo($error->file, PATHINFO_EXTENSION);
    }
    $return .= " on line $error->line";

    return $return;
}

//uscita di tutti gli errori come stringa
function libxml_display_errors()
{
    $errors = libxml_get_errors();
    $return = "";
    foreach ($errors as $error) {
        $return .= libxml_display_error($error);
    }
    libxml_clear_errors();
    return $return;
}

//restituisce l'indirizzo ip del cliente che usufruisce dell'applicativo
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

//controlla se una cartella essite
function folder_exist($folder)
{
    $path = realpath($folder);
    return ($path !== false and is_dir($path)) ? $path : false;
}