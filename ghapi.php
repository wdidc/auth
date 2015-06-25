<?php

class curl{
  public $headers = array();
  public $token;

  function __construct($token){
    $this->token = $token;
  }
  
  public function go($endpoint){
    $token = $this->token;
    $ch = curl_init();
    curl_setopt_array($ch, array(
      CURLOPT_URL => "https://api.github.com/$endpoint?access_token=$token",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => array_merge(
        $this->headers,
        array(
          "User-Agent: WDI Authenticator"
        )
      )
    ));
    $results = json_decode(curl_exec($ch), true);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return(array($results, $info));
  }
}

?>
