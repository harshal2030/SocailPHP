<?php

class PostHandler {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function insertPostData($id, $by, $for, $title, $desc, $mediaIncluded) {
        $query = $this->con->prepare("INSERT INTO posttexts(id, postedBy, postedFor, title, description, mediaIncluded) 
                                    VALUES(:id, :by, :for, :title, :desc, :media)");
        $query->bindParam(':id', $id); //uniqid of the post
        $query->bindParam(':by', $by);
        $query->bindParam(':for', $for);
        $query->bindParam(':title', $title);
        $query->bindParam(':desc', $desc);
        $query->bindParam(':media', $mediaIncluded); //checks if media is attached
        
        return $query->execute();
    }

    public function insertPostMedia($id, $by, $for, $mediaPath) {
        $query = $this->con->prepare("INSERT INTO postMedia(id, postedBy, postedFor, mediaPath) 
                                    VALUES(:id, :by, :for, :mediaPath)");
        $query->bindParam(':id', $id);
        $query->bindParam(':by', $by);
        $query->bindParam(':for', $for);
        $query->bindParam(':mediaPath', $mediaPath);

        return $query->execute();
    }

    public function fetchPostData($by) {
        
    }
}

?>