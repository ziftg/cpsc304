<html>
  <head>
  	<title><?php echo $pageTitle; ?></title>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

  	<link rel="stylesheet" href="css/style.css" type="text/css">
  </head>
  <body>

    <h2 style="text-align: center;">Purchase Ticket</h2>
    <div align="center">
    <form action="mainpage.php">
      <input type="submit" class="btn btn-primary" value="Back">
    </form>
    <form method="POST" action="purchase_ticket.php">
      <p>Flight Number:
        <input type="text" name="fno" value="<?php echo $_POST['flightNo'] ?>" readonly="readonly">
      </p>
      <p>Departure Date:
        <input type="text" name="dDate" value="<?php echo $_POST['departureDate'] ?>" readonly="readonly">
      </p>
      <p>Full Name:
        <input type="text" name="name" size="12">
      </p>
      <p>Passport Number:
        <input type="text" placeholder="AA12345678" name="passport" size="12">
      </p>
      <p>Nationality:
        <input type="text" name="nationality" size="12">
      </p>
      <p>Gender:
        <input type="text" placeholder="M/F" name="gender" size="12">
      </p>
      <p>email:
        <input type="text" placeholder="1234567@abc.com" name="email" size="18">
      </p>
      <p>Are you a member?
        <select name="member">
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
        <input type="text" placeholder="If yes, Please enter your user ID" name="userid11" size="35">
      </p>
      <p>Password:
        <input type="password" placehold="Enter your password" name="password11">
      </p>
      <p><input type="submit" class="btn btn-primary" value="Confirm and Purchase" name="buy"></p>

    </form>
    </div>

    <div class="wrapper">

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

      function printresultidprice($result) { //prints results from a select statement

        $row = OCI_Fetch_Array($result, OCI_BOTH);
        echo "<script> alert('Congratulation! Your purchase is successful!\nTicket ID: " . $row[0] .
              "\nPrice: CAD" . $row[1] . "\nThank you!')</script>";    
      }

      // // Connect Oracle...
      if ($db_conn) {

        if (array_key_exists('buy', $_POST)) {
        $flightno=$_POST['fno'];
        $date=$_POST['dDate'];
        $passportnumber=$_POST['passport']; 
        $ticketid=mt_rand(1000000000000,9999999999999);           
        $temp=executePlainSQL("select ticketPrice from Flight_Use where flightNumber='$flightno'and departureDate='$date'");
        $temp2 = OCI_Fetch_Array($temp, OCI_BOTH);
        $price = $temp2[0];
               
               
        if(isset($_POST['member'])){
          $member = $_POST['member'];
          switch ($member) {
            case 'yes':
              $userid=$_POST['userid11'];
              $password=$_POST['password11'];
              $result=executePlainSQL("
                  select case when count(*) > 0 then 1 else 0 end 
                  from member_serve
                  where userid='$userid' and password='$password'");
              $value = OCI_Fetch_Array($result, OCI_BOTH)[0];
              if(!$value)
                {
                  echo"<script>alert('Wrong ID Password Combination!')</script>";
                }
              else {
                executePlainSQL("insert into ticket_has values ('$ticketid','$price','$passportnumber','$flightno','$date')");
                executePlainSQL("insert into purchase values ('$userid','$ticketid')");
                OCICommit($db_conn);
                echo "<script>alert('Success!')</script>";
              }
            break;
            case 'no':
              executePlainSQL("insert into ticket_has values ('$ticketid','$price','$passportnumber','$flightno','$date')");
              OCICommit($db_conn);
              echo "<script>alert('Success!')</script>";
            break;
          }

        }
        }
          
    }
      ?>







    </div>

  </body>

  </html>