<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />

<script type="text/javascript" src="js/jquery-latest.pack.js"></script>
<script type="text/javascript">

function saveDownload(id)
{
	$.post("media_download_process.php",
	{
       id: id,
	},
	function(message)
    { 

    }
 	);
}
</script>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
  <a class="navbar-brand" href="browse.php"><img src="img/metube.png" width="80" height="40" alt="logo"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="wordcloud.php">Word Cloud<span class="sr-only">(current)</span></a>
      </li>
      <div class="dropdown">
  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Subscriptions
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	<?php 
	$username=$_SESSION['username'];
	$query = "SELECT createdby from subscribe where username='$username'";
		$result = mysqli_query($con, $query);
		while ($row = mysqli_fetch_row($result)){ ?>
		<a class="dropdown-item" href="<?php echo "subscriptions.php?id=".$row[0];?>"><?php echo $row[0];?></a>
		<?php }?>
  </div>
</div>
	 <form class="form-inline" action="browseFilter.php" method="post">
    <input class="form-control mr-sm-2" type="search" name="searchwords" placeholder="search" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
  </form>
  </div>
  
<?php
	if (! empty($_SESSION['logged_in']))
	{
  		echo "<a href='logout.php'>Logout</a>
  		<a href='update.php'>Profile</a>";
	}
	else {
		echo"<a href='index.php'>Login</a>"; 
		echo"<a href='registration.php'>Register</a>";
	}

	if(isset($_POST['search'])){

	}

  ?>
  </div>
</nav>

<h1>Browse</h1>
<?php
	if (! empty($_SESSION['logged_in']))
	{
		$username = $_SESSION['username'];
		echo "<p>Welcome ".$_SESSION['username'];
		echo "<br><a href='media_upload.php'>Upload File</a>";
?>
		<div id='upload_result'>
		<?php if(isset($_REQUEST['result']) && $_REQUEST['result']!=0)
		{
			echo upload_error($_REQUEST['result']);
		}
		if(isset($_POST['delchannel'])) {
			$channelname = $_POST['delchannel'];
			$query = "DELETE FROM channels WHERE user='$username' AND channel='$channelname'";
			$result = mysqli_query($con, $query );
			if(!$result){
				echo mysqli_error($con);
			}
			
		}
		if (empty($_SESSION['logged_in']))
		{
			$username="NULL";
		}
		?>
		</div>
<div class="container">
	<div class="row">
		<div class="col-sm">
		<h3>Add new playlist</h3>
			<form action="browse.php" method="post">
				<input name="new_playlist" type="text" placeholder="new playlist..." maxlength="20"> 
				<input type="submit" value="submit">
			</form>
			<h4><a href="manage_playlists.php?user=<?php echo $username;?>" target="_blank">Manage Playlists</a></h4>
		</div>
	<div class="col-sm">
	<h3>Add new channel</h3>
				<form action="browse.php" method="post">
					 <?php 
						$query = "select username from users where username != '$username' and username not in (select channel from channels where user='$username')";
						$channels_result = mysqli_query($con, $query); 
						if(!$channels_result){
							echo mysqli_error($con);
						}

						?>
						<select name="new_channel">
							<?php while ($channel_row = mysqli_fetch_row($channels_result)){ ?>
							<option value="<?php echo $channel_row[0]; ?>"> <?php echo $channel_row[0]; ?> </option><br>;
							<?php } ?>
						</select>
					<input type="submit" value="submit">
				</form>
	</div>
	<div class="col-sm">
	<h3>My Channels</h3>
				<?php 
				$q = "SELECT channel FROM channels WHERE user='$username'";
				$r = mysqli_query($con, $q);
				?><table><?php
				while ($channel_row = mysqli_fetch_row($r)){ ?>
					<tr>
						<td><?php echo $channel_row[0]; ?></td>
						<td><form action="browse.php" method="post">
						<input type="hidden" name="delchannel" value="<?php echo $channel_row[0]; ?>">
						<input type="submit" value="Delete">
					</form></td>
					</tr>
				<?php }

				?>
				</table>				
		</div>
	</div>
</div>
		
		<?php }
		else {
			echo "<p>Please login to upload media.</p>";
		}
		?>
