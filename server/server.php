<?php



// server will tell clients to upgrade to this version
global $cm_version;
$cm_version = "1";


// leave an older version here IF older clients can also connect safely
// (newer clients must use this old version number in their account hmac
//  too).
// NOTE that if old clients are incompatible, both numbers should be updated.
global $cm_accountHmacVersion;
$cm_accountHmacVersion = "1";







global $cm_flushInterval;
$cm_flushInterval = "0 0:02:0.000";


// override the default Notice and Warning handler 
set_error_handler( "cm_noticeAndWarningHandler", E_NOTICE | E_WARNING );



// edit settings.php to change server' settings
include( "settings.php" );



// no end-user settings below this point


// for use in readable base-32 encoding
// elimates 0/O and 1/I
global $readableBase32DigitArray;
$readableBase32DigitArray =
    array( "2", "3", "4", "5", "6", "7", "8", "9",
           "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
           "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" );


// no caching
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); 



// enable verbose error reporting to detect uninitialized variables
error_reporting( E_ALL );



// page layout for web-based setup
$setup_header = "
<HTML>
<HEAD><TITLE>Castle Doctrine Server Web-based setup</TITLE></HEAD>
<BODY BGCOLOR=#FFFFFF TEXT=#000000 LINK=#0000FF VLINK=#FF0000>

<CENTER>
<TABLE WIDTH=75% BORDER=0 CELLSPACING=0 CELLPADDING=1>
<TR><TD BGCOLOR=#000000>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=10>
<TR><TD BGCOLOR=#EEEEEE>";

$setup_footer = "
</TD></TR></TABLE>
</TD></TR></TABLE>
</CENTER>
</BODY></HTML>";






// ensure that magic quotes are on (adding slashes before quotes
// so that user-submitted data can be safely submitted in DB queries)
if( get_magic_quotes_gpc() ) {
    // force magic quotes to be added
    $_GET     = array_map( 'cm_stripslashes_deep', $_GET );
    $_POST    = array_map( 'cm_stripslashes_deep', $_POST );
    $_REQUEST = array_map( 'cm_stripslashes_deep', $_REQUEST );
    $_COOKIE  = array_map( 'cm_stripslashes_deep', $_COOKIE );
    }
    


// Check that the referrer header is this page, or kill the connection.
// Used to block XSRF attacks on state-changing functions.
// (To prevent it from being dangerous to surf other sites while you are
// logged in as admin.)
// Thanks Chris Cowan.
function cm_checkReferrer() {
    global $fullServerURL;
    
    if( !isset($_SERVER['HTTP_REFERER']) ||
        strpos($_SERVER['HTTP_REFERER'], $fullServerURL) !== 0 ) {
        
        die( "Bad referrer header" );
        }
    }




// general processing whenver server.php is accessed directly




// grab POST/GET variables
$action = cm_requestFilter( "action", "/[A-Z_]+/i" );

$trackDatabaseStats = true;

if( $action == "" || $action == "cm_setup" ) {
    // our stats table might not exist yet!
    $trackDatabaseStats = false;
    }


// all calls need to connect to DB, so do it once here
cm_connectToDatabase( $trackDatabaseStats );

// close connection down below (before function declarations)



global $flushDuringClientCalls;

if( $flushDuringClientCalls &&
    $action != "" &&
    $action != "cm_setup" &&
    $action != "check_for_flush" ) {
    // don't check for flush if we may be executing initial database setup
    // (flush breaks in that case)
    // also not if we are manually checking for flush, which happens below
    cm_checkForFlush();
    }



$debug = cm_requestFilter( "debug", "/[01]/" );

$remoteIP = "";
if( isset( $_SERVER[ "REMOTE_ADDR" ] ) ) {
    $remoteIP = $_SERVER[ "REMOTE_ADDR" ];
    }



global $shutdownMode;


if( $shutdownMode &&
    ( $action == "check_required_version" ||
      $action == "check_user" ||
      $action == "check_hmac" ||
      $action == "make_deposit" ) ) {

    echo "SHUTDOWN";
    global $shutdownMessage;
    echo "\n$shutdownMessage";
    }
else if( $action == "version" ) {
    global $cm_version;
    echo "$cm_version";
    }
else if( $action == "show_log" ) {
    cm_showLog();
    }
else if( $action == "clear_log" ) {
    cm_clearLog();
    }
else if( $action == "check_required_version" ) {
    cm_checkRequiredVersion();
    }
else if( $action == "check_user" ) {
    cm_checkUser();
    }
else if( $action == "check_hmac" ) {
    cm_checkHmac();
    }
else if( $action == "make_deposit" ) {
    cm_makeDeposit();
    }
else if( $action == "show_data" ) {
    cm_showData();
    }
else if( $action == "show_recent_user_emails" ) {
    cm_showRecentUserEmails();
    }
else if( $action == "show_stats" ) {
    cm_showStats();
    }
else if( $action == "block_user_id" ) {
    cm_blockUserID();
    }
else if( $action == "logout" ) {
    cm_logout();
    }
else if( $action == "cm_setup" ) {
    global $setup_header, $setup_footer;
    echo $setup_header; 

    echo "<H2>Cordial Minuet Server Web-based Setup</H2>";

    echo "Creating tables:<BR>";

    echo "<CENTER><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1>
          <TR><TD BGCOLOR=#000000>
          <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5>
          <TR><TD BGCOLOR=#FFFFFF>";

    cm_setupDatabase();

    echo "</TD></TR></TABLE></TD></TR></TABLE></CENTER><BR><BR>";
    
    echo $setup_footer;
    }
else if( $action != "" ) {
    cm_log( "Unknown action request:  $action" );
    echo "DENIED";
    }
else if( preg_match( "/server\.php/", $_SERVER[ "SCRIPT_NAME" ] ) ) {
    // server.php has been called without an action parameter

    // the preg_match ensures that server.php was called directly and
    // not just included by another script
    
    // quick (and incomplete) test to see if we should show instructions
    global $tableNamePrefix;
    
    // check if our tables exists
    $allExist =
        cm_doesTableExist( $tableNamePrefix."server_globals" ) &&
        cm_doesTableExist( $tableNamePrefix."log" ) &&
        cm_doesTableExist( $tableNamePrefix."users" ) &&
        cm_doesTableExist( $tableNamePrefix."server_stats" ) &&
        cm_doesTableExist( $tableNamePrefix."user_stats" );
    
        
    if( $allExist  ) {
        echo "Cordial Minuet Server database setup and ready";
        }
    else {
        // start the setup procedure

        global $setup_header, $setup_footer;
        echo $setup_header; 

        echo "<H2>Cordial Minuet Server Web-based Setup</H2>";
    
        echo "Server will walk you through a " .
            "brief setup process.<BR><BR>";
        
        echo "Step 1: ".
            "<A HREF=\"server.php?action=cm_setup\">".
            "create the database tables</A>";

        echo $setup_footer;
        }
    }


// done processing
// only function declarations below

// close database
cm_closeDatabase();












/**
 * Creates the database tables needed by seedBlogs.
 */
