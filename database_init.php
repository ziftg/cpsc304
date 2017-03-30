<form method="POST" action="database_init.php">
   
<p><input type="submit" value="Reset" name="reset"></p>
</form>

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

// function printResult($result) { //prints results from a select statement
// 	echo "<br>Got data from table Flight_Use:<br>";
// 	echo "<table>";
// 	echo "<tr><th>Flight Number</th><th>Departure Date</th><th>AirCraft Serial Number</th>
// 			<th>Price</th><th>Arrival Airport</th><th>Departure Airport</th><th>ETD</th>
// 			<th>ETA</th><th>ATD</th><th>ATA</th><th>Number of Passengers</th></tr>";
//
// 	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
// 		echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] .
// 				"</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] .
// 				"</td><td>" . $row[6] . "</td><td>" . $row[7] .
// 				"</td><td>" . $row[8] . "</td><td>" . $row[9] .
// 				"</td><td>" . $row[10] . "</td><td>"; //or just use "echo $row[0]"
// 	}
// 	echo "</table>";
//
// }

function printResult($result) { //prints results from a select statement
	echo "<br>Got data from table customerservice:<br>";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th><th>password</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["EMPLOYNUMBER"] . "</td><td>" . $row["NAME"] ."</td><td>". $row["PASSWORD"] ."</td></tr>"; //or just use "echo $row[0]"
	}
	echo "</table>";

}

function printResultob($result) { //prints results from a select statement
	echo "<br>Got data from table onboardstaff:<br>";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th><th>password</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["EMPLOYNUMBER"] . "</td><td>" . $row["NAME"] ."</td><td>". $row["PASSWORD"] ."</td><td>" . $row["ROLE"]. "</td></tr>"; //or just use "echo $row[0]"
	}
	echo "</table>";

}

function printResultwi($result) { //prints results from a select statement
	echo "<br>Got data from table workin:<br>";
	echo "<table>";
	echo "<tr><th>ID</th><th>Name</th><th>password</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["EMPLOYNUMBER"] . "</td><td>" . $row["FLIGHTNUMBER"] ."</td><td>". $row["DATEORG"] . "</td></tr>"; //or just use "echo $row[0]"
	}
	echo "</table>";
}

function printResultp($result) { //prints results from a select statement
	echo "<br>Got data from table workin:<br>";
	echo "<table>";
	echo "<tr><th>UserID</th><th>TicketID</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row["USERID"] . "</td><td>" . $row["TICKETID"] ."</td><tr>"; //or just use "echo $row[0]"
	}
	echo "</table>";
}

