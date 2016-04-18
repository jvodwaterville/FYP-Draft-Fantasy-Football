//Store GET details from url in an array
var GETARRAY = {};
if(document.location.toString().indexOf('?') !== -1) 
{
    var query = document.location
    .toString()
    // get the query string
    .replace(/^.*?\?/, '')
    // and remove any existing hash string (thanks, @vrijdenker)
    .replace(/#.*$/, '')
    .split('&');
    
    for(var i=0, l=query.length; i<l; i++) 
    {
        var aux = decodeURIComponent(query[i]).split('=');
        GETARRAY[aux[0]] = aux[1];
    }
}

//On page load run load page function to set objects functions
window.onload = loadThisPage;

//holds page of free agent lists player is looking at
var currentFreeAgentPage = 1;

//place holders for countdown timer
var selectionTime = 0;
var countDownTime = 0;

//holds the squad id of team curently picking and draft pick number
var teamCurrentlyPicking = 0; var draftPickNumber = 0;

//place holders for number of players required per position
var gks = 0; var dfs = 0; var mfs = 0; var fws = 0;

function loadThisPage()
{	
    getDraftDetails();
    
    checkPick(); 
    
    document.getElementById('selectPremTeam').setAttribute("onChange", "changePage(1)");
    document.getElementById('selectPosition').setAttribute("onChange", "changePage(1)");
    document.getElementById('selectName').setAttribute("onKeyUp", "changePage(1)");
    
    //set onClick functions for all player names
    $('[class="playerName"]:visible').each(function() 
    {
        this.setAttribute("onClick","openPlayerInfo(this.id, event);");
    });
    
    //check if its the users turn to pick, if not hide selectbuttons
    if (teamCurrentlyPicking == GETARRAY['squadid'])
    {
        $('.tradeButton').show();
    }
    else
    {
        $('.tradeButton').hide();
    }
}


var countDown = setInterval(countDownTimer,1000);

function countDownTimer()
{
    var theDiv = document.getElementById("theTimer");
    
    countDownTime++;
    var timeLeft = selectionTime - countDownTime;
    
    
    var minutes = Math.floor(timeLeft / 60);
    
    var seconds = timeLeft - minutes * 60;
    
    var finalTime = str_pad_left(minutes,'0',2)+':'+str_pad_left(seconds,'0',2);
    
    theDiv.innerHTML = "Time Remaining : "+ finalTime;
    
    if(timeLeft == 0)
        {
            clearInterval(countDown);
        }
}

function str_pad_left(string,pad,length) {
    return (new Array(length+1).join(pad)+string).slice(-length);
}

var myVar = setInterval(checkPick ,2000);


function checkPick()
{
    var theDiv = document.getElementById("thePlayers");
    
    $.ajax({
        url: 'index.php?checkdraftpcik=true&squadid='+GETARRAY['squadid'] ,
        cache: false,
        success: function(data)
        {
            var parsedData = JSON.parse(data);
            
            //set details of team currently picking
            var pickNumber = parsedData['pickNo'];
            var teamPicking = parsedData['pickTeam'];
            
            //check if the team picking has changed since last check
                //if it has then reload draft page with new details
            if(teamPicking != teamCurrentlyPicking  || draftPickNumber != pickNumber)
                {
                    //reset timer
                    countDownTime = 0;
                    
                    teamCurrentlyPicking = teamPicking;
                    draftPickNumber = pickNumber;
                    
                    changePage(currentFreeAgentPage);
                    loadThisPage(); 
                    
                    document.getElementById('pickText').innerHTML = "Pick Order - Pick #" + pickNumber +" in progress";
                    
                    //check if its the users turn to pick
                    if (teamPicking == GETARRAY['squadid'])
                    {
                        $('.tradeButton').show();
                    }
                    else
                    {
                        $('.tradeButton').hide();
                    }
                }
        },
    });
}


//loads the draft details on page load
function getDraftDetails()
{
    //get details time per draft selection and team and league name
    $.ajax({
        url: 'index.php?getteamleaguedetails=true&squadid='+GETARRAY['squadid'],
        cache: false,
        success: function(data)
        {
            //get json obect of details and parse it
            var parsedData = JSON.parse(data);
            
            //set selection time
            selectionTime = parsedData['selectionTime'];
            //output team and league name
            document.getElementById('teamNameLeagueName').innerHTML = parsedData['teamName'] +', '+parsedData['leagueName'];
        },
    });
    
    //get count of players required per position
    $.ajax({
        url: 'index.php?getplayersperposition=true&squadid='+GETARRAY['squadid'] ,
        cache: false,
        success: function(data)
        {
            //get json obect of details and parse it
            var parsedData = JSON.parse(data);
            
            //update required players per position count and output
            document.getElementById('gkCount').innerHTML = parsedData['Goalkeepers'];
            gks = parsedData['Goalkeepers'];
            document.getElementById('dfCount').innerHTML = parsedData['Defenders'];
            dfs = parsedData['Defenders'];
            document.getElementById('mfCount').innerHTML = parsedData['Midfielders'];
            mfs = parsedData['Midfielders'];
            document.getElementById('fwCount').innerHTML = parsedData['Forwards'];
            fws = parsedData['Forwards'];
        },
    });
    
    //get draft order
    $.ajax({
        url: 'index.php?getdraftorder=true&squadid='+GETARRAY['squadid'] ,
        cache: false,
        success: function(data)
        {
            //get json obect of details and parse it
            var parsedData = JSON.parse(data);
            //count total number of pics
            var totalPicks = Object.keys(parsedData).length;
            
            var tableHtml = '<table class="pickTable">';
            
            var pickNumber = 1;
            
            //loop through picks 18 times and print out pick order
            for(var round=1; round<=18; round++)
            {
                tableHtml += '<tr class="roundColumn"><td colspan="2">Round: ' + round + '</td></tr><tr class="headingColumn"><td>#</td><td>Team</td></tr>';
                if(round % 2 != 0)
                {
                    for(var pick=1; pick<=totalPicks; pick++)
                    {
                        tableHtml += '<tr><td>'+pickNumber+'</td><td>'+parsedData[pick]+'</td></tr>';
                        pickNumber++;
                    }
                }
                else
                {
                    
                    for(var pick=totalPicks; pick>=1; pick--)
                    {
                        tableHtml += '<tr><td>'+pickNumber+'</td><td>'+parsedData[pick]+'</td></tr>';
                        pickNumber++;
                    }
                }
            }
            tableHtml += '</table>';
            
            document.getElementById('pickTableHolder').innerHTML = tableHtml;
        },
    });
    
    
    //get draft history
    $.ajax({
        url: 'index.php?getdrafthistory=true&squadid='+GETARRAY['squadid'] ,
        cache: false,
        success: function(data)
        {
            //get json obect of details and parse it
            var parsedData = JSON.parse(data);
            
            var numberOfPicks = parsedData.length;
            
            var tableHtml = '<table class="pickTable"><tr class="roundColumn"><td>#</td><td>Team</td><td>Player</td></tr>';
            
            for(var round=numberOfPicks-1; round>=0; round--)
                {
                    var pickNo = round+1;
                    tableHtml += "<tr><td>"+ pickNo +"<td>"+ parsedData[round][0] + "</td><td>"+ parsedData[round][1] +"</td></tr>" ;
                }
            
            tableHtml += "</table>";
            
            document.getElementById('historyTableHolder').innerHTML = tableHtml;
        },
    });
}

//alerts user to confirm selection
function selectPlayer(id)
{
    //extract players id from id passed in ("select"+players id)
    var playerId = id.substr(6);
    
    //get players name
    var playerName = document.getElementById(playerId).innerHTML;
    
    var theDiv = document.getElementById('selectionAlertBox');
    
    var content = '<p>Confirm Selection of ' + playerName + '</p> <button onclick="addPlayerToSquad('+playerId+')">Confirm</button><button onclick="cancelSelection()">Cancel</button>';
    
    theDiv.innerHTML = content;
    
    document.getElementById('wrapper').style.opacity = '0.25';
    
    theDiv.style.display = "block";
    
}

//puts selection through
function addPlayerToSquad(playerId)
{
    if (teamCurrentlyPicking == GETARRAY['squadid'])
    {
        $.ajax({
            url: 'index.php?addplayertosquad=true&squadid='+GETARRAY['squadid']+'&playerid='+playerId ,
            cache: false,
            success: function(data)
            {
                var theDiv = document.getElementById('selectionAlertBox');
                theDiv.innerHTML = "";
                theDiv.style.display = "none";
                document.getElementById('wrapper').style.opacity = '1';
                loadThisPage(); 
                changePage(1);
            },
        });
    }
    else
    {
        alert("No Cheating!");  
    }
}

function cancelSelection()
{
    var theDiv = document.getElementById('selectionAlertBox');
    
    theDiv.innerHTML = "";
    
    document.getElementById('wrapper').style.opacity = '1';
    
    theDiv.style.display = "none";
}

//changes page on free agents result list, (pagification)
function changePage(id)
{
    currentFreeAgentPage = id;
    
    //set the header details for table
    var startOfTable = "<tr><th>Pos</th><th>Player</th><th>Team</th><th>TP</th><th>Select</th></tr>";
    
    //get search details
    var pos = document.getElementById('selectPosition').value;
    var team = document.getElementById('selectPremTeam').value;
    if(document.getElementById('selectName').value == "")
    {
        var name = "Any"; 
    }
    else
    {
        var name = document.getElementById('selectName').value;
    }
    
    
    var xmlhttp; 
        if (window.XMLHttpRequest)
          {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
          }
        else
          {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
          }
        xmlhttp.onreadystatechange=function()
          {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                //print out table details
                document.getElementById("freeAgentsTable").innerHTML= startOfTable + xmlhttp.responseText;
                //reset onclicks
                loadThisPage();
            }
          }

        //make call to getFreeAgents() function in model league.php, with data created above
        xmlhttp.open("GET","index.php?getavailableplayers=true&page=" + id + "&position=" + pos + "&premTeam=" + team + "&name=" + name +  "&squadid="+GETARRAY['squadid'], true);
        xmlhttp.send();
}