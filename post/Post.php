<?php

/**
 * Post php file
 *
 * @category Social_App
 * @package  MeetUp
 * @author   ISHIMWE <ishimwedeveloper@gmail.com>
 * @license  MIT url
 * @link     https://meet_up.com
 */

// require db connection file
require_once __DIR__ . "/../classes/DB.php";
require_once __DIR__ . "/../paginator/Paginator.php";


/**
 * Post php class
 *
 * @category Social_App
 * @package  MeetUp
 * @author   ISHIMWE <ishimwedeveloper@gmail.com>
 * @license  MIT url
 * @link     https://meet_up.com
 */

class Post
{
    public int $id;
    public string $post;
    public string $image;
    public string $video;
    public string $username;
    public string $date;


    /**
     * Create post into database
     *
     * @param array  $post     post text description
     * @param string $username owner username
     * @param string $image    uploaded image name
     * @param string $video    uploaded video name
     * 
     * @return Post
     */
    public static function create(
        string $post,
        string $username,
        string $image = "",
        string $video = ""
    ): Post {
        $sql = <<<QR
            INSERT INTO `posts` (`post`, `username`, `image`, `video`) 
            VALUES (?, ?, ?, ?)
        QR;
        $conn = DB::conn();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$post, $username, $image, $video]);
        $post_id = $conn->lastInsertId();
        $post = Post::findOne($post_id);

        return $post;
    }
    public static function update(
        string $post,
        string $image = "",
        string $video = "",
        int $p_id
    ): Post {
        $sql = <<<QR
            UPDATE posts SET post=?, image=?, video=? WHERE id=? 
        QR;
        $conn = DB::conn();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$post, $image, $video,$p_id]);
        header("Location: ../post/home.php?msg=Post updated!");
    }
public static function delete(
        int $p_id
    ): Post {
        $sql = <<<QR
            DELETE FROM posts WHERE id=? or post_id=?
        QR;
        $conn = DB::conn();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$p_id,$p_id]);
        header("Location: ../post/home.php?msg=Post deleted!");
    }

public static function share(
        string $post,
        string $username,
        string $image = "",
        string $video = "",
        int $postId
    ): Post {
        $sql = <<<QR
            INSERT INTO `posts` (`post`, `username`, `image`, `video`,`post_id`) 
            VALUES (?, ?, ?, ?,?)
        QR;
        $conn = DB::conn();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$post, $username, $image, $video,$postId]);
        $post_id = $conn->lastInsertId();
        $post = Post::findOne($post_id);

        return $post;
    }



    /**
     * Get one post from database
     *
     * @param integer $id Post id
     *
     * @return Post|bool
     */
    public static function findOne(int $id): Post|bool
    {

        // query to select post
        $stmt = DB::conn()->prepare(
            "SELECT * FROM `posts` WHERE `id` = '$id'"
        );

        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Get posts from friends
     *
     * @param string $username posts viewer
     *
     * @return array<Story>
     */
    public static function getFriendsPosts(string $username): array
    {

        
        $limit_string = (new Paginator())->getLimitString();

        $query = <<<QUERY
            SELECT `posts`.*
            FROM `posts` 
            WHERE (`posts`.`username` = :user)
            OR EXISTS
            (
                SELECT * FROM `friends` 
                WHERE :user IN (`friends`.`partener`, `friends`.`friend`) 
                AND `posts`.`username` IN (`friends`.`partener`, `friends`.`friend`)
            )
            ORDER BY `posts`.`date` DESC
            $limit_string
            QUERY;
        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute([":user" => $username]);
        $data = $stmt->fetchAll();
        return $data;
    }


    /**
     * Like a post
     * 
     * @param string $username liker username
     * 
     * @return bool
     */
    public function like(string $username)
    {
        // exit("Oka");
        if ($this->likedBy($username)) {
            return true;
        }

        $query = "INSERT INTO `post_likes` (`username`, `post_id`) VALUES (?,?)";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$username, $this->id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Check if user liked a post
     * 
     * @param string $username user username
     * 
     * @return bool
     */
    public function likedBy(string $username)
    {
        $query = "SELECT * FROM `post_likes` WHERE `username` = ? AND `post_id` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$username, $this->id]);
        // var_dump($stmt->rowCount(), $username, $this->id);
        return $stmt->rowCount() === 1;
    }

    /**
     * Unlike post
     * 
     * @param string username liker username
     * 
     * @return bool
     */
    public function unlike(string $username)
    {
        if (!$this->likedBy($username)) {
            return true;
        }

        $query = "DELETE FROM `post_likes` WHERE `username` = ? AND `post_id` = ?";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$username, $this->id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Get post likes count
     * 
     * @return int likes
     */
    public function likes()
    {
        $query = "SELECT COUNT(`post_likes`.`username`) as likes FROM `post_likes` WHERE `post_id` = ?";

        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$this->id]);
        $likes = $stmt->fetch(PDO::FETCH_OBJ)->likes;

        return $likes;
    }

    /**
     * Get post owner
     * 
     * @return object post owner
     */
    public function owner()
    {
        if (isset($this->owner)) {
            return $this->owner;
        }
        $query = "SELECT * FROM USERS WHERE `username` = ?";
        $stmt = DB::conn()->prepare($query);
        $stmt->execute([$this->username]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        $this->owner = $user;
        return $this->owner;
    }

    public static function getUserPosts(string $username): array
    {
        $limit_string = (new Paginator())->getLimitString();
$query = "SELECT * FROM posts WHERE username=? ORDER BY date DESC
            $limit_string";
        $stmt = DB::conn()->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute([$username]);
        $data = $stmt->fetchAll();
        return $data;
    }
    public static function tagedFriends(string $username,$shareDate): array
    {
        $limit_string = (new Paginator())->getLimitString();

$query = "SELECT distinct taged_friends,date FROM share WHERE (s_username=? or taged_friends=?) and date=?";
        $stmt = DB::conn()->prepare($query);
         $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute([$username,$username,$shareDate]);
        $datas = $stmt->fetchAll();
return $datas;


    }
}
