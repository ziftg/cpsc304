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
  		echo "<br> dropping AirCraft table <br>";
  		executePlainSQL("Drop table Flight_Use");
  		executePlainSQL("Drop table AirCraft");
      executePlainSQL("Drop table workin");
      executePlainSQL("Drop table onboardstaff");
      executePlainSQL("drop table customerservice");
      executePlainSQL("Drop table purchase");

  		echo "<br> creating AirCraft table<br>";
  		executePlainSQL("create table AirCraft (serialNo varchar2(8),
  												type varchar2(14),
  												capacity int,
  												primary key (serialNo))");
  		echo "<br> creating Flight table <br>";
  		executePlainSQL("create table Flight_Use (flightNo varchar2(6),
  													departureDate varchar2(10),
  													aircraftSerialNo varchar2(8),
  													ticketPrice number,
  													arrivalAirport varchar2(3),
  													departureAirport varchar2(3),
  													ETD timestamp,
  													ETA timestamp,
  													ATD timestamp,
  													ATA timestamp,
  													numOfPassengers int,
  													primary key (flightNo, departureDate),
  													foreign key (aircraftSerialNo) references AirCraft)");
  		OCICommit($db_conn);

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

executePlainSQL("create table customerservice(
employNumber int not null,
name varchar2(50) not null,
password varchar2(30) not null,
primary key(employNumber)
)");

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
executePlainSQL("create table onboardstaff(
employNumber int not null,
name varchar2(100) not null,
password varchar2(30) not null,
role varchar2(30) not null,
primary key(employNumber)
)");

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

executePlainSQL("create table workin(
employNumber int not null,
flightNumber char(10) not null,
dateorg char(30) not null,
primary key(employNumber,flightNumber,dateorg),
foreign key (employNumber) references onboardstaff
ON DELETE CASCADE)");

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

executePlainSQL("create table purchase(
userid varchar2(20) not null,
ticketid varchar2(20) not null,
primary key(userid,ticketid))");

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

      OCICommit($db_conn);


      executePlainSQL("ALTER SESSION SET NLS_TIMESTAMP_FORMAT='DD-MON-YYYY HH24:MI:SS'");
    	// $test = executePlainSQL("select * from Flight_Use");
      // $things = executePlainSQL("select * from customerservice");
      $work = executePlainSQL("select * from workin");
    	// printResult($things);
      $onboardstaff = executePlainSQL("select * from onboardstaff");
      $fuck = executePlainSQL("select * from purchase");
      // printResultwi($work);
      // printResultob($onboardstaff);
      // printResultp($fuck);


	} else
		if (array_key_exists('insertsubmit', $_POST)) {
			//Getting the values from user and insert data into the table
			$tuple = array (
				":bind1" => $_POST['insNo'],
				":bind2" => $_POST['insName']
			);
			$alltuples = array (
				$tuple
			);
			executeBoundSQL("insert into tab1 values (:bind1, :bind2)", $alltuples);
			OCICommit($db_conn);

		} else
			if (array_key_exists('updatesubmit', $_POST)) {
				// Update tuple using data from user
				$tuple = array (
					":bind1" => $_POST['oldName'],
					":bind2" => $_POST['newName']
				);
				$alltuples = array (
					$tuple
				);
				executeBoundSQL("update tab1 set name=:bind2 where name=:bind1", $alltuples);
				OCICommit($db_conn);

			} else
				if (array_key_exists('dostuff', $_POST)) {
					// Insert data into table...
          echo "<br> creating new table </br>";
					executePlainSQL("insert into tab1 values (10, 'Frank')");
					// Inserting data into table using bound variables
					$list1 = array (
						":bind1" => 6,
						":bind2" => "All"
					);
					$list2 = array (
						":bind1" => 7,
						":bind2" => "John"
					);
					$allrows = array (
						$list1,
						$list2
					);
					executeBoundSQL("insert into tab1 values (:bind1, :bind2)", $allrows); //the function takes a list of lists
					// Update data...
					//executePlainSQL("update tab1 set nid=10 where nid=2");
					// Delete data...
					//executePlainSQL("delete from tab1 where nid=1");
					OCICommit($db_conn);
				}

	if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: oracle-test.php");
	} else {
		// Select data...
		$result = executePlainSQL("select * from tab1");
		// printResult($result);
	}

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
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
