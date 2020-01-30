<?php
require_once('includes/paths.php');

function create_dir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
        echo "successfully created target dir";
    } else echo "unable to create target dir";
}

create_dir(LocalPath::$postImagePath);
create_dir(LocalPath::$postVideoPath);
create_dir(LocalPath::$profilepicPath);
?>