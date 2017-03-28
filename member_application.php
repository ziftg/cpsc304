<html>
  <head>
  	<title><?php echo $pageTitle; ?></title>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

  	<link rel="stylesheet" href="css/style.css" type="text/css">
  </head>
  <body>

    <h2 style="text-align: center;">Membership Application</h2>
    <form method="POST" action="member.php">
      <p>User ID:
        <input type="text" name="userID" size="12">
      </p>
      <p>Password:
        <input type="Password" name="password1" size="12">
      </p>
      <p>Confirm Password:
        <input type="Password" name="password2" size="12">
      </p>
      <p>Full Name:
        <input type="text" name="name" size="12">
      </p>
      <p>Gender:
        <input type="text" placeholder="M/F" name="gender" size="12">
      </p>
      <p>email:
        <input type="text" placeholder="1234567@abc.com" name="email" size="18">
      </p>
      <p>Passport Number:
        <input type="text" placeholder="AA12345678" name="passport" size="12">
      </p>
      <p>Nationality:
        <input type="text" name="nationality" size="12">
      </p>
      <p>Date of Birth:
        <input type="text" placeholder="YYYY-MM-DD" name="dob" size="12">
      </p>
      <p><input type="submit" value="Apply" name="applyMembership"></p>

    </form>

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

      function printResultp($result) { //prints results from a select statement
        //echo "<h2 class='text-centered'>Member</h2>";
        echo "<table class='table table-hover text-centered'>";
        echo "<tr><th>UserID</th><th>TicketID</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
          echo "<tr><td>" . $row["USERID"] . "</td><td>" . $row["TICKETID"] ."</td><tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
      }

      // // Connect Oracle...
      if ($db_conn) {

        if (array_key_exists('applyMembership', $_POST)) {
            if ($_POST['userID'] == NULL || $_POST['name'] == NULL || $_POST['gender'] == NULL ||
                $_POST['passport'] == NULL || $_POST['email'] == NULL || $_POST['nationality'] == NULL ||
                $_POST['dob'] == NULL)
              echo "<script> alert('All fields must not be empty.') </script>";
            if ($_POST['password1'] != $_POST['password2']) {
              echo "<script> alert('Password Not match.') </script>";
            }
            //else {
              // $employID = rand(1000, 1021);
              // $tuple = array (
              //   ":a" => $_POST['userID'],
              //   ":b" => $_POST['password'],
              //   ":c" => $_POST['gender'],
              //   ":d" => $_POST['email'],
              //   ":e" => $_POST['passport'],
              //   ":f" => $_POST['nationality'],
              //   ":g" => $_POST['dob'],
              //   ":h" => $_POST['name'],
              //   ":k" => $employID 
              // );
              // $alltuples = array (
              //   $tuple
              // );
              // executeBoundSQL("insert into member_serve values (:a, :b, :c, :d, :e, :f, :g, :h, :k)", $alltuples);
              // OCICommit($db_conn);
            //}

      }
    }
      ?>







    </div>

    











  </body>

  </html>