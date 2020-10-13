<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media Upload</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
</head>

<body>
<div class="topnav">
<a class="active logo" href="browse.php"><img src="img/metube.png" width="85" height="40" alt="logo"></a>
</div>
<form method="post" action="media_upload_process.php" enctype="multipart/form-data" >

  <p style="margin:0; padding:0">
  <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
   Add a Media: <label style="color:#663399"><em> (Each file limit 10M)</em></label><br/>
   <input  name="file" type="file" size="50" /></p><br>
   Title: <input name="title" type="text" maxlength="15"/><br>
   Description: <input name="description" type="text" /><br>
   Category: <select name="category">
   <option value="image">Image</option>
   <option value="video">Video</option>
   <option value="audio">Audio</option>
	</select><br>
	Sharing Mode: <select name="share">
   <option value="public">Public</option>
   <option value="me">Only me</option>
   <option value="friends">Only Friends</option>
   <option value="family">Only Family</option>
   <option value="favorites">Only Favorites</option>
	</select><br>
	Allow Discussion: <select name="discussion">
   <option value="yes">Yes</option>
   <option value="no">No</option>
	</select><br>
	Allow Rating: <select name="rating">
   <option value="yes">Yes</option>
   <option value="no">No</option>
	</select><br>
	Keywords: <br><textarea rows="5" cols="50" placeholder="Enter keywords separated by commas (,)." name="keywords"></textarea><br>
	<input value="Upload" name="submit" type="submit" />
  </p>


 </form>

</body>
</html>
