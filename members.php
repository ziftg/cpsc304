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

      function printExample ($result) {
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row[0] . "</td><td>" . $row[8] ."</td><tr>"; //or just use "echo $row[0]"
        }
      }

      function printMain() { //prints results from a select statement
        echo "<h2 class='text-centered'>Welcome: ". $_POST['userID'] ."</h2>";
        echo "<table class='table table-hover text-centered'>";
        echo "<tr><th>UserID</th><th>TicketID</th></tr>";

        if (array_key_exists('member', $_POST)) {
      		echo '
          <div class="container vertical-center-row">
            <div class="row">
            <form>
            <div class="control-group col-sm-8">
              <div class="controls">
                <input type="submit" class="btn btn-primary" value="login as member" name="member">
              </div>
            </div>

            <div class="control-group col-sm-2">
              <div class="controls">
                <input type="submit" class="btn btn-primary" value="login as member" name="member">
              </div>
            </div>

            </form>
            </div>
          </div>';
          $employID = (int)$_POST['userID'];
          $example = executePlainSQL("select * from member_serve where employNumber = $employID");

          printExample($example);

      	} else
      		if (array_key_exists('staff', $_POST)) {
            echo '
            <div class="container vertical-center-row">
              <div class="row">
              <form>
              <div class="control-group col-sm-4">
                <div class="controls">
                  <input type="submit" class="btn btn-primary" value="login as member" name="member">
                </div>
              </div>

              <div class="control-group col-sm-3">
                <div class="controls">
                  <input type="submit" class="btn btn-primary" value="login as member" name="member">
                </div>
              </div>

              <div class="control-group col-sm-2">
                <div class="controls">
                  <input type="text" id="inputEmail" placeholder="enter a flight ">
                  <input type="text" id="inputEmail" placeholder="enter a flight ">
                </div>
              </div>

              <div class="control-group col-sm-1">
                <div class="controls">
                  <input type="submit" class="btn btn-primary" value="login as member" name="member">
                </div>
              </div>
              </form>
              </div>
            </div>';

      		} else
      			if (array_key_exists('agent', $_POST)) {
              echo "<br> dropping agent <br>";
      			}


        echo "</table>";
      }

      // // Connect Oracle...
      if ($db_conn) {
        executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
        // $onboardstaff = executePlainSQL("select * from purchase");
        // printMain($onboardstaff);
        printMain();
      }
      ?>







    </div>











  </body>

  </html>
