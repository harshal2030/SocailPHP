<?php

class PostHandler {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function insertPostData($id, $by, $name, $for, $title, $desc, $mediaIncluded, $mediaPath) {
        $query = $this->con->prepare("INSERT INTO posttexts(id, postedBy, name, postedFor, title, description, mediaIncluded, mediaPath) 
                                    VALUES(:id, :by, :name,:for, :title, :desc, :media, :mPath)");
        $query->bindParam(':id', $id); //uniqid of the post
        $query->bindParam(':name', $name);
        $query->bindParam(':by', $by);
        $query->bindParam(':for', $for);
        $query->bindParam(':title', $title);
        $query->bindParam(':desc', $desc);
        $query->bindParam(':media', $mediaIncluded); //checks if media is attached
        $query->bindParam(':mPath', $mediaPath);
        
        return $query->execute();
    }

    public function fetchPostData($by) {
        $query = $this->con->prepare("SELECT * FROM posttexts WHERE postedBy=:by");
        $query->bindParam(':by', $by);
        $query->execute();

        $dataArray = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            if (strlen($row['mediaPath']) != 0) {
                $row['mediaPath'] = 'http://192.168.43.25/api/'.$row['mediaPath'];
            }
            array_push($dataArray, $row);
        }
        return json_encode($dataArray);
    }
}

?>