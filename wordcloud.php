<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Word Cloud</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" type="text/css" href="default.css" />
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

<div class="topnav">
<a class="active logo" href="browse.php"><img src="img/metube.png" width="85" height="40" alt="logo"></a>
	<table align="right">
	<form action="browseFilter.php" method="post">
		<td><input type="text" placeholder="Search.." name="searchwords"></td>
		<td><input type="submit" value="Search" name="search"></td>
</form>
</table>
  <?php
	if (! empty($_SESSION['logged_in']))
	{
		$username = $_SESSION['username'];
  		echo "<a href='logout.php'>Logout</a>
  		<a href='update.php'>Profile</a>";
	}
	else {
		echo"<a href='index.php'>Login</a>";
		echo"<a href='registration.php'>Register</a>";
	}

  ?>
</div>

<h1>Word Cloud</h1>
<br/><br/>

<div class="content">
  <div class="cloud">
    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="js/d3.layout.cloud.js"></script>
    <svg></svg>
    <script>
      //https://github.com/jasondavies/d3-cloud
      function getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
      }

      var values = [];
      <?php
      $query = "SELECT distinct keyword,count from keyword";
		$result = mysqli_query($con, $query);
      $total = 0;
      while ($keyword = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $word = $keyword[0];
        $count = $keyword[1];
        echo ("values.push({text : '$word', size: $count});\n");
        $total += $count;
      }
      ?>
      var width = document.querySelector('.content').offsetWidth;
      var layout = d3.layout.cloud()
        .size([800, 800])
        .words(values.map(function(d) {
          return {
            text: d.text,
            size: d.size * 325 / <?php echo $total;?>,
            test: "haha"
          };
        }))
        .padding(5)
        .font("Impact")
        .fontSize(function(d) {
          return d.size;
        })
        .on("end", draw)
        .spiral("archimedean");

      layout.start();

      function draw(words) {
        d3.select("svg")
          .attr("width", layout.size()[0])
          .attr("height", layout.size()[1])
          .append("g")
          .attr("transform", "translate(" + layout.size()[0] / 2 + "," + layout.size()[1] / 2 + ")")
          .selectAll("text")
          .data(words)
          .enter()
          .append("a")
          .attr("href",function (d){
              return "search.php?search=" + d.text;
          })
          .append("text")
          .style("font-size", function(d) {
            return d.size + "px";
          })
          .style("font-family", "Impact")
          .style("fill", function(d) {
            return "rgb(" + getRandomInt(50, 200) + "," + getRandomInt(50, 200) + "," + getRandomInt(50, 200) + ")";
          })
          .attr("text-anchor", "middle")
          .attr("transform", function(d) {
            return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
          })
          .text(function(d) {
            return d.text;
          });
      }
    </script>
  </div>

</body>
</html>
