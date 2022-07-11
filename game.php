
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
       if ($val){
           //echo ">>>>>>>>>>>>>>>>>>>$ndx<br>";
           //debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
       }
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
            //echo "Die: $val value $d  <br>"; 
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


    var $turnCount; 
    
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
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls
        
        
        // Corrosponding external scoreboard values
        global $prpoints;      // Points chosen from a turn


        // Debug        echo "Initialize Scoreboard<br>";  // Debug
        //echo "In constr  #die $numDice, #turn set to $turn_count<br>";
        $this->turnCount = $turn_count;


        // Initialize
        $values = array(); 
        $points = array(); 
        $valUnlocked = array();
        $diceCup = new DiceCup($numDice);
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
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
       // global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls
       
       for ($n=0; $n<self::VALCNT; $n++) {
           if ($n < 6) {
	         $values[$n] = 0;
           }
	       else {$values[$n] = FALSE; }

	       $valUnlocked[$n] = TRUE;
           $points[$n] = 0;
           $prpoints[$n] = 0;
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
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls

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
            case "Ones":
                $ndx = 0;
                break;
            case "Two":
            case "Twos":
                $ndx = 1;
                break;
            case "Three":
            case "Threes":
                $ndx = 2;
                break;
            case "Four":
            case "Fours":
                $ndx = 3;
                break;
            case "Five";
            case "Fives":
                $ndx = 4;
                break;
            case "Six":
            case "Sixs":
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
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        
        // Corrosponding external scoreboard values
        global $prpoints;    // Points to be earned/chosen previous rolls
        
        switch ($choice) {
            case "One":
            case "Ones":
                $ndx = 0;
                break;
            case "Two":
            case "Twos":
                $ndx = 1;
                break;
            case "Three":
            case "Threes":
                $ndx = 2;
                break;
            case "Four":
            case "Fours":
                $ndx = 3;
                break;
            case "Five";
            case "Fives":
                $ndx = 4;
                break;
            case "Six":
            case "Sixs":
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
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls
       
       $fourkind = FALSE;
       $threekind = FALSE;
       $yahtzee = FALSE;
       $fh = FALSE;
       $strt = FALSE;
       $sstrt = FALSE;
       

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
//           echo "$n val = $diceCup->getDieVal($n)<br>";
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
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls

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
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls

        $arr = array();
        
        for ($n = 0; $n < self::VALCNT; $n++) {
            $key = choiceOutputTranslate($n);
            $arr[$key] = $valUnlocked[$n];
        }
        return $arr;            
   }     


   // printScoreBoardTable
   //   outputs the tabel html for the scoreboard, just the rows/tds
   function printScoreBoardTable() {
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls


        $cnt = self::VALCNT;
        //echo "debug    $cnt<br>";
        for ($n = 0; $n < $cnt; $n++) {
// KLUDGE
        switch ($n) {
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
        $label = $val;
        // END KLUDGE
        //$label = choiceOuputTranslate($n);

            echo ('<tr>');

            // First cell
            echo ('   <td> <label>');
            if ($valUnlocked[$n]) {
               echo ('<input type="radio" id="');
                   echo $label;
                   if ($n < 6) {
                       echo "s";
                   }
                   echo ('" name="ScoreCard" value="');
                   echo $label;
                   if ($n < 6) {
                       echo "s";
                   }
                   echo ('">');
            }       
            
            echo $label;
            if ($n < 6) {
               echo "s";
            }
            echo ('</label></td>');   // End of first cell
            
            // Second Cell
            echo (' <td>');
            $spaces = 5;
            
            if ($valUnlocked[$n]) {
                if ($points[$n] > 9) {
                    $spaces = $spaces - 1;
                }
                $str = $points[$n];
            }
            else {
                $str = $prpoints[$n];
                if ($points[$n] > 9) {
                    $spaces = $spaces - 1;
                }
            }
            for ($i=0; $i < $spaces; $i++) {
                echo "&nbsp;";
            }
            echo $str;
            
            echo ('</td> ');  // Second Cell
            echo (' </tr>');          // End of row
        }
    }

   // getTurnCount
   //    Returns current turn number
   //
   function getTurnCount() {
       //global $turnCount;
       return $this->turnCount;
   }

   // setTurnCount
   //    sets current turn number
   //
   function setTurnCount($tc) {
       //global $turnCount;
       //echo "TC is set to $tc<br>";
       $this->turnCount = $tc;
   }
   
   
    // saveBoard
    //   Records board/dice data to array for cookie
    //      returns array for cookie
    //
    function saveBoard() {
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls

//       $myfile = fopen("db.txt", 'w') or die("Unable to open file!");

       //echo "Saving file<br>";
       $str = "";
       $arr = array();
       $ndx = 0;
       $dicecount = $diceCup->diceCount();
       $arr[$ndx++] = strval($dicecount);                // Set dicecount
       $arr[$ndx++] = strval($this->turnCount+1);        // Increment turncount 

       for ($n=0; $n<$dicecount; $n++) {
           $arr[$ndx++] = strval($diceCup->getDieVal($n));
           $arr[$ndx++] = $diceCup->isDieLocked($n) ? 'true' : 'false';
       }

      // for ($n=0; $n<$dicecount; $n++) {
      //       $arr[$ndx++] = $diceCup->isDieLocked($n) ? 'true' : 'false';
      // }
       $arr[$ndx++] = "CHECK";

       for ($n=0; $n< 13; $n++) {
           //echo "Table points:";
           if ($valUnlocked[$n]) {
               $arr[$ndx++] = strval($points[$n]);
           }
           else {
               $arr[$ndx++] = strval($prpoints[$n]);
           }
           $tmp = $arr[$ndx-1];
//           echo " $tmp";
       }
       echo "<br>";
       
       for ($n=0; $n< self::VALCNT; $n++) {
           $arr[$ndx++] = $valUnlocked[$n] ? 'true' : 'false';
       }
       
       // Convert to single string
       $str = strval($dicecount);
       for ($n=1; $n < $ndx; $n++) {
          $str .= "|".$arr[$n]; 
       }
       
       //setcookie('scoreboard', $str, time()+(86400 * 30) );
      $myfile = fopen("db.txt", 'w') or die("Unable to open file!");


      fputs($myfile, $str);

      fclose($myfile);
      return $arr;
    //   print_r($_COOKIE['scoreboard']); echo "<br>";
     
    }
    
    // restoreBoard
    //   Restores board from array passed in as a cookie
    //      arr is array from cookie
    //
    function restoreBoard($arr) {
        global $values;      // Values of dice
        global $points;      // Points to be earned by this roll of dice
        global $valUnlocked; // if this value has been used (so locked)
        global $diceCup;     // Cup of dice
        global $labels;      // Text labels for each cell in table
        //global $turnCount;   // count of current turn
        global $prpoints;    // Points to be earned/chosen previous rolls
         
       $ndx = 2;
       // ScoreBoard has to have been set external to this call
       // i.e. the count has to have been used
       $dicecount = $diceCup->diceCount();
       //echo "DiceCup ";
       for ($n=0; $n<$dicecount; $n++) {
           $diceCup->setDieVal($n, intval($arr[$ndx++]));
           if (strcmp($arr[$ndx++], "true")==0) { 
               $diceCup->lockDice($n);     
           }
           else {
               $diceCup->unLockDice($n);
           }

           $tmp = $arr[$ndx-1];
//           echo " #$n $tmp";
       }
//       echo "<br>DiceLocks  ";
//       for ($n=0; $n<$dicecount; $n++) {
//           $diceCup->setDielock($n, boolval($arr[$ndx++]));
//       }           
       
       $str = $arr[$ndx++];
       if (strcmp($str, "CHECK") != 0) {
           echo "Error found $str<br>";
       }
       for ($n=0; $n<self::VALCNT; $n++) {
           $prpoints[$n] = intval($arr[$ndx++]);
       }
       
       for ($n=0; $n<self::VALCNT; $n++) {
           $valUnlocked[$n] = boolval($arr[$ndx++]);
       }
       
    }

}



/////
//  restore
//     This routine is external to ScoreBoard, of necessity. It is used to
//    pass in the array pass as a cookie between rounds.
//
function restore() {
//    echo "In Restore print_r >";
//    print_r($_COOKIE['scoreboard']); echo "<br>";
//    $scoreBoard = new ScoreBoard(7, 1); // Debug, until Cookies work        

      $myfile = fopen("db.txt", 'r') or die("Unable to open file!");
      //echo filesize("db.txt")."<br>";
      $str = fread($myfile, filesize("db.txt"));
      fclose($myfile);


    if (strlen($str) > 20) {
//        $str = $_COOKIE['scoreboard'];
        //echo "$str<br>";
        $arr = explode('|', $str);
        //echo "Num die $arr[0] turn $arr[1]";
        $scoreboard = new ScoreBoard(intval($arr[0]), intval($arr[1]));
        $scoreboard->restoreBoard($arr);
        return $scoreboard;
    }
    else { 
        echo "Error in restore, failed to return scoreboard<br>"; 
        echo($str);
    }
    
    return $scoreBoard;

}
        
        // Dice image locations are denoted vi D1,D2,D3 etc
        function displayDie($n){

             $dice = array(
                'D1' => '<img src="d1.png" width =110 height=100>',
                'D2' => '<img src="d2.png" width =80 height=100>',
                'D3' => '<img src="d3.png" width =80 height=100>',
                'D4' => '<img src="d4.png" width =80 height=100>',
                'D5' => '<img src="d5.png" width =80 height=100>',
                'D6' => '<img src="d6.png" width =80 height=100>'
            );
            switch($n){
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
        
        // displayCheckboxes
        //prints x number of checkboxes below the dice rack
        //connected to a POST form that submits an array of boolean values
        function displayCheckboxes($num, $turns, $locks) {



            $checkopt = array();
            //echo "Locks:";
            for ($n=0;$n<$num; $n++) {

//                $name = "die".$i;
 //               $checkopt[$n] = "$name";
 //               $checkopt[$n] .= '" value="True" ';

                if ($lock[$n]) {
                   //echo "1";
                   $checkopt[$n] .= ' checked="checked" ';
                }
                else {
                   //echo "0";
                   $checkopt[$n] .= " ";
                }
                
//                $checkopt[$n] .= ' />';
                  $checkopt[$n] .= ',';
            }
            echo "<br>"; 
//            echo "checkopt<br>";
//            print_r($checkopt);
            
            //echo "Turncount $turns<br>";            
            echo('<form action="game.php" method="post">');


            if ($turns > 0) {
                if ($turns < 4 ) {
                   for ($i=1; $i < $num+1 ; $i++){
             
                      $name = "die";
                      $name .= "$i";
                      echo('<input type="checkbox" name="');
                      echo( $name );
                      echo ( '" value="True" ');//need to css this 
                      //echo str_repeat('&nbsp;', 5);//adds whitespace in between checkboxes
                      $strg = $checkopt[$n-1];
                      echo " $strg />";
                   }
                }
            }
            echo('<input type="submit" name="roll" value="Roll"/> ');
            echo( '</form>');
        }
        
        
        
        ////////
        //  Process Post input 
            
            
        $keys = array_keys($_POST);
        $key1 = $keys[0];
        //echo "$key1<br>";
//        print_r($_POST);
        echo "<br>";


        switch ($key1) {
            case "diceCount":  // From initial screen
//                print_r($_POST);
                $ans = substr($_POST["diceCount"],0,1);
                $dice_count_choice = (int) $ans;
                $scoreBoard = new ScoreBoard($dice_count_choice, 0);
                $scoreBoard->saveBoard();
                //echo "exiting entry<br>";

                break;
                
                
            // From pressing roll
          
            case "die1": // From internal post
            case "die2": // From internal post
            case "die3": // From internal post
            case "die4": // From internal post
            case "die5": // From internal post
            case "die6": // From internal post
            case "die7": // From internal post
            case "Roll":
            case "roll":
                
                $scoreBoard = restore();  // Restores 
//                $scoreBoard->setTurnCount($scoreBoard->getTurnCount()+1);
                $scoreBoard->clearDiceLocks();
                //echo "cleared Dice Locks<br>";
//                print_r($_POST);
                foreach ($_POST as $key => $value ) {
                    //echo "Locking $key<br>";
                    switch ($key) {
                        case "die1": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1);
                            break;
                        case "die2": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1);
                            break;
                        case "die3": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1); 
                            break;
                        case "die4": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1);
                            break;
                        case "die5": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1);
                            break;
                        case "die6": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1);
                            break;
                        case "die7": // From internal post
                            $ndx = (int) substr($key,-1);
                            $scoreBoard->lockDice($ndx-1);
                            break;
                        default: break;
                    }
                }
                
                $diceCup->rollDice();
                $scoreBoard->updateBoard();   // Does roll
                $scoreBoard->saveBoard();
                //echo "exiting diceroll $key<br>";
                break;
                
                
            case "ScoreCard":
            case "scoreCard":
                $scoreBoard = restore();  // Restores 
                //echo "Post $key1<br>";
                $selection = $_POST[$key1];
                // echo "selection to lock $val <br>";
                //echo ">>$selection<br>";
                
                $ndx = $scoreBoard->choiceInputTranslate($selection);
                $amt = $scoreBoard->chooseEntry($selection);
                //echo "$amt for selection $selection<br>";
                $scoreBoard->updateBoard();   // Does roll
                $scoreBoard->setTurnCount(0);
                $scoreBoard->saveBoard();
                //echo "exiting scoreboard<br>";
                
                break;
            default:
                echo "Error in Default with POST<br>";
        }

        $ans = substr($_POST["diceCount"],0,1);
        $dice_count_choice = (int) $ans;
