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


      function printclientinfo($result) { //prints results from a select statement
         // echo "<br>Got data from table onboardstaff:<br>";
          echo "<table>";
          echo "<tr><th>ID</th><th>Name</th><th>password</th></tr>";

          while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] ."</td><td>". $row[2] ."</td><td>" . $row[3]."</td><td>" . $row[4]."</td><td>" . $row[5]."</td><td>" . $row[6]."</td></tr>" ; //or just use "echo $row[0]"
          }
          echo "</table>";

        }

      function printclient($result) { //prints results from a select statement
        //echo "<br> onboardstaff check the aircraft is used on given date and flight no:<br>";
        echo "<table class='table table-hover text-centered'>";
        echo "<tr><th>Client Id</th><th>Client Name</th></th><th>Actions</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] ."</td><td>".$row[1]."</td><td>"."<form method='POST',action='database_init.php'>
              <p>
              <select name='InfoType'>
              <option value='' disabled selected>Select Information Type</option>
              <option value='history'>All Purchase History</option>
              <option value='min'>Cheapest Ticket Purchased</option>
              <option value='max'>Most Expensice Ticket Purchased</option>
              <option value='sum'>Total Money Spent</option>
              <option value='count'>Total Number of Tickets Purchased</option>
              <option value='Average'>Average Cost of Each Ticket</option>
              </select>
              <input type='hidden' name='userid2' size='6' value=$row[0]>
               <input type='hidden' name='name2' size='6' value=$row[1]>
               <input type='submit' value='Detail' name='query5'>
               </p>

               </form>"."</td></tr>"; 
        }
        echo "</table>"; 

      }

      function printExample ($result) {
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[8] ."</td><tr>"; //or just use "echo $row[0]"
        }
      }

      // // Connect Oracle...
      if ($db_conn) {
        if(isset($_POST)){
            echo "Here";
        }
    }
      ?>







    </div>











  </body>

  </html>
