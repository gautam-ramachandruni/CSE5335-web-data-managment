<!-- Gautam Ramachandruni -->	
	<?php
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	session_start();
	$loginMsg = "";
	try {
		$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$dbh->beginTransaction();
		$dbh->exec('delete from users where username="gautam1"');
		$dbh->exec('delete from users where username="gautam2"');
		$dbh->exec('insert into users values("gautam1","' . md5("aaa") . '","Gautam Ramachandruni","gautam.ramachandruni@mavs.uta.edu")')
					or die(print_r($dbh->errorInfo(), true));
		$dbh->exec('insert into users values("gautam2","' . md5("bbb") . '","Gautam Ramachandruni","gautam.ramachandruni@mavs.uta.edu")')
					or die(print_r($dbh->errorInfo(), true));
		$dbh->commit();
	
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	$scriptName = $_SERVER["PHP_SELF"]; // page redirection to it'self' i.e., login.php <--> login.php
	if(isset($_POST['logoutButton'])){
			unset($_SESSION["username"]);
			unset($_SESSION["password"]);
			$usr = "";
			$pwd = "";
	}
	else if (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
			header("Location: board.php");
	}
	else {
			$username = ""; //initialize the table username
			$password = ""; // intialize the table password
			
			if (isset($_POST["loginButton"])) {
				//user attempts to login i.e., clicks on the submit button
				$usr = trim($_POST["username"]); // get the username entered
				$pwd = trim($_POST["password"]); // get the password entered
				$encryptedPassword = md5($pwd); // get the encrypted version of the password
				
				//get the user info from table
				$getUsers = $dbh->prepare("SELECT * FROM users");
				//execute sql query
				$getUsers->execute();
				//get the rows from the result
				$users = $getUsers->fetchAll();
				$userFound = FALSE;
				if(count($users)<=0){
					//no users registered in the database
					$loginMsg = "No users registered!";
				}
				else if(count($users)>0){
					foreach ($users as $user) {
						if($user['username'] === $usr && $user['password'] === $encryptedPassword){
							//username and encrypted password match the username and password in the table
							$username = $user['username']; //get the username in the table
							$password = $user['password']; // get the password in the table
							//then successfully logged in, go to message board
							$scriptName = "board.php";
							$_SESSION['username'] = $username;
							$_SESSION['password'] = $password;
							$userFound = TRUE;
						} // if right user found
					} // check all users
					if(!$userFound){
						//username and password dont match, unset the session variables. scriptname is still login.php so user will be redirected to the login page again
						$loginMsg = "Invalid username or password, please try again!";
						//unset($_SESSION['username']);
						//unset($_SESSION['password']);
						$usr = "";
						$pwd = "";
					}
				} // if there are users
				unset($_POST['loginButton']);
			} // end $_POST['login']
			else {
				// initial state of the login page - username and password textfields should be emptys
				$usr = "";
				$pwd = "";
			}
		}
	?>
	
	<html>
		<head><title>Login Page</title></head>
		<body>
			<h2>Login Page</h2>
				<form action="<?php echo $scriptName ?>" method="post">
					<strong>Username: </strong><input type="text" name="username" value= "<?php echo $usr ?>" required/>
					<br>
					<br>
					<strong>Password: </strong><input type="password" name="password" value="<?php echo $pwd ?>"/>
					<br>
					<br>
					<input type="submit" name="loginButton" value="Submit"/>
					<br>
					<br>
					<label><b><?php echo $loginMsg ?></b></label>
				</form>
		</body>
	</html>