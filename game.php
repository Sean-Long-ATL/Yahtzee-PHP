
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Yahtzee Game!</title>
    <link rel="stylesheet" href="./game.css">
  </head>
  <body>
 
	<div id='dice'>
	    
        <?php
        
        $website = "npcomplete-solutions.com";
        
        // Class definitions
        
        

///////////////
//
//  Class DiceCup
//
//   Encompasses the data and actions of a set of Dice.
//     The indexing of dice is zero to the dicecount
//     Creation:       GEM 7/7/2022
//     Modifications:  GEM 7/8/2022   pulled out complex data structures
//
class DiceCup {

    var $num_dice;

    // DiceCup Constructor
    //
    //   Initializes data and structures 
    //   Parameters - $numberofDice - the number of dice in cup
    // 
    // 
    function __construct($numberofDice) {
        global $diceArr;        // an array to hold the dice
        global $diceCuplocked;  // an array to hold the dice

        $diceArr = array();
        $diceCuplocked = array();
        $this->num_dice = $numberofDice;
        for ($n = 0; $n<$this->num_dice; $n++) {
           $diceArr[$n] = rand(1,6);
           $diceCuplocked[$n] = FALSE;
        }
    }


    function diceCount() {
        return $this->num_dice;
    }

    // rollDice
    //
    //   rolls all unlocked dice
    //
    function rollDice() {
        global $diceArr;
        global $diceCuplocked;

        for ($n = 0; $n<$this->num_dice; $n++) {
            if (!$diceCuplocked[$n]) { 
                $diceArr[$n] = rand(1,6);
            }
        }
    }

    // setDieVal
    //   sets value of die 
    //
    function setDieVal($ndx,$val) {
       global $diceArr;

       $diceArr[$ndx] = $val;
    } 

    // getDieVal
    //   returns value of die 
    //
    function getDieVal($ndx) {
       global $diceArr;
       $val = $diceArr[$ndx];
       return $val;
    } 

    // setDieLock
    //   sets lock value of die
    //
    function setDieLock($ndx,$val) {
       global $diceCuplocked;  // an array to hold the dice locks
       
       $diceCuplocked[$ndx] = $val;
    } 

    // countofValue
    //   returns how many dice are of the parameter value
    //
    function countofValue($value) {
        global $diceArr;

        $val = 0;
        for ($n=0; $n< $this->num_dice; $n++) {
            //echo "checking $n for val $value : which has value $diceArr[$n]<br>";
	        if ($value == $diceArr[$n]) {
                    $val++;
            }
        }
        return $val;
    }


    // lockDice
    //    locks dice who's index passed
    //
    function lockDice($ndx) {
        global $diceCuplocked;  // an array to hold the dice
        $diceCuplocked[$ndx] = TRUE;
        return $val;
    } 
    

    // unLockDice
    //    unlocks dice who's index passed
    //
    function unLockDice($ndx) {
        global $diceCuplocked;  // an array to hold the dice
        $diceCuplocked[$ndx] = FALSE;
        return $val;
    } 
    

    // isDieLocked
    //    returns if die is locked
    //
    function isDieLocked($ndx) {
        global $diceCuplocked;  // an array to hold the dice
        $val = $diceCuplocked[$ndx];
        return $val;
    } 


    // printDice
    //   Prints all dice to page
    //
    function printDice() {
        global $diceArr;

        for ($n=0; $n< $this->num_dice; $n++) {
            $d = $diceArr[$n];
            //$d->getValue(); 
            $val = $n+1;
            echo "Die: $val value $d  <br>"; 
        }
    }
}



///////////////
//
//  Class ScoreBoard
//
//   Encompasses the data and actions of the scoreboard.
//     
//     Creation: GEM 7/7/2022
//     Modified: GEM 7/8/2022  Removed non-scalar variables to external
//               GEM 7/8/2022  Added chooseEntry function
//               GEM 7/8/2022  Added choiceOutputTranslate and choiceInputTranslate functions
//               GEM 7/8/2022  Added getDiceValues, getDiceLocks, lockDice, unLockDice, 
//                               isDieLocked, getDiceCount, getScoreBoard, and
//                               getScoreBoardLocks functions
//
class ScoreBoard {

