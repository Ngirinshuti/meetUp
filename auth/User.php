<?php

/**
 * New user file
 */
require __DIR__ . "/../config.php";
require_once $ROOT_DIR .  "/classes/DB.php";


/**
 * Interacts with users table
 */
class User
{
    public string $username;
    public string $email;
    public string $password;
    public string $fname;
    public string $lname;
    public string $dob;
    public string $sex;
    public string $about;
    public string $profile_pic;
    public string $address;
    public string $status;
    public string $code;
    public bool $verified;
    public string $remember_me;
    public ?string $last_seen;


    /**
     * Create user
     *
     * @param string $fname    user fisrtname
     * @param string $email    user email
     * @param string $username user username
     * @param string $password user password
     * @param string $lname    user lastname
     * 
     * @return User
     */
    public static function create(
        string $fname,
        string $email,
        string $username,
        string $password,
        string $lname = "",
        string $dob = "",
        string $sex = "",
        string $about = "",
        string $profile_pic = "default.png",
        string $address = "",
        string $status = "offline",
        string $code = "",
        bool $verified = false,
        string $remember_me = ""
    ): User {
        $query = "INSERT INTO `users` 
            (
                `fname`,
                `email`,
                `username`,
                `password`,
                `lname`,
                `dob`,
                `sex`,
                `about`,
                `profile_pic`,
                `address`,
                `status`,
                `code`,
                `verified`,
                `remember_me`
            )
            VALUES (
                :fname,
                :email,
                :username,
                :password,
                :lname,
                :dob,
                :sex,
                :about,
                :profile_pic,
                :address,
                :status,
                :code,
                :verified,
                :remember_me
            )
        ";
        $conn = DB::conn();
        $stmt = $conn->prepare($query);
        $stmt->execute(
            [
                ":fname" => $fname,
                ":email" => $email,
                ":username" => $username,
                ":password" => $password,
                ":lname" => $lname,
                ":dob" => $dob,
                ":sex" => $sex,
                ":about" => $about,
                ":profile_pic" => $profile_pic,
                ":address" => $address,
                ":status" => $status,
                ":code" => $code,
                ":verified" => $verified,
                ":remember_me" => $remember_me
            ]
        );

        return User::findOne($username);
    }

    /**
     * Get a user by username or email
     *
     * @param string $username user username
     * @param string $email    user email
     * 
     * @return User|boolean
     */
    public static function findOne(
        string $username = "",
        string $email = ""
    ): User|bool {
        $query = "SELECT * FROM `users` WHERE `username` = ? OR `email` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$username, $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    /**
     * Get specific column value for the user (like username, etc)
     *
     * @param string $property column name to retrieve
     * 
     * @return mixed column value
     */
    public function getProperty(string $property)
    {
        $query = "SELECT `$property` FROM `users` WHERE `username` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$this->username]);

        return $stmt->fetch()[$property];
    }


    /**
     * Update user specific column value
     *
     * @param string $property the column name
     * @param mixed  $value    column new value
     * @param bool  $raw  insert as raw value
     * 
     * @return bool
     */
    public function setProperty(string $property, mixed $value, $raw = false): bool
    {
        if ($raw) {
            return boolval(
                DB::conn()->query("UPDATE `users` SET `$property` = $value  WHERE `username` = '{$this->username}'")?->rowCount()
            );
        }


        $query = "UPDATE `users` SET `$property`= ? WHERE `username` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$value, $this->username]);

        return (bool) $stmt->rowCount();
    }

    /**
     * Get all users execept specified
     *
     * @param array $except excluded users
     * @return array
     */
    public static function getAll(array $except = []): array
    {
        $args = implode(",", str_split(str_repeat("?", count($except))));

        $args = strlen($args) > 0 ? $args : ", $args";

        $query = "SELECT * FROM `users` WHERE `username` NOT IN ('', $args) LIMIT 25";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute($except);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    /**
     * Get all non friends users
     * 
     * @return array
     */
    public function getNonFriends(): array
    {
        $query = "SELECT * FROM `users` WHERE `users`.`username` NOT IN 
        (
            SELECT DISTINCT (CASE WHEN friends.friend = :me THEN friends.partener ELSE friends.friend END)
            FROM `friends` WHERE :me  IN (`friends`.`partener`,`friends`.`friend`)
        )
        AND `users`.`username` NOT IN 
        (
            SELECT DISTINCT (CASE WHEN sender = :me THEN reciever ELSE sender END) 
            FROM `friendrequest` WHERE :me IN (sender,reciever)
        )
        AND `users`.`username` != :me";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([":me" => $this->username]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }


    /**
     * Search a user
     *
     * @param string $search search
     * @return array
     */
    public static function search(string $search): array
    {
        $query = "SELECT * FROM `users` 
            WHERE (SELECT CONCAT(users.username,' ', users.fname, ' ', users.lname, ' ', users.address, ' ', users.email)) LIKE :me 
        ";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([":me" => "%$search%"]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    /**
     * Get user by password reset token
     *
     * @param string $token
     * 
     * @return User|boolean
     */
    public static function getByResetToken(string $token): User|bool
    {
        $query = "SELECT `users`.* 
        FROM `pwdreset`
        JOIN `users` ON `pwdreset`.`email` = `users`.`email`
        WHERE `pwdreset`.`token` = :token AND `pwdreset`.`verified` = 0";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([":token" => $token]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    /**
     * create a user password reset token
     *
     * @return string
     */
    function createResetToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $query = "INSERT INTO `pwdreset` (`email`, `token`) 
                VALUES  (?, ?)
        ";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$this->email, $token]);
        return $token;
    }

    public function markTokenAsVerified(string $token)
    {
        $query = "UPDATE `pwdreset` SET `verified`=1 WHERE `email` = ? AND token= ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$this->email, $token]);
        return boolval($stmt->rowCount());
    }


    /**
     * Change user password
     *
     * @param string $new_pass
     * @return void
     */
    public function changePassword(string $new_pass)
    {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        return $this->setProperty("password", $hash);
    }

    /**
     * Update user properties
     *
     * @param array $properties
     * @return User
     */
    public function updateProperties(array $properties): User
    {
        $cols = implode("= ?, ", array_keys($properties)) . " = ?";
        $values = array_values($properties);

        $query = "UPDATE `users` SET $cols WHERE `username` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([...$values, $this->username]);

        return self::findOne($this->username);
    }
}
