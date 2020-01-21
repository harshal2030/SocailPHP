<?php

class PostHandler {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function insertPostData($by, $for, $title, $desc) {
        $query = $this->con->prepare("INSERT INTO posttexts(postedBy, postedFor, title, description) VALUES(:by, :for, :title, :desc)");
        $query->bindParam(':by', $by);
        $query->bindParam(':for', $for);
        $query->bindParam(':title', $title);
        $query->bindParam(':desc', $desc);
        
        return $query->execute();
    }

    public function insertPostMedia($by, $for, $mediaPath) {
        $query = $this->con->prepare("INSERT INTO postMedia(postedBy, postedFor, mediaPath) VALUES(:by, :for, :mediaPath)");
        $query->bindParam(':by', $by);
        $query->bindParam(':for', $for);
        $query->bindParam(':mediaPath', $mediaPath);

        return $query->execute();
    }
}

?>