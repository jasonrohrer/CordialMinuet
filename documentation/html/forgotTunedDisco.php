<?php include( "head.php" ); ?>




<?php

global $totalCash;
$totalCash = 0;


function generateAmuletRow( $inNumber, $inDate, $inWeight, $inMetal, $inMoney,
                            $inJPG, $inCodeName, $inPrizeMultiplier ) {

    $secondPlace = 20 * $inPrizeMultiplier;
    $thirdPlace = 10 * $inPrizeMultiplier;
    $fourthPlace = 5 * $inPrizeMultiplier;

    global $totalCash;
    
    $totalCash += $secondPlace + $thirdPlace + $fourthPlace + $inMoney;
?>

 
<tr><td><table border=0 cellpadding=10>
<tr>
<td colspan=2>
<font size=5>DAY <?php echo $inNumber;?>:</font><br>
<?php echo $inDate;?>

</td>
</tr>
<td VALIGN=top><img src=amulets/<?php echo $inJPG;?> width=200 height=200></td>
<td VALIGN=MIDDLE>

                                                         
<?php echo $inWeight;?> TROY OUNCE<br>
of <?php echo $inMetal;?><br>

<br>
PLUS<br>                              
<?php echo $inMoney;?> US DOLLARS<br>
<br>
<br>

[2ND PRIZE: <?php echo $secondPlace;?> US DOLLARS]<br>
[3RD PRIZE: <?php echo $thirdPlace;?> US DOLLARS]<br>
[4TH PRIZE: <?php echo $fourthPlace;?> US DOLLARS]<br>
<br>
                           
                           
<a href=http://cordialminuet.com/gameServer/server.php?action=vs_one_report&vs_one_code_name=<?php echo $inCodeName;?>>LEADERBOARD</a>
</td>
</tr>

</table></td></tr>
     
<?php
     
    }

?>


<center>


<font size=7>CORDIAL MINUET</FONT><br>
A two-player ONLINE STRATEGY GAME played for REAL MONEY by <a href="http://hcsoftware.sf.net/jason-rohrer">JASON ROHRER</a><br>

<br>
<br>


<font size=6>ORDER of the GOLDEN AMULET</FONT><br>
<br>

<table cellspacing=20><tr>

<td VALIGN=TOP ALIGN=CENTER>
<br>
<br>
<img src=amulets/10justBalance.jpg width=200 height=200>
</td>

<td width=500 ALIGN=JUSTIFY VALIGN=TOP>

<center>
<table border=0 cellpadding=2><tr><td bgcolor="#FF0000">
<table border=0 cellpadding=10<tr><td bgcolor="#000000">
<center>
The contest is over now.  Results are <a href="http://cordialminuet.com/incrementensemble/forums/viewtopic.php?id=240">posted here</a>.
</center>
</td></tr></table>
</td></tr></table>
</center>

<br> <br>     
    
A secret CABAL has been chosen.  Have YOU been chosen?  Only YOU know.  If you do not know, do not panic, because it is NOT OVER YET.<br>
<br>
Yes, you read that correctly.  YOU STILL HAVE A CHANCE.  The ancient dreams of THE INTERNET have mostly been SQUANDERED by fools and charlatans, but there is one dream left alive.  <font color=yellow>GOLD</font>.  Yes, if you are still reading this paragraph, you are SMARTER THAN YOU THINK, and more importantly, you have come to the RIGHT PLACE.<br>
<br>
Do you know how to get WHATEVER YOU WANT and even more using NEUROLINGUISTIC PROGRAMMING?  Have you ever built a WORKING RADIO with NO POWER SOURCE?  Do you know how much an OUNCE OF GOLD could buy in ANCIENT ROME?  Shockingly, this is only the TIP OF THE ICEBERG.<br>
<br>
<br>
READ ON TO FIND OUT HOW IT WORKS...<br>
<br>
<br>