function cm_setupDatabase() {
    global $tableNamePrefix;


    $tableName = $tableNamePrefix . "server_globals";
    if( ! cm_doesTableExist( $tableName ) ) {

        // this table contains general info about the server
        // use INNODB engine so table can be locked
        $query =
            "CREATE TABLE $tableName(" .
            "last_flush_time DATETIME NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";

        // create one row
        $query = "INSERT INTO $tableName VALUES ( CURRENT_TIMESTAMP );";
        $result = cm_queryDatabase( $query );
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    
    $tableName = $tableNamePrefix . "log";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "log_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ".
            "entry TEXT NOT NULL, ".
            "entry_time DATETIME NOT NULL );";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    
    $tableName = $tableNamePrefix . "users";
    if( ! cm_doesTableExist( $tableName ) ) {

        // this table contains general info about each user
        //
        // sequence number used and incremented with each client request
        // to prevent replay attacks
        $query =
            "CREATE TABLE $tableName(" .
            "user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT," .
            "account_key VARCHAR(255) NOT NULL," .
            "INDEX( account_key )," .
            "email VARCHAR(255) NOT NULL," .
            "INDEX( email ),".
            "dollar_balance DOUBLE NOT NULL,".
            "num_deposits INT NOT NULL,".
            "total_deposits DOUBLE NOT NULL,".
            "num_withdrawals INT NOT NULL,".
            "total_withdrawals DOUBLE NOT NULL,".
            "total_won DOUBLE NOT NULL,".
            "total_lost DOUBLE NOT NULL,".
            "sequence_number INT NOT NULL," .
            "last_action_time DATETIME NOT NULL," .
            "blocked TINYINT NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    
    $tableName = $tableNamePrefix . "server_stats";

    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "stat_date DATE NOT NULL PRIMARY KEY," .
            "unique_users INT NOT NULL DEFAULT 0," .

            "database_connections INT NOT NULL DEFAULT 0," .
            "max_concurrent_connections INT NOT NULL DEFAULT 0," .

            "game_count INT NOT NULL DEFAULT 0,".
            "total_buy_in DOUBLE NOT NULL DEFAULT 0 ".
            ") ENGINE = INNODB;";
        

        $result = cm_queryDatabase( $query );


        echo "<B>$tableName</B> table created<BR>";       
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    



    // stats collected every flush interval
    $tableName = $tableNamePrefix . "user_stats";

    if( ! cm_doesTableExist( $tableName ) ) {

        // doesn't need to be innodb, because rows never change
        $query =
            "CREATE TABLE $tableName(" .
            "stat_time DATETIME NOT NULL PRIMARY KEY," .
            "users_last_five_minutes INT NOT NULL," .
            "users_last_hour INT NOT NULL," .
            "users_last_day INT NOT NULL );";
        

        $result = cm_queryDatabase( $query );


        echo "<B>$tableName</B> table created<BR>";       
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }

    
    

    }



function cm_showLog() {
    cm_checkPassword( "show_log" );

    echo "[<a href=\"server.php?action=show_data" .
        "\">Main</a>]<br><br><br>";

    $entriesPerPage = 1000;
    
    $skip = cm_requestFilter( "skip", "/\d+/", 0 );

    
    global $tableNamePrefix;


    // first, count results
    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."log;";

    $result = cm_queryDatabase( $query );
    $totalEntries = mysql_result( $result, 0, 0 );


    
    $query = "SELECT entry, entry_time FROM $tableNamePrefix"."log ".
        "ORDER BY log_id DESC LIMIT $skip, $entriesPerPage;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );



    $startSkip = $skip + 1;
    
    $endSkip = $startSkip + $entriesPerPage - 1;

    if( $endSkip > $totalEntries ) {
        $endSkip = $totalEntries;
        }

    

    
    echo "$totalEntries Log entries".
        " (showing $startSkip - $endSkip):<br>\n";

    
    $nextSkip = $skip + $entriesPerPage;

    $prevSkip = $skip - $entriesPerPage;

    if( $skip > 0 && $prevSkip < 0 ) {
        $prevSkip = 0;
        }
    
    if( $prevSkip >= 0 ) {
        echo "[<a href=\"server.php?action=show_log" .
            "&skip=$prevSkip\">".
            "Previous Page</a>] ";
        }
    if( $nextSkip < $totalEntries ) {
        echo "[<a href=\"server.php?action=show_log" .
            "&skip=$nextSkip\">".
            "Next Page</a>]";
        }
    
        
    echo "<hr>";

        
    
    for( $i=0; $i<$numRows; $i++ ) {
        $time = mysql_result( $result, $i, "entry_time" );
        $entry = htmlspecialchars( mysql_result( $result, $i, "entry" ) );

        echo "<b>$time</b>:<br><pre>$entry</pre><hr>\n";
        }

    echo "<br><br><hr><a href=\"server.php?action=clear_log\">".
        "Clear log</a>";
    }



function cm_clearLog() {
    cm_checkPassword( "clear_log" );

     echo "[<a href=\"server.php?action=show_data" .
         "\">Main</a>]<br><br><br>";
    
    global $tableNamePrefix;

    $query = "DELETE FROM $tableNamePrefix"."log;";
    $result = cm_queryDatabase( $query );
    
    if( $result ) {
        echo "Log cleared.";
        }
    else {
        echo "DELETE operation failed?";
        }
    }








// check if we should flush stale checkouts from the database
// do this once every 2 minutes
function cm_checkForFlush() {
    global $tableNamePrefix, $chillTimeout, $forcedIgnoreTimeout, $gracePeriod;


    if( $gracePeriod ) {
        // skip flushing entirely during grace period
        }
    

    $tableName = "$tableNamePrefix"."server_globals";
    
    if( !cm_doesTableExist( $tableName ) ) {
        return;
        }

    global $cm_flushInterval;
    
    $staleTimeout = "0 0:05:0.000";
    $staleLogTimeout = "10 0:00:0.000";
    $staleLogTimeoutDeadOwners = "5 0:00:0.000";

    // how long to keep maps in cache after they are flagged for deletion
    // this gives us a chance to catch a map that was flagged accidentally
    // (concurrency issue?) but is still referenced in database
    $staleFlaggedMapTimeout = "0 0:20:0.000";
    
    // for testing:
    //$cm_flushInterval = "0 0:00:30.000";
    //$staleTimeout = "0 0:01:0.000";
    //$staleFlaggedMapTimeout = "0 0:02:0.000";
    
    
    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );

    
    $query = "SELECT last_flush_time FROM $tableName ".
        "WHERE last_flush_time < ".
        "SUBTIME( CURRENT_TIMESTAMP, '$cm_flushInterval' ) ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    if( mysql_numrows( $result ) > 0 ) {

        // last flush time is old

        // update it now, to unlock that row and let other requests
        // go through

        // note that if flushes start taking longer than $cm_flushInterval
        // this will become a problem

        // so, we kick it forward a bit, to be safe and make
        // sure we don't end up running two flushes in parallel

        // note that if this flush fails and never updates its end
        // time in the database, the next flush won't happen until
        // 2 x $cm_flushInterval from now.  This is okay, because if
        // a flush fails, this is a huge problem anyway, and we probably
        // want to give other queries time before we try flushing again

        // (for example, a flush can fail to complete because it holds its
        //  locks too long, which causes other critical queries that are trying
        //  to get through to kill it---in that case, we REALLY don't
        //  want another flush starting up right away).
        $query = "UPDATE $tableName SET " .
            "last_flush_time = ".
            "ADDTIME( ".
            "         ADDTIME( CURRENT_TIMESTAMP, '$cm_flushInterval' ), ".
            "         '$cm_flushInterval' );";
    
        $result = cm_queryDatabase( $query );

        cm_queryDatabase( "COMMIT;" );


        cm_log( "Flush operation starting up." );
        cm_queryDatabase( "COMMIT;" );

        
        $usersDay = cm_countUsersTime( '1 0:00:00' );
        $usersHour = cm_countUsersTime( '0 1:00:00' );
        $usersFiveMin = cm_countUsersTime( '0 0:05:00' );

        $query = "INSERT INTO $tableNamePrefix"."user_stats".
            "( stat_time, users_last_five_minutes, users_last_hour, ".
            "  users_last_day ) ".
            "VALUES( CURRENT_TIMESTAMP, ".
            "        $usersFiveMin, $usersHour, $usersDay );";
        cm_queryDatabase( $query );
        
        

        global $tableNamePrefix;


        // Execute flush operations here
        
        
        // flush done
        
        cm_log( "Flush operation completed." );
        
        $query = "UPDATE $tableName SET " .
            "last_flush_time =  CURRENT_TIMESTAMP;";
        
        $result = cm_queryDatabase( $query );
        }

    cm_queryDatabase( "COMMIT;" );

    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    }





function cm_checkRequiredVersion() {
    global $cm_version, $upgradeURL;
    
    echo "$cm_version $upgradeURL OK";
    }





function cm_checkUser() {
    global $tableNamePrefix;

    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i" );

    // first, see if user already exists in local users table

    $query = "SELECT user_id, sequence_number, blocked ".
        "FROM $tableNamePrefix"."users ".
        "WHERE email = '$email';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    $blocked;
    
    if( $numRows > 0 ) {

        $row = mysql_fetch_array( $result, MYSQL_ASSOC );
    
        $blocked = $row[ "blocked" ];
        
        $user_id = $row[ "user_id" ];
        $sequence_number = $row[ "sequence_number" ];

        if( $blocked ) {
            echo "DENIED";

            cm_log( "checkUser for $email DENIED, blocked" );
            return;
            }

        if( ! cm_verifyTransaction( $user_id, false ) ) {
            cm_log( "checkUser failed, dummy sequence number HMAC failed" );
            return;
            }
        
        echo "$user_id $sequence_number OK";
        }
    else {
        echo "DENIED";

        cm_log( "checkUser for $email DENIED, user not found" );
        return;
        }
    }



function cm_checkHmac() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    echo "OK";
    }




