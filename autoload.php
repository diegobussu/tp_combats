<?php

function chargerClasse( $classe )
{

    // Check if local or distant host, and set separator caracter
    $carSep = strpos( __DIR__, '/' ) !== false ? '/' : '\\';

    // Retrieve the real app folder name if it is different from that of the namespaces
    $appFolderName = strrchr( __DIR__, $carSep );

    // Change te rootPath with the real folder name
    $rootPath =  str_replace( $appFolderName, '', __DIR__ );

    // Remove app name from class namespace
    $classe = strstr( $classe, '\\' );

    // Change separator caracter
    $classe = str_replace('\\', DIRECTORY_SEPARATOR, $classe);
    $fullPathClasseFile = $rootPath . $appFolderName . $classe . '.php';

    if( file_exists( $fullPathClasseFile ) ) {
        require_once $fullPathClasseFile;
    } else {
        die( "Error 404 : File not found!" );
    }

}

spl_autoload_register( 'chargerClasse' );

