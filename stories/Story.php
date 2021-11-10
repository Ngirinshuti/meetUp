<?php

/**
 * Story php file
 *
 * @category Social_App
 * @package  ViaChat
 * @author   ISHIMWE <ishimwedeveloper@gmail.com>
 * @license  MIT url
 * @link     https://meet_up.com
 */
if (!class_exists('Message')) {
    include_once __DIR__ . "/../classes/message.php";
}
require_once __DIR__ . "/../classes/DB.php";

/**
 * Help interacting with stories
 *
 * @category Social_App
 * @package  ViaChat
 * @author   ISHIMWE <ishimwedeveloper@gmail.com>
 * @license  MIT url
 * @link     https://meet_up.com
 */
class Story
{
    public int $id;
    public string $username;
    public string $image;
    public string $description;
    public string $media;
    public bool $expired;
    public bool $has_media;
    public string $created_at;
    public int $views;


    /**
     * Create new story
     *
     * @param string $username    User who created the story
     * @param string $image       Path to story image if any
     * @param string $description Story Text description
     * @param string $media       Path to associated audio or video if any
     *
     * @return Story
     */
    public static function create(
        string $username,
        string $image = "",
        string $description = "",
        string $media = ""
    ): Story {
        $has_media = empty($media) ? 0 : 1;

        $sql = <<<Q
        INSERT INTO `stories`
        (`username`, `image`, `description`, `has_media`, `media`)
            VALUES (?, ?, ?, ?, ?)
        Q;

        $connection = DB::conn();

        $stmt = $connection->prepare($sql);
        $inserted = $stmt->execute(
            [$username, $image, $description, $has_media, $media]
        );

        if ($inserted) {
            // last inserted story id
            $story_id = $connection->lastInsertId();
            // select story from db
            return Story::findOne($story_id);
        }
    }

    /**
     * Get one story from database
     *
     * @param integer $id Story id
     *
     * @return Story|bool
     */
    public static function findOne(int $id): Story|bool
    {
        // make database connection
        $connection = DB::conn();

        // query to select story
        $new_stmt = $connection->prepare(
            "SELECT * FROM `stories` WHERE `id` = ?
            AND DATEDIFF(NOW(), `created_at`) < 1"
        );

        $new_stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $new_stmt->execute([$id]);

        return $new_stmt->fetch();
    }

    /**
     * List all user stories
     *
     * @param string $username Stories owner
     *
     * @return Story[] array of stories
     */
    public static function getUserStories(string $username):array
    {
        // $last
        $stmt = DB::conn()->prepare(
            "SELECT * FROM `stories` WHERE `username`=:username AND DATEDIFF(NOW(), `created_at`) < 1"
        );

        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute([':username' => $username]);

        return $stmt->fetchAll();
    }

    /**
     * Get stories from friends
     *
     * @param string $username stories viewer
     *
     * @return array<Story>
     */
    public static function getFriendsStories(string $username)
    {
        $we_are_friends = <<<QE
        (friend = `stories`.`username` AND partener = :user)
        OR
        (partener = `stories`.`username` AND friend = :user)
        QE;

        $query = <<<QUERY
            SELECT *, COUNT(`stories`.`id`) as story_count
             FROM `stories` WHERE EXISTS
             (SELECT * FROM `friends` WHERE ($we_are_friends))
             AND DATEDIFF(NOW(), `created_at`) < 1
             GROUP BY `stories`.`username`
             ORDER BY `stories`.`created_at` DESC
        QUERY;

        $stmt = DB::conn()->prepare($query);

        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute([':user' => $username]);

        return $stmt->fetchAll();
    }


    /**
     * View story
     *
     * @param string $username story viewer
     * @param int    $story_id story id
     *
     * @return bool
     */
    public static function viewStory(string $username, int $story_id)
    {
        if (Story::findOne($story_id)?->viewedBy($username)) {
            return true;
        }

        $query = "INSERT INTO `story_views`
        (`username`, `story_id`) VALUES (:user,:story)";
        $stmt = DB::conn()->prepare($query);

        $stmt->execute([':user' => $username, ':story' => $story_id]);

        $stmt2 = DB::conn()->prepare(
            "UPDATE `stories` SET `stories`.`views` = `stories`.`views` + 1 WHERE `id`= ?"
        );
        $stmt2->execute([$story_id]);

        return ($stmt->rowCount() && $stmt2->rowCount());
    }



    /**
     * Set story owner
     *
     * @param object|array $user user
     *
     * @return void
     */
    public function setUser(object|array $user = null)
    {
        $this->user = $user;
    }


    /**
     * Get user latest story
     *
     * @param string $username Usename
     *
     * @return Story
     */
    public static function findByUser(string $username)
    {
        $stmt = DB::conn()->prepare(
            "SELECT *,
            IFNULL(
                (SELECT COUNT(`stories`.`id`)
                FROM `stories` WHERE `username` = ?
                AND DATEDIFF(NOW(), `created_at`) < 1),
                0
            ) as story_count
            FROM `stories`
            WHERE `username`=?
            AND DATEDIFF(NOW(), `stories`.`created_at`) < 1
            ORDER BY created_at ASC LIMIT 1"
        );
        $stmt->execute([$username, $username]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

        return $stmt->fetch();
    }

    /**
     * Reply to user story
     *
     * @param string $username Sender username (usually current user)
     * @param string $message  Reply mesage
     *
     * @return mixed
     */
    public function reply(string $username, string $message)
    {

        $sent = (new Message(DB::conn(), $username))->send_message(
            $this->username,
            $message,
            $this->id
        );

        return $sent === true;
    }

    /**
     * Check user viewed a story
     *
     * @param string $username user name
     *
     * @return bool
     */
    public function viewedBy(string $username)
    {
        $sql = "SELECT * FROM `story_views` WHERE `username` =:username AND `story_id` = :story";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute([':username' => $username, ':story' => $this->id]);

        return $stmt->rowCount() > 0;
    }
}