function cm_makeDeposit() {
    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i" );

    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    // does account for this email exist already?
    $query = "SELECT user_id, account_key, blocked ".
        "FROM $tableNamePrefix"."users ".
        "WHERE email = '$email' FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    $user_id = "";
    $account_key = "";
    $dollar_balance = 0;
    
    if( $numRows > 0 ) {

        $row = mysql_fetch_array( $result, MYSQL_ASSOC );

        $blocked = $row[ "blocked" ];

        if( $blocked ) {
            echo "DENIED";
            cm_queryDatabase( "COMMIT;" );
            cm_queryDatabase( "SET AUTOCOMMIT=1" );

            cm_log( "deposit for $email DENIED, blocked" );

            return;
            }
        
        
        $user_id = $row[ "user_id" ];

        if( cm_getUserID() != $user_id ) {
            echo "ACCOUNT_EXISTS";
            cm_queryDatabase( "COMMIT;" );
            cm_queryDatabase( "SET AUTOCOMMIT=1" );

            cm_log( "deposit for $email DENIED, existing account with " .
                    "user_id mismatch" );
            return;
            }

        // existing account with valid user_id supplied
        // must have transaction credentials attached
        if( ! cm_verifyTransaction() ) {
            return;
            }

        $account_key = $row[ "account_key" ];
        $dollar_balance = $row[ "dollar_balance" ];
        }
    // else, no account exists for this email.
    // Leave user_id and dollar_balance blank


    $client_public_key = cm_requestFilter( "client_public_key",
                                           "/[A-F0-9]+/i" );

    if( strlen( $client_public_key ) != 64 ) {
        echo "DENIED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
            
        cm_log( "deposit for $email DENIED, ".
                "bad client key $client_public_key" );
        return;
        }



    global $serverCurve25519SecretKey;

    exec( "./curve25519GenSharedKey ".
          "$serverCurve25519SecretKey $client_public_key", $output );

    if( count( $output ) != 1 ) {
        echo "FAILED: Unexpected output from curve25519GenSharedKey:<br>\n" .
            "$output";
        
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );

        return;
        }
    
    $sharedSecretHex = $output[0];    
    
    $email_hmac = cm_requestFilter( "email_hmac",
                                    "/[A-F0-9]+/i" );
    if( strtoupper( $email_hmac ) !=
        strtoupper( cm_hmac_sha1( $sharedSecretHex, $email ) ) ) {
        echo "DENIED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "deposit for $email DENIED, ".
                "bad email hmac" );
        return;
        }

    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/[0-9]+[.][0-9][0-9]/i", 0.00 );

    global $minDeposit;
    if( $dollar_amount < $minDeposit ) {
        echo "DENIED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "deposit for $email DENIED, ".
                "bad dollar amount below $minDeposit, $dollar_amount" );
        return;
        }
    
    
    
    $dollar_amount_hmac = cm_requestFilter( "dollar_amount_hmac",
                                            "/[A-F0-9]+/i" );
    if( strtoupper( $dollar_amount_hmac ) !=
        strtoupper( cm_hmac_sha1( $sharedSecretHex, $dollar_amount ) ) ) {
        
        echo "DENIED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
            
        cm_log( "deposit for $email DENIED, ".
                "bad dollar amount hmac" );
        return;
        }

    
    $card_data_encrypted = cm_requestFilter( "card_data_encrypted",
                                             "/[A-F0-9]+/i" );

    
    $encryptionKeyHex =
        cm_hmac_sha1( $sharedSecretHex, "0" ) .
        cm_hmac_sha1( $sharedSecretHex, "1" );

    

    $encryptionKeyBin = cm_hex2bin( $encryptionKeyHex );    


    $card_data_encrypted_bin = cm_hex2bin( $card_data_encrypted );
    
    $cardDataBytes = array();

    $length = strlen( $card_data_encrypted_bin );

    for( $i=0; $i<$length; $i++ ) {
        $cardDataBytes[$i] =
            $encryptionKeyBin[$i] ^ $card_data_encrypted_bin[$i];
        }


    $cardData = implode( $cardDataBytes );

    $dataParts = preg_split( "/#/", $cardData );

    if( count( $dataParts ) != 4 ||
        strlen( $dataParts[1] ) != 2 ||
        strlen( $dataParts[2] ) != 4 ) {

        echo "DENIED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "deposit for $email DENIED, ".
                "badly formatted card data (length $length): $cardData" );
        return;
        }

    // card data came through okay
    // if charge goes through, we are set to trust this client

    $cardNumber = $dataParts[0];
    $month = $dataParts[1];
    $year = $dataParts[2];
    $cvc = $dataParts[3];

    $cents_amount = floor( $dollar_amount * 100 );
    
    global $curlPath, $stripeChargeURL, $stripeSecretKey,
        $stripeChargeDescription;

    $curlCallString =
        "$curlPath ".
        "'$stripeChargeURL' ".
        "-u $stripeSecretKey".": ".
        "-d 'receipt_email=$email'  ".
        "-d 'amount=$cents_amount'  ".
        "-d 'currency=usd'  ".
        "-d \"description=$stripeChargeDescription\" ".
        "-d 'card[number]=$cardNumber'  ".
        "-d 'card[exp_month]=$month'  ".
        "-d 'card[exp_year]=$year'  ".
        "-d 'card[cvc]=$cvc' ";

    //cm_log( "Calling Stripe with CURL:  $curlCallString" );
    
    exec( $curlCallString, $output );

    // process result
    $outputString = implode( "\n", $output );
    
    //cm_log( "Response from Stripe:\n$outputString" );


    if( strstr( $outputString, "error" ) != FALSE ) {
        echo "PAYMENT_FAILED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "PAYMENT_FAILED for $email, ".
                "stripe error:\n$outputString" );
        return;
        }

    
    $paid = false;

    foreach( $output as $line ) {

        if( strstr( $outputString, "paid" ) != FALSE &&
            strstr( $outputString, "true" ) != FALSE ) {
            $paid = true;
            }
        }

    if( !$paid ) {
        echo "PAYMENT_FAILED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "PAYMENT_FAILED for $email, ".
                "stripe result not marked as paid:\n$outputString" );
        return;
        }

    // else we're good

    $dollar_balance += $dollar_amount;
    

    if( $user_id == "" ) {
        // new account

        // look for collisions, try new salt
        $salt = 0;


        $found_unused = false;


        while( ! $found_unused ) {
            
        
            $account_key = cm_generateAccountKey( $email, $salt );

            
            // user_id auto-assigned (auto-increment)
            $query = "INSERT INTO $tableNamePrefix". "users SET ".
                "account_key = '$account_key', ".
                "email = '$email', ".
                "dollar_balance = '$dollar_balance', ".
                "num_deposits = 1, ".
                "total_deposits = '$dollar_amount', ".
                "num_withdrawals = 0, ".
                "total_withdrawals = 0, ".
                "total_won = 0, ".
                "total_lost = 0, ".
                "sequence_number = 0, ".
                "last_action_time = CURRENT_TIMESTAMP, ".
                "blocked = 0;";
            
            $result = mysql_query( $query );
            
            if( $result ) {
                $found_unused = 1;

                global $remoteIP;
                cm_log( "Account key $account_key created by $remoteIP" );
                }
            else {
                cm_log( "Duplicate ids?  Error:  " . mysql_error() );
                // try again
                $salt += 1;
                }
            }



        // for presentation to user
        
        // break into "-" separated chunks of 5 digits
        $account_key_chunks = str_split( $account_key, 5 );

        $account_key = implode( "-", $account_key_chunks );
        
        

        // now encrypt the account key

        $encryptionKeyHex =
            cm_hmac_sha1( $sharedSecretHex, "2" ) .
            cm_hmac_sha1( $sharedSecretHex, "3" );

    

        $encryptionKeyBin = cm_hex2bin( $encryptionKeyHex );    
    
        $encryptedAccountKeyBytes = array();
        
        
        $length = strlen( $account_key );

        for( $i=0; $i<$length; $i++ ) {
            $encryptedAccountKeyBytes[$i] =
                $encryptionKeyBin[$i] ^ $account_key[$i];
            }

        $encryptedAccountKeyHex =
            bin2hex( implode( $encryptedAccountKeyBytes ) );
        

        
        echo "1\n$encryptedAccountKeyHex\nOK";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        }
    else {
        // existing account

        $query = "UPDATE $tableNamePrefix". "users SET ".
            "dollar_balance = '$dollar_balance' ".
            "WHERE user_id = $user_id;";
        cm_queryDatabase( $query );

        
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        echo "0\n#\nOK";
        }

    // got here
    // deposit happened
    // send email receipt

    $balanceString = number_format( $dollar_balance, 2 );
    $amountString = number_format( $dollar_amount, 2 );
    
    
    $message =
        "You successfully deposited \$$amountString into your ".
        "CORDIAL MINUET account.  Your new balance is \$$balanceString.\n\n".
        "Here are your account details:\n\n".
        "Email:  $email\n".
        "Account Key:  $account_key\n\n".
        "Please save these for future reference.  These account details ".
        "are saved locally by your game client, but you may need to enter ".
        "them again if you are playing the game from a different computer ".
        "or fresh install in the future.\n\n\n".
        "Enjoy the game!\n".
        "Jason\n\n";
            
    
    cm_mail( $email, "Cordial Minuet Deposit Receipt",
             $message );
    
    }



