<?php
$dbServer = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "mashdb";

if (isset($_POST['usrid']))
{
  if (strlen($_POST['usrid']) == 40)
  {
    /* Start with the code */
    $dbConn = mysqli_connect($dbServer, $dbUsername, $dbPassword, $dbName);
    $usrid = mysqli_real_escape_string($dbConn, htmlspecialchars($_POST['usrid']));

    $res = $dbConn->query("SELECT name FROM members WHERE id = '".$usrid."' ");
    $respond = array();
    if (mysqli_num_rows($res) == 1) {
      while ($__row = mysqli_fetch_array($res))
      {
        $respond['username'] = ucfirst($__row['name']);
      }

      $_res = $dbConn->query("SELECT meetingid FROM meetingslog WHERE memberid = '".$usrid."' ");
      if (mysqli_num_rows($_res) > 0)
      {
        $respond['status'] = 1;
        while ($row = mysqli_fetch_array($_res))
        {
            $meetingid = $row['meetingid'];

            $____res = $dbConn->query("SELECT * FROM meetings WHERE id = '".$meetingid."' ");
            while ($_row = mysqli_fetch_array($____res))
            {
                $time = substr( $_row['time'], 11, 5);
                $respond['meetingId'][] = $meetingid;
                $respond['meetingName'][] = ucfirst($_row['name']);
                $respond['meetingDescription'][] = $_row['description'];
                $respond['meetingDate'][] = setDate($_row['date']);
                $respond['meetingTime'][] =   $time;
                $respond['meetingLocation'][] = $_row['location'];
                $respond['meetingPriotity'][] = $_row['priority'];
                $respond['meetingType'][] = $_row['type'];
                $respond['meetingCreator'][] = ucfirst($_row['creator']);
            }
        }
      }
      else
      {
        // code...
        $respond['status'] = 0;
      }

		mysqli_close($dbConn);
      echo json_encode($respond);
    }
  }
}

function setDate($date) {
  $year = substr($date, 0, 4);
  $day = substr($date, -2);
  $mon = is_numeric(substr($date, 5, 2));
  $months = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  $month = $months[$mon];
  return $day.' '.$month.' '.$year;
}

?>
