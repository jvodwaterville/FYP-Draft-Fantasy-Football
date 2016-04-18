<html>

<head>
	<title>DFF</title>
	
</head>
<body>
    
	<?php 
        
        /*$user = "root";
        $password = "";
        $host = "127.0.0.1";
        $database = "draftfantasyfootball";
        $con = new mysqli ($host, $user, $password, $database );*/
        
        $user = "j565246_draftFF";
        $password = "g@CcZ,QgVGKH";
        $host = "127.0.0.1";
        $database = "j565246_draftfantasyfootball";
        
    
		//remove php Maximum execution time limit
		set_time_limit(0);
		
		//set var to highest number of players in loop
		$limit = 1000;
		
		//loop through fantasy football json pages
		for ($x = 1; $x <= $limit; $x++)
		{
		//check if url exists
			$url = 'http://fantasy.premierleague.com/web/api/elements/' . $x . '/';
			$array = get_headers($url);
			$string = $array[0];
			//if it exists get the details
			if(strpos($string,"200")) 
			{
				$jsonFile = file_get_contents($url);
			
				$jsonDecoded = json_decode($jsonFile, true);
				
				$id = $jsonDecoded['id'];
				$team_id = $jsonDecoded['team_id'];
				$first_name = $jsonDecoded['first_name'];
				$second_name = $jsonDecoded['second_name'];
				$position = $jsonDecoded['type_name'];
				$value = $jsonDecoded['now_cost'];
				$webName = $jsonDecoded['web_name'];
                
                
				$news = $jsonDecoded['news'];
				$team_name = $jsonDecoded['team_name'];
                
               
               //add Player to database
                mysqli_query ( $con,"INSERT INTO player (id, teamId, firstName, lastName, position, value, news, webName)
									VALUES ('$id', '$team_id', '$first_name', '$second_name', '$position', '$value', 
									'$news', '$webName');" );
                
				
				echo '<p> ID: ' . $id . '  Team ID: ' . $team_id . '  Name: '  . $first_name . '  ' . $second_name . ' Pos: ' . $position .  '  £' . $value .  '  News: ' . $news . ' INSERTED!</p>';
                
                /*
                //add Team to database
                mysqli_query ( $con,"INSERT INTO clubteam (id, leagueId, fullName, shortName, stadium, manager)
									VALUES ('$team_id', '1', '$team_name', '', '', '');" );
                echo '<p> Team ID: ' . $team_id . '  Name: '  . $team_name . ' INSERTED!</p>';
                */
                
    ////////////////////////////////////////// this part players images \\\\\\\\\\\\\\\\\\\\\\\\ 
                /*$id = $jsonDecoded['id'];
				$photo = $jsonDecoded['photo'];
                
                echo $id + " - http://cdn.ismfg.net/static/plfpl/img/shirts/photos/".$photo;
                echo '<br>';
                $content = "http://cdn.ismfg.net/static/plfpl/img/shirts/photos/".$photo;
                
                copy("http://cdn.ismfg.net/static/plfpl/img/shirts/photos/".$photo, 'C:\xampp\htdocs\draftFantasyFootball\assets\images\playerImages\\'. $id .'.jpg');*/
                
			} 
			else 
			//if it doesnt exist set limit to current "$x" value to stop loop
			{
				$limit = $x;
			}
		
		}
		
		?>

</body>
</html>