<?php
	if (isset($_POST['channel'])) {
		$channel = $_POST['channel'];
		if ($channel == "all"){
			$channel_query = "SELECT mediaid FROM media";
		}
		else if ($channel == "mine"){
			$channel_query = "SELECT mediaid FROM media WHERE user='$username'";
		}
		else {
			$channel_query = "SELECT mediaid FROM media WHERE user='$channel'";
		}
	}
	else {
		$channel_query = "SELECT mediaid FROM media";
	}
	if(isset($_POST['type'])) {
		$type = $_POST['type'];
		if($type == 'all'){
			$type_query = "SELECT mediaid FROM media";
		}
		else if($type == 'images') {
			$type_query = "SELECT mediaid FROM media WHERE category='image'";
		}
		else if($type == 'videos'){
			$type_query = "SELECT mediaid FROM media WHERE category='video'";
		}
		else if($type == 'audio'){
			$type_query = "SELECT mediaid FROM media WHERE category='audio'";
		}
		else{
			$type_query = "SELECT mediaid FROM media";
		}
	}
	else {
		$type_query = "SELECT mediaid FROM media";
	}

	if(isset($_POST['playlist'])){
		$playlist = $_POST['playlist'];
		if($playlist == 'all'){
			$playlist_query = "SELECT mediaid from media";
		}
		else {
			$playlist_query = "SELECT media.mediaid FROM media INNER JOIN playlists ON media.mediaid=playlists.mediaid WHERE playlists.playlist='$playlist' AND username='$username'";
		}
	}
	else{
		$playlist_query = "SELECT mediaid from media";
	}
	$bigq = "SELECT media.mediaid FROM media WHERE media.mediaid in ($channel_query) AND media.mediaid in ($type_query) AND media.mediaid in ($playlist_query)";
	$allq = "SELECT * FROM media WHERE media.mediaid IN ($bigq)";
	if(isset($_POST['order']))
	{
		$order = $_POST['order'];
		if($order=='recent')
		{
			$allq = "SELECT * FROM media WHERE media.mediaid IN ($bigq) ORDER BY time DESC";
		}
		if($order=='name')
		{
			$allq = "SELECT * FROM media WHERE media.mediaid IN ($bigq) ORDER BY filename";
		}
		if($order=='size')
		{
			$allq = "SELECT * FROM media WHERE media.mediaid IN ($bigq) ORDER BY size";
		}
		if($order=='viewed')
		{
			$allq = "SELECT * FROM media WHERE media.mediaid IN ($bigq) ORDER BY views DESC";
		}
	}
	$result = mysqli_query($con, $allq);
	if(!$result){
		echo mysqli_error($con);
	}
?>
   
<?php
	
	if(isset($_POST['new_playlist'])){
		$new_playlist = $_POST['new_playlist'];
		$query = "SELECT playlist FROM user_playlists WHERE username='$username' and playlist='$new_playlist'";
		$playlist_result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($playlist_result);
		if(!$row) {
			$query = "INSERT into user_playlists(playlist, username) VALUES('$new_playlist', '$username')";
			$new_playlist_result = mysqli_query($con, $query);
		}

		if($row) {
			echo 'You already have a playlist with that name.';
		}
	}
	
	if(isset($_POST['new_channel'])){
		$new_channel = $_POST['new_channel'];
		$query = "INSERT into channels(user, channel) VALUES('$username','$new_channel')";
		$channel_result = mysqli_query($con, $query);
		if(!$channel_result){
			echo mysqli_error($con);
		}
		?>
		<meta http-equiv="refresh" content="0;url=browse.php">
		<?php
	}

?>
<div class="row">
<div class="col-8 col-sm-3">
    <h3>Filters</h3>
    <table>
    <tr>
    	<th><h4>Category</h4></th>
    	<?php if (! empty($_SESSION['logged_in'])) { ?>
			<th><h4>Playlist</h4></th>
			<th><h4>Channel</h4></th> <?php } ?>
			<th><h4>Order By</h4></th>
    	<th></th>
    </tr>
    <tr>
	    <td>
	    	<form action="browse.php" method="post">
		  		<select name="type" type="text">
		    		<option value="all" selected="selected">All</option>
		    		<option value="images">Images</option>
		    		<option value="videos">Videos</option>
		    		<option value="audio">Audio</option>
		  		</select>
		</td>
		
	</div>
