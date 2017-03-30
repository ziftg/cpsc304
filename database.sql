Drop table member_serve;
Drop table ticket_has;
Drop table workin;
Drop table Flight_Use;
drop table purchase;
Drop table onboardstaff;
drop table customerservice;
Drop table AirCraft;

create table AirCraft (
  serialNo varchar2(8),
  type varchar2(14),
  capacity int,
  primary key (serialNo)
);

create table customerservice(
  employNumber int not null,
  name varchar2(50) not null,
  password varchar2(30) not null,
  primary key(employNumber)
);

create table onboardstaff(
  employNumber int not null,
  name varchar2(100) not null,
  password varchar2(30) not null,
  role varchar2(30) not null,
  check (role in ('cabincrew', 'engineer', 'pilot')),
  primary key(employNumber)
);

create table purchase(
  userid varchar2(20) not null,
  ticketid varchar2(20) not null,
  primary key(userid,ticketid)
);

create table Flight_Use (
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
);

create table workin(
  employNumber int not null,
  flightNumber varchar2(6) not null,
  dateorg varchar2(10) not null,
  primary key(employNumber,flightNumber,dateorg),
  foreign key (employNumber) references onboardstaff
  ON DELETE CASCADE,
  foreign key(flightNumber, dateorg) references 
  Flight_Use ON DELETE CASCADE
);

create table ticket_has(
  ticketID    int,
  ticketPrice   int,
  passportNumber    varchar2(10),
  flightNumber  varchar2(6) not null,
  dateorg     varchar2(10) not null,
  primary key (ticketID),
  foreign key(flightNumber,dateorg) references Flight_Use
  ON DELETE CASCADE
);

create table member_serve(
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
);

