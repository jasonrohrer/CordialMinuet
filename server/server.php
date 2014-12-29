<?php



// for testing
//sleep( 5 );


// server will tell clients to upgrade to this version
global $cm_version;
$cm_version = "11";


// leave an older version here IF older clients can also connect safely
// (newer clients must use this old version number in their account hmac
//  too).
// NOTE that if old clients are incompatible, both numbers should be updated.
global $cm_accountHmacVersion;
$cm_accountHmacVersion = "9";







global $cm_flushInterval;
$cm_flushInterval = "0 0:02:0.000";



global $cm_gameCoins;
$cm_gameCoins = 100;


// override the default Notice and Warning handler 
set_error_handler( "cm_noticeAndWarningHandler", E_NOTICE | E_WARNING );



// edit settings.php to change server' settings
include( "settings.php" );


include( "semaphores.php" );


// no end-user settings below this point


// for use in readable base-32 encoding
// elimates 0/O and 1/I
global $readableBase32DigitArray;
$readableBase32DigitArray =
    array( "2", "3", "4", "5", "6", "7", "8", "9",
           "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
           "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" );





// ISO country codes
global $allowedCountries;

$allowedCountries = array(
    'AF' => 'Afghanistan',
    'AX' => 'Aland Islands',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua And Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia And Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'IO' => 'British Indian Ocean Territory',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos (Keeling) Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CD' => 'Congo, Democratic Republic',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'CI' => 'Cote D\'Ivoire',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands (Malvinas)',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island & Mcdonald Islands',
    'VA' => 'Holy See (Vatican City State)',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran, Islamic Republic Of',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IM' => 'Isle Of Man',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KR' => 'Korea',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Lao People\'s Democratic Republic',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libyan Arab Jamahiriya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'MX' => 'Mexico',
    'FM' => 'Micronesia, Federated States Of',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
    'MP' => 'Northern Mariana Islands',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestinian Territory, Occupied',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RU' => 'Russian Federation',
    'RW' => 'Rwanda',
    'BL' => 'Saint Barthelemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts And Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin',
    'PM' => 'Saint Pierre And Miquelon',
    'VC' => 'Saint Vincent And Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome And Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia And Sandwich Isl.',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard And Jan Mayen',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad And Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks And Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'GB' => 'United Kingdom',
    'US' => 'United States',
    'UM' => 'United States Outlying Islands',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VE' => 'Venezuela',
    'VN' => 'Viet Nam',
    'VG' => 'Virgin Islands, British',
    'VI' => 'Virgin Islands, U.S.',
    'WF' => 'Wallis And Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe'
    );



global $currencyMap;

$currencyMap = array(
    'AU' => 'AUD',
    'AT' => 'USD',
    'BE' => 'EUR',
    'BR' => 'BRL',
    'CA' => 'CAD',
    'EE' => 'USD',
    'FI' => 'USD',
    'FR' => 'EUR',
    'DE' => 'EUR',
    'GB' => 'GBP',
    'GR' => 'EUR',
    'HK' => 'HKD',
    'HU' => 'USD',
    'IS' => 'USD',
    'IN' => 'INR',
    'IE' => 'EUR',
    'IT' => 'EUR',
    'LU' => 'USD',
    'MY' => 'MYR',
    'MX' => 'MXN',
    'NL' => 'USD',
    'NZ' => 'NZD',
    'NO' => 'USD',
    'PL' => 'USD',
    'PT' => 'EUR',
    'SG' => 'SGD',
    'SI' => 'USD',
    'SK' => 'USD',
    'ZA' => 'ZAR',
    'ES' => 'EUR',
    'SE' => 'SEK',
    'CH' => 'USD',
    'TH' => 'THB',
    'US' => 'USD'
    );









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
<HEAD><TITLE>Cordial Minuet Server Web-based setup</TITLE></HEAD>
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
global $action;
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



//cm_patchOldWithdrawals();


global $shutdownMode;


if( $shutdownMode &&
    ( $action == "check_required_version" ||
      $action == "check_user" ||
      $action == "check_sequence_number" ||
      $action == "check_hmac" ||
      $action == "get_deposit_fees" ||
      $action == "make_deposit" ||
      $action == "get_withdrawal_methods" ||
      $action == "send_check" ||
      $action == "account_transfer" ||
      $action == "check_in_person_code" ||
      $action == "join_games" ||
      $action == "wait_game_start" ||
      $action == "get_balance" ||
      $action == "list_games" ||
      $action == "start_next_round" ) ) {
    
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
else if( $action == "check_sequence_number" ) {
    cm_checkSequenceNumber();
    }
else if( $action == "check_hmac" ) {
    cm_checkHmac();
    }
else if( $action == "check_in_person_code" ) {
    cm_checkInPersonCode();
    }
else if( $action == "get_balance" ) {
    cm_getBalance();
    }
else if( $action == "get_deposit_fees" ) {
    cm_getDepositFees();
    }
else if( $action == "make_deposit" ) {
    cm_makeDeposit();
    }
else if( $action == "get_withdrawal_methods" ) {
    cm_getWithdrawalMethods();
    }
else if( $action == "send_check" ) {
    cm_sendCheck();
    }
else if( $action == "account_transfer" ) {
    cm_accountTransfer();
    }
else if( $action == "join_game" ) {
    cm_joinGame();
    }
else if( $action == "wait_game_start" ) {
    cm_waitGameStart();
    }
else if( $action == "leave_game" ) {
    cm_leaveGame();
    }
else if( $action == "list_games" ) {
    cm_listGames();
    }
else if( $action == "get_game_state" ) {
    cm_getGameState();
    }
else if( $action == "make_move" ) {
    cm_makeMove();
    }
else if( $action == "make_reveal_move" ) {
    cm_makeRevealMove();
    }
else if( $action == "make_bet" ) {
    cm_makeBet();
    }
else if( $action == "fold_bet" ) {
    cm_foldBet();
    }
else if( $action == "end_round" ) {
    cm_endRound();
    }
else if( $action == "start_next_round" ) {
    cm_startNextRound();
    }
else if( $action == "wait_move" ) {
    cm_waitMove();
    }
else if( $action == "check_for_flush" ) {
    cm_checkForFlush();
    echo "OK";
    }
else if( $action == "make_account" ) {
    cm_makeAccount();
    }
else if( $action == "show_data" ) {
    cm_showData();
    }
else if( $action == "show_detail" ) {
    cm_showDetail();
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
else if( $action == "update_user" ) {
    cm_updateUser();
    }
else if( $action == "toggle_card_proof" ) {
    cm_toggleCardProof();
    }
else if( $action == "in_person_deposit" ) {
    cm_inPersonDeposit();
    }
else if( $action == "in_person_withdrawal" ) {
    cm_inPersonWithdrawal();
    }
else if( $action == "recompute_elo" ) {
    cm_recomputeElo();
    }
else if( $action == "logout" ) {
    cm_logout();
    }
else if( $action == "leaders_dollar" ) {
    cm_leadersDollar();
    }
else if( $action == "leaders_profit" ) {
    cm_leadersProfit();
    }
else if( $action == "leaders_profit_ratio" ) {
    cm_leadersProfitRatio();
    }
else if( $action == "leaders_win_loss_ratio" ) {
    cm_leadersWinLossRatio();
    }
else if( $action == "leaders_elo" ) {
    cm_leadersElo();
    }
else if( $action == "leaders_elo_all" ) {
    cm_leadersEloAll();
    }
else if( $action == "tournament_report" ) {
    cm_tournamentReport();
    }
else if( $action == "users_graph" ) {
    cm_usersGraph();
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
        cm_doesTableExist( $tableNamePrefix."random_nouns" ) &&
        cm_doesTableExist( $tableNamePrefix."users" ) &&
        cm_doesTableExist( $tableNamePrefix."cards" ) &&
        cm_doesTableExist( $tableNamePrefix."deposits" ) &&
        cm_doesTableExist( $tableNamePrefix."withdrawals" ) &&
        cm_doesTableExist( $tableNamePrefix."game_ledger" ) &&
        cm_doesTableExist( $tableNamePrefix."games" ) &&
        cm_doesTableExist( $tableNamePrefix."tournament_stats" ) &&
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








function cm_generateRandomName() {
    global $tableNamePrefix;

    $query = "SELECT GROUP_CONCAT( temp.noun SEPARATOR ' ' ) AS random_name ".
        "FROM ( SELECT noun FROM $tableNamePrefix"."random_nouns ".
        "       ORDER BY RAND() LIMIT 2) AS temp;";

    $result = cm_queryDatabase( $query );

    return mysql_result( $result, 0, 0 );
    }






/**
 * Creates the database tables needed by seedBlogs.
 */
function cm_setupDatabase() {
    global $tableNamePrefix;


    $tableName = $tableNamePrefix . "server_globals";
    if( ! cm_doesTableExist( $tableName ) ) {

        // this table contains general info about the server
        // house_dollar_balance is where unwithdrawn house game fees are
        // tracked
        // house_withdrawals is total money withdrawn from house balance 
        // use INNODB engine so table can be locked
        $query =
            "CREATE TABLE $tableName(" .
            "last_flush_time DATETIME NOT NULL, ".
            "house_dollar_balance DECIMAL(13, 4) NOT NULL, ".
            "house_withdrawals DECIMAL(13, 4) NOT NULL, ".
            "next_magic_square_seed BIGINT UNSIGNED NOT NULL, ".
            // amount of money left in chexx check-sending account
            "check_account_dollar_balance DECIMAL(13, 2) NOT NULL ".
            ") ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";

        // create one row
        // assume chexx account starts out with $5000
        $query = "INSERT INTO $tableName ".
            "VALUES ( CURRENT_TIMESTAMP, 0, 0, 1977, 5000.00 );";
        $result = cm_queryDatabase( $query );
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    
    $tableName = $tableNamePrefix . "log";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "log_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, ".
            "entry TEXT NOT NULL, ".
            "entry_time DATETIME NOT NULL );";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    // these words taken from a cognitive experiment database
    // http://www.datavis.ca/online/paivio/
    
    $tableName = $tableNamePrefix . "random_nouns";
    if( ! cm_doesTableExist( $tableName ) ) {

        // a source list of character last names
        // cumulative count is number of people in 1993 population
        // who have this name or a more common name
        // less common names have higher cumulative counts
        $query =
            "CREATE TABLE $tableName( " .
            "id SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, ".
            "noun VARCHAR(14) NOT NULL, ".
            "UNIQUE KEY( noun ) );";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";


        if( $file = fopen( "randomNouns.txt", "r" ) ) {
            $firstLine = true;

            $query = "INSERT INTO $tableName (noun) VALUES ";
            /*
			( 'bird' ),
            ( 'monster' ),
            ( 'ability' );
            */

            while( !feof( $file ) ) {
                $noun = trim( fgets( $file) );
                
                if( ! $firstLine ) {
                    $query = $query . ",";
                    }
                
                $query = $query . " ( '$noun' )";
            
                $firstLine = false;
                }
            
            fclose( $file );

            $query = $query . ";";
            
            $result = cm_queryDatabase( $query );
            }

        if( cm_doesTableExist( $tableNamePrefix ."users" ) ) {
            // add a random name for each user

            $query = "SELECT user_id, email FROM $tableNamePrefix"."users;";
            $result = cm_queryDatabase( $query );

            $numRows = mysql_numrows( $result );
            for( $i=0; $i<$numRows; $i++ ) {
                $user_id = mysql_result( $result, $i, "user_id" );
                $email = mysql_result( $result, $i, "email" );

                $random_name = cm_generateRandomName();

                $message =
                    "I just set up a new alias system for ".
                    "Cordial Minuet.\n\n". 
                    "These handles will be used for leaderboards and other ".
                    "public purposes where your true ".
                    "identity will be hidden.\n\n".
                    "Your new alias is:  $random_name\n\n".
                    "Please save this alias so that you can ".
                    "reference it later.  Your alias is not used for ".
                    "security purposes.  It's fine to share it ".
                    "with friends or even publicly if you want to ".
                    "reveal your identity.".
                    "\n\n\n".
                    "Enjoy the game!\n".
                    "Jason\n\n";
                
                cm_mail( $email, "Cordial Minuet Alias",
                         $message );
                
                $query = "UPDATE $tableNamePrefix"."users ".
                    "SET random_name = '$random_name' ".
                    "WHERE user_id = '$user_id';";
                cm_queryDatabase( $query );
                }
            }
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
            "user_id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT," .
            "account_key VARCHAR(255) NOT NULL," .
            "UNIQUE KEY( account_key )," .
            "email VARCHAR(255) NOT NULL," .
            "UNIQUE KEY( email ),".
            "random_name VARCHAR(30) NOT NULL,".
            "in_person_code VARCHAR(30) NOT NULL,".
            // 9 whole dollar digits (up to 999,999,999)
            // 4 fractional digits (0.0001 resolution)
            "dollar_balance DECIMAL(13, 4) NOT NULL,".
            "num_deposits SMALLINT UNSIGNED NOT NULL,".
            "total_deposits DECIMAL(13, 2) NOT NULL,".
            "num_withdrawals SMALLINT UNSIGNED NOT NULL,".
            "total_withdrawals DECIMAL(13, 2) NOT NULL,".
            "tax_info_on_file TINYINT UNSIGNED NOT NULL,".
            "games_created INT UNSIGNED NOT NULL,".
            "games_joined INT UNSIGNED NOT NULL,".
            "games_started INT UNSIGNED NOT NULL,".
            "total_buy_in DECIMAL(13, 2) NOT NULL,".
            "total_won DECIMAL(13, 4) NOT NULL,".
            "total_lost DECIMAL(13, 4) NOT NULL,".
            "elo_rating INT NOT NULL,".
            "last_buy_in DECIMAL(13, 2) NOT NULL,".
            "last_pay_out DECIMAL(13, 4) NOT NULL,".
            "sequence_number INT UNSIGNED NOT NULL," .
            "request_sequence_number INT UNSIGNED NOT NULL," .
            "last_action_time DATETIME NOT NULL," .
            "last_request_tag CHAR(40) NOT NULL,".
            "last_request_response TEXT NOT NULL,".
            "admin_level TINYINT UNSIGNED NOT NULL,".
            "blocked TINYINT UNSIGNED NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    $tableName = $tableNamePrefix . "cards";
    if( ! cm_doesTableExist( $tableName ) ) {
        $query =
            "CREATE TABLE $tableName(" .
            "card_id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT," .
            // stripe fingerprint is currently 16 characters long
            // this only indicates card number uniquely
            // but card numbers can be reissued in the future
            "fingerprint CHAR(16) NOT NULL,".
            // 6-character expiration date as MMYYYY
            // this, plus card number fingerprint, uniquely indentifies
            // a card
            "exp_date CHAR(6) NOT NULL,".
            "UNIQUE KEY( fingerprint, exp_date ),".
            // user who has used this card (only one user per card)
            "user_id INT UNSIGNED NOT NULL," .
            "INDEX( user_id )," .
            "last_used_time DATETIME NOT NULL," .
            "proof_on_file TINYINT UNSIGNED NOT NULL) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }

    

    $tableName = $tableNamePrefix . "deposits";
    if( ! cm_doesTableExist( $tableName ) ) {
        $query =
            "CREATE TABLE $tableName(" .
            "deposit_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT," .
            "user_id INT UNSIGNED NOT NULL," .
            "INDEX( user_id )," .
            "processing_id VARCHAR(255) NOT NULL,".
            "deposit_time DATETIME NOT NULL," .
            "INDEX( deposit_time )," .
            // the amount charged to them, including fees
            "dollar_amount DECIMAL(13, 2) NOT NULL, ".
            "fee DECIMAL(13, 2) NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    // for now, this table only tracks mailed checks
    $tableName = $tableNamePrefix . "withdrawals";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "withdrawal_id BIGINT UNSIGNED NOT NULL ".
            "              PRIMARY KEY AUTO_INCREMENT," .
            "user_id INT UNSIGNED NOT NULL," .
            "INDEX( user_id )," .
            "withdrawal_time DATETIME NOT NULL," .
            "INDEX( withdrawal_time )," .
            // the amount sent to them, excluding fee
            "dollar_amount DECIMAL(13, 2) NOT NULL, ".
            "fee DECIMAL(13, 2) NOT NULL, ".
            "email VARCHAR(255) NOT NULL," .
            "name VARCHAR(255) NOT NULL," .
            "address1 VARCHAR(255) NOT NULL," .
            "address2 VARCHAR(255) NOT NULL," .
            "city VARCHAR(255) NOT NULL," .
            "us_state CHAR(2) NOT NULL," .
            "province VARCHAR(255) NOT NULL," .
            "country VARCHAR(255) NOT NULL," .
            "postal_code VARCHAR(16) NOT NULL, ".
            "reference_code VARCHAR(255) NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    $tableName = $tableNamePrefix . "games";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "game_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT," .
            "creation_time DATETIME NOT NULL,".
            "last_action_time DATETIME NOT NULL,".
            "player_1_id INT UNSIGNED NOT NULL," .
            "INDEX( player_1_id )," .
            "player_2_id INT UNSIGNED NOT NULL," .
            "INDEX( player_2_id )," .
            "dollar_amount DECIMAL(11, 2) NOT NULL,".
            "INDEX( dollar_amount )," .
            "started TINYINT UNSIGNED NOT NULL,".
            // 36-cell square, numbers from 1 to 36, separated by #
            // character
            "game_square CHAR(125) NOT NULL,".
            "player_1_moves CHAR(13) NOT NULL,".
            "player_2_moves CHAR(13) NOT NULL,".
            "player_1_bet_made TINYINT UNSIGNED NOT NULL,".
            "player_2_bet_made TINYINT UNSIGNED NOT NULL,".
            "player_1_ended_round TINYINT UNSIGNED NOT NULL,".
            "player_2_ended_round TINYINT UNSIGNED NOT NULL,".
            "move_deadline DATETIME NOT NULL,".
            "player_1_coins TINYINT UNSIGNED NOT NULL, ".
            "player_2_coins TINYINT UNSIGNED NOT NULL, ".
            "player_1_pot_coins TINYINT UNSIGNED NOT NULL, ".
            "player_2_pot_coins TINYINT UNSIGNED NOT NULL, ".
            "semaphore_key INT UNSIGNED NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    $tableName = $tableNamePrefix . "game_ledger";
    if( ! cm_doesTableExist( $tableName ) ) {
        $query =
            "CREATE TABLE $tableName(" .
            "entry_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT," .
            // 0 for money moving to house
            "user_id INT UNSIGNED NOT NULL," .
            "INDEX( user_id )," .
            // from game table, though dead games discared
            // this ledger entry is only record of game outcome
            "game_id BIGINT UNSIGNED NOT NULL," .
            "entry_time DATETIME NOT NULL," .
            // 9 whole dollar digits (up to 999,999,999)
            // 4 fractional digits (0.0001 resolution)
            // can be positive or negative
            // (negative is money they spent (buy-in),
            // positive is money they gained (pay-out)
            "dollar_delta DECIMAL(13, 4) NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";

        
        // auto-populate with dummy entries for old transactions
        cm_queryDatabase( "SET AUTOCOMMIT = 0;" );
        
        $query = "SELECT user_id, total_lost, total_won FROM ".
            "$tableNamePrefix"."users FOR UPDATE;";
        $result = cm_queryDatabase( $query );
        $numRows = mysql_numrows( $result );
        for( $i=0; $i<$numRows; $i++ ) {
            $user_id = mysql_result( $result, $i, "user_id" );
            $total_won = mysql_result( $result, $i, "total_won" );
            $total_lost = mysql_result( $result, $i, "total_lost" );

            if( $total_won > 0 ) {
                cm_addLedgerEntry( $user_id, 0, $total_won );
                }
            if( $total_lost > 0 ) {
                cm_addLedgerEntry( $user_id, 0, -$total_lost );
                }
            }

        // auto-populate with buy-ins for live games
        $query = "SELECT game_id, dollar_amount, player_1_id, player_2_id ".
            "FROM ".
            "$tableNamePrefix"."games ".
            "WHERE player_1_id != 0 AND player_2_id != 0 FOR UPDATE;";
        $result = cm_queryDatabase( $query );
        $numRows = mysql_numrows( $result );
        for( $i=0; $i<$numRows; $i++ ) {
            $game_id = mysql_result( $result, $i, "game_id" );
            $dollar_amount = mysql_result( $result, $i, "dollar_amount" );
            $player_1_id = mysql_result( $result, $i, "player_1_id" );
            $player_2_id = mysql_result( $result, $i, "player_2_id" );

            cm_addLedgerEntry( $player_1_id, $game_id, - $dollar_amount );
            cm_addLedgerEntry( $player_2_id, $game_id, - $dollar_amount );
            }

        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    $tableName = $tableNamePrefix . "tournament_stats";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "user_id INT NOT NULL," .
            "tournament_code_name VARCHAR(255) NOT NULL," .
            "PRIMARY KEY( user_id, tournament_code_name )," .
            "update_time DATETIME NOT NULL," .
            "num_games_started INT NOT NULL,".
            "INDEX( num_games_started ),".
            "net_dollars DECIMAL(13, 4) NOT NULL,".
            "INDEX( net_dollars ) ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }

    

    
    $tableName = $tableNamePrefix . "server_stats";

    if( ! cm_doesTableExist( $tableName ) ) {

        // leave as MyISAM, because stats are only changed via
        // INSERT ... ON DUPLICATE UPDATE calls, which are atomic
        // so locking isn't needed (and not locking stats during a
        // transaction allows for more concurrency and one less deadlock
        // opportunity).
              
        $query =
            "CREATE TABLE $tableName(" .
            "stat_date DATE NOT NULL PRIMARY KEY," .
            "unique_users INT UNSIGNED NOT NULL DEFAULT 0," .

            "database_connections INT UNSIGNED NOT NULL DEFAULT 0," .
            "max_concurrent_connections INT UNSIGNED NOT NULL DEFAULT 0," .

            "deposit_count INT UNSIGNED NOT NULL DEFAULT 0, ".
            "total_deposits DECIMAL(13, 2) NOT NULL DEFAULT 0, ".
            "max_deposit DECIMAL(13, 2) NOT NULL DEFAULT 0, ".

            "withdrawal_count INT UNSIGNED NOT NULL DEFAULT 0, ".
            "total_withdrawals DECIMAL(13, 2) NOT NULL DEFAULT 0, ".
            "max_withdrawal DECIMAL(13, 2) NOT NULL DEFAULT 0, ".
            
            
            "game_count INT UNSIGNED NOT NULL DEFAULT 0,".
            "total_buy_in DECIMAL(13, 2) NOT NULL DEFAULT 0, ".
            
            "max_game_stakes DECIMAL(13, 2) NOT NULL DEFAULT 0.00, ".

            "total_house_rake DECIMAL(13, 4) NOT NULL DEFAULT 0.0000, ".
            "max_house_rake DECIMAL(13, 4) NOT NULL DEFAULT 0.0000 ".
            
            ");";
        

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
            "users_last_five_minutes INT UNSIGNED NOT NULL," .
            "users_last_hour INT UNSIGNED NOT NULL," .
            "users_last_day INT UNSIGNED NOT NULL );";
        

        $result = cm_queryDatabase( $query );


        echo "<B>$tableName</B> table created<BR>";       
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }

    
    

    }



// updates deposit log by adding dummy entries for deposits that pre-dated
// the deposit log
function cm_patchOldDeposits() {
    global $tableNamePrefix;

    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );
    
    $query = "SELECT user_id, ".
        "total_deposits - (select coalesce( sum(dollar_amount - fee), 0) ".
        "                  from $tableNamePrefix"."deposits ".
        "                  where $tableNamePrefix"."deposits.user_id = ".
        "                        $tableNamePrefix"."users.user_id ) ".
        "AS leak ".
        "FROM $tableNamePrefix"."users FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    for( $i=0; $i<$numRows; $i++ ) {
        $user_id = mysql_result( $result, $i, "user_id" );
        $leak = mysql_result( $result, $i, "leak" );

        if( $leak > 0 ) {

            $query = "INSERT INTO $tableNamePrefix"."deposits ".
                "SET user_id = '$user_id', deposit_time = CURRENT_TIMESTAMP, ".
                "dollar_amount = '$leak', ".
                "fee = '0', processing_id = 'manual_batch'; ";
            cm_queryDatabase( $query );
            }
        }

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    }




// updates deposit log by adding dummy entries for deposits that pre-dated
// the deposit log
function cm_patchOldWithdrawals() {
    global $tableNamePrefix;

    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );
    
    $query = "SELECT user_id, email, ".
        "total_withdrawals - (select coalesce( sum(dollar_amount + fee), 0) ".
        "                  from $tableNamePrefix"."withdrawals ".
        "                  where $tableNamePrefix"."withdrawals.user_id = ".
        "                        $tableNamePrefix"."users.user_id ) ".
        "AS leak ".
        "FROM $tableNamePrefix"."users FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    for( $i=0; $i<$numRows; $i++ ) {
        $user_id = mysql_result( $result, $i, "user_id" );
        $email = mysql_result( $result, $i, "email" );
        $leak = mysql_result( $result, $i, "leak" );

        if( $leak > 0 ) {

            $query = "INSERT INTO $tableNamePrefix"."withdrawals ".
                "SET user_id = '$user_id', email = '$email', ".
                "withdrawal_time = CURRENT_TIMESTAMP, ".
                "dollar_amount = '$leak', ".
                "fee = '0', name = 'manual_batch'; ";
            cm_queryDatabase( $query );
            }
        }

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
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
    global $tableNamePrefix, $staleGameTimeLimit, $gracePeriod;


    if( $gracePeriod ) {
        // skip flushing entirely during grace period
        return;
        }
    

    $tableName = "$tableNamePrefix"."server_globals";
    
    if( !cm_doesTableExist( $tableName ) ) {
        return;
        }

    global $cm_flushInterval;
    
    
    // for testing:
    //$cm_flushInterval = "0 0:00:30.000";
    
    
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

        $query = "SELECT player_1_id, player_2_id ".
            "FROM $tableNamePrefix"."games ".
            "WHERE last_action_time < ".
            "SUBTIME( CURRENT_TIMESTAMP, '$staleGameTimeLimit' ) FOR UPDATE;";

        $result = cm_queryDatabase( $query );

        $numRows = mysql_numrows( $result );
    
        for( $i=0; $i<$numRows; $i++ ) {
            $player_1_id = mysql_result( $result, $i, "player_1_id" );
            $player_2_id = mysql_result( $result, $i, "player_2_id" );

            if( $player_1_id != 0 ) {
                cm_endOldGames( $player_1_id );
                }
            if( $player_2_id != 0 ) {
                cm_endOldGames( $player_2_id );
                }
            }
        
        cm_queryDatabase( "COMMIT;" );
        
        
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
    global $cm_version, $upgradeURL, $autoUpdateURL;
    
    echo "$cm_version $upgradeURL $autoUpdateURL OK";
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





function cm_checkSequenceNumber() {
    global $tableNamePrefix;

    $user_id = cm_getUserID();
    
    $query = "SELECT sequence_number, blocked ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    $blocked;
    
    if( $numRows > 0 ) {

        $row = mysql_fetch_array( $result, MYSQL_ASSOC );
    
        $blocked = $row[ "blocked" ];
        
        $sequence_number = $row[ "sequence_number" ];

        if( $blocked ) {
            echo "DENIED";

            cm_log( "checkSequenceNumber for $user_id DENIED, blocked" );
            return;
            }

        
        echo "$sequence_number OK";
        }
    else {
        echo "DENIED";

        cm_log( "checkSequenceNumber for $user_id DENIED, user not found" );
        return;
        }
    }




function cm_checkHmac() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    echo "OK";
    }



function cm_checkInPersonCode() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    
    
    $code = cm_requestFilter( "code", "/[A-Z0-9]+/i" );

    $user_id = cm_getUserID();

    global $tableNamePrefix;
    
    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id' AND in_person_code = '$code';";

    $result = cm_queryDatabase( $query );

    if( mysql_result( $result, 0, 0 ) == 1 ) {
        echo "OK";
        }
    else {
        cm_transactionDeny();
        }
    }




function cm_getBalance() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    $user_id = cm_getUserID();

    cm_endOldGames( $user_id );
    
    
    global $tableNamePrefix;
    
    
    // does account for this email exist already?
    $query = "SELECT dollar_balance ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows == 0 ) {
        cm_transactionDeny();
        return;
        }

    $dollar_balance = mysql_result( $result, 0, "dollar_balance" );

    echo "$dollar_balance\n";
    
    echo "OK";
    }



function cm_recomputeBalanceFromHistory( $user_id ) {
    global $tableNamePrefix;

    $query = "SELECT (SELECT COALESCE( SUM( dollar_amount - fee), 0 ) ".
        "  FROM $tableNamePrefix"."deposits WHERE user_id = '$user_id') - ".
        "(SELECT COALESCE( SUM( dollar_amount + fee), 0 ) ".
        "  FROM $tableNamePrefix"."withdrawals WHERE user_id = '$user_id') + ".
        "(SELECT COALESCE( SUM( dollar_delta), 0 ) ".
        "  FROM $tableNamePrefix"."game_ledger WHERE user_id = '$user_id') ".
        "AS recomputed_balance;";
    $result = cm_queryDatabase( $query );

    if( mysql_numrows( $result ) == 0 ) {
        return 0;
        }
    else {
        return mysql_result( $result, 0, 0 );
        }
    }



// does not include fees (total actually sent to player this year)
function cm_computeAmountWithdrawnThisYear( $user_id ) {
    global $tableNamePrefix;

    $query = "SELECT COALESCE( SUM( dollar_amount ), 0 ) ".
        "as withdrawnThisYear ".
        "FROM $tableNamePrefix"."withdrawals WHERE user_id = '$user_id' ".
        "AND YEAR( withdrawal_time ) = YEAR( CURRENT_TIMESTAMP );";
    $result = cm_queryDatabase( $query );

    if( mysql_numrows( $result ) == 0 ) {
        return 0;
        }
    else {
        return mysql_result( $result, 0, 0 );
        }
    }




// handles cases for user-validated transactions where request_tag matches
// the user's last_request_tag by sending out last_request_response
//
// Assumes that cm_verifyTransaction has already been passed.
//
// returns true if this is a repeat response
function cm_handleRepeatResponse() {
    $user_id = cm_getUserID();

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );

    if( $request_tag == "" ) {
        return false;
        }

    global $tableNamePrefix;
    
    $query = "SELECT last_request_response ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id' AND last_request_tag = '$request_tag' ".
        "AND blocked = 0;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows == 0 ) {
        return false;
        }

    global $action;
    
    cm_log( "Retry of already-complete $action request,".
            " resending last response" );
    
    $last_request_response = mysql_result( $result, 0,
                                           "last_request_response" );

    echo $last_request_response;
    
    return true;
    }