    // Index values
    const THREEKIND = 6;
    const FOURKIND = 7;
    const FULLH = 8;
    const SHORTSTRT = 9;
    const STRT = 10;
    const YAHTZEE = 11;
    const CHANCE = 12;

    const VALCNT = 13;

    // ScoreBoard Constructor
    //
    //   Initializes Scoreboard data and structures 
    //   Parameters - none
    // 
    function __construct($numDice, $turn_count) {
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        global $turnCount;   // count of current turn
        
        
        // Corrosponding external scoreboard values
        global $prpoints;      // Points chosen from a turn

        // Initialize
        $values = array(); 
        $points = array(); 
        $valUnlocked = array();
        $diceCup = new DiceCup($numDice);
        $turnCount = $turn_count;
        $labels = array();
        $this->clearboard(TRUE);

        // debug Straight testing
        //for($n=0;$n<$numDice;$n++) {
        //   if ($n != 3) { $diceCup->setDieVal($n, $n+1); }
        //}
       
       
        // Set label array
        for ($n=0; $n<self::VALCNT; $n++) {
            if ($n < 6) {
	            $labels[$n] = strval($n+1);
            }
	        else {
	            switch ($n) {
                    case self::THREEKIND:
                        $labels[$n] = "Three of a kind";
                        break;
                    case self::FOURKIND:
                        $labels[$n] = "Four of a kind";
                        break;
                    case self::FULLH:
                        $labels[$n] = "Full House";
                        break;
                    case self::SHORTSTRT:
                        $labels[$n] = "Short Straight";
                        break;
                    case self::STRT:
                        $labels[$n] = "Straight";
                        break;
                    case self::YAHTZEE:
                        $labels[$n] = "Yahtzee";
                        break;
                    case self::CHANCE:
                        $labels[$n] = "Chance";
                        break;
                    default:
                        $labels[$n] = "Unknown";
                        break;
	            }
	        }
        }       
    }

    // clearBoard
    //    zeros all values
    //   arg: restart is boolean: if true, clears all games points
    //         Used at game start or restart
    function clearboard($restart) {
        
       global $values;
       global $points;
       global $valUnlocked;
       
       for ($n=0; $n<self::VALCNT; $n++) {
           if ($n < 6) {
	         $values[$n] = 0;
           }
	       else {$values[$n] = FALSE; }

	       $valUnlocked[$n] = TRUE;
           $points[$n] = 0;
           if ($restart) {$points[$n] = 0;}
        }
    }
    
    // printBoard() {
    //    print all values
    // 
    function printboard() {

        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;

        $diceCup->printDice();

        for ($n=0; $n<self::VALCNT; $n++) {
            if ($n < 6 ) {
                $cval = "dice cnt $values[$n]";
            }
            else {
                $cval = "";
            }
            echo "$labels[$n]  &emsp;  $cval  pnts $points[$n]  <br>";
        }
    }

    // choiceOutputTranslate
    //   This translates the string parameter as used in chooseEntry and 
    //   getEntryValue into a numeric index.
    //
    function choiceOutputTranslate($ndx) {
        
        switch ($ndx) {
            case 0:
                $val = "One";
                break;
            case 1:
                $val = "Two";
                break;
            case 2:
                $val = "Three";
                break;
            case 3:
                $val = "Four";
                break;
            case 4:
                $val = "Five";
                break;
            case 5:
                $val = "Six";
                break;
            case 6:
                $val = "Three of a kind";
                break;
            case 7:
                $val = "Four of a kind";
                break;
            case 8:
                $val = "Full House";
                break;
            case 9:
                $val = "Short Straight";
                break;
            case 10:
                $val = "Straight";
                break;
            case 11:
                $val = "Yahtzee";
                break;
            case 12:
                $val = "Chance";
                break;
            default:
                echo "Error: $choice incorrect type<br>";
                $val = "Error";
	    }
        return $val;
    }

    