// no hyphens
function cm_generateAccountKey( $inEmail, $inSalt ) {

    global $serverSecretKey, $accountKeyLength;
    
    $account_key = "";

    // repeat hashing new rand values, mixed with our secret
    // for security, until we have generated enough digits.
    while( strlen( $account_key ) < $accountKeyLength ) {
        
        $randVal = rand();
        
        $hash_bin =
            cm_hmac_sha1_raw( $serverSecretKey,
                              $inEmail .
                              uniqid( "$randVal"."$inSalt", true ) );
        
        
        $hash_base32 = cm_readableBase32Encode( $hash_bin );
        
        $digitsLeft = $accountKeyLength - strlen( $account_key );
        
        $account_key = $account_key . substr( $hash_base32, 0, $digitsLeft );
        }
    
    
    return $account_key;
    }





// utility function for stuff common to all denied user transactions
function cm_transactionDeny( $inLogDetails = true ) {

    if( $inLogDetails ) {
        // log it
        $postBody = file_get_contents( 'php://input' );
        $getString = $_SERVER[ 'QUERY_STRING' ];

        $requestData = $postBody;
        
        if( $getString != "" ) {

            if( $requestData != "" ) {
                $requestData = $getString . "\n" . $requestData;
                }
            else {
                $requestData = $getString;
                }
            }
        
        cm_log( "Transaction denied with the following get/post data:  ".
                "$requestData" );
        }
    
    
    
    echo "DENIED";
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );
    }





function cm_getUserID() {
    return cm_requestFilter( "user_id", "/\d+/" );
    }





// checks the ticket HMAC for the user ID and sequence number
// attached to a transaction (also makes sure user isn't blocked!)
// also checks for permadeath condition (no fresh starts left) and handles it

// can be called multiple times in one run without tripping over the repeated
// sequence number (only checked the first time)
global $transactionAlreadyVerified;
$transactionAlreadyVerified = 0;

function cm_verifyTransaction( $inUserID = -1,
                               $inCheckSequenceNumber = true ) {
    global $transactionAlreadyVerified;

    if( $transactionAlreadyVerified ) {
        return 1;
        }


    // first, make sure this is a valid, modern HTTP request
    if( $_SERVER[ "SERVER_PROTOCOL" ] == "" ||
        $_SERVER[ "SERVER_PROTOCOL" ] == "HTTP/0.9" ) {

        // sleep to allow client to timeout and retry without forcing
        // it to deal with this error message
        sleep( 30 );

        cm_log( "Incomplete HTTP request, SERVER_PROTOCOL= ".
                $_SERVER[ "SERVER_PROTOCOL" ] );
        
        echo "INCOMPLETE";
        return 0;
        }


    if( $_SERVER[ "REQUEST_METHOD" ] == "POST" &&
        $_SERVER[ "CONTENT_LENGTH" ] !=
        strlen( file_get_contents( 'php://input' ) ) ) {

        // sleep to allow client to timeout and retry without forcing
        // it to deal with this error message
        sleep( 30 );

        cm_log( "Incomplete HTTP POST body, Content-Length= ".
                $_SERVER[ "CONTENT_LENGTH" ] . ", but saw body length of ".
                strlen( file_get_contents( 'php://input' ) ) );
        
        echo "INCOMPLETE";
        return 0;
        }
    

    
    
    global $tableNamePrefix;

    $user_id = $inUserID;
    
    if( $user_id == -1 ) {
        $user_id = cm_getUserID();
        }
    
    $sequence_number = cm_requestFilter( "sequence_number", "/\d+/" );

    $account_hmac = cm_requestFilter( "account_hmac", "/[A-F0-9]+/i" );
    

    cm_queryDatabase( "SET AUTOCOMMIT=0" );

    // automatically ignore blocked users
    
    $query = "SELECT sequence_number, account_key ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id' AND blocked='0' FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    
    if( $numRows < 1 ) {
        cm_transactionDeny();
        cm_log( "Transaction denied for user_id $user_id, ".
                "not found or blocked" );
        return 0;
        }
    
    $row = mysql_fetch_array( $result, MYSQL_ASSOC );

    

    $last_sequence_number = $row[ "sequence_number" ];

    if( $inCheckSequenceNumber &&
        $sequence_number < $last_sequence_number ) {

        cm_transactionDeny();
        cm_log( "Transaction denied for user_id $user_id, ".
                "stale sequence number" );
        return 0;
        }
    
    
    
    $account_key = $row[ "account_key" ];


    global $cm_accountHmacVersion;
    
    $correct_account_hmac = cm_hmac_sha1( $account_key,
                                         "$sequence_number" .
                                         "$cm_accountHmacVersion" );


    if( strtoupper( $correct_account_hmac ) !=
        strtoupper( $account_hmac ) ) {
        cm_transactionDeny();
        cm_log( "Transaction denied for user_id $user_id, ".
                "hmac check failed" );

        return 0;
        }


    if( $inCheckSequenceNumber ) {
        
        // sig passed, sequence number not a replay!
        
        // update the sequence number, which we have locked
        
        $new_number = $sequence_number + 1;
        
        $query = "UPDATE $tableNamePrefix"."users SET ".
            "sequence_number = $new_number ".
            "WHERE user_id = $user_id;";
        cm_queryDatabase( $query );
        }

    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );


    if( $inCheckSequenceNumber ) {
        
        // counts as an action for this user
        $query = "UPDATE $tableNamePrefix"."houses SET ".
            "last_owner_action_time = CURRENT_TIMESTAMP ".
            "WHERE user_id = $user_id;";
        
        cm_queryDatabase( $query );


        $transactionAlreadyVerified = 1;
        }

    
    return 1;
    }




function cm_logout() {
    cm_checkReferrer();
    cm_clearPasswordCookie();

    echo "Logged out";
    }




// assumes database connection already open
// does not commit if a transaction is open
//
// increments one column in stat table for today
// or creates a row of 0's in the stat table for today (if no row exists)
// and puts a 1 in that one column.
function cm_incrementStat( $inStatColumnName, $inIncrementAmount = 1 ) {
    global $tableNamePrefix;
    
    $query = "INSERT INTO $tableNamePrefix"."server_stats ".
        "SET stat_date = CURRENT_DATE, ".
        "    $inStatColumnName = $inIncrementAmount ".
        "ON DUPLICATE KEY UPDATE ".
        "   $inStatColumnName = $inStatColumnName + $inIncrementAmount;";

    cm_queryDatabase( $query );
    }



// update one column's max-stat in stat table for today
// or creates a row of 0's in the stat table for today (if no row exists)
// and puts a new max value in that one column.
function cm_updateMaxStat( $inStatColumnName, $inPossibleNewMax ) {
    global $tableNamePrefix;
    
    $query = "INSERT INTO $tableNamePrefix"."server_stats ".
        "SET stat_date = CURRENT_DATE, ".
        "    $inStatColumnName = $inPossibleNewMax ".
        "ON DUPLICATE KEY UPDATE ".
        "   $inStatColumnName = GREATEST( $inStatColumnName, ".
        "                                 $inPossibleNewMax );";

    cm_queryDatabase( $query );
    }









function orderLink( $inOrderBy, $inLinkText ) {
        global $skip, $search, $order_by;
        if( $inOrderBy == $order_by ) {
            // already displaying this order, don't show link
            return "<b>$inLinkText</b>";
            }

        // else show a link to switch to this order
        return "<a href=\"server.php?action=show_data" .
            "&search=$search&skip=$skip&order_by=$inOrderBy\">$inLinkText</a>";
        }


