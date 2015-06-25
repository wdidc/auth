<?php
  require("config.php");
  header("Location: https://github.com/login/oauth/authorize?scope=user,repo&client_id=". CLIENT_ID);
?>