// Connect Oracle...
if ($db_conn) {

    if (array_key_exists('reset', $_POST)) {
  	// Drop old table...
  		//echo "<br> dropping AirCraft table <br>";
  		executePlainSQL("Drop table member_serve");
  		executePlainSQL("Drop table ticket_has");
      executePlainSQL("Drop table workin");
       executePlainSQL("Drop table Flight_Use");
       executePlainSQL("drop table purchase");
      executePlainSQL("Drop table onboardstaff");
      executePlainSQL("drop table customerservice");
      executePlainSQL("Drop table AirCraft");

  		//echo "<br> creating AirCraft table<br>";
  		executePlainSQL("create table AirCraft (
                          serialNo varchar2(8),
  												type varchar2(14),
  												capacity int,
  												primary key (serialNo))");
  		// echo "<br> creating Flight table <br>";
      executePlainSQL("create table customerservice(
                          employNumber int not null,
                          name varchar2(50) not null,
                          password varchar2(30) not null,
                          primary key(employNumber)
                          )");
      executePlainSQL("create table onboardstaff(
                          employNumber int not null,
                          name varchar2(100) not null,
                          password varchar2(30) not null,
                          role varchar2(30) not null,
                          primary key(employNumber)
                          )");
      executePlainSQL("create table purchase(
                          userid varchar2(20) not null,
                          ticketid varchar2(20) not null,
                          primary key(userid,ticketid))");

  		executePlainSQL(  "create table Flight_Use (
                            flightNumber varchar2(6),
  													departureDate varchar2(10),
  													aircraftSerialNo varchar2(8),
  													ticketPrice int,
  													arrivalAirport varchar2(3),
  													departureAirport varchar2(3),
  													ETD timestamp,
  													ETA timestamp,
  													ATD timestamp,
  													ATA timestamp,
  													numOfPassengers int,
  													primary key (flightNumber, departureDate),
  													foreign key (aircraftSerialNo) references AirCraft
                            )");
      
      executePlainSQL("create table workin(
                          employNumber int not null,
                          flightNumber varchar2(6) not null,
                          dateorg varchar2(10) not null,
                          primary key(employNumber,flightNumber,dateorg),
                          foreign key (employNumber) references onboardstaff
                          ON DELETE CASCADE,
                          foreign key(flightNumber, dateorg) references 
                          Flight_Use ON DELETE CASCADE)
                          ");
      
      executePlainSQL("create table ticket_has(
                          ticketID    int,
                          ticketPrice   int,
                          passportNumber    varchar2(10),
                          flightNumber  varchar2(6) not null,
                          dateorg     varchar2(10) not null,
                          primary key (ticketID),
                          foreign key(flightNumber,dateorg) references Flight_Use
                          ON DELETE CASCADE)");
      executePlainSQL("create table member_serve(
                          userid    varchar2(30),
                          password  varchar2(30),
                          gender    varchar2(20),
                          emailAddress  varchar2(50),
                          passportNum  varchar2(30),
                          nationality  varchar2(50),
                          dob       varchar2(10),
                          name      varchar2(100),
                          employNumber  int,
                          primary key   (userid),
                          foreign key (employNumber) references customerservice ON DELETE CASCADE
                          )");
  		//OCICommit($db_conn);

  		$a1 = array (
  			":sno" => "51-11001",
  			":type" => "Airbus 320-300",
  			":cap" => 172
  		);
  		$a2 = array (
  			":sno" => "51-11002",
  			":type" => "Airbus 330-200",
  			":cap" => 248
  		);
  		$a3 = array (
  			":sno" => "51-11003",
  			":type" => "Airbus 380-200",
  			":cap" => 522
  		);
  		$a4 = array (
  			":sno" => "51-11004",
  			":type" => "Boeing 737-300",
  			":cap" => 186
  		);
  		$a5 = array (
  			":sno" => "51-11005",
  			":type" => "Boeing 787-900",
  			":cap" => 334
  		);
  		$allA = array (
  			$a1,
  			$a2,
  			$a3,
  			$a4,
  			$a5
  		);
  		executeBoundSQL("insert into AirCraft values (:sno, :type, :cap)", $allA);
  		OCICommit($db_conn);

  		executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
  		$a1 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-03-20",
  			":sno" => "51-11001",
  			":price" => 415,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "20-MAR-2017 13:20:00",
  			":ETA" => "20-MAR-2017 15:30:00",
  			":ATD" => "20-MAR-2017 13:28:34",
  			":ATA" => "20-MAR-2017 15:29:17",
  			":nop" => 168
  		);

  		$a2 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-03-22",
  			":sno" => "51-11001",
  			":price" => 432,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "22-MAR-2017 17:25:00",
  			":ETA" => "22-MAR-2017 19:50:00",
  			":ATD" => "22-MAR-2017 17:43:13",
  			":ATA" => "22-MAR-2017 19:58:41",
  			":nop" => 155
  		);

  		$a3 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-03-24",
  			":sno" => "51-11002",
  			":price" => 398,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "24-MAR-2017 13:20:00",
  			":ETA" => "24-MAR-2017 15:30:00",
  			":ATD" => "24-MAR-2017 13:33:16",
  			":ATA" => "24-MAR-2017 15:42:56",
  			":nop" => 226
  		);

  		$a4 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-03-25",
  			":sno" => "51-11002",
  			":price" => 409,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "25-MAR-2017 17:25:00",
  			":ETA" => "25-MAR-2017 19:50:00",
  			":ATD" => "25-MAR-2017 17:18:44",
  			":ATA" => "25-MAR-2017 19:47:11",
  			":nop" => 239
  		);

  		$a5 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-03-27",
  			":sno" => "51-11003",
  			":price" => 418,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "27-MAR-2017 13:20:00",
  			":ETA" => "27-MAR-2017 15:30:00",
  			":ATD" => "27-MAR-2017 13:47:22",
  			":ATA" => "27-MAR-2017 15:49:43",
  			":nop" => 487
  		);

  		$a6 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-03-29",
  			":sno" => "51-11003",
  			":price" => 433,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "29-MAR-2017 17:25:00",
  			":ETA" => "29-MAR-2017 19:50:00",
  			":ATD" => "29-MAR-2017 17:22:46",
  			":ATA" => "29-MAR-2017 19:45:31",
  			":nop" => 502
  		);

  		$a7 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-03-31",
  			":sno" => "51-11004",
  			":price" => 399,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "31-MAR-2017 13:20:00",
  			":ETA" => "31-MAR-2017 15:30:00",
  			":ATD" => "31-MAR-2017 13:20:34",
  			":ATA" => "31-MAR-2017 15:27:49",
  			":nop" => 179
  		);

  		$a8 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-01",
  			":sno" => "51-11004",
  			":price" => 432,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "01-APR-2017 17:25:00",
  			":ETA" => "01-APR-2017 19:50:00",
  			":ATD" => "01-APR-2017 17:27:21",
  			":ATA" => "01-APR-2017 19:50:03",
  			":nop" => 182
  		);

  		$a9 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-04-03",
  			":sno" => "51-11005",
  			":price" => 432,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "03-APR-2017 13:20:00",
  			":ETA" => "03-APR-2017 15:30:00",
  			":ATD" => "03-APR-2017 13:28:44",
  			":ATA" => "03-APR-2017 15:33:06",
  			":nop" => 328
  		);
  		$a10 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-05",
  			":sno" => "51-11005",
  			":price" => 480,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "05-APR-2017 17:25:00",
  			":ETA" => "05-APR-2017 19:50:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 261
  		);

  		$a11 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-04-07",
  			":sno" => "51-11001",
  			":price" => 480,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "07-APR-2017 13:20:00",
  			":ETA" => "07-APR-2017 15:30:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 143
  		);
  		$a12 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-08",
  			":sno" => "51-11001",
  			":price" => 470,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "08-APR-2017 17:25:00",
  			":ETA" => "08-APR-2017 19:50:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 122
  		);

  		$a13 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-04-10",
  			":sno" => "51-11002",
  			":price" => 470,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "10-APR-2017 13:20:00",
  			":ETA" => "10-APR-2017 15:30:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 178
  		);
  		$a14 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-12",
  			":sno" => "51-11002",
  			":price" => 470,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "12-APR-2017 17:25:00",
  			":ETA" => "12-APR-2017 19:50:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 199
  		);

  		$a15 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-04-14",
  			":sno" => "51-11003",
  			":price" => 470,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "14-APR-2017 13:20:00",
  			":ETA" => "14-APR-2017 15:30:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 201
  		);
  		$a16 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-15",
  			":sno" => "51-11003",
  			":price" => 425,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "15-APR-2017 17:25:00",
  			":ETA" => "15-APR-2017 19:50:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 182
  		);

  		$a17 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-04-17",
  			":sno" => "51-11004",
  			":price" => 425,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "17-APR-2017 13:20:00",
  			":ETA" => "17-APR-2017 15:30:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 32
  		);
  		$a18 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-19",
  			":sno" => "51-11004",
  			":price" => 425,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "19-APR-2017 17:25:00",
  			":ETA" => "19-APR-2017 19:50:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 45
  		);

  		$a19 = array (
  			":fn" => "EF1001",
  			":dDate" => "2017-04-21",
  			":sno" => "51-11005",
  			":price" => 425,
  			":aApt" => "SFO",
  			":dApt" => "YVR",
  			":ETD" => "21-APR-2017 13:20:00",
  			":ETA" => "21-APR-2017 15:30:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 52
  		);
  		$a20 = array (
  			":fn" => "EF1002",
  			":dDate" => "2017-04-22",
  			":sno" => "51-11005",
  			":price" => 425,
  			":aApt" => "YVR",
  			":dApt" => "SFO",
  			":ETD" => "22-APR-2017 17:25:00",
  			":ETA" => "22-APR-2017 19:50:00",
  			":ATD" => NULL,
  			":ATA" => NULL,
  			":nop" => 28
  		);

  		$allA = array (
  			$a1,
  			$a2,
  			$a3,
  			$a4,
  			$a5,
  			$a6,
  			$a7,
  			$a8,
  			$a9,
  			$a10,
  			$a11,
  			$a12,
  			$a13,
  			$a14,
  			$a15,
  			$a16,
  			$a17,
  			$a18,
  			$a19,
  			$a20
  		);
  		executeBoundSQL("insert into Flight_Use values (:fn, :dDate, :sno, :price, :aApt, :dApt, :ETD, :ETA, :ATD, :ATA, :nop)", $allA);

