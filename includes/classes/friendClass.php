<?php

class FriendHandler {
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function addFriend($userEmail, $user, $friendEmail, $friend) {
        $query = $this->con->prepare("INSERT INTO friends(userEmail, user, followingEmail, following) 
                                    VALUES(:uemail, :un, :femail, :fw)");
        $query->bindParam(":uemail", $userEmail);
        $query->bindParam(":un", $user);
        $query->bindParam(":femail", $friendEmail);
        $query->bindParam(":fw", $friend);

        return $query->execute();
    }

    public function fetchFollowing($userEmail) {
        $query = $this->con->prepare("SELECT following FROM friends WHERE userEmail=:uemail");
        $query->bindParam(":uemail", $userEmail);
        $query->execute();

        $following = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($following, $row['following']);
        }

        return json_encode($following);
    }

    public function fetchFollower($userEmail) {
        $query = $this->con->prepare("SELECT user FROM friends WHERE followingEmail=:uemail");
        $query->bindParam(":uemail", $userEmail);
        $query->execute();

        $follower = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($follower, $row['user']);
        }

        return json_encode($follower);
    }


}
?>