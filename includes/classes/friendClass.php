<?php

class FriendHandler {
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function addFriend($username, $name, $folowingUserName, $following) {
        $query = $this->con->prepare("INSERT INTO friends(username, user, followingUserName, following)
                                    VALUES(:un, :unm, :FUN, :fw)");
        
        $query->bindParam(':un', $username);
        $query->bindParam(':unm', $name);
        $query->bindParam(':FUN', $folowingUserName);
        $query->bindParam(':fw', $following);

        return $query->execute();
    }


}
?>