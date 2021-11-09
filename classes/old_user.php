<?php


class OldUser
{
    protected $me;
    protected $db;

    function __construct($conn, $me)
    {
        $this->db = $conn;
        $this->me = $me;
    }

    function get_users($expression)
    {
        try {
            $sql  = "SELECT * FROM users WHERE $expression";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function user_exist($user)
    {
        $sql = "SELECT * FROM users WHERE username  = ?";
        $stmt  = $this->db->prepare($sql);
        $stmt->execute([$user]);
        if ($stmt->rowCount() === 1) {
            return true;
        } else {
            return false;
        }
    }

    function invalid_date($date)
    {
        return ["Error" => "date (" . $date . ") is not valid"];
        exit;
    }

    function register_user($fname, $lname, $email, $dob, $sex, $username, $password, $code, $verify)
    {
        try {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
                return ["Error" => "Invalid email address"];
                exit;
            }
            $salted = "salting_string@12345" . $password;
            $password = sha1($salted);

            $sql1     = "SELECT username FROM users WHERE username = ?";
            $sql2     = "SELECT username FROM users WHERE email = ?";
            $stmt1    = $this->db->prepare($sql1);
            $stmt2    = $this->db->prepare($sql2);
            $stmt1->execute([$username]);
            $stmt2->execute([$email]);

            if ($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0) {
                return ['Error' => 'Username <u>' . $username . '</u> and e-mail <u>' . $email . '</u> are both taken.'];
                exit;
            } elseif ($stmt1->rowCount() > 0 && $stmt2->rowCount() === 0) {
                return ['Error' => 'Username <u>' . $username . '</u> is unavailable.'];
                exit;
            } elseif ($stmt1->rowCount() === 0 && $stmt2->rowCount() > 0) {
                return ['Error' => 'e-mail <u>' . $email . '</u> was taken.'];
                exit;
            }

            $sql = "INSERT INTO users(fname, lname, email, dob, sex, username, password,code,verify) VALUES(:fname, :lname, :email, :dob, :sex, :username, :password, :code, :verify)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":fname", $fname);
            $stmt->bindParam(":lname", $lname);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":dob", $dob);
            $stmt->bindParam(":sex", $sex);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":code", $code);
            $stmt->bindParam(":verify", $verify);
            $stmt->execute();
            //$_SESSION['email']=$email;
            if ($stmt->rowCount() > 0) {
                $to = $email;
                $subject = "confirmation code";
                $message = $code;
                $header = "From:prudentenz001@gmail.com \r\n";
                $retval = mail($to, $subject, $message, $header);
                header("location:testing.php?conf=sent");
                //return['Success' => 'Account created successfully'];
            } else {
                return ['Error' => 'Account creation failed please try again!'];
            }
        } catch (PDOException $e) {
            return ['Error' => '<big>' . $e->getMessage() . '</big>'];
        }
    }
    function verify_user($code, $email)
    {
        $query = "SELECT * FROM users WHERE code=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$code]);
        if ($stmt->rowCount() > 0) {
            $sql = "UPDATE users SET verify='Verified' WHERE email=? ";
            $stmti = $this->db->prepare($sql);
            $stmti->bindParam(":email", $email);
            $stmti->execute([$email]);
            //$stmti->close();
            header('location:index.php?ver=try');
        } else {
            //$email=$_SESSION['email'];
            header('location:testing.php?email=$email');
        }
    }
    function verify_now($email)
    {
        $query = "SELECT * FROM users WHERE email=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {

            //$_SESSION['email']=$email;
            $otp = mt_rand(100000, 999999);

            $sql = "UPDATE users SET code='$otp' WHERE email=? ";
            $stmti = $this->db->prepare($sql);
            //	$stmti->bindParam(":email", $email);
            $stmti->execute([$email]);
            //$_SESSION['stat'] = $status;
            $to = $email;
            $from = "From: prudentenz001@gmail.com";
            $subject = "Verification Code";
            $message = $otp;

            $mailing = mail($to, $subject, $message, $from);

            header("location:testing.php?message=code");
        } else {
            header('location: verified.php?message=code');
        }
    }
    function reset_password($email)
    {
        $query = "SELECT * FROM users WHERE email=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);
            $validator = bin2hex($token);
            $url = "http://localhost/project2/createnewpass.php?selector=" . $selector;
            $expires = date("U") + 1800;
            $quer = "DELETE FROM pwdreset WHERE email=?";
            $stmt = $this->db->prepare($quer);
            $stmt->execute([$email]);
            $sql = "INSERT INTO pwdreset(email,reset,token,expires) VALUES(:email, :selector, :validator, :expires)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":selector", $selector);
            $stmt->bindParam(":validator", $validator);
            $stmt->bindParam(":expires", $expires);

            $stmt->execute();
            $from = 'prudentenz001@gmail.com';
            $to = $email;
            $subject = 'Reset password';
            $message = '<p>Here is the link you need to follow';
            $message .= '<a href="' . $url . '</a></p>';
            $headers = 'From: ' . $from;
            $headers .= 'Reply-To: ' . $from;
            $headers .= 'Content-type:text/html';
            mail($to, $subject, $message, $headers);
            header("location:recover.php?reset=success");
        } else {
            header("location:recover.php?found=success");
        }
    }
    function reset_password1($pwd, $pwd_repeat, $selector)
    {
        //echo "sdfghjklfdghj";
        if (empty($pwd) || empty($pwd_repeat)) {
            header("location:createnewpass.php?newpwd=pwdnotsame");
        } else if ($pwd != $pwd_repeat) {
            header("location:createnewpass.php?selector=$selector&newpwd=pwdnotsame");
        } else {
            $query = "SELECT * FROM pwdreset WHERE reset=?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$selector]);

            if ($stmt->rowCount() === 0) {
                echo "you need to re-submit your request";
            } else {
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                $email = $row->email;
                $query = "SELECT * FROM pwdreset WHERE email=?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$email]);
                if ($stmt->rowCount() === 0) {
                    echo "There is an error";
                } else {
                    $salted = "salting_string@12345" . $pwd;
                    $password = sha1($salted);
                    $sql = "UPDATE users SET password='$password' WHERE email=? ";
                    $stmti = $this->db->prepare($sql);
                    //$stmti->bindParam(":email", $email);
                    $stmti->execute([$email]);
                    $quer = "DELETE FROM pwdreset WHERE email=?";
                    $stmt = $this->db->prepare($quer);
                    $stmt->execute([$email]);
                    header("location:index.php?newpwd=passwordupdated");
                }
            }
        }
    }
    function login_user($identifier, $password, $remember)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :id OR username = :id";
            $login = $this->db->prepare($sql);
            $login->bindParam(":id", $identifier);
            $login->execute();

            if ($login->rowCount() === 1) {
                $salted = "salting_string@12345" . $password;
                $pass = sha1($salted);
                $row = $login->fetch(PDO::FETCH_OBJ);
                $match_pass = $row->password;
                $verify = $row->verify;
                if ($match_pass == $pass) {
                    if ($verify == 'Verified') {
                        if ($row->status != "online") {
                            $sql1 = "UPDATE users SET status = 'online' WHERE email = :id OR username = :id";

                            $update = $this->db->prepare($sql1);
                            $update->bindParam(":id", $row->username);
                            $update->execute();
                            if (!empty($remember)) {
                                $check = $remember;
                                setcookie("name", $row->username, time() + 3600 * 24 * 7);
                                setcookie("password", $password, time() + 3600 * 24 * 7);
                                setcookie("check", $check, time() + 3600 * 24 * 7);
                            } else {
                                $check = 0;
                                setcookie("name", $row->username, 7);
                                setcookie("password", $password, 7);
                                setcookie("check", $check, 7);
                            }

                            if ($update->rowCount() == 1) {
                                $_SESSION["a_user"] = $row->username;
                                return ["Success" => 1];
                            } else {
                                return ["Error" => "Please try again."];
                            }
                        } else {
                            $_SESSION["a_user"] = $row->username;
                            return ["Success" => 1];
                        }
                    } else {
                        return ["Error" => "Account is not Verified! <a href='verified.php' style='color:green;'>Verify Now</a>"];
                    }
                } else {
                    return ["Error" => "Incorrect e-mail address or Password!"];
                }
            } else {
                return ["Error" => "Incorrect e-mail address or Password!"];
            }
        } catch (PDOException $e) {
            return ["Error" => "Error: " . $e->getMessage()];
            exit;
        }
    }

    function get_all_users($bool)
    {
        try {
            $sql   = "SELECT profile_pic, username, lname, fname FROM users WHERE username != ?";
            $run_query = $this->db->prepare($sql);
            $run_query->execute([$this->me]);

            if ($run_query->rowCount() > 0 && $bool) {
                return $run_query->fetchAll(PDO::FETCH_OBJ);
            } else if (!$run_query->rowCount() && $bool) {
                return ["Error" => "We have problems getting users."];
            } else {
                return $run_query->rowCount();
            }
        } catch (PDOException $e) {
            return ["Error" => "DB_ERROR: <u><i><b>" . $e->getMessage() . "</b></i></u>"];
        }
    }

    function get_user_data($username)
    {
        try {
            $sql  = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username]);

            if ($stmt->rowCount() == 1) {
                return $stmt->fetch(PDO::FETCH_OBJ);
            } else {
                return ["Error" => " User data not found."];
            }
        } catch (PDOException $e) {
            return ["Error" => "DB_ERROR: <u><i><b>" . $e->getMessage() . "</b></i></u>"];
        }
    }

    function update_property($property, $value)
    {
        $sql  = "UPDATE users SET $property = :value WHERE username = :user";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":value", $value);
        $stmt->bindParam(":user", $this->me);
        $stmt->execute();
        if ($stmt->rowCount() === 1) {
            return ["Success" => "Successfully updated <u>" . $property . ".</u>"];
        } else {
            return ["Error" => "Error upadating <u>" . $property . ".</u>"];
        }
    }
    function create_post($texts, $dates, $user)
    {
        // image upload
        $image_file_name = $_FILES['image']['name'];
        $image = "./images/" . $image_file_name;

        // random image name
        $random_int = (string) random_int(100, 10000000);
        $image_name_separated = explode(".", $image_file_name);
        $ext = "." . end($image_name_separated);
        $random_image_name = "./images/" . $random_int . $ext;
        $uploaded = !empty($image_file_name) && move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $random_image_name
        );
        $image_name = basename(!$uploaded  ? "" :  $random_image_name);

        // video upload
        $video_file_name = $_FILES['video']['name'];
        $video = "./videos/" . $video_file_name;
        // random video name and upload
        $random_int = strval(random_int(100, 10000000));
        $video_name_separated = explode(".", $video_file_name);
        $ext = "." . end($video_name_separated);
        $random_video_name = "./videos/" . $random_int . $ext;
        $uploadedv = !empty($video_file_name) && move_uploaded_file(
            $_FILES['video']['tmp_name'],
            $random_video_name
        );
        $video_name = basename(!$uploadedv  ? "" :  $random_video_name);

        if (empty($texts) && empty($_FILES['image']['name']) && empty($_FILES['video']['name'])) {
            return ["Error" => "Post is not submitted because you submitted an empty post."];
        } else {

            $sql = "INSERT INTO posts(post,image,video, username,date) VALUES( :texts, :image_name, :video_name, :user, :dates)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":texts", $texts);
            $stmt->bindParam(":image_name", $image_name);
            $stmt->bindParam(":video_name", $video_name);
            $stmt->bindParam(":user", $user);
            $stmt->bindParam(":dates", $dates);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            }
        }
    }
    
    function profile1($user)
    {

        $sql   = "SELECT username FROM posts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $username = $row->username;
        if ($stmt->rowCount() == 1) {
            $sql1   = "SELECT * FROM users WHERE username = ?";
            $userse = $_SESSION["a_user"];
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([$username]);
            $row = $stmt1->fetchAll(PDO::FETCH_OBJ);
            foreach ($row as $val) {
                $fname = $val->fname;
                $lname = $val->lname;
                $email = $val->email;
                $about = $val->about;
                $address = $val->address;
                $usern = $val->username;
                if ($usern == $userse) {
                    header("location:../friends/profile.php");
                } else {
                    require_once '../theme.php';
                    $_SESSION['p_userfname'] = $fname;
                    $_SESSION['p_userlname'] = $lname;
                    $_SESSION['p_userabout'] = $about;
                    $_SESSION['p_useraddress'] = $address;
                }
            }
        }
    }
    function profile1_image($user)
    {
        $sql   = "SELECT username FROM posts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $username = $row->username;
        //echo "fghjkl".$username;
        if ($stmt->rowCount() == 1) {

            $sql1   = "SELECT profile_pic FROM users WHERE username = ?";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([$username]);
            $row = $stmt1->fetchAll(PDO::FETCH_OBJ);
            foreach ($row as $val) :
                echo <<<OUT
					<img src='../images/$val->profile_pic' style="width: 4cm;height: 8cm;">
					OUT;
            endforeach;
        }
    }
}
