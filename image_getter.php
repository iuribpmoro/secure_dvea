<?php

if(isset($_GET["filename"])) {

    $filename = $_GET["filename"];
    if(!preg_match("/^[a-zA-Z0-9_.]+$/", $filename)) {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid file name " . $filename;
        exit();
    }

    $base = "/var/www/html/dvea/images/";
    $file = $base . $filename;
    $canonicalized_path = realpath($file);
    
    if(strpos($canonicalized_path, $base) === 0) {
        $extension = pathinfo($canonicalized_path, PATHINFO_EXTENSION);
        switch($extension){
            case "jpg":
                header("Content-Type: image/jpg");
                break;
            case "png":
                header("Content-Type: image/png");
                break;
            case "gif":
                header("Content-Type: image/gif");
                break;
            default:
                break;
        }
        readfile($canonicalized_path);
        exit();
    }

    header("HTTP/1.0 404 Not Found");
    echo "File ". $canonicalized_path ." not found.";
    exit();
}

?>