// formats dollar values with up to 4 fractional digits
// adds $ and commas to separate thousands, trims off 00 if value
// is a whole number of cents
function cm_formatBalanceForDisplay( $inDollars,
                                     $inForceFourDecimal = false ) {
    $result = number_format( $inDollars, 4 );

    if( !$inForceFourDecimal && substr( $result, -2 ) === "00" ) {
        $result = number_format( $inDollars, 2 );
        }
    return "\$$result";
    }




function cm_getDepositFees() {
    global $stripeFlatFee, $stripePercentage, $minDeposit, $maxDeposit,
        $maxNumLifetimeDeposits;
    

    $user_id = cm_getUserID();
    
    if( $user_id != "" ) {    
        if( ! cm_verifyTransaction() ) {
            return;
            }

        global $tableNamePrefix;
    
    
        // does account for this email exist already?
        $query = "SELECT num_deposits ".
            "FROM $tableNamePrefix"."users ".
            "WHERE user_id = '$user_id';";

        $result = cm_queryDatabase( $query );
        
        $numRows = mysql_numrows( $result );
        
        if( $numRows == 0 ) {
            cm_transactionDeny();
            return;
            }
        
        $num_deposits = mysql_result( $result, 0, "num_deposits" );

    
        
        if( $maxNumLifetimeDeposits >= 0 ) {
            // a limit is set
            
            if( $num_deposits >= $maxNumLifetimeDeposits ) {
                // can't deposit more
                $maxDeposit = 0;
                $minDeposit = 0;
                }
            }
        }
            
    

    echo "$stripeFlatFee\n";
    echo "$stripePercentage\n";
    echo "$minDeposit\n";
    echo "$maxDeposit\n";
    echo "OK";
    }




function cm_makeDeposit() {

    // Note that we never call cm_transactionDeny() from this function
    // Thus, submitted variables, which include encrypted credit card
    // information, are never logged, even in the case of failure.

    
    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i" );

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i" );
    
    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    // does account for this email exist already?
    // lock the gap at this email address

    // SELECT FOR UPDATE doesn't actually lock the gap against other
    // SELECTS (only blocks insert).
    // This means that duplicate new account requests can get past
    // SELECT FOR UPDATE and charge the card twice, etc.

    // Only way to REALLY lock the gap is with an INSERT
    // this blocks both other INSERTS on the same email, and also
    // SELECT FOR UPDATES on the same email.

    // of course, we haven't parsed their other detail yet,
    // and they don't have any money, etc., so we don't want to leave
    // this new, dumy record in the db.  INSERT followed by DELETE is enough
    // to lock the gap.

    // BUT, don't want to flood the AUTO_INCREMENT ID space here with 2x
    // IDs for each new user.  So, remember the temp ID and use it later
    // (the ID is locked)
    $temp_user_id = "";

    $temp_account_key = cm_generateAccountKey( $email, 0 );
    $query = "INSERT into $tableNamePrefix"."users SET email = '$email', ".
        "account_key = '$temp_account_key';";

    // handle INSERT error ourselves
    $result = mysql_query( $query );
            
    if( $result ) {
        $temp_user_id = mysql_insert_id();

        $query = "DELETE FROM $tableNamePrefix"."users WHERE ".
            "user_id = '$temp_user_id';";
        cm_queryDatabase( $query );

        // now that it has been created and deleted, row gap is
        // locked to prevent other transactions from getting
        // through before we commit

        // go ahead with normal SELECT FOR UPDATE
        }
    else {
        // insert failed, assume that account for this email already
        // exists
        // Note that if row exists, INSERT won't lock anything
        // so, we go onto SELECT FOR UPDATE below
        }
    
    
    $query = "SELECT user_id, account_key, random_name, dollar_balance, ".
        "total_deposits, last_request_tag, last_request_response, blocked ".
        "FROM $tableNamePrefix"."users ".
        "WHERE email = '$email' FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    $user_id = "";
    $account_key = "";
    $random_name = "";
    $dollar_balance = 0;
    $total_deposits = 0;
    
    $last_request_tag = "";
    $last_request_response = "";
    
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

        $last_request_tag = $row[ "last_request_tag" ];
        $last_request_response = $row[ "last_request_response" ];

        if( $last_request_tag == $request_tag ) {
            // repeat of an already-complete deposit
            cm_queryDatabase( "COMMIT;" );
            cm_queryDatabase( "SET AUTOCOMMIT=1" );

            // don't need to check anything else at this point
            // secure to just return same response again
            // (only original request originator will be able to
            //  make use of it)

            cm_log( "Retry of already-complete deposit for $email,".
                    " resending last response" );
            
            echo $last_request_response;
            return;
            }
        
        
        $user_id = $row[ "user_id" ];
        $random_name = $row[ "random_name" ];

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
        $total_deposits = $row[ "total_deposits" ];
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
        "dollar_amount", "/[0-9]+[.][0-9][0-9]/i", "0.00" );

    global $minDeposit, $maxDeposit;
    if( $dollar_amount < $minDeposit || $dollar_amount > $maxDeposit ) {
        echo "DENIED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "deposit for $email DENIED, ".
                "bad dollar amount not in range [$minDeposit, $maxDeposit], ".
                "$dollar_amount" );
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

        // don't log card_data
        cm_log( "deposit for $email DENIED, ".
                "badly formatted card data (length $length)" );
        return;
        }

    // card data came through okay
    // if charge goes through, we are set to trust this client

    $cardNumber = $dataParts[0];
    $month = $dataParts[1];
    $year = $dataParts[2];
    $cvc = $dataParts[3];

    $cents_amount = round( $dollar_amount * 100 );
    
    global $curlPath, $stripeChargeURL, $stripeTokensURL, $stripeSecretKey,
        $stripeChargeDescription;



    // before charging card, get card token so that we can obtain card
    // fingerprint

    $curlCallString =
        "$curlPath ".
        "'$stripeTokensURL' ".
        "-u $stripeSecretKey".": ".
        "-d 'card[number]=$cardNumber'  ".
        "-d 'card[exp_month]=$month'  ".
        "-d 'card[exp_year]=$year'  ".
        "-d 'card[cvc]=$cvc' ";

    //cm_log( "Calling Stripe with CURL:  $curlCallString" );
    
    $output = array();
    exec( $curlCallString, $output );


    // process result
    $outputString = implode( "\n", $output );
    
    //cm_log( "Response from Stripe:\n$outputString" );


    if( strstr( $outputString, "error" ) != FALSE ) {
        echo "PAYMENT_FAILED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "PAYMENT_FAILED for $email (amount \$$dollar_amount), ".
                "failed to get card token before charge, ".
                "stripe error:\n$outputString" );
        return;
        }
    

    $fingerprint = "";


    foreach( $output as $line ) {

        if( strstr( $line, "\"fingerprint\"" ) != FALSE ) {
            $matches = array();

            if( preg_match( "/:\s+\"(\w+)\"/", $line, $matches ) ) {
                
                $fingerprint = $matches[1];
                }
            }
        }

    //cm_log( "Card fingerprint = $fingerprint" );

    
    $exp_date = "$month$year";

    // lock the gap here if card doesn't exist yet
    // so we can guarantee that this user will be the first and only
    // user to use this card
    $query = "SELECT user_id, proof_on_file FROM $tableNamePrefix"."cards ".
        "WHERE fingerprint = '$fingerprint' and exp_date = '$exp_date' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $existing_card = false;
    $proof_on_file = 0;
    
    if( mysql_numrows( $result ) > 0 ) {
        $existing_card = true;

        $existing_card_user_id = mysql_result( $result, 0, "user_id" );
        $proof_on_file = mysql_result( $result, 0, "proof_on_file" );

        // $user_id can be blank here, if we're creating a new
        // account, in which case this won't match
        // (can't make a new account with a card someone else
        //  has already used)
        if( $existing_card_user_id != $user_id ) {
            echo "CARD_ALREADY_USED";
            cm_queryDatabase( "COMMIT;" );
            cm_queryDatabase( "SET AUTOCOMMIT=1" );

            cm_log( "deposit for $email blocked, card already used by user " .
                    "$existing_card_user_id" );
            return;
            }
        }
    

    global $depositWithNoInfoLimit;


    if( $total_deposits + $dollar_amount >= $depositWithNoInfoLimit &&
        ! $proof_on_file ) {

        echo "MORE_INFO_NEEDED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );

        cm_log( "MORE_INFO_NEEDED for $email (\$$dollar_amount deposit), ".
                "\$$total_deposits previous deposits, ".
                "limit = \$$depositWithNoInfoLimit" );


        $message =
            "In order to deposit \$$depositWithNoInfoLimit or more, total,".
            " into ".
            "your CORDIAL MINUET account, I need to verify your financial ".
            "information.  This requirement may seem tedious, but when the ".
            "financial stakes get this high, I need to protect everyone from ".
            "potential fraud.\n\n".
            "Here's what I need from you:\n\n".
            "--A scan or clear photograph of the FRONT of your ".
            "credit card with the cardholder name, expiration date, and ".
            "last 4 digits visible (please block the other digits ".
            "of the card number with your finger or tape).\n\n".
            "--A scan or clear photograph of your photo ID (driver license, state ID, or passport).\n\n".
            "--A third clear photograph of you holding your card and ID up near your face (again, block all but the last 4 digits of the card number).\n\n".
            "Obviously, the name on the card must match the name on your ID.  If you've been using a relative's card, please have your relative complete these steps.  I need proof that the cardholder has approved these charges.\n\n\n".

            "You can get these photographs to me in two ways:\n\n".
            "1) Reply to this email, and attach the photographs.\n\n".
            "2) Print the photographs and mail them to:\n".
            "        Jason Rohrer\n".
            "        1208 L St.\n".
            "        Davis, CA 95616\n".
            "        USA\n\n\n".
            "If you use the paper mail option, don't forget to include your ".
            "account email address in the envelope.\n\n".
            "DO NOT send a photo of your complete credit card number.  ".
            "DO NOT send a photo of your card's CVC (3- or 4-digit security code, usually on the back, but sometimes on the front).  Neither email nor postal mail are secure enough to transmit this sensitive data.\n\n\n".

            "Thanks for your help here, and enjoy the game!\n".
            "Jason\n\n";
        
            
    
        cm_mail( $email, "Cordial Minuet Information Request",
                 $message );

        
        return;
        }
    

    
    
    $fullDescription = $stripeChargeDescription . $email;
    
    $curlCallString =
        "$curlPath ".
        "'$stripeChargeURL' ".
        "-u $stripeSecretKey".": ".
        "-d 'receipt_email=$email'  ".
        "-d 'amount=$cents_amount'  ".
        "-d 'currency=usd'  ".
        "-d 'expand[]=balance_transaction'  ".
        "-d \"description=$fullDescription\" ".
        "-d 'card[number]=$cardNumber'  ".
        "-d 'card[exp_month]=$month'  ".
        "-d 'card[exp_year]=$year'  ".
        "-d 'card[cvc]=$cvc' ";

    //cm_log( "Calling Stripe with CURL:  $curlCallString" );
    
    $output = array();
    exec( $curlCallString, $output );
    
    // process result
    $outputString = implode( "\n", $output );
    
    //cm_log( "Response from Stripe:\n$outputString" );


    if( strstr( $outputString, "error" ) != FALSE ) {
        echo "PAYMENT_FAILED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "PAYMENT_FAILED for $email (amount \$$dollar_amount), ".
                "stripe error:\n$outputString" );
        return;
        }

    
    $paid = false;

    $fee = 0;
    $paymentID = "";
    
    foreach( $output as $line ) {

        if( strstr( $line, "paid" ) != FALSE &&
            strstr( $line, "true" ) != FALSE ) {
            $paid = true;
            }
        else if( strstr( $line, "\"fee\"" ) != FALSE ) {
            $matches = array();
            
            if( preg_match( "/(\d+),/", $line, $matches ) ) {
                
                $fee = $matches[1];
                
                // in dollars
                $fee = $fee / 100;
                }
            }
        else if( strstr( $line, "\"id\"" ) != FALSE &&
                 strstr( $line, "\"ch_" ) != FALSE ) {
            if( preg_match( "/\"(ch_\w+)\"/", $line, $matches ) ) {
                
                $paymentID = $matches[1];
                }
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

    

    // fee is taken out of amount actually deposited, pass this on
    // to player
    $deposit_net = $dollar_amount - $fee;
    
    
    
    if( $user_id == "" ) {
        // new account

        // look for collisions, try new salt
        $salt = 0;


        $found_unused = false;

        $tryCount = 0;

        while( ! $found_unused && $tryCount < 10 ) {
            
        
            $account_key = cm_generateAccountKey( $email, $salt );

            $user_id_line = "";
            if( $temp_user_id != "" ) {
                $user_id_line = "user_id = '$temp_user_id',";
                }
            
            $random_name = cm_generateRandomName();

            global $eloStartingRating;
            
            // user_id auto-assigned (auto-increment)
            $query = "INSERT INTO $tableNamePrefix". "users SET ".
                $user_id_line .
                "account_key = '$account_key', ".
                "email = '$email', ".
                "random_name = '$random_name', ".
                "dollar_balance = '$deposit_net', ".
                "num_deposits = 1, ".
                "total_deposits = '$deposit_net', ".
                "num_withdrawals = 0, ".
                "total_withdrawals = 0, ".
                "total_won = 0, ".
                "total_lost = 0, ".
                "elo_rating = $eloStartingRating, ".
                "sequence_number = 0, ".
                "request_sequence_number = 0, ".
                "last_action_time = CURRENT_TIMESTAMP, ".
                "last_request_tag = '$request_tag', ".
                "last_request_response = '', ".
                "admin_level = 0, ".
                "blocked = 0;";
            
            $result = mysql_query( $query );
            
            if( $result ) {
                $found_unused = 1;

                $user_id = mysql_insert_id();
                
                global $remoteIP;
                cm_log( "Account key $account_key, user_id $user_id ".
                        "created by $email from ".
                        "$remoteIP, ".
                        "initial deposit \$$dollar_amount (\$$fee fee), ".
                        "net \$$deposit_net, ".
                        "desired temp user id $temp_user_id" );
                cm_incrementStat( "unique_users" );
                }
            else {
                cm_log( "Duplicate ids?  Error:  " . mysql_error() );
                // try again
                $salt += 1;

                $tryCount ++;
                }
            }
        if( $user_id == "" ) {
            // exceeded try count
            cm_log( "Failed to insert new user record ".
                    "after $tryCount tries, returning ACCOUNT_EXISTS" );

            echo "ACCOUNT_EXISTS";
            cm_queryDatabase( "COMMIT;" );
            cm_queryDatabase( "SET AUTOCOMMIT=1" );

            return;
            }
        
        
        $dollar_balance = $deposit_net;

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
        

        $response = "1\n$encryptedAccountKeyHex\nOK";


        $query = "UPDATE $tableNamePrefix". "users SET ".
            "last_request_tag = '$request_tag', ".
            "last_request_response = '$response' ".
            "WHERE email = '$email';";
        cm_queryDatabase( $query );

        
        echo $response;
        }
    else {
        // existing account

        $response = "0\n#\nOK";
        
        $query = "UPDATE $tableNamePrefix". "users SET ".
            "dollar_balance = dollar_balance + $deposit_net, ".
            "num_deposits = num_deposits + 1, ".
            "total_deposits = total_deposits + '$deposit_net', ".
            "last_request_tag = '$request_tag', ".
            "last_request_response = '$response' ".
            "WHERE user_id = $user_id;";
        cm_queryDatabase( $query );

        global $remoteIP;
        cm_log( "Deposit of \$$dollar_amount (\$$fee fee), ".
                "net \$$deposit_net for ".
                "user $user_id ($email) by $remoteIP" );

        $query = "SELECT dollar_balance FROM $tableNamePrefix"."users ".
            "WHERE user_id = $user_id;";
        $result = cm_queryDatabase( $query );

        $dollar_balance = mysql_result( $result, 0, "dollar_balance" );
        
        
        
        echo $response;

        
        // for presentation to user (in email that we send below)
        
        // break into "-" separated chunks of 5 digits
        $account_key_chunks = str_split( $account_key, 5 );

        $account_key = implode( "-", $account_key_chunks );
        }

    // got here
    // deposit happened


    // log card usage
    if( $existing_card ) {
        $query = "UPDATE $tableNamePrefix"."cards ".
            "SET last_used_time = CURRENT_TIMESTAMP ".
            "WHERE fingerprint = '$fingerprint' AND exp_date='$exp_date' ".
            "AND user_id = '$user_id';";
        cm_queryDatabase( $query );
        }
    else {
        // card never used before
        $query = "INSERT INTO $tableNamePrefix"."cards ".
            "SET fingerprint = '$fingerprint', exp_date = '$exp_date', ".
            "user_id = '$user_id', last_used_time = CURRENT_TIMESTAMP, ".
            "proof_on_file = 0;";
        cm_queryDatabase( $query );
        }


    // unlock users table (or gap there) and cards table (or gap there)
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );


    
    cm_incrementStat( "deposit_count" );
    cm_incrementStat( "total_deposits", $deposit_net );
    cm_updateMaxStat( "max_deposit", $deposit_net );

    
    // log it, including fees (whole amount charged to them)

    $query = "INSERT INTO $tableNamePrefix"."deposits ".
        "SET user_id = '$user_id', deposit_time = CURRENT_TIMESTAMP, ".
        "dollar_amount = '$dollar_amount', ".
        "fee = '$fee', processing_id = '$paymentID'; ";
    
    $result = cm_queryDatabase( $query );
    
    
    // send email receipt

    $balanceString = cm_formatBalanceForDisplay( $dollar_balance );
    $amountString = cm_formatBalanceForDisplay( $dollar_amount );
    $netString = cm_formatBalanceForDisplay( $deposit_net );
    $feeString = cm_formatBalanceForDisplay( $fee );
    
    
    $message =
        "You successfully deposited $netString ".
        "($feeString fee, total charge $amountString) into your ".
        "CORDIAL MINUET account.  Your new balance is $balanceString.\n\n".
        "Here are your account details:\n\n".
        "Email:  $email\n".
        "Account Key:  $account_key\n\n".
        "Alias:  $random_name\n\n".
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



    
function cm_getWithdrawalMethods() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    $user_id = cm_getUserID();
    
    $in_person_code = cm_getUserData( $user_id, "in_person_code" );
    
    if( $in_person_code != "" ) {
        echo "in_person#0.00\n";
        }
    else {
        
        global $usCheckMethodAvailable, $globalCheckMethodAvailable,
            $transferMethodAvailable, $usCheckCost, $globalCheckCost,
            $transferCost;
        
        if( $usCheckMethodAvailable ) {
            echo "us_check#$usCheckCost\n";
            }
        if( $globalCheckMethodAvailable ) {
            echo "global_check#$globalCheckCost\n";
            }
        if( $transferMethodAvailable ) {
            echo "account_transfer#$transferCost\n";
            }
        }
    
    echo "OK";
    }



function cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                             $field_name, $field_value ) {
    $field_hmac = cm_requestFilter( "$field_name"."_hmac",
                                     "/[A-F0-9]+/i" );
    if( strtoupper( $field_hmac ) !=
        strtoupper( cm_hmac_sha1( $account_key . $request_sequence_number,
                                  $field_value ) ) ) {
        

        cm_log( "cm_sendCheck/cm_accountTransfer bad hmac for ".
                "$field_name (contents = '$field_value')" );

        
        cm_transactionDeny();
            
        return false;
        }
    else {
        return true;
        }
    }




function cm_sendCheck() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );


    
    global $tableNamePrefix;
    
    

    $user_id = cm_getUserID();
    
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT email, account_key, dollar_balance, tax_info_on_file, ".
        "request_sequence_number ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id' FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    
    if( $numRows == 0 ) {
        cm_log( "cm_sendCheck, user $user_id not found" );
        cm_transactionDeny();
        return;
        }

    $row = mysql_fetch_array( $result, MYSQL_ASSOC );


    $email = $row[ "email" ];
    $account_key = $row[ "account_key" ];
    $dollar_balance = $row[ "dollar_balance" ];
    $tax_info_on_file = $row[ "tax_info_on_file" ];
    $old_request_sequence_number = $row[ "request_sequence_number" ];


    $request_sequence_number =
        cm_requestFilter( "request_sequence_number", "/\d+/" );


    if( $request_sequence_number < $old_request_sequence_number ) {
        cm_log( "cm_sendCheck, stale request sequence number" );
        cm_transactionDeny();
        return;
        }


    

    $country = cm_requestFilter( "country", "/[A-Z][A-Z]/", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "country", $country ) ) {
        return;
        }

    if( $country == "" ) {
        cm_transactionDeny();
        return;
        }

    global $allowedCountries;
    
    if( ! array_key_exists( $country, $allowedCountries ) ) {
        echo "UNKNOWN_COUNTRY";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "UNKNOWN_COUNTRY for check request ($country)" );
        return;
        }
    

    
    global $usCheckCost, $globalCheckCost, $otherCountriesWithUSCheckCost;

    $isUS = false;
    $fee = $globalCheckCost;

    if( $country == "US" ) {
        $isUS = true;
        $fee = $usCheckCost;
        }
    else if( in_array( $country, $otherCountriesWithUSCheckCost ) ) {
        $fee = $usCheckCost;
        }
             
    
    
    global $usCheckMethodAvailable, $globalCheckMethodAvailable;

    
    if( $isUS && !$usCheckMethodAvailable ) {
        cm_log( "cm_sendCheck, check-send-US withdrawal method blocked" );
        cm_transactionDeny();
        return;
        }
    else if( !$isUS && !$globalCheckMethodAvailable ) {
        cm_log( "cm_sendCheck, check-send-global withdrawal method blocked" );
        cm_transactionDeny();
        return;
        }

        
    
    
    
    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/[0-9]+[.][0-9][0-9]/i", "0.00" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "dollar_amount", $dollar_amount ) ) {
        return;
        }
    
    
    
    if( $dollar_amount <= $fee ) {
        cm_log( "cm_sendCheck withdrawal too small: \$$dollar_amount" );
        cm_transactionDeny();
        return;
        }


    $recomputedBalance = cm_recomputeBalanceFromHistory( $user_id );

    if( $dollar_balance != $recomputedBalance ) {

        $message = "User $user_id table dollar balance = $dollar_balance, ".
            "but recomputed balance = $recomputedBalance, ".
            "blocking send_check.";

        cm_log( $message );
        cm_informAdmin( $message );
        
        cm_transactionDeny();

        return;
        }
    

    
    if( $dollar_amount > $dollar_balance ) {
        cm_log( "cm_sendCheck withdrawal too big: \$$dollar_amount ".
                "(account only has \$$dollar_balance)" );
        cm_transactionDeny();
        return;
        }



    global $withdrawalWithNoInfoYearlyLimit;

    
    
    $withdrawnThisYear = cm_computeAmountWithdrawnThisYear( $user_id );
    
    if( $withdrawnThisYear + $dollar_amount
        >= $withdrawalWithNoInfoYearlyLimit &&
        ! $tax_info_on_file ) {

        echo "MORE_INFO_NEEDED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );

        cm_log( "MORE_INFO_NEEDED for $email (\$$dollar_amount withdrawal), ".
                "\$$withdrawnThisYear previously withdrawn this year, ".
                "limit = \$$withdrawalWithNoInfoYearlyLimit" );


        $message =
            "In order to withdraw \$$withdrawalWithNoInfoYearlyLimit ".
            "or more in a given calendar year from ".
            "your CORDIAL MINUET account, I need to collect your tax ".
            "information so that I can file the necessary tax reporting ".
            "documents at year end, as required by law in the ".
            "United States.\n\n".
            "The information I need from you depends on whether or not ".
            "you are a US person for tax purposes.\n\n".

            "Please send me one of the following:\n\n".

            "--If you are a US person, please fill out and send me Form W-9, ".
            "which can be printed from http://www.irs.gov/pub/irs-pdf/fw9.pdf".
            "\n\n".

            "--If you are a non-US person, please fill out and send me ".
            "Form W-8BEN, which can be printed from ".
            "http://www.irs.gov/pub/irs-pdf/fw8ben.pdf\n\n\n".

            "Mail the appropriate form, along with your account email ".
            "address, to me here:\n".
            "        Jason Rohrer\n".
            "        1208 L St.\n".
            "        Davis, CA 95616\n".
            "        USA\n\n\n".
            "Thanks for your help here, and enjoy the game!\n".
            "Jason\n\n";
        
            
    
        cm_mail( $email, "Cordial Minuet Tax Information Request",
                 $message );

        
        return;
        }

    


    $name = cm_requestFilter(
        "name", "/[A-Za-z.\-' ]+/i", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "name", $name ) ) {
        return;
        }

    if( $name == "" ) {
        cm_log( "cm_sendCheck name missing" );
                
        cm_transactionDeny();
        return;
        }

    $address1 = cm_requestFilter(
        "address1", "/[A-Za-z.\-' ,0-9#]+/i", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "address1", $address1 ) ) {
        return;
        }

    if( $address1 == "" ) {
        cm_log( "cm_sendCheck address1 missing" );
        cm_transactionDeny();
        return;
        }

    $address2 = cm_requestFilter(
        "address2", "/[A-Za-z.\-' ,0-9#]+/i", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "address2", $address2 ) ) {
        return;
        }
    // address2 can be empty

    
    $city = cm_requestFilter(
        "city", "/[A-Za-z.\-' ,]+/i", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "city", $city ) ) {
        return;
        }

    if( $city == "" ) {
        cm_log( "cm_sendCheck city missing" );
        cm_transactionDeny();
        return;
        }


    
    $us_state = cm_requestFilter(
        "us_state", "/[A-Z][A-Z]/", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "us_state", $us_state ) ) {
        return;
        }

    if( $isUS && $us_state == "" ) {
        cm_log( "cm_sendCheck us_state missing for US destination" );
        cm_transactionDeny();
        return;
        }
    else if( !$isUS && $us_state != "" ) {
        cm_log( "cm_sendCheck us_state forbidden for global destinations" );
        cm_transactionDeny();
        return;
        }


    $province = cm_requestFilter(
        "province", "/[A-Za-z.\-' ,]+/i", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "province", $province ) ) {
        return;
        }

    if( $isUS && $province != "" ) {
        cm_log( "cm_sendCheck province forbidden for US destinations" );
        cm_transactionDeny();
        return;
        }
    

    $postal_code = "";


    if( $isUS ) {
        // enforce US ZIP code format
        $postal_code = cm_requestFilter(
            "postal_code", "/\d{5}([- ]\d{4})?/", "" );

        if( $postal_code == "" ) {
            cm_log( "cm_sendCheck zip-formated postal_code ".
                    "required for US destinations" );
            cm_transactionDeny();
            return;
            }
        }
    else {
        // allow global format
        $postal_code = cm_requestFilter(
            "postal_code", "/[A-Z\- 0-9]+/i", "" );
        }
    
    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "postal_code", $postal_code ) ) {
        return;
        }

    
    $check_amount = $dollar_amount - $fee;

    $dollar_amount_string = number_format( $dollar_amount, 2 );
    $check_amount_string = number_format( $check_amount, 2 );
    $fee_string = number_format( $fee, 2 );
    


    // no longer using Lob
    /*
    cm_log( "Got a verfied [\$$check_amount_string] US check request to:\n".
            "$name\n".
            "$address1\n".
            "$address2\n".
            "$city, $state $zip" );


    // send lob request

    global $curlPath, $lobURL, $lobAPIKey,
        $lobCheckNote, $lobBankAccount;

    $dollar_string = number_format( $dollar_amount, 2 );
    $fee_string = number_format( $usCheckCost, 2 );

    
    $fullNote = $lobCheckNote .
        "$email".".  Withdrawing USD $dollar_string with a $fee_string fee.  ".
        "Check is for USD $check_amount.";

    
    $address2Param = "";

    if( $address2 != "" ) {
        $address2Param = "-d \"to[address_line2]=$address2\" ";
        }
    
    $curlCallString =
        "$curlPath ".
        "'$lobURL' ".
        "-u $lobAPIKey".": ".
        "-d \"message=$fullNote\" ".
        "-d 'memo=$email' ".
        "-d 'name=Cordial Minuet Withdrawal' ".
        "-d \"bank_account=$lobBankAccount\" ".
        "-d \"amount=$check_amount\" ".
        "-d \"to[name]=$name\" ".
        "-d \"to[address_line1]=$address1\" ".
        $address2Param .
        "-d \"to[address_city]=$city\" ".
        "-d \"to[address_state]=$state\" ".
        "-d \"to[address_zip]=$zip\" ".
        "-d \"to[address_country]=US\" ";


    //cm_log( "Calling Lob with:\n$curlCallString" );

    $output = array();
    exec( $curlCallString, $output );

    // process result
    $outputString = implode( "\n", $output );
    
    //cm_log( "Response from Lob:\n$outputString" );


    if( strstr( $outputString, "errors" ) != FALSE ) {
        echo "CHECK_FAILED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "CHECK_FAILED for $email, ".
                "Lob error:\n$outputString" );
        return;
        }

    
    $processed = false;
    $price = 0;
    
    foreach( $output as $line ) {

        if( strstr( $line, "status" ) != FALSE &&
            strstr( $line, "processed" ) != FALSE ) {
            $processed = true;
            }
        else if( strstr( $line, "price" ) != FALSE ) {
            $matches = array();

            if( preg_match( "/\"(\d*\.\d*)\"/", $line, $matches ) ) {
                
                $price = $matches[1];
                }
            }
        }

    if( !$processed ) {
        echo "CHECK_FAILED";
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "CHECK_FAILED for $email, ".
                "Lob result not marked as processed:\n$outputString" );
        return;
        }

    // else we're good


    // we found "price" field in LOB response
    // it may differ from what we charged the user for their check
    
    // (we don't actually charge the user a different amount)

    // house_dollar_balance should reflect how much we actually charged them,
    // because lob fees come out of a different account anyway
    
    if( $price != $usCheckCost ) {
        $message = "Lob check price has changed from server setting.  ".
            "New Lob price: \$$price  Our setting: \$$usCheckCost";
        
        cm_log( $message );
        
        cm_informAdmin( $message );
        }
    */



    cm_log( "Got a verfied [\$$check_amount_string] check request to:\n".
            "$name\n".
            "$address1\n".
            "$address2\n".
            "$city, $us_state, $province, $postal_code, $country" );



    global $curlPath, $chexxSubmitURL, $chexxResponseURL,
        $chexxUserName, $chexxSharedSecret, $chexxPRN,
        $checkMemo, $checkComment;


    $description = $checkMemo . $email;

    
    $currency = "USD";

    global $currencyMap;
    
    if( array_key_exists( $country, $currencyMap ) ) {
        $currency = $currencyMap[ $country ];
        }

    $currencyNote = "";

    if( $currency != "USD" ) {
        $currencyNote = "Your check has been converted into your ".
            "local $currency currency.";
        }
    

    $comment = $checkComment .
        "$email".".  Withdrawing USD $dollar_amount_string with a ".
        "$fee_string fee.  ".
        $currencyNote;

    
    $address2Param = "";

    if( $address2 != "" ) {
        $address2Param = "-d \"AddressLine2=$address2\" ";
        }

    $provState = $province;
    if( $isUS ) {
        $provState = $us_state;
        }
    
    
    $timestamp = gmdate( "Y-m-d\TH:i:s.000\Z" );

    $requestID = $user_id . "_" . $request_sequence_number;

    $paymentType = "chq_issue_credit";

    
    $check_amount_cents = round( $check_amount * 100 );


    // amount used in signature
    // must match Amount value passed in (which isn't necessarily check
    // amount)
    $amount = 0;
    
    

    $amountLines;

    if( $currency == "USD" ) {
        // specify amount directly in USD
        $amountLines =
            "-d 'Amount=$check_amount_cents' ";
        $amount = $check_amount_cents;
        }
    else {
        // leave local currency amount blank
        // specify amount to be taken out of our account in USD
        $amountLines =
            "-d 'Amount=0' ".
            "-d 'AccountAmount=$check_amount_cents' ";
        $amount = 0;
        }


    
    $signature = cm_hmac_sha1( $chexxSharedSecret,
                               $chexxUserName .
                               $timestamp .
                               $requestID .
                               $paymentType .
                               $amount .
                               $currency );
    
    
    $curlCallString =
        "$curlPath ".
        "'$chexxSubmitURL' ".
        "-d 'RAPIVersion=2' ".
        "-d 'Username=$chexxUserName' ".
        "-d 'Timestamp=$timestamp' ".
        "-d 'RequestID=$requestID' ".
        "-d 'Signature=$signature' ".
        "-d 'PRN=$chexxPRN' ".
        "-d 'PaymentType=$paymentType' ".
        $amountLines .
        "-d \"Beneficiary=$name\" ".
        "-d 'Currency=$currency' ".
        // country of the source account (our account)
        "-d 'Country=US' ".
        "-d \"Description=$description\" ".
        "-d 'Reference=user_id_$user_id' ".
        "-d \"AddressLine1=$address1\" ".
        $address2Param .
        "-d \"City=$city\" ".
        "-d \"ProvState=$provState\" ".
        "-d 'PostalCode=$postal_code' ".
        "-d 'CustomerCountry=$country' ".
        "-d \"Comment=$comment\" ";
    

    //cm_log( "Calling chexx with:\n$curlCallString" );

    $output = array();
    exec( $curlCallString, $output );

    // process result
    $outputString = implode( "\n", $output );
    
    //cm_log( "Response from Chexx submit:\n$outputString" );

    //$decodedOutputString = urldecode( $outputString );
    //cm_log( "Decoded response from Chexx submit:\n$decodedOutputString" );
    
    if( strstr( $outputString, "InProgress" ) == FALSE &&
        strstr( $outputString, "Invalid" ) == FALSE &&
        strstr( $outputString, "Rejected" ) == FALSE &&
        strstr( $outputString, "ConfigError" ) == FALSE ) {
            
        // chexx sennt nothing back, must ask for result in second call
        // send back old requestID
        $timestamp = gmdate( "Y-m-d\TH:i:s.000\Z" );
    
        $signature = cm_hmac_sha1( $chexxSharedSecret,
                                   $chexxUserName .
                                   $timestamp .
                                   $requestID );

        $curlCallString =
            "$curlPath ".
            "'$chexxResponseURL' ".
            "-d 'RAPIVersion=2' ".
            "-d 'Username=$chexxUserName' ".
            "-d 'Timestamp=$timestamp' ".
            "-d 'RequestID=$requestID' ".
            "-d 'Signature=$signature' ";

        $output = array();
        exec( $curlCallString, $output );

    
        // process result
        $outputString = implode( "\n", $output );
    
        cm_log( "Response from Chexx response:\n$outputString" );
        }
        

    

    if( ( strstr( $outputString, "Invalid" ) != FALSE &&
          // allow test channel responses through, even though they
          // are marked as invalid
          strstr( $outputString, "Invalid%3ATestChannel" ) == FALSE )
        ||
        strstr( $outputString, "Rejected" ) != FALSE
        ||
        strstr( $outputString, "ConfigError" ) != FALSE
        ||
        strstr( $outputString, "UnsupportedCountry" ) != FALSE ) {

        echo "CHECK_FAILED";
        
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "CHECK_FAILED for $email, ".
                "Chexx Raven error:\n$outputString" );

        if( strstr( $outputString, "UnsupportedCountry" ) != FALSE ) {
            $countryFullName = $allowedCountries[ $country ];

            $message = "Country ($country) $countryFullName unsupported ".
                "by Chexx for $email";

            cm_log( $message );

            cm_informAdmin( $message,
                            "Cordial Minuet Chexx country unsupported" );
            }
        
        return;
        }
    

    if( strstr( $outputString, "InProgress" ) == FALSE &&
        strstr( $outputString, "Invalid%3ATestChannel" ) == FALSE ) {

        echo "CHECK_FAILED";
        
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        cm_log( "CHECK_FAILED for $email, ".
                "Chexx Raven error:\n$outputString" );
        return;
        }
        
    // a valid response
    

    // extract tracking number
    $matches = array();

    $trackingNumber = "";
    
    if( preg_match( "/TrackingNumber=(\d+)/", $outputString, $matches ) ) {
        
        $trackingNumber = $matches[1];
        }

    //cm_log( "Tracking number = $trackingNumber" );
    

    
    

    $response = "OK";
        
    $query = "UPDATE $tableNamePrefix". "users SET ".
        "dollar_balance = dollar_balance - $dollar_amount, ".
        "request_sequence_number = $request_sequence_number + 1, ".
        "num_withdrawals = num_withdrawals + 1, ".
        // track amount they took out (check plus fee)
        "total_withdrawals = total_withdrawals + '$dollar_amount', ".
        "last_request_tag = '$request_tag', ".
        "last_request_response = '$response' ".
        "WHERE user_id = $user_id;";
    cm_queryDatabase( $query );


    cm_incrementStat( "withdrawal_count" );
    cm_incrementStat( "total_withdrawals", $dollar_amount );
    cm_updateMaxStat( "max_withdrawal", $dollar_amount );
    
    

    /*
     // No longer using Lob
     // fees will be taken out of house account by new check processor
    
    // since Lob fees are taken out of a separate bank account
    // (via debit card payment), count them as free money in the house account
    // this is how much WE charged the user for the check.
    $query = "UPDATE $tableNamePrefix". "server_globals SET ".
        "house_dollar_balance = house_dollar_balance + '$lobCheckCost';";
    cm_queryDatabase( $query );
    */

    // log it, excluding fee (amount they will receive)


    // these may have ' in them, which would break our query
    $name = mysql_real_escape_string( $name );
    $address1 = mysql_real_escape_string( $address1 );
    $address2 = mysql_real_escape_string( $address2 );
    $city = mysql_real_escape_string( $city );
    $province = mysql_real_escape_string( $province );

    
    
    $query = "INSERT INTO $tableNamePrefix"."withdrawals ".
        "SET user_id = '$user_id', withdrawal_time = CURRENT_TIMESTAMP, ".
        "dollar_amount = '$check_amount', ".
        "fee = '$fee', ".
        "email = '$email', ".
        "name = '$name', address1 = '$address1', address2 = '$address2', ".
        "city = '$city', us_state = '$us_state', province = '$province', ".
        "postal_code = '$postal_code', country='$country', ".
        "reference_code = '$trackingNumber' ; ";
    
    $result = cm_queryDatabase( $query );

    $query = "UPDATE $tableNamePrefix"."server_globals ".
        "SET check_account_dollar_balance = ".
        "check_account_dollar_balance - ( $check_amount + $fee );";

    $result = cm_queryDatabase( $query );

    $query = "SELECT check_account_dollar_balance FROM ".
        "$tableNamePrefix"."server_globals FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $check_account_dollar_balance = mysql_result( $result, 0, 0 );

    global $checkAccountTarget, $checkAccountThreshold;

    if( $check_account_dollar_balance < $checkAccountThreshold ) {

        $addition = $checkAccountTarget - $check_account_dollar_balance;


        global $refreshCheckName, $refreshCheckAddress,
            $refreshCheckCity, $refreshCheckState, $refreshCheckPostalCode,
            $refreshCheckCountry, $chexxPRN;

        $memo = "PRN $chexxPRN";

        $fullNote = "Deposit into Jason Rohrer's Chexx account, ".
            "PRN $chexxPRN";
        
        // send lob request

        global $curlPath, $lobURL, $lobAPIKey, $lobBankAccount;
    
        $curlCallString =
            "$curlPath ".
            "'$lobURL' ".
            "-u $lobAPIKey".": ".
            "-d \"message=$fullNote\" ".
            "-d 'memo=$memo' ".
            "-d 'name=Chexx Balance Refresh' ".
            "-d \"bank_account=$lobBankAccount\" ".
            "-d \"amount=$addition\" ".
            "-d \"to[name]=$refreshCheckName\" ".
            "-d \"to[address_line1]=$refreshCheckAddress\" ".
            "-d \"to[address_city]=$refreshCheckCity\" ".
            "-d \"to[address_state]=$refreshCheckState\" ".
            "-d \"to[address_zip]=$refreshCheckPostalCode\" ".
            "-d \"to[address_country]=$refreshCheckCountry\" ";


        //cm_log( "Calling Lob with:\n$curlCallString" );

        $output = array();
        exec( $curlCallString, $output );

        // process result
        $outputString = implode( "\n", $output );
    
        //cm_log( "Response from Lob:\n$outputString" );


        if( strstr( $outputString, "errors" ) != FALSE ) {

            $message = "Failed to send Chexx account refresh check, ".
                "Lob error:\n$outputString";
            cm_log( $message );
            cm_informAdmin( $message );
            }
        else {
            
    
            $processed = false;
            
            foreach( $output as $line ) {

                if( strstr( $line, "status" ) != FALSE &&
                    strstr( $line, "processed" ) != FALSE ) {
                    $processed = true;
                    }
                }

            if( !$processed ) {
                $message = "Failed to send Chexx account refresh check, ".
                    "Lob result not marked as processed:\n$outputString";
                cm_log( $message );
                cm_informAdmin( $message );
                }
            else {
                
                // else we're good
                $query = "UPDATE $tableNamePrefix"."server_globals ".
                    "SET check_account_dollar_balance = ".
                    "check_account_dollar_balance + $addition;";

                $result = cm_queryDatabase( $query );

                $message = "Sent refresh check to Chexx account for $addition";

                cm_log( $message );

                cm_informAdmin( $message,
                                "Cordial Minuet refresh check sent to Chexx" );
                }
            }
        }
    
    
    
    global $remoteIP;
    cm_log( "Withdrawal of \$$dollar_amount for $email by ".
            "$remoteIP (Chexx tracking number $trackingNumber)" );
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );



    // send email receipt

    $balanceString = cm_formatBalanceForDisplay( $dollar_balance -
                                                 $dollar_amount );
    $amountString = cm_formatBalanceForDisplay( $dollar_amount );
    $netString = cm_formatBalanceForDisplay( $check_amount );
    $feeString = cm_formatBalanceForDisplay( $fee );

    $outsideUSLine = "";

    if( $currency != "USD" ) {
        $outsideUSLine =
            "\n\nSince you are outside the United States, your check ".
            "will be issued in your local $currency currency according ".
            "to current ".
            "exchange rates.";
        }
    
    
    $message =
        "You successfully withdrew $netString ".
        "($feeString fee, total withdrawl $amountString) from your ".
        "CORDIAL MINUET account.  Your new balance is $balanceString.\n\n".
        "Please allow a few weeks for your $netString check to arrive ".
        "in the mail (reference number $trackingNumber).".
        $outsideUSLine .
        "\n\n\n".
        "Thanks for playing!\n".
        "Jason\n\n";
            
    
    cm_mail( $email, "Cordial Minuet Withdrawal Receipt",
             $message );
    
    echo $response;
    }





