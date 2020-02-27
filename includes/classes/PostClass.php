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
        $query = $this->con->prepare("SELECT * FROM posttexts WHERE postedBy=:by ORDER BY timePosted DESC");
        $query->bindParam(':by', $by);
        $query->execute();

        $dataArray = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            if (strlen($row['mediaPath']) != 0) {
                $row['mediaPath'] = 'http://192.168.43.25/api/'.$row['mediaPath'];
            }

            $row['actionData'] = $this->fetchPostActions($row['id'], $by);

            $temp_array = array("type" => "NORMAL", "item" => $row);
            array_push($dataArray, $temp_array);
        }
        return json_encode($dataArray);
    }

    public function fetchPostDataById($id) {
        $query = $this->con->prepare("SELECT * FROM posttexts WHERE id=:id");
        $query->bindParam(':id', $id);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (strlen($data['mediaPath']) != 0) {
            $data['mediaPath'] = 'http://192.168.43.25/api/'.$data['mediaPath'];
        }
        return json_encode($data);
    }

    public function fetchPostActions($id, $user) {
        $query=$this->con->prepare("SELECT * FROM likes WHERE postId=:id");
        $query->bindParam(':id', $id);
        $query->execute();

        $queryC = $this->con->prepare("SELECT * FROM likes WHERE postId=:id AND LikedBy=:user");
        $queryC->bindParam(':id', $id);
        $queryC->bindParam(':user', $user);
        $queryC->execute();

        $liked = $queryC->rowCount() == 0 ? false : true;

        $query2=$this->con->prepare("SELECT * FROM comments WHERE postId=:id");
        $query2->bindParam(':id', $id);
        $query2->execute();

        $postMap = array("likes" => 0, "comments" => 0, "likedByUser" => $liked);
        $postMap['likes'] = $query->rowCount();
        $postMap['comments'] = $query2->rowCount();

        return $postMap;
    }

    public function addLike($id, $postedBy, $user) {
        $query = $this->con->prepare("INSERT INTO likes(postId, postedBy, LikedBy) VALUES(:pid, :pb, :lb)");
        $query->bindParam(':pid', $id);
        $query->bindParam(':pb', $postedBy);
        $query->bindParam(':lb', $user);

        $query->execute();
    }

    public function removeLike($id, $postedBy, $user) {
        $query = $this->con->prepare("DELETE FROM likes WHERE postId=:id AND postedBy=:pby AND LikedBy=:lb");
        $query->bindParam(':id', $id);
        $query->bindParam(':pby', $postedBy);
        $query->bindParam(':lb', $user);

        $query->execute();
    }
}

?>