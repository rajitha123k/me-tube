<?php
session_start();

include_once "function.php";
?>

<head>
<title>Profile</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<body>
<div class="topnav">
<a class="active logo" href="browse.php"><img src="img/metube.png" width="85" height="40" alt="logo"></a>
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
</body>

<?php

	$_susername = $_SESSION['username'];
	$query = "select * from users where username='$_susername'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$_semail = $row[2];
	$_spassword = $row[1];

if(isset($_POST['submit'])) {
	if($_POST['email'] == "") {
		$update_error = "Please fill in email field.";
	}
	else {
		$email = $_POST['email'];
		$old_password = $_POST['old_password'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  		$update_error = "Invalid email format";
		}
		else {
			if($_POST['new_password'] != "") {
				$new_password = $_POST['new_password'];
				$confirm_new_password = $_POST['confirm_new_password'];

				if($old_password != $_spassword) {
					$update_error = "Old password is not correct.";
				}
				else {
					if($new_password != $confirm_new_password){
						$update_error = "New passwords do not match.";
					}
					else {
						$query = "UPDATE users SET email='$email', password='$new_password' WHERE username='$_susername'";
						$result = mysqli_query($con, $query);

						if($result){
							$smsg = "User Updated Successfully";
						}
						else {
							$fmsg = "User Update Failed".mysqli_error($con);
						}
					}
				}
			}
			else {
				if($old_password != $_spassword) {
					$update_error = "Old password is not correct.";
				}
				else {
					$query = "UPDATE users SET email='$email' WHERE username='$_susername'";
					$result = mysqli_query($con, $query);

					if($result){
						$smsg = "User Updated Successfully";
					}
					else {
						$fmsg = "User Update Failed".mysqli_error($con);
					}
				}
			}
		}
	}
}
  if(isset($update_error))
   {  echo "<div><h2>".$update_error."</h2></div>";}


if(isset($_POST['delete_contact'])) {
	$_susername = $_SESSION['username'];
	$delusername = $_POST['delete_contact'];
	$res = mysqli_query($con, "SELECT conversationID FROM conversations WHERE (userA='$_susername' AND userB='$delusername') OR (userB='$_susername' AND userA='$delusername')");
	$convIDrow = mysqli_fetch_row($res);
	$convID_del = (int)$convIDrow[0];
	$query = "SELECT id FROM users WHERE username='$_susername'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$userid = (int)$row[0];
	$query = "SELECT id FROM users WHERE username='$delusername'";
	$result = mysqli_query($con, $query);
	$row = mysqli_fetch_row($result);
	$contactid = (int)$row[0];

	$sql = "DELETE FROM conversations WHERE conversationID='$convID_del'";
	$res = mysqli_query($con,$sql);
	if(!$res){
		echo mysqli_error($con);
	}
	$sql = "DELETE FROM messages WHERE convID='$convID_del'";
	$res = mysqli_query($con,$sql);
	if(!$res){
		echo mysqli_error($con);
	}
	$sql = "DELETE FROM user_contact WHERE userid='$userid' AND contactid='$contactid'";
	$res = mysqli_query($con,$sql);
	if(!$res){
		echo mysqli_error($con);
	}
	$sql = "DELETE FROM user_contact WHERE contactid='$userid' AND userid='$contactid'";
	$res = mysqli_query($con,$sql);
	if(!$res){
		echo mysqli_error($con);
	}
}

?>

<h1>My Profile</h1>
<h3>User Info</h3>
<form method="POST" action="<?php echo "update.php"; ?>">

<?php if(isset($smsg)){ ?><div role="alert"> <?php echo $smsg; ?> </div><?php } ?>
<?php if(isset($fmsg)){ ?><div role="alert"> <?php echo $fmsg; ?> </div><?php } ?>

<table width="100%">
	<tr>
		<td  width="20%">Username:</td>
		<td width="80%"><?php echo $_SESSION['username']; ?><br /></td>
	</tr>
	<tr>
		<td  width="20%">Email:</td>
		<td width="80%"><input class="text"  type="text" name="email" maxlength="20" value="<?php echo $_semail; ?>"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Old Password (max 15 characters):</td>
		<td width="80%"><input class="text"  type="password" name="old_password" maxlength="15" value="<?php echo $_spassword; ?>"><br /></td>
	</tr>
	<tr>
	<tr>
		<td  width="20%">New Password (max 15 characters):</td>
		<td width="80%"><input class="text"  type="password" name="new_password" maxlength="15"><br /></td>
	</tr>
	<tr>
		<td  width="20%">Confirm new Password:</td>
		<td width="80%"><input class="text"  type="password" name="confirm_new_password" maxlength="15"><br /></td>
	</tr>

		<td><input name="submit" type="submit" value="Update"><br /></td>
	</tr>
