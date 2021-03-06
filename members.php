<!--Test Oracle file for UBC CPSC304 2011 Winter Term 2
  Created by Jiemin Zhang
  Modified by Simona Radu
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "tab1" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

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
      echo "
      <div class='container'>
        <div class='row'>
          <form method='POST' action='mainpage.php'>
            <div class='control-group col-sm-4'><input type='submit' class='btn btn-primary' value='Back' style='margin-top: 30'></div>
          </form>
        </div>
      </div>
      ";

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


      function printclientinfo($result) { //prints results from a select statement
          echo "<table>";
          echo "<tr><th>ID</th><th>Name</th><th>password</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] ."</td><td>". $row[2] ."</td><td>" . $row[3]."</td><td>" . $row[4]."</td><td>" . $row[5]."</td><td>" . $row[6]."</td></tr>" ; //or just use "echo $row[0]"
          }
          echo "</table>";

        }

      function printclient($result) { //prints results from a select statement
        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>Client Id</th><th>Client Name</th></th><th>Actions</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] ."</td><td>".$row[1]."</td><td>"."<form method='POST' action='client_info.php'>
              <p>
              <select name='InfoType'>
              <option value='' disabled selected>Select Information Type</option>
              <option value='history'>All Purchase History</option>
              <option value='min'>Cheapest Ticket Purchased</option>
              <option value='max'>Most Expensice Ticket Purchased</option>
              <option value='sum'>Total Money Spent</option>
              <option value='count'>Total Number of Tickets Purchased</option>
              <option value='average'>Average Cost of Each Ticket</option>
              </select>
              <input type='hidden' name='clientid' size='6' value=$row[0]>
               <input type='submit' value='Detail' class='btn btn-primary' name='memberDetail'>
               </p>

               </form>"."</td></tr>";
        }
        echo "</table>";

      }

      function printResultwinew($result) { //prints results from a select statement
        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>Employee Number</th><th>name</th><th>flightnumber</th><th>date</th><th>Role</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row["EMPLOYNUMBER"] . "</td><td>" . $row["NAME"] ."</td><td>". $row["FLIGHTNUMBER"] ."</td><td>". $row["DATE1"]. "</td><td>" . $row["ROLE"] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
      }

      function printresultaircraft($result) { //prints results from a select statement
        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>Aircraft Serial Number</th><th>Aircraft Type</th><th>Capacity</th>
              <th>Actual Number of Passengers</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] ."</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[1] . "</td><td>";
        }
        echo "</table>";
      }

      function printMain() { //prints results from a select statement

        if (array_key_exists('member', $_POST)) {
          $eid=$_POST['userID'];
          $password=$_POST['password'];
          $result = executePlainSQL("
                                      select case when count(*) > 0 then 1 else 0 end
                                       from member_serve
                                       where userid='$eid' and password='$password'");
          $value = OCI_Fetch_Array($result, OCI_BOTH)[0];

          if(!$value) {
            echo "<script>alert('Oops! We cannot find you:(')</script>";
          }
          else {
            echo "<h3 class='text-centered'>Welcome: ". $_POST['userID'] ."</h3>";
            echo "<table class='table table-hover text-centered'>";
            $userid = $_POST['userID'];

            echo "
              <form method='POST' action='members.php'>
                <input type='hidden' name='userid' value=$userid>
                <div class='form-group' align='center'>
                  <input type='submit' class='btn btn-primary-outline btn-block' value='Purchase History' name='history'>
                </div>
                <div class='form-group' align='center'>
                  <input type='submit' class='btn btn-primary-outline btn-block' value='Your Service Agent' name='myAgent'>
                </div>
                <div class='form-group' align='center'>
                  <input type='submit' class='btn btn-primary-outline btn-block' value='Change Your Personal Information' name='profile'>
                </div>
              </form>
            ";
          }



      	} else
      		if (array_key_exists('staff', $_POST)) {

            $eid=$_POST['userID'];
            $password=$_POST['password'];

            $result = executePlainSQL("
            select case when count(*) > 0 then 1 else 0 end
            from onboardstaff
            where employNumber='$eid' and password='$password'");

            $value = OCI_Fetch_Array($result, OCI_BOTH)[0];
             if(!$value){
               echo "<script>alert('Oops! We cannot find you:(')</script>";

             }
              else
              {
              echo "<h3 class='text-centered'>Welcome: ". $_POST['userID'] ."</h3>";
              echo "<table class='table table-hover text-centered'>";
              $employid = $_POST['userID'];
              echo "
              <div style='margin-left: 400' align='center'>
                <form align='center' method='POST' action='members.php'>
                  <div class='row'>
                    <div class='control-group col-sm-4'>
                      <input type='hidden' value=$employid name='employID'>
                      <div class='controls'>
                        <input type='submit' class='btn btn-primary' value='Check Previous Tasks' name='previous'>
                      </div>

                      <div class='controls' style='margin-top: 30'>
                        <input type='submit' class='btn btn-primary' value='Check Future Tasks' name='future'>
                      </div>

                      <div class='controls' style='margin-top: 30'>
                        <label class='control-label'>Aircraft Statistics</label>
                        <select name='statsType'>
                          <option value='' disabled selected>Select Statistics Type</option>
                          <option value='avg'>Average Attendance Rate</option>
                          <option value='max'>Maximum Attendance Rate</option>
                          <option value='min'>Minimum Attendance Rate</option>
                        </select>
                      </div>

                      <div class='controls' style='margin-top: 30'>
                        <input type='submit' class='btn btn-primary' value='Check Flights Statistics' name='stats'>
                      </div>
                    </div>
                  </div>

                  <div class='row' style='margin-top: 30' align='center'>
                    <div class='control-group col-sm-4'>
                      <label class='control-label' for='inputEmail'>Flight Number</label>
                      <div class='controls'>
                        <input type='text' name='flightno' placeholder='Enter a flight number'>
                      </div>

                      <label class='control-label' for='inputEmail' style='margin-top: 15'>Departure Date</label>
                      <div class='controls'>
                        <input type='text' name='date' placeholder='YYYY-MM-DD'>
                      </div>

                      <div class='controls' style='margin-top: 15'>
                        <input type='submit' class='btn btn-primary' value='Check Aircraft' name='aircraft'>
                      </div>
                    </div>
                  </div>

                  <div class='row' style='margin-top: 30'>
                    <div class='control-group col-sm-4'>
                      <div class='controls'>
                        <input type='submit' class='btn btn-primary' value='Most Royal Members' name='division'>
                      </div>
                    </div>
                  </div>
                </form>

                <form align='center' method='POST' action='mainpage.php'>
                  <div class='control-group col-sm-4'><input type='submit' class='btn btn-primary' value='Back' style='margin-top:'></div>
                </form>
              </div>";
            }

      		} else
      			if (array_key_exists('agent', $_POST)) {

              $eid=$_POST['userID'];
              $password=$_POST['password'];

              $result = executePlainSQL("
              select case when count(*) > 0 then 1 else 0 end
              from customerservice
              where employNumber='$eid' and password='$password'");

              $value = OCI_Fetch_Array($result, OCI_BOTH)[0];

              if(!$value)
              {echo "<script>alert('Oops! We cannot find you:(')</script>";}
              else
              {
                echo "<h3 class='text-centered'>Welcome: ". $_POST['userID'] ."</h3>";
              echo "<table class='table table-hover text-centered'>";
              $eno=$_POST['userID'];
              $result = executePlainSQL("
                  select member_serve.userid as userid, member_serve.name as name
                  from member_serve
                  where member_serve.employNumber=$eno");
              printclient($result);
              echo "<form align='center' method='POST' action='mainpage.php'>
                      <input type='submit' class='btn btn-primary' value='Back' style='margin-top:'>
                    </form>";
            }


            }


        echo "</table>";
      }

      function printPurchaseHistory($result) { //prints results from a select statement

        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>Ticket ID</th><th>Price</th><th>Flight Number</th><th>Departure Time</th><th>Arrival Time</th>
              <th>Departure Airport</th><th>Arrival Airport</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] ."</td></tr>" ; //or just use "echo $row[0]"
        }
        echo "</table>";

      }

      function printAgent($result) { //prints results from a select statement

        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>Employee Number</th><th>Name</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>" ; //or just use "echo $row[0]"
        }
        echo "</table>";

      }

      function printProfile($result1) { //prints results from a select statement
       // echo "<br>Got data from table onboardstaff:<br>";
        echo "<table class='table table-hover text-centered' style='color: black'>";
        echo "<tr><th>User ID</th><th>Name</th><th>Gender</th><th>Passport Number</th><th>Nationality</th>
              <th>Email</th><th>Date of Birth</th></tr>";

        while ($row1 = OCI_Fetch_Array($result1, OCI_BOTH)) {
          echo "<tr><td>" . $row1[0] . "</td><td>" . $row1[6] ."</td><td>". $row1[1] ."</td><td>" . $row1[3]."</td><td>" . $row1[4]."</td><td>" . $row1[2]. "</td><td>" . $row1[5]. "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";

      }

      // // Connect Oracle...
      if ($db_conn) {
        executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
        printMain();
        if(array_key_exists('memberDetail', $_POST)){
            $userid=$_POST['userid2'];
            $name=$_POST['name2'];
            $result=executePlainSQL("select member_serve.userid as userid, member_serve.gender as gender,
                      member_serve.emailAddress as email,member_serve.passportNum as passport,
                      member_serve.nationality as nationality,
                      member_serve.dob as dob, member_serve.name as name
                      from member_serve,
                      where member_serve.userid=$userid");
            printclientinfo($result);
        }

        if(array_key_exists('previous', $_POST)){
          echo "<h3 class='text-centered'>Previous Tasks for: ". $_POST['employID'] ."</h3>";
          echo "<table class='table table-hover text-centered'>";
          $number1=$_POST['employID'];
          executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
          $result = executePlainSQL("select workin.employNumber as employnumber,onboardstaff.name as
                                        name,workin.flightNumber as flightnumber,workin.dateorg as date1,
                                        onboardstaff.role as role
                                        from workin,onboardstaff,Flight_Use
                                        where workin.employNumber=$number1 and
                                        workin.employNumber=onboardstaff.employNumber and
                                        Flight_Use.departureDate=workin.dateorg and
                                        Flight_Use.ETD<='01-APR-2017 00:00:00'");
          printResultwinew($result);
        }

        if (array_key_exists('future', $_POST)){
          echo "<h3 class='text-centered'>Future Tasks for: ". $_POST['employID'] ."</h3>";
          echo "<table class='table table-hover text-centered'>";
          $number1=$_POST['employID'];
          executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
          $result = executePlainSQL("select workin.employNumber as employnumber,onboardstaff.name as
                                      name,workin.flightNumber as flightnumber,workin.dateorg as date1,
                                      onboardstaff.role as role
                                      from workin,onboardstaff,Flight_Use
                                      where workin.employNumber=$number1 and
                                      workin.employNumber=onboardstaff.employNumber and
                                      Flight_Use.departureDate=workin.dateorg and
                                      Flight_Use.ETD>'01-APR-2017 00:00:00'  ");
          printResultwinew($result);
        }

        if (array_key_exists('division', $_POST)){
          echo "<h3 class='text-centered'>Most Royal Members</h3>";
          echo "<table class='table table-hover text-centered'>";
          $result=executePlainSQL("select member_serve.name from member_serve where not exists (select Flight_Use.flightNumber from Flight_Use minus select ticket_has.flightNumber from ticket_has where ticket_has.passportNumber = member_serve.passportNum)");

          echo "<table class='table table-hover text-centered' style='color: black'>";
          echo "<tr><th>Member name</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>";
          }
          echo "</table>";
        }


        if (array_key_exists('stats', $_POST)){
          $result1= executePlainSQL("
               select round(AVG(avgs)*100,4)
               from(select AVG(Flight_Use.numOfPassengers/AirCraft.capacity) as avgs
                from AirCraft,Flight_Use
                where Flight_Use.aircraftSerialNo=AirCraft.serialNo
                group by AirCraft.serialNo)
                ");

          $result2= executePlainSQL("
                select round(MAX(avgs)*100,4)
                from(select AVG(Flight_Use.numOfPassengers/AirCraft.capacity) as avgs
                from AirCraft,Flight_Use
                where Flight_Use.aircraftSerialNo=AirCraft.serialNo
                group by AirCraft.serialNo)
                ");

          $result3= executePlainSQL("
               select round(MIN(avgs)*100,4)
              from(select AVG(Flight_Use.numOfPassengers/AirCraft.capacity) as avgs
                from AirCraft,Flight_Use
                where Flight_Use.aircraftSerialNo=AirCraft.serialNo
                group by AirCraft.serialNo)
                ");

          if(isset($_POST['statsType'])){
            $decide = $_POST['statsType'];
            switch ($decide) {
                case 'avg':
                   echo 'Average Attendance Rate: ',OCI_Fetch_Array($result1,OCI_BOTH)[0],'%';
                    break;
                case 'max':
                    echo 'Maximum Attendance Rate: ',OCI_Fetch_Array($result2,OCI_BOTH)[0],'%';
                    break;
                case 'min':
                      echo 'Minimum Attendance Rate: ',OCI_Fetch_Array($result3,OCI_BOTH)[0],'%';
                    break;
            }
        }
        }

        if (array_key_exists('aircraft', $_POST)){
          $fno=$_POST['flightno'];
          $date=$_POST['date'];
          echo "<h3 class='text-centered'>Aircraft Information for: ". $fno ."</h3>";
          echo "<table class='table table-hover text-centered'>";

          $result = executePlainSQL("
                  select Flight_Use.aircraftSerialNo as sno, Flight_Use.numOfPassengers as nop,
                          AirCraft.type as type, AirCraft.capacity as cap
                  from Flight_Use, AirCraft
                  where Flight_Use.aircraftSerialNo=AirCraft.serialNo and Flight_Use.flightNumber='$fno' and Flight_Use.departureDate='$date'");
          printresultaircraft($result);

        }

        if (array_key_exists('history', $_POST)){
          echo "<h3 class='text-centered'>Purchase History for: ". $_POST['userid'] ."</h3>";
          echo "<table class='table table-hover text-centered'>";
          $userid=$_POST['userid'];
          $result=executePlainSQL("select ticket_has.ticketID as tid, ticket_has.ticketPrice as price,
                                      ticket_has.flightNumber as fno, Flight_Use.ETD as etd, Flight_Use.ETA as eta,
                                      Flight_Use.departureAirport as dapt, Flight_Use.arrivalAirport as aapt
                                      from member_serve, Flight_Use, ticket_has
                                      where member_serve.userid='$userid' and
                                            member_serve.passportNum=ticket_has.passportNumber and
                                            ticket_has.flightNumber=Flight_Use.flightNumber and
                                            ticket_has.dateorg=Flight_Use.departureDate");
            printPurchaseHistory($result);
        }

        if (array_key_exists('profile', $_POST)){
          $userid = $_POST['userid'];
          echo "<h3 class='text-centered'>Change your Profile: ". $_POST['userid'] ."</h3>";
          $result=executePlainSQL("select member_serve.userid as userid, member_serve.gender as gender, member_serve.emailAddress
              as email,member_serve.passportNum as passport, member_serve.nationality as nationality,
              member_serve.dob as dob, member_serve.name as name
              from member_serve
              where member_serve.userid='$userid'");
          printProfile($result);
          echo "<div align='center'>
                  <form action='members.php' method='POST'>
                    <input type='hidden' name='userid' value=$userid>
                    <p>Passport Number:
                      <input type='text' name='passport'>
                    </p>
                    <p>New User ID:
                      <input type='text' name='newid'>
                    </p>
                    <p>
                      <input type='submit' name='changeid' class='btn btn-primary' value='Change User ID'>
                    </p>
                  </form>

                  <form action='members.php' method='POST'>
                    <input type='hidden' name='userid' value=$userid>
                    <p>Old Password:
                      <input type='password' name='oldpassword'>
                    </p>
                    <p>New Password:
                      <input type='password' name='newpassword1'>
                    </p>
                    <p>New Password Confirm:
                      <input type='password' name='newpassword2'>
                    </p>
                    <p>
                      <input type='submit' name='changepwd' class='btn btn-primary' value='Change Password'>
                    </p>
                  </form>

                  <form action='members.php' method='POST'>
                    <input type='hidden' name='userid' value=$userid>
                    <p>New Email:
                      <input type='text' name='newemail'>
                    </p>
                    <p>
                      <input type='submit' name='changeemail' class='btn btn-primary' value='Change Email'>
                    </p>
                  </form>
                </div>";

        }

        if (array_key_exists('changepwd', $_POST)){
          $userid=$_POST['userid'];
          $passwordold=$_POST['oldpassword'];
          $temp=executePlainSQL("select password from member_serve where userid='$userid'");
          $temp2 = OCI_Fetch_Array($temp, OCI_BOTH);
          $checkpwd = $temp2[0];
          if ($checkpwd != $passwordold) {
            echo "<script>alert('You entered a wrong password.')</script>";
          } else if ($_POST['newpassword1'] != $_POST['newpassword2']) {
            echo "<script>alert('Your new passwords do not match.')</script>";
          } else {
            $passwordnew=$_POST['newpassword1'];
            executePlainSQL("update member_serve set member_serve.password='$passwordnew' where member_serve.userid ='$userid' and member_serve.password='$passwordold'");
            OCICommit($db_conn);
            echo "<script>alert('You have successfully changed your password.')</script>";
          }
        }

        if (array_key_exists('changeemail', $_POST)){
          $userid=$_POST['userid'];
          $email=$_POST['newemail'];
          executePlainSQL("update member_serve set member_serve.emailAddress='$email' where member_serve.userid ='$userid'");
          OCICommit($db_conn);
          $newuser=executePlainSQL("select emailAddress from member_serve where userid='$userid'");
          $newuser=OCI_Fetch_Array($newuser,OCI_BOTH)[0];
          if($newuser!=$email)
            echo "<script> alert('Please enter a valid email address and try again.') </script>";
          else
            echo "<script>alert('You have successfully changed your email.')</script>";
        }

        if (array_key_exists('changid', $_POST)){
          $id10=$_POST['passport'];
          $uid10=$_POST['newid'];
          $result=executePlainSQL("
                 create or replace trigger alter_id_trigger before update on member_serve begin set member_serve.userid='$uid10' where member_serve.passportNumber ='$id10' end");
        }

        if(array_key_exists('myAgent', $_POST)){
          echo "<h3 class='text-centered'>Custormer Service Agent for: ". $_POST['userid'] ."</h3>";
          echo "<table class='table table-hover text-centered'>";
          $userid=$_POST['userid'];
          $result=executePlainSQL("select customerservice.employNumber, customerservice.name
                                      from member_serve, customerservice
                                      where member_serve.userid='$userid' and
                                            member_serve.employNumber=customerservice.employNumber");
          printAgent($result);
        }

      }
      ?>
    </div>

  </body>

  </html>
