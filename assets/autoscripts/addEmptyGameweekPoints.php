<html>

<head>
    <title>DFF</title>
</head>

<body>

    <?php
        
        $user = "j565246_draftFF";
        $password = "g@CcZ,QgVGKH";
        $host = "127.0.0.1";
        /*$database = "j565246_draftfantasyfootball";
        
        $user = "root";
        $password = "";
        $host = "127.0.0.1";
        $database = "draftfantasyfootball";*/
    
        $con = new mysqli ($host, $user, $password, $database );
    
		//remove php Maximum execution time limit
		set_time_limit(0);
		
        //get current date
        $date = date('y-m-d');
    
        //get current gameweek based on date
        $gwResults = mysqli_query ( $con, "SELECT * FROM gameweek where '$date' between startDate and endDate;" );
        $gwRow = $gwResults->fetch_assoc();
        $gameweek = $gwRow['id'];
    
    //add blank points to db for all players in db for this gameweek
        //set var to highest number of players in loop
		$limit = 1000;
		
		//loop through fantasy football json pages
		for ($playerId = 1; $playerId <= $limit; $playerId++)
		{
		//check if url exists
			$url = 'http://fantasy.premierleague.com/web/api/elements/' . $playerId . '/';
			$array = get_headers($url);
			$string = $array[0];
			//if it exists get the details
			if(strpos($string,"200")) 
			{
				$jsonFile = file_get_contents($url);
				$jsonDecoded = json_decode($jsonFile, true);
                
                //get size of fixtures all array
                $allSize = count($jsonDecoded['fixtures']['all']);
                
                //loop through all array
                for ($e = 0; $e < $allSize; $e++)
                {
                
                    //get required details from fixtures array
                    $date = $jsonDecoded['fixtures']['all'][$e][0];
                    $fixtureGameweek = $jsonDecoded['fixtures']['all'][$e][1];
                    $opponent = $jsonDecoded['fixtures']['all'][$e][2];

                    if($fixtureGameweek == "Gameweek ".$gameweek)
                    {
                        //add Player Points to database
                        mysqli_query ( $con,"INSERT INTO points (gameweek, playerId, dateTime, opponentResult, mp, gs, a, cs, gc, og, ps, pm, yc, rc, s, b, total)
                                           VALUES ('$gameweek', '$playerId', '$date', '$opponent', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');" );
                    }
                    
                    
                }
                
			} 
			else 
			//if it doesnt exist set limit to current "$playerId" value to stop loop
			{
				$limit = $playerId;
			}
        }
		?>
</body>

</html>
