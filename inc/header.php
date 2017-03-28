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

      <div class="container">
        <div class="row">

          <div class="control-group col-sm-2">
            <div class="controls">
              <input type="text" id="inputEmail" placeholder="user name">
            </div>
          </div>

          <div class="control-group col-sm-2">
            <div class="controls">
              <input type="password" id="inputPassword" placeholder="password">
            </div>
          </div>

          <div class="control-group col-sm-2">
            <div class="controls">
              <button type="submit" class="btn btn-primary">login as member</button>
            </div>
            <div class="controls">
              <button type="submit" class="btn btn-primary">login as staff</button>
            </div>
          </div>

          <div class="control-group col-sm-2">
            <div class="controls">
              <button type="submit" class="btn btn-primary">login as agent</button>
            </div>
            <div class="controls">
              <button type="submit" class="btn btn-primary">Apply Membership</button>
            </div>
          </div>
        </div>
      </div>
		</div>

	</div>
  <div class="wrapper">
    <h2>Eagle Fly</h2>
    <h3>Your travel companion</h3>
  </div>


  <form class="form-horizontal">


    <div class="container">
      <div class="row vertical-center-row">
        <div class="control-group col-sm-2">
          <h4>search by</h4>
        </div>
        <div class="control-group col-sm-4">
          <label class="control-label" for="inputEmail">Flight Number</label>
          <div class="controls">
            <input type="text" id="inputEmail" placeholder="enter a flight ">
          </div>
        </div>

        <div class="control-group col-sm-4">
          <label class="control-label" for="inputPassword">Date</label>
          <div class="controls">
            <input type="password" id="inputPassword" placeholder="YYYY-MM-DD">
          </div>
        </div>
      </div>


      <div class="row">
        <div class="control-group col-sm-2">
          <h4>or search by</h4>
        </div>
        <div class="control-group col-sm-2">
          <label class="control-label" for="inputEmail">Arrival</label>
          <div class="controls">
            <input type="text" id="inputEmail" placeholder="enter a flight ">
          </div>
        </div>

        <div class="control-group col-sm-2">
          <label class="control-label" for="inputPassword">Departure</label>
          <div class="controls">
            <input type="password" id="inputPassword" placeholder="year">
          </div>
        </div>

        <div class="control-group col-sm-2">
          <label class="control-label" for="inputPassword">From Date</label>
          <div class="controls">
            <input type="password" id="inputPassword" placeholder="asdf">
          </div>
        </div>

        <div class="control-group col-sm-2">
          <label class="control-label" for="inputPassword">To Date</label>
          <div class="controls">
            <input type="password" id="inputPassword" placeholder="month">
          </div>
        </div>
      </div>
    </div>

    <div class="control-group">
      <div class="controls">
        <button type="submit" class="btn btn-primary">Check Availability</button>
      </div>
    </div>
  </form>

	<div id="content">
