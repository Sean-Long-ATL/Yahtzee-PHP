<?php
if(!isset($_COOKIE['score'])){
    setcookie('score', '0');
    $_COOKIE['score'] = '0';
}
if(!isset($_COOKIE['login'])){
    setcookie('login', 'temp');
    $_COOKIE['login'] = 'temp';
}
?>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hi Scores!</title>
    <link rel="stylesheet" href="./game.css">
  </head>
  <body>
    <a href="./start.html"> Back to the game</a>
<?php
$myFile = fopen("highscore.txt", "r");


// all of this opens file and stores it in associative array called highScores 
$content = fread($myFile, 1000);
$content =explode(",",$content);
foreach($content as $result){
    $b = explode(':', $result);
    $highScores[$b[0]] = $b[1];
}



//pulls info from cookie, 
$score = $_COOKIE["score"];
$user = $_COOKIE['login'];
$score = 240; //for debugging


//adds user score to array
//sorts array
//pops last value
 //reset gets first value

$highScores[$user]= $score;
arsort($highScores, SORT_NUMERIC);

fclose($myFile);
$keys = array_keys($highScores);


//prints highScores into table 
echo "<table>";
echo "<th> User</th>";
echo "<th> Hi-Score</th>";
for($i=0; $i<5; $i++ ){
    $k=$keys[$i];
    $v = $highScores[$k];
    echo "<tr>";
    echo "<td>".$k."</td>";
    echo "<td>".$v."</td>";
    echo "</tr>";
}
echo "</table>";

$myFile2 = fopen("highscore.txt", "w");
fclose($myFile2);

$myFile2 = fopen("highscore.txt", "w");

for($i=0; $i<5; $i++ ){
    
    $key=$keys[$i];
    $value = $highScores[$key];
    if($i<4){ 
        fwrite($myFile2, $key.":".$value.","); 
    }
    else{
        fwrite($myFile2, $key.":".$value); //this is so no comma is added at EOF
    }
        $stringbreak = "\n";
    fwrite($myFile2, $stringbreak);
}




?>

</body>
</html>