<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
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

<?php
if(isset($_GET['id'])) {
	$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
	$result = mysqli_query($con, $query );
	$result_row = mysqli_fetch_row($result);
	
	updateMediaTime($_GET['id']);
	
	$filename=$result_row[1];
	$filepath=$result_row[2];
	$type=$result_row[3];

if(isset($_POST['submit'])){
	$username = $_SESSION['username'];
	$mediaid = $_GET['id'];
	$comment = $_POST['comment'];
	$id = rand(0000,9999);
	$query = "INSERT INTO comments(username, mediaid, comment,id) VALUES ('$username', '$mediaid', '$comment','$id')";
	$result = mysqli_query($con, $query);

	if($result){
		$smsg = "Comment Created Successfully";
		$mediapath='Location: media.php?id='.$_GET["id"];
		header($mediapath);
	}
	else {
		$fmsg = "Comment Failed".mysqli_error($con);
	}
}
if(isset($_POST['delete_comment'])){
	$commentid = $_POST['delete_comment'];
	$res = mysqli_query($con, "DELETE FROM comments WHERE id = '$commentid'");
}


?>

<div class="meta_media">
	<h3><?php echo $result_row[4]?></h3>
	<div class="media_player">
		<?php
			if(substr($type,0,5)=="image") //view image
			{
				echo "<img src='".$filepath.$filename."' width=400px height=325px/>";
			}
			else //view movie
			{	
		?>	  
		<video width="320" height="240" controls>
			<source src="<?php echo $result_row[2].$result_row[1];  ?>" type="video/mp4">
		</video>
		<?php } ?>
		<?php 
					if(isset($_POST['submitrate'])){
						$username = $_SESSION['username'];
					$rates=$_POST['rate'];
					$mediaid=$_POST['mediarate'];
					$query = "INSERT INTO rating_data(rating,mediaid,username) VALUES('$rates','$mediaid','$username')";
					$rate_result = mysqli_query($con, $query);
					if(!$rate_result){
			echo mysqli_error($con);
		}
					?>
					<!--<meta http-equiv="refresh" content="0;url=media.php?id=".<?php echo $GET_['id']; ?>">-->
					<?php
					}
					$mediapath="media.php?id=".$_GET["id"];
				?>
				<?php
				$id=$_GET["id"];
				$query = "select allow_rating from media where mediaid='$id'";
					$rating_result = mysqli_query($con, $query);
					$rating_row = mysqli_fetch_row($rating_result);
					if($rating_row[0]=='yes')
					{?>
				<div><form action=<?php echo $mediapath ?> method="post">
						Rate:
						<select name="rate" type="text">
		    		<option value="1" selected="selected">1</option>
		    		<option value="2">2</option>
		    		<option value="3">3</option>
		    		<option value="4">4</option>
					<option value="5">5</option>
		  		</select>
				
				<td>
				<?php $mediaid = $_GET['id'];
				 ?>
				<input type="hidden" name="mediarate" value="<?php echo $mediaid; ?>">
				<input type="submit" value="submit" name="submitrate"></td>
					</form><br></div>
					
				<div><form action=<?php echo $mediapath ?> method="post">
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
				
	</div>
					<?php }?>
	<?php
	$username = $_SESSION['username'];
				$mediaid=$_GET["id"];
	if(isset($_POST['favorite'])) {
		$mediaid = $_POST['favorite'];
		$query = "INSERT INTO playlists(playlist,username, mediaid) VALUES('favorites', '$username', '$mediaid')";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['unfavorite'])) {
		$mediaid = $_POST['unfavorite'];
		$query = "DELETE FROM playlists WHERE playlist='favorites' AND username='$username' AND mediaid='$mediaid'";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['subscribe'])) {
		$user = $_POST['subscribe'];
		$query = "INSERT INTO subscribe(subscribed,username, createdby) VALUES('yes', '$username', '$user')";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['unsubscribe'])) {
		$user = $_POST['unsubscribe'];
		$query = "DELETE FROM subscribe WHERE subscribed='yes' AND username='$username' AND createdby='$user'";
		$favs = mysqli_query($con, $query );
	}
	if(isset($_POST['add_to_playlist'])) {
		$mediaid = $_POST['mediaAddToPlaylist'];
		$addToPlaylist = $_POST['add_to_playlist'];
		$query = "SELECT * FROM playlists WHERE username='$username' and playlist='$addToPlaylist' and mediaid='$mediaid'";
		$add_to_playlist_result = mysqli_query($con, $query);
		$row = mysqli_fetch_row($add_to_playlist_result);
		if(!$row) {
			$query = "INSERT INTO playlists(playlist,username, mediaid) VALUES('$addToPlaylist', '$username', '$mediaid')";
			$add_to_playlist_result = mysqli_query($con, $query);
		}

		if($row){
			echo 'This media is already part of that playlist';
		}
	}
	?>
	<?php
			if (! empty($_SESSION['logged_in']))
			{ 
				
				$mediapath="media.php?id=".$_GET["id"];
				$query = "SELECT COUNT(*) FROM playlists WHERE playlist='favorites' AND username='$username' and mediaid='$mediaid'";
				$favs = mysqli_query($con, $query );
				$favs_row = mysqli_fetch_row($favs);
				if($favs_row[0] == 0){ ?>
					<div><form action=<?php echo $mediapath ?> method="post">
						<input type="hidden" name="favorite" value="<?php echo $mediaid; ?>">
						<input type="submit" value="Favorite">
					</form><br></div>
				<?php } 
				else { ?>
					<div><form action=<?php echo $mediapath ?> method="post">
						<input type="hidden" name="unfavorite" value="<?php echo $mediaid; ?>">
						<input type="submit" value="Unfavorite">
					</form><br></div>
				<?php } ?>
				<?php
				$query = "SELECT user FROM media WHERE mediaid='$mediaid'";
				$favs = mysqli_query($con, $query );
				$favs_row = mysqli_fetch_row($favs);
				$user=$favs_row[0];
				$query = "SELECT COUNT(*) FROM subscribe WHERE subscribed='yes' AND username='$username' and createdby='$user'";
				$favs = mysqli_query($con, $query );
				$favs_row = mysqli_fetch_row($favs);
				if($favs_row[0] == 0){ ?>
					<div><form action=<?php echo $mediapath ?> method="post">
						<input type="hidden" name="subscribe" value="<?php echo $user; ?>">
						<input type="submit" value="Subscribe">
					</form><br></div>
				<?php } 
				else { ?>
					<div><form action=<?php echo $mediapath ?> method="post">
						<input type="hidden" name="unsubscribe" value="<?php echo $user; ?>">
						<input type="submit" value="Unsubscribe">
					</form><br></div>
				<?php } ?>
				
				<div><h4>Add to playlist:</h4></div>
				<?php 
					$query = "SELECT * FROM user_playlists where username='$username'";
					$addToPlaylist_result = mysqli_query($con, $query); ?>
					<div><form action=<?php echo $mediapath ?> method="post">
						<input type="hidden" name="mediaAddToPlaylist" value="<?php echo $mediaid; ?>">
						<select name="add_to_playlist">
							<?php while ($addToPlaylist_row = mysqli_fetch_row($addToPlaylist_result)){ ?>
								<option value="<?php echo $addToPlaylist_row[0]; ?>"> <?php echo $addToPlaylist_row[0]; ?> </option><br>;
							<?php } ?>
						</select>
						<input type="submit" value="submit">
					</form></div>
				<?php } ?>
	<div class="meta">
		<p>Owner: <?php echo $result_row[7]?></p>
		<p>Date Uploaded: <?php echo $result_row[10]?></p>
		<p>Category: <?php echo $result_row[6]?></p>
		<p>Description: <?php echo $result_row[5]?></p>
		<a href="<?php echo "media_download_process.php?id=".$result_row[0];?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[0];?>);">Download</a>
			<?php $query = "SELECT views from media where mediaid='$result_row[0]'";
		$view_result = mysqli_query($con, $query);
		$view = mysqli_fetch_row($view_result); ?>
			<a href="<?php echo $result_row[2].$result_row[1];?>" target="_blank" onclick="<?php $view[0]=$view[0]+1; ?>">View</a>
			<?php $query = "update media set views='$view[0]' where mediaid='$result_row[0]'";
		$view_result = mysqli_query($con, $query);?>
		
		</div>
		<?php
				$id=$_GET["id"];
				$query = "select allow_disc from media where mediaid='$id'";
					$rating_result = mysqli_query($con, $query);
					$rating_row = mysqli_fetch_row($rating_result);
					if($rating_row[0]=='yes')
					{?>
		<b><p>Comments:</p></b>
		<?php 
			$query = "SELECT * FROM comments WHERE mediaid='".$_GET['id']."'"."ORDER BY commentTime";
			$result = mysqli_query($con, $query);
		?>
		<table style="text-align: center">
			<tr>
				<th>User</th>
				<th>Comment</th>
			</tr>
			<?php
				while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			?>
			<tr>
				<td><?php echo $row[0] ?></td>
				<td><?php echo $row[2] ?></td>
			<?php if (! empty($_SESSION['logged_in']))
			{
				if($_SESSION['username'] == $row[0]){ 
					$mediapath="media.php?id=".$_GET["id"]; ?>
					<td><form action=<?php echo $mediapath ?> method="post">
						<input type="hidden" name="delete_comment" value="<?php echo $row[4]; ?>">
						<input type="submit" value="Delete">
					</form></td>
				<?php }
			} ?>
			</tr>

			<?php } ?>
			<?php 
				if (! empty($_SESSION['logged_in']))
				{
					$mediapath="media.php?id=".$_GET["id"]; ?> 
					<form method="POST" action=<?php echo $mediapath ?>>
						<tr>
	  						<td><input name="comment" type="text" placeholder="New comment (max 200 characters)..." maxlength="200"></td>
						</tr>
						<tr>
	    					<td><input name="submit" type="submit" value="Post"><br /></td>
						</tr>
					</form>
				<?php }
			?>
		</table>
					<?php }?>
	</div>
	<?php if(isset($smsg)){ ?><div role="alert"> <?php echo $smsg; ?> </div><?php } ?>
	<?php if(isset($fmsg)){ ?><div role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
</div>
              
<?php
}
else
{
?>
<meta http-equiv="refresh" content="0;url=media.php?id=".<?php echo $GET_['id']; ?>>
<?php
}
?>
<div>
<h1>Recommendations</h1>
<br/><br/>
<div class="all_media">
<div class="media_box">
<?php
	$array=array();
	$mediaid = $_GET['id'];
	$query = "SELECT keyword from keywords where mediaid='$mediaid'";
	$result = mysqli_query($con, $query );
	while($row = mysqli_fetch_row($result))
	{ 
		$query = "SELECT mediaid from keywords where mediaid!='$mediaid' AND keyword='$row[0]'";
		$res = mysqli_query($con, $query );
		while($res_row = mysqli_fetch_row($res))
		{ 
			if(!in_array($res_row[0],$array))
			{
			array_push($array, $res_row[0]);
			$query = "SELECT * from media where mediaid='$res_row[0]'";
			$resu = mysqli_query($con, $query );
			while($result_row = mysqli_fetch_row($resu))
			{
			?>
			<?php
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

		<?php }
		}
		}
}
?> 
</div>
</div>
</div>
</div>
</body>
</html>
