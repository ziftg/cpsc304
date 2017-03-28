<html>
<head>
	<title><?php echo $pageTitle; ?></title>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>

	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

	<div class="header">

		<div class="wrapper">

			<h1 class="branding-title"><a href="/">Personal Media Library</a></h1>

			<ul class="nav">
                <li <?php if ($section == "books") { echo " on"; } ?>>
                  <!-- <a href="catalog.php?cat=books">Flights</a> -->
                  <form method="POST" action="oracle-test.php">
                    <!-- <a type="submit" value="asdf" name="reset" href="catalog.php?cat=books">Flights</a> -->
                    <!-- <p class="text-center"><input type="submit" value="reset" name="reset"></p> -->
                    <button type="submit" value="reset" class="btn btn-primary btn-primary-outline" name="reset">staff</button>
                    <!-- <button type="submit" value="staff" class="btn btn-primary">staff</button> -->
                  </form>
                </li>

                <!-- <li class="books<?php if ($section == "books") { echo " on"; } ?>"><a href="catalog.php?cat=books">Books</a></li> -->
                <li <?php if ($section == "movies") { echo " on"; } ?>>
                  <!-- <a href="catalog.php?cat=movies">Customer</a> -->
                  <form method="POST" action="staff.php">
                    <!-- <p class="text-center"><input type="submit" value="update" name="updatesubmit"></p> -->
                    <!-- <p class="text-center"><input type="submit" value="staff"></p> -->
                    <button type="submit" value="staff" class="btn btn-primary btn-primary-outline">staff</button>
                  </form>
                </li>

                <li <?php if ($section == "music") { echo " on"; } ?>>
                  <form id="formButton" method="POST" action="flight.php">
                  <!--refresh page when submit-->


                       <!-- <input type="text" name="insNo" size="6">
                       <input type="text" name="insName" size="18"> -->
                  <!--define two variables to pass the value-->

                  <button type="submit" value="flight" class="btn btn-primary btn-primary-outline">flight</button>
                  </form>
                </li>

                <li <?php if ($section == "suggest") { echo " on"; } ?>>
                  <!-- <a href="suggest.php">Food</a> -->
                  <form method="POST" action="flight.php">
                    <!-- <p class="text-center">
                      <input class="text-center" type="submit" value="run hardcoded queries">
                    </p> -->
                    <button type="submit" value="flight" class="btn btn-primary btn-primary-outline">flight2</button>
                  </form>
                </li>
            </ul>

		</div>

	</div>
  <div class="wrapper">
    <h2>Eagle Fly</h2>
    <h3>Your travel compaion</h3>
  </div>

  <div style="width: 500px; margin: 200px auto 0 auto;">
  <form method="get" action="catalog.php">
    <label for="s">Search Flights:</label>
    <input type="text" name="s" id="s" />
    <input type="submit" value="go" />
  </form>
  </div>

  <!-- <div class="wrapper">
    <h2>Eagle Fly</h2>
    <h3>Your travel compaion</h3>
  </div>

  <div class="search">
  <form method="get" action="catalog.php">
    <label for="s">Search Flights:</label>
    <input type="text" name="s" id="s" />
    <input type="submit" value="go" />
  </form>
  </div> -->


	<div id="content">