function cm_accountTransfer() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    global $tableNamePrefix, $transferMethodAvailable;

    
    if( !$transferMethodAvailable ) {
        cm_log( "cm_accountTransfer, transfer withdrawal method blocked" );
        cm_transactionDeny();
        return;
        }

        
    $user_id = cm_getUserID();
    
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT email, account_key, dollar_balance, ".
        "request_sequence_number ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id' FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    
    if( $numRows == 0 ) {
        cm_log( "cm_accountTransfer, user $user_id not found" );
        cm_transactionDeny();
        return;
        }

    $row = mysql_fetch_array( $result, MYSQL_ASSOC );

    
    $email = $row[ "email" ];
    $account_key = $row[ "account_key" ];
    $dollar_balance = $row[ "dollar_balance" ];
    $old_request_sequence_number = $row[ "request_sequence_number" ];


    $request_sequence_number =
        cm_requestFilter( "request_sequence_number", "/\d+/" );


    if( $request_sequence_number < $old_request_sequence_number ) {
        cm_log( "cm_accountTransfer, stale request sequence number" );
        cm_transactionDeny();
        return;
        }


    $recipient_email =
        cm_requestFilter( "recipient_email",
                          "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i", "" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "recipient_email", $recipient_email ) ) {
        return;
        }

    
    if( $email == $recipient_email ) {
        // can't transfer to self
        cm_log( "cm_accountTransfer blocked transfer to self" );
        cm_transactionDeny();
        return;
        }

    
    
    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/[0-9]+[.][0-9][0-9]/i", "0.00" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "dollar_amount", $dollar_amount ) ) {
        return;
        }
    
    global $transferCost;
    
    
    if( $dollar_amount <= $transferCost ) {
        cm_log( "cm_accountTransfer withdrawal too small: \$$dollar_amount" );
        cm_transactionDeny();
        return;
        }
    if( $dollar_amount > $dollar_balance ) {
        cm_log( "cm_accountTransfer transfer too big: \$$dollar_amount ".
                "(account only has \$$dollar_balance)" );
        cm_transactionDeny();
        return;
        }
    
    


    
    $transfer_amount = $dollar_amount - $transferCost;
    

    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."users ".
        "WHERE email = '$recipient_email' and blocked = 0;";

    $result = cm_queryDatabase( $query );

    if( mysql_result( $result, 0, 0 ) == 0 ) {
        cm_log( "cm_accountTransfer recipient not found; $recipient_email" );

        echo "RECIPIENT_NOT_FOUND";
        return;
        }

    // first, withdraw from sender
    
    $response = "OK";
        
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "dollar_balance = dollar_balance - $dollar_amount, ".
        "request_sequence_number = $request_sequence_number + 1, ".
        "num_withdrawals = num_withdrawals + 1, ".
        // track amount recipient receives
        // transfer fee is added to house_balance below
        "total_withdrawals = total_withdrawals + '$transfer_amount', ".
        "last_request_tag = '$request_tag', ".
        "last_request_response = '$response' ".
        "WHERE user_id = $user_id;";

    cm_queryDatabase( $query );

    $query = "UPDATE $tableNamePrefix". "server_globals SET ".
        "house_dollar_balance = house_dollar_balance + '$transferCost';";
    cm_queryDatabase( $query );

    $query = "UPDATE $tableNamePrefix"."users SET ".
        "dollar_balance = dollar_balance + $transfer_amount, ".
        "num_deposits = num_deposits + 1, ".
        // track amount recipient receives
        // transfer fee is added to house_balance below
        "total_deposits = total_deposits + '$transfer_amount' ".
        "WHERE email = '$recipient_email';";
    cm_queryDatabase( $query );
    
    
    global $remoteIP;
    cm_log( "Account transfer of \$$dollar_amount_string ".
            "($transfer_amount after fee) from $email to $recipient_email ".
            "by $remoteIP" );
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
    echo $response;
    }



function cm_addLedgerEntry( $user_id, $game_id, $dollar_delta ) {

    global $tableNamePrefix;

    $query = "INSERT INTO $tableNamePrefix"."game_ledger SET ".
        "user_id = '$user_id', game_id = '$game_id', ".
        "entry_time = CURRENT_TIMESTAMP, dollar_delta = '$dollar_delta'; ";

    cm_queryDatabase( $query );
    }




// ends any games that this user is part of
// (to clear up conflicts before starting new games

// assumes that autocommit is off

// returns payout to $user_id
function cm_endOldGames( $user_id ) {
    global $tableNamePrefix;
    
    $query = "SELECT game_id, semaphore_key, player_1_id, player_2_id, ".
        "game_square, ".
        "dollar_amount, player_1_coins, player_2_coins, ".
        "player_1_pot_coins, player_2_pot_coins, ".
        "player_1_moves, player_2_moves, ".
        "player_1_bet_made, player_2_bet_made, ".
        "move_deadline, CURRENT_TIMESTAMP as now_time ". 
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    
    $numRows = mysql_numrows( $result );

    global $cm_gameCoins, $areGamesAllowed;
    
    for( $i = 0; $i<$numRows; $i++ ) {
        $game_id = mysql_result( $result, $i, "game_id" );
        $semaphore_key = mysql_result( $result, $i, "semaphore_key" );
        $player_1_id = mysql_result( $result, $i, "player_1_id" );
        $player_2_id = mysql_result( $result, $i, "player_2_id" );

        cm_log( "Calling endOldGames for game $game_id, p1=$player_1_id, ".
                "p2=$player_2_id, stack = \n" . cm_getBacktrace() );
        
        $game_square = mysql_result( $result, $i, "game_square" );

        $old_player_1_id = $player_1_id;
        $old_player_2_id = $player_2_id;
        
        
        $dollar_amount = mysql_result( $result, $i, "dollar_amount" );
        
        $player_1_coins = mysql_result( $result, $i, "player_1_coins" );
        $player_2_coins = mysql_result( $result, $i, "player_2_coins" );

        $player_1_pot_coins =
            mysql_result( $result, $i, "player_1_pot_coins" );
        $player_2_pot_coins =
            mysql_result( $result, $i, "player_2_pot_coins" );


        $player_1_moves = mysql_result( $result, $i, "player_1_moves" );
        $player_2_moves = mysql_result( $result, $i, "player_2_moves" );

        $player_1_bet_made = mysql_result( $result, $i, "player_1_bet_made" );
        $player_2_bet_made = mysql_result( $result, $i, "player_2_bet_made" );

        $player_1_move_list = preg_split( "/#/", $player_1_moves );
        $player_2_move_list = preg_split( "/#/", $player_2_moves );
        
        $player_1_move_count = count( $player_1_move_list );
        $player_2_move_count = count( $player_2_move_list );

        $move_deadline = mysql_result( $result, $i, "move_deadline" );
        $now_time = mysql_result( $result, $i, "now_time" );

        cm_log( "endOldGames with ".
                "p1Moves = $player_1_moves, ".
                "p2Moves = $player_2_moves, ".
                "p1Pot = $player_1_pot_coins, ".
                "p2Pot = $player_1_pot_coins, ".
                "p1BetMade = $player_1_bet_made, ".
                "p2BetMade = $player_2_bet_made, ".
                "move_deadline = $move_deadline, ".
                "now_time = $now_time" );
        
        
        $pot = $player_1_pot_coins + $player_2_pot_coins;

        global $housePotFraction;

        // only rake matching part of pot, extra immune from rake
        $potToRake = 0;

        if( $player_2_pot_coins < $player_1_pot_coins ) {
            $potToRake = 2 * $player_2_pot_coins;
            }
        else {
            $potToRake = 2 * $player_1_pot_coins;
            }
            
        $housePotShare = floor( $potToRake * $housePotFraction );

        global $housePotLimit;

        if( $housePotShare > $housePotLimit ) {
            $housePotShare = $housePotLimit;
            }
        
        $pot -= $housePotShare;
        

        
        if( $player_1_id != 0 &&
            $player_2_id != 0 &&
            $player_1_move_count == 7 &&
            $player_2_move_count == 7 &&
            $player_1_bet_made &&
            $player_2_bet_made ) {

            // player leaving at the end of a round

            $loserID = cm_computeLoser( $game_square,
                                    $player_1_id, $player_2_id,
                                    $player_1_moves, $player_2_moves );
            $tie = false;
            
            if( $loserID == -1 ) {
                $loserID = $player_1_id;
                $tie = true;
                }

            if( $tie ) {
                // no rake on tie
                $player_1_coins += $player_1_pot_coins;
                $player_2_coins += $player_2_pot_coins;
                }
            else {
                
                if( $player_1_id == $loserID ) {
                    $player_2_coins += $pot;
                    }
                else {
                    $player_1_coins += $pot;
                    }
                }

            if( $player_1_id == $user_id ) {
                $player_1_id = 0;
                }
            else {
                $player_2_id = 0;
                }
            }
        // else player leaving in middle
        else if( ! $areGamesAllowed ) {
            // game force-ended by admin, return pots to players
            $player_1_coins += $player_1_pot_coins;
            $player_2_coins += $player_2_pot_coins;

            if( $player_1_id == $user_id ) {
                $player_1_id = 0;
                }
            else {
                $player_2_id = 0;
                }
            }
        else if( $player_1_id == $user_id ) {
            $player_1_id = 0;

            // whole pot to player 2
            $player_2_coins += $pot;
            }
        else if( $player_2_id == $user_id ) {
            $player_2_id = 0;
            
            // whole pot to player 1
            $player_1_coins += $pot;
            }

        $player_1_pot_coins = 0;
        $player_2_pot_coins = 0;


        global $houseTableCoins;

        $tournamentLive = cm_isTournamentLive( $dollar_amount );
        
        if( $tournamentLive ) {
            global $tournamentHouseTableCoins;

            $houseTableCoins = $tournamentHouseTableCoins;
            }
        
        if( $player_1_coins < 100 && $player_2_coins < 100 ) {
            // neither player recouped their table rake before one left
            // return table rake to the remaining player

            if( $old_player_1_id == $user_id ) {
                $player_2_coins += $houseTableCoins;
                }
            else if( $old_player_2_id == $user_id ) {
                $player_1_coins += $houseTableCoins;
                }
            }
        

        
        $player_1_payout =
            ( $player_1_coins * $dollar_amount ) / $cm_gameCoins;

        $player_2_payout =
            ( $player_2_coins * $dollar_amount ) / $cm_gameCoins;

        $house_coins =
            2 * $cm_gameCoins - ( $player_1_coins + $player_2_coins );
        
        $house_payout =
            ( $house_coins * $dollar_amount ) / $cm_gameCoins;


        // now that payouts computed, any that have left take their coins
        if( $player_1_id == 0 ) {
            $player_1_coins = 0;
            }
        else if( $player_2_id == 0 ) {
            $player_2_coins = 0;
            }
        
        
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_1_id = '$player_1_id', player_2_id = '$player_2_id', ".
            "player_1_coins = '$player_1_coins', ".
            "player_2_coins = '$player_2_coins', ".
            "player_1_pot_coins = 0, ".
            "player_2_pot_coins = 0 ".
            "WHERE game_id = '$game_id';";

        cm_log( "endOldGames updated $game_id, p1=$player_1_id, ".
                "p2=$player_2_id" );
        
        cm_queryDatabase( $query );
        
        if( $player_1_id != 0 ||
            $player_2_id != 0 ) {

            // first player just left the game, payout both
            cm_log( "endOldGames paying out both players for game $game_id" );



            // update Elo ratings
            $query = "SELECT elo_rating, games_started ".
                "FROM $tableNamePrefix"."users ".
                "WHERE user_id = $old_player_1_id;";

            $result = cm_queryDatabase( $query );

            $ratingA = mysql_result( $result, 0, "elo_rating" );
            $aN = mysql_result( $result, 0, "games_started" );
            $payoutA = $player_1_payout;

            $query = "SELECT elo_rating, games_started ".
                "FROM $tableNamePrefix"."users ".
                "WHERE user_id = $old_player_2_id;";

            $result = cm_queryDatabase( $query );

            $ratingB = mysql_result( $result, 0, "elo_rating" );
            $bN = mysql_result( $result, 0, "games_started" );
            $payoutB = $player_2_payout;


            $result = cm_computeNewElo( $ratingA, $ratingB, $aN, $bN,
                                        $payoutA, $payoutB );

            $ratingA = $result[0];
            $ratingB = $result[1];
            
            $query = "UPDATE $tableNamePrefix"."users ".
                "SET elo_rating = $ratingA WHERE user_id = $old_player_1_id;";
            cm_queryDatabase( $query );

            $query = "UPDATE $tableNamePrefix"."users ".
                "SET elo_rating = $ratingB WHERE user_id = $old_player_2_id;";
            cm_queryDatabase( $query );
            


            
            $won = $player_1_payout - $dollar_amount;
            $lost = 0;
            
            if( $won < 0 ) {
                $lost = - $won;
                $won = 0;
                }
            
            $query = "UPDATE $tableNamePrefix"."users ".
                "SET dollar_balance = dollar_balance + $player_1_payout, ".
                "total_won = total_won + $won, ".
                "total_lost = total_lost + $lost, ".
                "last_pay_out = $player_1_payout ".
                "WHERE user_id = '$old_player_1_id';";
            cm_queryDatabase( $query );

            cm_addLedgerEntry( $old_player_1_id, $game_id, $player_1_payout );

            

            if( $tournamentLive ) {
                cm_tournamentCashOut( $old_player_1_id, $player_1_payout );
                }

            
            
            $won = $player_2_payout - $dollar_amount;
            $lost = 0;
            
            if( $won < 0 ) {
                $lost = - $won;
                $won = 0;
                }
            
            $query = "UPDATE $tableNamePrefix"."users ".
                "SET dollar_balance = dollar_balance + $player_2_payout, ".
                "total_won = total_won + $won, ".
                "total_lost = total_lost + $lost, ".
                "last_pay_out = $player_2_payout ".
                "WHERE user_id = '$old_player_2_id';";
            cm_queryDatabase( $query );

            cm_addLedgerEntry( $old_player_2_id, $game_id, $player_2_payout );


            if( $tournamentLive ) {
                cm_tournamentCashOut( $old_player_2_id, $player_2_payout );
                }

            
            $query = "UPDATE $tableNamePrefix"."server_globals ".
                "SET house_dollar_balance = ".
                "  house_dollar_balance + $house_payout;";
            cm_queryDatabase( $query );

            cm_addLedgerEntry( 0, $game_id, $house_payout );

            if( $tournamentLive ) {
                cm_tournamentCashOut( 0, $house_payout );
                }
            
            cm_incrementStat( "total_house_rake", $house_payout );
            cm_updateMaxStat( "max_house_rake", $house_payout );
            
            // if other player is waiting for our move, free them to find
            // out that we left
            semSignal( $semaphore_key );
            }
        else {
            // both have now left

            // delete semaphore
            semRemove( $semaphore_key );

            cm_log( "endOldGames deleting game $game_id" );
            
            $query = "DELETE FROM $tableNamePrefix"."games ".
                "WHERE game_id = '$game_id';";

            cm_queryDatabase( $query );
            }
        
        
        
        
        }
    }



