<?php include( "head.php" ); ?>

<center>


<font size=7>CORDIAL MINUET</FONT><br>
A two-player ONLINE STRATEGY GAME played for REAL MONEY by <a href="http://hcsoftware.sf.net/jason-rohrer">JASON ROHRER</a><br>
<br>
<br>


<center>REDEEM COUPONS HERE
<br>
<br>

NOTE:  After REDEMPTION, your account DETAILS will arrive by EMAIL.<br>
If you use a FAKE email address, you will not get your DETAILS.<br>
No SPAM will be SENT.  PROMISE.<br>
<br>
(You can also add coupon funds to an existing account.)<br>
<br>
<br>

<FORM ACTION="gameServer/server.php" METHOD="post">

<INPUT TYPE="hidden" NAME="action" VALUE="redeem_coupon">

<table border=0>
<tr>
<td>Valid Email:</td>
<td><INPUT TYPE="text" MAXLENGTH=80 SIZE=20 NAME="email"><td>
</tr><tr>
<td>Confirm Email:</td>
<td><INPUT TYPE="text" MAXLENGTH=80 SIZE=20 NAME="email_confirm"><td>
</tr><tr>
<td>Coupon Code:</td>
<td><INPUT TYPE="text" MAXLENGTH=80 SIZE=20 NAME="coupon_code"><td>
</tr><tr>
<td colspan=2 align=right>
<INPUT TYPE="Submit" VALUE="REDEEM">
</td>
</tr>
</table>

</FORM>

</center>

<?php include( "foot.php" ); ?>