//        echo "Key $firstKey<br>" ;
//        echo "$dice_count_choice<br>";
        
        
        $numDice = $scoreBoard->getDiceCount(); 
        $diceArry = $scoreBoard->getDiceValues();
        $turns = $scoreBoard->getTurnCount();
        $locks = array();
        //echo "TC pre ckbox $turns<br>";
        $ndice = sizeof($diceArry);
        for ($n = 0; $n < $numDice; $n++) {
            displayDie($diceArry[$n]);
            $locks[$n] = $scoreBoard->isDieLocked($n);
        }

        displayCheckboxes($numDice, $turns, $locks);
        

    echo ('</div>');

//    <!-- first td is a radio button, ALL NEED SAME NAME DIFFERENT VALUE
//        second td is populated with score via array from Classes.php many problems have arisen here
//        todo: 
//            fill in second td with form response, 
//            make it so radio button gets locked after use
//            Populate score at bottom, i believe this is tracked in Classes.php
//            css
//    -->
    echo ('<div id="scoreCard">');
    echo ('   <form action="game.php" method="post">');
    echo ('       <table id= "ScoreTable">');
    echo ('           <tbody>');
    $scoreBoard->printScoreBoardTable();
            ?>
               </tbody>
           </table>
           <input type="submit" name="scoreCard" value="Submit"/>
       </form>
   </div>
  </body>
</html>