<?php include( "head.php" ); ?>




<?php


function generateAmuletRow( $inNumber, $inDate, $inWeight, $inMetal, $inMoney,
                            $inJPG, $inCodeName ) {
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
<?php echo $inMoney;?> US DOLLARS<br>
<br>
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

<table width=500><tr><td ALIGN=JUSTIFY>
A secret CABAL has been chosen.  Have YOU been chosen?  Only YOU know.  If you don't know, don't panic, because it's NOT OVER YET.<br>
<br>
Yes, you read that correctly.  YOU STILL HAVE A CHANCE.  The ancient dreams of THE INTERNET have mostly been SQUANDERED by fools and charlitans, but there is one dream left alive.  <font color=yellow>GOLD</font>.  Yes, if you're still reading this paragraph, you're smarter than you think, and more importantly, you've come to the RIGHT PLACE.<br>
<br>
Do you know how to get WHATEVER YOU WANT and even more using NEUROLINGUISTIC PROGRAMMING?  Have you ever built a WORKING RADIO with NO POWER SOURCE?  Do you know how much an OUNCE OF GOLD could buy in ANCIENT ROME?  Shockingly, this is only the TIP OF THE ICEBERG.<br>
<br>
<br>
READ ON TO FIND OUT HOW IT WORKS...<br>
<br>
<br>
Over TWELVE days, ONE amulet will be won EACH DAY.  Whenever you play against a CABAL MEMBER, each net coin GAINED by the end of the match is worth one point added, and each net coin LOST by the end is worth one point taken away.  How do you know when you're playing against a cabal member?  Here's the surprise you've been waiting for:  YOU DON'T!  Win that day's amulet by scoring more points against the cabal than your fellow players that day.  Don't worry:  SCORES CAN NEVER FALL BELOW ZERO.<br>
<br>
If you're thinking about pulling a CLEVER TRICK and winning more than one amulet, STOP RIGHT THERE, because you can't.  EXACTLY TWELVE PEOPLE WILL WIN AMULETS, although hundreds will TRY.<br>
<br>
But will YOU try?  Close your eyes for TEN SECONDS before answering:  YES, YOU WILL.<br>
<br>
AND THERE'S MORE:  Each amulet-winner will also win <font color=lightgreen>$MONEY$</font>, as will the SECOND-, THIRD-, and FOURTH-place players each day.  (Second place is $20, third place $10, fourth place $10).<br>
<br>
Each contest runs from MIDNIGHT to MIDNIGHT PDT (GMT -0700) on the APPOINTED DAY.
<br>
<br>

DOUBLE BONUS:  Each amulet comes with a UNIQUE and PARTIAL SECRET on the back.


<br>
<br>
<br>
<center>
<table border=1 cellpadding=10 cellspacing=0>


<?php

generateAmuletRow( 1, "WEDNESDAY MAY 6, 2015 (PDT)", "0.xx",
                   "PURE COPPER", 50,
                   "01oceanRam.jpg", "test1" );


generateAmuletRow( 2, "THURSDAY MAY 7, 2015 (PDT)",
                   "0.xx", "PURE COPPER", 50,
                   "02clayStream.jpg", "test1" );

?>




</table>
</center>




</td></tr>
</table>

</center>

<?php include( "foot.php" ); ?>