</table>
</form>

<div class="my_contacts">
	<?php
		echo "<h3>Contacts</h3>";
		$query = "SELECT id FROM users WHERE username='$_susername'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($result);
		$userid = $row[0];

		$query = "SELECT username, email, priority FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE user_contact.userid='$userid' ORDER BY priority";
		$result = mysqli_query($con, $query);

		if(!$result){
			echo "fail";
		}
		else {
	?>
		<table style="width:30%">
			<tr>
				<td>Username</td>
				<td>Email</td>
				<td>Relation</td>
				<td>Message</td>
			</tr>
		<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		?>
			<?php
				$conv_query = "SELECT conversationID FROM conversations WHERE (userA='$_susername' AND userB='$row[0]') OR (userB='$_susername' AND userA='$row[0]')";
				$conv_result = mysqli_query($con, $conv_query);
				$conv_row = mysqli_fetch_row($conv_result);
				$convid = $conv_row[0];
			?>

			<tr>
				<td><?php echo $row[0] ?></td>
				<td><?php echo $row[1] ?></td>
				<td><?php echo $row[2] ?></td>
				<td><a href="message.php?id=<?php echo $convid;?>" target="_blank">Message</a></td>
				<?php
			if (! empty($_SESSION['logged_in']))
			{ 
				$query = "SELECT id FROM users WHERE username='$_susername'";
				$res = mysqli_query($con, $query );
				$res_row = mysqli_fetch_row($res);
				$id=$res_row[0];
				$query = "SELECT id FROM users WHERE username='$row[0]'";
				$res = mysqli_query($con, $query );
				$res_row = mysqli_fetch_row($res);
				$contact_id=$res_row[0];
				$query = "SELECT COUNT(*) FROM user_contact WHERE isblock='block' AND userid='$id' and contactid='$contact_id'";
				$favs = mysqli_query($con, $query );
				$favs_row = mysqli_fetch_row($favs);
				if($favs_row[0] == 0){ ?>
					<td><form action="update.php" method="post">
						<input type="hidden" name="block" value="<?php echo $contact_id;?>">
						<input type="submit" value="Block">
					</form><br></td>
				<?php } 
				else { ?>
					<td><form action="update.php" method="post">
						<input type="hidden" name="unblock" value="<?php echo $contact_id;?>">
						<input type="submit" value="Unblock">
					</form><br></td>
			<?php } }?>
				<td><form action="update.php" method="post">
						<input type="hidden" name="delete_contact" value="<?php echo $row[0]; ?>">
						<input type="submit" value="Delete">
					</form></td>
			</tr>
		<?php } ?>
		</table>
		<?php } ?>

<?php
	if(isset($_POST['block'])) {
		$query = "SELECT id FROM users WHERE username='$_susername'";
				$res = mysqli_query($con, $query );
				$res_row = mysqli_fetch_row($res);
				$id=$res_row[0];
		$contactid = $_POST['block'];
		$query = "UPDATE user_contact SET isblock='block' WHERE userid='$id' AND contactid='$contactid'";
		$favs = mysqli_query($con, $query );
		?>
		<meta http-equiv="refresh" content="0;url=update.php">
		<?php
	}
	if(isset($_POST['unblock'])) {
		$query = "SELECT id FROM users WHERE username='$_susername'";
				$res = mysqli_query($con, $query );
				$res_row = mysqli_fetch_row($res);
				$id=$res_row[0];
		$contactid = $_POST['unblock'];
		$query = "UPDATE user_contact SET isblock='unblock' WHERE userid='$id' AND contactid='$contactid'";
		$favs = mysqli_query($con, $query );
		?>
		<meta http-equiv="refresh" content="0;url=update.php">
		<?php
	}?>
	<?php
    	echo "<p> Click <a href='add_contact.php'>here</a> to add a contact by username.</p>";
    ?>

</div>
<?php
	$query = "SELECT id FROM users WHERE username='$_susername'";
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($result);
		$userid = $row[0];
	$query = "SELECT username, email FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE user_contact.userid='$userid' AND user_contact.priority='friend'";
		$result = mysqli_query($con, $query);?>
			<div class="my_contacts">
				<?php echo "<h3>Friends</h3>";?>
				<table style="width:30%">
			<tr>
				<td>Username</td>
				<td>Email</td>
				<td>Message</td>
			</tr>
		<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		?>
			<?php
				$conv_query = "SELECT conversationID FROM conversations WHERE (userA='$_susername' AND userB='$row[0]') OR (userB='$_susername' AND userA='$row[0]')";
				$conv_result = mysqli_query($con, $conv_query);
				$conv_row = mysqli_fetch_row($conv_result);
				$convid = $conv_row[0];
			?>

			<tr>
				<td><?php echo $row[0] ?></td>
				<td><?php echo $row[1] ?></td>
				<td><a href="message.php?id=<?php echo $convid;?>" target="_blank">Message</a></td>
			<td><form action="update.php" method="post">
						<input type="hidden" name="delete_contact" value="<?php echo $row[0]; ?>">
						<input type="submit" value="Delete">
					</form></td>
			</tr>
		<?php }?>
		</table>
		</div>
			
