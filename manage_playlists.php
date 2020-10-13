<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Playlists</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="default.css" />
</head>

<?php
	$username = $_SESSION['username'];
	if(isset($_POST['playlistname'])) {
		$playlistname = $_POST['playlistname'];

		$query = "DELETE FROM user_playlists WHERE playlist='$playlistname' AND username='$username'";
		$result = mysqli_query($con, $query );

		$query = "DELETE FROM playlists WHERE playlist='$playlistname' AND username='$username'";
		$result = mysqli_query($con, $query );
	}
	if(isset($_POST['mediaid'])) {
		$mediaid = $_POST['mediaid'];
		$query = "DELETE FROM playlists WHERE mediaid='$mediaid' AND username='$username'";
		$result = mysqli_query($con, $query );
		if(!$result){
			echo mysqli_error($con);
		}
	}

?>

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
	$user = $_GET['user'];

	$query = "SELECT playlist FROM user_playlists WHERE username='$user'";
	$result = mysqli_query($con, $query);
	$count = mysqli_num_rows($result);

	if ($count < 1){
		echo "You have no playlists.";
	}

	while($row = mysqli_fetch_row($result)){
		$playlistname = $row[0]; ?>
		<table>
			<tr>
				<th>Playlist: <?php echo $row[0]; ?></th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<th>
					<?php $path = "manage_playlists.php?user=".$_GET['user']; ?>
					<form action=<?php echo $path ?> method="post">
						<input type="hidden" name="playlistname" value="<?php echo $playlistname; ?>">
						<input type="submit" value="Delete Playlist">
					</form><br>
				</th>
			</tr>
			<?php
				$query = "SELECT media.mediaid, title FROM media INNER JOIN playlists ON media.mediaid=playlists.mediaid WHERE playlists.username='$username' AND playlists.playlist='$playlistname'";
				$titles = mysqli_query($con, $query);
				if(!$titles){
					echo mysqli_error($con);
				}
				while($title = mysqli_fetch_row($titles)) {
					$mediaid = $title[0]; ?>
					<tr>
						<td><?php echo $title[1]; ?></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td>
							<?php $path = "manage_playlists.php?user=".$_GET['user']; ?>
							<form action=<?php echo $path ?> method="post">
								<input type="hidden" name="mediaid" value="<?php echo $mediaid; ?>">
								<input type="submit" value="Delete Media">
							</form><br>
						</td>
					</tr>
				<?php } ?>
		</table>
	<?php } ?>

</body>
</html>
