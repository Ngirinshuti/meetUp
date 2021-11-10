<?php 
// mail file



/**
 * Mail class
 */
class Mail
{
    /**
     * Send mail function
     *
     * @param string $to      the reciever
     * @param string $message mail message
     * 
     * @return void
     */
    public static function send(string $to, string $message):bool
    {
        $from = "From: prudentenz001@gmail.com \r\n";
        $subject = "Verification Code";

        return mail($to, $subject, $message, $from);
    }

    /**
     * Send mail function
     *
     * @param string $to      the reciever
     * @param string $message mail message
     * 
     * @return void
     */
    public static function sendReset(string $to, string $message): bool
    {
        $from = "From: prudentenz001@gmail.com \r\n";
        $from = "Content-Type: text/html \r\n";
        $subject = "Password Reset";

        return mail($to, $subject, $message, $from);
    }
}