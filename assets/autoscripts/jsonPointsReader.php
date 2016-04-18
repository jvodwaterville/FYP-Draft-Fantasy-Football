<html>

<head>
	<title>DFF</title>
	
</head>
<body>
    
	<?php 
        
        $user = "j565246_draftFF";
        $password = "g@CcZ,QgVGKH";
        $host = "127.0.0.1";
        $database = "j565246_draftfantasyfootball";
        
        /*$user = "root";
        $password = "";
        $host = "127.0.0.1";
        $database = "draftfantasyfootball";*/
        
        $con = new mysqli ($host, $user, $password, $database );
        
    
		//remove php Maximum execution time limit
		set_time_limit(0);
		
		//set var to highest number of players in loop
		$limit = 1000;
		
		//loop through fantasy football json pages
		for ($playerId = 1; $playerId <= $limit; $playerId++)
		{
            echo '<p>'+$playerId+'</p>';
            
		//check if url exists
			$url = 'http://fantasy.premierleague.com/web/api/elements/' . $playerId . '/';
			$array = get_headers($url);
			$string = $array[0];
			//if it exists get the details
			if(strpos($string,"200")) 
			{
				$jsonFile = file_get_contents($url);
				$jsonDecoded = json_decode($jsonFile, true);
                
                //get size of fixture history all array
                $allSize = count($jsonDecoded['fixture_history']['all']);
                
                //get current date
                $date = date('y-m-d');
                
                //get current gameweek based on date
                $gwResults = mysqli_query ( $con, "SELECT * FROM gameweek where '$date' between startDate and endDate;" );
                $gwRow = $gwResults->fetch_assoc();
                $todaysGameweek = $gwRow['id'];
				
                //loop through all array
                for ($e = 0; $e < $allSize; $e++)
                {
                    //get required details from array
                    $date = $jsonDecoded['fixture_history']['all'][$e][0];
                    $playerGameweek = $jsonDecoded['fixture_history']['all'][$e][1];
                    $opponent = $jsonDecoded['fixture_history']['all'][$e][2];
                    $minutesPlayed = $jsonDecoded['fixture_history']['all'][$e][3];
                    $GoalsScored = $jsonDecoded['fixture_history']['all'][$e][4];
                    $assists = $jsonDecoded['fixture_history']['all'][$e][5];
                    $cleansheets = $jsonDecoded['fixture_history']['all'][$e][6];
                    $goalsConceded = $jsonDecoded['fixture_history']['all'][$e][7];
                    $ownGoals = $jsonDecoded['fixture_history']['all'][$e][8];
                    $penaltiesSaved = $jsonDecoded['fixture_history']['all'][$e][9];
                    $penaltiesMissed = $jsonDecoded['fixture_history']['all'][$e][10];
                    $yellowCards = $jsonDecoded['fixture_history']['all'][$e][11];
                    $redCards = $jsonDecoded['fixture_history']['all'][$e][12];
                    $savesMade = $jsonDecoded['fixture_history']['all'][$e][13];
                    $bonus = $jsonDecoded['fixture_history']['all'][$e][14];
                    $value = $jsonDecoded['fixture_history']['all'][$e][18];
                    $points = $jsonDecoded['fixture_history']['all'][$e][19];

                    //check to see if the match date is the same as todays date, if it is add to database
                    if($playerGameweek == $todaysGameweek)
                    {
                        //update players points details in db
                        mysqli_query ( $con,"UPDATE points SET opponentResult='$opponent', mp='$minutesPlayed', gs='$GoalsScored', a='$assists', cs='$cleansheets', gc='$goalsConceded', og='$ownGoals', ps='$penaltiesSaved', pm='$penaltiesMissed', yc='$yellowCards', rc='$redCards', s='$savesMade', b='$bonus', total='$points' WHERE playerId = $playerId and dateTime = '$date';" );
                    }  

                }
                
			} 
			else 
			//if it doesnt exist set limit to current "$playerId" value to stop loop
			{
				$limit = $playerId;
			}
		}
		      echo 'Job done!';
		?>

</body>
</html>