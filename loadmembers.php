<?php
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "mashdb";

if (isset($_POST['code'])) {
    $code = htmlspecialchars($_POST['code']);

    $dbConn = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
    $respond = array();
    if ($code) {
      $query = "SELECT * FROM members";
      $res = $dbConn->query($query);
      if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res))
        {
          $respond['id'][] = $row['id'];
          $respond['name'][] = $row['name'];
          $respond['online'][] = $row['online'];
          $respond['picture'][] = $row['picture'];

          $query2 = "SELECT meetingid FROM meetingslog WHERE memberid = '".$row['id']."' ";
          $res2 = $dbConn->query($query2);
          if (mysqli_num_rows($res2) > 0)
          {
            $respond['nummeeting'][] = mysqli_num_rows($res2);
          }
          else
          {
              $respond['nummeeting'][] = 0;
          }
        }
      }
    }

	mysqli_close($dbConn);
    echo json_encode($respond);
}

 ?>