function cm_showDataUserList() {
    global $tableNamePrefix;
    
    // these are global so they work in embeded function call below
    global $skip, $search, $order_by;

    $skip = cm_requestFilter( "skip", "/\d+/", 0 );

    global $usersPerPage;
    
    $search = cm_requestFilter( "search", "/[A-Z0-9_@. -]+/i" );

    $order_by = cm_requestFilter( "order_by", "/[A-Z_]+/i",
                                  "last_action_time" );
    

    $keywordClause = "";
    $searchDisplay = "";

    $usersTable = "$tableNamePrefix"."users";

    
    if( $search != "" ) {

        $search = preg_replace( "/ /", "%", $search );

        $keywordClause = "WHERE ( user_id LIKE '%$search%' " .
            "OR email LIKE '%$search%' ".
            "OR account_key LIKE '%$search%' ) ";

        $searchDisplay = " matching <b>$search</b>";
        }
    


    

    // first, count results
    $query = "SELECT COUNT(*) ".
        "FROM $usersTable ".
        "$keywordClause;";

    $result = cm_queryDatabase( $query );
    $totalUsers = mysql_result( $result, 0, 0 );

    
             
    $query = "SELECT user_id, account_key, email, ".
        "dollar_balance, last_action_time, ".
        "blocked ".
        "FROM $usersTable ".
        "$keywordClause ".
        "ORDER BY $order_by DESC ".
        "LIMIT $skip, $usersPerPage;";
    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    $startSkip = $skip + 1;
    
    $endSkip = $startSkip + $usersPerPage - 1;

    if( $endSkip > $totalUsers ) {
        $endSkip = $totalUsers;
        }





    

    
    echo "$totalUsers active users". $searchDisplay .
        " (showing $startSkip - $endSkip):<br>\n";

    
    $nextSkip = $skip + $usersPerPage;

    $prevSkip = $skip - $usersPerPage;
    
    if( $prevSkip >= 0 ) {
        echo "[<a href=\"server.php?action=show_data" .
            "&skip=$prevSkip&search=$search&order_by=$order_by\">".
            "Previous Page</a>] ";
        }
    if( $nextSkip < $totalUsers ) {
        echo "[<a href=\"server.php?action=show_data" .
            "&skip=$nextSkip&search=$search&order_by=$order_by\">".
            "Next Page</a>]";
        }

    echo "<br><br>";
    
    echo "<table border=1 cellpadding=5>\n";


    
    
    echo "<tr>\n";
    echo "<td>".orderLink( "user_id", "User ID" )."</td>\n";
    echo "<td>Blocked?</td>\n";
    echo "<td>".orderLink( "account_key", "Account Key" )."</td>\n";
    echo "<td>".orderLink( "email", "Email" )."</td>\n";
    echo "<td>".orderLink( "dollar_balance", "Balance" )."</td>\n";
    echo "<td>".orderLink( "last_action_time", "Action" )."</td>\n";

    echo "</tr>\n";
    

    for( $i=0; $i<$numRows; $i++ ) {
        $user_id = mysql_result( $result, $i, "user_id" );
        $account_key = mysql_result( $result, $i, "account_key" );
        $email = mysql_result( $result, $i, "email" );
        $dollar_balance = mysql_result( $result, $i, "dollar_balance" );
        $last_action_time = mysql_result( $result, $i, "last_action_time" );
        $blocked = mysql_result( $result, $i, "blocked" );
        
        $block_toggle = "";
        
        if( $blocked ) {
            $blocked = "BLOCKED";
            $block_toggle = "<a href=\"server.php?action=block_user_id&".
                "blocked=0&user_id=$user_id".
                "&search=$search&skip=$skip&order_by=$order_by\">unblock</a>";
            
            }
        else {
            $blocked = "";
            $block_toggle = "<a href=\"server.php?action=block_user_id&".
                "blocked=1&user_id=$user_id".
                "&search=$search&skip=$skip&order_by=$order_by\">block</a>";
            
            }        

        
        echo "<tr>\n";
        
        echo "<td><b>$user_id</b> ";
        echo "[<a href=\"server.php?action=show_detail" .
            "&user_id=$user_id\">detail</a>]</td>\n";
        echo "<td align=right>$blocked [$block_toggle]</td>\n";
        echo "<td>$account_key</td>\n";
        echo "<td>$email</td>\n";
        echo "<td>\$$dollar_balance</td>\n";
        echo "<td>$last_action_time</td>\n";
        echo "</tr>\n";
        }
    echo "</table>";
    }




function cm_formatBytes( $inNumBytes ) {
    
    $sizeString = "";

    if( $inNumBytes <= 1024 ) {
        $sizeString = "$inNumBytes B";
        }
    else if( $inNumBytes <= 1024 * 1024 ) {
        $sizeString = sprintf( "%.2f KiB", $inNumBytes / 1024 );
        }
    else if( $inNumBytes <= 1024 * 1024 * 1024) {
        $sizeString = sprintf( "%.2f MiB", $inNumBytes / ( 1024 * 1024 ) );
        }
    else if( $inNumBytes <= 1024 * 1024 * 1024 * 1024 ) {
        $sizeString = sprintf( "%.2f GiB",
                               $inNumBytes / ( 1024 * 1024 * 1024 ) );
        }
    return $sizeString;
    }


function cm_generateHeader() {
    $bytesUsed = cm_getTotalSpace();

    $sizeString = cm_formatBytes( $bytesUsed );

    $userCount = cm_countUsers();

    $result = cm_queryDatabase( "SHOW FULL PROCESSLIST;" );
    $connectionCount = mysql_numrows( $result );

    global $tableNamePrefix;
    $usersDay = cm_countUsersTime( '1 0:00:00' );
    $usersHour = cm_countUsersTime( '0 1:00:00' );
    $usersFiveMin = cm_countUsersTime( '0 0:05:00' );
    $usersMinute = cm_countUsersTime( '0 0:01:00' );
    $usersSecond = cm_countUsersTime( '0 0:00:01' );
    
    $perUserString = "?";
    if( $userCount > 0 ) {
        $perUserString = cm_formatBytes( $bytesUsed / $userCount );
        }

    $connectionWord = "connections";
    if( $connectionCount == 1 ) {
        $connectionWord = "connection";
        }
    
    echo "<table width='100%' border=0><tr>".
        "<td valign=top width=25%>[<a href=\"server.php?action=show_data" .
            "\">Main</a>] ".
            "[<a href=\"server.php?action=show_stats" .
            "\">Stats</a>]</td>".
        "<td valign=top align=center width=50%>".
        "$sizeString ($perUserString per user)<br>".
        "$connectionCount active MySQL $connectionWord<br>".
        "Users: $usersDay/d | $usersHour/h | $usersFiveMin/5m | ".
        "$usersMinute/m | $usersSecond/s</td>".
        "<td valign=top align=right width=25%>[<a href=\"server.php?action=logout" .
            "\">Logout</a>]</td>".
        "</tr></table><br><br><br>";
    }



function cm_showData() {

    global $tableNamePrefix, $remoteIP;


    cm_checkPassword( "show_data" );

    
    cm_generateHeader();
    
    
    $search = cm_requestFilter( "search", "/[A-Z0-9_@. -]+/i" );
    $order_by = cm_requestFilter( "order_by", "/[A-Z_]+/i",
                                  "last_action_time" );
    
    // form for searching users
?>
        <hr><table border=0 width = 100%><tr>
             <td>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="show_data">
    <INPUT TYPE="hidden" NAME="order_by" VALUE="<?php echo $order_by;?>">
    <INPUT TYPE="text" MAXLENGTH=40 SIZE=20 NAME="search"
             VALUE="<?php echo $search;?>">
    <INPUT TYPE="Submit" VALUE="Search">
    </FORM>
             </td>
             <td align=right>
<FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="show_recent_user_emails">
    Past <INPUT TYPE="text" MAXLENGTH=10 SIZE=10 NAME="day_limit"
             VALUE="7"> Days  
             <INPUT TYPE="Submit" VALUE="Get User Emails">
    </FORM>
             </td>
             </tr></table>
        <hr>
<?php

             
    cm_showDataUserList();
    





    echo "<hr>";

    echo "<a href=\"server.php?action=show_log\">".
        "Show log</a>";
    echo "<hr>";
    global $callAdminInEmergency;
    if( $callAdminInEmergency ) {    
        echo "<a href=\"server.php?action=test_admin_call\">".
            "Test phone call to admin</a>";
        echo "<hr>";
        }
    
    echo "Generated for $remoteIP\n";
    
    }





function cm_showRecentUserEmails() {
    global $tableNamePrefix, $remoteIP;


    cm_checkPassword( "show_recent_user_emails" );

    cm_generateHeader();

    $day_limit = cm_requestFilter( "day_limit", "/\d+/", 7 );
    
    $query = "set group_concat_max_len=100000;";

    cm_queryDatabase( $query );
    
    
    $query = "SELECT COUNT(*), GROUP_CONCAT( email separator ', ') ".
        "FROM $tableNamePrefix"."users ".
        "WHERE last_action_time > ".
        "      SUBTIME( CURRENT_TIMESTAMP, '$day_limit 0:00:00' );";
        
    $result = cm_queryDatabase( $query );

    $count = mysql_result( $result, 0, 0 );
    $emailList = mysql_result( $result, 0, 1 );


    echo "$count users played the game in the past $day_limit days:<br><br>";

    echo "$emailList";

    echo "<br><br>END";
    }






function cm_showStats() {
    global $tableNamePrefix, $remoteIP;


    cm_checkPassword( "show_stats" );

    cm_generateHeader();
    
    $query = "SELECT * ".
        "FROM $tableNamePrefix"."server_stats ORDER BY stat_date;";
    $result = cm_queryDatabase( $query );

    $numFields = mysql_num_fields( $result );
    $numRows = mysql_numrows( $result );


    echo "<br><table border=1>\n";

    $bgColor = "#EEEEEE";
    $altBGColor = "#CCCCCC";


    echo "<tr>";
    for( $i=0; $i<$numFields; $i++ ) {
        $name = mysql_field_name( $result, $i );

        echo "<td><b>$name</b></td>";
        }
    echo "</tr>\n";
    
        
    for( $i=0; $i<$numRows; $i++ ) {

        echo "<tr>";
        for( $j=0; $j<$numFields; $j++ ) {
            $value = mysql_result( $result, $i, $j );

            echo "<td bgcolor=$bgColor align=right>$value</td>";
            }
        echo "</tr>\n";
        
        $temp = $bgColor;
        $bgColor = $altBGColor;
        $altBGColor = $temp;
        }

    echo "</table>\n";

    }



