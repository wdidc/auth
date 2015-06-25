<?php

require("config.php");
require("ghapi.php");
echo('<!--');

function color_range($img, $max = 0){
  $img_x = imagesx($img);
  $img_y = imagesy($img);
  $colors = array();
  for($x = 0; $x < $img_x; $x++){
    for($y = 0; $y < $img_y; $y++){
      $colors[imagecolorat($img, $x, $y)] = true;
      if(!$max){
        continue;
      }
      if(count($colors) >= $max){
        break 2;
      }
    }
  }
  return $colors;
}

function prompt_for_gh(){
  die('--><p>Something went wrong! Please go to <a href="https://github.com/settings/profile">your Github profile</a> and make sure you have filled out the Name field (your real name), and have a <strong>good</strong> profile picture that is an actual photo of you.</p>');
}

function get_student_index($student, $studs){
  for($s = 0; $s < count($studs); $s++){
    $st = $studs[$s];
    if($st["name"] === $student["name"] || $st["email"] == $student["email"]){
      return $s;
    }
  }
  return false;
}


$url = 'https://github.com/login/oauth/access_token';
$data = array(
  'client_id' => CLIENT_ID,
  'client_secret' => CLIENT_SECRET,
  'code' => $_GET["code"],
  'accept' => 'json'
);
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$res = array();
parse_str($result, $res);

$user = new curl($res["access_token"]);
$acct = $user->go("user")[0];
$email = $user->go("user/emails");

$student = array(
  "name" => $acct["name"],
  "email" => $email[0][0]["email"],
  "github_user_id" => $acct["id"],
  "github_username" => $acct["login"],
  "avatar" => $acct["avatar_url"]
);
foreach($student as $key => $val){
  if(empty($val)){
    prompt_for_gh();
  }
}

$img = imagecreatefromstring(file_get_contents($acct["avatar_url"]));
$range = color_range($img, 3);
if(count($range) < 3){
  prompt_for_gh();
}
imagedestroy($img);

$studs = json_decode(file_get_contents("students.json"), true);
$student_number = get_student_index($student, $studs);
if($student_number){
  $studs[$student_number] = $student;
}else{
  $studs[] = $student;
}

file_put_contents("students.json", json_encode($studs));

echo("--><p>Success! Here's the information we got for you:</p><pre>");
print_r($student);
echo("</pre>");

?>
<script>
  window.opener.postMessage( "<?php echo $res["access_token"]; ?>", "*")
  window.close()
</script>
