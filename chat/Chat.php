<?php

require_once __DIR__ . '/../auth/User.php';

interface MessageInterface
{
   
    /**
     * Get a single message from db
     *
     * @param integer $id
     * 
     * @return Message|boolean
     */
    public static function findOne(int $id):Message|bool;

    /**
     * Create a message
     *
     * @param string $sender
     * @param string $reciever
     * @param string $body
     * @param integer|null $story_id
     * @param integer|null $group_id
     * @return Message
     */
    public static function create(
        string $sender,
        string $reciever,
        string $body,
        ?int $story_id,
        ?int $group_id
    ): Message|bool;

    /**
     * Get message sender
     *
     * @return User
     */
    public function getSender(): User;

    /**
     * Get message group
     *
     * @return object
     */
    public function getGroup(): object|null;

    /**
     * Get replied story
     *
     * @return object|null
     */
    public function getStory(): object|null;


    /**
     * Get message reciever
     *
     * @return User
     */
    public function getReciever(): User;

    /**
     * Change message status
     *
     * @param integer $id
     * @param string $status
     * 
     * @return Message|boolean
     */
    public function setStatus(int $id, string $status): Message|bool;


    /**
     * Gets a user most recent messages
     *
     * @param string $reciever
     * 
     * @return array
     */
    public static function getUserRecentMessages(string $reciever): array;

    /**
     * Gets messages between users
     *
     * @param string $sender
     * @param string $reciever
     * 
     * @return array
     */
    public static function getConversation(string $sender, string $reciever): array;
}



interface GroupInterface
{

    /**
     * Gets a single group from db
     *
     * @param integer $group_id
     * @return Group|boolean
     */
    public static function findOne(int $group_id):Group|bool;

    /**
     * Creates a new group
     *
     * @param string $name
     * @return Group
     */
    public static function create(string $name, string $about): Group;

    /**
     * Adds a user to a group
     *
     * @param string $username
     * @param integer $group_id
     * @return boolean
     */
    public function join(string $username, string $role = "member"): bool;

    /**
     * Removes a user from a group
     *
     * @param string $username
     * @param integer $group_id
     * @return boolean
     */
    public function leave(string $username): bool;


    /**
     * Gets group messages
     *
     * @param integer $group_id
     * @return array
     */
    public function  getMessages(): array;

    /**
     * Gets group members
     *
     * @param integer $group_id
     * @return array
     */
    public function getMembers(): array;

    /**
     * Checks if a user is a group member
     *
     * @param string $username
     * @return boolean
     */
    public function isMember(string $username): bool;
}

