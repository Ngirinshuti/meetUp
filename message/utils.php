<?php require "../classes/init.php";

	if (isset($_GET["page"]) or isset($_POST["page"])) {
		if (isset($_POST["m_r"]) && isset($_POST["m_body"])) {
			$page = $_POST["page"];
			$m_r = $_POST["m_r"];
			$m_body = $_POST["m_body"];
			$result = $msg_obj->send_message($m_r, $m_body);
			throw_result($result, $page ."?m_r=" . $m_r);
		}
	}
	else {
		echo "<script>history.back();</script>";
	}

	function throw_result($result, $page){
		if (isset($result["Error"])) {
			header("location: ".$page."&Error=".$result["Error"]. "#error");
		}
		elseif (isset($result["Info"])) {
			header("location: ".$page."&Info=".$result["Info"]. "#info");
		}
		elseif (isset($result["Success"])) {
			header("location: ".$page."&Success=".$result["Success"]. "#success");
		}
		else {
			header("location: ".$page);
		}
	}

	function test_input($data){
	 $data = trim($data);
	 $data = stripslashes($data);
	 $data = htmlspecialchars($data);
	 return $data;
	}

?>
