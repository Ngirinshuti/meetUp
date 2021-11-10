<?php

require_once __DIR__ . '/Chat.php';
require_once __DIR__ . '/Message.php';


class Group implements GroupInterface
{
    public int $id;
    public string $name;
    public string $about;

    public static function create(string $name, string $about = ""): Group
    {
        $conn = DB::conn();
        $query = "INSERT INTO `groups` (`name`,`about`) VALUES(?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$name, $about]);

        return Group::findOne($conn->lastInsertId("id"));
    }


    public static function findOne(int $id): Group|bool
    {
        $query = "SELECT * FROM `groups` WHERE `id` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$id]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public function join(string $username, string $role = "member"): bool
    {
        $query = "INSERT INTO `user_groups` (`username`, `group_id`, `role`)
            VALUES (:username, :group_id, :role)
        ";

        $stmt = DB::conn()->prepare($query);
        $successs = $stmt->execute([":username" => $username, ":group_id" => $this->id, ":role" => $role]);

        return boolval($successs);
    }


    public function leave(string $username): bool
    {
        $query = "DELETE FROM `user_groups` WHERE `username` = ? AND `group_id` = ?";
        $stmt = DB::conn()->prepare($query);
        $success = $stmt->execute([$username, $this->id]);

        return boolval($success);
    }

    public function getMessages(): array
    {
        $query = "SELECT users.profile_pic, messages.* FROM 
        `messages` 
        JOIN users ON users.username = messages.sender
        WHERE `group_id` = ?
        ORDER BY `created_at` ASC
        ";

        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Message::class);
        $stmt->execute([$this->id]);

        return $stmt->fetchAll();
    }

    public function getMembers(): array
    {
        $query = "SELECT `users`.*, user_groups.role FROM `user_groups`
        JOIN `users` ON `users`.`username` = `user_groups`.`username`
        WHERE `group_id` = ?
        ORDER BY user_groups.role ASC
        ";
        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute([$this->id]);

        return $stmt->fetchAll();
    }


    public function isMember(string $username): bool
    {
        $query = "SELECT * FROM `user_groups` WHERE `username` = ? AND `group_id` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$username, $this->id]);
        
        return boolval($stmt->rowCount());
    }

    /**
     * Gets a user groups
     *
     * @param string $username
     * @return array
     */
    public static function getUserGroups(string $username):array
    {
        $query = "SELECT groups.* FROM `user_groups`
        JOIN `groups` ON `groups`.`id` = `user_groups`.`group_id`
        WHERE `user_groups`.`username` = ?
        ";

        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Group::class);
        $stmt->execute([$username]);

        return $stmt->fetchAll();
    }

    public function isAdmin(string $username):bool
    {
        $query = "SELECT * FROM `user_groups` 
        WHERE `username` = ? AND `group_id` = ? AND `role` = 'admin'
        ";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$username, $this->id]);

        return $stmt->rowCount() > 0;
    }


    /**
     * Get all groups
     *
     * @return array
     */
    public static function all():array
    {
        $query = "SELECT * FROM Groups";

        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Group::class);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    function delete():bool
    {
        $query = "DELETE FROM `groups` WHERE id = ?";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->rowCount() > 0;
    } 

}
