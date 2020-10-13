<?php
session_start();
include_once "function.php";



$username=$_SESSION['username'];



if(!file_exists('uploads/'))

{
	mkdir('uploads/');
	chmod('uploads/', 0755);
}
$dirfile = 'uploads/'.$username.'/';
if(!file_exists($dirfile))

{
	mkdir($dirfile);
	chmod($dirfile, 0755);
}
if($_POST['title']==NULL)
{
	$result= 8;
}
else
{
	if($_FILES["file"]["error"] > 0 )
	{ $result=$_FILES["file"]["error"];} //error from 1-4
	else
	{
	  $upfile = $dirfile.urlencode($_FILES["file"]["name"]);

	  if(file_exists($upfile))
	  {
	  		$result="5"; //The file has been uploaded.
	  }
	  else{
			if(is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				if(!move_uploaded_file($_FILES["file"]["tmp_name"],$upfile))
				{
					$result="6"; //Failed to move file from temporary directory
				}
				else /*Successfully upload file*/
				{
					chmod($upfile, 0644);
					//insert into media table
					date_default_timezone_set("America/New_York");
					$time=date("Y-m-d h:i:sa");
					$filename=urlencode($_FILES["file"]["name"]);
					$filepath=$dirfile;
					$size=filesize($filepath.$filename);
					$ext = end(explode('.', $filename));
					$insert = "insert into media(
							  mediaid, filename,filepath,type,title,description,category,user,share,time,size,allow_disc,allow_rating)
							  values(NULL,
							  	'". urlencode($_FILES["file"]["name"])."',
							  	'$dirfile',
							  	'".$_FILES["file"]["type"]."',
							  	'".$_POST["title"]."',
							  	'".$_POST["description"]."',
							  	'".$_POST["category"]."',
							  	'$username','".$_POST["share"]."','$time','$size','".$_POST["discussion"]."','".$_POST["rating"]."'
							  )";
					$queryresult = mysqli_query($con, $insert)
						  or die("Insert into Media error in media_upload_process.php " .mysqli_error($con));
					$result="0";
					

					$mediaid = mysqli_insert_id($con);
					
					$insertUpload="insert into upload(uploadid,username,mediaid) values(NULL,'$username','$mediaid')";
					$queryresult = mysqli_query($con, $insertUpload)
						  or die("Insert into view error in media_upload_process.php " .mysqli_error($con));

					$kwords = $_POST["keywords"];
					$karray = array_map('trim', explode(',', $kwords));
					$count=1;
					foreach($karray as $val){
						$keyQuery = mysqli_query($con, "insert into keywords(mediaid, keyword,count) value('$mediaid', '$val','$count')");
					}
					$insert="INSERT INTO keywords(mediaid,keyword,count)values('$mediaid','$time','$count')";
					$queryresult = mysqli_query($con, $insert)
						  or die("Insert into Media error in media_upload_process.php " .mysqli_error($con));
					$insert="INSERT INTO keywords(mediaid,keyword,count)values('$mediaid','$size','$count')";
					$queryresult = mysqli_query($con, $insert)
						  or die("Insert into Media error in media_upload_process.php " .mysqli_error($con));
					$insert="INSERT INTO keywords(mediaid,keyword,count)values('$mediaid','$ext','$count')";
					$queryresult = mysqli_query($con, $insert)
						  or die("Insert into Media error in media_upload_process.php " .mysqli_error($con));

				}
			}
			else
			{
					$result="7"; //upload file failed
			}
		}
	}
}

?>

<meta http-equiv="refresh" content="0;url=browse.php?result=<?php echo $result;?>">
