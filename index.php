<!DOCTYPE html>
<html>
<head> 
<title>Login</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
</head>


<div class="topnav">

  <a class="active logo" href="browse.php"><img src="img/metube.png" width="85" height="40" alt="logo"></a> 
  <?php
    if (! empty($_SESSION['logged_in']))
    {
        echo "<a href='logout.php'>Logout</a>
        <a href='update.php'>Profile</a>";
    }
    else {
		
        echo"<a class='nav-item' href='index.php' style='margin-left:1350px;'>Login</a>
        <a class='nav-item' href='registration.php' >Register</a>";
    }
  ?>
</div>


<body>
    <div class="container">
        <h1 class="text-center">Welcome to MeTube</h1>
        <h3 class="text-center">Please login or register to continue.</h3>   
        <?php
            session_start();

            include_once "function.php";

            if(isset($_POST['submit'])) {
                if($_POST['username'] == "" || $_POST['password'] == "") {
                    $login_error = "One or more fields are missing.";
                }
                else {
                    $check = user_pass_check($_POST['username'],$_POST['password']); // Call functions from function.php
                    if($check == 1) {
                        $login_error = "User ".$_POST['username']." not found.";
                    }
                    elseif($check==2) {
                        $login_error = "Incorrect password.";
                    }
                    else if($check==3){
                        $login_error = "Unregistered username.";
                    }
                    else if($check==0){
                        $_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
                        $_SESSION['logged_in']=1;
                        header('Location: browse.php');
                    }
                }
            }
        ?> 
        <div class="row ">
            <div class="col-md-4 offset-md-4">
            <form method="post" action="<?php echo "index.php"; ?>">
            <div class="form-group">
                <label for="exampleInputEmail">Username</label>
                <input type="text" name="username" class="text form-control" id="exampleInputEmail1" placeholder="Enter username">
                
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="text form-control" name="password" id="exampleInputPassword1" placeholder="Enter Password">
            </div>
            <div class="row ">
                <div class="col-md-3">
                    <button name="submit" type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="col-md-4 offset-md-1">
                    <button name="reset" type="reset" class="btn btn-primary">Reset</button> 
                </div>
            </div>
            
            <br/>
            <p>Not a user? <a href=registration.php>Click here to register</a></p>
            </form>
</div>

<?php
  if(isset($login_error))
   {
    echo "<div id='passwd_result'>".$login_error."</div>";
    }
?>

</body>