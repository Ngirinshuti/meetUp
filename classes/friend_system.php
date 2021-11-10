<?php
class Friends
{
    protected $me;
    protected $db;

    function __construct(PDO $conn, string $me)
    {
        $this->me = $me;
        $this->db = $conn;
    }

    function current_status($user)
    {
        if ($this->request_sent($user)) {
            if ($this->i_sent_request($user)) {
                return "sent";
            } else {
                return "recieved";
            }
        } elseif ($this->is_friends($user)) {
            return "friends";
        } else {
            return false;
        }
    }

    function active_friends():array
    {
        $query = "SELECT `users`.* FROM friends
        JOIN users ON IF(friends.partener = :me, friends.friend, friends.partener) = users.username
        WHERE :me IN (friends.partener, friends.friend) AND `users`.`status` = 'online'
        ORDER BY `users`.`last_seen` DESC";
        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute([":me" => $this->me]);

        return $stmt->fetchAll();
    }

    public function getFriendsSorted(): array
    {
        $query = "SELECT `users`.* FROM friends
        JOIN users ON IF(friends.partener = :me, friends.friend, friends.partener) = users.username
        WHERE :me IN (friends.partener, friends.friend)
        ORDER BY `users`.`status` DESC
        ";

        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute([":me" => $this->me]);

        return $stmt->fetchAll();
    }

    /**
     * Get all active friends count
     *
     * @return integer
     */
    function activeFriendsCount(): int
    {
        $query = "SELECT COUNT(`friends`.partener) as active_count FROM friends 
        JOIN users ON IF(friends.partener = :me, friends.friend, friends.partener) = users.username
        WHERE :me IN (friends.partener, friends.friend)
        AND `users`.status = 'online'
        ";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([":me" => $this->me]);

        return $stmt->fetch()['active_count'];
    }

    function request_sent($user)
    {
        try {
            $sql = "SELECT * FROM friendrequest WHERE (sender = :me AND reciever = :friend) OR (sender = :friend AND reciever = :me)";
            $check = $this->db->prepare($sql);
            $check->bindParam(":me", $this->me);
            $check->bindParam(":friend", $user);
            $check->execute();

            if ($check->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return ["Error" => $e->getMessage()];
        }
    }

    function get_sent_requests(): array
    {
        $sql  = "SELECT users.* FROM friendrequest 
        JOIN users ON friendrequest.reciever = users.username 
        WHERE sender = :me";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":me", $this->me);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function i_sent_request($friend)
    {
        $sql  = "SELECT * FROM friendrequest WHERE sender = :me AND reciever = :friend";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":me", $this->me);
        $stmt->bindParam(":friend", $friend);
        $stmt->execute();
        if ($stmt->rowCount() === 1) {
            return true;
        } else {
            return false;
        }
    }

    function is_friends($friend)
    {
        $sql = "SELECT * FROM friends WHERE (friend = :friend AND partener = :me) OR (partener = :friend AND friend = :me)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":friend", $friend);
        $stmt->bindParam(":me", $this->me);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function send_friend_request($reciever)
    {
        try {
            $sql = "INSERT INTO friendrequest(sender, reciever) VALUES(:sender, :reciever)";
            $send_request = $this->db->prepare($sql);
            $send_request->bindParam(":sender", $this->me);
            $send_request->bindParam(":reciever", $reciever);
            $send_request->execute();

            if ($send_request->rowCount() === 1) {
                return ["Success" => "Friend request sent."];
            } else {
                return ["Error" => "Error Sending Freind Request!"];
            }
        } catch (PDOException $e) {
            return ["Error" => "DB_ERROR: <u><i><b>" . $e->getMessage() . "</b></i></u>"];
        }
    }

    function delete_friend_request($reciever): bool
    {
        $query = "DELETE FROM `friendrequest` WHERE `sender` = :me AND `reciever` = :user";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":me" => $this->me, ":user" => $reciever]);
        return $stmt->rowCount() > 0;
    }

    function respond_to_request(string $reciever, bool $accepted)
    {
        // first remove from friend requests
        $sql1 = "DELETE FROM friendrequest WHERE (sender = :sender AND reciever = :reciever) OR (reciever = :sender AND sender = :reciever)";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bindParam(":sender", $this->me);
        $stmt1->bindParam(":reciever", $reciever);
        $stmt1->execute();

        // check if friend request was accepted
        if ($stmt1->rowCount() > 0 && $accepted) {
            // then make them friends
            $sql = "INSERT INTO friends(friend, partener) VALUES(:friend, :partener)";
            $confirm = $this->db->prepare($sql);
            $confirm->bindParam(":friend", $reciever);
            $confirm->bindParam(":partener", $this->me);
            $confirm->execute();

            // send response message
            if ($confirm->rowCount() > 0) {
                return ["Success" => "You are now friends."];
            } else {
                return ["Error" => "Unable to make Friend."];
            }
            // if the friend request was rejected
        } else if ($stmt1->rowCount() > 0 && !$accepted) {
            // send response message
            return ["Success" => "Friend request removed."];
        } else {
            // if unknown error occured
            // send response message
            return ["Error" => "Something went wrong!"];
        }
    }

    function get_friend_requests($bool = true)
    {
        $sql = "SELECT `users`.* FROM friendrequest JOIN users ON users.username = friendrequest.sender WHERE reciever = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->me]);

        if ($bool && $stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } else if ($stmt->rowCount() == 0 && $bool) {
            return [];
        } else {
            return $stmt->rowCount();
        }
    }

    function get_all_friends(string $user = "", bool $bool = true)
    {
        if (!$user) {
            $user = $this->me;
        }

        $sql = "SELECT users.* FROM friends JOIN users ON (CASE WHEN friends.friend = :user THEN friends.partener ELSE friends.friend END) = users.username WHERE friends.friend = :user OR friends.partener = :user";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":user", $user);
        $stmt->execute();

        if ($stmt->rowCount() > 0 && $bool) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } elseif ($stmt->rowCount() > 0 && !$bool) {
            return $stmt->rowCount();
        } elseif ($stmt->rowCount() === 0 && $bool) {
            return [];
        } else {
            return 0;
        }
    }

    function delete_friend($friend)
    {
        try {
            $sql = "DELETE FROM friends WHERE (friend = :friend AND partener = :partener) OR (partener = :friend AND friend = :partener)";
            $delete_friend = $this->db->prepare($sql);
            $delete_friend->bindParam(":friend", $this->me);
            $delete_friend->bindParam(":partener", $friend);
            $delete_friend->execute();

            if ($delete_friend->rowCount() > 0) {
                return ["Success" => "Friend Deleted Successfully."];
            } else {
                return ["Error" => "Friend was not found."];
            }
        } catch (PDOException $e) {
            return ["Error" => "DB_ERROR: <u><i><b>" . $e->getMessage() . "</b></i></u>"];
        }
    }

    function get_mutual_friends(string $user, bool $get_num = false)
    {
        // mutual friends query
        $we_are_friends = <<<QE
        (`friends`.`partener` = :me OR `friends`.`friend` = :me)
        QE;

        $they_are_friends = <<<QE
        (`friends`.`friend` = :user OR `friends`.`partener` = :user)
        QE;

        $query = "SELECT users.* FROM `friends` 
        JOIN `users` ON (
            CASE 
                WHEN friends.friend = :me THEN friends.partener 
                ELSE friends.friend 
            END
        ) = `users`.username
        WHERE $we_are_friends AND $they_are_friends";

        $stmt = $this->db->prepare($query);
        $stmt->execute([":me" => $this->me, ":user" => $this->user]);

        if ($get_num) {
            return $stmt->rowCount();
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