executePlainSQL(
"insert into customerservice values('1001', 'Amanda Smith','amanda1001')");
executePlainSQL(
"insert into customerservice values('1002', 'Paul Jones','paul1002')");
executePlainSQL(
"insert into customerservice values('1003', 'Daniel Radcliffee','daniel1003')");
 executePlainSQL(
"insert into customerservice values('1004', 'Tom Felton','tom1004')");
 executePlainSQL(
"insert into customerservice values('1005', 'Chris Brown','chris1005')");
 executePlainSQL(
"insert into customerservice values('1006', 'Alice Stone','alice1006')");
 executePlainSQL(
"insert into customerservice values('1007', 'Charlie Smith','charlie1007')");
 executePlainSQL(
"insert into customerservice values('1008', 'Peter Liu','peter1008')");
 executePlainSQL(
"insert into customerservice values('1009', 'Lily Potter','lily1009')");
 executePlainSQL(
"insert into customerservice values('1010', 'Jinny Potter','jinny1010')");
 executePlainSQL(
"insert into customerservice values('1011', 'Hua Li','hua1011')");
 executePlainSQL(
"insert into customerservice values('1012', 'Suzy Elizabeth','suzy1012')");
 executePlainSQL(
"insert into customerservice values('1013', 'Hazel Xiang','hazel1013')");
 executePlainSQL(
"insert into customerservice values('1014', 'Yang Li','yang1014')");
 executePlainSQL(
"insert into customerservice values('1015', 'Louis Wang','louis1015')");
 executePlainSQL(
"insert into customerservice values('1016', 'Kevin Liu','kevin1016')");
 executePlainSQL(
"insert into customerservice values('1017', 'Charlotte Zhen','charlotte1017')");
 executePlainSQL(
"insert into customerservice values('1018', 'Annie Lee','annie1018')");
 executePlainSQL(
"insert into customerservice values('1019', 'Leo Driedger','leo1019')");
 executePlainSQL(
"insert into customerservice values('1020', 'Leo Xia','leo1020')");
 executePlainSQL(
"insert into customerservice values('1021', 'Adagio Liu','adagio1021')"
);

