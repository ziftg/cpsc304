<html>
<head>
	<title><?php echo $pageTitle; ?></title>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

	<div class="header">
    <div class="wrapper">
            <div class="container">
              <div class="row">
                <div class="col-lg-2" style="margin-right:120"><h1 class="branding-title"><a href="/">Personal Media Library</a></h1></div>
                <div class="col-lg-6">
                  <form method="POST" action="members.php">

                  <div class="control-group col-sm-6">
                    <div class="controls">
                      <input type="text" name="userID" id="inputEmail" placeholder="user name">
                    </div>
                  </div>

                  <div class="control-group col-sm-6">
                    <div class="controls">
                      <input type="password" name="password" id="inputPassword" placeholder="password">
                    </div>
                  </div>

                  <div class="control-group col-lg-4">
                    <div class="controls">
                      <input type="submit" class="btn btn-primary" value="login as member" name="member">
                    </div>
                  </div>
                  <div class="control-group col-lg-3">
                    <div class="controls">
                      <input type="submit" class="btn btn-primary" value="login as staff" name="staff">
                    </div>
                  </div>

                  <div class="control-group col-lg-3">
                    <div class="controls">
                      <input type="submit" class="btn btn-primary" value="login as agent" name="agent">
                    </div>

                  </div>
            </form>
                </div>
              <div class="col-lg-1">
                <form method="POST" action="member_application.php">
                  <div class="controls">
                    <button type="submit" class="btn btn-primary btn-minor-outline">Apply Membership !</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
    		</div>
	</div>
  <div class="wrapper">
    <h2>Eagle Fly</h2>
    <h3>Your travel companion</h3>
  </div>


  <form class="form-horizontal" action="mainpage.php" method="POST">


    <div class="container">
      <div class="row vertical-center-row">
        <div class="control-group col-sm-2">
          <h4>search by</h4>
        </div>
        <div class="control-group col-sm-4">
          <label class="control-label" for="inputEmail">Flight Number</label>
          <div class="controls">
            <input type="text" name="flightno" placeholder="Enter a flight number">
          </div>
        </div>

        <div class="control-group col-sm-4">
          <label class="control-label" for="inputPassword">Date</label>
          <div class="controls">
            <input type="text" name="dDate" placeholder="YYYY-MM-DD">
          </div>
        </div>
      </div>


      <div class="row">
        <div class="control-group col-sm-2">
          <h4>or search by</h4>
        </div>
        <div class="control-group col-sm-2">
          <label class="control-label" for="inputEmail">Departure Airport</label>
          <div class="controls">
            <input type="text" name="dAirport" placeholder="Enter 3-letter Code">
          </div>
        </div>

        <div class="control-group col-sm-2">
          <label class="control-label" for="inputPassword">Arrival Airport</label>
          <div class="controls">
            <input type="text" name="aAirport" placeholder="Enter 3-letter Code">
          </div>
        </div>

        <div class="control-group col-sm-2">
          <label class="control-label" for="inputPassword">From Date</label>
          <div class="controls">
            <input type="text" name="fromDate" placeholder="YYYY-MON-DD">
          </div>
        </div>

        <div class="control-group col-sm-2">
          <label class="control-label" for="inputPassword">To Date</label>
          <div class="controls">
            <input type="text" name="toDate" placeholder="YYYY-MON-DD">
          </div>
        </div>

        <div class="control-group col-sm-2" >
          <label class="control-label" id="sel1">Order By:</label>
            <select name="order_by" class="form-control">
              <option value="">date</option>
              <option value="order by ticketPrice">price low to high</option>
              <option value="order by ticketPrice DESC">price high to low</option>
            </select>
        </div>
      </div>
    </div>

    <div align="center">
      <div class="controls">
        <input type="submit" class="btn btn-primary btn-primary-big" name="checkTicket" value="Check Availability"></input>
      </div>
    </div>
  </form>

  <form class="form" action="mainpage.php" method="POST">

    <div align="center" id="ticketId">
      <label class="control-label" for="inputPassword" style="margin: 30">Manage My Ticket</label>
      <div class="controls">
        <input type="text" name="tpnm1" placeholder="Enter your ticket ID" >
      </div>
    </div>


    <div align="center">
      <div class="controls">
        <input type="submit" class="btn btn-primary btn-primary-big" name="manageMyTicket" value="Manage My Ticket" style="margin-top: 15"></input>
      </div>
    </div>
  </form>

	<div id="content">
  <?php
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

      function printflightinfo($result) { //prints results from a select statement
        //echo "<br> CLIENTS INFO<br>";
        echo "<table class='table table-hover text-centered' style='color: white'>";
        echo "<tr><th>Flight Number</th><th>Departure Date</th><th>Price</th><th>Departure Airport</th>
        <th>Arrival Airport</th><th>ETD</th>
        <th>ETA</th><th>Action</th></tr>";
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] ."</td><td>" . $row[1]."</td><td>".
          $row[3]."</td><td>".
          $row[5]."</td><td>".
          $row[4]."</td><td>".
          $row[6]."</td><td>".
          $row[7]."</td><td>".
          "<form method='POST' action='purchase_ticket.php'>
              <p> <input type='hidden' name='flightNo' size='6' value=$row[0]>
                  <input type='hidden' name='departureDate' size='6' value=$row[1]>
                  <input type='submit' name='purchase' class='btn btn-primary' value='purchase'>
              </p>
            </form>"."</td></tr>";
        }
        echo "</table>";
      }

      function printresultticket($result){
        if($result){
          echo "<table class='table table-hover text-centered' style='color: white'>";
          echo "<tr><th>Ticket ID</th><th>Ticket Price</th><th>Passport Number</th><th>Flight Number</th><th>Date</th>
                <th>Cancel Flight</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
           echo "<tr align='center'><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3].
            "</td><td>".$row[4]."</td><td>" .
            "<form method='POST' action='mainpage.php'>
              <p> <input type='hidden' name='ticketid' size='6' value=$row[0]>
                  <input type='submit' name='cancel' class='btn btn-primary' value='Cancel'>
              </p>
            </form>" ."</td></tr>" ;
       }
       echo "</table>";
     } else {
       echo "null";
     }

    }

    function printreDefault(){
      echo "<table>";
      echo "</table>";
    }

      if ($db_conn) {
        if(array_key_exists('checkTicket', $_POST)){
            if (($_POST['flightno'] == NULL || $_POST['dDate'] == NULL) &&
                ($_POST['dAirport'] == NULL || $_POST['aAirport'] == NULL ||
                  $_POST['fromDate'] == NULL || $_POST['toDate'] == NULL)) {
              echo "<script>alert('Oops! Not enough information for searching:(')</script>";
            } else if ($_POST['flightno'] != NULL || $_POST['dDate'] != NULL) {
              $flightno=$_POST['flightno'];
              $ddate=$_POST['dDate'];
              $result=executePlainSQL("select *
                                      from Flight_Use
                                      where Flight_Use.flightNumber='$flightno' and Flight_Use.departureDate='$ddate'");
              executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
              printflightinfo($result);
            }

            else {
              executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
              $dairport=$_POST['dAirport'];
              $aairport=$_POST['aAirport'];
              $date1=$_POST['fromDate'];
              $timestamp1= executePlainSQL("SELECT TO_TIMESTAMP('$date1','YYYY-MON-DD') FROM dual");

              $date2=$_POST['toDate'];
              $timestamp2=executePlainSQL("SELECT TO_TIMESTAMP('$date2','YYYY-MON-DD') FROM dual" );

              $time1 = OCI_Fetch_Array($timestamp1, OCI_BOTH);
              $time2 = OCI_Fetch_Array($timestamp2, OCI_BOTH);

              $order_by=$_POST['order_by'];

              $result=executePlainSQL("
                      select *
                      from Flight_Use
                      where Flight_Use.departureAirport='$dairport' and Flight_Use.arrivalAirport='$aairport' and
                        Flight_Use.ETD>= '$time1[0]' and Flight_Use.ETD<= '$time2[0]' $order_by");
              printflightinfo($result);

            }

        } else if(array_key_exists('manageMyTicket', $_POST)){
          $tid3=$_POST['tpnm1'];
          $result = executePlainSQL("select * from ticket_has where ticket_has.ticketID='$tid3'");
            printresultticket($result);
        }
        if(array_key_exists('cancel', $_POST)){
          $tid=$_POST['ticketid'];
          executePlainSQL("delete from ticket_has where exists (select * from ticket_has where ticket_has.ticketID='$tid')");
          OCICommit($db_conn);
        }
      }
  ?>
  </div>
  </body>
  </html>
