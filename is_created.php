<?php

ERROR_REPORTING(E_ERROR);
$studs = json_decode(file_get_contents("http://api.wdidc.org/students.json"), true);
for($s = 0; $s < count($studs); $s++){
  $st = $studs[$s];
  if(strtolower($st["github_username"]) == strtolower($_GET["username"])){
    if(!empty($st["avatar"])){
      die("PASS");
    }
  }
}
die("FAIL");

?>
