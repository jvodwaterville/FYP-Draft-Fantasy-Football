<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="assets/images/layOutPics/favicon.ico">
    <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/menu.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/table.css" />
	<script src="assets/javascript/jqueryui/jquery-ui.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="assets/javascript/menu.js"></script>
    <script src="assets/javascript/table.js"></script>
    
    <title>DFF | Table |</title>

</head>
<body>

    <!-- Get nav bar -->
    <?php include('assets/view/nav.php'); ?>
    
    <div id="wrapper">
        <div id="tableHolder">
            <?php $this->league->loadLeagueTable() ?>
        </div>
        <div id="fixturesHolder">
            <h2>Fixtures/Results</h2>
            <?php $this->league->getFixtures(date('y-m-d')) ?>
        </div>
    </div>
    
</body>
</html>