// for now, give everyone the same square
// assumes autocommit is off and caller will commit after
function cm_getNewSquare() {
    global $tableNamePrefix;

    
    // have it wrap around at the 32-bit unsigned max
    // because getMagicSquare6 takes a 32-bit unsigned seed.
    
    // we store it as a BIGINT to keep it from getting stuck on the same
    // square after four billion games
    $query = "SELECT next_magic_square_seed % 4294967296 ".
        "FROM $tableNamePrefix".
        "server_globals FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $next_magic_square_seed =
        mysql_result( $result, 0, 0 );
    
    $query = "UPDATE $tableNamePrefix". "server_globals SET ".
            "next_magic_square_seed = next_magic_square_seed + 1;";
    cm_queryDatabase( $query );

    $output = array();

    exec( "./getMagicSquare6 $next_magic_square_seed", $output );
    
    
    return $output[0];
    }




function cm_tournamentBuyIn( $user_id, $inIsHouse = false ) {
    global $tableNamePrefix, $tournamentCodeName, $tournamentStake;

    if( $inIsHouse ) {
        // house doesn't pay in
        $tournamentStake = 0;
        }

    $query = "INSERT INTO $tableNamePrefix"."tournament_stats ".
        "SET user_id = '$user_id', ".
        "    tournament_code_name = '$tournamentCodeName', ".
        "    update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = - $tournamentStake, ".
        "    num_games_started = 1 ".
        "ON DUPLICATE KEY UPDATE ".
        "    update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = net_dollars - $tournamentStake, ".
        "    num_games_started = num_games_started + 1;";

    cm_queryDatabase( $query );
    }



function cm_tournamentCashOut( $user_id, $inDollarsOut ) {
    global $tableNamePrefix, $tournamentCodeName;


    $query = "UPDATE $tableNamePrefix"."tournament_stats ".
        "SET user_id = '$user_id', ".
        "    update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = net_dollars + $inDollarsOut ".
        "WHERE user_id = $user_id AND ".
        "      tournament_code_name = '$tournamentCodeName';";

    cm_queryDatabase( $query );
    }



function cm_isTournamentLive( $inStakes ) {
    global $tournamentLive, $tournamentStake,
        $tournamentStartTime, $tournamentEndTime;

    if( ! $tournamentLive || $tournamentStake != $inStakes ) {
        return false;
        }
    $time = time();

    $startTime = strtotime( $tournamentStartTime );
    $endTime = strtotime( $tournamentEndTime );


    if( $time >= $startTime && $time <= $endTime ) {
        return true;
        }
    
    return false;
    }



// returns -1 if not, or stake if is live
function cm_getTournamentStake() {
    global $tournamentStake;

    if( ! cm_isTournamentLive( $tournamentStake ) ) {
        return -1;
        }

    return $tournamentStake;
    }




function cm_joinGame() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }


    global $areGamesAllowed;
    if( ! $areGamesAllowed ) {
        cm_log( "cm_joinGame denied, areGamesAllowed is off" );
        cm_transactionDeny();
        return;
        }
    
    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    global $tableNamePrefix ;

        
    $user_id = cm_getUserID();

    cm_queryDatabase( "SET AUTOCOMMIT=0" );

    
    $query = "SELECT account_key, dollar_balance, ".
        "request_sequence_number ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id' FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    
    if( $numRows == 0 ) {
        cm_log( "cm_joinGame denied, user $user_id not found" );
        cm_transactionDeny();
        return;
        }

    $row = mysql_fetch_array( $result, MYSQL_ASSOC );

    

    $account_key = $row[ "account_key" ];
    $dollar_balance = $row[ "dollar_balance" ];
    $old_request_sequence_number = $row[ "request_sequence_number" ];


    $request_sequence_number =
        cm_requestFilter( "request_sequence_number", "/\d+/" );


    if( $request_sequence_number < $old_request_sequence_number ) {
        cm_log( "cm_joinGame denied, stale request sequence number" );
        cm_transactionDeny();
        return;
        }

    // two fractional digits here
    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/[0-9]+[.][0-9][0-9]/i", "0.00" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "dollar_amount", $dollar_amount ) ) {
        return;
        }

    global $minGameStakes, $maxGameStakes;

    if( $dollar_amount < $minGameStakes || $dollar_amount > $maxGameStakes ) {
        cm_log( "cm_joinGame denied, dollar_amount $dollar_amount ".
                "is not in allowed range [$minGameStakes, $maxGameStakes]" );
        cm_transactionDeny();
        return;
        }

    
    $recomputedBalance = cm_recomputeBalanceFromHistory( $user_id );
    
    if( $dollar_balance != $recomputedBalance ) {

        $message = "User $user_id table dollar balance = $dollar_balance, ".
            "but recomputed balance = $recomputedBalance, ".
            "blocking join_game.";

        cm_log( $message );
        cm_informAdmin( $message );
        
        cm_transactionDeny();

        return;
        }
    

    if( $dollar_amount < 0.01 ) {
        cm_log( "cm_joinGame denied, requested game of value ".
                "\$$dollar_amount, too low" );
        cm_transactionDeny();
        return;
        }
    
    
    if( $dollar_amount > $dollar_balance ) {
        cm_log( "cm_joinGame denied, balance too low for requested game" );
        cm_transactionDeny();
        return;
        }
    

    // if we got here, we've got a valid request
        
    cm_endOldGames( $user_id );



    
    // does a game already exist with this value?

    $query = "SELECT semaphore_key, player_1_id, game_id ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id != 0 AND player_2_id = 0 AND started = 0 ".
        "AND dollar_amount = '$dollar_amount' ".
        "LIMIT 1 FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    
    $numRows = mysql_numrows( $result );

    
    if( $numRows == 1 ) {
        // one exists already

        // join it
        $game_id = mysql_result( $result, 0, "game_id" );
        $player_1_id = mysql_result( $result, 0, "player_1_id" );
        $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

        global $moveTimeLimit;
        
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_2_id = '$user_id', started = 1,  ".
            "player_1_coins = player_1_coins - 1, ".
            "player_2_coins = player_2_coins - 1, ".
            "player_1_pot_coins = player_1_pot_coins + 1, ".
            "player_2_pot_coins = player_2_pot_coins + 1, ".
            // buy-ins are done, ready for first moves to be made
            "player_1_bet_made = 1, player_2_bet_made = 1, ".
            "move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ".
            "WHERE game_id = '$game_id';";
        
        cm_queryDatabase( $query );


        // game has started now, subtract from both balances
        $query = "UPDATE $tableNamePrefix"."users ".
            "SET dollar_balance = dollar_balance - $dollar_amount, ".
            "games_started = games_started + 1, ".
            "total_buy_in = total_buy_in + $dollar_amount, ".
            "last_buy_in = $dollar_amount ".
            "WHERE user_id = '$player_1_id' OR user_id = '$user_id';";
        cm_queryDatabase( $query );

        cm_addLedgerEntry( $player_1_id, $game_id, - $dollar_amount );
        cm_addLedgerEntry( $user_id, $game_id, - $dollar_amount );


        if( cm_isTournamentLive( $dollar_amount ) ) {
            cm_tournamentBuyIn( $player_1_id );
            cm_tournamentBuyIn( $user_id );

	    // make sure house stat line exists
	    cm_tournamentBuyIn( 0, true );
            }
        
        
        $query = "UPDATE $tableNamePrefix"."users ".
            "SET games_joined = games_joined + 1 ".
            "WHERE user_id = '$user_id';";
        cm_queryDatabase( $query );

        
        $response = "OK";
        
        $query = "UPDATE $tableNamePrefix"."users SET ".
            "last_request_response = '$response', ".
            "last_request_tag = '$request_tag' ".
            "WHERE user_id = '$user_id';";

        cm_queryDatabase( $query );


        // wake up opponent who may be waiting
        semSignal( $semaphore_key );
        
    
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );

        cm_incrementStat( "game_count" );
        cm_incrementStat( "total_buy_in", $dollar_amount * 2 );

        cm_updateMaxStat( "max_game_stakes", $dollar_amount );
        
        echo $response;

        return;
        }


    
    // else, create a new one

    

    // don't subtract from their balance yet
    // wait until both join
    // (we later cash them both out when the first one leaves, for
    //  cleanest accounting.
    //  There's no money sitting in idle, half-full games.)
    //
    // reset last_buy_in too, because user hasn't actually bought in until
    // the opponent joins (then we set last_buy_in for both)
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "games_created = games_created + 1, last_buy_in = 0, ".
        "last_pay_out = 0 ".
        "WHERE user_id = '$user_id';";
    
    $result = cm_queryDatabase( $query );

    $square = cm_getNewSquare();

    global $startingSemKey;

    $query = "SELECT COALESCE( MAX( semaphore_key ) + 1, $startingSemKey ) ".
        "FROM $tableNamePrefix"."games;";

    $result = cm_queryDatabase( $query );

    $semaphore_key = mysql_result( $result, 0, 0 );
    
    // add game to the game table


    global $cm_gameCoins;

    global $houseTableCoins;

    if( cm_isTournamentLive( $dollar_amount ) ) {
        global $tournamentHouseTableCoins;
        $houseTableCoins = $tournamentHouseTableCoins;
        }
    
    
    $playerStartingCoins = $cm_gameCoins - $houseTableCoins;
    
    $query = "INSERT INTO $tableNamePrefix"."games SET ".
        // game_id is auto-increment
        "creation_time = CURRENT_TIMESTAMP, ".
        "last_action_time = CURRENT_TIMESTAMP, ".
        "player_1_id = '$user_id'," .
        "player_2_id = 0," .
        "dollar_amount = '$dollar_amount',".
        "started = 0,".
        "game_square = '$square',".
        "player_1_moves = '#',".
        "player_2_moves = '#',".
        "player_1_bet_made = 0,".
        "player_2_bet_made = 0,".
        "player_1_ended_round = 0,".
        "player_2_ended_round = 0,".
        "move_deadline = CURRENT_TIMESTAMP, ".
        "player_1_coins = $playerStartingCoins, ".
        "player_2_coins = $playerStartingCoins, ".
        "player_1_pot_coins = 0, ".
        "player_2_pot_coins = 0, ".
        "semaphore_key = '$semaphore_key';";
    $result = mysql_query( $query );

    
    $query = "SELECT game_id, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id';";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    
    if( $numRows == 0 ) {
        cm_log( "Newly created game not found" );
        cm_transactionDeny();
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

    $tryCount = 0;
    
    while( $tryCount < 10 && semInitLock( $semaphore_key ) != 0 ) {

        $semaphore_key ++;

        $query = "UPDATE $tableNamePrefix"."games SET ".
            "semaphore_key = '$semaphore_key' ".
            "WHERE game_id = '$game_id';";

        cm_queryDatabase( $query );
        cm_log( "Creating semaphore failed, trying next key $semaphore_key" );
        $tryCount++;
        }

    
    
    if( $tryCount == 10 ) {
        cm_log( "Tried to create semaphore 10 times, failed." );
        cm_transactionDeny();
        return;
        }
    
    

    $response = "OK";

    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
        
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
    echo $response;
    }




function cm_keepGameAlive( $user_id ) {
    global $tableNamePrefix;
    
    $query = "UPDATE $tableNamePrefix"."games ".
        "SET last_action_time = CURRENT_TIMESTAMP ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";

    cm_queryDatabase( $query );
    }




function cm_getOtherGameList( $user_id ) {
    global $tableNamePrefix;
    
    $query = "SELECT dollar_amount ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    
    if( $numRows == 0 ) {
        return "#";
        }
    
    $dollar_amount = mysql_result( $result, 0, "dollar_amount" );

    $query = "SELECT dollar_amount ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id != '$user_id' AND player_2_id != '$user_id' ".
        "AND started = 0 ".
        "ORDER BY ABS( dollar_amount - $dollar_amount ) ASC LIMIT 3;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    $otherGameList = "";

    if( $numRows == 0 ) {
        $otherGameList = "#";
        }
    else {
        $otherGameList = mysql_result( $result, 0, "dollar_amount" );
        
        for( $i=1; $i<$numRows; $i++ ) {
            
            $otherGameList .=
                "#". mysql_result( $result, $i, "dollar_amount" );
            }
        }

    return $otherGameList;
    }




function cm_waitGameStart() {
    if( ! cm_verifyTransaction() ) {
        return;
        }
    
    global $tableNamePrefix, $areGamesAllowed;

    if( ! $areGamesAllowed ) {
        cm_log( "cm_waitGameStart denied, areGamesAllowed is off" );
        cm_transactionDeny();
        return;
        }
        
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );
    
    
    $otherGameList = cm_getOtherGameList( $user_id );
    
    
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT semaphore_key, started ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    
    if( $numRows == 0 ) {
        cm_log( "Waiting on game that doesn't exist to start" );

        cm_transactionDeny();
        return;
        }

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );
    $started = mysql_result( $result, 0, "started" );
    
    if( $started != 0 ) {
        echo "started\n";
        echo "$otherGameList\n";
        echo "OK";
        return;
        }
    else {
        global $waitTimeout;

        semLock( $semaphore_key );

        cm_queryDatabase( "COMMIT;" );
        
        $result = semWait( $semaphore_key, $waitTimeout );

        
        $otherGameList = cm_getOtherGameList( $user_id );

        
        if( $result == -2 ) {
            echo "waiting\n";
            echo "$otherGameList\n";
            echo "OK";
            return;
            }
        else {


            $query = "SELECT player_2_id ".
                "FROM $tableNamePrefix"."games ".
                "WHERE player_1_id = '$user_id';";
            
            $result = cm_queryDatabase( $query );


            $numRows = mysql_numrows( $result );

    
            if( $numRows == 0 ) {
                cm_log( "Waiting on game that doesn't exist to start" );
                cm_transactionDeny();
                return;
                }

            $player_2_id = mysql_result( $result, 0, "player_2_id" );


            if( $player_2_id == 0 ) {
                // sem signaled, but opponent still not there?
                echo "waiting\n";
                echo "$otherGameList\n";
                echo "OK";
                return;
                }
            else {
                // opponent present
                echo "started\n";
                echo "$otherGameList\n";
                echo "OK";
                return;
                }
            
            }
        }
    }




function cm_leaveGame() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );
    
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    cm_endOldGames( $user_id );

    global $tableNamePrefix;
    
    $query = "SELECT last_buy_in, last_pay_out ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows == 0 ) {
        cm_transactionDeny();
        return;
        }

    $last_buy_in = mysql_result( $result, 0, "last_buy_in" );
    $last_pay_out = mysql_result( $result, 0, "last_pay_out" );

    
    cm_queryDatabase( "COMMIT;" );

    echo "$last_buy_in\n";
    echo "$last_pay_out\n";
    echo "OK";
    }



function cm_listGames() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    global $areGamesAllowed, $minGameStakes, $maxGameStakes;

    if( !$areGamesAllowed ) {
        echo "0\n";
        echo "$minGameStakes\n";
        echo "$maxGameStakes\n";
        echo "0#0\n";
        echo "OK";
        return;
        }
    else {
        echo "1\n";
        echo "$minGameStakes\n";
        echo "$maxGameStakes\n";
        }
    

    $user_id = cm_getUserID();

    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    cm_endOldGames( $user_id );
    
    cm_queryDatabase( "COMMIT;" );

    cm_queryDatabase( "SET AUTOCOMMIT=1" );

    
    $skip = cm_requestFilter( "skip", "/\d+/", 0 );
    
    $limit = cm_requestFilter( "limit", "/\d+/", 10 );


    global $tableNamePrefix;


    $tournamentStake = cm_getTournamentStake();

    $skipAdjust = 0;

    $ignoreClause = "";
    if( $tournamentStake != -1 ) {
        // leave room for tournament stake which appears on every page
        $limit --;

        // estimate that client shows 9 per page
        $pages = $skip / 9;

        $skipAdjust = $pages;
        
        // we've been inserting a dummy entry on each page
        $skip -= $skipAdjust;

        $ignoreClause = " AND dollar_amount != $tournamentStake ";        
        }

    
    // get one extra, beyond requested limit, to detect presence
    // of additional pages beyond limit  
    $query_limit = $limit + 1;
    
    $query = "SELECT dollar_amount FROM $tableNamePrefix"."games ".
        "WHERE player_2_id = 0 AND started = 0 $ignoreClause".
        "ORDER BY dollar_amount ASC ".
        "LIMIT $skip, $query_limit;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );


    
    
    
    if( $numRows == 0 && $skip != 0 ) {
        // gone off end (maybe game list has changed since player loaded
        // last page)

        // wrap around
        $skip = 0;

        $query = "SELECT dollar_amount FROM $tableNamePrefix"."games ".
            "WHERE player_2_id = 0 AND started = 0 $ignoreClause".
            "ORDER BY dollar_amount ASC ".
            "LIMIT $skip, $query_limit;";
        
        $result = cm_queryDatabase( $query );
        
        $numRows = mysql_numrows( $result );
        }
    

    if( $tournamentStake != -1 ) {
        // always stick tournament stakes at top of list
        echo "$tournamentStake\n";

        // adjust back to the skip they requested (unless we wrapped around)
        if( $skip != 0 ) {
            $skip += $skipAdjust;
            }
        }

    for( $i=0; $i < $numRows && $i < $limit; $i++ ) {
        $dollar_amount = mysql_result( $result, $i, "dollar_amount" );

        echo "$dollar_amount\n";
        }

    if( $numRows > $limit ) {
        echo "1#$skip\n";
        }
    else {
        echo "0#$skip\n";
        }
    echo "OK";
    }



function cm_swapSquare( $inSquare ) {
    $squareParts = preg_split( "/#/", $inSquare );

    $swappedParts = array();

    for( $x=0; $x<6; $x++ ) {
        
        for( $y=5; $y>=0; $y-- ) {

            $i = $y * 6 + $x;

            $swappedParts[] = $squareParts[$i];
            }
        }
    return implode( "#", $swappedParts );
    }




function cm_getGameState() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    cm_printGameState( true );
    }




// assumes transaction is verified already
function cm_printGameState( $inHideOpponentSecretMoves = true ) {
    

    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );


    global $tableNamePrefix;


    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "game_square, player_1_coins, player_2_coins, ".
        "player_1_pot_coins, player_2_pot_coins, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_moves, player_2_moves, ".
        "TIMESTAMPDIFF( SECOND, CURRENT_TIMESTAMP, move_deadline ) ".
        "  AS seconds_left ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    
    global $areGamesAllowed;

    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
        
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );
    $game_square = mysql_result( $result, 0, "game_square" );

    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );


    $seconds_left = mysql_result( $result, 0, "seconds_left" );

    // give the player a grace period by giving them a shorter deadline
    // than what is actually enforced
    global $moveLimitGraceSeconds;
    $seconds_left -= $moveLimitGraceSeconds;
    

    global $areGamesAllowed;
    
    $running = 1;
    if( $player_1_id == 0 || $player_2_id == 0 ) {
        $running = 0;
        }

    $your_coins = $player_1_coins;
    $your_pot_coins = $player_1_pot_coins;

    $their_coins = $player_2_coins;
    $their_pot_coins = $player_2_pot_coins;

    $your_moves = $player_1_moves;
    $their_moves = $player_2_moves;
    
    if( $player_2_id == $user_id ) {

        $your_coins = $player_2_coins;
        $your_pot_coins = $player_2_pot_coins;
        
        $their_coins = $player_1_coins;
        $their_pot_coins = $player_1_pot_coins;

        $game_square = cm_swapSquare( $game_square );

        $your_moves = $player_2_moves;
        $their_moves = $player_1_moves;
        }



    if( $their_moves != "#" ) {
            
        $moves = preg_split( "/#/", $their_moves );

        $numMoves = count( $moves );

        if( $user_id == $player_1_id ) {
            
            // row 5 is our column 0
            for( $i=0; $i<$numMoves; $i++ ) {
                $moves[$i] = 5 - $moves[$i];
                }
            }

        
        $reveal = false;

        if( $numMoves == 7 &&
            $player_1_pot_coins == $player_2_pot_coins &&
            $player_1_bet_made && $player_2_bet_made ) {

            $movesUs = preg_split( "/#/", $your_moves );

            $numMovesUs = count( $movesUs );

            if( $numMovesUs == 7 ) {
                // game round done
                $reveal = true;
                }
            }

        
        if( $inHideOpponentSecretMoves && ! $reveal ) {

            $theirReveal = -1;
            if( $numMoves > 6 ) {
                $theirReveal = $moves[6];
                }
            
            // replace moves they made for themselves with ?
            $theirRevealIndex = -1;
            $movesToScan = $numMoves;
            if( $movesToScan > 6 ) {
                $movesToScan = 6;
                }
            for( $i=0; $i<$movesToScan; $i++ ) {
                if( $i % 2 == 0 &&
                    $theirReveal != $moves[$i] ) {

                    $moves[$i] = "?";
                    }
                else if( $theirReveal == $moves[$i] ) {
                    $theirRevealIndex = $i;
                    }
                }

            if( $theirRevealIndex != -1 ) {
                // reveal of an out-of-order move, other than their first
                // move

                // but game is done so order doesn't matter to players
                // anymore

                // swap this into the first move position
                $temp = $moves[0];
                $moves[0] = $moves[$theirRevealIndex];
                $moves[$theirRevealIndex] = $temp;

                // same for corresponding your_move pick

                $your_moves_split = preg_split( "/#/", $your_moves );

                $temp = $your_moves_split[1];
                $your_moves_split[1] =
                    $your_moves_split[$theirRevealIndex + 1];
                $your_moves_split[$theirRevealIndex + 1] = $temp;

                $your_moves = implode( "#", $your_moves_split );
                }
            }
        
        $their_moves = implode( "#", $moves );
        }

    // never show more of their moves than what we've committed so far
    // in either direction.  Move lists are always the same length
    if( $your_moves == "#" ) {
        $their_moves = "#";
        }
    if( $their_moves == "#" ) {
        $your_moves = "#";
        }
    else if( strlen( $your_moves ) < strlen( $their_moves ) ) {

        // trim
        $their_moves = substr( $their_moves, 0, strlen( $your_moves ) );
        }
    else if( strlen( $their_moves ) < strlen( $your_moves ) ) {

        // trim
        $your_moves = substr( $your_moves, 0, strlen( $their_moves ) );
        }

    if( $your_pot_coins > $their_pot_coins ) {
        // we've bet more than them, so we're getting state out of order here
        // we should be waiting for them to match our bet before getting
        // state.  This should never happen, but a rogue client might
        // do it.

        $message = "Game id $game_id, we're getting game state ".
            "when we shouldn't be, our pot = $your_pot_coins, ".
            "their pot = $their_pot_coins, at " . date( DATE_RFC2822 );

        cm_log( $message );
        
        cm_informAdmin( $message );

        
        // fix it to hide info

        // default to showing only ante pots
        $your_coins += $your_pot_coins - 1;
        $their_coins += $their_pot_coins - 1;

        $your_pot_coins = 1;
        $their_pot_coins = 1;
        }
    
    
    echo "$running\n";    
    echo "$game_square\n";
    echo "$your_coins\n";
    echo "$their_coins\n";
    echo "$your_pot_coins\n";
    echo "$their_pot_coins\n";
    echo "$your_moves\n";
    echo "$their_moves\n";
    echo "$seconds_left\n";
    echo "OK";
    }