    // choiceInputTranslate
    //   This translates the string parameter as used in chooseEntry and 
    //   getEntryValue into a numeric index.
    //
    function choiceInputTranslate($choice) {
        
        switch ($choice) {
            case "One":
                $ndx = 0;
                break;
            case "Two":
                $ndx = 1;
                break;
            case "Three":
                $ndx = 2;
                break;
            case "Four":
                $ndx = 3;
                break;
            case "Five":
                $ndx = 4;
                break;
            case "Six":
                $ndx = 5;
                break;
            case "Three of a kind":                  
                $ndx = self::THREEKIND;
                break;
            case "Four of a kind":                  
                $ndx = self::FOURKIND;
                break;
            case "Full House":                  
                $ndx = self::FULLH;
                break;
            case "Short Straight":                  
                $ndx = self::SHORTSTRT;
                break;
            case "Straight":                  
                $ndx = self::STRT;
                break;
            case "Yahtzee":                  
                $ndx = self::YAHTZEE;
                break;
            case "Chance":                  
                $ndx = self::CHANCE;
                break;
            default:
                echo "Error: $choice incorrect type:$choice:<br> ";
                $ndx = 0;
	    }
        return $ndx;
    }

    // chooseEntry
    //   This allows choice of an value in table. No action will occur
    //  if entry is locked. Choice of this entry will return the points of 
    //  that choice and result in the locking of that entry for this game
    //  and moving of the values the the external value tables
    //
    function chooseEntry($choice) {
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        
        // Corrosponding external scoreboard values
        global $prpoints;    // Points to be earned/chosen previous rolls
        
        echo "$choice<br>";
        $ndx = choiceInputTranslate($choice);
        
        $valUnlocked[$ndx] = FALSE;
        $prpoints[$ndx] = $points[$ndx];
        
        return $points[$ndx];
    }

    // getPermanentValue
    //   This returns the permanent (already chosen) value associated by the 
    //  parameter.
    //
    function getPermanentValue($choice) {
        global $prpoints;    // Points to be earned/chosen previous rolls


        $ndx = choiceInputTranslate($choice);
        return $prpoints[$ndx];
    }
    
    // updateBoard
    //    This updates the internal scoreboard
    //    based on the most recent roll (values in dicecup param). 
    //
    function updateBoard() {
       global $values; // board values - used each roll
       global $points; // board points - used each roll
       global $valUnlocked; // board locks - session
       global $diceCup;     // session
       
       $fourkind = FALSE;
       $threekind = FALSE;
       $yahtzee = FALSE;
       $fh = FALSE;
       $strt = FALSE;
       $sstrt = FALSE;
       
       $diceCup->rollDice();

       // Update the counts of dice/values
       for ($n=0; $n<6; $n++) {
           $dieval = $n+1;
	       $val = $diceCup->countofValue($dieval);
           $values[$n] = $val;
           $points[$n] = $val*$dieval;
	       if ($val >= 3) {
	           $threekind = TRUE;
               if ($val >= 4) {
                  $fourkind = TRUE;          
  	              if ($val >= 5 ) {
	                  $yahtzee = TRUE;
	                  $points[self::YAHTZEE] = 50;
                      $points[$n] = 50;
                  } 
                  $points[self::FOURKIND] = 4*$dieval;
               }
               $points[self::THREEKIND] = 3*$dieval;
            }
        }      
        $lastVal = 100;
        $cnt = 0; 
        for ($n=0; $n<6; $n++) {
            $dieval = $n+1;
	        $val = $diceCup->countofValue($dieval);
	        // Straight test
	        if ($val > 0) {
	            $cnt++;
                if ($cnt > 3) {
                    $sstrt = TRUE;
                    $points[self::SHORTSTRT] = 30;
		            if ($cnt > 4) {
		                $strt = TRUE;
                        $points[self::STRT] = 40;
                        break;
                    }
                }
	        }
            else { $cnt = 0; }
      
            // FH test
	        if ($val >= 3) {
	            if (!$fh ) {
		            for ($i=0; $i<6; $i++) {
		                $dieval2 = $i+1;
		                if ($i != $n and $diceCup->countofValue($dieval2) >= 2) {
                            $points[self::FULLH] = 25;
                            $fh = TRUE;
                        }
                    }
                }
            }
            $lastVal = $val;
        }      
        $values[self::THREEKIND] = $threekind;
        $values[self::FOURKIND] = $fourkind;
        $values[self::FULLH] = $fh;
        $values[self::SHORTSTRT] = $sstrt;
        $values[self::STRT] = $strt;
        $values[self::YAHTZEE] = $yahtzee;
        $values[self::CHANCE] = FALSE;
        $points[self::CHANCE] = rand(12,25);
   }

