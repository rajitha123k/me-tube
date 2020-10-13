<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Groups</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<body>
<div class="topnav">
  <a class="active" href="browse.php">MeTube</a>
  <?php 
	if (! empty($_SESSION['logged_in']))
	{
  		echo "<a href='logout.php'>Logout</a>
  		<a href='update.php'>Profile</a>";
	}
	else {
		echo"<a href='index.php'>Login</a>";
		echo"<a href='register.php'>Register</a>";
	}
  ?>
</div>

<?php 
	if(isset($_POST['submit'])){
		$username = $_SESSION['username'];
		$groupname = $_GET['id'];
		$msg = $_POST['message'];
		$query = "INSERT INTO group_messages(groupname, username, message) VALUES ('$groupname', '$username', '$msg')";
		$result = mysqli_query($con, $query);

		if($result){
			$smsg = "Message Created Successfully";
			$msgpath='Location: groups.php?id='.$_GET["id"];
			header($msgpath);
		}
		else {
			$fmsg = "Message Failed".mysqli_error($con);
		}
	}
?>
<?php
	$groupname = $_GET['id'];
	$query = "SELECT * FROM group_messages WHERE groupname='$groupname'";
	$res = mysqli_query($con, $query);
	$query = "SELECT topic FROM groups WHERE groupname='$groupname'";
	$r = mysqli_query($con, $query);
	$r_row = mysqli_fetch_row($r);
?>
<h1>Topic:<?php echo $r_row[0]?></h1>
<h4>Messages</h4>
<table>
	<tr>
		<th>Username</th>
		<th>Message</th>
	</tr>

	<?php
		while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
			
	?>
		<tr>
			<td><?php echo $row[1] ?></td>
			<td><?php echo $row[2] ?></td>
		</tr>
		<?php } ?>
		<?php 
			$msgpath="groups.php?id=".$_GET["id"]; ?> 
				<form method="POST" action=<?php echo $msgpath ?>>
					<tr>
						<td></td>
  						<td><input name="message" type="text" placeholder="New message (max 200 characters)..." maxlength="200"><br>
  							<input name="submit" type="submit" value="Post"></td>
					</tr>
				</form>
</table>