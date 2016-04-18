<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="assets/images/layOutPics/favicon.ico">
    <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/squad.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/menu.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/playerInfoPopUp.css" />
	<script src="assets/javascript/jqueryui/jquery-ui.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="assets/javascript/menu.js"></script>
	<script src="assets/javascript/squad.js"></script>
	<script src="assets/javascript/playerInfoPopUp.js"></script>
    
    <title>DFF | Squad |</title>

</head>
<body>

    <?php include('assets/view/nav.php'); ?>
    
    <div id="playerInformationWrapper">
        
    </div>
    
    <div id="wrapper">
        
        <?php $this->squad->loadSquad() ?>
        
    </div>
    
</body>
</html>