function cm_blockUserID() {
    cm_checkPassword( "block_user_id" );


    global $tableNamePrefix;
        
    $user_id = cm_getUserID();

    $blocked = cm_requestFilter( "blocked", "/[01]/" );

    // don't touch admin
    if( cm_updateUser_internal( $user_id, $blocked, -1 ) ) {
        cm_showData();
        }
    }



function cm_updateUser() {
    cm_checkPassword( "update_user" );


    $user_id = cm_getUserID();

    $blocked = cm_requestFilter( "blocked", "/[1]/", "0" );
    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i" );

    if( cm_updateUser_internal( $user_id, $blocked, $email ) ) {
        cm_showDetail();
        }
    }



// set any to -1 to leave unchanged
// returns 1 on success
function cm_updateUser_internal( $user_id, $blocked, $email ) {
    
    global $tableNamePrefix;
        
    
    global $remoteIP;
    

    
    $query = "SELECT user_id, blocked, email ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    if( $numRows == 1 ) {
        $old_blocked = mysql_result( $result, 0, "blocked" );
        $old_email = mysql_result( $result, 0, "email" );

        if( $blocked == -1 ) {
            $blocked = $old_blocked;
            }
        if( $email == -1 ) {
            $email = $old_email;
            }
        
        
        $query = "UPDATE $tableNamePrefix"."users SET " .
            "blocked = '$blocked', email = '$email' " .
            "WHERE user_id = '$user_id';";
        
        $result = cm_queryDatabase( $query );
        
        return 1;
        }
    else {
        cm_log( "$user_id not found for $remoteIP" );

        echo "$user_id not found";
        }
    return 0;
    }







$cm_mysqlLink;




// general-purpose functions down here, many copied from seedBlogs

/**
 * Connects to the database according to the database variables.
 *
 * If $inTrackStats is true, will save connection count stats to database.
 */  
function cm_connectToDatabase( $inTrackStats = true) {
    global $databaseServer,
        $databaseUsername, $databasePassword, $databaseName,
        $cm_mysqlLink;
    
    
    $cm_mysqlLink =
        mysql_connect( $databaseServer, $databaseUsername, $databasePassword );


    if( ! $cm_mysqlLink && mysql_errno() == 1040 ) {
        // too many mysql connections!
        
        // sleep before displaying an error message
        // this will give the client a chance to give up on this
        // connection and try reconnecting again
        // (without our error message screwing it up)

        // 30 seconds should be long enough.
        sleep( 30 );

        // note that this is better than retrying the mysql connection
        // here after sleeping, because the client will give up on
        // us by that time anyway, and the connection that we make
        // after sleeping will consume resources but be wasted.
        }
    
    if( !$cm_mysqlLink ) {
        
        cm_operationError( "Could not connect to database server: " .
                           mysql_error() );
        }
    
    
    mysql_select_db( $databaseName )
        or cm_operationError( "Could not select $databaseName database: " .
                              mysql_error() );


    $result = mysql_query( "SHOW FULL PROCESSLIST;", $cm_mysqlLink );

    $numRows = mysql_numrows( $result );

    if( $inTrackStats ) {
        cm_incrementStat( "database_connections" );

        cm_updateMaxStat( "max_concurrent_connections", $numRows );
        }
    
    global $mysqlConnectionCountThreshold;

    if( $numRows > $mysqlConnectionCountThreshold ) {
        cm_informAdmin(
            "This is a warning message generated by ".
            "The Cordial Minuet server.  ".
            "The data base currently has $numRows connections. ".
            "The warning threshold is $mysqlConnectionCountThreshold ".
            "connections." );
        }
    }


 
/**
 * Closes the database connection.
 */
function cm_closeDatabase() {
    global $cm_mysqlLink;
    
    mysql_close( $cm_mysqlLink );
    }



/**
 * Queries the database, and dies with an error message on failure.
 *
 * @param $inQueryString the SQL query string.
 * @param $inDeadlockFatal 1 to treat a deadlock as a fatal error (default)
 *        or 0 to return an error code on deadlock.
 *
 * @return a result handle that can be passed to other mysql functions or
 *        FALSE on deadlock (if deadlock is not a fatal error).
 */
function cm_queryDatabase( $inQueryString, $inDeadlockFatal=1 ) {
    global $cm_mysqlLink;

    if( gettype( $cm_mysqlLink ) != "resource" ) {
        // not a valid mysql link?
        cm_connectToDatabase();
        }
    
    $result = mysql_query( $inQueryString, $cm_mysqlLink );
    
    if( $result == FALSE ) {

        $errorNumber = mysql_errno();

        // server lost or gone?
        if( $errorNumber == 2006 ||
            $errorNumber == 2013 ||
            // access denied?
            $errorNumber == 1044 ||
            $errorNumber == 1045 ||
            // no db selected?
            $errorNumber == 1046 ) {

            // connect again?
            cm_closeDatabase();
            cm_connectToDatabase();

            $result = mysql_query( $inQueryString, $cm_mysqlLink )
                or cm_operationError(
                    "Database query failed:<BR>$inQueryString<BR><BR>" .
                    mysql_error() );
            }
        else if( $errorNumber == 1205 ) {
            // lock wait timeout exceeded
            // probably some wayward process


            $logMessage = mysql_error() .
                " for query:<br>\n$inQueryString<br><br>\n\n";


            $result = mysql_query( "SELECT connection_id();", $cm_mysqlLink );

            $ourID = mysql_result( $result, 0, 0 );

            $logMessage = $logMessage .
                "Our process ID: $ourID<br><br>\n\n";

            $logMessage = $logMessage .
                "Process list:<br>\n";

            
            $result = mysql_query( "SHOW FULL PROCESSLIST;", $cm_mysqlLink );

            $numRows = mysql_numrows( $result );
            
            $oldestID = $ourID;
    
            for( $i=0; $i<$numRows; $i++ ) {
                $id = mysql_result( $result, $i,
                                    "id" );
                $time = mysql_result( $result, $i,
                                      "time" );
                $info = mysql_result( $result, $i,
                                      "info" );

                $logMessage = $logMessage .
                    "ID: $id   Time: $time   Info: $info<br>\n";

                if( $id == $ourID ) {
                    }
                else if( $id != $ourID &&
                         $id < $oldestID ) {
                    $oldestID = $id;
                    }
                }


            $shouldRestartQuery = false;
            
            if( $oldestID != $ourID ) {

                $logMessage = $logMessage .
                    "<br><br>\n\nKilling oldest process $oldestID";
                
                $result = mysql_query( "KILL $oldestID;", $cm_mysqlLink );

                
                // just to be safe, kick next flush forward by two intervals
                $logMessage = $logMessage . "<br><br>\n\nDelaying next flush";
                
                global $cm_flushInterval, $tableNamePrefix;
                $query = "UPDATE $tableNamePrefix"."server_globals SET " .
                    "last_flush_time = ".
                    "ADDTIME( ".
                    "         ADDTIME( CURRENT_TIMESTAMP, ".
                    "                  '$cm_flushInterval' ), ".
                    "         '$cm_flushInterval' );";
    
                $result = cm_queryDatabase( $query );
                cm_queryDatabase( "COMMIT;" );


                
                $logMessage = $logMessage . "<br><br>\n\nRestarting query";
                
                $shouldRestartQuery = true;
                }
            else {
                $logMessage = $logMessage .
                    "<br><br>\n\nOldest process is this one?  Giving up.";
                }
            
                    
            cm_log( $logMessage );

            
            global $adminEmail, $emailAdminOnFatalError;

            if( $emailAdminOnFatalError ) {    
                cm_mail( $adminEmail, "Cordial Minuet lock wait timeout",
                         $logMessage );
                }

            if( $shouldRestartQuery ) {
                // call self again
                // if we lock wait timeout again, we'll kill the next oldest
                // process and try again, until none are left, if necessary

                return cm_queryDatabase( $inQueryString, $inDeadlockFatal );
                }
            
            }
        else if( $inDeadlockFatal == 0 && $errorNumber == 1213 ) {
            // deadlock detected, but it's not a fatal error
            // caller will handle it
            return FALSE;
            }
        else {
            // some other error (we're still connected, so we can
            // add log messages to database
            cm_fatalError( "Database query failed:<BR>$inQueryString<BR><BR>" .
                           mysql_error() .
                           "<br>(error number $errorNumber)<br>" );
            }
        }
    

    return $result;
    }



/**
 * Gets the number of rows MATCHED by the last UPDATE query.
 *
 * For UPDATE queries, this will sometimes return a larger value than
 * mysql_affected_rows(), because some rows may already contain the updated
 * data values and therefore not be affected by the UPDATE.
 *
 * This is especially important in places where a timed-out UPDATE query
 * might be retried in another thread (the first one might go through, causing
 * the second one to affect 0 rows).
 */