   //////////
   // Informational methods

       // Dice informational

   // getDiceCount
   //    returns the number of dice
   //   arg: none
   //
   function getDiceCount() {
       global $diceCup;
       return $diceCup->diceCount();
   }
    
   // getDiceValues
   //   returns an array with the values of the current dice 
   //
   function getDiceValues() {
       global $diceCup;
       $arr = array();
       for ($n = 0; $n < $diceCup->diceCount(); $n++) {
           $arr[$n] = $diceCup->getDieVal($n);
       }
       return $arr;
   }

   // getDiceLocks
   //   returns an array with the lock values of the current dice 
   //
   function getDiceLocks() {
       global $diceCup;
       $arr = array();
       for ($n = 0; $n < $diceCup->diceCount(); $n++) {
           $arr[$n] = $diceCup->isDieLocked($n);
       }
       return $arr;
   }


   // lockDice
   //   locks die
   //
   function lockDice($ndx) {
       global $diceCup;
       $diceCup->lockDice($ndx);     
   }

   // unlockDice
   //   unlocks die
   //
   function unLockDice($ndx) {
       global $diceCup;
       $diceCup->unLockDice($ndx);     
   }

   // clearDiceLocks
   //   unlocks all dice
   //
   function clearDiceLocks() {
       global $diceCup;
       for ($n = 0; $n < $diceCup->diceCount(); $n++) {
          $diceCup->unLockDice($ndx);
       }
   }

   // isDieLocked
   //    Returns if die is locked
   //
   function isDieLocked($ndx) {
       global $diceCup;
       return $diceCup->isDieLocked($ndx);     
   }
   
       // ScoreBoard informational
       
   // getScoreBoard
   //    Returns associative array of score values
   //
   function getScoreBoard() {
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)

        // Corrosponding external scoreboard values
        global $prpoints;      // Points chosen from a turn

        $arr = array();
        
