
<?php

include( "settings.php" );


?>

<FORM ACTION="server.php" METHOD="post">
    Email: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="email"><br>
    Name: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="name"><br>
    Address 1: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="address1"><br>
    Address 2: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="address2"><br>
    City: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="city"><br>
    US State: <INPUT TYPE="text" MAXLENGTH=2 SIZE=2 NAME="state"><br>
    Province: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="province"><br>
    Postal Code: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="postal_code"><br>
    Country: <INPUT TYPE="text" MAXLENGTH=20 SIZE=20 NAME="country"><br>

    <INPUT TYPE="hidden" NAME="action" VALUE="add_test_check">
	        <INPUT TYPE="Submit" VALUE="Add Test Check">
    </FORM>