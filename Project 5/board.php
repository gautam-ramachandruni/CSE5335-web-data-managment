<!doctype html>
<!-- Gautam Ramachandruni -->
<html>
<head><title>Message Board</title></head>
<body>

	<form action="login.php" method="post" id="messageBoard">
		<h2>Message Board</h2>
		<input type="submit" name="logoutButton" value="Log out" formaction="login.php"/>
		<br><br>
		<label>Write a new message: </label>
		<br>
		<textarea id="newMessage" name="newMessage" form="messageBoard" rows="10" cols="80" value=""></textarea> 
		<br><br>
		<input type="submit" name="newPostButton" value="New Post" formaction="board.php"/>
		<br><br>
		
		<?php
			error_reporting(E_ALL);
			ini_set('display_errors','On');
			session_start();
				
			$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));	
			$errorMsg = "";	
			$username = $_SESSION['username'];
			if(isset($_POST['newPostButton'])){
				$message = htmlspecialchars($_POST['newMessage']);
				$id1 = uniqid();
				$dbh->beginTransaction();
				$dbh->exec("insert into posts values('$id1', null, '$username', now(), '$message')")
					  or die(print_r($dbh->errorInfo(), true));
				$dbh->commit();
				unset($_POST['newPostButton']);
				unset($_POST['newMessage']);
			}
			else if(isset($_GET['replyto'])){
				$replyToId = $_GET['replyto'];
				$dbh->beginTransaction();
				$message = htmlspecialchars($_POST['newMessage']);
				$id2 = uniqid();
				$statement = $dbh->prepare("insert into posts values('$id2', '$replyToId', '$username', now(), '$message')")
						or die(print_r($dbh->errorInfo(), true));
				$statement->execute();
				$dbh->commit();
				unset($_POST['replyButton']);
				unset($_POST['newMessage']);
			}
			else{
				$message = "";
			}
		?>
		
		<?php
			$getData = $dbh->prepare("select * from users inner join posts on posts.postedby = users.username order by posts.datetime desc");
			$getData->execute();
			$data = $getData->fetchAll();
			if(count($data)>0){
				echo "<br><br> <h3>Messages: </h3>";
				echo "&nbsp &nbsp &nbsp<b> Message Id -- Username -- Full Name -- Date/Time -- Replyto Id -- Message </b>";
				echo "<br>";
				foreach($data as $d){
					$messageId = $d['id'];
					$postUsername = $d['username'];
					$fullname = $d['fullname'];
					$datetime = $d['datetime'];
					$replyto = $d['replyto'];
					$messageText = $d['message'];
					echo "&nbsp &nbsp &nbsp" . "$messageId, $postUsername, $fullname, $datetime, $replyto, $messageText" . "&nbsp&nbsp&nbsp" . "<input type=\"submit\" name=\"replyButton\" value=\"Reply\" formaction=\"board.php?replyto={$messageId}\" ";
					echo "<br><br>";
				}
			}
		?>
	</form>
	<br>
<label> <?php echo $errorMsg ?> </label>
</body>
</html>