        $sum = 0;
        for ($n = 0; $n < 13; $n++) {
            $key = choiceOutputTranslate($n);
            if (!$valUnlocked[$n]) { // i.e. if already set
                $arr[$key] = $points[$n];
            }
            else {  // Assumed locked
                $arr[$key] = $prpoints[$n];
                $sum += $prpoints[$n];
            }
            
            $arr["Total"] = $sum; // Append total
            
            return $arr;            
        }
   }
    
    
    
     
   // getScoreBoardLocks
   //    Returns associative array of score locks
   //
   function getScoreBoardLocks() {
        global $valUnlocked; // if this value has been used (so locked)

        $arr = array();
        
        for ($n = 0; $n < 13; $n++) {
            $key = choiceOutputTranslate($n);
            $arr[$key] = $valUnlocked[$n];
        }
        return $arr;            
   }     

   // getTurnCount
   //    Returns current turn number
   //
   function getTurnCount() {
       global $turnCount;
       return $turnCount;
   }
   // setTurnCount
   //    sets current turn number
   //
   function setTurnCount($tc) {
       global $turnCount;
       $turnCount = $tc;
   }
    // saveBoard
    //   Records board/dice data to array for cookie
    //      returns array for cookie
    //
    function saveBoard() {
       global $diceCup;     // session
       global $prpoints;
       global $valUnlocked; // board locks - session
       global $turnCount;
       
       $arr = array();
       $ndx = 0;
       $dicecount = $diceCup->diceCount();
       $arr[$ndx++] = $dicecount;                // Set dicecount
       $arr[$ndx++] = $turnCount+1;              // Increment turncount
       for ($n=0; $n<$dicecount; $n++) {
           $arr[$ndx++] = $diceCup->getDieVal($n);
       }
       for ($n=0; $n<$dicecount; $n++) {
           $arr[$ndx++] = $diceCup->isDieLocked($n);
       }

       for ($n=0; $n< self::VALCNT; $n++) {
           $arr[$ndx++] = $prpoints[$n];
       }
       
       for ($n=0; $n< self::VALCNT; $n++) {
           $arr[$ndx++] = $valUnlocked[$n];
       }
       return $arr;
       setcookie("scoreboard", $arr, time() + (86400 * 30), "/" );
      print_r($_COOKIE["scoreboard"]); echo "<br>";
     
    }
    
    // restoreBoard
    //   Restores board from array passed in as a cookie
    //      arr is array from cookie
    //
    function restoreBoard($arr) {
       global $valUnlocked; // board locks - session
       global $diceCup;     // session
       global $prpoints;
         
       $ndx = 2;
       // ScoreBoard has to have been set external to this call
       // i.e. the count has to have been used
       $dicecount = $diceCup->diceCount();
       for ($n=0; $n<$dicecount; $n++) {
           $diceCup->setDieVal($arr[$ndx++]);
       }
       for ($n=0; $n<$dicecount; $n++) {
           $diceCup->setDielock($arr[$ndx++]);
       }
       
       for ($n=0; $n<self::VALCNT; $n++) {
           $prpoints[$n] = $arr[$ndx++];
       }
       
       for ($n=0; $n<self::VALCNT; $n++) {
           $valUnlocked[$n] = $arr[$ndx++];
       }
       
    }

}


