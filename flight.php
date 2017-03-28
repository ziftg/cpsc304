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

  function printResult($result) { //prints results from a select statement
  	echo "<br>Got data from table Flight_Use:<br>";
  	echo "<table>";
  	echo "<tr><th>Flight Number</th><th>Departure Date</th><th>AirCraft Serial Number</th>
  			<th>Price</th><th>Arrival Airport</th><th>Departure Airport</th><th>ETD</th>
  			<th>ETA</th><th>ATD</th><th>ATA</th><th>Number of Passengers</th></tr>";

  	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
  		echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] .
  				"</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] .
  				"</td><td>" . $row[6] . "</td><td>" . $row[7] .
  				"</td><td>" . $row[8] . "</td><td>" . $row[9] .
  				"</td><td>" . $row[10] . "</td><td>"; //or just use "echo $row[0]"
  	}
  	echo "</table>";

  }

  // Connect Oracle...
  if ($db_conn) {
    executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
    $test = executePlainSQL("select * from Flight_Use");
    printResult($test);
  }
  ?>
  </html>