FIRST THINGS FIRST:  simply READING will do you NO GOOD AT ALL if you have not <a href="foyer.php">PLAYED THE GAME</a>.<br>
<br>
Over TWELVE days, ONE amulet will be won EACH DAY.  Whenever you play against a CABAL MEMBER, each net coin GAINED by the end of the match is worth one point added, and each net coin LOST by the end is worth one point taken away.  How do you know when you are playing against the cabal?  Here is the surprise you have been waiting for:  YOU DO NOT KNOW!<br>
<br>
If you are thinking about pulling a CLEVER TRICK and winning more than one amulet, STOP RIGHT THERE, because you cannot.  EXACTLY TWELVE PEOPLE WILL WIN AMULETS, although millions will TRY.<br>
<br>
But will YOU try?  Close your eyes for TEN SECONDS before answering:  YES, YOU WILL.<br>
<br>
AND THERE IS MORE:  Each amulet includes <font color=lightgreen>$MONEY$</font>, as will the SECOND-, THIRD-, and FOURTH-place prizes each day.<br>
<br>
Each contest runs from MIDNIGHT to MIDNIGHT <a href="http://www.timeanddate.com/time/zones/pdt">PDT (GMT -0700)</a> on the APPOINTED DAY.
<br>
<br>

DOUBLE BONUS:  Each amulet comes with a UNIQUE and PARTIAL SECRET on the back that will...
<br><br>
<center>UNLOCK the door to ENDLESS RICHES&trade;</center>
<br>
<br>
YOUR QUESTIONS are <a href="http://cordialminuet.com/incrementensemble/forums/viewtopic.php?id=200">ANSWERED</a>.


<br>
<br>
<br>
<center>
<table border=1 cellpadding=10 cellspacing=0>


<?php

generateAmuletRow( 1, "WEDNESDAY MAY 6, 2015 (PDT)", "0.65",
                   "PURE COPPER", 50,
                   "01oceanRam.jpg", "oceanRam", 1 );

generateAmuletRow( 2, "THURSDAY MAY 7, 2015 (PDT)",
                   "0.59", "PURE COPPER", 50,
                   "02clayStream.jpg", "clayStream", 1 );



generateAmuletRow( 3, "FRIDAY MAY 8, 2015 (PDT)",
                   "0.50", "99.9% PURE SILVER", 100,
                   "06sameFaces.jpg", "sameFaces", 2 );

generateAmuletRow( 4, "SATURDAY MAY 9, 2015 (PDT)",
                   "0.53", "99.9% PURE SILVER", 100,
                   "07twoClaws.jpg", "twoClaws", 2 );


generateAmuletRow( 5, "SUNDAY MAY 10, 2015 (PDT)",
                   "0.58", "99.93% PURE GOLD", 200,
                   "10justBalance.jpg", "justBalance", 4 );






generateAmuletRow( 6, "MONDAY MAY 11, 2015 (PDT)",
                   "0.53", "PURE COPPER", 50,
                   "03twistingFish.jpg", "twistingFish", 1 );

generateAmuletRow( 7, "TUESDAY MAY 12, 2015 (PDT)",
                   "0.50", "PURE COPPER", 50,
                   "04curledHorns.jpg", "curledHorns", 1 );

generateAmuletRow( 8, "WEDNESDAY MAY 13, 2015 (PDT)",
                   "0.57", "PURE COPPER", 50,
                   "05bullCalf.jpg", "bullCalf", 1 );



generateAmuletRow( 9, "THURSDAY MAY 14, 2015 (PDT)",
                   "0.53", "99.9% PURE SILVER", 100,
                   "08toughPelt.jpg", "toughPelt", 2 );

generateAmuletRow( 10, "FRIDAY MAY 15, 2015 (PDT)",
                   "0.56", "99.9% PURE SILVER", 100,
                   "09wheatFurrow.jpg", "wheatFurrow", 2 );



generateAmuletRow( 11, "SATUDAY MAY 16, 2015 (PDT)",
                   "0.65", "99.93% PURE GOLD", 200,
                   "11burningSting.jpg", "burningSting", 4 );

generateAmuletRow( 12, "SUNDAY MAY 17, 2015 (PDT)",
                   "0.66", "99.93% PURE GOLD", 200,
                   "12horseMan.jpg", "horseMan", 4 );



?>




</table>
</center>

<br>
<br>

Total cash prizes are <?php global $totalCash; echo $totalCash;?> US DOLLARS.

<br>
<br>


Who MADE THE AMULETS?  Who MADE THE GAME?



</td>


<td VALIGN=TOP ALIGN=CENTER>
<br>
<br>
<img src=amulets/12horseMan.jpg width=200 height=200>
</td>


</tr>
</table>


<br>
<br>

<img src="amulets/wax.jpg">

<br>
<br>
<br>
<br>

<img src="amulets/castUncut.jpg">

<br>
<br>
<br>
<br>

<img src="amulets/polished.jpg">


</center>

<?php include( "foot.php" ); ?>