function cm_makeMove() {    
    
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }
    
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    $our_column = cm_requestFilter( "our_column", "/[0-5]/", "0" );

    $their_column = cm_requestFilter( "their_column", "/[0-5]/", "0" );
    

    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_pot_coins, player_2_pot_coins, ".
        "player_1_moves, player_2_moves, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE ( player_1_id = '$user_id' OR player_2_id = '$user_id' )".
        "AND player_1_bet_made = 1 AND player_2_bet_made = 1 ".
        "AND player_1_pot_coins = player_2_pot_coins ".
        "AND started = 1 ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;

    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {
            cm_log(
                "Making a move for a game that doesn't exist ".
                "(or maybe game is in betting phases, so moves forbidden)" );
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Making move us $our_column, ".
            "them $their_column for game $game_id" );
    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );
    
    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

    $our_moves = "";
    $their_moves = "";
    
    if( $player_1_id == $user_id ) {
        $our_moves = $player_1_moves;
        $their_moves = $player_2_moves;
        }
    else {
        $our_moves = $player_2_moves;
        $their_moves = $player_1_moves;
        }

    if( ! $player_1_bet_made || ! $player_2_bet_made ||
        $player_1_pot_coins != $player_2_pot_coins ) {
        cm_log( "Making a move when it's time for betting, ".
                "blocked" );
        cm_transactionDeny();
        return;
        }

    

    if( strlen( $our_moves ) > strlen( $their_moves ) ) {
        cm_log( "Making another move when we're waiting for their move, ".
                "blocked" );
        cm_transactionDeny();
        return;
        }
    
    
    if( strstr( $our_moves, $our_column ) ||
        strstr( $our_moves, $their_column ) ) {
        
        cm_log( "Player already used column $our_column or $their_column ".
                " in past move list $our_moves" );
        cm_transactionDeny();
        return;
        }
    
    
    if( $our_moves != "#" ) {
        $our_moves = $our_moves . "#";
        }
    else {
        // clear placeholder pound sign to make room for first move
        $our_moves = "";
        }
    
    
    $our_moves = $our_moves . $our_column . "#" .$their_column;


    if( $player_1_id == $user_id ) {
        $player_1_moves =  $our_moves;
        }
    else {
        $player_2_moves = $our_moves;
        }

    $betsMade = 1;

    $deadlineUpdate = "";
    
    if( strlen( $player_1_moves ) == strlen( $player_2_moves ) ) {
        // get ready for next betting round
        $betsMade = 0;
        
        global $moveTimeLimit;
        $deadlineUpdate =
          ", move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ";
        }
    

    $query = "UPDATE $tableNamePrefix"."games ".
        "SET player_1_moves = '$player_1_moves', ".
        "player_2_moves = '$player_2_moves', ".
        "player_1_bet_made = '$betsMade', ".
        "player_2_bet_made = '$betsMade' ".
        $deadlineUpdate .
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";
    

    $result = cm_queryDatabase( $query );
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );

    
    // if they are waiting, they can stop waiting
    semSignal( $semaphore_key );

    
    $response = "OK";

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
    echo $response;
    }





function cm_makeRevealMove() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }
    
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    $our_column = cm_requestFilter( "our_column", "/[0-5]/", "0" );
    

    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_pot_coins, player_2_pot_coins, ".
        "player_1_moves, player_2_moves, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE ( player_1_id = '$user_id' OR player_2_id = '$user_id' )".
        "AND player_1_bet_made = 1 AND player_2_bet_made = 1 ".
        "AND player_1_pot_coins = player_2_pot_coins ".
        "AND started = 1 ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;
    
    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {
            cm_log( "Making a move for a game that doesn't exist ".
                "(or maybe game is in betting phases, so moves forbidden)" );
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Making reveal move $our_column for game $game_id" );

    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );
    
    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

    $our_moves = "";
    $their_moves = "";
    
    if( $player_1_id == $user_id ) {
        $our_moves = $player_1_moves;
        $their_moves = $player_2_moves;
        }
    else {
        $our_moves = $player_2_moves;
        $their_moves = $player_1_moves;
        }

    if( ! $player_1_bet_made || ! $player_2_bet_made ||
        $player_1_pot_coins != $player_2_pot_coins ) {
        cm_log( "Making a move when it's time for betting, ".
                "blocked" );
        cm_transactionDeny();
        return;
        }

    

    if( strlen( $our_moves ) > strlen( $their_moves ) ) {
        cm_log( "Making another move when we're waiting for their move, ".
                "blocked" );
        cm_transactionDeny();
        return;
        }

    if( strlen( $our_moves ) < 11 ) {
         cm_log( "Making a reveal move before we've made our main moves, ".
                "blocked" );
        cm_transactionDeny();
        return;
        }
    

    $our_moves_split = preg_split( "/#/", $our_moves ); 
    $revealIndex = array_search( $our_column, $our_moves_split );
    
    if( $revealIndex % 2 != 0 ) {
        
        cm_log( "Player trying to reveal $our_column, but that column ".
                " is not theirs in past move list $our_moves" );
        cm_transactionDeny();
        return;
        }
    
    
    if( $our_moves != "#" ) {
        $our_moves = $our_moves . "#";
        }
    
    
    $our_moves = $our_moves . $our_column;


    if( $player_1_id == $user_id ) {
        $player_1_moves =  $our_moves;
        }
    else {
        $player_2_moves = $our_moves;
        }

    $betsMade = 1;
    $deadlineUpdate = "";
    
    if( strlen( $player_1_moves ) == strlen( $player_2_moves ) ) {
        // get ready for next betting round
        $betsMade = 0;

        global $moveTimeLimit;
        $deadlineUpdate =
          ", move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ";
        }
    

    $query = "UPDATE $tableNamePrefix"."games ".
        "SET player_1_moves = '$player_1_moves', ".
        "player_2_moves = '$player_2_moves', ".
        "player_1_bet_made = '$betsMade', ".
        "player_2_bet_made = '$betsMade' ".
        $deadlineUpdate .
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";
    

    $result = cm_queryDatabase( $query );
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );

    
    // if they are waiting, they can stop waiting
    semSignal( $semaphore_key );

    
    $response = "OK";

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
    echo $response;
    }





function cm_makeBet() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }
    
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    $bet = cm_requestFilter( "bet", "/[0-9]+/", "0" );

    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "player_1_moves, player_2_moves, ".
        "player_1_coins, player_2_coins, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_pot_coins, player_2_pot_coins, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;
    
    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {
            cm_log( "Making a bet for a game that doesn't exist" );
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Making bet $bet for game $game_id" );

    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );
    
    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );

    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    
    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );


    if( strlen( $player_1_moves ) != strlen( $player_2_moves ) ||
        $player_1_moves == "#" || $player_2_moves == "#" ) {

        cm_log( "Making a bet when no bets allowed ".
                "(incomplete moves for one or both players)" );
        cm_transactionDeny();
        return;
        }
        
    
    if( $player_1_bet_made && $player_2_bet_made &&
        $player_1_pot_coins == $player_2_pot_coins ) {

        cm_log( "Making a bet when no bets allowed ".
                "(both players have already placed matching bets ".
                "or buy-ins)" );
        cm_transactionDeny();
        return;
        }
        
    
    $otherID;
    
    $ourCoins;
    $theirCoins;
    
    $ourPotCoins;
    $theirPotCoins;
    if( $player_1_id == $user_id ) {
        $ourCoins = $player_1_coins;
        $theirCoins = $player_2_coins;

        $ourPotCoins = $player_1_pot_coins;
        $theirPotCoins = $player_2_pot_coins;

        $otherID = $player_2_id;
        }
    else {
        $ourCoins = $player_2_coins;
        $theirCoins = $player_1_coins;

        $ourPotCoins = $player_2_pot_coins;
        $theirPotCoins = $player_1_pot_coins;

        $otherID = $player_1_id;
        }

    if( $bet > $ourCoins ) {
        cm_log( "Bet of $bet exceeds player's available coins" );
        cm_transactionDeny();
        return;
        }
    if( $otherID != 0 &&
        $bet + $ourPotCoins > $theirCoins + $theirPotCoins ) {
        cm_log( "Bet of $bet exceeds opponent player's available coins" );
        cm_transactionDeny();
        return;
        }

    if( $otherID != 0 ) {
        // ignore bet if opponent has left
        $ourPotCoins += $bet;
        $ourCoins -= $bet;
        }
    

    if( $player_1_id == $user_id ) {
        $player_1_coins =  $ourCoins;
        $player_1_pot_coins =  $ourPotCoins;
        $player_1_bet_made = 1;
        }
    else {
        $player_2_coins =  $ourCoins;
        $player_2_pot_coins =  $ourPotCoins;
        $player_2_bet_made = 1;
        }

    $deadlineUpdate = "";

    if( $player_2_bet_made && $player_2_bet_made ) {
        global $moveTimeLimit;
        $deadlineUpdate =
          ", move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ";
        }


    $query = "UPDATE $tableNamePrefix"."games ".
        "SET player_1_coins = '$player_1_coins', ".
        "player_2_coins = '$player_2_coins', ".
        "player_1_bet_made = '$player_1_bet_made', ".
        "player_2_bet_made = '$player_2_bet_made', ".
        "player_1_pot_coins = '$player_1_pot_coins', ".
        "player_2_pot_coins = '$player_2_pot_coins' ".
        $deadlineUpdate .
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";
    

    $result = cm_queryDatabase( $query );
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    
    // if they are waiting, they can stop waiting
    semSignal( $semaphore_key );

    $response = "OK";

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
    echo $response;
    }




function cm_foldBet() {
    if( ! cm_verifyTransaction() ) {
        return;
        }
    
    if( cm_handleRepeatResponse() ) {
        return;
        }

    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "player_1_pot_coins, player_2_pot_coins, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;
    
    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {    
            cm_log( "Folding a bet for a game that doesn't exist" );
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Folding bet for game $game_id" );

    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );
    
    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

    if( $player_1_pot_coins == $player_2_pot_coins
        ||
        ( $user_id == $player_1_id &&
          $player_1_pot_coins > $player_2_pot_coins )
        ||
        ( $user_id == $player_2_id &&
          $player_2_pot_coins > $player_1_pot_coins ) ) {

        cm_log( "Folding a bet when no folding allowed ".
                "(players have matching pot coins, or we're already higher)" );
        cm_transactionDeny();
        return;
        }

    
    cm_makeRoundLoser( $user_id );

    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    
    // if they are waiting, they can stop waiting
    semSignal( $semaphore_key );

    $response = "OK";

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
    echo $response;
    }




// makes one player a loser (or ties) and handles coin distribution
// starts a new game after, if possible
// assumes autocommit is off and caller will commit after
// $inTie forces all pot coins to be returned
function cm_makeRoundLoser( $inLoserID, $inTie = false ) {
    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT player_1_id, player_2_id,".
        "player_1_coins, player_2_coins, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_pot_coins, player_2_pot_coins ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$inLoserID' OR player_2_id = '$inLoserID' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows != 1 ) {
        cm_log( "Making a round loser for a game that doesn't exist" );
        cm_transactionDeny();
        return;
        }
    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );

    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    
    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    
    global $housePotFraction;

    if( $inTie ) {
        $player_1_coins += $player_1_pot_coins;
        $player_2_coins += $player_2_pot_coins;
        // house takes nothing
        }
    else if( $player_1_id == $inLoserID ) {

        $extra = $player_2_pot_coins - $player_1_pot_coins;

        // immune from house fraction
        $player_2_coins += $extra;

        $pot = $player_2_pot_coins + $player_1_pot_coins - $extra;


        $houseCoins = floor( $pot * $housePotFraction );

        global $housePotLimit;

        if( $houseCoins > $housePotLimit ) {
            $houseCoins = $housePotLimit;
            }
        
        $pot -= $houseCoins;

        $player_2_coins += $pot;

        
        if( $extra > 0 ) {
            // player 1 folded, mark their bet as unmade
            // to block reveal
            $player_1_bet_made = 0;
            }
        }
    else {
        $extra = $player_1_pot_coins - $player_2_pot_coins;

        // immune from house fraction
        $player_1_coins += $extra;

        $pot = $player_1_pot_coins + $player_2_pot_coins - $extra;


        $houseCoins = floor( $pot * $housePotFraction );

        global $housePotLimit;

        if( $houseCoins > $housePotLimit ) {
            $houseCoins = $housePotLimit;
            }
        
        $pot -= $houseCoins;

        $player_1_coins += $pot;

        
        if( $extra > 0 ) {
            // player 2 folded, mark their bet as unmade
            // to block reveal
            $player_2_bet_made = 0;
            }
        }


    $player_1_pot_coins = 0;
    $player_2_pot_coins = 0;
    
    
    
    global $moveTimeLimit;

    
    $query = "UPDATE $tableNamePrefix"."games ".
        "SET player_1_coins = '$player_1_coins', ".
        "player_2_coins = '$player_2_coins', ".
        "player_1_bet_made = '$player_1_bet_made', ".
        "player_2_bet_made = '$player_2_bet_made', ".
        "player_1_pot_coins = '$player_1_pot_coins', ".
        "player_2_pot_coins = '$player_2_pot_coins', ".
        "move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ".
        "WHERE player_1_id = '$inLoserID' OR player_2_id = '$inLoserID';";
    

    $result = cm_queryDatabase( $query );
    }



// returns losing user_id or -1 on tie
function cm_computeLoser( $game_square, $player_1_id, $player_2_id,
                          $player_1_moves, $player_2_moves ) {

    $game_cells = preg_split( "/#/", $game_square );
        
    $player_1_move_list = preg_split( "/#/", $player_1_moves );
    $player_2_move_list = preg_split( "/#/", $player_2_moves );
    
    // compute score to find out who won
    $p1Score = 0;
    $p2Score = 0;

    for( $i=0; $i<3; $i++ ) {
        $p1SelfChoice = $player_1_move_list[ $i * 2 ];
        $p1OtherChoice = $player_1_move_list[ $i * 2 + 1 ];
        
        $p2SelfChoice = 5 - $player_2_move_list[ $i * 2 ];
        $p2OtherChoice = 5 - $player_2_move_list[ $i * 2 + 1 ];
        
        $p1Score += $game_cells[ $p2OtherChoice * 6 + $p1SelfChoice ];
        $p2Score += $game_cells[ $p2SelfChoice * 6 + $p1OtherChoice ];
        
        }
    
        
    if( $p1Score < $p2Score ) {
        return $player_1_id;
        }
    else if( $p1Score > $p2Score ) {
        return $player_2_id;
        }
    else {
        return -1;
        }
    }





function cm_endRound() {
    if( ! cm_verifyTransaction() ) {
        return;
        }
    
    if( cm_handleRepeatResponse() ) {
        return;
        }
    
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "game_square, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_moves, player_2_moves, ".
        "player_1_ended_round, player_2_ended_round, ".
        "player_1_pot_coins, player_2_pot_coins, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;
    
    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {
            cm_log( "Ending round for a game that doesn't exist" );
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Ending round for game $game_id" );

    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );

    $game_square = mysql_result( $result, 0, "game_square" );
    
    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );

    $player_1_ended_round = mysql_result( $result, 0, "player_1_ended_round" );
    $player_2_ended_round = mysql_result( $result, 0, "player_2_ended_round" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );


    $player_1_move_list = preg_split( "/#/", $player_1_moves );
    $player_2_move_list = preg_split( "/#/", $player_2_moves );

    $player_1_move_count = count( $player_1_move_list );
    $player_2_move_count = count( $player_2_move_list );
             
    if( $player_1_move_count != 7
        ||
        $player_2_move_count != 7
        ||
        ( $user_id == $player_1_id && $player_1_ended_round == 1 )
        ||
        ( $user_id == $player_2_id && $player_2_ended_round == 1 )
        ||
        $player_1_pot_coins != $player_2_pot_coins
        ||
        ! $player_1_bet_made || ! $player_2_bet_made ) {

        cm_log( "Ending round when ending not allowed " );
        cm_transactionDeny();
        return;
        }


    if( $user_id == $player_1_id ) {
        $player_1_ended_round = 1;
        }
    else {
        $player_2_ended_round = 1;
        }

    
    if( $player_1_ended_round == 1 && $player_2_ended_round == 1 ) {

        $loserID = cm_computeLoser( $game_square,
                                    $player_1_id, $player_2_id,
                                    $player_1_moves, $player_2_moves );
        $tie = false;

        if( $loserID == -1 ) {
            $loserID = $player_1_id;
            $tie = true;
            }

        
        cm_makeRoundLoser( $loserID, $tie );
        }

    global $endRoundTimeLimit;
    
    $query = "UPDATE $tableNamePrefix"."games ".
        "SET player_1_ended_round = '$player_1_ended_round', ".
        "player_2_ended_round = '$player_2_ended_round', ".
        "move_deadline = ".
        "ADDTIME( CURRENT_TIMESTAMP, '$endRoundTimeLimit' ) ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";
    

    $result = cm_queryDatabase( $query );
    
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    
    // if they are waiting, they can stop waiting
    semSignal( $semaphore_key );

    $response = "OK";

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
    echo $response;
    }





function cm_startNextRound() {
    if( ! cm_verifyTransaction() ) {
        return;
        }
    
    if( cm_handleRepeatResponse() ) {
        return;
        }
    
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id,".
        "game_square, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_moves, player_2_moves, ".
        "player_1_ended_round, player_2_ended_round, ".
        "player_1_coins, player_2_coins, ".
        "player_1_pot_coins, player_2_pot_coins, semaphore_key ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;
    
    if( $numRows != 1 ) {
        if( $areGamesAllowed ) {
            cm_log( "Starting next round for a game that doesn't exist" );
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    
    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Starting next round for game $game_id" );

    
    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );

    $game_square = mysql_result( $result, 0, "game_square" );
    
    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );

    $player_1_ended_round = mysql_result( $result, 0, "player_1_ended_round" );
    $player_2_ended_round = mysql_result( $result, 0, "player_2_ended_round" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );


    $player_1_move_list = preg_split( "/#/", $player_1_moves );
    $player_2_move_list = preg_split( "/#/", $player_2_moves );

    $player_1_move_count = count( $player_1_move_list );
    $player_2_move_count = count( $player_2_move_list );
             
    if( $player_1_pot_coins != 0 || $player_2_pot_coins != 0 ) {

        cm_log( "Starting next round when not allowed " );
        cm_transactionDeny();
        return;
        }


    if( $user_id == $player_1_id ) {
        $player_1_ended_round = 2;
        }
    else {
        $player_2_ended_round = 2;
        }

    
    if( $player_1_ended_round == 2 && $player_2_ended_round == 2 ) {

        if( $player_1_coins > 0 && $player_2_coins > 0 ) {
            // start a new game

            $game_square = cm_getNewSquare();
            
            $player_1_coins --;
            $player_2_coins --;
            
            $player_1_pot_coins = 1;
            $player_2_pot_coins = 1;
            
            $player_1_bet_made = 1;
            $player_2_bet_made = 1;
            }
        else {
            // one player is out of coins
            
            $player_1_pot_coins = 0;
            $player_2_pot_coins = 0;
            
            $player_1_bet_made = 0;
            $player_2_bet_made = 0;
            
            // this state, no moves made, but no bets made (no buy-in),
            // but still flagged as started, means the game is over
            }

        global $moveTimeLimit;
            
        
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_1_coins = '$player_1_coins', ".
            "player_2_coins = '$player_2_coins', ".
            "player_1_bet_made = '$player_1_bet_made', ".
            "player_2_bet_made = '$player_2_bet_made', ".
            "player_1_pot_coins = '$player_1_pot_coins', ".
            "player_2_pot_coins = '$player_2_pot_coins', ".
            "player_1_moves = '#', ".
            "player_2_moves = '#', ".
            "player_1_ended_round = '0', ".
            "player_2_ended_round = '0', ".
            "game_square = '$game_square', ".
            "move_deadline = ".
            "ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ".
            "WHERE player_1_id = '$user_id' OR ".
            "player_2_id = '$user_id';";
        

        $result = cm_queryDatabase( $query );
        }
    else {
        global $endRoundTimeLimit;
        
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_1_ended_round = '$player_1_ended_round', ".
            "player_2_ended_round = '$player_2_ended_round', ".
            "move_deadline = ".
            "ADDTIME( CURRENT_TIMESTAMP, '$endRoundTimeLimit' ) ".
            "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";
    

        $result = cm_queryDatabase( $query );
        }

    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    
    // if they are waiting, they can stop waiting
    semSignal( $semaphore_key );

    $response = "OK";

    $request_tag = cm_requestFilter( "request_tag", "/[A-F0-9]+/i", "" );
    
    $query = "UPDATE $tableNamePrefix"."users SET ".
        "last_request_response = '$response', ".
        "last_request_tag = '$request_tag' ".
        "WHERE user_id = '$user_id';";

    cm_queryDatabase( $query );
    
    echo $response;
    }







function cm_waitMove() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    cm_waitMoveInternal( true );
    }



