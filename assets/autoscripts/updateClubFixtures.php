<html>

<head>
    <title>DFF</title>

</head>

<body>

    <?php
        
        /*$user = "root";
        $password = "";
        $host = "127.0.0.1";
        $database = "draftfantasyfootball";*/
        
        $user = "j565246_draftFF";
        $password = "g@CcZ,QgVGKH";
        $host = "127.0.0.1";
        $database = "j565246_draftfantasyfootball";
        
        $con = new mysqli ($host, $user, $password, $database );
    
		//remove php Maximum execution time limit
		set_time_limit(0);
		
		//set var to highest number of players in loop
		$limit = 1000;
    
        //clear current club matches
        mysqli_query ( $con,"TRUNCATE clubmatch;" ); 
    
        echo 'cleared Table';
        
        //var to hold id of last team
        $lastTeam = 0; 
    
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
				$team_id = $jsonDecoded['team_id'];
                
                echo '<p>updating for ' .$team_id. '</p>';
                
                //get size of fixture history all array
                $allSize = count($jsonDecoded['fixtures']['all']);
                
                //if the team being checked is different to the last team checked loop through fixtures
                if($lastTeam != $team_id)
                {
                    //loop through all array
                    for ($e = 0; $e < $allSize; $e++)
                    {
                        echo '<p>---------Game ' .$e. '</p>';
                        //get required details from array
                        $date = $jsonDecoded['fixtures']['all'][$e][0];
                        $gameweek = $jsonDecoded['fixtures']['all'][$e][1];
                        $gameweek = substr($gameweek,9);
                        $opponent = $jsonDecoded['fixtures']['all'][$e][2];

                        //add Player to database
                    mysqli_query ( $con,"INSERT INTO clubmatch (teamId, gameweek, date, opponent)
                                        VALUES ('$team_id', '$gameweek', '$date', '$opponent');" );  
                    }   
                }
                
                //set last team equal to team of player just slooked at
                $lastTeam = $team_id;
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
