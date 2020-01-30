<?php
class FormSanitizer {
    public static function sanitizeString($var) {
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);
        return $var;
    }
    public static function sanitizeTitle($var) {
        $var = htmlentities($var);
        return $var;
    }
    public static function sanitizeDesc($desc) {
        $desc = htmlentities($desc);
        return $desc;
    }
    public static function sanitizePassword($var) {
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);
        return $var;
    }
    public static function sanitizeUsername($var) {
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);
        $var = str_replace(" ", "", $var);
        return $var;
    }
    public static function sanitizeEmail($var) {
        $var = strip_tags($var);
        $var = htmlentities($var);
        $var = stripslashes($var);
        $var = str_replace(" ", "", $var);
        return $var;
    }
}
?>