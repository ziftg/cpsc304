<html>
  <head>
    <title><?php echo $pageTitle; ?></title>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

    <link rel="stylesheet" href="css/style.css" type="text/css">
  </head>
  <body>

    <div class="wrapper">
      <!-- <h2>Eagle Fly</h2>
      <h3>Your travel compaion</h3> -->
      <?php

      //this tells the system that it's no longer just parsing
      //html; it's now parsing PHP
      $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))";

      $success = True; //keep track of errors so it redirects the page only if there are no errors
      $db_conn = OCILogon("ora_f7l0b", "a32816143", $db);

      function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo "<br>running ".$cmdstr."<br>";
        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
          echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
          $e = OCI_Error($db_conn); // For OCIParse errors pass the
          // connection handle
          echo htmlentities($e['message']);
          $success = False;
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
          echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
          $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
          echo htmlentities($e['message']);
          $success = False;
        } else {

        }
        return $statement;

      }


      function executeBoundSQL($cmdstr, $list) {
        /* Sometimes a same statement will be excuted for severl times, only
         the value of variables need to be changed.
         In this case you don't need to create the statement several times;
         using bind variables can make the statement be shared and just
         parsed once. This is also very useful in protecting against SQL injection. See example code below for       how this functions is used */

        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr);

        if (!$statement) {
          echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
          $e = OCI_Error($db_conn);
          echo htmlentities($e['message']);
          $success = False;
        }

        foreach ($list as $tuple) {
          foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

          }
          $r = OCIExecute($statement, OCI_DEFAULT);
          if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
          }
        }

      }

      function printPurchaseHistory($result) { //prints results from a select statement
        // echo "<br>Got data from table onboardstaff:<br>";
        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>Ticket ID</th><th>Price</th><th>Flight Number</th><th>Departure Time</th><th>Arrival Time</th>
              <th>Departure Airport</th><th>Arrival Airport</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] ."</td></tr>" ; //or just use "echo $row[0]"
        }
        echo "</table>";

      }

      function printclientinfo3($result1,$result2,$choose) { //prints results from a select statement
       // echo "<br>Got data from table onboardstaff:<br>";
        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>User ID</th><th>Name</th><th>Gender</th><th>Passport Number</th><th>Nationality</th>
              <th>Email</th><th>Date of Birth</th><th>" . $choose . "</th></tr>";

        while (($row1 = OCI_Fetch_Array($result1, OCI_BOTH))&&($row2 = OCI_Fetch_Array($result2, OCI_BOTH))) {
          echo "<tr><td>" . $row1[0] . "</td><td>" . $row1[6] ."</td><td>". $row1[1] ."</td><td>" . $row1[3]."</td><td>" . $row1[4]."</td><td>" . $row1[2]."</td><td>" . $row1[5]."</td><td>". $row2[0].
          "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";

      }


      // // Connect Oracle...
      if ($db_conn) {
        if (array_key_exists('memberDetail', $_POST)) {

            //echo "<h2 class='text-centered'>Previous Tasks for: ". $_POST['employID'] ."</h2>";
            //echo "<table class='table table-hover text-centered'>";
          $userid=$_POST['clientid'];

          if ($_POST['InfoType'] == 'history') {
            executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
            $result=executePlainSQL("select ticket_has.ticketID as tid, ticket_has.ticketPrice as price,
                                      ticket_has.flightNumber as fno, Flight_Use.ETD as etd, Flight_Use.ETA as eta,
                                      Flight_Use.departureAirport as dapt, Flight_Use.arrivalAirport as aapt
                                      from member_serve, Flight_Use, ticket_has
                                      where member_serve.userid='$userid' and 
                                            member_serve.passportNum=ticket_has.passportNumber and
                                            ticket_has.flightNumber=Flight_Use.flightNumber and 
                                            ticket_has.dateorg=Flight_Use.departureDate");
            echo "<h3 class='text-centered'>Purchase History for: ". $_POST['clientid'] ."</h3>";
            echo "<table class='table table-hover text-centered'>";
            printPurchaseHistory($result);
          }
          else {
         
            $result=executePlainSQL("select member_serve.userid as userid, member_serve.gender as gender, member_serve.emailAddress
              as email,member_serve.passportNum as passport, member_serve.nationality as nationality, 
              member_serve.dob as dob, member_serve.name as name
              from member_serve
              where member_serve.userid='$userid'");

               // printclientinfo($result);
            $result1=executePlainSQL("
                   select COUNT(ticket_has.ticketID) as numtickets
                   from member_serve,ticket_has
                   where member_serve.userid='$userid' and 
                   member_serve.passportNum=ticket_has.passportNumber");  

            $result2=executePlainSQL("
                   select AVG(ticket_has.ticketPrice) as avgs
                   from member_serve,ticket_has
                   where member_serve.userid='$userid' and 
                   member_serve.passportNum=ticket_has.passportNumber");  

            $result3=executePlainSQL("
                   select MIN(ticket_has.ticketPrice) as mins
                   from member_serve,ticket_has
                   where member_serve.userid='$userid' and 
                   member_serve.passportNum=ticket_has.passportNumber");  

            $result4=executePlainSQL("
                   select  MAX(ticket_has.ticketPrice) as maxs
                   from member_serve,ticket_has
                   where member_serve.userid='$userid' and 
                   member_serve.passportNum=ticket_has.passportNumber");  

            $result5=executePlainSQL("
                   select SUM(ticket_has.ticketPrice) as sums
                   from member_serve,ticket_has
                   where member_serve.userid='$userid' and 
                   member_serve.passportNum=ticket_has.passportNumber"); 

            echo "<h3 class='text-centered'>Profile of ". $_POST['clientid'] ."</h3>";
            echo "<table class='table table-hover text-centered'>";
            $choose = $_POST['InfoType'];
            switch ($choose) {
                  case 'count':
                      printclientinfo3($result,$result1,$choose);
                      break;
                  case 'average':
                      printclientinfo3($result,$result2,$choose);
                      break;
                  case 'min':
                       printclientinfo3($result,$result3,$choose);
                      break;
                  case 'max':
                      printclientinfo3($result,$result4,$choose);
                      break;
                  case 'sum':
                      printclientinfo3($result,$result5,$choose);
                      break;
                  
            }
          }
        }
      }
  ?>

    </div>

  </body>

  </html>