// executePlainSQL("Drop table onboardstaff");


executePlainSQL(
"insert into onboardstaff
values('2001','Jon Snow','jon2001','pilot')");
executePlainSQL("
insert into onboardstaff
values('2002','Benedict Cumberbatch','benedict2002','pilot')");
executePlainSQL("insert into onboardstaff
values('2003','Taylor Moore','taylor2003','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2004','Justin Brown','justin2004','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2005','William Wallace','william2005','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2006','Taylor Lee','taylor2006','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2007','Wills Brown','wills2007','engineer')");
executePlainSQL("
insert into onboardstaff
values('2008','Amanda Kun','amanda2008','pilot')");
executePlainSQL("
insert into onboardstaff
values('2009','Jannie Kenney','jannie2009','pilot')");
executePlainSQL("
insert into onboardstaff
values('2010','Felipe Ollison','felipe2010','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2011','Shani Toews','shani2011','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2012','Mallory Radney','mallory2012','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2013','Yelena Bissette ','yelena2013','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2014','Keitha Fellers','keitha2014','engineer')");
executePlainSQL("
insert into onboardstaff
values('2015','Daryl Erben','daryl2015','pilot')");
executePlainSQL("
insert into onboardstaff
values('2016','Lily Fang','lily2016','pilot')");
executePlainSQL("
insert into onboardstaff
values('2017','Yiran Wang','yiran2017','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2018','Paula Loaiza','paula2018','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2019','Jamie Saylors','jamie2019','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2020','Jed Carlsen','jed2020','cabincrew')");
executePlainSQL("
insert into onboardstaff
values('2021','Sihan Wang','sihan2021','engineer')");



executePlainSQL("
insert into workin
values('2001','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2002','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2003','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2004','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2005','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2006','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2007','EF1001','2017-03-20')");
executePlainSQL("
insert into workin
values('2008','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2009','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2010','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2011','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2012','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2013','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2014','EF1002','2017-03-22')");
executePlainSQL("
insert into workin
values('2015','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2016','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2017','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2018','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2019','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2020','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2021','EF1001','2017-03-24')");
executePlainSQL("
insert into workin
values('2001','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2002','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2003','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2004','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2005','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2006','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2007','EF1002','2017-03-25')");
executePlainSQL("
insert into workin
values('2008','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2009','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2010','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2011','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2012','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2013','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2014','EF1001','2017-03-27')");
executePlainSQL("
insert into workin
values('2015','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2016','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2017','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2018','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2019','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2020','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2021','EF1002','2017-03-29')");
executePlainSQL("
insert into workin
values('2001','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2002','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2003','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2004','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2005','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2006','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2007','EF1001','2017-03-31')");
executePlainSQL("
insert into workin
values('2008','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2009','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2010','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2011','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2012','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2013','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2014','EF1002','2017-04-01')");
executePlainSQL("
insert into workin
values('2015','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2016','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2017','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2018','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2019','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2020','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2021','EF1001','2017-04-03')");
executePlainSQL("
insert into workin
values('2001','EF1002','2017-04-05')");
executePlainSQL("
insert into workin
values('2002','EF1002','2017-04-05')");
executePlainSQL("
insert into workin
values('2003','EF1002','2017-04-05')");

executePlainSQL("
insert into workin
values('2004','EF1002','2017-04-05')");
executePlainSQL("

insert into workin
values('2005','EF1002','2017-04-05')");
executePlainSQL("

insert into workin
values('2006','EF1002','2017-04-05')");

executePlainSQL("
insert into workin
values('2007','EF1002','2017-04-05')");
executePlainSQL("
insert into workin
values('2008','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2009','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2010','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2011','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2012','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2013','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2014','EF1001','2017-04-07')");
executePlainSQL("
insert into workin
values('2015','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2016','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2017','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2018','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2019','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2020','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2021','EF1002','2017-04-08')");
executePlainSQL("
insert into workin
values('2001','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2002','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2003','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2004','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2005','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2006','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2007','EF1001','2017-04-10')");
executePlainSQL("
insert into workin
values('2008','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2009','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2010','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2011','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2012','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2013','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2014','EF1002','2017-04-12')");
executePlainSQL("
insert into workin
values('2015','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2016','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2017','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2018','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2019','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2020','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2021','EF1001','2017-04-14')");
executePlainSQL("
insert into workin
values('2001','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2002','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2003','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2004','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2005','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2006','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2007','EF1002','2017-04-15')");
executePlainSQL("
insert into workin
values('2008','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2009','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2010','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2011','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2012','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2013','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2014','EF1001','2017-04-17')");
executePlainSQL("
insert into workin
values('2015','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2016','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2017','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2018','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2019','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2020','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2021','EF1002','2017-04-19')");
executePlainSQL("
insert into workin
values('2001','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2002','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2003','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2004','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2005','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2006','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2007','EF1001','2017-04-21')");
executePlainSQL("
insert into workin
values('2008','EF1002','2017-04-22')");
executePlainSQL("
insert into workin
values('2009','EF1002','2017-04-22')");
executePlainSQL("
insert into workin
values('2010','EF1002','2017-04-22')");
executePlainSQL("
insert into workin
values('2011','EF1002','2017-04-22')");
executePlainSQL("
insert into workin
values('2012','EF1002','2017-04-22')");
executePlainSQL("
insert into workin
values('2013','EF1002','2017-04-22')");
executePlainSQL("
insert into workin
values('2014','EF1002','2017-04-22')");


executePlainSQL("insert into purchase values('acd123','8382177546344')");

executePlainSQL("insert into purchase values('cdf123','2194387198102')");

executePlainSQL("insert into purchase values('pmf123','2348975981998')");

executePlainSQL("insert into purchase values('abc234','9837481923897')");

executePlainSQL("insert into purchase values('chf123','2848971298923')");

executePlainSQL("insert into purchase values('eqw143','3283042890234')");

executePlainSQL("insert into purchase values('sfg234','4285904839508')");

executePlainSQL("insert into purchase values('fwh134','2348590843423')");

executePlainSQL("insert into purchase values('hsf452','2340958934202')");

executePlainSQL("insert into purchase values('fwe983','9082937489298')");

executePlainSQL("insert into purchase values('dkh242','2894198798429')");

executePlainSQL("insert into purchase values('wfi234','1203849284739')");

executePlainSQL("insert into purchase values('hjk983','1234080132347')");
executePlainSQL("insert into purchase values('whe452','1230489312798')");

executePlainSQL("insert into purchase values('fjk234','2938237498293')");

executePlainSQL("insert into purchase values('fwe452','2394819739848')");

executePlainSQL("insert into purchase values('wre343','8021843324897')");

executePlainSQL("insert into purchase values('fdf534','1324803812041')");



executePlainSQL("insert into ticket_has values('8382177546344','400','WO1029387','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('2194387198102','200','AK1827392','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('2348975981998','240','DU2984738','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('9837481923897','240','WI1992832','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('2848971298923','500','SE2301829','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('3283042890234','240','WU3229832','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('4285904839508','240','EQ2938473','EF1001','2017-03-20')");
executePlainSQL("insert into ticket_has values('2348590843423','240','SS2983432','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('2340958934202','335','SI2938293','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('2092429837843','240','SJ9283728','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('9082937489298','240','DU2938293','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('2894198798429','240','DF123212','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('1203849284739','219','XF1232123','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('1234080132347','338','CD1234123','EF1002','2017-03-22')");
executePlainSQL("insert into ticket_has values('1230489312798','178','CD2231234','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('2938237498293','567','CD1232123','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('2394819739848','240','AS1230822','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('8021843324897','240','IE1829382','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('1324803812041','575','EC2839283','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('1239048018084','240','KQ5659620','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('2134809123800','383','TE2637788','EF1001','2017-03-24')");
executePlainSQL("insert into ticket_has values('1203840231843','240','KK8062753','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('2138409231804','240','YP5754670','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('1248031208323','240','OD5381468','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('4283408120348','240','VJ7214899','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('9283748947532','240','TG1670183','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('8497598285432','240','CE4256878','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('2374198542385','240','YF4020758','EF1002','2017-03-25')");
executePlainSQL("insert into ticket_has values('2482580943890','242','KU1982873','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('8478939837298','245','IJ3983234','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('4897239874293','240','IE2983098','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('3298749239872','245','SK2983234','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('1948082039284','240','JI2983483','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('9238742289372','200','WE2983453','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('3497859238297','200','IJ3982342','EF1001','2017-03-27')");
executePlainSQL("insert into ticket_has values('2058028409280','300','RW2423453','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('2319048029182','300','CE2094323','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('4328957893452','300','EW2094234','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('2459798248975','300','QA6613188','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('4378932578922','345','NH2938345','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('2389749873242','315','FG2933593','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('2895798347529','324','SF2933493','EF1002','2017-03-29')");
executePlainSQL("insert into ticket_has values('8978972924245','253','HG2348293','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('5798279824532','300','SD2648293','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('4329578932479','300','DF2755493','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('2345782430092','300','NG2938563','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('7624359793238','300','ER2933565','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('4235698732492','300','SD3456293','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('2439875982734','243','JH2745293','EF1001','2017-03-31')");
executePlainSQL("insert into ticket_has values('2342499023202','235','DF2456293','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('3942589234345','234','JH2956493','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('3245878432924','300','DF2934593','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('2345349280435','300','JG2936453','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('2435809342823','300','HG2933453','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('9809534280524','245','SD2348293','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('3428975893274','300','SG2343593','EF1002','2017-04-01')");
executePlainSQL("insert into ticket_has values('4239873429852','243','DG2956393','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('8798729345234','345','HG2945693','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('3427589734892','300','SD2945693','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('3427589723489','339','DF2934593','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('4238957349823','200','HG2934696','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('7897239482345','200','FG2933534','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('2340829938842','200','EI23432','EF1001','2017-04-03')");
executePlainSQL("insert into ticket_has values('2459834327589','200','HG3564593','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('2435789342732','200','SD2456393','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('5843728957892','200','HG3454693','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('2345984327985','200','DF2945693','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('5432098509342','200','GF2356593','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('3245803942852','200','SF5645393','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('3248578934792','200','SD3456593','EF1002','2017-04-05')");
executePlainSQL("insert into ticket_has values('5798324759823','240','FG2543493','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('8932475892342','240','AR6443293','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('2347598273243','240','RR7543293','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('5234850982430','240','WR4638293','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('2348590328422','240','WR9456983','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('2349589023489','240','SD2453332','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('8725498273455','240','TW3975642','EF1001','2017-04-07')");
executePlainSQL("insert into ticket_has values('2438759823472','205','ER9563984','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('5243502834092','205','SI2534432','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('2982982091832','205','SQ0263623','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('5432580843923','205','SQ2452322','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('5432524543923','205','SQ0263623','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('5432523443923','205','SQ0263623','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('5432580123923','205','SQ0263623','EF1002','2017-04-08')");
executePlainSQL("insert into ticket_has values('5432580213923','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('5432140843923','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('3232580843923','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('4532580843923','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('5434380843923','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('1234213412342','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('5432580234122','205','SQ0263623','EF1001','2017-04-10')");
executePlainSQL("insert into ticket_has values('5432513243923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('5432521342923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('5432582134923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('2342580843923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('5342330843923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('2345233343923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('5432435343923','250','SQ0263623','EF1002','2017-04-12')");
executePlainSQL("insert into ticket_has values('5432580234533','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('5432582345333','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('3425234233923','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('6345453423923','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('5234523453323','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('6352342324923','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('5432345234923','160','SQ0263623','EF1001','2017-04-14')");
executePlainSQL("insert into ticket_has values('6524352343923','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('3842435342282','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('2383245234012','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('2435234345842','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('6234432233998','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('2345323452923','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('5324532434234','160','SQ0263623','EF1002','2017-04-15')");
executePlainSQL("insert into ticket_has values('7563242234923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('2343453454923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('5452345323923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('5463454232923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('6345253453923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('5434536345923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('5433456453923','150','SQ0263623','EF1001','2017-04-17')");
executePlainSQL("insert into ticket_has values('2345345644923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('7563453345923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('2345324645923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('7456432532923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('9765363222923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('5436345634923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('2345345343923','200','SQ0263623','EF1002','2017-04-19')");
executePlainSQL("insert into ticket_has values('7654353453923','200','SQ0263623','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('5463434534923','200','SQ0263623','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('5432634563923','200','SQ0263623','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('5432580354633','200','SQ0263623','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('5443560843923','200','SQ4652323','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('5423580843923','200','SQ7456423','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('4325258084393','200','SQ856724','EF1001','2017-04-21')");
executePlainSQL("insert into ticket_has values('2345234084393','220','SQ2435464','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('6532453243923','220','SQ0546324','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('7542340843923','220','SQ2453434','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('7464334843923','220','SQ0746564','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('5432635643923','220','SQ2354233','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('5432588433453','220','SQ4562342','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('5425435643543','220','SQ2314892','EF1002','2017-04-22')");
executePlainSQL("insert into ticket_has values('3459827938294','220','WO1029387','EF1002','2017-04-23')");
executePlainSQL("insert into ticket_has values('3234879852739','234','WO1029387','EF1003','2017-04-24')");
executePlainSQL("insert into ticket_has values('6453098482303','534','WO1029387','EF1004','2017-04-25')");
executePlainSQL("insert into ticket_has values('3457298345798','345','WO1029387','EF1005','2017-04-26')");
executePlainSQL("insert into ticket_has values('2534798792348','453','WO1029387','EF1006','2017-04-27')");	    

      //OCICommit($db_conn);


executePlainSQL("insert into member_serve values('acd123','cnoad','M','nbcsja@gmail.com','WO1029387','China',
'1982-09-01','Hua Li','1012')");

executePlainSQL("insert into member_serve values('cc666','123456','M','cc666@gmail.com','AA4646464','US',
'1988-05-16','Avatar Chen','1012')");

executePlainSQL("insert into member_serve values('cdf123','pnvae','F','novad@gmail.com','AK1827392','US',
'1978-02-01','Adele Smith','1013')");

executePlainSQL("insert into member_serve values('pmf123','nvaen','F','ncaedv@gmail.com','DU2984738','UK',
'1996-02-21','Emma Waston','1014')");

executePlainSQL("insert into member_serve values('abc234','vnpanvkef','F','ncoia@gmail.com','WI1992832','France',
'1976-03-03','Taylor Evans','1015')");

executePlainSQL("insert into member_serve values('chf133','Nvafdkl','M','nvoiae@gmail.com','SE2301829','Canada',
'1999-01-01','Harry Evans','1016')");

executePlainSQL("insert into member_serve values('eqw143','qweruo','M','dsfoui@gmail.com','WU3229832','China',
'1982-02-09','Tameika Joly','1017')");

executePlainSQL("insert into member_serve values('sfg234','qwufuo','M','wqeiy@gmail.com','EQ2938473','US',
'1936-09-12','Karena Mcclaskey','1018')");

executePlainSQL("insert into member_serve values('fwh134','qewryow','M','ewqroi@gmail.com','SS2983432','Canada',
'1997-12-23','Dione Ammons','1019')");

executePlainSQL("insert into member_serve values('hsf452','qerywe','M','qwehfi@gmail.com','SI2938293','China',
'1989-05-23','Shae Fitton','1020')");
 OCICommit($db_conn);


      //executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
    	// $test = executePlainSQL("select * from Flight_Use");
      // $things = executePlainSQL("select * from customerservice");
      //$work = executePlainSQL("select * from workin");
    	// printResult($things);
      //$onboardstaff = executePlainSQL("select * from onboardstaff");
      // printResultwi($work);
      // printResultob($onboardstaff);
      // printResultp($fuck);

} 
}

/* OCILogon() allows you to log onto the Oracle database
     The three arguments are the username, password, and database
     You will need to replace "username" and "password" for this to
     to work.
     all strings that start with "$" are variables; they are created
     implicitly by appearing on the left hand side of an assignment
     statement */

/* OCIParse() Prepares Oracle statement for execution
      The two arguments are the connection and SQL query. */
/* OCIExecute() executes a previously parsed statement
      The two arguments are the statement which is a valid OCI
      statement identifier, and the mode.
      default mode is OCI_COMMIT_ON_SUCCESS. Statement is
      automatically committed after OCIExecute() call when using this
      mode.
      Here we use OCI_DEFAULT. Statement is not committed
      automatically when using this mode */

/* OCI_Fetch_Array() Returns the next row from the result data as an
     associative or numeric array, or both.
     The two arguments are a valid OCI statement identifier, and an
     optinal second parameter which can be any combination of the
     following constants:

     OCI_BOTH - return an array with both associative and numeric
     indices (the same as OCI_ASSOC + OCI_NUM). This is the default
     behavior.
     OCI_ASSOC - return an associative array (as OCI_Fetch_Assoc()
     works).
     OCI_NUM - return a numeric array, (as OCI_Fetch_Row() works).
     OCI_RETURN_NULLS - create empty elements for the NULL fields.
     OCI_RETURN_LOBS - return the value of a LOB of the descriptor.
     Default mode is OCI_BOTH.  */
?>