function cm_waitMoveInternal( $inWaitOnSemaphore ) {
    
    global $tableNamePrefix;

        
    $user_id = cm_getUserID();

    cm_keepGameAlive( $user_id );

    
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT game_id, player_1_id, player_2_id, ".
        "semaphore_key, player_1_moves, player_2_moves, ".
        "player_1_pot_coins, player_2_pot_coins, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_ended_round, player_2_ended_round, ".
        "TIMESTAMPDIFF( SECOND, CURRENT_TIMESTAMP, move_deadline ) ".
        "  AS seconds_left ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    global $areGamesAllowed;
    
    if( $numRows == 0 ) {
        if( $areGamesAllowed ) {
            cm_log( "Waiting on move for a game that doesn't exist" );
            /*
            cm_informAdmin(
                "User $user_id waiting on move for game that ".
                "doesn't exist at ". date( DATE_RFC2822 ) );
            */
            echo "GAME_EXPIRED";
            }
        else {
            echo "GAME_ENDED";
            }
        return;
        }

    $player_1_id = mysql_result( $result, 0, "player_1_id" );
    $player_2_id = mysql_result( $result, 0, "player_2_id" );


    global $areGamesAllowed;
    if( ! $areGamesAllowed ) {
        
        // first to wait ends game for the other

        // endOldGames will return pots to each in this case
        $otherPlayer = $player_2_id;
            
        if( $user_id == $player_2_id ) {
            $otherPlayer = $player_1_id;
            }
        cm_endOldGames( $otherPlayer );

        cm_queryDatabase( "COMMIT;" );
        
        echo "GAME_ENDED";
        return;        
        }
    
    
    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );
    
    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );
    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_ended_round = mysql_result( $result, 0, "player_1_ended_round" );
    $player_2_ended_round = mysql_result( $result, 0, "player_2_ended_round" );


    $seconds_left = mysql_result( $result, 0, "seconds_left" );

    
    $game_id = mysql_result( $result, 0, "game_id" );
    cm_log( "Waiting move with $seconds_left seconds left for game $game_id" );

    
    
    $ourPotCoins;
    $theirPotCoins;

    $ourMoves;
    $theirMoves;
    
    $ourBetMade;
    $theirBetMade;

    $weEndedRound;
    $theyEndedRound;
    
    if( $player_1_id == $user_id ) {
        $ourPotCoins = $player_1_pot_coins;
        $theirPotCoins = $player_2_pot_coins;

        $ourMoves = $player_1_moves;
        $theirMoves = $player_2_moves;

        $ourBetMade = $player_1_bet_made;
        $theirBetMade = $player_2_bet_made;

        $weEndedRound = $player_1_ended_round;
        $theyEndedRound = $player_2_ended_round;
        }
    else {
        $ourPotCoins = $player_2_pot_coins;
        $theirPotCoins = $player_1_pot_coins;

        $ourMoves = $player_2_moves;
        $theirMoves = $player_1_moves;

        $ourBetMade = $player_2_bet_made;
        $theirBetMade = $player_1_bet_made;

        $weEndedRound = $player_2_ended_round;
        $theyEndedRound = $player_1_ended_round;
        }
    

    if( $player_1_id == 0 || $player_2_id == 0 ) {
        echo "opponent_left\nOK";
        return;
        }
    else if( strlen( $ourMoves ) <= strlen( $theirMoves ) &&
             ( $ourBetMade <= $theirBetMade ||
               // watch for case where round ended without full bets made
               // (one player folded)
               ( $player_1_pot_coins == 0 && $player_2_pot_coins == 0 ) )
             &&
             $weEndedRound <= $theyEndedRound &&
             $theirPotCoins >= $ourPotCoins ) {

        if( $player_1_pot_coins == 0 && $player_2_pot_coins == 0 ) {
            echo "round_ended\nOK";
            }
        else if( $player_1_moves != "#" ) {
            
            echo "move_ready\nOK";
            }
        else {
            // move lists empty, can't be a move ready or a bet ready
            // 
            // opponent must have folded or round ended
            echo "next_round_started\nOK";
            }
        return;
        }
    else {
        // move not ready

        global $gracePeriod;

        if( $gracePeriod && $seconds_left <= 0 ) {
            // don't time out during grace period
            // add some extra seconds here
            $seconds_left = 15;
            }
        
        if( $seconds_left <= 0 ) {
            // deadline for opponent move has passed
            // force them to leave game
            
            $otherPlayer = $player_2_id;
            
            if( $user_id == $player_2_id ) {
                $otherPlayer = $player_1_id;
                }
            
            cm_endOldGames( $otherPlayer );

            cm_queryDatabase( "COMMIT;" );
            
            echo "opponent_left\nOK";
            return;
            }
        // else their deadline still running
        else if( $inWaitOnSemaphore ) {
            global $waitTimeout;

            $waitLimit = ( $seconds_left + 1 ) * 1000;
        
            
            if( $waitTimeout > $waitLimit ) {
                $waitTimeout = $waitLimit;
                }
        
            
            semLock( $semaphore_key );

            cm_queryDatabase( "COMMIT;" );

            //cm_log( "Waiting on semaphore with timeout $waitTimeout, ".
            //        "$seconds_left seconds left" );
            
            $result = semWait( $semaphore_key, $waitTimeout );
            
            
            if( $result == -2 ) {
                echo "waiting\nOK";
                return;
                }
            else {
                // don't re-wait on the semaphore this time,
                // but re-perform the same tests to check if the move is ready 
                cm_waitMoveInternal( false );
                }
            }
        else {
            // we're waiting, but not supposed to block on semaphore
            // (maybe we already woke up from waiting on it)
            echo "waiting\nOK";
            
            cm_queryDatabase( "COMMIT;" );
            return;
            }
        }
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

        if( strstr( $requestData, "card_data" ) != FALSE ) {
            // don't log card data (not even encrypted card data)
            $requestData = "[Request contains card data, redacted]";
            }
        
        
        cm_log( "Transaction denied with the following get/post data:  ".
                "$requestData" );
        }
    
    
    
    echo "DENIED";
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );
    }





function cm_getUserID() {
    return cm_requestFilter( "user_id", "/\d+/", "" );
    }




function cm_getUserData( $user_id, $inColumnName ) {
    global $tableNamePrefix;
    
    $query = "SELECT $inColumnName FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );

    return mysql_result( $result, 0, 0 );
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
    
    $query = "SELECT sequence_number, account_key, last_action_time ".
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
    $last_action_time = $row[ "last_action_time" ];
        

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


        // log potentially unique user today
        $query = "SELECT CURRENT_DATE != DATE( '$last_action_time' );";
        $result = cm_queryDatabase( $query );

        if( mysql_result( $result, 0, 0 ) ) {
            // last visit of this user was NOT today
            cm_incrementStat( "unique_users" );
            }

        
        $query = "UPDATE $tableNamePrefix"."users SET ".
            "last_action_time = CURRENT_TIMESTAMP ".
            "WHERE user_id = $user_id;";
        
        cm_queryDatabase( $query );


        $transactionAlreadyVerified = 1;
        }

    
    return 1;
    }




function cm_getAdminLevel( $user_id ) {
    global $tableNamePrefix;
    
    $query = "SELECT admin_level ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );
    if( $numRows < 1 ) {
        return 0;
        }

    return mysql_result( $result, 0, 'admin_level' );
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
            "OR random_name LIKE '%$search%' ".
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
        "random_name, ".
        "total_deposits, total_withdrawals, ".
        "dollar_balance, last_action_time, ".
        "blocked, games_started, ".
        "(total_buy_in + total_won - total_lost) /  total_buy_in ".
        " AS profit_ratio, ".
        "elo_rating ".
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
    echo "<td>".orderLink( "random_name", "Alias" )."</td>\n";
    echo "<td>".orderLink( "total_deposits", "Deposits" )."</td>\n";
    echo "<td>".orderLink( "total_withdrawals", "Withdrawals" )."</td>\n";
    echo "<td>".orderLink( "dollar_balance", "Balance" )."</td>\n";
    echo "<td>".orderLink( "profit_ratio", "Profit Ratio" )."</td>\n";
    echo "<td>".orderLink( "elo_rating", "Elo" )."</td>\n";
    echo "<td>".orderLink( "games_started", "Games" )."</td>\n";
    
    echo "<td>".orderLink( "last_action_time", "Action" )."</td>\n";

    echo "</tr>\n";
    

    for( $i=0; $i<$numRows; $i++ ) {
        $user_id = mysql_result( $result, $i, "user_id" );
        $account_key = mysql_result( $result, $i, "account_key" );
        $email = mysql_result( $result, $i, "email" );
        $random_name = mysql_result( $result, $i, "random_name" );
        $total_deposits = mysql_result( $result, $i, "total_deposits" );
        $total_withdrawals = mysql_result( $result, $i, "total_withdrawals" );
        $dollar_balance = mysql_result( $result, $i, "dollar_balance" );
        $profit_ratio = mysql_result( $result, $i, "profit_ratio" );
        $elo_rating = mysql_result( $result, $i, "elo_rating" );
        $games_started = mysql_result( $result, $i, "games_started" );
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
        echo "<td>$random_name</td>\n";

        $depositsString = cm_formatBalanceForDisplay( $total_deposits );
        $withdrawalsString = cm_formatBalanceForDisplay( $total_withdrawals );
        $balanceString = cm_formatBalanceForDisplay( $dollar_balance );
        
        echo "<td>$depositsString</td>\n";
        echo "<td>$withdrawalsString</td>\n";
        echo "<td>$balanceString</td>\n";
        echo "<td>$profit_ratio</td>\n";
        echo "<td>$elo_rating</td>\n";
        echo "<td>$games_started</td>\n";
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

    global $tableNamePrefix;

    $query = "SELECT SUM( dollar_balance ) FROM $tableNamePrefix"."users;";
    $result = cm_queryDatabase( $query );

    $totalBalance = mysql_result( $result, 0, 0 );

    $query = "SELECT SUM( total_deposits ) FROM $tableNamePrefix"."users;";
    $result = cm_queryDatabase( $query );

    $totalDeposits = mysql_result( $result, 0, 0 );

    $query = "SELECT SUM( total_withdrawals ) FROM $tableNamePrefix"."users;";
    $result = cm_queryDatabase( $query );

    $totalWithdrawals = mysql_result( $result, 0, 0 );

    $query = "SELECT SUM( total_withdrawals ) FROM $tableNamePrefix"."users;";
    $result = cm_queryDatabase( $query );

    $totalWithdrawals = mysql_result( $result, 0, 0 );

    
    $query = "SELECT 2 * SUM( dollar_amount ) FROM $tableNamePrefix"."games ".
        "WHERE player_1_id != 0 AND player_2_id != 0;";
    $result = cm_queryDatabase( $query );

    $totalLiveBuyIns = mysql_result( $result, 0, 0 );


    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."games;";
    $result = cm_queryDatabase( $query );

    $liveGames = mysql_result( $result, 0, 0 );


    $liveGamesWord = "live games";
    if( $liveGames == 1 ) {
        $liveGamesWord = "live game";
        }
    

    $query = "SELECT house_dollar_balance, house_withdrawals, ".
        "check_account_dollar_balance ".
        "FROM  $tableNamePrefix"."server_globals;";
    $result = cm_queryDatabase( $query );

    $house_dollar_balance = mysql_result( $result, 0, "house_dollar_balance" );
    $house_withdrawals = mysql_result( $result, 0, "house_withdrawals" );
    $check_account_dollar_balance =
        mysql_result( $result, 0, "check_account_dollar_balance" );

    $leaked_money =
        $totalBalance
        + $totalLiveBuyIns
        - $totalDeposits
        + $totalWithdrawals
        + ( $house_dollar_balance + $house_withdrawals );
    

    $totalBalanceString = cm_formatBalanceForDisplay( $totalBalance );
    $totalLiveBuyInString = cm_formatBalanceForDisplay( $totalLiveBuyIns );
    $totalDepositsString = cm_formatBalanceForDisplay( $totalDeposits );
    $totalWithdrawalsString = cm_formatBalanceForDisplay( $totalWithdrawals );

    $houseBalanceString = cm_formatBalanceForDisplay( $house_dollar_balance );
    $houseWithdrawalsString = cm_formatBalanceForDisplay( $house_withdrawals );
    $checkAccountBalanceString
        = cm_formatBalanceForDisplay( $check_account_dollar_balance );

    $leakedMoneyString = cm_formatBalanceForDisplay( $leaked_money );


    global $eloManualRecompute;
    if( $eloManualRecompute ) {
        echo "[<a href=\"server.php?action=recompute_elo" .
            "\">Recompute Elo</a>]<br>";
        }
    
    
    echo "<table width='100%' border=0><tr>".
        "<td valign=top width=25%>[<a href=\"server.php?action=show_data" .
            "\">Main</a>] ".
            "[<a href=\"server.php?action=show_stats" .
            "\">Stats</a>]</td>".
        "<td valign=top align=center width=50%>".
        "$sizeString ($perUserString per user)<br>".
        "$connectionCount active MySQL $connectionWord<br>".
        "$liveGames $liveGamesWord<br>".
        "Users: $usersDay/d | $usersHour/h | $usersFiveMin/5m | ".
        "$usersMinute/m | $usersSecond/s<br>".
        "Player balance: $totalBalanceString | ".
        "Live buy-ins: $totalLiveBuyInString | ".
        "Deposits: $totalDepositsString | ".
        "Withdrawals: $totalWithdrawalsString<br>".
        "House balance: $houseBalanceString | ".
        "House withdrawals: $houseWithdrawalsString | ".
        "Leaked: $leakedMoneyString<br>".
        "Chexx Balance: $checkAccountBalanceString</td>".
        "<td valign=top align=right width=25%>[<a href=\"server.php?action=logout" .
            "\">Logout</a>]</td>".
        "</tr></table><br><br><br>";
    }



function cm_makeAccount() {
    cm_checkPassword( "make_account" );

    global $tableNamePrefix;

    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i", "" );

    if( $email == "" ) {
        echo "[<a href=\"server.php?action=show_data" .
        "\">Main</a>]<br><br><br>";
        
        echo "Bad email address";
        return;
        }
    
    $salt = 0;
    $account_key = cm_generateAccountKey( $email, $salt );

    $random_name = cm_generateRandomName();

    global $eloStartingRating;
    
    // user_id auto-assigned (auto-increment)
    $query = "INSERT INTO $tableNamePrefix". "users SET ".
        "account_key = '$account_key', ".
        "email = '$email', ".
        "random_name = '$random_name', ".
        "dollar_balance = 0, ".
        "num_deposits = 0, ".
        "total_deposits = 0, ".
        "num_withdrawals = 0, ".
        "total_withdrawals = 0, ".
        "total_won = 0, ".
        "total_lost = 0, ".
        "elo_rating = $eloStartingRating, ".
        "sequence_number = 0, ".
        "request_sequence_number = 0, ".
        "last_action_time = CURRENT_TIMESTAMP, ".
        "last_request_tag = '', ".
        "last_request_response = '', ".
        "admin_level = 0, ".
        "blocked = 0;";
            
    $result = cm_queryDatabase( $query );

    cm_incrementStat( "unique_users" );
    
    cm_showData();
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

        // put forms in a table
    echo "<center><table border=1 cellpadding=10><tr>\n";
        // form for force-creating a new account
?>
        <td>
        Create new Account:<br>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="make_account">
             Email:
    <INPUT TYPE="text" MAXLENGTH=40 SIZE=20 NAME="email"><br>
    <INPUT TYPE="Submit" VALUE="Add">
    </FORM>
        </td>
<?php


    echo "</tr></table></center>\n";

    
    
    echo "<hr>";

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
        "      DATE_SUB( CURRENT_TIMESTAMP, INTERVAL $day_limit DAY );";
        
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

    $query = "SELECT update_time, tournament_code_name, net_dollars ".
        "FROM $tableNamePrefix"."tournament_stats ".
        "WHERE user_id = 0 ".
        "ORDER BY update_time DESC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    echo "Total House Rake for Tournaments:";
    echo "<br><table border=1 cellpadding=10>\n";

    for( $i=0; $i<$numRows; $i++ ) {
        $update_time = mysql_result( $result, $i, "update_time" );
        $code_name = mysql_result( $result, $i, "tournament_code_name" );
        $net_dollars = mysql_result( $result, $i, "net_dollars" );

        $net_dollars = cm_formatBalanceForDisplay( $net_dollars );
        
        echo "<tr><td>$update_time</td>".
            "<td>$code_name</td><td>$net_dollars</td></tr>";
        }
    echo "</table><br><br>";
    

    
    $query = "SELECT * ".
        "FROM $tableNamePrefix"."server_stats ORDER BY stat_date;";
    $result = cm_queryDatabase( $query );

    $numFields = mysql_num_fields( $result );
    $numRows = mysql_numrows( $result );


    echo "<br><table border=1>\n";

    $bgColor = "#EEEEEE";
    $altBGColor = "#CCCCCC";


    
    
        
    for( $i=0; $i<$numRows; $i++ ) {

        // repeat header periodically so column titles are visible
        // when scrolling
        if( $i % 20 == 0 ) {
            echo "<tr>";
            for( $j=0; $j<$numFields; $j++ ) {
                $name = mysql_field_name( $result, $j );
                
                echo "<td><b>$name</b></td>";
                }
            echo "</tr>\n";
            
            echo "<tr>";
            }
        
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



function cm_formatDataTable( $tableName, $whereClause,
                             $fieldNames, $columnLabels, $dollarFieldNames,
                             $linkLabel = "",
                             $linkPrefix = "",
                             // these database fields are appended
                             // to link prefix
                             $linkFieldNames = array() ) {
    global $tableNamePrefix;
    
    $fieldListString = implode( $fieldNames, "," );

    $query = "SELECT $fieldListString ".
        "FROM $tableNamePrefix"."$tableName $whereClause;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    echo "<table border=1 cellpadding=5>";

    echo "<tr>";

    foreach( $columnLabels as $label ) {
        echo "<td><b>$label:</b></td>";
        }
    echo "</tr>";
    
        
    for( $i=0; $i<$numRows; $i++ ) {
        echo "<tr>";

        foreach( $fieldNames as $field ) {
            $fieldValue = mysql_result( $result, $i, "$field" );

            if( in_array( $field, $dollarFieldNames ) ) {
                $fieldValue = cm_formatBalanceForDisplay( $fieldValue );
                }
            
            echo "<td>$fieldValue</td>";
            }

        if( $linkPrefix != "" ) {
            $linkURL = $linkPrefix;
            foreach( $linkFieldNames as $field ) {
                $fieldValue = mysql_result( $result, $i, "$field" );
                $linkURL .= "&$field=$fieldValue";
                }
            echo "<td>[<a href='$linkURL'>$linkLabel</a>]</td>";
            }
        
        
        echo "</tr>";
        }
    echo "</table>";
    }





function cm_showDetail() {
    cm_checkPassword( "show_detail" );

    cm_showDetailInternal();
    }




// version that assumes admin password already checked
function cm_showDetailInternal() {
    
    $user_id = cm_getUserID();
    
    echo "[<a href=\"server.php?action=show_data" .
        "\">Main</a>]<br><br><br>";
     
    global $tableNamePrefix;

    $query = "SELECT account_key, email, random_name, ".
        "in_person_code, ".
        "admin_level, tax_info_on_file, ".
        "sequence_number, dollar_balance, total_buy_in, ".
        "total_won, total_lost, total_deposits, total_withdrawals, blocked ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows < 1 ) {
        cm_operationError( "User ID $user_id not found" );
        }
    $row = mysql_fetch_array( $result, MYSQL_ASSOC );

    $account_key = $row[ "account_key" ];
    $random_name = $row[ "random_name" ];
    $dollar_balance = cm_formatBalanceForDisplay( $row[ "dollar_balance" ] );
    $total_deposits = cm_formatBalanceForDisplay( $row[ "total_deposits" ] );
    $total_withdrawals =
        cm_formatBalanceForDisplay( $row[ "total_withdrawals" ] );
    $total_buy_in = cm_formatBalanceForDisplay( $row[ "total_buy_in" ] );
    $total_won = cm_formatBalanceForDisplay( $row[ "total_won" ] );
    $total_lost = cm_formatBalanceForDisplay( $row[ "total_lost" ] );

    $admin_level = $row[ "admin_level" ];
    $tax_info_on_file = $row[ "tax_info_on_file" ];
    $blocked = $row[ "blocked" ];
    $email = $row[ "email" ];
    $sequence_number = $row[ "sequence_number" ];

    $in_person_code = $row[ "in_person_code" ];

    echo "User ID: $user_id<br>\n";
    echo "Account Key: $account_key<br><br>\n";
    echo "Alias: $random_name<br><br>\n";

    echo "Balance: $dollar_balance<br><br>\n";


    
?>
        <table border=1 cellpadding=20><tr><td>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="in_person_deposit">
    <INPUT TYPE="hidden" NAME="user_id" VALUE="<?php echo $user_id;?>">
     $<INPUT TYPE="text" MAXLENGTH=40 SIZE=30 NAME="dollar_amount"
            VALUE="0.00">            
    <INPUT TYPE="Submit" VALUE="Deposit">
    </FORM>
                 </td><td>
<?php

?>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="in_person_withdrawal">
    <INPUT TYPE="hidden" NAME="user_id" VALUE="<?php echo $user_id;?>">
     $<INPUT TYPE="text" MAXLENGTH=40 SIZE=30 NAME="dollar_amount"
            VALUE="0.00">            
    <INPUT TYPE="Submit" VALUE="Withdraw">
    </FORM>
             </td></tr></table><br>
<?php

                 


    
    echo "Total Deposits: $total_deposits<br>\n";
    echo "Total Withdrawals: $total_withdrawals<br>\n";
    echo "Total Buy In: $total_buy_in<br>\n";
    echo "Total Won: $total_won<br>\n";
    echo "Total Lost: $total_lost<br><br>\n";

    $taxInfoChecked = "";
    if( $tax_info_on_file ) {
        $taxInfoChecked = "checked";
        }
    $blockedChecked = "";
    if( $blocked ) {
        $blockedChecked = "checked";
        }
?>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="update_user">
    <INPUT TYPE="hidden" NAME="user_id" VALUE="<?php echo $user_id;?>">
    Email: <INPUT TYPE="text" MAXLENGTH=40 SIZE=30 NAME="email"
            VALUE="<?php echo $email;?>"><br>            
    In Person Code: <INPUT TYPE="text"
                           MAXLENGTH=40 SIZE=30 NAME="in_person_code"
            VALUE="<?php echo $in_person_code;?>"><br>            
    Tax Info on File: <INPUT TYPE="checkbox" NAME="tax_info_on_file" VALUE=1
                 <?php echo $taxInfoChecked;?> ><br>
    Admin Level: <INPUT TYPE="text" MAXLENGTH=1 SIZE=2 NAME="admin_level"
            VALUE="<?php echo $admin_level;?>"><br>            
    Blocked: <INPUT TYPE="checkbox" NAME="blocked" VALUE=1
                 <?php echo $blockedChecked;?> ><br>
    <INPUT TYPE="Submit" VALUE="Update">
    </FORM>
<?php


    echo "<br><HR><br>Cards:<br>";
    cm_formatDataTable( "cards", "WHERE user_id = '$user_id'",
                        array( "fingerprint", "exp_date",
                               "last_used_time", "proof_on_file" ),
                        array( "Fingerprint", "MMYYYY", "Last Used",
                               "Proof on File" ),
                        array( ),
                        "Toggle Proof",
                        "server.php?action=toggle_card_proof&user_id=$user_id",
                        array( "fingerprint", "exp_date" ) );

                                                     
    echo "<br><HR><br>Deposits:<br>";
    cm_formatDataTable( "deposits", "WHERE user_id = '$user_id'",
                        array( "deposit_time", "processing_id",
                               "dollar_amount", "fee" ),
                        array( "Date", "ID", "Total Amount", "Fee" ),
                        array( "dollar_amount", "fee" ) );

    
    echo "<br><HR><br>Withdrawals:<br>";
    cm_formatDataTable( "withdrawals", "WHERE user_id = '$user_id'",
                        array( "withdrawal_time",
                               "dollar_amount", "fee",
                               "name",
                               "address1", "address2", "city", 
                               "us_state", "province", "country",
                               "postal_code", "reference_code" ),
                        array( "Date", "Check Amount", "Fee", "Name",
                               "Address", "Address", "City", "State",
                               "Province", "Country", "Post Code", "Ref" ),
                        array( "dollar_amount", "fee" ) );
    

    echo "<br><HR><br>Game Ledger:<br>";
    cm_formatDataTable( "game_ledger", "WHERE user_id = '$user_id'",
                        array( "entry_time", "game_id",
                               "dollar_delta" ),
                        array( "Date", "Game ID", "Delta" ),
                        array( "dollar_delta" ) );


    echo "<br><HR><br>Game Partners:<br>";
    $query = "SELECT entry_time, game_id ".
        "FROM $tableNamePrefix"."game_ledger WHERE user_id = $user_id AND ".
        "dollar_delta < 0 AND game_id != 0;";

    
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    echo "<table border=1 cellpadding=5>";

    
    echo "<tr><td><b>Date:</b></td>".
        "<td><b>Game ID:</b></td>".
        "<td><b>Partner:</b></td></tr>";
    
        
    for( $i=0; $i<$numRows; $i++ ) {
        $entry_time = mysql_result( $result, $i, "entry_time" );
        $game_id = mysql_result( $result, $i, "game_id" );

        // find partner matching buy-in
        
        $query = "SELECT user_id FROM $tableNamePrefix"."game_ledger ".
            "WHERE game_id = $game_id AND ".
            "user_id != $user_id AND $user_id != 0 AND dollar_delta < 0;";
        
        $resultPartner = cm_queryDatabase( $query );

        if( mysql_numrows( $resultPartner ) > 0 ) {
            $partner_id = mysql_result( $resultPartner, 0, 0 );

            echo "<tr><td>$entry_time</td>".
                "<td>$game_id</td>".
                "<td>$partner_id [<a href=\"server.php?action=show_detail" .
                "&user_id=$partner_id\">detail</a>]</td></tr>";
            }
        }
    echo "</table>";

    }


function cm_leaders( $order_column_name, $inIsDollars = false,
                     $inWhereClause = "", $inUnlimited = false ) {

    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );

    $limitClause = "LIMIT $leaderboardLimit";

    if( $inUnlimited ) {
        $limitClause = "";
        }
    
    $query = "SELECT random_name, ".
        "dollar_balance, ".
        "(dollar_balance + total_withdrawals) - total_deposits ".
        " AS profit, ".
        "( total_buy_in + total_won - total_lost + 20 / games_started ) / ".
        "( total_buy_in + 20 / games_started )".
        " AS profit_ratio, ".
        // watch for divide by 0, or almost zero, that makes this
        // blow up for people who haven't played much
        "( total_won + 20 / games_started ) / ".
        "( total_lost + 20 / games_started ) ".
        " AS win_loss, ".
        "elo_rating ".
        "FROM $tableNamePrefix"."users ".
        "$inWhereClause ".
        "ORDER BY $order_column_name DESC ".
        "$limitClause;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    
    echo "<center><table border=0 cellspacing=8>";
    
    
    for( $i=0; $i<$numRows; $i++ ) {
        $random_name = mysql_result( $result, $i, "random_name" );
        $value = mysql_result( $result, $i, $order_column_name );

        if( $inIsDollars ) {
            $value = cm_formatBalanceForDisplay( $value, true );
            }

        if( $i != 0 ) {
            echo "<tr><td colspan=3><hr></td></tr>";
            }

        $rowNum = $i + 1;
        
        echo "<tr><td align=right>$rowNum.</td><td>$random_name</td>".
            "<td align=right>$value</td></tr>";
        }
    echo "</table></center>";

    eval( $leaderFooter );
    }



function cm_leadersDollar() {
    cm_leaders( "dollar_balance", true );
    }



function cm_leadersProfit() {
    cm_leaders( "profit", true );
    }



function cm_leadersProfitRatio() {
    cm_leaders( "profit_ratio" );
    }


function cm_leadersWinLossRatio() {
    cm_leaders( "win_loss" );
    }


function cm_leadersElo() {
    global $eloProvisionalGames;
    cm_leaders( "elo_rating", false,
                "WHERE games_started > $eloProvisionalGames", true  );
    }


function cm_leadersEloAll() {
    global $eloProvisionalGames;
    cm_leaders( "elo_rating", false,
                "", true  );
    }




function cm_formatDuration( $seconds ) {
    $days = floor( $seconds / 86400 );
    $seconds -= $days * 86400;

    $hours = floor( $seconds / 3600 );
    $seconds -= $hours * 3600;
            
    $minutes = floor( $seconds / 60 );
    $seconds -= $minutes * 60;

    $timeString = '';
    if( $days > 0 ) {
        $timeString .= "$days day";
        if( $days > 1 ) {
            $timeString .= 's';
            }
        }
    if( $hours > 0 ) {
        if( $timeString != '' ) {
            $timeString .= ' and ';
            }
        $timeString .= "$hours hour";
        if( $hours > 1 ) {
            $timeString .= 's';
            }
        }
    if( ( $days == 0 || $hours == 0 ) && $minutes > 0 ) {
        if( $timeString != '' ) {
            $timeString .= ' and ';
            }
        $timeString .= "$minutes minute";
        if( $minutes > 1 ) {
            $timeString .= 's';
            }
        }
    if( ( $days == 0 && $hours == 0
          ||
          ( $days == 0 || $hours == 0 ) && $minutes == 0 ) 
        &&
        $seconds > 0 ) {

        if( $timeString != '' ) {
            $timeString .= ' and ';
            }
        $timeString .= "$seconds second";
        if( $seconds > 1 ) {
            $timeString .= 's';
            }
        
        }
    
    return $timeString;
    }



function cm_tournamentReport() {
    $code_name = cm_requestFilter( "code_name", "/[A-Z0-9_]+/i", "" );


    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );


    global $tournamentStartTime, $tournamentEndTime, $tournamentCodeName;
    
    $time = time();

    $startTime = strtotime( $tournamentStartTime );
    $endTime = strtotime( $tournamentEndTime );


    if( $code_name == $tournamentCodeName ) {
        
        if( $time > $endTime ) {
            echo "<center>This tournament is over.<br><br></center>";
            }
        else if( $time < $startTime ) {

            $timeString = cm_formatDuration( $startTime - $time );

            echo "<br><br><br><center>This tournament will ".
                "start in $timeString.<br><br></center>";
            return;
            }
        else {
            // live
            $timeString = cm_formatDuration( $endTime - $time );

            echo "<center>This tournament is live and will ".
                "end in $timeString.<br><br></center>";
            }
        }
    
    
    $query = "SELECT num_games_started, net_dollars, random_name ".
        "FROM $tableNamePrefix"."tournament_stats as stats ".
        "LEFT JOIN $tableNamePrefix"."users as users ".
        "     ON stats.user_id = users.user_id ".
        "WHERE tournament_code_name = '$code_name' ".
        // don't show house on leaderboard
        "AND stats.user_id != 0 ".
        "ORDER BY net_dollars DESC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    
    echo "<center><table border=0 cellspacing=10>";

    echo "<tr><td align=right></td>".
            "<td></td><td></td>".
            "<td valign=bottom align=right>Profit</td><td></td>".
            "<td valign=bottom>Games<br>Played</td></tr>";

    echo "<tr><td colspan=6><hr></td></tr>";

    
    for( $i=0; $i<$numRows; $i++ ) {
        $random_name = mysql_result( $result, $i, "random_name" );
        $num_games_started = mysql_result( $result, $i, "num_games_started" );
        $net_dollars = mysql_result( $result, $i, "net_dollars" );

        $net_dollars = cm_formatBalanceForDisplay( $net_dollars, true );

        if( $i != 0 ) {
            echo "<tr><td colspan=6><hr></td></tr>";
            }

        $rowNum = $i + 1;
        
        echo "<tr><td align=right>$rowNum.</td>".
            "<td>$random_name</td><td></td>".
            "<td align=right>$net_dollars</td><td></td>".
            "<td align=right>$num_games_started</td></tr>";
        }
    echo "</table></center>";

    eval( $leaderFooter );
    }