<div class="my_uploads">
	<h3>My Media</h3>




	<table width="50%" cellpadding="0" cellspacing="0" style="text-align: center">
		<tr>
			<th>Title</th>
			<th>Description</th>
			<th>Category:
        <form action="update.php" method="post">
        <select name="type" type="text">
          <option value="all"  selected="selected">All</option>
          <option value="images">Images</option>
          <option value="videos">Videos</option>
          <option value="audio">Audio</option>
        </select>
        <input type="submit" value="Sort" name="change"/>
      </form>
      </th>
			<th></th>
		</tr>

    <?php
      $catquery="";

      if(isset($_POST['change'])){
        $type = $_POST['type'];
        if($type == 'all'){
          $catquery = "AND media.category IN ('image', 'video', 'audio')";
        }
        else if($type == 'images'){
          $catquery = "AND media.category = 'image'";
        }
        else if($type == 'videos'){
          $catquery = "AND media.category = 'video'";
        }
        else if($type == 'audio'){
          $catquery = "AND media.category = 'audio'";
        }
      }
  		$query = "SELECT * FROM media INNER JOIN upload ON media.mediaid = upload.mediaid INNER JOIN users ON upload.username = users.username WHERE users.username='$_susername' $catquery";


      $result = mysqli_query($con, $query );
  		if (!$result)
  		{
  		   die ("Could not query the media table in the database: <br />". mysqli_error($con));
  		}
  	?>


		<?php
			while ($result_row = mysqli_fetch_row($result))
			{
		?>
        <tr valign="top">
			<td>
					<h4><a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[4];?></a></h4>
			</td>
			<td>
					<?php
						echo $result_row[5];
					?>
			</td>
			<td>
					<?php
						echo $result_row[6];
					?>
			</td>
         </tr>
		<?php
			}
		?>
	</table>

</div>
<div class="my_uploads">
	<h3>Groups</h3>
	<?php
	if(isset($_POST['join'])) {
		$groupname = $_POST['join'];
		$query = "INSERT INTO group_user(groupname,username) VALUES('$groupname', '$_susername')";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['leave'])) {
		$groupname = $_POST['leave'];
		$query = "DELETE FROM group_user WHERE groupname='$groupname' AND username='$_susername'";
		$favs = mysqli_query($con, $query );
	}
	?>
	<table width="50%" cellpadding="0" cellspacing="0" style="text-align: center">
		<tr>
			<th>Group Name</th>
		</tr>
		<tr>
			<td>
			<?php
			$query = "SELECT groupname FROM groups";
				$res = mysqli_query($con, $query );
				$res_row = mysqli_fetch_row($res);
			?>
			<?php
			$query = "SELECT username FROM group_user WHERE groupname='$res_row[0]' AND username='$_susername'";
				$res = mysqli_query($con, $query );
				$row = mysqli_fetch_row($res);
				if($row[0]==$_susername){ 
				$href="groups.php?id=$res_row[0]";
				}
				else{
					$href="update.php";
				}?>
			<a href="<?php echo $href;?>" target="_blank"><?php echo $res_row[0];?></a>
			</td>
			<?php
			echo $res_row[0];
			$query = "SELECT username FROM group_user WHERE groupname='$res_row[0]' AND username='$_susername'";
				$favs = mysqli_query($con, $query );
				$favs_row = mysqli_fetch_row($favs);
				$username=$_susername;
				if($favs_row[0]==$username){ ?>
					<td><form action="update.php" method="post">
						<input type="hidden" name="leave" value="<?php echo $res_row[0];?>">
						<input type="submit" value="leave">
					</form><br></td>
				<?php } 
				else { ?>
					<td><form action="update.php" method="post">
						<input type="hidden" name="join" value="<?php echo $res_row[0];?>">
						<input type="submit" value="join">
					</form><br></td>
			<?php }   ?>
		</tr>
		<?php
    	echo "<p> Click <a href='add_group.php'>here</a> to create a new group.</p>";
    ?>
	</table>
</div>

<form action="browse.php"><input name="home" type="submit" value="Cancel"></form>