function cm_getMySQLRowsMatchedByUpdate() {

    // format of mysql_info() after UPDATE is string like:
    // Rows matched: 0 Changed: 0 Warnings: 0
    // Thus, if we match the first int, we get what we want.
    $numMatches = preg_match( "/\d+/",
                              mysql_info(), $matches );

    if( $numMatches != 1 ) {
        // some kind of error?
        return 0;
        }
        
    return $matches[0];
    }



/**
 * Gets the CURRENT_TIMESTAMP string from MySQL database.
 */
function cm_getMySQLTimestamp() {
    $result = cm_queryDatabase( "SELECT CURRENT_TIMESTAMP;" );
    return mysql_result( $result, 0, "CURRENT_TIMESTAMP" );
    }




/**
 * Checks whether a table exists in the currently-connected database.
 *
 * @param $inTableName the name of the table to look for.
 *
 * @return 1 if the table exists, or 0 if not.
 */
function cm_doesTableExist( $inTableName ) {
    // check if our table exists
    $tableExists = 0;
    
    $query = "SHOW TABLES";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );


    for( $i=0; $i<$numRows && ! $tableExists; $i++ ) {

        $tableName = mysql_result( $result, $i, 0 );
        
        if( $tableName == $inTableName ) {
            $tableExists = 1;
            }
        }
    return $tableExists;
    }



function cm_log( $message ) {
    global $enableLog, $tableNamePrefix;

    if( $enableLog ) {
        $user_id = cm_getUserID();
        
        if( $user_id != "" ) {
            $message = "[user_id = $user_id] " . $message;
            }

        $slashedMessage = mysql_real_escape_string( $message );
        
        $query = "INSERT INTO $tableNamePrefix"."log( entry, entry_time ) ".
            "VALUES( '$slashedMessage', CURRENT_TIMESTAMP );";
        $result = cm_queryDatabase( $query );
        }
    }



/**
 * Displays the error page and dies.
 *
 * @param $message the error message to display on the error page.
 */
function cm_fatalError( $message ) {
    //global $errorMessage;

    // set the variable that is displayed inside error.php
    //$errorMessage = $message;
    
    //include_once( "error.php" );

    // print error message, with backtrace, and log it.
    $logMessage = "Fatal error:  $message\n" . cm_getBacktrace();
    
    echo( $logMessage );

    global $emailAdminOnFatalError, $adminEmail;

    if( $emailAdminOnFatalError ) {
        cm_mail( $adminEmail, "Cordial Minuet fatal error",
                 $logMessage );
        }
    
    cm_log( $logMessage );
    
    die();
    }



/**
 * Displays the operation error message and dies.
 *
 * @param $message the error message to display.
 */
function cm_operationError( $message ) {
    
    // for now, just print error message
    echo( "ERROR:  $message" );
    die();
    }