</div>
  	<?php 
	if (! empty($_SESSION['logged_in']))
	{ ?>
		<td>
		  	<form action="browse.php" method="post">
		  <?php 
			$query = "SELECT * FROM user_playlists where username='$username'";
			$playlist_result = mysqli_query($con, $query); ?>
				<select name="playlist">
					<option value="all" selected="selected">All</option>
					<option value="favorites">Favorites</option>
				<?php while ($playlist_row = mysqli_fetch_row($playlist_result)){ ?>
					<option value="<?php echo $playlist_row[0]; ?>"> <?php echo $playlist_row[0]; ?> </option><br>;
			<?php } ?>
				</select>
		</td>
		<td>
		  	<form action="browse.php" method="post">
		  <?php 
			$query = "SELECT channel FROM channels where user='$username'";
			$channel_result = mysqli_query($con, $query); ?>
				<select name="channel">
					<option value="all" selected="selected">Any</option>
					<option value="mine">My Channel</option>
				<?php while ($channel_row = mysqli_fetch_row($channel_result)){ ?>
					<option value="<?php echo $channel_row[0]; ?>"> <?php echo $channel_row[0]; ?> </option><br>;
			<?php } ?>
				</select>
		</td>
		<td>
	    	<form action="browse.php" method="post">
		  		<select name="order" type="text">
		    		<option value="recent" selected="selected">Most Recent</option>
		    		<option value="viewed">Most Viewed</option>
		    		<option value="name">Name</option>
		    		<option value="size">Size</option>
		  		</select>
		</td>

	<?php } ?>
		<td><input type="submit" value="submit"></td>
		</form>
	</tr>
	</table>

    <div class="all_media">
		<?php
			//print $result;
			while ($result_row = mysqli_fetch_row($result))
			{
				if (empty($_SESSION['logged_in']))
		{
			$username="NULL";
		}
				$query="SELECT id FROM users INNER JOIN media ON users.username = media.user";
				$res = mysqli_query($con, $query);
				$res_row = mysqli_fetch_row($res);
				$id=$res_row[0];
				$query="SELECT id FROM users WHERE username='$username'";
				$res = mysqli_query($con, $query);
				$res_row = mysqli_fetch_row($res);
				$contactid=$res_row[0];
				$query="SELECT isblock FROM user_contact WHERE userid='$id' AND contactid='$contactid'";
				$res = mysqli_query($con, $query);
				$res_row = mysqli_fetch_row($res);
				$isblock=$res_row[0];
				if($isblock=='block')
				{
					continue;
				}
				$query = "SELECT user FROM media where mediaid='$result_row[0]'";
				$user_share_result = mysqli_query($con, $query);
				$user_share_row = mysqli_fetch_row($user_share_result);
				if(($result_row[9]=="me") && ($user_share_row[0]!=$username))
				{
					continue;
				}
				$query = "SELECT priority FROM users INNER JOIN user_contact ON users.id = user_contact.contactid WHERE users.username='$username'";
				$user_share_result = mysqli_query($con, $query);
				$user_share_row1 = mysqli_fetch_row($user_share_result);
				if(($result_row[9]=="friends") && (($user_share_row1[0]!="friend")))
				{
					if($user_share_row[0]!=$username)
					continue;
				}
				if(($result_row[9]=="family") && (($user_share_row1[0]!="family")))
				{
					if($user_share_row[0]!=$username)
					continue;
				}
				if(($result_row[9]=="favorites") && (($user_share_row1[0]!="favorite")))
				{
					if($user_share_row[0]!=$username)
					continue;
				}
		?>

		<div class="media_box">
			<?php
				$mediaid = $result_row[0];
				$filename=$result_row[1];
				$filepath=$result_row[2];
				$type=$result_row[3];
				if(substr($type,0,5)=="image") //view image
				{
					echo "<img src='".$filepath.$filename."' height=200 width=300/>";
				}
				else //view movie
				{
			?>
		    		<div>
					<video width="320" height="240" controls>
			<source src="<?php echo $result_row[2].$result_row[1];  ?>" type="video/mp4">
		</video>
					</div>
				<?php } ?>
			<div><h4><a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[4];?></a></h4></div> 
			
			<div><form action="browse.php" method="post">
						Rating:
						<?php 
					$query = "SELECT AVG(rating) FROM rating_data where mediaid='$result_row[0]'";
				$rate_result = mysqli_query($con, $query);
				$rate_row = mysqli_fetch_row($rate_result);
				if($rate_row[0]==NULL)
				{
					echo "0";
				}
				else{
				echo $rate_row[0];
				}
				?>
					</form><br></div>
				<div><form action="browse.php" method="post">
						Views:
						<?php 
					$query = "SELECT views FROM media where mediaid='$result_row[0]'";
				$rate_result = mysqli_query($con, $query);
				$rate_row = mysqli_fetch_row($rate_result);
				if($rate_row[0]==NULL)
				{
					echo "0";
				}
				else{
				echo $rate_row[0];
				}
				?>
					</form><br></div>
				
			
			
		</div>
			<?php }  ?>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</body>
</html>