/////
//  restore
//     This routine is external to ScoreBoard, of necessity. It is used to
//    pass in the array pass as a cookie between rounds.
//
function restore() {
    print_r($_COOKIE); echo "<br>";
    $scoreBoard = new ScoreBoard(7, 1); // Debug, until Cookies work        

    if (isset($_COOKIE["scoreboard"])) {
        $arr = $_COOKIE["scoreboard"];
        $scoreboard = new ScoreBoard($arr[0], $arr[1]);
        $scoreboard->restoreBoard($arr, $turn_count);
        return $scoreboard;
    }
    else { 
        echo "Error in restore, failed to return scoreboard<br>"; 
        print_r($_COOKIE);
    }
    
    return $scoreBoard;

}
        
        
        
        ////////
        //  Process Post input 
            
            
        //print_r($_POST);
        $keys = array_keys($_POST);
        $key1 = $keys[0];
        echo "$key1<br>";
        print_r($_POST);
        echo "<br>";


        switch ($key1) {
            case "diceCount":  // From initial screen
                print_r($_POST);
                $ans = substr($_POST["diceCount"],0,1);
                $dice_count_choice = (int) $ans;
                $scoreBoard = new ScoreBoard($dice_count_choice, 0);

                break;
            case "die1": // From internal post
            case "die2": // From internal post
            case "die3": // From internal post
            case "die4": // From internal post
            case "die5": // From internal post
            case "die6": // From internal post
            case "die7": // From internal post
            case "Roll":
                $scoreBoard = restore();  // Restores 
                $scoreBoard->clearDiceLocks();
                foreach ($_POST as $key => $value ) {
                    $ndx = (int) substr($key,-1);
                    $scoreBoard->lockDice($ndx);
                }
                
                $scoreBoard->updateBoard();   // Does roll
                $scoreBoard->saveBoard();
                break;
            case "ScoreCard":
                $scoreBoard = restore();  // Restores 
                $selection = $_POST[$key1];
                // echo "selection to lock $val <br>";
                echo ">>$selection<br>";
                
                $ndx = $scoreBoard->choiceInputTranslate($selection);
                //$amt = $scoreBoard->chooseEntry($selection);
                echo "$amt for selection $selection<br>";
                $scoreBoard->updateBoard();   // Does roll
                $scoreBoard->saveBoard();
                
                break;
          }
        
        $test = $scoreBoard->getDiceCount();  // Debug
        
        $ans = substr($_POST["diceCount"],0,1);
        $dice_count_choice = (int) $ans;
        echo "Key $firstKey<br>" ;
        echo "$dice_count_choice<br>";
        
        
        // Dice image locations are denoted vi D1,D2,D3 etc
        function displayDie($int){
            $dice = array(
                'D1' => '<img src="d1.png" width =80 height=100>',
                'D2' => '<img src="d2.png" width =80 height=100>',
                'D3' => '<img src="d3.png" width =80 height=100>',
                'D4' => '<img src="d4.png" width =80 height=100>',
                'D5' => '<img src="d5.png" width =80 height=100>',
                'D6' => '<img src="d6.png" width =80 height=100>'
            );
            switch($int){
                case 1: 
                    echo $dice['D1'];
                    break;
                case 2:
                    echo $dice['D2'];
                    break;
                case 3:
                    echo $dice['D3'];
                    break;
                case 4:
                    echo $dice['D4'];
                    break;
                case 5:
                    echo $dice['D5'];
                    break;
                case 6:
                    echo $dice['D6'];
                    break;
            }
            
        }
        //prints x number of checkboxes below the dice rack
        //connected to a POST form that submits an array of boolean values
        function displayCheckboxes($num, $diceLocks) {
            
            
            echo('<form action="game.php" method="post">');
            
            for ($i=0; $i < count($diceLocks) ; $i++){
            
               $name = "die".$i;
               echo('<input type="checkbox" name="');
               echo( $name );
               echo ( '" value="True" id="keptCheckBox" ');//need to css this 
               //echo str_repeat('&nbsp;', 5);//adds whitespace in between checkboxes
               if ($diceLocks[$n]) {
                  echo (' checked ');
               }
               echo (' />');
            }
            echo('<input type="submit" name="roll" value="Roll"/> ');
            echo( '</form>');
        }
        
        $numDice = $scoreBoard->getDiceCount(); 
        $diceArry = $scoreBoard->getDiceValues();
        for ($n = 0; $n < $numDice; $n++) {
            displayDie($diceArry[$n]);
        }
        $locks = $scoreBoard->getDiceLocks();
        displayCheckboxes($numDice,$locks);
        
        
        ?>
    </div>

    <!-- first td is a radio button, ALL NEED SAME NAME DIFFERENT VALUE
        second td is populated with score via array from Classes.php many problems have arisen here
        todo: 
            fill in second td with form response, 
            make it so radio button gets locked after use
            Populate score at bottom, i believe this is tracked in Classes.php
            css
    -->
    <div id="scoreCard">
        <form action="game.php" method="post">
            <table id= 'ScoreTable'>
                <tbody>
                    <tr>
                        <td> <label><input type="radio" id="ones" name="ScoreCard" value="One">Ones</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="twos" name="ScoreCard" value="Two">Twos</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="threes" name="ScoreCard" value="Three">Threes</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="fours" name="ScoreCard" value="Four">Fours</label></td>                
                        <td> </td>
                    </tr>
                    <tr>
                      
                        <td> <label><input type="radio" id="fives" name="ScoreCard" value="Five">Fives</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="sixes" name="ScoreCard" value="Six">Sixes</label></td> 
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="3 of a kind" name="ScoreCard" value="Three of a kind">Three of a Kind</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="4 of a kind" name="ScoreCard" value="Four of a kind">Four of a Kind</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="Full House" name="ScoreCard" value="Full House">Full House</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="small straight" name="ScoreCard" value="Short Straight">Small Straight</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="large straight" name="ScoreCard" value="Straight">Large Straight</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="yahtzee" name="ScoreCard" value="Yahtzee">Yahtzee</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> <label><input type="radio" id="Chance" name="ScoreCard" value="Chance">Chance</label></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td>Score: </td>
                        <td> </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="scoreCard" value="Submit"/>
        </form>
    </div>
  </body>
</html>