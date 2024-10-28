<?php
function getRealIP()
{
    if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
        return $_SERVER["HTTP_X_FORWARDED"];
    } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
        return $_SERVER["HTTP_FORWARDED"];
    } else {
        return $_SERVER["REMOTE_ADDR"];
    }
}

//echo getRealIP();
function getUserIpAddress() {

    foreach ( [ 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ] as $key ) {

        // Comprobamos si existe la clave solicitada en el array de la variable $_SERVER 
        if ( array_key_exists( $key, $_SERVER ) ) {

            // Eliminamos los espacios blancos del inicio y final para cada clave que existe en la variable $_SERVER 
            foreach ( array_map( 'trim', explode( ',', $_SERVER[ $key ] ) ) as $ip ) {

                // Filtramos* la variable y retorna el primero que pase el filtro
                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                    return $ip;
                }
            }
        }
    }

    return '?'; // Retornamos '?' si no hay ninguna IP o no pase el filtro
}
//echo getUserIpAddress();

$ip_address = file_get_contents('http://checkip.amazonaws.com/');
//echo $ip_address;
$ip = $_SERVER['REMOTE_ADDR'];
echo $ip;
?>