--insert data into AirCraft table
insert into AirCraft values ('51-11001', 'Airbus 320-300', 172;
insert into AirCraft values ('51-11002', 'Airbus 330-200', 172;
insert into AirCraft values ('51-11003', 'Airbus 380-200', 172;
insert into AirCraft values ('51-11004', 'Boeing 737-300', 172;
insert into AirCraft values ('51-11005', 'Boeing 787-900', 172;

--insert data into Flight_Use table
insert into Flight_Use values ('EF1001', '2017-03-20', '51-11001', 415, 'SFO', 'YVR', '20-MAR-2017 13:20:00', '20-MAR-2017 15:30:00', '20-MAR-2017 13:28:34', '20-MAR-2017 15:29:17', 168;
insert into Flight_Use values ('EF1002', '2017-03-22', '51-11001', 432, 'YVR', 'SFO', '22-MAR-2017 17:25:00', '22-MAR-2017 19:50:00', '22-MAR-2017 17:43:13', '22-MAR-2017 19:58:41', 155;
insert into Flight_Use values ('EF1001', '2017-03-24', '51-11002', 398, 'SFO', 'YVR', '24-MAR-2017 13:20:00', '24-MAR-2017 15:30:00', '24-MAR-2017 13:33:16', '24-MAR-2017 15:42:56', 226;
insert into Flight_Use values ('EF1002', '2017-03-25', '51-11002', 409, 'YVR', 'SFO', '25-MAR-2017 17:25:00', '25-MAR-2017 19:50:00', '25-MAR-2017 17:18:44', '25-MAR-2017 19:47:11', 239;
insert into Flight_Use values ('EF1001', '2017-03-27', '51-11003', 418, 'SFO', 'YVR', '27-MAR-2017 13:20:00', '27-MAR-2017 15:30:00', '27-MAR-2017 13:47:22', '27-MAR-2017 15:49:43', 487;
insert into Flight_Use values ('EF1002', '2017-03-29', '51-11003', 433, 'YVR', 'SFO', '29-MAR-2017 17:25:00', '29-MAR-2017 19:50:00', '29-MAR-2017 17:22:46', '29-MAR-2017 19:45:31', 502;
insert into Flight_Use values ('EF1001', '2017-03-31', '51-11004', 399, 'SFO', 'YVR', '31-MAR-2017 13:20:00', '31-MAR-2017 15:30:00', '31-MAR-2017 13:20:34', '31-MAR-2017 15:27:49', 179;
insert into Flight_Use values ('EF1002', '2017-04-01', '51-11004', 432, 'YVR', 'SFO', '01-APR-2017 17:25:00', '01-APR-2017 19:50:00', '01-APR-2017 17:27:21', '01-APR-2017 19:50:03', 182;
insert into Flight_Use values ('EF1001', '2017-04-03', '51-11005', 432, 'SFO', 'YVR', '03-APR-2017 13:20:00', '03-APR-2017 15:30:00', '03-APR-2017 13:28:44', '03-APR-2017 15:33:06', 328;
insert into Flight_Use values ('EF1002', '2017-04-05', '51-11005', 480, 'YVR', 'SFO', '05-APR-2017 17:25:00', '05-APR-2017 19:50:00', NULL, NULL, 261;
insert into Flight_Use values ('EF1001', '2017-04-07', '51-11001', 480, 'SFO', 'YVR', '07-APR-2017 13:20:00', '07-APR-2017 15:30:00', NULL, NULL, 143;
insert into Flight_Use values ('EF1002', '2017-04-08', '51-11001', 470, 'YVR', 'SFO', '08-APR-2017 17:25:00', '08-APR-2017 19:50:00', NULL, NULL, 122;
insert into Flight_Use values ('EF1001', '2017-04-10', '51-11002', 470, 'SFO', 'YVR', '10-APR-2017 13:20:00', '10-APR-2017 15:30:00', NULL, NULL, 178;
insert into Flight_Use values ('EF1002', '2017-04-12', '51-11002', 470, 'YVR', 'SFO', '12-APR-2017 17:25:00', '12-APR-2017 19:50:00', NULL, NULL, 199;
insert into Flight_Use values ('EF1001', '2017-04-14', '51-11003', 470, 'SFO', 'YVR', '14-APR-2017 13:20:00', '14-APR-2017 15:30:00', NULL, NULL, 201;
insert into Flight_Use values ('EF1002', '2017-04-15', '51-11003', 425, 'YVR', 'SFO', '15-APR-2017 17:25:00', '15-APR-2017 19:50:00', NULL, NULL, 182;
insert into Flight_Use values ('EF1001', '2017-04-17', '51-11004', 425, 'SFO', 'YVR', '17-APR-2017 13:20:00', '17-APR-2017 15:30:00', NULL, NULL, 32;
insert into Flight_Use values ('EF1002', '2017-04-19', '51-11004', 425, 'YVR', 'SFO', '19-APR-2017 17:25:00', '19-APR-2017 19:50:00', NULL, NULL, 45;
insert into Flight_Use values ('EF1001', '2017-04-21', '51-11005', 425, 'SFO', 'YVR', '21-APR-2017 13:20:00', '21-APR-2017 15:30:00', NULL, NULL, 52;
insert into Flight_Use values ('EF1002', '2017-04-22', '51-11005', 425, 'YVR', 'SFO', '22-APR-2017 17:25:00', '22-APR-2017 19:50:00', NULL, NULL, 28;

--insert data into customerservice table
insert into customerservice values('1001', 'Amanda Smith','amanda1001');
insert into customerservice values('1002', 'Paul Jones','paul1002');
insert into customerservice values('1003', 'Daniel Radcliffee','daniel1003');
insert into customerservice values('1004', 'Tom Felton','tom1004');
insert into customerservice values('1005', 'Chris Brown','chris1005');
insert into customerservice values('1006', 'Alice Stone','alice1006');
insert into customerservice values('1007', 'Charlie Smith','charlie1007');
insert into customerservice values('1008', 'Peter Liu','peter1008');
insert into customerservice values('1009', 'Lily Potter','lily1009');
insert into customerservice values('1010', 'Jinny Potter','jinny1010');
insert into customerservice values('1011', 'Hua Li','hua1011');
insert into customerservice values('1012', 'Suzy Elizabeth','suzy1012');
insert into customerservice values('1013', 'Hazel Xiang','hazel1013');
insert into customerservice values('1014', 'Yang Li','yang1014');
insert into customerservice values('1015', 'Louis Wang','louis1015');
insert into customerservice values('1016', 'Kevin Liu','kevin1016');
insert into customerservice values('1017', 'Charlotte Zhen','charlotte1017');
insert into customerservice values('1018', 'Annie Lee','annie1018');
insert into customerservice values('1019', 'Leo Driedger','leo1019');
insert into customerservice values('1020', 'Leo Xia','leo1020');
insert into customerservice values('1021', 'Adagio Liu','adagio1021');

--insert data into onboardstaff table
insert into onboardstaff values('2001','Jon Snow','jon2001','pilot');
insert into onboardstaff values('2002','Benedict Cumberbatch','benedict2002','pilot');
insert into onboardstaff values('2003','Taylor Moore','taylor2003','cabincrew');
insert into onboardstaff values('2004','Justin Brown','justin2004','cabincrew');
insert into onboardstaff values('2005','William Wallace','william2005','cabincrew');
insert into onboardstaff values('2006','Taylor Lee','taylor2006','cabincrew');
insert into onboardstaff values('2007','Wills Brown','wills2007','engineer');
insert into onboardstaff values('2008','Amanda Kun','amanda2008','pilot');
insert into onboardstaff values('2009','Jannie Kenney','jannie2009','pilot');
insert into onboardstaff values('2010','Felipe Ollison','felipe2010','cabincrew');
insert into onboardstaff values('2011','Shani Toews','shani2011','cabincrew');
insert into onboardstaff values('2012','Mallory Radney','mallory2012','cabincrew');
insert into onboardstaff values('2013','Yelena Bissette ','yelena2013','cabincrew');
insert into onboardstaff values('2014','Keitha Fellers','keitha2014','engineer');
insert into onboardstaff values('2015','Daryl Erben','daryl2015','pilot');
insert into onboardstaff values('2016','Lily Fang','lily2016','pilot');
insert into onboardstaff values('2017','Yiran Wang','yiran2017','cabincrew');
insert into onboardstaff values('2018','Paula Loaiza','paula2018','cabincrew');
insert into onboardstaff values('2019','Jamie Saylors','jamie2019','cabincrew');
insert into onboardstaff values('2020','Jed Carlsen','jed2020','cabincrew');
insert into onboardstaff values('2021','Sihan Wang','sihan2021','engineer');

--insert data into workin table
insert into workin values('2001','EF1001','2017-03-20');
insert into workin values('2002','EF1001','2017-03-20');
insert into workin values('2003','EF1001','2017-03-20');
insert into workin values('2004','EF1001','2017-03-20');
insert into workin values('2005','EF1001','2017-03-20');
insert into workin values('2006','EF1001','2017-03-20');
insert into workin values('2007','EF1001','2017-03-20');
insert into workin values('2008','EF1002','2017-03-22');
insert into workin values('2009','EF1002','2017-03-22');
insert into workin values('2010','EF1002','2017-03-22');
insert into workin values('2011','EF1002','2017-03-22');
insert into workin values('2012','EF1002','2017-03-22');
insert into workin values('2013','EF1002','2017-03-22');
insert into workin values('2014','EF1002','2017-03-22');
insert into workin values('2015','EF1001','2017-03-24');
insert into workin values('2016','EF1001','2017-03-24');
insert into workin values('2017','EF1001','2017-03-24');
insert into workin values('2018','EF1001','2017-03-24');
insert into workin values('2019','EF1001','2017-03-24');
insert into workin values('2020','EF1001','2017-03-24');
insert into workin values('2021','EF1001','2017-03-24');
insert into workin values('2001','EF1002','2017-03-25');
insert into workin values('2002','EF1002','2017-03-25');
insert into workin values('2003','EF1002','2017-03-25');
insert into workin values('2004','EF1002','2017-03-25');
insert into workin values('2005','EF1002','2017-03-25');
insert into workin values('2006','EF1002','2017-03-25');
insert into workin values('2007','EF1002','2017-03-25');
insert into workin values('2008','EF1001','2017-03-27');
insert into workin values('2009','EF1001','2017-03-27');
insert into workin values('2010','EF1001','2017-03-27');
insert into workin values('2011','EF1001','2017-03-27');
insert into workin values('2012','EF1001','2017-03-27');
insert into workin values('2013','EF1001','2017-03-27');
insert into workin values('2014','EF1001','2017-03-27');
insert into workin values('2015','EF1002','2017-03-29');
insert into workin values('2016','EF1002','2017-03-29');
insert into workin values('2017','EF1002','2017-03-29');
insert into workin values('2018','EF1002','2017-03-29');
insert into workin values('2019','EF1002','2017-03-29');
insert into workin values('2020','EF1002','2017-03-29');
insert into workin values('2021','EF1002','2017-03-29');
insert into workin values('2001','EF1001','2017-03-31');
insert into workin values('2002','EF1001','2017-03-31');
insert into workin values('2003','EF1001','2017-03-31');
insert into workin values('2004','EF1001','2017-03-31');
insert into workin values('2005','EF1001','2017-03-31');
insert into workin values('2006','EF1001','2017-03-31');
insert into workin values('2007','EF1001','2017-03-31');
insert into workin values('2008','EF1002','2017-04-01');
insert into workin values('2009','EF1002','2017-04-01');
insert into workin values('2010','EF1002','2017-04-01');
insert into workin values('2011','EF1002','2017-04-01');
insert into workin values('2012','EF1002','2017-04-01');
insert into workin values('2013','EF1002','2017-04-01');
insert into workin values('2014','EF1002','2017-04-01');
insert into workin values('2015','EF1001','2017-04-03');
insert into workin values('2016','EF1001','2017-04-03');
insert into workin values('2017','EF1001','2017-04-03');
insert into workin values('2018','EF1001','2017-04-03');
insert into workin values('2019','EF1001','2017-04-03');
insert into workin values('2020','EF1001','2017-04-03');
insert into workin values('2021','EF1001','2017-04-03');
insert into workin values('2001','EF1002','2017-04-05');
insert into workin values('2002','EF1002','2017-04-05');
insert into workin values('2003','EF1002','2017-04-05');
insert into workin values('2004','EF1002','2017-04-05');
insert into workin values('2005','EF1002','2017-04-05');
insert into workin values('2006','EF1002','2017-04-05');
insert into workin values('2007','EF1002','2017-04-05');
insert into workin values('2008','EF1001','2017-04-07');
insert into workin values('2009','EF1001','2017-04-07');
insert into workin values('2010','EF1001','2017-04-07');
insert into workin values('2011','EF1001','2017-04-07');
insert into workin values('2012','EF1001','2017-04-07');
insert into workin values('2013','EF1001','2017-04-07');
insert into workin values('2014','EF1001','2017-04-07');
insert into workin values('2015','EF1002','2017-04-08');
insert into workin values('2016','EF1002','2017-04-08');
insert into workin values('2017','EF1002','2017-04-08');
insert into workin values('2018','EF1002','2017-04-08');
insert into workin values('2019','EF1002','2017-04-08');
insert into workin values('2020','EF1002','2017-04-08');
insert into workin values('2021','EF1002','2017-04-08');
insert into workin values('2001','EF1001','2017-04-10');
insert into workin values('2002','EF1001','2017-04-10');
insert into workin values('2003','EF1001','2017-04-10');
insert into workin values('2004','EF1001','2017-04-10');
insert into workin values('2005','EF1001','2017-04-10');
insert into workin values('2006','EF1001','2017-04-10');
insert into workin values('2007','EF1001','2017-04-10');
insert into workin values('2008','EF1002','2017-04-12');
insert into workin values('2009','EF1002','2017-04-12');
insert into workin values('2010','EF1002','2017-04-12');
insert into workin values('2011','EF1002','2017-04-12');
insert into workin values('2012','EF1002','2017-04-12');
insert into workin values('2013','EF1002','2017-04-12');
insert into workin values('2014','EF1002','2017-04-12');
insert into workin values('2015','EF1001','2017-04-14');
insert into workin values('2016','EF1001','2017-04-14');
insert into workin values('2017','EF1001','2017-04-14');
insert into workin values('2018','EF1001','2017-04-14');
insert into workin values('2019','EF1001','2017-04-14');
insert into workin values('2020','EF1001','2017-04-14');
insert into workin values('2021','EF1001','2017-04-14');
insert into workin values('2001','EF1002','2017-04-15');
insert into workin values('2002','EF1002','2017-04-15');
insert into workin values('2003','EF1002','2017-04-15');
insert into workin values('2004','EF1002','2017-04-15');
insert into workin values('2005','EF1002','2017-04-15');
insert into workin values('2006','EF1002','2017-04-15');
insert into workin values('2007','EF1002','2017-04-15');
insert into workin values('2008','EF1001','2017-04-17');
insert into workin values('2009','EF1001','2017-04-17');
insert into workin values('2010','EF1001','2017-04-17');
insert into workin values('2011','EF1001','2017-04-17');
insert into workin values('2012','EF1001','2017-04-17');
insert into workin values('2013','EF1001','2017-04-17');
insert into workin values('2014','EF1001','2017-04-17');
insert into workin values('2015','EF1002','2017-04-19');
insert into workin values('2016','EF1002','2017-04-19');
insert into workin values('2017','EF1002','2017-04-19');
insert into workin values('2018','EF1002','2017-04-19');
insert into workin values('2019','EF1002','2017-04-19');
insert into workin values('2020','EF1002','2017-04-19');
insert into workin values('2021','EF1002','2017-04-19');
insert into workin values('2001','EF1001','2017-04-21');
insert into workin values('2002','EF1001','2017-04-21');
insert into workin values('2003','EF1001','2017-04-21');
insert into workin values('2004','EF1001','2017-04-21');
insert into workin values('2005','EF1001','2017-04-21');
insert into workin values('2006','EF1001','2017-04-21');
insert into workin values('2007','EF1001','2017-04-21');
insert into workin values('2008','EF1002','2017-04-22');
insert into workin values('2009','EF1002','2017-04-22');
insert into workin values('2010','EF1002','2017-04-22');
insert into workin values('2011','EF1002','2017-04-22');
insert into workin values('2012','EF1002','2017-04-22');
insert into workin values('2013','EF1002','2017-04-22');
insert into workin values('2014','EF1002','2017-04-22');

--insert data into purchase table
insert into purchase values('acd123','8382177546344');
insert into purchase values('cdf123','2194387198102');
insert into purchase values('pmf123','2348975981998');
insert into purchase values('abc234','9837481923897');
insert into purchase values('chf123','2848971298923');
insert into purchase values('eqw143','3283042890234');
insert into purchase values('sfg234','4285904839508');
insert into purchase values('fwh134','2348590843423');
insert into purchase values('hsf452','2340958934202');
insert into purchase values('fwe983','9082937489298');
insert into purchase values('dkh242','2894198798429');
insert into purchase values('wfi234','1203849284739');
insert into purchase values('hjk983','1234080132347');
insert into purchase values('whe452','1230489312798');
insert into purchase values('fjk234','2938237498293');
insert into purchase values('fwe452','2394819739848');
insert into purchase values('wre343','8021843324897');
insert into purchase values('fdf534','1324803812041');

--insert data into ticket_has table
insert into ticket_has values('8382177546344','400','WO1029387','EF1001','2017-03-20');
insert into ticket_has values('2194387198102','200','AK1827392','EF1001','2017-03-20');
insert into ticket_has values('2348975981998','240','DU2984738','EF1001','2017-03-20');
insert into ticket_has values('9837481923897','240','WI1992832','EF1001','2017-03-20');
insert into ticket_has values('2848971298923','500','SE2301829','EF1001','2017-03-20');
insert into ticket_has values('3283042890234','240','WU3229832','EF1001','2017-03-20');
insert into ticket_has values('4285904839508','240','EQ2938473','EF1001','2017-03-20');
insert into ticket_has values('2348590843423','240','SS2983432','EF1002','2017-03-22');
insert into ticket_has values('2340958934202','335','SI2938293','EF1002','2017-03-22');
insert into ticket_has values('2092429837843','240','SJ9283728','EF1002','2017-03-22');
insert into ticket_has values('9082937489298','240','DU2938293','EF1002','2017-03-22');
insert into ticket_has values('2894198798429','240','DF123212','EF1002','2017-03-22');
insert into ticket_has values('1203849284739','219','XF1232123','EF1002','2017-03-22');
insert into ticket_has values('1234080132347','338','CD1234123','EF1002','2017-03-22');
insert into ticket_has values('1230489312798','178','CD2231234','EF1001','2017-03-24');
insert into ticket_has values('2938237498293','567','CD1232123','EF1001','2017-03-24');
insert into ticket_has values('2394819739848','240','AS1230822','EF1001','2017-03-24');
insert into ticket_has values('8021843324897','240','IE1829382','EF1001','2017-03-24');
insert into ticket_has values('1324803812041','575','EC2839283','EF1001','2017-03-24');
insert into ticket_has values('1239048018084','240','KQ5659620','EF1001','2017-03-24');
insert into ticket_has values('2134809123800','383','TE2637788','EF1001','2017-03-24');
insert into ticket_has values('1203840231843','240','KK8062753','EF1002','2017-03-25');
insert into ticket_has values('2138409231804','240','YP5754670','EF1002','2017-03-25');
insert into ticket_has values('1248031208323','240','OD5381468','EF1002','2017-03-25');
insert into ticket_has values('4283408120348','240','VJ7214899','EF1002','2017-03-25');
insert into ticket_has values('9283748947532','240','TG1670183','EF1002','2017-03-25');
insert into ticket_has values('8497598285432','240','CE4256878','EF1002','2017-03-25');
insert into ticket_has values('2374198542385','240','YF4020758','EF1002','2017-03-25');
insert into ticket_has values('2482580943890','242','KU1982873','EF1001','2017-03-27');
insert into ticket_has values('8478939837298','245','IJ3983234','EF1001','2017-03-27');
insert into ticket_has values('4897239874293','240','IE2983098','EF1001','2017-03-27');
insert into ticket_has values('3298749239872','245','SK2983234','EF1001','2017-03-27');
insert into ticket_has values('1948082039284','240','JI2983483','EF1001','2017-03-27');
insert into ticket_has values('9238742289372','200','WE2983453','EF1001','2017-03-27');
insert into ticket_has values('3497859238297','200','IJ3982342','EF1001','2017-03-27');
insert into ticket_has values('2058028409280','300','RW2423453','EF1002','2017-03-29');
insert into ticket_has values('2319048029182','300','CE2094323','EF1002','2017-03-29');
insert into ticket_has values('4328957893452','300','EW2094234','EF1002','2017-03-29');
insert into ticket_has values('2459798248975','300','QA6613188','EF1002','2017-03-29');
insert into ticket_has values('4378932578922','345','NH2938345','EF1002','2017-03-29');
insert into ticket_has values('2389749873242','315','FG2933593','EF1002','2017-03-29');
insert into ticket_has values('2895798347529','324','SF2933493','EF1002','2017-03-29');
insert into ticket_has values('8978972924245','253','HG2348293','EF1001','2017-03-31');
insert into ticket_has values('5798279824532','300','SD2648293','EF1001','2017-03-31');
insert into ticket_has values('4329578932479','300','DF2755493','EF1001','2017-03-31');
insert into ticket_has values('2345782430092','300','NG2938563','EF1001','2017-03-31');
insert into ticket_has values('7624359793238','300','ER2933565','EF1001','2017-03-31');
insert into ticket_has values('4235698732492','300','SD3456293','EF1001','2017-03-31');
insert into ticket_has values('2439875982734','243','JH2745293','EF1001','2017-03-31');
insert into ticket_has values('2342499023202','235','DF2456293','EF1002','2017-04-01');
insert into ticket_has values('3942589234345','234','JH2956493','EF1002','2017-04-01');
insert into ticket_has values('3245878432924','300','DF2934593','EF1002','2017-04-01');
insert into ticket_has values('2345349280435','300','JG2936453','EF1002','2017-04-01');
insert into ticket_has values('2435809342823','300','HG2933453','EF1002','2017-04-01');
insert into ticket_has values('9809534280524','245','SD2348293','EF1002','2017-04-01');
insert into ticket_has values('3428975893274','300','SG2343593','EF1002','2017-04-01');
insert into ticket_has values('4239873429852','243','DG2956393','EF1001','2017-04-03');
insert into ticket_has values('8798729345234','345','HG2945693','EF1001','2017-04-03');
insert into ticket_has values('3427589734892','300','SD2945693','EF1001','2017-04-03');
insert into ticket_has values('3427589723489','339','DF2934593','EF1001','2017-04-03');
insert into ticket_has values('4238957349823','200','HG2934696','EF1001','2017-04-03');
insert into ticket_has values('7897239482345','200','FG2933534','EF1001','2017-04-03');
insert into ticket_has values('2340829938842','200','EI23432','EF1001','2017-04-03');
insert into ticket_has values('2459834327589','200','HG3564593','EF1002','2017-04-05');
insert into ticket_has values('2435789342732','200','SD2456393','EF1002','2017-04-05');
insert into ticket_has values('5843728957892','200','HG3454693','EF1002','2017-04-05');
insert into ticket_has values('2345984327985','200','DF2945693','EF1002','2017-04-05');
insert into ticket_has values('5432098509342','200','GF2356593','EF1002','2017-04-05');
insert into ticket_has values('3245803942852','200','SF5645393','EF1002','2017-04-05');
insert into ticket_has values('3248578934792','200','SD3456593','EF1002','2017-04-05');
insert into ticket_has values('5798324759823','240','FG2543493','EF1001','2017-04-07');
insert into ticket_has values('8932475892342','240','AR6443293','EF1001','2017-04-07');
insert into ticket_has values('2347598273243','240','RR7543293','EF1001','2017-04-07');
insert into ticket_has values('5234850982430','240','WR4638293','EF1001','2017-04-07');
insert into ticket_has values('2348590328422','240','WR9456983','EF1001','2017-04-07');
insert into ticket_has values('2349589023489','240','SD2453332','EF1001','2017-04-07');
insert into ticket_has values('8725498273455','240','TW3975642','EF1001','2017-04-07');
insert into ticket_has values('2438759823472','205','ER9563984','EF1002','2017-04-08');
insert into ticket_has values('5243502834092','205','SI2534432','EF1002','2017-04-08');
insert into ticket_has values('2982982091832','205','SQ0263623','EF1002','2017-04-08');
insert into ticket_has values('5432580843923','205','SQ2452322','EF1002','2017-04-08');
insert into ticket_has values('5432524543923','205','SQ0263623','EF1002','2017-04-08');
insert into ticket_has values('5432523443923','205','SQ0263623','EF1002','2017-04-08');
insert into ticket_has values('5432580123923','205','SQ0263623','EF1002','2017-04-08');
insert into ticket_has values('5432580213923','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('5432140843923','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('3232580843923','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('4532580843923','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('5434380843923','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('1234213412342','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('5432580234122','205','SQ0263623','EF1001','2017-04-10');
insert into ticket_has values('5432513243923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('5432521342923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('5432582134923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('2342580843923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('5342330843923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('2345233343923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('5432435343923','250','SQ0263623','EF1002','2017-04-12');
insert into ticket_has values('5432580234533','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('5432582345333','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('3425234233923','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('6345453423923','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('5234523453323','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('6352342324923','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('5432345234923','160','SQ0263623','EF1001','2017-04-14');
insert into ticket_has values('6524352343923','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('3842435342282','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('2383245234012','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('2435234345842','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('6234432233998','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('2345323452923','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('5324532434234','160','SQ0263623','EF1002','2017-04-15');
insert into ticket_has values('7563242234923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('2343453454923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('5452345323923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('5463454232923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('6345253453923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('5434536345923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('5433456453923','150','SQ0263623','EF1001','2017-04-17');
insert into ticket_has values('2345345644923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('7563453345923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('2345324645923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('7456432532923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('9765363222923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('5436345634923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('2345345343923','200','SQ0263623','EF1002','2017-04-19');
insert into ticket_has values('7654353453923','200','SQ0263623','EF1001','2017-04-21');
insert into ticket_has values('5463434534923','200','SQ0263623','EF1001','2017-04-21');
insert into ticket_has values('5432634563923','200','SQ0263623','EF1001','2017-04-21');
insert into ticket_has values('5432580354633','200','SQ0263623','EF1001','2017-04-21');
insert into ticket_has values('5443560843923','200','SQ4652323','EF1001','2017-04-21');
insert into ticket_has values('5423580843923','200','SQ7456423','EF1001','2017-04-21');
insert into ticket_has values('4325258084393','200','SQ856724','EF1001','2017-04-21');
insert into ticket_has values('2345234084393','220','SQ2435464','EF1002','2017-04-22');
insert into ticket_has values('6532453243923','220','SQ0546324','EF1002','2017-04-22');
insert into ticket_has values('7542340843923','220','SQ2453434','EF1002','2017-04-22');
insert into ticket_has values('7464334843923','220','SQ0746564','EF1002','2017-04-22');
insert into ticket_has values('5432635643923','220','SQ2354233','EF1002','2017-04-22');
insert into ticket_has values('5432588433453','220','SQ4562342','EF1002','2017-04-22');
insert into ticket_has values('5425435643543','220','SQ2314892','EF1002','2017-04-22');

--insert data into member_serve table
insert into member_serve values('acd123','cnoad','M','nbcsja@gmail.com','WO1029387','China', '1982-09-01','Hua Li','1012');
insert into member_serve values('cc666','123456','M','cc666@gmail.com','AA4646464','US', '1988-05-16','Avatar Chen','1012');
insert into member_serve values('cdf123','pnvae','F','novad@gmail.com','AK1827392','US', '1978-02-01','Adele Smith','1013');
insert into member_serve values('pmf123','nvaen','F','ncaedv@gmail.com','DU2984738','UK', '1996-02-21','Emma Waston','1014');
insert into member_serve values('abc234','vnpanvkef','F','ncoia@gmail.com','WI1992832','France', '1976-03-03','Taylor Evans','1015');
insert into member_serve values('chf133','Nvafdkl','M','nvoiae@gmail.com','SE2301829','Canada', '1999-01-01','Harry Evans','1016');
insert into member_serve values('eqw143','qweruo','M','dsfoui@gmail.com','WU3229832','China', '1982-02-09','Tameika Joly','1017');
insert into member_serve values('sfg234','qwufuo','M','wqeiy@gmail.com','EQ2938473','US', '1936-09-12','Karena Mcclaskey','1018');
insert into member_serve values('fwh134','qewryow','M','ewqroi@gmail.com','SS2983432','Canada', '1997-12-23','Dione Ammons','1019');
insert into member_serve values('hsf452','qerywe','M','qwehfi@gmail.com','SI2938293','China', '1989-05-23','Shae Fitton','1020');
