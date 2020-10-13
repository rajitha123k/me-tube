<?php
session_start();

include_once "function.php";

?>

<head> 
<title>Add Group</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
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
		echo"<a href='registeration.php'>Register</a>";
	}
  ?>
</div>
</body>

<?php
$username = $_SESSION['username'];

if(isset($_POST['submit'])) {
		if($_POST['groupname'] == "") {
			$contact_error = "Please enter a Group name.";
		}
		if($_POST['topic'] == "") {
			$contact_error = "Please enter the Topic to be discussed.";
		}
		else {
			$groupname = $_POST['groupname'];
			$topic = $_POST['topic'];
			$discussion = $_POST['discussion'];
			$check = addGroup($_SESSION['username'], $groupname, $topic, $discussion);

			if($check==1) {
				$contact_error = "".$groupname." already exists";
			}
			else if($check==2){
				$contact_error = "Some other error.";
			}	
			else if($check==0){
				echo "Group created successfully";
			}
		}
}


 
?>
	<form method="post" action="<?php echo "add_group.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Group Name:</td>
			<td width="80%"><input class="text"  type="text" name="groupname" maxlength="15"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Topic:</td>
			<td width="80%"><input class="text"  type="text" name="topic" maxlength="15"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Discussion:</td>
			<td width="80%"><input class="text"  type="text" name="discussion" maxlength="15"><br /></td>
		</tr>
        <tr>
		<td><input name="submit" type="submit" value="Submit"><br /></td>
		</tr>
	</table>
	</form>

	<a href="browse.php">Home</a>

<?php
  if(isset($contact_error))
   {  
   	echo "<div>".$contact_error."</div>";
	}

?>