function cm_graphUserData( $inTitle, $inStatToGraph, $inWhereClause,
                           $inLimit ) {

    global $tableNamePrefix;
    
    
    $query = "SELECT stat_time, $inStatToGraph ".
        "FROM $tableNamePrefix"."user_stats ".
        "WHERE $inWhereClause ".
        "ORDER BY stat_time DESC LIMIT $inLimit;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    
    echo "<center><table border=0 cellspacing=8> <tr>";

    echo "<td align=right valign=bottom>";
    echo "Users:<br><br>$inTitle:<br>";
    echo "</td>";
    echo "<td >";
    echo "</td>";

    $curColor = "#000000";
    $altColor = "#333333";
    
    for( $i=0; $i<$numRows; $i++ ) {
        echo "<td bgcolor='$curColor' align=center valign=bottom>";

        $tempColor = $curColor;
        $curColor = $altColor;
        $altColor = $tempColor;
        
        $users_count = mysql_result( $result, $i, "$inStatToGraph" );

        if( $users_count > 5 ) {
            echo "$users_count<br>";
            }
        
        for( $u=0; $u<$users_count; $u++ ) {
            echo "+<br>";
            }
        echo "---<br>";
        echo "$i<br>";
        echo "</td>";
        }
    echo "</tr></table></center>";
    }





function cm_usersGraph() {

    
    
    global $tableNamePrefix, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );

    cm_graphUserData( "Hours Ago", "users_last_hour", "MINUTE(stat_time) <= 2",
                      24 );

    echo "<br><br><br>";
    cm_graphUserData( "Days Ago", "users_last_day",
                      "HOUR(stat_time) = HOUR(CURRENT_TIMESTAMP) AND ".
                      "MINUTE(stat_time) <= 2",
                      14 );


    eval( $leaderFooter );
    }





function cm_blockUserID() {
    cm_checkPassword( "block_user_id" );


    global $tableNamePrefix;
        
    $user_id = cm_getUserID();

    $blocked = cm_requestFilter( "blocked", "/[01]/" );

    // don't touch admin
    if( cm_updateUser_internal( $user_id, $blocked, -1, -1, -1, -1 ) ) {
        cm_showData();
        }
    }



function cm_updateUser() {
    cm_checkPassword( "update_user" );


    $user_id = cm_getUserID();

    $blocked = cm_requestFilter( "blocked", "/[1]/", "0" );
    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i" );
    $in_person_code = cm_requestFilter( "in_person_code", "/[A-Z0-9]+/i" );
    $admin_level = cm_requestFilter( "admin_level", "/[0-9]+/i" );

    $tax_info_on_file = cm_requestFilter( "tax_info_on_file", "/[1]/", "0" );
    
    if( cm_updateUser_internal( $user_id, $blocked, $email, $in_person_code,
                                $admin_level,
                                $tax_info_on_file) ) {
        cm_showDetail();
        }
    }



// set any to -1 to leave unchanged
// returns 1 on success
function cm_updateUser_internal( $user_id, $blocked, $email, $in_person_code,
                                 $admin_level,
                                 $tax_info_on_file ) {
    
    global $tableNamePrefix;
        
    
    global $remoteIP;
    

    
    $query = "SELECT user_id, blocked, email, in_person_code, ".
        "admin_level, tax_info_on_file ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    if( $numRows == 1 ) {
        $old_blocked = mysql_result( $result, 0, "blocked" );
        $old_email = mysql_result( $result, 0, "email" );
        $old_in_person_code = mysql_result( $result, 0, "in_person_code" );
        $old_admin_level = mysql_result( $result, 0, "admin_level" );
        $old_tax_info_on_file = mysql_result( $result, 0, "tax_info_on_file" );

        if( $blocked == -1 ) {
            $blocked = $old_blocked;
            }
        if( $email == -1 ) {
            $email = $old_email;
            }
        if( $in_person_code == -1 ) {
            $in_person_code = $old_in_person_code;
            }
        if( $admin_level == -1 ) {
            $admin_level = $old_admin_level;
            }
        if( $tax_info_on_file == -1 ) {
            $tax_info_on_file = $old_tax_info_on_file;
            }
        
        
        $query = "UPDATE $tableNamePrefix"."users SET " .
            "blocked = '$blocked', email = '$email', ".
            "in_person_code = '$in_person_code', ".
            "admin_level = '$admin_level', ".
            "tax_info_on_file = '$tax_info_on_file' " .
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





function cm_toggleCardProof() {
    
    global $tableNamePrefix;

    cm_checkPassword( "toggle_card_proof" );


    $user_id = cm_getUserID();

    $fingerprint = cm_requestFilter( "fingerprint", "/[[A-Z0-9]+/i", "" );
    $exp_date = cm_requestFilter( "exp_date", "/\d\d\d\d\d\d/", "" );

    $query = "UPDATE $tableNamePrefix"."cards SET " .
        "proof_on_file = (proof_on_file + 1) % 2 ".
        "WHERE user_id = '$user_id' AND fingerprint = '$fingerprint' ".
        "AND exp_date = '$exp_date';";

    cm_log( "Query = $query" );
    
    $result = cm_queryDatabase( $query );
    

    cm_showDetail();
    }







function cm_inPersonDeposit() {
    cm_checkPassword( "in_person_deposit" );


    $user_id = cm_getUserID();

    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/^[0-9]*([.][0-9][0-9])?/i", "0.00" );

    if( $dollar_amount == 0 ) {
        echo "ERROR:  Dollar amount invalid<br><br>";
        cm_showDetailInternal();
        return;
        }
    
    global $tableNamePrefix;

    $query = "UPDATE $tableNamePrefix". "users SET ".
            "dollar_balance = dollar_balance + '$dollar_amount', ".
            "num_deposits = num_deposits + 1, ".
            "total_deposits = total_deposits + '$dollar_amount' ".
            "WHERE user_id = $user_id;";
    cm_queryDatabase( $query );
    
    cm_incrementStat( "deposit_count" );
    cm_incrementStat( "total_deposits", $dollar_amount );
    cm_updateMaxStat( "max_deposit", $dollar_amount );

    
    // log it

    $query = "INSERT INTO $tableNamePrefix"."deposits ".
        "SET user_id = '$user_id', deposit_time = CURRENT_TIMESTAMP, ".
        "dollar_amount = '$dollar_amount', ".
        "fee = '0', processing_id = 'in_person'; ";
    
    $result = cm_queryDatabase( $query );

    cm_showDetailInternal();
    }




function cm_inPersonWithdrawal() {
    cm_checkPassword( "in_person_withdrawal" );


    $user_id = cm_getUserID();

    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/^[0-9]*([.][0-9][0-9])?/i", "0.00" );

    if( $dollar_amount == 0 ) {
        echo "ERROR:  Dollar amount invalid<br><br>";
        cm_showDetailInternal();
        return;
        }

    global $tableNamePrefix;


    $dollar_balance = cm_getUserData( $user_id, "dollar_balance" );

    if( $dollar_amount > $dollar_balance ) {
        echo "ERROR:  Dollar amount greater than balance<br><br>";
        cm_showDetailInternal();
        return;
        }

    $query = "UPDATE $tableNamePrefix". "users SET ".
            "dollar_balance = dollar_balance - '$dollar_amount', ".
            "num_withdrawals = num_withdrawals + 1, ".
            "total_withdrawals = total_withdrawals + '$dollar_amount' ".
            "WHERE user_id = $user_id;";
    cm_queryDatabase( $query );


    cm_incrementStat( "withdrawal_count" );
    cm_incrementStat( "total_withdrawals", $dollar_amount );
    cm_updateMaxStat( "max_withdrawal", $dollar_amount );

    
    // log it

    $query = "INSERT INTO $tableNamePrefix"."withdrawals ".
        "SET user_id = '$user_id', withdrawal_time = CURRENT_TIMESTAMP, ".
        "dollar_amount = '$dollar_amount', ".
        "fee = '0', ".
        "reference_code = 'in_person' ; ";

    $result = cm_queryDatabase( $query );

    cm_showDetailInternal();
    }



// computes expected score, aE, for A based on Elo ratings of two players
// Expected score for B is 1 - aE
function cm_computeExpectedScore( $inRatingA, $inRatingB ) {
    return 1 / ( 1 + pow( 10, ( $inRatingB - $inRatingA ) / 400 ) );
    }


// N is number of games played so far
// returns 2-element array with new ratings, $ratingA first
function cm_computeNewElo( $ratingA, $ratingB, $aN, $bN,
                           $payoutA, $payoutB ) {

    global $eloProvisionalGames, $eloKProvisional, $eloKMain;
    
    $aE = cm_computeExpectedScore( $ratingA, $ratingB );
    $bE = 1 - $aE;

    // score is fraction of payout between 0 and 1
    $aS = $payoutA / ( $payoutA + $payoutB );
    $bS = $payoutB / ( $payoutA + $payoutB );
            
            
    $aK = $eloKProvisional;
    $aProvisional = true;
    if( $aN > $eloProvisionalGames ) {
        $aK = $eloKMain;
        $aProvisional = false;
        }

    $bK = $eloKProvisional;
    $bProvisional = true;
    if( $bN > $eloProvisionalGames ) {
        $bK = $eloKMain;
        $bProvisional = false;
        }

    // provisional doesn't affect non-provisional
    if( $bProvisional && ! $aProvisional ) {
        $aK = 0;
        }
    if( $aProvisional && ! $bProvisional ) {
        $bK = 0;
        }
            
            
    $ratingA += $aK * ( $aS - $aE );
    $ratingB += $bK * ( $bS - $bE );

    $result = array();

    $result[ 0 ] = $ratingA;
    $result[ 1 ] = $ratingB;

    return $result;
    }



function cm_recomputeElo() {
    cm_checkPassword( "recompute_elo" );

    echo "[<a href=\"server.php?action=show_data" .
         "\">Main</a>]<br><br><br>";

    global $tableNamePrefix;

    global $eloStartingRating, $eloProvisionalGames,
        $eloKProvisional, $eloKMain;

    
    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );
    
    $query = "SELECT user_id from $tableNamePrefix"."users FOR UPDATE;";
    
    $result = cm_queryDatabase( $query );

    $eloRatings = array();

    $gamesPlayed = array();


    $numRows = mysql_numrows( $result );
            
    for( $i=0; $i<$numRows; $i++ ) {
        $user_id = mysql_result( $result, $i, "user_id" );

        $eloRatings[ $user_id ] = $eloStartingRating;
        $gamesPlayed[ $user_id ] = 0;
        }

    $query = "SELECT game_id FROM $tableNamePrefix"."game_ledger ".
        "WHERE game_id != 0 GROUP BY game_id;";
    
    $result = cm_queryDatabase( $query );
    $numRows = mysql_numrows( $result );
            
    for( $i=0; $i<$numRows; $i++ ) {
        $game_id = mysql_result( $result, $i, "game_id" );

        $query = "SELECT user_id, dollar_delta ".
            "FROM $tableNamePrefix"."game_ledger ".
            "WHERE dollar_delta >= 0 ".
            "AND game_id = $game_id AND user_id != 0;";
        

        $result2 = cm_queryDatabase( $query );
        $numRows2 = mysql_numrows( $result2 );

        if( $numRows2 == 2 ) {
            // count the result
            $userA = mysql_result( $result2, 0, "user_id" );
            $payoutA = mysql_result( $result2, 0, "dollar_delta" );

            $userB = mysql_result( $result2, 1, "user_id" );
            $payoutB = mysql_result( $result2, 1, "dollar_delta" );

            $ratingA = $eloRatings[ $userA ];
            $ratingB = $eloRatings[ $userB ];

            $aN = $gamesPlayed[ $userA ];
            $bN = $gamesPlayed[ $userB ];
            

            $resultArray = cm_computeNewElo( $ratingA, $ratingB, $aN, $bN,
                                             $payoutA, $payoutB );

            $ratingA = $resultArray[0];
            $ratingB = $resultArray[1];
            
            echo "$userA elo changing to $ratingA<br>";
            echo "$userB elo changing to $ratingB<br>";
            
            
            
            $eloRatings[ $userA ] = $ratingA;
            $eloRatings[ $userB ] = $ratingB;
            
            $gamesPlayed[ $userA ] ++;
            $gamesPlayed[ $userB ] ++; 
            }
        }

    foreach( $eloRatings as $user_id => $elo ) {
        echo "Final update $user_id to elo $elo<br>";
        $query = "UPDATE $tableNamePrefix"."users ".
            "SET elo_rating = $elo WHERE user_id = $user_id;";

        cm_queryDatabase( $query );
        }

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1;" );

    echo "Done<br>";
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
            $sequence_number = cm_requestFilter( "sequence_number", "/\d+/" );
            
            $message = "[user_id = $user_id, #$sequence_number] " . $message;
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
function cm_informAdmin( $inMessage,
                         $inSubject = "Cordial Minuet server issue" ) {
    
    global $emailAdminOnFatalError, $callAdminInEmergency;


    if( $emailAdminOnFatalError ) {
        global $adminEmail;
        
        cm_mail( $adminEmail, $inSubject,
                 $inMessage );
        }
    if( $callAdminInEmergency ) {
        cm_callAdmin( $inMessage );
        }
    }

    


?>