// found here:
// http://www.php.net/manual/en/function.debug-print-backtrace.php
function cm_getBacktrace() {
    ob_start();
    debug_print_backtrace();
    $trace = ob_get_contents();
    ob_end_clean();

    // Remove first item from backtrace as it's this function which
    // is redundant.
    $trace =
        preg_replace( '/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1 );
    
    // Renumber backtrace items.
    $trace = preg_replace( '/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace );
    
    return $trace;
    }




/**
 * Displays the non-fatal error page and dies.
 *
 * @param $message the error message to display on the error page.
 */
function cm_nonFatalError( $message ) {

    cm_checkPassword( "nonFatalError" );
    
     echo "[<a href=\"server.php?action=show_data" .
         "\">Main</a>]<br><br><br>";
    
    // for now, just print error message
    $logMessage = "Error:  $message";
    
    echo( $logMessage );

    cm_log( $logMessage );
    
    die();
    }



/**
 * Custom handler to override default Notice handling.
 */
function cm_noticeAndWarningHandler( $errno, $errstr, $errfile, $errline ) {

    $errorName = "Notice";

    if( $errno == E_WARNING ) {
        $errorName = "Warning";
        }
    
    // copy format of default Notice/Warning message (without HTML):
    $logMessage =
        "$errorName:  $errstr in $errfile on line $errline\n" .
        cm_getBacktrace();


    echo( $logMessage . "\n" );

    // treat notices as reportable failures, because they cause protocol
    // failures for client
    global $emailAdminOnFatalError, $adminEmail;


    if( $emailAdminOnFatalError ) {

        cm_mail( $adminEmail, "Cordial Minuet $errorName",
                 $logMessage );
        
        }

    

    cm_log( $logMessage );
    }





/**
 * Recursively applies the addslashes function to arrays of arrays.
 * This effectively forces magic_quote escaping behavior, eliminating
 * a slew of possible database security issues. 
 *
 * @inValue the value or array to addslashes to.
 *
 * @return the value or array with slashes added.
 */
function cm_addslashes_deep( $inValue ) {
    return
        ( is_array( $inValue )
          ? array_map( 'cm_addslashes_deep', $inValue )
          : addslashes( $inValue ) );
    }



/**
 * Recursively applies the stripslashes function to arrays of arrays.
 * This effectively disables magic_quote escaping behavior. 
 *
 * @inValue the value or array to stripslashes from.
 *
 * @return the value or array with slashes removed.
 */
function cm_stripslashes_deep( $inValue ) {
    return
        ( is_array( $inValue )
          ? array_map( 'sb_stripslashes_deep', $inValue )
          : stripslashes( $inValue ) );
    }




/**
 * Filters a $_REQUEST variable using a regex match.
 *
 * Returns "" (or specified default value) if there is no match.
 */
function cm_requestFilter( $inRequestVariable, $inRegex, $inDefault = "" ) {
    if( ! isset( $_REQUEST[ $inRequestVariable ] ) ) {
        return $inDefault;
        }
    
    $numMatches = preg_match( $inRegex,
                              $_REQUEST[ $inRequestVariable ], $matches );

    if( $numMatches != 1 ) {
        return $inDefault;
        }
        
    return $matches[0];
    }





// this function checks the password directly from a request variable
// or via hash from a cookie.
//
// It then sets a new cookie for the next request.
//
// This avoids storing the password itself in the cookie, so a stale cookie
// (cached by a browser) can't be used to figure out the password and log in
// later. 
function cm_checkPassword( $inFunctionName ) {
    $password = "";
    $password_hash = "";

    $badCookie = false;
    
    
    global $accessPasswords, $tableNamePrefix, $remoteIP, $enableYubikey,
        $passwordHashingPepper;

    $cookieName = $tableNamePrefix . "cookie_password_hash";

    
    $passwordSent = false;
    
    if( isset( $_REQUEST[ "password" ] ) ) {
        $passwordSent = true;
        
        $password = cm_hmac_sha1( $passwordHashingPepper,
                                  $_REQUEST[ "password" ] );

        // generate a new hash cookie from this password
        $newSalt = time();
        $newHash = md5( $newSalt . $password );
        
        $password_hash = $newSalt . "_" . $newHash;
        }
    else if( isset( $_COOKIE[ $cookieName ] ) ) {
        cm_checkReferrer();

        $password_hash = $_COOKIE[ $cookieName ];
        
        // check that it's a good hash
        
        $hashParts = preg_split( "/_/", $password_hash );

        // default, to show in log message on failure
        // gets replaced if cookie contains a good hash
        $password = "(bad cookie:  $password_hash)";

        $badCookie = true;
        
        if( count( $hashParts ) == 2 ) {
            
            $salt = $hashParts[0];
            $hash = $hashParts[1];

            foreach( $accessPasswords as $truePassword ) {    
                $trueHash = md5( $salt . $truePassword );
            
                if( $trueHash == $hash ) {
                    $password = $truePassword;
                    $badCookie = false;
                    }
                }
            
            }
        }
    else {
        // no request variable, no cookie
        // cookie probably expired
        $badCookie = true;
        $password_hash = "(no cookie.  expired?)";
        }
    
        
    
    if( ! in_array( $password, $accessPasswords ) ) {

        if( ! $badCookie ) {
            
            echo "Incorrect password.";

            cm_log( "Failed $inFunctionName access with password:  ".
                    "$password" );
            }
        else {
            echo "Session expired.";
                
            cm_log( "Failed $inFunctionName access with bad cookie:  ".
                    "$password_hash" );
            }
        
        die();
        }
    else {
        
        if( $passwordSent && $enableYubikey ) {
            global $yubikeyIDs, $yubicoClientID, $yubicoSecretKey,
                $serverSecretKey;
            
            $yubikey = $_REQUEST[ "yubikey" ];

            $index = array_search( $password, $accessPasswords );
            $yubikeyIDList = preg_split( "/:/", $yubikeyIDs[ $index ] );

            $providedID = substr( $yubikey, 0, 12 );

            if( ! in_array( $providedID, $yubikeyIDList ) ) {
                echo "Provided Yubikey does not match ID for this password.";
                die();
                }
            
            
            $nonce = cm_hmac_sha1( $serverSecretKey, uniqid() );
            
            $callURL =
                "http://api2.yubico.com/wsapi/2.0/verify?id=$yubicoClientID".
                "&otp=$yubikey&nonce=$nonce";
            
            $result = trim( file_get_contents( $callURL ) );

            $resultLines = preg_split( "/\s+/", $result );

            sort( $resultLines );

            $resultPairs = array();

            $messageToSignParts = array();
            
            foreach( $resultLines as $line ) {
                // careful here, because = is used in base-64 encoding
                // replace first = in a line (the key/value separator)
                // with #
                
                $lineToParse = preg_replace( '/=/', '#', $line, 1 );

                // now split on # instead of =
                $parts = preg_split( "/#/", $lineToParse );

                $resultPairs[$parts[0]] = $parts[1];

                if( $parts[0] != "h" ) {
                    // include all but signature in message to sign
                    $messageToSignParts[] = $line;
                    }
                }
            $messageToSign = implode( "&", $messageToSignParts );

            $trueSig =
                base64_encode(
                    hash_hmac( 'sha1',
                               $messageToSign,
                               // need to pass in raw key
                               base64_decode( $yubicoSecretKey ),
                               true) );
            
            if( $trueSig != $resultPairs["h"] ) {
                echo "Yubikey authentication failed.<br>";
                echo "Bad signature from authentication server<br>";
                die();
                }

            $status = $resultPairs["status"];
            if( $status != "OK" ) {
                echo "Yubikey authentication failed: $status";
                die();
                }

            }
        
        // set cookie again, renewing it, expires in 24 hours
        $expireTime = time() + 60 * 60 * 24;
    
        setcookie( $cookieName, $password_hash, $expireTime, "/" );
        }
    }
 



function cm_clearPasswordCookie() {
    global $tableNamePrefix;

    $cookieName = $tableNamePrefix . "cookie_password_hash";

    // expire 24 hours ago (to avoid timezone issues)
    $expireTime = time() - 60 * 60 * 24;

    setcookie( $cookieName, "", $expireTime, "/" );
    }




function cm_getTotalSpace() {
    global $tableNamePrefix, $databaseName;

    $query = "SELECT SUM( DATA_LENGTH ) ".
        "FROM information_schema.tables ".
        "WHERE TABLE_NAME like '$tableNamePrefix%' AND ".
        "TABLE_SCHEMA like '$databaseName';";

    $result = cm_queryDatabase( $query );

    return mysql_result( $result, 0, 0 );
    }


function cm_countUsers() {
    global $tableNamePrefix;

    $query = "SELECT COUNT(*) ".
        "FROM $tableNamePrefix"."users;";
    $result = cm_queryDatabase( $query );

    return mysql_result( $result, 0, 0 );
    }



// counts users in a given time interval (string time interval) 
function cm_countUsersTime( $inInterval ) {
    global $tableNamePrefix;

    $query = "SELECT COUNT(*) ".
        "FROM $tableNamePrefix"."users ".
        "WHERE last_action_time > ".
        "SUBTIME( CURRENT_TIMESTAMP, '$inInterval' );";
    $result = cm_queryDatabase( $query );

    return mysql_result( $result, 0, 0 );
    }
 






function cm_hmac_sha1( $inKey, $inData ) {
    return hash_hmac( "sha1", 
                      $inData, $inKey );
    }



function cm_hmac_sha1_raw( $inKey, $inData ) {
    return hash_hmac( "sha1", 
                      $inData, $inKey, true );
    }



function cm_hex2bin( $inHexString ) {
    $pos = 0;
    $result = "";
    $length = strlen( $inHexString );
    
    while( $pos < $length ) {
        $code = hexdec( substr( $inHexString, $pos, 2 ) );
        $pos = $pos + 2;
        $result .= chr( $code ); 
        }

    return $result;
    }




// convert a binary string into a "readable" base-32 encoding
function cm_readableBase32Encode( $inBinaryString ) {
    global $readableBase32DigitArray;
    
    $binaryDigits = str_split( $inBinaryString );

    // string of 0s and 1s
    $binString = "";
    
    foreach( $binaryDigits as $digit ) {
        $binDigitString = decbin( ord( $digit ) );

        // pad with 0s
        $binDigitString =
            substr( "00000000", 0, 8 - strlen( $binDigitString ) ) .
            $binDigitString;

        $binString = $binString . $binDigitString;
        }

    // now have full string of 0s and 1s for $inBinaryString

    return cm_readableBase32EncodeFromBitString( $binString );
    }



// encodes a string of 0s and 1s into an ASCII readable-base32 string 
function cm_readableBase32EncodeFromBitString( $inBitString ) {
    global $readableBase32DigitArray;


    // chunks of 5 bits
    $chunksOfFive = str_split( $inBitString, 5 );

    $encodedString = "";
    foreach( $chunksOfFive as $chunk ) {
        $index = bindec( $chunk );

        $encodedString = $encodedString . $readableBase32DigitArray[ $index ];
        }
    
    return $encodedString;
    }
 


// decodes an ASCII readable-base32 string into a string of 0s and 1s 
function cm_readableBase32DecodeToBitString( $inBase32String ) {
    global $readableBase32DigitArray;
    
    $digits = str_split( $inBase32String );

    $bitString = "";

    foreach( $digits as $digit ) {
        $index = array_search( $digit, $readableBase32DigitArray );

        $binDigitString = decbin( $index );

        // pad with 0s
        $binDigitString =
            substr( "00000", 0, 5 - strlen( $binDigitString ) ) .
            $binDigitString;

        $bitString = $bitString . $binDigitString;
        }

    return $bitString;
    }
 
 
 
// decodes a ASCII hex string into an array of 0s and 1s 
function cm_hexDecodeToBitString( $inHexString ) {
        global $readableBase32DigitArray;
    
    $digits = str_split( $inHexString );

    $bitString = "";

    foreach( $digits as $digit ) {
        $index = hexdec( $digit );

        $binDigitString = decbin( $index );

        // pad with 0s
        $binDigitString =
            substr( "0000", 0, 4 - strlen( $binDigitString ) ) .
            $binDigitString;

        $bitString = $bitString . $binDigitString;
        }

    return $bitString;
    }









function cm_mail( $inEmail,
                  $inSubject,
                  $inBody ) {
    
    global $useSMTP, $siteEmailAddress;

    if( $useSMTP ) {
        require_once "Mail.php";

        global $smtpHost, $smtpPort, $smtpUsername, $smtpPassword;

        $headers = array( 'From' => $siteEmailAddress,
                          'To' => $inEmail,
                          'Subject' => $inSubject );
        
        $smtp = Mail::factory( 'smtp',
                               array ( 'host' => $smtpHost,
                                       'port' => $smtpPort,
                                       'auth' => true,
                                       'username' => $smtpUsername,
                                       'password' => $smtpPassword ) );


        $mail = $smtp->send( $inEmail, $headers, $inBody );


        if( PEAR::isError( $mail ) ) {
            cm_log( "Email send failed:  " .
                    $mail->getMessage() );
            return false;
            }
        else {
            return true;
            }
        }
    else {
        // raw sendmail
        $mailHeaders = "From: $siteEmailAddress";
        
        return mail( $inEmail,
                     $inSubject,
                     $inBody,
                     $mailHeaders );
        }
    }





// makes a Twilio call to the admin with $inTextMessage as text-to-speech
function cm_callAdmin( $inTextMessage ) {

    
    global $twilioFromNumber, $twilioToNumber, $twilioAcountID,
        $twilioAuthToken;

    $fromParam = urlencode( $twilioFromNumber );
    $toParam = urlencode( $twilioToNumber );

    $encodedMessage = urlencode( $inTextMessage );


    // repeat 4 times
    $messageCopies =
        "Message%5B0%5D=$encodedMessage".
        "&".
        "Message%5B1%5D=$encodedMessage".
        "&".
        "Message%5B2%5D=$encodedMessage".
        "&".
        "Message%5B3%5D=$encodedMessage";
    

    $twimletURL = "http://twimlets.com/message?$messageCopies";
    
    $urlParam = urlencode( $twimletURL );
    
    
    global $curlPath;

    $curlCallString =
    "$curlPath -X POST ".
    "'https://api.twilio.com/2010-04-01/Accounts/$twilioAcountID/Calls.json' ".
    "-d 'To=$toParam'  ".
    "-d 'From=$fromParam' ".
    "-d ".
    "'Url=$urlParam' ".
    "-u $twilioAcountID:$twilioAuthToken";

    exec( $curlCallString );

    return;
    }





// informs admin by email and phone, if either are enabled
// of a non-fatal but serious condition
function cm_informAdmin( $inMessage ) {
    global $emailAdminOnFatalError, $callAdminInEmergency;


    if( $emailAdminOnFatalError ) {
        global $adminEmail;
        
        cm_mail( $adminEmail, "Cordial Minuet server issue",
                 $inMessage );
        }
    if( $callAdminInEmergency ) {
        cm_callAdmin( $inMessage );
        }
    }

    


?>

