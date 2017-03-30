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
      <p><input type="submit" class="btn btn-primary" value="Confirm and Purchase" name="buy"></p>

    </form>
    </div>

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

      function printresultidprice($result) { //prints results from a select statement

        $row = OCI_Fetch_Array($result, OCI_BOTH);
        echo "<script> alert('Congratulation! Your purchase is successful!\nTicket ID: " . $row[0] .
              "\nPrice: CAD" . $row[1] . "\nThank you!')</script>";    
      }

      // // Connect Oracle...
      if ($db_conn) {

        if(array_key_exists('buy', $_POST)){
            $fno=$_POST['fno'];
            $ddate=$_POST['dDate'];
            $pno=$_POST['passport']; 

            $ticketid=mt_rand(1000000000000,9999999999999);
            $temp=executePlainSQL("select ticketPrice from Flight_Use where flightNumber='$fno'and departureDate='$ddate'");
            $temp2 = OCI_Fetch_Array($temp, OCI_BOTH);
            $price = $temp2[0];
               
            executePlainSQL("insert into ticket_has values ('$ticketid','$price','$pno','$fno','$ddate')"); 
            OCICommit($db_conn);
            $result =  executePlainSQL("select ticket_has.ticketID, ticket_has.ticketPrice from ticket_has where ticket_has.passportNumber = '$pno' and ticket_has.flightNumber =  '$fno' and ticket_has.dateorg = '$ddate'");
            //printresultidprice($result); 
            $row = OCI_Fetch_Array($result, OCI_BOTH);
            echo "<script> alert('Congratulation! Your purchase is successful! \\nTicket ID: " . $row[0] . "\\nPrice: CAD" .
                  $row[1]. "Thank you!')</script>";
        }

    }
      ?>







    </div>

  </body>

  </html>