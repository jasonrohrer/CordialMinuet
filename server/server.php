<?php



// for testing
//sleep( 5 );


// server will tell clients to upgrade to this version
global $cm_version;
$cm_version = "27";


// leave an older version here IF older clients can also connect safely
// (newer clients must use this old version number in their account hmac
//  too).
// NOTE that if old clients are incompatible, both numbers should be updated.
global $cm_accountHmacVersion;
$cm_accountHmacVersion = "25";







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



// replace hard-coded global tournament settings with
// an auto-scheduled tournament if one is running
cm_checkForAutoTournament();



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
else if( $action == "enter_tournament" ) {
    cm_enterTournament();
    }
else if( $action == "drop_amulet" ) {
    cm_dropAmulet();
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
else if( $action == "make_batch_accounts" ) {
    cm_makeBatchAccounts();
    }
else if( $action == "make_coupons" ) {
    cm_makeCoupons();
    }
else if( $action == "redeem_coupon" ) {
    cm_redeemCoupon();
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
else if( $action == "award_cabal_points" ) {
    cm_awardCabalPoints();
    }
else if( $action == "logout" ) {
    cm_logout();
    }
else if( $action == "leaders_games" ) {
    cm_leadersGames();
    }
else if( $action == "leaders_dollar" ) {
    cm_leadersDollar();
    }
else if( $action == "leaders_profit" ) {
    cm_leadersProfit();
    }
else if( $action == "leaders_day_profit" ) {
    cm_leadersDayProfit();
    }
else if( $action == "leaders_week_profit" ) {
    cm_leadersWeekProfit();
    }
else if( $action == "leaders_month_profit" ) {
    cm_leadersMonthProfit();
    }
else if( $action == "leaders_year_profit" ) {
    cm_leadersYearProfit();
    }
else if( $action == "leaders_profit_ratio" ) {
    cm_leadersProfitRatio();
    }
else if( $action == "leaders_day_profit_ratio" ) {
    cm_leadersDayProfitRatio();
    }
else if( $action == "leaders_week_profit_ratio" ) {
    cm_leadersWeekProfitRatio();
    }
else if( $action == "leaders_month_profit_ratio" ) {
    cm_leadersMonthProfitRatio();
    }
else if( $action == "leaders_year_profit_ratio" ) {
    cm_leadersYearProfitRatio();
    }
else if( $action == "leaders_day_games" ) {
    cm_leadersDayGames();
    }
else if( $action == "leaders_week_games" ) {
    cm_leadersWeekGames();
    }
else if( $action == "leaders_month_games" ) {
    cm_leadersMonthGames();
    }
else if( $action == "leaders_year_games" ) {
    cm_leadersYearGames();
    }
else if( $action == "leaders_win_loss_ratio" ) {
    cm_leadersWinLossRatio();
    }
else if( $action == "leaders_elo" ) {
    cm_leadersElo();
    }
else if( $action == "leaders_elo_provisional" ) {
    cm_leadersEloProvisional();
    }
else if( $action == "tournament_report" ) {
    cm_tournamentReport();
    }
else if( $action == "list_past_tournaments" ) {
    cm_listPastTournaments();
    }
else if( $action == "list_future_tournaments" ) {
    cm_listFutureTournaments();
    }
else if( $action == "tournament_prizes" ) {
    cm_tournamentPrizes();
    }
else if( $action == "vs_one_report" ) {
    cm_vsOneReport();
    }
else if( $action == "amulet_report" ) {
    cm_amuletReport();
    }
else if( $action == "amulet_summary" ) {
    cm_amuletSummary();
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
        cm_doesTableExist( $tableNamePrefix."leaderboard_cache" ) &&
        cm_doesTableExist( $tableNamePrefix."cards" ) &&
        cm_doesTableExist( $tableNamePrefix."deposits" ) &&
        cm_doesTableExist( $tableNamePrefix."withdrawals" ) &&
        cm_doesTableExist( $tableNamePrefix."game_ledger" ) &&
        cm_doesTableExist( $tableNamePrefix."games" ) &&
        cm_doesTableExist( $tableNamePrefix."tournament_stats" ) &&
        cm_doesTableExist( $tableNamePrefix."tournament_pairings" ) &&
        cm_doesTableExist( $tableNamePrefix."amulets" ) &&
        cm_doesTableExist( $tableNamePrefix."amulet_points" ) &&
        cm_doesTableExist( $tableNamePrefix."vs_one_scores" ) &&
        cm_doesTableExist( $tableNamePrefix."vs_one_cache" ) &&
        cm_doesTableExist( $tableNamePrefix."server_stats" ) &&
        cm_doesTableExist( $tableNamePrefix."user_stats" ) &&
        cm_doesTableExist( $tableNamePrefix."coupons" );
    
        
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

    $foundUnique = false;
    $tryCount = 0;

    $name = "";

    $numberOfWords = 2;
    
    
    while( ! $foundUnique && $numberOfWords < 5 ) {
        
    
        $query =
            "SELECT GROUP_CONCAT( temp.noun SEPARATOR ' ' ) AS random_name ".
            "FROM ( SELECT noun FROM $tableNamePrefix"."random_nouns ".
            "       ORDER BY RAND() LIMIT $numberOfWords ) AS temp;";

        $result = cm_queryDatabase( $query );

        $name = mysql_result( $result, 0, 0 );

        
        $query = "SELECT COUNT(*) from $tableNamePrefix"."users ".
            "WHERE random_name = '$name';";

        $result = cm_queryDatabase( $query );

        if( 0 == mysql_result( $result, 0, 0 ) ) {
            $foundUnique = true;
            }
        else {
            $tryCount ++;

            if( $tryCount >= 10 ) {
                // can't find unique, try more words
                $numberOfWords ++;
                
                $tryCount = 0;
                }
            }        
        }

    // if we passed the 4-word limit without finding a unique name,
    // give up and use the last 4-word name that we tried.
    
    
    return $name;
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
            "random_name VARCHAR(60) NOT NULL,".
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
            "last_vs_one_coins INT NOT NULL,".
            "sequence_number INT UNSIGNED NOT NULL," .
            "request_sequence_number INT UNSIGNED NOT NULL," .
            "last_action_time DATETIME NOT NULL," .
            "INDEX( last_action_time ),".
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



    $tableName = $tableNamePrefix . "leaderboard_cache";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "column_name VARCHAR(25) NOT NULL,".
            "where_clause VARCHAR(255) NOT NULL,".
            "skip_number INT UNSIGNED NOT NULL,".
            "PRIMARY KEY( column_name, where_clause, skip_number ),".
            "update_time DATETIME NOT NULL," .
            "html_text MEDIUMTEXT NOT NULL ) ENGINE = INNODB;";

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
            "game_type TINYINT NOT NULL,".
            "last_action_time DATETIME NOT NULL,".
            "player_1_id INT UNSIGNED NOT NULL," .
            "INDEX( player_1_id )," .
            "player_2_id INT UNSIGNED NOT NULL," .
            "INDEX( player_2_id )," .
            "dollar_amount DECIMAL(11, 2) NOT NULL,".
            "INDEX( dollar_amount )," .
            "amulet_game TINYINT UNSIGNED NOT NULL,".
            "INDEX( amulet_game ),".
            // wait for a random amount of time before
            // settling on an opponent for an amulet game
            "amulet_game_wait_time DATETIME NOT NULL,".
            "INDEX( amulet_game_wait_time ),".
            "started TINYINT UNSIGNED NOT NULL,".
            "round_number INT UNSIGNED NOT NULL," .
            // 36-cell square, numbers from 1 to 36, separated by #
            // character
            "game_square CHAR(125) NOT NULL,".
            // flag set when each player requests the very first game
            // state.  This indicates that both are aware that the game
            // has started, so leave penalties can be assessed
            "player_1_got_start TINYINT NOT NULL,".
            "player_2_got_start TINYINT NOT NULL,".
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
            // coins in both pots that are common knowledge to both players
            // (coins that have been matched by opponent to move on
            //  to next turn)
            "settled_pot_coins TINYINT UNSIGNED NOT NULL, ".
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
            "INDEX( entry_time )," .
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
            "entry_fee DECIMAL(13, 4) NOT NULL,".
            "prize DECIMAL(13, 4) NOT NULL,".
            "update_time DATETIME NOT NULL," .
            "num_games_finished INT NOT NULL,".
            "INDEX( num_games_finished ),".
            "net_dollars DECIMAL(13, 4) NOT NULL,".
            "INDEX( net_dollars ) ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    // net dollars user_id profited whie playing against opponent
    // a given tournament.
    // Can be negative if player lost money while playing opponent.
    $tableName = $tableNamePrefix . "tournament_pairings";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "user_id INT NOT NULL," .
            "user_id_opponent INT NOT NULL," .
            "tournament_code_name VARCHAR(255) NOT NULL," .
            "PRIMARY KEY( user_id, user_id_opponent, tournament_code_name )," .
            "update_time DATETIME NOT NULL," .
            "num_games_started INT NOT NULL,".
            "net_dollars DECIMAL(13, 4) NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }





    $tableName = $tableNamePrefix . "amulets";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "amulet_id INT NOT NULL PRIMARY KEY," .
            "holding_user_id INT NOT NULL," .
            "INDEX( holding_user_id ), ".
            // if holding_user_id is 0, amulet has been dropped into player
            // pool
            // We will hand it to the Nth last player standing in a match,
            // where N is the number of live games at the moment of drop.
            // users_to_skip_on_drop is how many more to skip before
            // we give the amulet to that last player standing.
            "users_to_skip_on_drop INT NOT NULL, ".
            "acquire_time DATETIME NOT NULL," .
            "last_amulet_game_time DATETIME NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }


    // does not take into account points lost due to time held by
    // currently-holding user
    
    $tableName = $tableNamePrefix . "amulet_points";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "amulet_id INT NOT NULL," .
            "user_id INT NOT NULL," .
            "PRIMARY KEY( amulet_id, user_id ), ".
            "points INT NOT NULL, ".
            "last_score_time DATETIME NOT NULL ) ENGINE = INNODB;";

        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }



    $tableName = $tableNamePrefix . "vs_one_scores";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "user_id INT NOT NULL," .
            "vs_one_code_name VARCHAR(255) NOT NULL," .
            "PRIMARY KEY( user_id, vs_one_code_name )," .
            // can be negative
            "coins_won INT NOT NULL,".
            "INDEX( coins_won ) ) ENGINE = INNODB;";
        $result = cm_queryDatabase( $query );

        echo "<B>$tableName</B> table created<BR>";
        }
    else {
        echo "<B>$tableName</B> table already exists<BR>";
        }

    $tableName = $tableNamePrefix . "vs_one_cache";
    if( ! cm_doesTableExist( $tableName ) ) {

        $query =
            "CREATE TABLE $tableName(" .
            "vs_one_code_name VARCHAR(255) NOT NULL PRIMARY KEY,".
            "update_time DATETIME NOT NULL," .
            "html_text MEDIUMTEXT NOT NULL ) ENGINE = INNODB;";

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
            "exp_game_count INT UNSIGNED NOT NULL DEFAULT 0,".
            "total_buy_in DECIMAL(13, 2) NOT NULL DEFAULT 0, ".
            
            "max_game_stakes DECIMAL(13, 2) NOT NULL DEFAULT 0.00, ".

            "total_house_rake DECIMAL(13, 4) NOT NULL DEFAULT 0.0000, ".
            "max_house_rake DECIMAL(13, 4) NOT NULL DEFAULT 0.0000, ".
            
            "round_count INT UNSIGNED NOT NULL DEFAULT 0, ".
            "fold_count INT UNSIGNED NOT NULL DEFAULT 0, ".

            "one_ante_fold_count INT UNSIGNED NOT NULL DEFAULT 0, ".

            "reveal_count INT UNSIGNED NOT NULL DEFAULT 0 ".

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


    
    // coupons for free money
    $tableName = $tableNamePrefix . "coupons";

    if( ! cm_doesTableExist( $tableName ) ) {

        // doesn't need to be innodb, because rows never change
        $query =
            "CREATE TABLE $tableName(" .
            "coupon_code VARCHAR(255) NOT NULL PRIMARY KEY," .
            "coupon_tag VARCHAR(255) NOT NULL,".
            "creation_time DATETIME NOT NULL," .
            "expire_time DATETIME NOT NULL,".
            "dollar_amount DECIMAL(13, 2) NOT NULL, ".
            // 0 if not redeemed yet
            "redeemed_by_user_id INT UNSIGNED NOT NULL,".
            "redeemed_time DATETIME NOT NULL ) ENGINE = INNODB;";
        

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

    $nextSuperSkip = $skip + 10*$entriesPerPage;
    $nextMegaSkip = $skip + 100*$entriesPerPage;
    
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
    if( $nextSuperSkip < $totalEntries ) {
        echo "[<a href=\"server.php?action=show_log" .
            "&skip=$nextSuperSkip\">".
            "Skip 10 pages</a>]";
        }
    if( $nextMegaSkip < $totalEntries ) {
        echo "[<a href=\"server.php?action=show_log" .
            "&skip=$nextMegaSkip\">".
            "Skip 100 pages</a>]";
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
    

    // flush logs after 30 days
    $logFlushDays = 30;
    
    
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


        
        // flush stale games
        
        $query = "SELECT player_1_id, player_2_id ".
            "FROM $tableNamePrefix"."games ".
            "WHERE last_action_time < ".
            "SUBTIME( CURRENT_TIMESTAMP, '$staleGameTimeLimit' ) FOR UPDATE;";

        $result = cm_queryDatabase( $query );

        $numRows = mysql_numrows( $result );
    
        for( $i=0; $i<$numRows; $i++ ) {
            $player_1_id = mysql_result( $result, $i, "player_1_id" );
            $player_2_id = mysql_result( $result, $i, "player_2_id" );

            // force tie (pots returned to both players)
            // if end of round not reached and both players still in the game
            // (both players failed to update last_action_time in time,
            //  which means a connection error for both)
            
            if( $player_1_id != 0 ) {
                cm_endOldGames( $player_1_id, true );
                }
            if( $player_2_id != 0 ) {
                cm_endOldGames( $player_2_id, true );
                }
            }
        
        cm_queryDatabase( "COMMIT;" );



        
        // look for amulet holders that have been inactive too long
        
        // how many live games are running?
        $liveNonAmuletGameCount =
            cm_countQuery(
                "games",
                "player_1_id != 0 AND player_2_id != 0 ".
                "AND amulet_game = 0" );
        
        $users_to_skip = $liveNonAmuletGameCount;
        if( $users_to_skip > 10 ) {
            $users_to_skip = 10;
            }
        
        
        global $amuletInactivityLimit;
        
        $query = "SELECT holding_user_id ".
            "FROM $tableNamePrefix"."amulets ".
            "WHERE last_amulet_game_time < ".
            "SUBTIME( CURRENT_TIMESTAMP, '$amuletInactivityLimit' ) ".
            "AND holding_user_id != 0 ".
            "FOR UPDATE;";

        $result = cm_queryDatabase( $query );

        $numRows = mysql_numrows( $result );
    
        for( $i=0; $i<$numRows; $i++ ) {
            $holding_user_id =
                mysql_result( $result, $i, "holding_user_id" );

            cm_subtractPointsForAmuletHoldTime( $holding_user_id );
            
            $query = "UPDATE $tableNamePrefix"."amulets " .
                "SET holding_user_id = 0, ".
                "users_to_skip_on_drop = $users_to_skip ".
                "WHERE holding_user_id = $holding_user_id;";
            
            cm_queryDatabase( $query );
            }

        cm_queryDatabase( "COMMIT;" );



        // look for any held amulets that are now over (contest ended)
        global $amulets;
        
        $query = "SELECT holding_user_id, amulet_id ".
            "FROM $tableNamePrefix"."amulets ".
            "FOR UPDATE;";

        $result = cm_queryDatabase( $query );

        $numRows = mysql_numrows( $result );
    
        for( $i=0; $i<$numRows; $i++ ) {
            $holding_user_id =
                mysql_result( $result, $i, "holding_user_id" );
            $amulet_id =
                mysql_result( $result, $i, "amulet_id" );

            if( time() >= strtotime( $amulets[$amulet_id][0] ) ) {

                if( $holding_user_id != 0 ) {    
                    cm_subtractPointsForAmuletHoldTime( $holding_user_id );
                    }
                
                $query = "DELETE FROM $tableNamePrefix"."amulets " .
                    "WHERE amulet_id = $amulet_id;";
            
                cm_queryDatabase( $query );
                }
            }

        cm_queryDatabase( "COMMIT;" );


        

        
        
        // payout tournament prizes if tournament just ended

        global $tournamentLive, $tournamentCodeName, $tournamentEndTime;

        $time = time();

        $endTime = strtotime( $tournamentEndTime );

        if( $tournamentLive && $time >= $endTime ) {
            
            $query = "SELECT COUNT(*), COALESCE( SUM(prize), 0 ) ".
                "FROM $tableNamePrefix"."tournament_stats ".
                "WHERE tournament_code_name = '$tournamentCodeName' ".
                "FOR UPDATE;";
        
            $result = cm_queryDatabase( $query );
            
            $numPlayers = mysql_result( $result, 0, 0 );
            $prizesPaid = mysql_result( $result, 0, 1 );

            if( $prizesPaid == 0 ) {

                $query = "SELECT user_id, net_dollars ".
                    "FROM $tableNamePrefix"."tournament_stats ".
                    "WHERE tournament_code_name = '$tournamentCodeName' ".
                    // ignore any house entries
                    "AND user_id != 0 ".
                    "ORDER BY net_dollars DESC FOR UPDATE;";
                $result = cm_queryDatabase( $query );
                
                $numRows = mysql_numrows( $result );

                $scores = array();

                for( $i=0; $i<$numRows; $i++ ) {
                    $net_dollars = mysql_result( $result, $i, "net_dollars" );
                    
                    $scores[$i] = $net_dollars;
                    }                    
                    
                $prizes = cm_tournamentGetPrizes( $numPlayers, $scores );
                
                $prizeTotal = array_sum( $prizes );
                
                if( $prizeTotal > 0 ) {

                    for( $i=0; $i<$numRows; $i++ ) {
                        $user_id = mysql_result( $result, $i, "user_id" );

                        $prize = $prizes[ $i ];

                        $query = "UPDATE $tableNamePrefix"."users ".
                            "SET dollar_balance = dollar_balance + ".
                            "$prize ".
                            "WHERE user_id = $user_id;";
                        cm_queryDatabase( $query );

                        $query = "UPDATE $tableNamePrefix"."tournament_stats ".
                            "SET prize = $prize ".
                            "WHERE tournament_code_name = ".
                            "    '$tournamentCodeName' ".
                            "AND user_id = $user_id;";
                        cm_queryDatabase( $query );

                        }

                    // don't trust $prizeTotal because of precision issues

                    // instead, sum decimal prizes as they appear in database

                    $query = "SELECT SUM(prize) ".
                        "FROM $tableNamePrefix"."tournament_stats ".
                        "WHERE tournament_code_name = '$tournamentCodeName';";
                    $result = cm_queryDatabase( $query );

                    $truePrizeTotal = mysql_result( $result, 0, 0 );
                    
                    $query = "UPDATE $tableNamePrefix"."server_globals ".
                        "SET house_dollar_balance = ".
                        "  house_dollar_balance - $truePrizeTotal;";
                    cm_queryDatabase( $query );
                    }
                }
            
            cm_queryDatabase( "COMMIT;" );
            }
        


        
        $query = "DELETE ".
            "FROM $tableNamePrefix"."log ".
            "WHERE entry_time < ".
            "DATE_SUB( CURRENT_TIMESTAMP, INTERVAL $logFlushDays DAY );";

        $result = cm_queryDatabase( $query );
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




// returns -1 on failure
function cm_getPlayerBalance( $user_id ) {

    global $tableNamePrefix;
        
    $query = "SELECT dollar_balance ".
        "FROM $tableNamePrefix"."users ".
        "WHERE user_id = '$user_id';";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows == 0 ) {
        return -1;
        }

    return mysql_result( $result, 0, "dollar_balance" );
    }




function cm_getBalance() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    $user_id = cm_getUserID();

    cm_endOldGames( $user_id );
    
    
    $dollar_balance = cm_getPlayerBalance( $user_id );

    if( $dollar_balance == -1 ) {
        cm_transactionDeny();
        return;
        }
    
    
    $amulet_id = cm_getHeldAmulet( $user_id );
    
    
    $amulet_tga_url = "#";
    $amulet_points = 0;
    global $amuletHoldPenaltyPerMinute;

    $amulet_seconds_held = 0;
    
    if( $amulet_id != 0 ) {
        $amulet_tga_url = cm_getAmuletTGAURL( $amulet_id );
        
        $amulet_points = cm_getAmuletPoints( $amulet_id, $user_id );
        

        global $tableNamePrefix;
        
        $query = "SELECT ".
            "TIMESTAMPDIFF( SECOND, acquire_time, CURRENT_TIMESTAMP ) ".
            "AS amulet_seconds_held ".
            "FROM $tableNamePrefix"."amulets ".
            "WHERE holding_user_id = $user_id;";
        
        $result = cm_queryDatabase( $query );

        $numRows = mysql_numrows( $result );
        if( $numRows == 1 ) {
            $amulet_seconds_held =
                mysql_result( $result, 0, "amulet_seconds_held" );
            }
        }
    
    
    echo "$dollar_balance\n";
    echo "$amulet_id\n";
    echo "$amulet_tga_url\n";
    echo "$amulet_points\n";
    echo "$amulet_seconds_held\n";
    echo "$amuletHoldPenaltyPerMinute\n";
    echo "OK";
    }



function cm_recomputeBalanceFromHistory( $user_id ) {
    global $tableNamePrefix;

    $p = $tableNamePrefix;
    
    $query ="SELECT ".

        "(SELECT COALESCE( SUM( dollar_amount - fee), 0 ) ".
        "  FROM $p"."deposits WHERE user_id = '$user_id') - ".

        "(SELECT COALESCE( SUM( dollar_amount + fee), 0 ) ".
        "  FROM $p"."withdrawals WHERE user_id = '$user_id') + ".

        "(SELECT COALESCE( SUM( dollar_delta), 0 ) ".
        "  FROM $p"."game_ledger WHERE user_id = '$user_id') -".

        "(SELECT COALESCE( SUM( entry_fee ), 0 ) ".
        "  FROM $p"."tournament_stats WHERE user_id = '$user_id') +".

        "(SELECT COALESCE( SUM( prize ), 0 ) ".
        "  FROM $p"."tournament_stats WHERE user_id = '$user_id') ".

        "AS recomputed_balance;";
    $result = cm_queryDatabase( $query );

    if( mysql_numrows( $result ) == 0 ) {
        return 0;
        }
    else {
        return mysql_result( $result, 0, 0 );
        }
    }



function cm_recomputeHouseBalanceFromHistory() {
    global $tableNamePrefix;

    $p = $tableNamePrefix;
    
    $query ="SELECT ".

        "(SELECT COALESCE( SUM( dollar_delta), 0 ) ".
        "  FROM $p"."game_ledger WHERE user_id = '0') +".

        "(SELECT COALESCE( SUM( entry_fee ), 0 ) ".
        "  FROM $p"."tournament_stats ) -".

        "(SELECT COALESCE( SUM( prize ), 0 ) ".
        "  FROM $p"."tournament_stats ) - ".
        
        "( SELECT house_withdrawals FROM  $p"."server_globals ) ".

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
    $negative = false;
    
    if( $inDollars < 0 ) {
        $inDollars *= -1;
        $negative = true;
        }
        
    $result = number_format( $inDollars, 4 );

    if( !$inForceFourDecimal && substr( $result, -2 ) === "00" ) {
        $result = number_format( $inDollars, 2 );
        }

    if( $negative ) {
        return "-\$$result";
        }
    else {
        return "\$$result";
        }
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

    $cardNumber = cm_inputFilter( $dataParts[0], "/\d+/", 0 );
    $month = cm_inputFilter( $dataParts[1], "/\d\d/", "01" );
    $year = cm_inputFilter( $dataParts[2], "/\d\d\d\d/", "1901" );
    // 3 or 4 digits
    $cvc = cm_inputFilter( $dataParts[3], "/\d\d\d\d?/", "111" );

    $cents_amount = round( $dollar_amount * 100 );
    
    global $stripeTokens, $stripeCharges,
        $stripeChargeDescription;



    // before charging card, get card token so that we can obtain card
    // fingerprint

    $stripeArguments = array (
        "card[number]=$cardNumber", "card[exp_month]=$month", 
        "card[exp_year]=$year", "card[cvc]=$cvc");
    //cm_log( "Calling Stripe with CURL:  $curlCallString" );
    $output = array();
    $output = cm_stripeCall( $stripeTokens, $stripeArguments );

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


    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."cards ".
        "WHERE user_id = '$user_id' AND proof_on_file = 1;";

    $result = cm_queryDatabase( $query );

    if( mysql_result( $result, 0, 0 ) > 0 ) {
        // allow deposits from other cards for this user if we have proof on
        // file for ONE card for this user
        // Otherwise, after they're approved for one card and go over the
        // noInfo deposit limit, they will be blocked from using any other
        // cards.  Once proof is on file for one card, we trust them.
        
        $proof_on_file = 1;
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
    
    $stripeArguments = array("receipt_email=$email", 
        "amount=$cents_amount",
        "currency=usd",
        "expand[]=balance_transaction",
        "description=$fullDescription",
        "card[number]=$cardNumber",
        "card[exp_month]=$month",
        "card[exp_year]=$year",
        "card[cvc]=$cvc");

    //cm_log( "Calling Stripe with CURL:  $curlCallString" );
    
    $output = array();
    $output = cm_stripeCall($stripeCharges, $stripeArguments);
    
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

// cm_stripeCall($stripecall string, $arguments array)
// this function can be configured to call stripe locally or through a relay
// function expects a string with the stripe call and an array with stripe arguments
// (example for array
// $stripe_argument_array = array("card[number]=4242424242424242",
//      "card[exp_month]=12",
//      "card[exp_year]=2017",
//      "card[cvc]=123");
// function also includes a shared hardcoded key that must be deployed on client and server
// this shared secret must be manually updated both places if compromised.
function cm_stripeCall($stripecall, $arguments)
{
    global $curlPath, $stripeBaseURL, $stripeSecretKey,
        $stripeChargeDescription, $stripeUseRelayServer, $stripeRelayURL,
        $stripeRelaySecretKey;

    // for testing override some values
    //$curlPath = "curl";
    //$stripeSecretKey = "sk_test_BQokikJOvBiI2HlWgH4olfQ2";
        
    $results = array();
    if($stripeUseRelayServer){
        //echo "calling stripe via relay<br>";
        cm_log( "Calling stripe via relay" );
        // add call and key to array
        array_unshift($arguments, $stripecall, $stripeSecretKey);

        //http://randomkeygen.com/
        //96E9DD3EB4161A8525AA83EA9A38EFA8FCC1CF56871E1747CC9D12737A9775CD
        //137FDD84976BC7D2E75E77BE874C488FECC564778D1BC6DBBF4C
        //original sample key:
        // bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3
        $relaycurlcall = $curlPath." ".$stripeRelayURL;
        // shared secret across both systems in hex
        // if this key is changed, it must be changed on the relay
        // server as well
        $key = pack('H*', $stripeRelaySecretKey );
        $key_size =  strlen($key);
        //echo "Key size: " . $key_size . "<br>";
        //echo "input: " . $input . "<br>";
    
        // create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
        // creates a cipher text compatible with AES (Rijndael block size = 128)
        // to keep the text confidential 
        // only suitable for encoded input that never ends with value 00h
        // (because of default zero padding)
        $argument_string = implode("&", $arguments);
        //echo "pre-send".$argument_string;
        $stripecall_encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                     $argument_string, MCRYPT_MODE_CBC, $iv);

        // prepend the IV for it to be available for decryption
        $ciphertext = $iv . $stripecall_encrypted;
        // base 64 encode the encrypted block so it can be sent safely
        $ciphertext_base64 = base64_encode($ciphertext);
        // escape any characters that the curl command would mangle or
        // interpret wrong
        $urlencoded = urlencode($ciphertext_base64);

    
        $relay_call = $relaycurlcall." -d call=".$urlencoded;
        //echo "relay call: ".$relay_call;
        exec($relay_call, $results);
    } else {
        //echo "calling stripe locally<br>";
        cm_log( "Calling stripe via local curl directly" );
        $curlCallString = $curlPath.
            " '$stripeBaseURL"."$stripecall' ".
            "-u $stripeSecretKey".": ";
            foreach($arguments as &$arg){
                $curlCallString = $curlCallString." -d \"$arg\" ";
            }
            //echo "curl call string: ".$curlCallString."<br>";
            exec( $curlCallString, $results );
    }
    return $results;
}


// no hyphens
function cm_generateAccountKey( $inEmail, $inSalt, $inLength = -1 ) {

    global $serverSecretKey, $accountKeyLength;

    if( $inLength == -1 ) {
        // default
        $inLength = $accountKeyLength;
        }
    
    
    $account_key = "";

    // repeat hashing new rand values, mixed with our secret
    // for security, until we have generated enough digits.
    while( strlen( $account_key ) < $inLength ) {
        
        $randVal = rand();
        
        $hash_bin =
            cm_hmac_sha1_raw( $serverSecretKey,
                              $inEmail .
                              uniqid( "$randVal"."$inSalt", true ) );
        
        
        $hash_base32 = cm_readableBase32Encode( $hash_bin );
        
        $digitsLeft = $inLength - strlen( $account_key );
        
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
        $lobCheckNote, $lobBankAccount, $lobFromAddress;

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
        "-d 'description=Cordial Minuet Withdrawal' ".
        "-d \"bank_account=$lobBankAccount\" ".
        "-d \"from=$lobFromAddress\" ".
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

        if( strstr( $line, "object" ) != FALSE &&
            strstr( $line, "check" ) != FALSE ) {
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

    $originCountry = "US";

    if( $currency != "USD" ) {
        // check being sent out from customer's country
        $originCountry = $country;
        }
    
    
    
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
        // country of the source account (our account, or local bank)
        "-d 'Country=$originCountry' ".
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
    
        // cm_log( "Response from Chexx response:\n$outputString" );
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
        strstr( $outputString, "UnsupportedCountry" ) != FALSE
        ||
        strstr( $outputString, "UnsupportedCurrency" ) != FALSE ){

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
        else if( strstr( $outputString, "UnsupportedCurrency" ) != FALSE ) {
            $countryFullName = $allowedCountries[ $country ];

            $message = "Currency $currency unsupported ".
                "by Chexx for $email";

            cm_log( $message );

            cm_informAdmin( $message,
                            "Cordial Minuet Chexx currency unsupported" );
            }
        else {
            // uknown reason
            $message = "CHECK_FAILED for $email, ".
                "Chexx Raven error:\n$outputString";
            
            cm_log( $message );
        
            cm_informAdmin( $message,
                            "Cordial Minuet Chexx call failed" );
            }
        
        return;
        }
    

    if( strstr( $outputString, "InProgress" ) == FALSE &&
        strstr( $outputString, "Invalid%3ATestChannel" ) == FALSE ) {

        echo "CHECK_FAILED";
        
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );
        
        $message = "CHECK_FAILED for $email, ".
            "Chexx Raven error:\n$outputString";

        cm_log( $message );
        
        cm_informAdmin( $message,
                        "Cordial Minuet Chexx call failed" );

        return;
        }
        
    // a valid response

    // email admin about all outgoing checks

    cm_informAdmin( "Sending $currency check for $check_amount_string ".
                    "USD value ($fee_string fee) ".
                    "to $email in country $country",
                    "Cordial Minuet Chexx check sent" );
    

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

        global $curlPath, $lobURL, $lobAPIKey, $lobBankAccount, 
               $lobFromAddress;
    
        $curlCallString =
            "$curlPath ".
            "'$lobURL' ".
            "-u $lobAPIKey".": ".
            "-d \"message=$fullNote\" ".
            "-d 'memo=$memo' ".
            "-d 'description=Chexx Balance Refresh' ".
            "-d \"bank_account=$lobBankAccount\" ".
            "-d \"from=$lobFromAddress\" ".
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

                if( strstr( $line, "object" ) != FALSE &&
                    strstr( $line, "check" ) != FALSE ) {
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




function cm_getHeldAmulet( $user_id ) {
    global $tableNamePrefix;
    
    $query = "SELECT amulet_id FROM $tableNamePrefix"."amulets ".
        "WHERE holding_user_id = $user_id;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    if( $numRows == 0 ) {
        return 0;
        }
    else {
        $amulet_id = mysql_result( $result, 0, "amulet_id" );

        global $amulets;
        
        $endTime = strtotime( $amulets[$amulet_id][0] );

        if( time() < $endTime ) {
            return $amulet_id;
            }
        else {
            return 0;
            }
        }
    }


// returns total points, accounting for pending hold-time penalty
function cm_getAmuletPoints( $amulet_id, $user_id ) {
    global $tableNamePrefix;

    $penalty = cm_getAmuletHoldTimePenalty( $user_id );

    $query = "SELECT points FROM $tableNamePrefix"."amulet_points ".
        "WHERE amulet_id = $amulet_id AND user_id = $user_id;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    if( $numRows == 0 ) {
        return 0;
        }
    else {
        $points = mysql_result( $result, 0, "points" ) - $penalty;

        if( $points < 0 ) {
            $points = 0;
            }
        
        return $points;
        }
    }



function cm_getAmuletHoldTimePenalty( $user_id ) {

    global $tableNamePrefix;

    global $amuletHoldPenaltyPerMinute;
    
    $query = "SELECT ".
        "TIMESTAMPDIFF( MINUTE, acquire_time, CURRENT_TIMESTAMP ) ".
        "AS minutes_held ".
        "FROM $tableNamePrefix"."amulets ".
        "WHERE holding_user_id = $user_id;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    if( $numRows == 1 ) {
        $minutes_held = mysql_result( $result, 0, "minutes_held" );

        return $amuletHoldPenaltyPerMinute * $minutes_held;
        }
    else {
        return 0;
        }
    }



function cm_subtractPointsForAmuletHoldTime( $user_id ) {
    global $tableNamePrefix;

    $penalty = cm_getAmuletHoldTimePenalty( $user_id );
    

    if( $penalty > 0 ) {
    
        $query = "SELECT amulet_id ".
            "FROM $tableNamePrefix"."amulets ".
            "WHERE holding_user_id = $user_id;";
        
        $result = cm_queryDatabase( $query );
        
        $numRows = mysql_numrows( $result );
        if( $numRows == 1 ) {
            $amulet_id = mysql_result( $result, 0, "amulet_id" );
            
            $query = "UPDATE $tableNamePrefix"."amulet_points " .
                "SET points = GREATEST( 0, points - $penalty ) ".
                "WHERE amulet_id = $amulet_id AND user_id = $user_id;";
            
            cm_queryDatabase( $query );
            }
        }
    }


function cm_addPointsForAmuletWin( $amulet_id, $user_id ) {
    global $tableNamePrefix;

    global $amuletLastStandingPoints;
    
    $winPoints = $amuletLastStandingPoints;

    $query = "INSERT INTO $tableNamePrefix"."amulet_points " .
        "SET amulet_id = $amulet_id, user_id = $user_id, ".
        "    points = $winPoints, last_score_time = CURRENT_TIMESTAMP ".
        "ON DUPLICATE KEY ".
        "UPDATE points = points + $winPoints, ".
        "       last_score_time = CURRENT_TIMESTAMP;";

    cm_queryDatabase( $query );

    $query = "UPDATE $tableNamePrefix"."amulets " .
        "SET last_amulet_game_time = CURRENT_TIMESTAMP ".
        "WHERE amulet_id = $amulet_id AND holding_user_id = $user_id;";

    cm_queryDatabase( $query );
    }



function cm_pickUpAmulet( $amulet_id, $user_id ) {
    global $tableNamePrefix;

    $query = "UPDATE $tableNamePrefix"."amulets " .
        "SET holding_user_id = $user_id, ".
        "acquire_time = CURRENT_TIMESTAMP, ".
        "last_amulet_game_time = CURRENT_TIMESTAMP ".
        "WHERE amulet_id = $amulet_id;";

    cm_queryDatabase( $query );
    }



// table name without prefix
// where clause without where
function cm_countQuery( $inTableName, $inWhere ) {
    global $tableNamePrefix;
    
    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."$inTableName ".
            "WHERE $inWhere;";

    $result = cm_queryDatabase( $query );

    return mysql_result( $result, 0, 0 );
    }




function cm_pickUpDroppedAmulet( $user_id, $inAmountJustWon ) {
    global $amuletMaxStake;

    if( cm_getPlayerBalance( $user_id ) + $inAmountJustWon
        < $amuletMaxStake ) {
        
        // player ineligible to pick up a dropped amulet (balance too low)
        return;
        }

    $waitingAmuletGameCount =
        cm_countQuery( "games",
                       "player_1_id != 0 AND player_2_id = 0 AND started = 0 ".
                       "AND amulet_game = 1" );

    if( $waitingAmuletGameCount > 0 ) {
        // there are already unmatched amulet games out there
        // don't put another amulet into the mix.
        return;
        }

    
    $heldAmulets =
        cm_countQuery( "amulets", "holding_user_id != 0" );

    
    // number of users in last two minutes
    $activeUsers = cm_countUsersTime( '0 0:02:00' );

    if( $activeUsers < 2 * $heldAmulets + 3 ) {
        // not enough active users to warrant handing out another amulet
        // (not enough pairs of players)
        return;
        }
    
    

    // find an amulet

    // note that this code uses FOR UPDATE locks on select
    // this is okay, because table only allows one user to hold a given
    // amulet, and in the case of conention, one will prevail

    // don't want to add locks here, because this is called from
    // endOldGames while it has a lock on the users table.
    // this would be deadlock prone

    // however, UPDATES will lock... hmm... watch out for this
    
    global $tableNamePrefix;

    $query = "UPDATE $tableNamePrefix"."amulets " .
        "SET users_to_skip_on_drop = users_to_skip_on_drop - 1 ".
        "WHERE holding_user_id = 0 AND users_to_skip_on_drop > 0 LIMIT 1;";

    cm_queryDatabase( $query );

    $query = "SELECT amulet_id FROM $tableNamePrefix"."amulets ".
        "WHERE holding_user_id = 0 && users_to_skip_on_drop = 0 LIMIT 1;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );
    if( $numRows == 1 ) {
        $amulet_id = mysql_result( $result, 0, "amulet_id" );

        $query = "UPDATE $tableNamePrefix"."amulets ".
            "SET holding_user_id = $user_id, ".
            "acquire_time = CURRENT_TIMESTAMP, ".
            "last_amulet_game_time = CURRENT_TIMESTAMP ".
            "WHERE amulet_id = $amulet_id;";
        cm_queryDatabase( $query );

        cm_addPointsForAmuletWin( $amulet_id, $user_id );
        }
    
        
    // check if a new amulet should be dropped (one that has never
    // dropped before)
    
    if( cm_countQuery( "amulets", "holding_user_id = 0" )  == 0 ) {
        
        global $amulets;

        $time = time();
            
        foreach( $amulets as $id => $record ) {

            $endTime = strtotime( $record[0] );

            if( $time < $endTime ) {

                if( cm_countQuery( "amulets",
                                   "amulet_id = $id" ) == 0 ) {
                    // found one to add

                    // how many live games are running?
                    $liveNonAmuletGameCount =
                        cm_countQuery(
                            "games",
                            "player_1_id != 0 AND player_2_id != 0 ".
                            "AND amulet_game = 0 " );

                    $users_to_skip = $liveNonAmuletGameCount;
                    
                    if( $users_to_skip > 10 ) {
                        $users_to_skip = 10;
                        }
                                        

                    // use ON DUPLICATE UPDATE here
                    // because we're not using locks, so
                    // some other user could slip in and do the
                    // insert before us
                    $query = "INSERT INTO $tableNamePrefix"."amulets " .
                        "SET amulet_id = $id, ".
                        "    users_to_skip_on_drop = $users_to_skip ".
                        "ON DUPLICATE KEY UPDATE ".
                        "    users_to_skip_on_drop = $users_to_skip; ";

                    cm_queryDatabase( $query );

                    break;
                    }
                }
            }
        }
    }




// returns code name of running contest, or "" if not running
function cm_isVsOneRunning() {
    global $vsOneLive;

    if( $vsOneLive ) {

        global $vsOneStartTimes, $vsOneEndTimes;
        foreach( $vsOneStartTimes as $codeName => $startTimeString ) {
                    
            $time = time();

            $startTime = strtotime( $startTimeString );
            $endTime = strtotime( $vsOneEndTimes[ $codeName ] );
            
            
            if( $time >= $startTime && $time < $endTime ) {
                return $codeName;
                }
            }
        }
    return "";
    }




// ends any games that this user is part of
// (to clear up conflicts before starting new games

// assumes that autocommit is off

// returns payout to $user_id
function cm_endOldGames( $user_id, $inForceTie = false ) {
    global $tableNamePrefix;
    
    $query = "SELECT game_id, semaphore_key, player_1_id, player_2_id, ".
        "game_square, ".
        "game_type, ".
        "round_number, ".
        "amulet_game, ".
        "dollar_amount, ".
        "player_1_got_start, player_2_got_start, ".
        "player_1_coins, player_2_coins, ".
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
        
        $amulet_game = mysql_result( $result, $i, "amulet_game" );

        $round_number = mysql_result( $result, $i, "round_number" );

        cm_log( "Calling endOldGames for game $game_id, p1=$player_1_id, ".
                "p2=$player_2_id, stack = \n" . cm_getBacktrace() );
        
        $game_square = mysql_result( $result, $i, "game_square" );
        $game_type = mysql_result( $result, $i, "game_type" );

        $old_player_1_id = $player_1_id;
        $old_player_2_id = $player_2_id;
        
        
        $dollar_amount = mysql_result( $result, $i, "dollar_amount" );
        
        $player_1_got_start =
            mysql_result( $result, $i, "player_1_got_start" );
        $player_2_got_start =
            mysql_result( $result, $i, "player_2_got_start" );

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
                "p2Pot = $player_2_pot_coins, ".
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
        

        $leaverPenalty = 0;
        
        if( $player_1_id != 0 &&
            $player_2_id != 0 ) {

            $leaverPenalty = cm_getLeavePenalty( $round_number );
            }
        
        
        
        if( $player_1_id != 0 &&
            $player_2_id != 0 &&
            $player_1_move_count == 7 &&
            $player_2_move_count == 7 &&
            $player_1_bet_made &&
            $player_2_bet_made ) {

            // player leaving at the end of a round

            $loserID = cm_computeLoser( $game_square, $game_type,
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

            if( $player_1_coins == 0 || $player_2_coins == 0 ) {
                // one player won everything, no penalty for leaving
                $leaverPenalty = 0;
                }
            
            if( $player_1_id == $user_id ) {
                $player_1_id = 0;
                }
            else {
                $player_2_id = 0;
                }
            }
        // else player leaving in middle
        else if( $inForceTie || ! $areGamesAllowed ||
                 !( $player_1_got_start && $player_2_got_start ) ) {
            // game force-ended by admin, or tie forced,
            // OR both haven't gotten game start yet
            // return pots to players
            $player_1_coins += $player_1_pot_coins;
            $player_2_coins += $player_2_pot_coins;

            if( $player_1_id == $user_id ) {
                $player_1_id = 0;
                }
            else {
                $player_2_id = 0;
                }

            // no penalty in this case
            $leaverPenalty = 0;
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


        
        if( $player_1_id != 0 ||
            $player_2_id != 0 ) {

            // one left just now, assess $leaverPenalty and pay to one
            // who stayed

            if( $player_1_id == 0 ) {
                if( $leaverPenalty > $player_1_coins ) {
                    $leaverPenalty = $player_1_coins;
                    }
                $player_1_coins -= $leaverPenalty;
                $player_2_coins += $leaverPenalty;
                }
            else if( $player_2_id == 0 ) {
                if( $leaverPenalty > $player_2_coins ) {
                    $leaverPenalty = $player_2_coins;
                    }
                $player_2_coins -= $leaverPenalty;
                $player_1_coins += $leaverPenalty;
                }
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

        // However, still show remaining coins for each player
        // Client can detect game-over thorugh running=0 flag (
        //   (triggered when one player ID is 0).
        // Having remaining coin balances is necessary for client to compute
        // and display correct house tribute.
        
        
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



            global $vsOneLive, $vsOneUserIDs;


            $p1Special = in_array( $old_player_1_id, $vsOneUserIDs );
            $p2Special = in_array( $old_player_2_id, $vsOneUserIDs );

            $p1VsOneCoinDelta = 0;
            $p2VsOneCoinDelta = 0;
            
            
            if( $vsOneLive &&
                ( $p1Special || $p2Special ) ) {
                
                // this game was against one of the special players

                // but is the vsOne contest running?

                $codeName = cm_isVsOneRunning();
            
                if( $codeName != "" ) {
                    // in middle of contest                    

                    global $vsOneCabalBonus;
                    
                    if( $p1Special ) {
                        $deltaCoins = $player_2_coins - $cm_gameCoins;

                        // bonus only given if row doesn't exist
                        // yet for special p2 OR if p2 has a 0 in the row
                        // (bonus re-given whenver p2's score is 0 again)
                        $bonus = 0;

                        if( $p2Special ) {
                            $bonus = $vsOneCabalBonus;
                            }
                        
                        $query =
                            "INSERT INTO $tableNamePrefix"."vs_one_scores ".
                            "SET user_id = '$old_player_2_id', ".
                            "    vs_one_code_name = '$codeName', ".
                            "    coins_won = ".
                            "      GREATEST( $deltaCoins + $bonus, 0 ) ".
                            "ON DUPLICATE KEY UPDATE ".
                            "    coins_won = ".
                            "    GREATEST( coins_won + $deltaCoins + ".
                            "                  if( coins_won, 0, $bonus ), ".
                            "              0 );";
                        
                        cm_queryDatabase( $query );

                        $p2VsOneCoinDelta = $deltaCoins;
                        }
                    
                    if( $p2Special ) {
                        $deltaCoins = $player_1_coins - $cm_gameCoins;

                        // bonus only given if row doesn't exist
                        // yet for special p1 OR if p1 has a 0 in the row
                        // (bonus re-given whenver p1's score is 0 again)
                        $bonus = 0;

                        if( $p1Special ) {
                            $bonus = $vsOneCabalBonus;
                            }
                        
                        $query =
                            "INSERT INTO $tableNamePrefix"."vs_one_scores ".
                            "SET user_id = '$old_player_1_id', ".
                            "    vs_one_code_name = '$codeName', ".
                            "    coins_won = ".
                            "      GREATEST( $deltaCoins + $bonus, 0 ) ".
                            "ON DUPLICATE KEY UPDATE ".
                            "    coins_won = ".
                            "    GREATEST( coins_won + $deltaCoins + ".
                            "                  if( coins_won, 0, $bonus ), ".
                            "              0 );";
                        
                        cm_queryDatabase( $query );

                        $p1VsOneCoinDelta = $deltaCoins;
                        }
                    
                    }
                }
            
            

            
            // figure out if an amulet should change hands
            $player_1_amulet = cm_getHeldAmulet( $old_player_1_id );
            $player_2_amulet = cm_getHeldAmulet( $old_player_2_id );


            $player_1_last_standing = 0;
            $player_2_last_standing = 0;
            
            if( $player_1_id == 0 ) {
                // p1 first to leave
                
                if( $player_2_payout == 0 ) {
                    $player_1_last_standing = 1;
                    }
                    else {
                        $player_2_last_standing = 1;
                        }
                }
            else if( $player_2_id == 0 ) {
                // p2 first to leave
                
                if( $player_1_payout == 0 ) {
                    $player_2_last_standing = 1;
                    }
                else {
                    $player_1_last_standing = 1;
                    }
                }
            
            

            if( $amulet_game &&
                ( $player_1_amulet != 0 || $player_2_amulet != 0 ) ) {
                
                
                if( $player_1_last_standing ) {
                    if( $player_1_amulet != 0 ) {
                        // p1 keeps
                        }
                    else {
                        // p2 drops
                        cm_subtractPointsForAmuletHoldTime( $old_player_2_id );
                        
                        cm_pickUpAmulet( $player_2_amulet, $old_player_1_id );

                        $player_1_amulet = $player_2_amulet;

                        $player_2_amulet = 0;
                        }
                    
                    cm_addPointsForAmuletWin( $player_1_amulet,
                                              $old_player_1_id );
                    }
                else if( $player_2_last_standing ) {
                    if( $player_2_amulet != 0 ) {
                        // p2 keeps
                        }
                    else {
                        // p1 drops
                        cm_subtractPointsForAmuletHoldTime( $old_player_1_id );
                        
                        cm_pickUpAmulet( $player_1_amulet, $old_player_2_id );

                        $player_2_amulet = $player_1_amulet;

                        $player_1_amulet = 0;
                        }
                    
                    cm_addPointsForAmuletWin( $player_2_amulet,
                                              $old_player_2_id );
                    }                
                }
            else if( ! $amulet_game ) {
                // not an amulet game

                // potentially hand last standing a dropped amulet
                // (if they don't have one already)
                // but this doesn't count as a payout from this match
                
                if( $player_1_last_standing && $player_1_amulet == 0 ) {
                    cm_pickUpDroppedAmulet( $old_player_1_id,
                                            $player_1_payout );
                    }
                else if( $player_2_last_standing && $player_2_amulet == 0 ) {
                    cm_pickUpDroppedAmulet( $old_player_2_id,
                                            $player_2_payout );
                    }
                }
            
            
            


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
                                        $payoutA, $payoutB, $house_payout );

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
                "last_pay_out = $player_1_payout, ".
                "last_vs_one_coins = $p1VsOneCoinDelta ".
                "WHERE user_id = '$old_player_1_id';";
            cm_queryDatabase( $query );

            cm_addLedgerEntry( $old_player_1_id, $game_id, $player_1_payout );

            

            if( $tournamentLive ) {
                // this will do nothing if players not in tournament
                cm_tournamentCashOut( $old_player_1_id,
                                      // opponent
                                      $old_player_2_id,
                                      $player_1_payout );
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
                "last_pay_out = $player_2_payout, ".
                "last_vs_one_coins = $p2VsOneCoinDelta ".
                "WHERE user_id = '$old_player_2_id';";
            cm_queryDatabase( $query );

            cm_addLedgerEntry( $old_player_2_id, $game_id, $player_2_payout );


            if( $tournamentLive ) {
                // this will do nothing if players not in tournament
                cm_tournamentCashOut( $old_player_2_id,
                                      // opponent
                                      $old_player_1_id,
                                      $player_2_payout );
                }

            
            $query = "UPDATE $tableNamePrefix"."server_globals ".
                "SET house_dollar_balance = ".
                "  house_dollar_balance + $house_payout;";
            cm_queryDatabase( $query );

            cm_addLedgerEntry( 0, $game_id, $house_payout );
            
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



// checks if there's no manual tournament running right now
// if so, schedules the next auto tournament
function cm_checkForAutoTournament( $inCodeNameOverride = "" ) {
    global $tournamentLive, $tournamentCodeName, $tournamentEntryFee,
        $tournamentStake, $tournamentPairProfitLimit, $tournamentMinPrize,
        $tournamentPrizePoolFraction, $tournamentPrizeRatio,
        $tournamentMinProfitForPrize,
        $tournamentStartTime, $tournamentEndTime;


    $time = time();
    
    $startTime = strtotime( $tournamentStartTime );
    $endTime = strtotime( $tournamentEndTime );


    if( ( $inCodeNameOverride == "" ||
          $tournamentCodeName == $inCodeNameOverride )
        &&
        $tournamentLive
        &&
        $time >= $startTime && $time <= $endTime + 15 * 60 ) {
        // manual one running, or ened <15 minutes ago (leave room for flush
        // to issue payouts before we override fees with an auto-tournament),
        // don't schedule it
        return;
        }

    if( !$tournamentLive ) {
        // tournaments disabled
        return;
        }

    global $autoTournamentStartTime, $autoTournamentSpacingMinutes,
        $autoTournamentLengthMinutes;
    
    $autoTime = strtotime( $autoTournamentStartTime );

    if( $time < $autoTime ) {
        return;
        }

    $minutesSinceStart = ( $time - $autoTime ) / 60;

    $tournamentNumber =
        floor( $minutesSinceStart / $autoTournamentSpacingMinutes );


    // watch for request that passes in a different code name
    // 
    $code_name = cm_requestFilter( "tournament_code_name",
                                   "/[A-Z0-9_]+/i", "" );

    global $autoTournamentNamePrefix;
    
    
    if( $inCodeNameOverride != "" ) {
        $code_name = $inCodeNameOverride;
        }


    if( $code_name != "" ) {
        // override the tournament number with the number from this
        // code name
        sscanf( $code_name,
                $autoTournamentNamePrefix . "_%d", $tournamentNumber );
        }
    
    $tournamentMinutesIn = $minutesSinceStart % $autoTournamentSpacingMinutes;

    
    $startTime = $autoTime +
        $tournamentNumber * $autoTournamentSpacingMinutes * 60;

    $endTime = $startTime + $autoTournamentLengthMinutes * 60;


    global $autoTournamentNumInRotation;
    
    $rotNumber = $tournamentNumber % $autoTournamentNumInRotation;

    
    $tournamentCodeName = $autoTournamentNamePrefix ."_$tournamentNumber";

    //echo "Code name = 

    global $autoTournamentEntryFees, $autoTournamentStakes,
        $autoTournamentPairProfitLimits, $autoTournamentPrizePoolFractions,
        $autoTournamentMinPrizes, $autoTournamentPrizeRatios,
        $autoTournamentMinProfitForPrizes;
    
    $tournamentEntryFee = $autoTournamentEntryFees[ $rotNumber ];
    $tournamentStake = $autoTournamentStakes[ $rotNumber ];
    $tournamentPairProfitLimit = $autoTournamentPairProfitLimits[ $rotNumber ];
    
    $tournamentPrizePoolFraction =
        $autoTournamentPrizePoolFractions[$rotNumber];
    
    $tournamentMinPrize = $autoTournamentMinPrizes[ $rotNumber ];
    
    $tournamentPrizeRatio = $autoTournamentPrizeRatios[ $rotNumber ];
    $tournamentMinProfitForPrize =
        $autoTournamentMinProfitForPrizes[ $rotNumber ];


    $tournamentStartTime = date( DATE_RFC2822, $startTime );
    $tournamentEndTime = date( DATE_RFC2822, $endTime );
    }




function cm_addUserToTournament( $user_id ) {
    global $tableNamePrefix, $tournamentEntryFee,
        $tournamentCodeName, $tournamentStake;    

    $query = "INSERT INTO $tableNamePrefix"."tournament_stats ".
        "SET user_id = '$user_id', ".
        "    tournament_code_name = '$tournamentCodeName', ".
        "    entry_fee = $tournamentEntryFee, ".
        "    prize = 0, ".        
        "    update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = 0, ".
        "    num_games_finished = 0;";
    
    cm_queryDatabase( $query );
    }



function cm_tournamentBuyIn( $user_id, $inOpponentID ) {
    global $tableNamePrefix, $tournamentCodeName;

    // don't count buy in against profit until cash out
    // thus, a game that doesn't end before the deadline simply doesn't count
    // (instead of counting as negative profit for the buy-in that
    //  didn't cash out by the deadline)
    //
    // Don't uptick num_games_finished until cash-out (so you can't watch
    // this stat on leaderboard to figure out who you're playing against)
    $query = "UPDATE $tableNamePrefix"."tournament_stats ".
        "SET update_time = CURRENT_TIMESTAMP ".
        "WHERE user_id = $user_id AND ".
        "      tournament_code_name = '$tournamentCodeName';";

    cm_queryDatabase( $query );


    $query = "INSERT INTO $tableNamePrefix"."tournament_pairings ".
        "SET user_id = '$user_id', user_id_opponent = $inOpponentID, ".
        "    tournament_code_name = '$tournamentCodeName', ".
        "    update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = 0, ".
        "    num_games_started = 1 ".
        "ON DUPLICATE KEY UPDATE ".
        "    update_time = CURRENT_TIMESTAMP, ".
        "    num_games_started = num_games_started + 1;";
    
    cm_queryDatabase( $query );
    }



function cm_tournamentCashOut( $user_id, $inOpponentID, $inDollarsOut ) {
    global $tableNamePrefix, $tournamentCodeName, $tournamentStake;

    $profit = $inDollarsOut - $tournamentStake;

    $query = "UPDATE $tableNamePrefix"."tournament_stats ".
        "SET update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = net_dollars + $profit, ".
        "    num_games_finished = num_games_finished + 1 ".
        "WHERE user_id = $user_id AND ".
        "      tournament_code_name = '$tournamentCodeName';";

    cm_queryDatabase( $query );

    

    $query = "UPDATE $tableNamePrefix"."tournament_pairings ".
        "SET update_time = CURRENT_TIMESTAMP, ".
        "    net_dollars = net_dollars + $profit ".
        "WHERE user_id = $user_id AND user_id_opponent = $inOpponentID AND".
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



function cm_isUserInTournament( $user_id ) {
    global $tableNamePrefix, $tournamentCodeName;

    $query = "SELECT COUNT(*) FROM $tableNamePrefix"."tournament_stats ".
        "WHERE user_id = $user_id ".
        "AND tournament_code_name = '$tournamentCodeName';";

    $result = cm_queryDatabase( $query );

    if( mysql_result( $result, 0, 0 ) == 1 ) {
        return true;
        }
    else {
        return false;
        }
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

    $amulet_game = cm_requestFilter( "amulet_game", "/[01]/", "0" );


    $amulet_id = cm_getHeldAmulet( $user_id );

    if( $amulet_game && $amulet_id == 0 ) {
        cm_log( "cm_joinGame denied, asked for amulet_game when not ".
                "holding an amulet" );

        echo "AMULET_DROPPED";
        return;        
        }

    
    $game_type = cm_requestFilter( "game_type", "/[0-9]+/", "0" );

    if( $game_type != 0 && $game_type != 1 ) {
        cm_log( "cm_joinGame denied, forbidden game_type $game_type" );
        cm_transactionDeny();
        return;
        }

    // force non-experimental game mode
    $game_type = 0;
    

    global $amuletMaxStake;

    if( $amulet_game && $dollar_amount != $amuletMaxStake ) {
        cm_log( "cm_joinGame denied, dollar_amount $dollar_amount ".
                "does not match amuletMaxStake $amuletMaxStake for ".
                "amulet game" );
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


    $tournamentPairingClause = "";


    if( cm_isTournamentLive( $dollar_amount ) &&
        cm_isUserInTournament( $user_id ) ) {

        // user in a live tournament at this stake level
        
        global $tournamentCodeName;

        $tableName = "$tableNamePrefix"."tournament_stats";
        
        // limit user to joining others in tournament
        $tournamentPairingClause =
            "AND ( SELECT COUNT(*) FROM $tableName ".
            "      WHERE user_id = player_1_id ".
            "      AND tournament_code_name = '$tournamentCodeName' ) > 0 ";


        $tableName = "$tableNamePrefix"."tournament_pairings";

        // limit user to joining with those that they've not made too much
        // profit from already in tournament
        global $tournamentPairProfitLimit;

        $pLimit = $tournamentPairProfitLimit;

        $tournamentPairingClause .=
            "AND ( ".
            //     Either no games played between them yet
            "      ( SELECT COUNT(*) FROM $tableName ".
            "        WHERE user_id = $user_id ".
            "        AND user_id_opponent = player_1_id ".
            "        AND tournament_code_name = '$tournamentCodeName' ) = 0 ".
            "      OR ".
            //     Or profit in either direction below limit
            "      ( SELECT net_dollars FROM $tableName ".
            "        WHERE user_id = $user_id ".
            "        AND user_id_opponent = player_1_id ".
            "        AND tournament_code_name = '$tournamentCodeName' ) ".
            "      < $pLimit ".
            "      AND ".
            "      ( SELECT net_dollars FROM $tableName ".
            "        WHERE user_id = player_1_id ".
            "        AND user_id_opponent = $user_id ".
            "        AND tournament_code_name = '$tournamentCodeName' ) ".
            "      < $pLimit )";
        }
    else if( cm_isTournamentLive( $dollar_amount ) &&
        ! cm_isUserInTournament( $user_id ) ) {    

        // live tournament happening at this stake level
        // BUT user not participating

        // make sure they're only paired with NON-tournament players

        global $tournamentCodeName;

        $tableName = "$tableNamePrefix"."tournament_stats";
        
        $tournamentPairingClause =
            "AND ( SELECT COUNT(*) FROM $tableName ".
            "      WHERE user_id = player_1_id ".
            "      AND tournament_code_name = '$tournamentCodeName' ) = 0 ";
        }
    
    
    // does a game already exist with this value?

    $query = "SELECT semaphore_key, player_1_id, game_id ".
        "FROM $tableNamePrefix"."games ".
        "WHERE player_1_id != 0 AND player_2_id = 0 AND started = 0 ".
        "AND dollar_amount = '$dollar_amount' ".
        "AND amulet_game = 0 ".
        "AND game_type = $game_type ".
        "$tournamentPairingClause ".
        "LIMIT 1 FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    
    $numRows = mysql_numrows( $result );


    $returnJoinResult = true;
    $needToSignalOldSem = false;
    $oldSemKey;
    

    
    $joiningPlayerID = $user_id;

    
    if( $numRows == 1 && !$amulet_game && $dollar_amount <= $amuletMaxStake ) {

        $game_id = mysql_result( $result, 0, "game_id" );
        $player_1_id = mysql_result( $result, 0, "player_1_id" );
        $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

        if( cm_getHeldAmulet( $user_id ) == 0 ||
            cm_getHeldAmulet( $player_1_id ) == 0 ) {
        
        
            // have a pair of players about to join each other
            // this game is in amulet range
            
            // AND at least one is not an amulet holder already
            
            
            // check if an amulet player is waiting

            $query = "SELECT semaphore_key, player_1_id, game_id ".
                "FROM $tableNamePrefix"."games ".
                "WHERE player_1_id != 0 AND player_2_id = 0 AND started = 0 ".
                "AND amulet_game = 1 ".
                "AND CURRENT_TIMESTAMP > amulet_game_wait_time ".
                "LIMIT 1 FOR UPDATE;";


            $resultAmulet = cm_queryDatabase( $query );


            $numRowsAmulet = mysql_numrows( $resultAmulet );

            if( $numRowsAmulet == 1 ) {
            
                // got one!
                

                // count this game start toward pushing back amult-inactivity
                $amulet_holder_id = mysql_result( $resultAmulet,
                                                  0, "player_1_id" );
                $amulet_holder_held_amulet =
                    cm_getHeldAmulet( $amulet_holder_id );
                
                $query = "UPDATE $tableNamePrefix"."amulets " .
                    "SET last_amulet_game_time = CURRENT_TIMESTAMP ".
                    "WHERE amulet_id = $amulet_holder_held_amulet AND ".
                    "holding_user_id = $amulet_holder_id;";

                cm_queryDatabase( $query );
                
                

                $pullAway;

                if( cm_getHeldAmulet( $user_id ) != 0 ) {
                    $pullAway = 0;
                    }
                else if( cm_getHeldAmulet( $player_1_id ) != 0 ) {
                    $pullAway = 1;
                    }
                else {
                    // neither hold amulet, pick random
                    $pullAway = rand( 0, 1 );
                    }
                
            
                // pull one of them away from the game they're about to connect
                // leave other hanging
                if( $pullAway ) {

                    // pulling away player that is joining game, can just
                    // leave game creator hanging (they were already hanging)
            
                    // just replace our selected result with the amulet game
                    // result

                    $joiningPlayerID = $user_id;
                    $result = $resultAmulet;

                    $returnJoinResult = true;
                    }
                else {

                    // pulling away player that created game

                    // they are waiting on the other semaphore


                    $joiningPlayerID = $player_1_id;

                    $needToSignalOldSem = true;
                    $oldSemKey = $semaphore_key;

                    // again,
                    // replace our selected result with the amulet game result
                    $result = $resultAmulet;

                    // BUT delete the old game that they created
                    $query = "DELETE FROM $tableNamePrefix"."games ".
                        "WHERE game_id = $game_id;";
                    cm_queryDatabase( $query );

                    // we'll delete the semaphore below after we signal it
                    // one last time
            
            
                    // let the joining player create their own game
                    // and return THAT result
                    $returnJoinResult = false;
                    }
                }
            }
        }
    


    

    // ignore existing games and ALWAYS create a new game for amulet_game
    
    if( $numRows == 1 && ! $amulet_game ) {
        // one exists already

        // join it
        $game_id = mysql_result( $result, 0, "game_id" );
        $player_1_id = mysql_result( $result, 0, "player_1_id" );
        $semaphore_key = mysql_result( $result, 0, "semaphore_key" );

        global $moveTimeLimit;

        $anteCoins = cm_getAnte( 1 );
        
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_2_id = '$joiningPlayerID', ".
            "dollar_amount = '$dollar_amount', ".
            "started = 1,  ".
            "player_1_coins = player_1_coins - $anteCoins, ".
            "player_2_coins = player_2_coins - $anteCoins, ".
            "player_1_pot_coins = $anteCoins, ".
            "player_2_pot_coins = $anteCoins, ".
            "settled_pot_coins = $anteCoins, ".
            // buy-ins are done, ready for first moves to be made
            "player_1_bet_made = 1, player_2_bet_made = 1, ".
            "last_action_time = CURRENT_TIMESTAMP, ".
            "move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ".
            "WHERE game_id = '$game_id';";
        
        cm_queryDatabase( $query );


        // game has started now, subtract from both balances
        $query = "UPDATE $tableNamePrefix"."users ".
            "SET dollar_balance = dollar_balance - $dollar_amount, ".
            "games_started = games_started + 1, ".
            "total_buy_in = total_buy_in + $dollar_amount, ".
            "last_buy_in = $dollar_amount, ".
            "last_pay_out = -1, ".
            "last_vs_one_coins = 0 ".
            "WHERE user_id = '$player_1_id' OR user_id = '$joiningPlayerID';";
        cm_queryDatabase( $query );

        cm_addLedgerEntry( $player_1_id, $game_id, - $dollar_amount );
        cm_addLedgerEntry( $joiningPlayerID, $game_id, - $dollar_amount );


        if( cm_isTournamentLive( $dollar_amount ) ) {
            if( cm_isUserInTournament( $player_1_id )
                &&
                cm_isUserInTournament( $joiningPlayerID ) ) {
                
                cm_tournamentBuyIn( $player_1_id, $joiningPlayerID );
                cm_tournamentBuyIn( $joiningPlayerID, $player_1_id );
                }
            }
        
        
        $query = "UPDATE $tableNamePrefix"."users ".
            "SET games_joined = games_joined + 1 ".
            "WHERE user_id = '$joiningPlayerID';";
        cm_queryDatabase( $query );

        
        $response = "OK";

        if( $returnJoinResult ) {
            
            $query = "UPDATE $tableNamePrefix"."users SET ".
                "last_request_response = '$response', ".
                "last_request_tag = '$request_tag' ".
                "WHERE user_id = '$user_id';";
            
            cm_queryDatabase( $query );
            }
        

        // wake up opponent who may be waiting
        semSignal( $semaphore_key );

        if( $needToSignalOldSem ) {
            // waiting game creator was pulled in as joiner for an amulet
            // game.  They are waiting on the old semaphore
            semSignal( $oldSemKey );
            semRemove( $oldSemKey );
            }
        
    
        cm_queryDatabase( "COMMIT;" );
        cm_queryDatabase( "SET AUTOCOMMIT=1" );

        cm_incrementStat( "game_count" );

        if( $game_type == 1 ) {
            cm_incrementStat( "exp_game_count" );
            }
        
        cm_incrementStat( "round_count" );
        cm_incrementStat( "total_buy_in", $dollar_amount * 2 );

        cm_updateMaxStat( "max_game_stakes", $dollar_amount );

        if( $returnJoinResult ) {
            echo $response;

            return;

            // otherwise, go on below and allow joiner to create
            // their own game (we pulled the waiting game creator
            // out from under them)
            }
        else {
            // new transaction
            cm_queryDatabase( "SET AUTOCOMMIT=0" );
            }
        
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
        "last_pay_out = 0, last_vs_one_coins = 0 ".
        "WHERE user_id = '$user_id';";
    
    $result = cm_queryDatabase( $query );

    $square = cm_getNewSquare();

    global $startingSemKey;

    
    // add game to the game table


    global $cm_gameCoins;

    global $houseTableCoins;

    global $amuletJoinDelayMaxSec;
    
    
    $playerStartingCoins = $cm_gameCoins - $houseTableCoins;
    
    $query = "INSERT INTO $tableNamePrefix"."games SET ".
        // game_id is auto-increment
        // however, auto-increment can reset after server restart
        // games are removed from this table after they end
        // the auto-increment restart means game_ids may not be unique
        // (since they are also used in the game_ledger table, we must
        //  enformce their uniqueness)
        "game_id = ".
        "  1 + ".
        "  GREATEST( ".
        "    ( SELECT COALESCE( MAX( game_id ), 0 ) ".
        "             FROM $tableNamePrefix"."games AS temp ),".
        "    ( SELECT COALESCE( MAX( game_id ), 0 ) ".
        "             FROM $tableNamePrefix"."game_ledger ) ".
        "          ),".
        "creation_time = CURRENT_TIMESTAMP, ".
        "game_type = $game_type, ".
        "last_action_time = CURRENT_TIMESTAMP, ".
        "player_1_id = '$user_id'," .
        "player_2_id = 0," .
        "dollar_amount = '$dollar_amount',".
        "amulet_game = '$amulet_game',".
        "amulet_game_wait_time = ".
        "  TIMESTAMPADD( ".
        "    SECOND, ".
        "    FLOOR( RAND() * $amuletJoinDelayMaxSec ), ".
        "    CURRENT_TIMESTAMP ),".
        "started = 0,".
        "round_number = 1,".
        "game_square = '$square',".
        "player_1_got_start = 0, player_2_got_start = 0, ".
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
        "settled_pot_coins = 0, ".
        "semaphore_key = ".
        "   ( SELECT COALESCE( MAX( semaphore_key ) + 1, $startingSemKey ) ".
        "             FROM $tableNamePrefix"."games as temp2 );";
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

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );
    

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


    // make sure this game isn't past move deadline
    // due to lack of actions from both players

    // if so, then there might have been a server connection issue
    // should end game now for both and split pot, even though $user_id seems
    // to be reconnected now
    cm_queryDatabase( "SET AUTOCOMMIT=0" );

    global $moveTimeLimit;
    
    $query = "SELECT player_1_id, player_2_id ".
        "FROM $tableNamePrefix"."games ".
        "WHERE  ( player_1_id = '$user_id' OR player_2_id = '$user_id' ) ".
        " AND last_action_time < ".
        "     SUBTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) FOR UPDATE;";

    
    // watch for deadlock here and retry
    $result = cm_queryDatabase( $query, 0 );

    $tryCount = 0;
    
    while( $result == FALSE && $tryCount < 10 ) {
        cm_log( "Deadlocked on query $query  retry $tryCount" );
        
        // sleep before retrying
        sleep( 1 );
        $result = cm_queryDatabase( $query, 0 );
        
        $tryCount ++;
        }
    
    if( $result == FALSE ) {
        $errorNumber = mysql_errno();
        cm_fatalError( "Database query deadlocked after $tryCount retruies:".
                       "<BR>$inQueryString<BR><BR>" .
                       mysql_error() .
                       "<br>(error number $errorNumber)<br>" );
        return;
        }

    
    $numRows = mysql_numrows( $result );

    if( $numRows > 0 ) {
        $player_1_id = mysql_result( $result, 0, "player_1_id" );
        $player_2_id = mysql_result( $result, 0, "player_2_id" );

        // force tie (pots returned to both players)
        // if end of round not reached and both players still in the game
        // (both players failed to update last_action_time in time,
        //  which means a connection error for both)
            
        if( $player_1_id != 0 ) {
            cm_endOldGames( $player_1_id, true );
            }
        if( $player_2_id != 0 ) {
            cm_endOldGames( $player_2_id, true );
            }
        }


    
    $query = "UPDATE $tableNamePrefix"."games ".
        "SET last_action_time = CURRENT_TIMESTAMP ".
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";

    cm_queryDatabase( $query );

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=1" );
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
        // during tournament, don't show same-stake games that we're
        // blocked from playing
        "AND dollar_amount != $dollar_amount ".
        "AND started = 0 ".
        "AND amulet_game = 0 ".
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
    $active_user_count = cm_countUsersTime( '0 0:02:00' );
    
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT semaphore_key, started, dollar_amount ".
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
    $dollar_amount = mysql_result( $result, 0, "dollar_amount" );
    
    if( $started != 0 ) {
        echo "started\n";
        echo "$dollar_amount\n";
        echo "$otherGameList\n";
        echo "$active_user_count\n";
        echo "OK";
        return;
        }
    else {
        global $waitTimeout;

        semLock( $semaphore_key );

        cm_queryDatabase( "COMMIT;" );
        
        $result = semWait( $semaphore_key, $waitTimeout );

        
        $otherGameList = cm_getOtherGameList( $user_id );
        $active_user_count = cm_countUsersTime( '0 0:02:00' );

        
        if( $result == -2 ) {
            echo "waiting\n";
            echo "$dollar_amount\n";
            echo "$otherGameList\n";
            echo "$active_user_count\n";
            echo "OK";
            return;
            }
        else {


            $query = "SELECT dollar_amount, player_1_id, player_2_id ".
                "FROM $tableNamePrefix"."games ".
                "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";
            
            $result = cm_queryDatabase( $query );


            $numRows = mysql_numrows( $result );

    
            if( $numRows == 0 ) {
                cm_log( "Waiting on game that doesn't exist to start" );
                cm_transactionDeny();
                return;
                }

            $player_1_id = mysql_result( $result, 0, "player_1_id" );
            $player_2_id = mysql_result( $result, 0, "player_2_id" );
            $dollar_amount = mysql_result( $result, 0, "dollar_amount" );


            if( $player_1_id == 0 || $player_2_id == 0 ) {
                // sem signaled, but opponent still not there?
                echo "waiting\n";
                echo "$dollar_amount\n";
                echo "$otherGameList\n";
                echo "$active_user_count\n";
                echo "OK";
                return;
                }
            else {
                // opponent present
                echo "started\n";
                echo "$dollar_amount\n";
                echo "$otherGameList\n";
                echo "$active_user_count\n";
                echo "OK";
                return;
                }
            
            }
        }
    }



function cm_getAmuletTGAURL( $amulet_id ) {
    global $amulets;
    
    return $amulets[ $amulet_id ][1];
    }

function cm_getAmuletPNGURL( $amulet_id ) {
    global $amulets;
    
    return $amulets[ $amulet_id ][2];
    }

function cm_getAmuletEndTime( $amulet_id ) {
    global $amulets;
    
    return $amulets[ $amulet_id ][0];
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
    
    $query = "SELECT last_buy_in, last_pay_out, last_vs_one_coins ".
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
    $last_vs_one_coins = mysql_result( $result, 0, "last_vs_one_coins" );

    
    cm_queryDatabase( "COMMIT;" );

    
    echo "$last_buy_in\n";
    echo "$last_pay_out\n";
    echo "$last_vs_one_coins\n";
    echo "OK";
    }



function cm_listGames() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    global $areGamesAllowed, $minGameStakes, $maxGameStakes, $amuletMaxStake;

    // active users in last 2 minutes
    $active_user_count = cm_countUsersTime( '0 0:02:00' );
    
    
    if( !$areGamesAllowed ) {
        echo "0\n";
        echo "$active_user_count\n";
        echo "$minGameStakes\n";
        echo "$maxGameStakes\n";
        echo "$amuletMaxStake\n";
        echo "0#0\n";
        echo "OK";
        return;
        }
    else {
        echo "1\n";
        echo "$active_user_count\n";
        echo "$minGameStakes\n";
        echo "$maxGameStakes\n";
        echo "$amuletMaxStake\n";
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


    $tournamentStakeForUser = $tournamentStake;
    
    
    if( $tournamentStake != -1 ) {
        if( ! cm_isUserInTournament( $user_id ) ) {
            $tournamentStakeForUser = -1;
            }
        }
    
    $skipAdjust = 0;

    $ignoreClause = "";
    if( $tournamentStake != -1 ) {
        // leave room for tournament info/stake which appears on every page
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
    
    $query = "SELECT dollar_amount, game_type FROM $tableNamePrefix"."games ".
        "WHERE player_2_id = 0 AND started = 0 ".
        "AND amulet_game = 0 $ignoreClause".
        "ORDER BY dollar_amount ASC ".
        "LIMIT $skip, $query_limit;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );


    
    
    
    if( $numRows == 0 && $skip != 0 ) {
        // gone off end (maybe game list has changed since player loaded
        // last page)

        // wrap around
        $skip = 0;

        $query = "SELECT dollar_amount, game_type ".
            "FROM $tableNamePrefix"."games ".
            "WHERE player_2_id = 0 AND started = 0 $ignoreClause".
            "ORDER BY dollar_amount ASC ".
            "LIMIT $skip, $query_limit;";
        
        $result = cm_queryDatabase( $query );
        
        $numRows = mysql_numrows( $result );
        }
    

    if( $tournamentStakeForUser != -1 ) {
        // always stick tournament stake at top of list
        echo "T$tournamentStake\n";
        }
    else if( $tournamentStake != -1 ) {
        // tournament running, but user not in it, stick info at top
        global $tournamentEntryFee;

        global $tournamentEndTime;
        $time = time();
        $endTime = strtotime( $tournamentEndTime );

        $secondsLeft = $endTime - $time;
        
        echo "T#$tournamentEntryFee#$tournamentStake#$secondsLeft\n";
        }

    
    if( $tournamentStake != -1 ) {
        // adjust back to the skip they requested (unless we wrapped around)
        if( $skip != 0 ) {
            $skip += $skipAdjust;
            }
        }

    

    for( $i=0; $i < $numRows && $i < $limit; $i++ ) {
        $dollar_amount = mysql_result( $result, $i, "dollar_amount" );

        $gameType = mysql_result( $result, $i, "game_type" );

        if( $gameType == 1 ) {
            echo "E";
            }
        
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




function cm_enterTournament() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    if( cm_handleRepeatResponse() ) {
        return;
        }


    global $areGamesAllowed;
    if( ! $areGamesAllowed ) {
        cm_log( "cm_enterTournament denied, areGamesAllowed is off" );
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
        cm_log( "cm_enterTournament denied, user $user_id not found" );
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
        cm_log( "cm_enterTournament denied, stale request sequence number" );
        cm_transactionDeny();
        return;
        }

    // two fractional digits here
    $fee_dollar_amount = cm_requestFilter(
        "fee_dollar_amount", "/[0-9]+[.][0-9][0-9]/i", "0.00" );

    if( ! cm_verifyCheckHMAC( $account_key, $request_sequence_number,
                              "fee_dollar_amount", $fee_dollar_amount ) ) {
        return;
        }


    global $tournamentEntryFee;
    
    if( cm_getTournamentStake() == -1 ||
        $fee_dollar_amount != $tournamentEntryFee ) {

        cm_log( "cm_enterTournament denied, no live tournament has ".
                "requested entry fee $fee_dollar_amount" );
        cm_transactionDeny();
        return;
        }

    
    $recomputedBalance = cm_recomputeBalanceFromHistory( $user_id );
    
    if( $dollar_balance != $recomputedBalance ) {

        $message = "User $user_id table dollar balance = $dollar_balance, ".
            "but recomputed balance = $recomputedBalance, ".
            "blocking enter_tournament.";

        cm_log( $message );
        cm_informAdmin( $message );
        
        cm_transactionDeny();

        return;
        }

    global $tournamentEntryFee, $tournamentStake;
    
    if( $fee_dollar_amount + $tournamentStake > $dollar_balance ) {
        cm_log( "cm_enterTournament denied, ".
                "balance too low to cover entry fee plus first game stake" );
        cm_transactionDeny();
        return;
        }
    

    if( cm_isUserInTournament( $user_id ) ) {
        cm_log( "cm_enterTournament denied, ".
                "user is already in the tournament" );
        cm_transactionDeny();
        return;
        }
    

    
    // if we got here, we've got a valid request
        
    cm_endOldGames( $user_id );




    $response = "OK";

    $query = "UPDATE $tableNamePrefix"."users ".
        "SET dollar_balance = dollar_balance - $fee_dollar_amount, ".
        "last_request_response = '$response' ".
        "WHERE user_id = '$user_id';";
    cm_queryDatabase( $query );


    $query = "UPDATE $tableNamePrefix"."server_globals ".
        "SET house_dollar_balance = ".
        "  house_dollar_balance + $fee_dollar_amount;";
    cm_queryDatabase( $query );

    
    cm_addUserToTournament( $user_id );

    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT =1;" );


    echo $response;
    }




function cm_dropAmulet() {
    if( ! cm_verifyTransaction() ) {
        return;
        }

    $user_id = cm_getUserID();

    cm_queryDatabase( "SET AUTOCOMMIT=0" );

    global $tableNamePrefix;

    // how many live games are running?
    $liveNonAmuletGameCount =
        cm_countQuery(
            "games",
            "player_1_id != 0 AND player_2_id != 0 ".
            "AND amulet_game = 0" );
    
    $users_to_skip = $liveNonAmuletGameCount;
    if( $users_to_skip > 10 ) {
        $users_to_skip = 10;
        }
    

    // first, lock the amulet row
    // this prevents an intervening flush from subtracting penalty twice
    $query = "SELECT holding_user_id ".
            "FROM $tableNamePrefix"."amulets ".
            "WHERE holding_user_id = $user_id ".
            "FOR UPDATE;";

    $result = cm_queryDatabase( $query );


    $numRows = mysql_numrows( $result );

    if( $numRows == 1 ) {

        cm_subtractPointsForAmuletHoldTime( $user_id );
            
        $query = "UPDATE $tableNamePrefix"."amulets " .
            "SET holding_user_id = 0, ".
            "users_to_skip_on_drop = $users_to_skip ".
            "WHERE holding_user_id = $user_id;";
            
        cm_queryDatabase( $query );
        }

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    

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
        "game_square, ".
        "game_type, ".
        "round_number, ".
        "player_1_got_start, player_2_got_start,".
        "player_1_coins, player_2_coins, ".
        "player_1_pot_coins, player_2_pot_coins, ".
        "settled_pot_coins, ".
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
    $game_type = mysql_result( $result, 0, "game_type" );

    $round_number = mysql_result( $result, 0, "round_number" );
    
    $player_1_got_start = mysql_result( $result, 0, "player_1_got_start" );
    $player_2_got_start = mysql_result( $result, 0, "player_2_got_start" );

    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $settled_pot_coins = mysql_result( $result, 0, "settled_pot_coins" );

    $player_1_bet_made = mysql_result( $result, 0, "player_1_bet_made" );
    $player_2_bet_made = mysql_result( $result, 0, "player_2_bet_made" );

    $player_1_moves = mysql_result( $result, 0, "player_1_moves" );
    $player_2_moves = mysql_result( $result, 0, "player_2_moves" );


    $seconds_left = mysql_result( $result, 0, "seconds_left" );

    // give the player a grace period by giving them a shorter deadline
    // than what is actually enforced
    global $moveLimitGraceSeconds;
    $seconds_left -= $moveLimitGraceSeconds;



    
    // flag game to show that we got the start state
    if( $player_1_id == $user_id && !$player_1_got_start ) {
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_1_got_start = 1 WHERE player_1_id = '$user_id';";
        cm_queryDatabase( $query );
        }
    else  if( $player_2_id == $user_id && !$player_2_got_start ) {
        
        $query = "UPDATE $tableNamePrefix"."games ".
            "SET player_2_got_start = 1 WHERE player_2_id = '$user_id';";
        cm_queryDatabase( $query );
        }
    
    

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

    $your_bet_made = $player_1_bet_made;
    $their_bet_made = $player_2_bet_made;
    
    
    if( $player_2_id == $user_id ) {

        $your_coins = $player_2_coins;
        $your_pot_coins = $player_2_pot_coins;
        
        $their_coins = $player_1_coins;
        $their_pot_coins = $player_1_pot_coins;

        $game_square = cm_swapSquare( $game_square );

        $your_moves = $player_2_moves;
        $their_moves = $player_1_moves;

        $your_bet_made = $player_2_bet_made;
        $their_bet_made = $player_1_bet_made;
        }



    if( $their_moves != "#" ) {
            
        $their_split_moves = preg_split( "/#/", $their_moves );

        $theirNumMoves = count( $their_split_moves );


        $yourNumMoves = 0;

        if( $your_moves != "#" ) {
            $your_split_moves = preg_split( "/#/", $your_moves );

            $yourNumMoves = count( $your_split_moves );
            }
        
        
        if( $user_id == $player_1_id ) {
            
            // row 5 is our column 0
            for( $i=0; $i<$theirNumMoves; $i++ ) {
                $their_split_moves[$i] = 5 - $their_split_moves[$i];
                }
            }

        
        $reveal = false;

        if( $theirNumMoves == 7 &&
            $player_1_pot_coins == $player_2_pot_coins &&
            $player_1_bet_made && $player_2_bet_made ) {

            if( $yourNumMoves == 7 ) {
                // game round done
                $reveal = true;
                }
            }

        
        if( $inHideOpponentSecretMoves && ! $reveal ) {

            $theirReveal = -1;
            if( $theirNumMoves > 6 && $yourNumMoves > 6 ) {
                // only show their reveal if our reveal is also in
                $theirReveal = $their_split_moves[6];
                }
            
            // replace moves they made for themselves with ?
            $theirRevealIndex = -1;
            $movesToScan = $theirNumMoves;
            if( $movesToScan > 6 ) {
                $movesToScan = 6;
                }
            for( $i=0; $i<$movesToScan; $i++ ) {
                if( $i % 2 == 0 &&
                    $theirReveal != $their_split_moves[$i] ) {

                    $their_split_moves[$i] = "?";
                    }
                else if( $theirReveal == $their_split_moves[$i] ) {
                    $theirRevealIndex = $i;
                    }
                }

            if( $theirRevealIndex != -1 ) {
                // reveal of an out-of-order move, other than their first
                // move

                // but game is done so order doesn't matter to players
                // anymore

                // swap this into the first move position
                $temp = $their_split_moves[0];
                $their_split_moves[0] = $their_split_moves[$theirRevealIndex];
                $their_split_moves[$theirRevealIndex] = $temp;

                // same for corresponding your_move pick

                $your_moves_split = preg_split( "/#/", $your_moves );

                $temp = $your_moves_split[1];
                $your_moves_split[1] =
                    $your_moves_split[$theirRevealIndex + 1];
                $your_moves_split[$theirRevealIndex + 1] = $temp;

                $your_moves = implode( "#", $your_moves_split );
                }
            }
        
        $their_moves = implode( "#", $their_split_moves );
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

        // default to showing only settled pot
        $your_coins += $your_pot_coins - $settled_pot_coins;
        $their_coins += $their_pot_coins - $settled_pot_coins;

        $your_pot_coins = $settled_pot_coins;
        $their_pot_coins = $settled_pot_coins;
        }

    if( $your_pot_coins < $their_pot_coins &&
        ! $your_bet_made ) {

        // bad timing here.
        // we're fetching game state when their bet has already come through
        // hide it
        $their_coins += $their_pot_coins - $settled_pot_coins;

        $their_pot_coins = $settled_pot_coins;
        }
    
    
    $leave_penalty = cm_getLeavePenalty( $round_number );

    if( $leave_penalty > $your_coins ) {
        $leave_penalty = $your_coins;
        }
    
    echo "$running\n";    
    echo "$game_square\n";
    echo "$game_type\n";
    echo "$your_coins\n";
    echo "$their_coins\n";
    echo "$your_pot_coins\n";
    echo "$their_pot_coins\n";
    echo "$your_moves\n";
    echo "$their_moves\n";
    echo "$seconds_left\n";
    echo "$leave_penalty\n";
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
        "player_1_coins, player_2_coins, ".
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

    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

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
        
        if( $player_1_coins > 0 &&
            $player_2_coins > 0 ) {

            // both players have made their moves (move lists matched)
            // AND
            // both player still have some coins, so betting round
            // can happen
        
            // get ready for next betting round
            $betsMade = 0;
            }
        
        // deadline extended for next betting round OR next move,
        // if betting is not possible
        
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
        "player_1_coins, player_2_coins, ".
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

    $player_1_coins = mysql_result( $result, 0, "player_1_coins" );
    $player_2_coins = mysql_result( $result, 0, "player_2_coins" );

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

        if( $player_1_coins > 0 &&
            $player_2_coins > 0 ) {

            // both players have made their moves (move lists matched)
            // AND
            // both player still have some coins, so betting round
            // can happen
            
            // get ready for next betting round
            $betsMade = 0;
            }
        
        // deadline extended for next betting round OR next move,
        // if betting is not possible

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

        if( $player_1_pot_coins == 0 && $player_2_pot_coins == 0
            &&
            ( $player_1_id == 0 || $player_2_id == 0 ) ) {

            // other player left, OK this bet but do nothing with it
            echo "OK";
            return;
            }
        else {
            cm_log( "Making a bet when no bets allowed ".
                    "(both players have already placed matching bets ".
                    "or buy-ins)" );
            cm_transactionDeny();
            return;
            }
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


    $settledPotUpdate = "";

    if( $player_1_bet_made &&
        $player_2_bet_made &&
        $player_1_pot_coins == $player_2_pot_coins ) {

        // bets have been matched
        $settledPotUpdate =
            ", settled_pot_coins = $player_1_pot_coins ";
        
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
        $settledPotUpdate .
        "WHERE player_1_id = '$user_id' OR player_2_id = '$user_id';";


    if( $player_1_bet_made && $player_2_bet_made &&
        $player_1_pot_coins == $player_2_pot_coins &&
        strlen( $player_2_moves ) == 13 ) {

        cm_incrementStat( "reveal_count" );
        }
    
        

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



function cm_getAnte( $round_number ) {
    global $anteCoins, $anteIncrease;

    return $anteCoins + floor( $anteIncrease * ( $round_number - 1 ) );
    }



function cm_getLeavePenalty( $round_number ) {
    global $penaltyForLeaving, $penaltyForLeavingIncrease;

    return $penaltyForLeaving +
        floor( $penaltyForLeavingIncrease * ( $round_number - 1 ) );
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
        "round_number, ".
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

    $round_number = mysql_result( $result, 0, "round_number" );

    $player_1_pot_coins = mysql_result( $result, 0, "player_1_pot_coins" );
    $player_2_pot_coins = mysql_result( $result, 0, "player_2_pot_coins" );

    $semaphore_key = mysql_result( $result, 0, "semaphore_key" );


    if( $player_1_pot_coins == 0 && $player_2_pot_coins == 0
        &&
        ( $player_1_id == 0 || $player_2_id == 0 ) ) {

        // other player left, OK this fold but do nothing with it
        echo "OK";
        return;
        }
    
    
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

    cm_incrementStat( "fold_count" );

    $anteCoins = cm_getAnte( $round_number );
    
    if( $user_id == $player_1_id && $player_1_pot_coins <= $anteCoins
        ||
        $user_id == $player_2_id && $player_2_pot_coins <= $anteCoins ) {

        cm_incrementStat( "one_ante_fold_count" );
        }
    
    cm_makeRoundLoser( $game_id, $user_id );

    
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
function cm_makeRoundLoser( $inGameID, $inLoserID, $inTie = false ) {
    global $tableNamePrefix;


    cm_queryDatabase( "SET AUTOCOMMIT=0" );
    
    $query = "SELECT player_1_id, player_2_id,".
        "player_1_coins, player_2_coins, ".
        "player_1_bet_made, player_2_bet_made, ".
        "player_1_pot_coins, player_2_pot_coins ".
        "FROM $tableNamePrefix"."games ".
        "WHERE ( player_1_id = '$inLoserID' OR player_2_id = '$inLoserID' )".
        "     AND game_id = '$inGameID' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );
    
    $numRows = mysql_numrows( $result );

    if( $numRows != 1 ) {
        cm_log( "Making a round loser for a game that doesn't exist" );

        // don't count this as a transaction error
        // game could have been ended by one player leaving out from under
        // us
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
    $settled_pot_coins = 0;
    
    
    global $moveTimeLimit;

    
    $query = "UPDATE $tableNamePrefix"."games ".
        "SET player_1_coins = '$player_1_coins', ".
        "player_2_coins = '$player_2_coins', ".
        "player_1_bet_made = '$player_1_bet_made', ".
        "player_2_bet_made = '$player_2_bet_made', ".
        "player_1_pot_coins = '$player_1_pot_coins', ".
        "player_2_pot_coins = '$player_2_pot_coins', ".
        "settled_pot_coins = 'settled_pot_coins', ".
        "move_deadline = ADDTIME( CURRENT_TIMESTAMP, '$moveTimeLimit' ) ".
        "WHERE player_1_id = '$inLoserID' OR player_2_id = '$inLoserID';";
    

    $result = cm_queryDatabase( $query );
    }


function cm_getCellValue( $inCell ) {
    return ceil( $inCell / 4 );
    }

function cm_getCellSuit( $inCell ) {
    return $inCell % 4;
    }


function cm_allEqual( $inArray ) {
    return ( count( array_unique( $inArray ) ) === 1 );
    }

// cells scored in range 1..36
// these are converted to suited cells from 1..9
// then assigned a score based on hand ranking
function cm_computeSuitedScore( $inScoredCellList ) {

    $score = 0;

    $values = array_map( "cm_getCellValue", $inScoredCellList );
    $suits = array_map( "cm_getCellSuit", $inScoredCellList );


    sort( $values );
    

    $flush = false;
    
    if( cm_allEqual( $suits ) ) {
        $flush = true;
        }

    $straight = false;

    if( $values[0] + 1 == $values[1] &&
        $values[1] + 1 == $values[2] ) {
        $straight = true;
        }

    // regular straight:  score in [10001 .. 10009]
    if( $straight && ! $flush ) {
        // highest straight wins
        return 10000 + $values[0];
        }

    // regular flush:  score in [20321 .. 20987]
    if( $flush && ! $straight ) {
        return 20000 +
            // highest card wins on two flushes
            // with next card breaking a tie
            100 * $values[2] +
            10 * $values[1] +
            $values[0];
        }

    // set (3-of-kind): score in [30001 .. 30009]
    if( cm_allEqual( $values ) ) {
        // three of a kind
        return 30000 + $values[0];
        }

    // Straigh flush:  score in [40001 .. 40009]
    if( $flush && $straight ) {
        // highest straight flush wins
        return 40000 + $values[0];
        }
    
    // now detect a pair
    // score in [1002 .. 9008]
    if( $values[0] == $values[1]
        ||
        $values[1] == $values[2] ) {

        $pairValue = $values[1];
        $otherValue;

        if( $values[0] != $pairValue ) {
            $otherValue = $values[0];
            }
        else {
            $otherValue = $values[2];
            }

        return 1000 * $pairValue + $otherValue;
        }

    // high cards and kickers
    // score in [421 .. 986]
    return 100 * $values[2] + 10 * $values[1] + $values[0];
    }



// returns losing user_id or -1 on tie
function cm_computeLoser( $game_square, $game_type, $player_1_id, $player_2_id,
                          $player_1_moves, $player_2_moves ) {

    $game_cells = preg_split( "/#/", $game_square );
        
    $player_1_move_list = preg_split( "/#/", $player_1_moves );
    $player_2_move_list = preg_split( "/#/", $player_2_moves );
    
    // compute score to find out who won
    $p1Score = 0;
    $p2Score = 0;

    $p1CellList = array();
    $p2CellList = array();
        
    for( $i=0; $i<3; $i++ ) {
        $p1SelfChoice = $player_1_move_list[ $i * 2 ];
        $p1OtherChoice = $player_1_move_list[ $i * 2 + 1 ];
        
        $p2SelfChoice = 5 - $player_2_move_list[ $i * 2 ];
        $p2OtherChoice = 5 - $player_2_move_list[ $i * 2 + 1 ];
        
        $p1CellList[] = $game_cells[ $p2OtherChoice * 6 + $p1SelfChoice ];
        $p2CellList[] = $game_cells[ $p2SelfChoice * 6 + $p1OtherChoice ];
        }


    if( $game_type == 0 ) {
        $p1Score = array_sum( $p1CellList );
        $p2Score = array_sum( $p2CellList );
        }
    else if( $game_type == 1 ) {
        $p1Score = cm_computeSuitedScore( $p1CellList );
        $p2Score = cm_computeSuitedScore( $p2CellList );
        }

    cm_log( "Score:  p1 = $p1Score, p2 = $p2Score" );
    
    
        
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
        "game_type, ".
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
    $game_type = mysql_result( $result, 0, "game_type" );
    
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

    // watch for case where other player left
    if( $player_1_id != 0 && $player_2_id != 0 &&
        $player_1_ended_round == 1 && $player_2_ended_round == 1 ) {

        $loserID = cm_computeLoser( $game_square, $game_type,
                                    $player_1_id, $player_2_id,
                                    $player_1_moves, $player_2_moves );
        $tie = false;

        if( $loserID == -1 ) {
            $loserID = $player_1_id;
            $tie = true;
            }

        cm_log( "Making $loserID the loser (tie = $tie)for game $game_id" );
        cm_makeRoundLoser( $game_id, $loserID, $tie );
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
        "round_number, ".
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

    $round_number = mysql_result( $result, 0, "round_number" );
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

        $settled_pot_coins = 0;
        
        
        if( $player_1_id != 0 && $player_2_id != 0 &&
            $player_1_coins > 0 && $player_2_coins > 0 ) {

            // start a new game

            $game_square = cm_getNewSquare();

            $round_number ++;
            
            $anteCoins = cm_getAnte( $round_number );
            
            // ante shrinks if one player cannot afford it
            if( $player_1_coins < $anteCoins ) {
                $anteCoins = $player_1_coins;
                }
            if( $player_2_coins < $anteCoins ) {
                $anteCoins = $player_2_coins;
                }
            
            $player_1_coins -= $anteCoins;
            $player_2_coins -= $anteCoins;
            
            $player_1_pot_coins = $anteCoins;
            $player_2_pot_coins = $anteCoins;

            $settled_pot_coins = $anteCoins;
            
            $player_1_bet_made = 1;
            $player_2_bet_made = 1;

            cm_incrementStat( "round_count" );
            }
        else {
            // other player has left
            // OR
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
            "settled_pot_coins = '$settled_pot_coins', ".
            "player_1_moves = '#', ".
            "player_2_moves = '#', ".
            "player_1_ended_round = '0', ".
            "player_2_ended_round = '0', ".
            "round_number = $round_number, ".
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

    $houseBalanceRecomputed = cm_formatBalanceForDisplay(
        cm_recomputeHouseBalanceFromHistory() );
    

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
        "Live buy-ins: $totalLiveBuyInString<br> ".
        "Deposits: $totalDepositsString | ".
        "Withdrawals: $totalWithdrawalsString<br>".
        "House balance: $houseBalanceString | ".
        "House withdrawals: $houseWithdrawalsString | ".
        "Leaked: $leakedMoneyString<br>".
        "Recomputed House balance: $houseBalanceRecomputed <br>".
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
    
    cm_showData();
    }



function cm_makeBatchAccounts() {
    cm_checkPassword( "make_batch_accounts" );

    global $tableNamePrefix;

    $email_prefix =
        cm_requestFilter( "email_prefix",
                          "/[A-Z0-9._%+-]+/i", "" );


    echo "[<a href=\"server.php?action=show_data" .
        "\">Main</a>]<br><br><br>";

    
    if( $email_prefix == "" ) {
        echo "Bad email prefix";
        return;
        }


    $num_accounts = cm_requestFilter( "num_accounts", "/[0-9]+/", "0" );
    $index_skip = cm_requestFilter( "index_skip", "/[0-9]+/", "0" );
    $confirm = cm_requestFilter( "confirm", "/[1]/", "0" );

    if( $num_accounts <= 0 ) {
        echo "Num accounts must be positive.";
        return;
        }

    if( $confirm == 0 ) {
        echo "Must check confirmation box.";
        return;
        }

    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/^[0-9]*([.][0-9][0-9])?/i", "0.00" );


    echo "Making $num_accounts accounts with \$$dollar_amount each ".
        "and email prefix <b>$email_prefix</b>:<br><br><pre>\n\n";


    $numMade = 0;
    $failureList = array();
    
    for( $i=0; $i<$num_accounts; $i++ ) {

        $index = $i + $index_skip;
        
        $email = $email_prefix ."_$index@cordialminuet.com";
        
    
        $salt = 0;
        $account_key = cm_generateAccountKey( $email, $salt );

        $random_name = cm_generateRandomName();

        global $eloStartingRating;

        $num_deposits = 0;

        if( $dollar_amount > 0 ) {
            $num_deposits = 1;
            }
        
        // user_id auto-assigned (auto-increment)
        $query = "INSERT INTO $tableNamePrefix". "users SET ".
            "account_key = '$account_key', ".
            "email = '$email', ".
            "random_name = '$random_name', ".
            "dollar_balance = $dollar_amount, ".
            "num_deposits = $num_deposits, ".
            "total_deposits = $dollar_amount, ".
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

        // handle insert error ourselves
        $result = mysql_query( $query );

        if( $result ) {
            $user_id = mysql_insert_id();

            if( $num_deposits == 1 ) {
                
                $query = "INSERT INTO $tableNamePrefix"."deposits ".
                    "SET user_id = '$user_id', ".
                    "deposit_time = CURRENT_TIMESTAMP, ".
                    "dollar_amount = '$dollar_amount', ".
                    "fee = '0', processing_id = 'in_person'; ";
                
                cm_queryDatabase( $query );
                }

            $key_chunks = str_split( $account_key, 5 );
            
            $keyToPrint = implode( "-", $key_chunks );
            echo "Email: $email    Key: $keyToPrint\n";

            $numMade++;
            }
        else {
            $failureList[] = "Email: $email    ".
                "Key: $account_key    RandomName: $random_name";
            }
        }

    echo "\n\n</pre><br><br>Successfully made $numMade accounts.<br><br>";

    if( $numMade != $num_accounts ) {
        echo "Failed to make:<br><br>";

        foreach( $failureList as $row ) {
            echo "$row<br>";
            }
        }

    echo "<br><br>Done.";
    }





function cm_makeCoupons() {
    cm_checkPassword( "make_coupons" );

    global $tableNamePrefix;

    $coupon_tag =
        cm_requestFilter( "coupon_tag",
                          "/[A-Z0-9_]+/i", "" );


    echo "[<a href=\"server.php?action=show_data" .
        "\">Main</a>]<br><br><br>";

    
    if( $coupon_tag == "" ) {
        echo "Bad coupon tag";
        return;
        }


    $num_coupons = cm_requestFilter( "num_coupons", "/[0-9]+/", "0" );
    $expire_days = cm_requestFilter( "expire_days", "/[0-9-]+/", "-1" );
    $confirm = cm_requestFilter( "confirm", "/[1]/", "0" );

    if( $num_coupons <= 0 ) {
        echo "Num coupons must be positive.";
        return;
        }

    if( $confirm == 0 ) {
        echo "Must check confirmation box.";
        return;
        }

    $dollar_amount = cm_requestFilter(
        "dollar_amount", "/^[0-9]*([.][0-9][0-9])?/i", "0.00" );


    echo "Making $num_coupons coupons with \$$dollar_amount each ".
        "and tag <b>$coupon_tag</b>:<br><br><pre>\n\n";


    $numMade = 0;
    $failureList = array();

    $salt = 341347;
    
    for( $i=0; $i<$num_coupons; $i++ ) {

        
        $coupon_code = cm_generateAccountKey( $coupon_tag, $i + $salt,
                                              15 );
        
        // user_id auto-assigned (auto-increment)
        $query = "INSERT INTO $tableNamePrefix". "coupons SET ".
            "coupon_code = '$coupon_code', ".
            "coupon_tag = '$coupon_tag', ".
            "creation_time = CURRENT_TIMESTAMP, ".
            // will be earlier than creation time if never expires
            "expire_time = ".
            "  DATE_ADD( CURRENT_TIMESTAMP, INTERVAL $expire_days DAY ), ".
            "dollar_amount = '$dollar_amount', ".
            "redeemed_by_user_id = 0, ".
            "redeemed_time = CURRENT_TIMESTAMP;";
        
        // handle insert error ourselves
        $result = mysql_query( $query );

        if( $result ) {

            $key_chunks = str_split( $coupon_code, 5 );
            
            $keyToPrint = implode( "-", $key_chunks );
            echo "$keyToPrint\n";

            $numMade++;
            }
        else {
            $failureList[] = "Code: $coupon_code";
            }
        }

    echo "\n\n</pre><br><br>Successfully made $numMade coupons.<br><br>";

    if( $numMade != $num_coupons ) {
        echo "Failed to make:<br><br>";

        foreach( $failureList as $row ) {
            echo "$row<br>";
            }
        }

    echo "<br><br>Done.";
    }




function cm_redeemCouponFailEnd() {
    global $footer;
    
    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    eval( $footer );
    }


function cm_redeemCoupon() {
    global $header, $footer;
    
    eval( $header );
    
    $email = cm_requestFilter( "email", "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i", "" );
    $email_confirm = cm_requestFilter( "email_confirm",
                                       "/[A-Z0-9._%+-]+@[A-Z0-9.-]+/i", "" );

    if( $email == "" ) {
        echo "Email address not valid.";
        cm_redeemCouponFailEnd();
        return;
        }

    if( $email != $email_confirm ) {
        echo "Email confirmation does not match.";
        cm_redeemCouponFailEnd();
        return;
        }
    

    $coupon_code = cm_requestFilter( "coupon_code",
                                     "/[2-9A-HJ-NP-Z-]+/i", "" );

    if( $coupon_code == "" ) {
        echo "Coupon code not valid.";
        cm_redeemCouponFailEnd();
        return;
        }

    $code_split = preg_split( "/-/", $coupon_code );

    $coupon_code = implode( $code_split, "" );
    
    
    global $tableNamePrefix;

    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );
    
    $query = "SELECT coupon_tag, dollar_amount, ".
        "(redeemed_by_user_id = 0) as not_used,".
        "(expire_time > CURRENT_TIMESTAMP OR expire_time < creation_time ) ".
        "  as not_expired ".
        "FROM $tableNamePrefix"."coupons ".
        "WHERE coupon_code = '$coupon_code' FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    if( $numRows == 0 ) {
        echo "Coupon code not found.";
        cm_redeemCouponFailEnd();
        return;
        }

    $coupon_tag = mysql_result( $result, 0, "coupon_tag" );
    $dollar_amount = mysql_result( $result, 0, "dollar_amount" );
    $not_used = mysql_result( $result, 0, "not_used" );
    $not_expired = mysql_result( $result, 0, "not_expired" );

    if( ! $not_used ) {
        echo "Coupon code already redeemed.";
        cm_redeemCouponFailEnd();
        return;
        }
    if( ! $not_expired ) {
        echo "Coupon code expired.";
        cm_redeemCouponFailEnd();
        return;
        }

    
    


    // valid!

    // check if user exists with this email

    //    $query = "SELECT user_id

    $salt = 0;
    $account_key = cm_generateAccountKey( $email, $salt );

    $random_name = cm_generateRandomName();

    global $eloStartingRating;
    
    // user_id auto-assigned (auto-increment)
    $query = "INSERT INTO $tableNamePrefix". "users SET ".
        "account_key = '$account_key', ".
        "email = '$email', ".
        "random_name = '$random_name', ".
        "dollar_balance = '$dollar_amount', ".
        "num_deposits = 1, ".
        "total_deposits = '$dollar_amount', ".
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


    // handle INSERT error ourselves  (incase account with this email exists
    $result = mysql_query( $query );

    $user_id = 0;
    
    if( $result ) {
        $user_id = mysql_insert_id();
        }
    else {
        // already exists
        $query = "SELECT user_id, account_key, random_name ".
            "FROM $tableNamePrefix"."users ".
            "WHERE email='$email' FOR UPDATE;";
        $result = cm_queryDatabase( $query );

        if( mysql_numrows( $result ) == 0 ) {
            echo "Failed to add funds to existing account.";
            cm_redeemCouponFailEnd();
            return;
            }
        $user_id = mysql_result( $result, 0, "user_id" );
        $account_key = mysql_result( $result, 0, "account_key" );
        $random_name = mysql_result( $result, 0, "random_name" );

        $query = "UPDATE $tableNamePrefix". "users SET ".
            "dollar_balance = dollar_balance + '$dollar_amount', ".
            "num_deposits = num_deposits + 1, ".
            "total_deposits = total_deposits + '$dollar_amount' ".
            "WHERE user_id = $user_id;";
        cm_queryDatabase( $query );
        }
    
    $query = "INSERT INTO $tableNamePrefix"."deposits ".
        "SET user_id = '$user_id', ".
        "deposit_time = CURRENT_TIMESTAMP, ".
        "dollar_amount = '$dollar_amount', ".
        "fee = '0', processing_id = 'coupon_".$coupon_tag."_$coupon_code'; ";
    
    cm_queryDatabase( $query );


    $query = "UPDATE $tableNamePrefix"."coupons ".
        "SET redeemed_by_user_id = $user_id, ".
        "redeemed_time = CURRENT_TIMESTAMP ".
        "WHERE coupon_code = '$coupon_code';";

    cm_queryDatabase( $query );
    


    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );

    $netString = cm_formatBalanceForDisplay( $dollar_amount );


    $account_key_chunks = str_split( $account_key, 5 );

    $account_key = implode( "-", $account_key_chunks );
    
    
    global $upgradeURL;
    

    $message =
        "You redeemed a coupon for $netString ".
        "into your CORDIAL MINUET account.\n\n".
        "You can download the game here:\n".
        "$upgradeURL\n\n".
        "Here are your account details:\n\n".
        "Email:  $email\n".
        "Account Key:  $account_key\n\n".
        "Alias:  $random_name\n\n".
        "Please save these for future reference.  These account details ".
        "are saved locally by your game client after you log in the ".
        "first time, but you may need to enter ".
        "them again if you are playing the game from a different computer ".
        "or fresh install in the future.\n\n\n".
        "Enjoy the game!\n".
        "Jason\n\n";
            
    
    cm_mail( $email, "Cordial Minuet Coupon Account Information",
             $message );
    

    echo "Your coupon has been redeemed.<br><br>";

    echo "Account details have been emailed to you at <b>$email</b><br><br>";

    echo "You will need these details to log into the game and ".
        "access your funds.<br><br>Please check your spam folder.";
    
    
    eval( $footer );
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

        <td>
        Make account batch:<br>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="make_batch_accounts">
                 Number of Accounts:
    <INPUT TYPE="text" MAXLENGTH=10 SIZE=4 NAME="num_accounts" VALUE="0"><br>
                 Email Prefix:
    <INPUT TYPE="text" MAXLENGTH=40 SIZE=10 NAME="email_prefix">_N@cordialminuet.com<br>
                 Index skip:
    <INPUT TYPE="text" MAXLENGTH=10 SIZE=4 NAME="index_skip" VALUE="0"><br>
             Initial deposit:  
    $<INPUT TYPE="text" MAXLENGTH=10 SIZE=10 NAME="dollar_amount" VALUE="0.00"><br>
             Confirm:
    <INPUT TYPE="checkbox" NAME="confirm" VALUE=1><br>
    <INPUT TYPE="Submit" VALUE="Create Batch">
    </FORM>
        </td>
        <td>
        Make Coupons:<br>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="make_coupons">
                 Number of Coupons:
    <INPUT TYPE="text" MAXLENGTH=10 SIZE=4 NAME="num_coupons" VALUE="0"><br>
                 Coupon Tag:
    <INPUT TYPE="text" MAXLENGTH=40 SIZE=10 NAME="coupon_tag"><br>
                 Expire in:
    <INPUT TYPE="text" MAXLENGTH=10 SIZE=4 NAME="expire_days" VALUE="-1">Days (-1 = never)<br>
             Initial deposit:  
    $<INPUT TYPE="text" MAXLENGTH=10 SIZE=10 NAME="dollar_amount" VALUE="0.00"><br>
             Confirm:
    <INPUT TYPE="checkbox" NAME="confirm" VALUE=1><br>
    <INPUT TYPE="Submit" VALUE="Create Coupons">
    </FORM>
        </td>     
             
<?php


    if( cm_isVsOneRunning() != "" ) {
?>
        <td>
        DEPRECATED<br>
        Award Cabal Contest Points:<br>
            <FORM ACTION="server.php" METHOD="post">
    <INPUT TYPE="hidden" NAME="action" VALUE="award_cabal_points">
            Points:
    <INPUT TYPE="text" MAXLENGTH=10 SIZE=10 NAME="points" VALUE="50"><br>
             Confirm:
    <INPUT TYPE="checkbox" NAME="confirm" VALUE=1><br>
    <INPUT TYPE="Submit" VALUE="Give Points">
    </FORM>
        </td>
<?php
        
        }
    

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
        "      DATE_SUB( CURRENT_TIMESTAMP, INTERVAL $day_limit DAY ) ".
        "      AND email NOT LIKE '%@cordialminuet.com';";
        
    $result = cm_queryDatabase( $query );

    $count = mysql_result( $result, 0, 0 );
    $emailList = mysql_result( $result, 0, 1 );


    echo "$count users played the game ".
        "(ignoring @cordialminuet.com test accounts) in ".
        "the past $day_limit days:<br><br>";

    echo "$emailList";

    echo "<br><br>END";
    }






function cm_showStats() {
    global $tableNamePrefix, $remoteIP;


    cm_checkPassword( "show_stats" );

    cm_generateHeader();

    $query = "SELECT MAX(update_time) as last_update, ".
        "tournament_code_name, SUM( entry_fee ) as net_fees, ".
        "SUM( prize ) as net_prizes, ".
        "SUM( entry_fee ) - SUM( prize ) as house_profit  ".
        "FROM $tableNamePrefix"."tournament_stats ".
        "GROUP BY tournament_code_name ORDER BY last_update DESC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    echo "House Net for Tournaments:";
    echo "<br><table border=1 cellpadding=10>\n";

    for( $i=0; $i<$numRows; $i++ ) {
        
        $update_time = mysql_result( $result, $i, "last_update" );
        $code_name = mysql_result( $result, $i, "tournament_code_name" );
        $house_profit = mysql_result( $result, $i, "house_profit" );

        $net_fees = mysql_result( $result, $i, "net_fees" );
        $net_prizes = mysql_result( $result, $i, "net_prizes" );

        if( $net_prizes == 0 ) {
            // prizes not paid yet, estimate
            global $tournamentPrizePoolFraction;
            
            $house_profit = $net_fees * ( 1 - $tournamentPrizePoolFraction );
            }
        
        
        $house_profit = cm_formatBalanceForDisplay( $house_profit );

        if( $net_prizes == 0 ) {
            $house_profit = "Est. $house_profit";
            }

        global $fullServerURL;
        $link = "$fullServerURL?action=tournament_report".
            "&tournament_code_name=$code_name";
        
        
        echo "<tr><td>$update_time</td>".
            "<td><a href=$link>$code_name</code></td>".
            "<td align=right>$house_profit</td></tr>";
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
                             // array of true/false for each dollar field
                             $fourDigitsFlags,
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

    if( $linkPrefix != "" ) {
        echo "<td></td>";
        }
    
    echo "</tr>";
    
        
    for( $i=0; $i<$numRows; $i++ ) {
        echo "<tr>";

        foreach( $fieldNames as $field ) {
            $fieldValue = mysql_result( $result, $i, "$field" );

            $align = "left";
            
            if( in_array( $field, $dollarFieldNames ) ) {
                $index = array_search( $field, $dollarFieldNames );

                $fourDigits = $fourDigitsFlags[ $index ];
                $fieldValue = cm_formatBalanceForDisplay( $fieldValue,
                                                          $fourDigits );

                $align = "right";
                }
            
            echo "<td align=$align>$fieldValue</td>";
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
        "\">Main</a>] [<a href=server.php?action=show_detail".
	"&user_id=$user_id>Reload</a>]<br><br><br>";
     
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
                        array( ),
                        "Toggle Proof",
                        "server.php?action=toggle_card_proof&user_id=$user_id",
                        array( "fingerprint", "exp_date" ) );

                                                     
    echo "<br><HR><br>Deposits:<br>";
    cm_formatDataTable( "deposits", "WHERE user_id = '$user_id'",
                        array( "deposit_time", "processing_id",
                               "dollar_amount", "fee" ),
                        array( "Date", "ID", "Total Amount", "Fee" ),
                        array( "dollar_amount", "fee" ),
                        array( false, false ) );

    
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
                        array( "dollar_amount", "fee" ),
                        array( false, false ) );
    

    echo "<br><HR><br>Game Ledger:<br>";
    cm_formatDataTable( "game_ledger", "WHERE user_id = '$user_id'",
                        array( "entry_time", "game_id",
                               "dollar_delta" ),
                        array( "Date", "Game ID", "Delta" ),
                        array( "dollar_delta" ),
                        array( true ) );


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




    echo "<br><HR><br>Tournaments:<br>";

    cm_formatDataTable( "tournament_stats", "WHERE user_id = '$user_id'",
                        array( "update_time", "tournament_code_name",
                               "entry_fee",
                               "num_games_finished", "net_dollars", "prize" ),
                        array( "Date", "Code name",
                               "Entry fee", "Games", "Profit",
                               "Prize" ),
                        array( "entry_fee", "net_dollars", "prize" ),
                        array( false, true, true ),
                        "Leaderboard",
                        "server.php?action=tournament_report",
                        array( "tournament_code_name" ) );
    
    }




function cm_getLeadersSkip() {
    global $leaderboardLimit;

    // at most a 12 digit int to avoid int overflow
    $skip = cm_requestFilter( "skip", "/\d{1,12}/", 0 );

    // round to closest multiple of leaderboardLimit
    // thus, can't bypass caching by skipping ahead 1
    $skip = $leaderboardLimit * floor( $skip / $leaderboardLimit );

    return $skip;
    }



// note that $inWhereClause cannot exceed 255 characters (cache key is limited
// to 255 for indexing)
// $inColumnName must not exceed 25 characters.
function cm_leadersCached( $order_column_name, $inIsDollars = false,
                           $inWhereClause = "", $inUnlimited = false,
                           $inDayWindow = 0 ) {
    global $tableNamePrefix, $leaderboardUpdateInterval,
        $leaderHeader, $leaderFooter;

    $skip = cm_getLeadersSkip();

    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );

    // pack new $inDayWindow parameter into column_name in cache
    
    $query = "SELECT html_text, ".
        "  update_time, ".
        "  update_time < ".
        "   SUBTIME( CURRENT_TIMESTAMP, '$leaderboardUpdateInterval' ) ".
        "  AS stale, ".
        "TIMESTAMPDIFF( SECOND, update_time, CURRENT_TIMESTAMP ) ".
        "  AS seconds_old ".
        "FROM $tableNamePrefix"."leaderboard_cache ".
        "WHERE column_name = '$order_column_name$inDayWindow' AND ".
        "  where_clause = '$inWhereClause' AND skip_number = $skip ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    $stale = 1;

    $html_text = '';
    if( $numRows > 0 ) {

        $stale = mysql_result( $result, 0, "stale" );

        if( !$stale ) {

            $html_text = mysql_result( $result, 0, "html_text" );
            }
        }


    if( $stale ) {
        ob_start();

        cm_leaders( $order_column_name, $inIsDollars,
                    $inWhereClause, $inUnlimited, $inDayWindow );

        $html_text = ob_get_contents();

        ob_end_clean();
        }
    
    


    // fixme:  show age of displayed text

    $ageString = "";
    
    if( $stale ) {
        $ageString = "0 seconds";
        }
    else {

        $seconds_old = mysql_result( $result, 0, "seconds_old");

        $ageString = cm_formatDuration( $seconds_old );
        }

    eval( $leaderHeader );

    
    echo "<center>Updated $ageString ago.</center><br>";
        
    echo $html_text;
    
    eval( $leaderFooter );

    
    if( $stale ) {

        // pack new $inDayWindow parameter into column_name in cache

        $query = "INSERT INTO $tableNamePrefix"."leaderboard_cache ".
            "SET column_name = '$order_column_name$inDayWindow', ".
            "  where_clause = '$inWhereClause', skip_number = $skip, ".
            "  update_time = CURRENT_TIMESTAMP,".
            "  html_text = '$html_text'".
            "ON DUPLICATE KEY UPDATE ".
            "  update_time = CURRENT_TIMESTAMP, ".
            "  html_text = '$html_text';";

        cm_queryDatabase( $query );
        }

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );
    }





// does not included header or footer
function cm_leaders( $order_column_name, $inIsDollars = false,
                     $inWhereClause = "", $inUnlimited = false,
                     // number of days for windowed profit query
                     $inDayWindow = 0 ) {

    global $tableNamePrefix, $leaderboardLimit;

    $skip = cm_getLeadersSkip();
    
    
    $limitClause = "LIMIT $skip, $leaderboardLimit";

    if( $inUnlimited ) {
        $limitClause = "";
        $skip = 0;
        }


    $nextPrevLinks = "";
    
    
    
    
    // h is a factor that hides effect of user's live game from leaderboards
    //   thus, you can't check for changes in the leaderboard to detect who
    //   you're playing against.
    // g is a factor that hides effect of a game that was just started on
    //   game count as it impacts leaderboards
    // Q is the factor in profit_ratio and win_loss that accounts for players
    //   with too few games played

    // the (SELECT alias) trick, found on StackOverflow, allows us to
    // reuse alias in other calculations.  Thus, we can define h, g, and Q
    // once and reuse them in more complicated calculations
    $query = "SELECT random_name, ".
        // h is non-zero (last_buy_in) only if player is still in game
        "if( last_pay_out = -1, last_buy_in, 0 ) AS h, ".
        // g is -1 if player is still in the game
        "if( last_buy_in != 0 AND last_pay_out = -1, -1, 0 ) as g, ".
        // Q factor is their average buy-in
        // we can use this to smooth the profit_ratio and win_loss toward 1 of
        // players who haven't played enough games
        // Because it's based on buy-in, it is not biased toward high
        // or low stakes players.
        "total_buy_in / ( games_started + (SELECT g) ) as Q, ".
        
        "dollar_balance + (SELECT h) AS dollar_balance, ".
        "(dollar_balance + (SELECT h) + total_withdrawals) - total_deposits ".
        " AS profit, ".
        "( total_buy_in - (SELECT h) + total_won - total_lost + (SELECT Q) ) ".
        "  / ( total_buy_in - (SELECT h) + (SELECT Q) )".
        " AS profit_ratio, ".
        // watch for divide by 0, or almost zero, that makes this
        // blow up for people who haven't played much
        "( total_won + (SELECT Q) ) / ".
        "( total_lost + (SELECT Q) ) ".
        " AS win_loss, ".
        "elo_rating, ".
        "games_started, ".

        "( SELECT SUM( dollar_delta ) ".
        "  FROM $tableNamePrefix"."game_ledger AS ledger ".
        "  WHERE users.user_id = ledger.user_id ".
        "  AND entry_time > ".
        "      DATE_SUB( CURRENT_TIMESTAMP, INTERVAL $inDayWindow DAY ) )".
        " AS days_profit, ".

        "( SELECT - SUM( dollar_delta ) ".
        "  FROM $tableNamePrefix"."game_ledger AS ledger ".
        "  WHERE users.user_id = ledger.user_id ".
        "  AND dollar_delta < 0 ".
        "  AND entry_time > ".
        "      DATE_SUB( CURRENT_TIMESTAMP, INTERVAL $inDayWindow DAY ) )".
        " AS days_buy_in, ".

        "( SELECT COUNT(*) ".
        "  FROM $tableNamePrefix"."game_ledger AS ledger ".
        "  WHERE users.user_id = ledger.user_id ".
        "  AND dollar_delta < 0 ".
        "  AND entry_time > ".
        "      DATE_SUB( CURRENT_TIMESTAMP, INTERVAL $inDayWindow DAY ) )".
        " AS days_games_started, ".

        // replicate Q factor inline here for days
        "( SELECT days_buy_in + days_profit +  ".
        "         days_buy_in / (days_games_started ) ) / ".
        "    ( SELECT days_buy_in + ".
        "         days_buy_in / (days_games_started ) ) ".
        " AS days_profit_ratio ".
        
        "FROM $tableNamePrefix"."users AS users ".
        "$inWhereClause ".
        "ORDER BY $order_column_name DESC ".
        "$limitClause;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );


    if( !$inUnlimited ) {
        global $action;

        
        $nextPrevLinks .= "<br><center>";
        
        $nextSkip = $skip + $leaderboardLimit;
        $prevSkip = $skip - $leaderboardLimit;
        if( $prevSkip < 0 ) {
            $prevSkip = 0;
            }

        if( $skip > 0 ) {
            $nextPrevLinks .= "[<a href=\"server.php?action=$action" .
                "&skip=$prevSkip\">".
                "Previous Page</a>]";
            }

        if( $numRows == $leaderboardLimit ) {    
            if( $skip > 0 ) {
                $nextPrevLinks .= " --- ";
                }
            
            $nextPrevLinks .= "[<a href=\"server.php?action=$action" .
                "&skip=$nextSkip\">".
                "Next Page</a>]";
            }
        
        $nextPrevLinks .= "</center><br>";
        }


    echo $nextPrevLinks;


    
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

        $rowNum = $i + 1 + $skip;
        
        echo "<tr><td align=right>$rowNum.</td><td>$random_name</td>".
            "<td align=right>$value</td></tr>";
        }
    echo "</table></center>";

    echo $nextPrevLinks;
    }



function cm_leadersGames() {
    cm_leadersCached( "games_started", false );
    }



function cm_leadersDollar() {
    cm_leadersCached( "dollar_balance", true );
    }



function cm_leadersProfit() {
    cm_leadersCached( "profit", true );
    }


function cm_leadersDayProfit() {
    cm_leadersCached( "days_profit", true, "", false, 1 );
    }

function cm_leadersWeekProfit() {
    cm_leadersCached( "days_profit", true, "", false, 7 );
    }

function cm_leadersMonthProfit() {
    cm_leadersCached( "days_profit", true, "", false, 30 );
    }

function cm_leadersYearProfit() {
    cm_leadersCached( "days_profit", true, "", false, 365 );
    }


function cm_leadersDayProfitRatio() {
    cm_leadersCached( "days_profit_ratio", false, "", false, 1 );
    }

function cm_leadersWeekProfitRatio() {
    cm_leadersCached( "days_profit_ratio", false, "", false, 7 );
    }

function cm_leadersMonthProfitRatio() {
    cm_leadersCached( "days_profit_ratio", false, "", false, 30 );
    }

function cm_leadersYearProfitRatio() {
    cm_leadersCached( "days_profit_ratio", false, "", false, 365 );
    }


function cm_leadersDayGames() {
    cm_leadersCached( "days_games_started", false, "", false, 1 );
    }

function cm_leadersWeekGames() {
    cm_leadersCached( "days_games_started", false, "", false, 7 );
    }

function cm_leadersMonthGames() {
    cm_leadersCached( "days_games_started", false, "", false, 30 );
    }

function cm_leadersYearGames() {
    cm_leadersCached( "days_games_started", false, "", false, 365 );
    }



function cm_leadersProfitRatio() {
    cm_leadersCached( "profit_ratio" );
    }


function cm_leadersWinLossRatio() {
    cm_leadersCached( "win_loss" );
    }


function cm_leadersElo() {
    global $eloProvisionalGames;
    cm_leadersCached( "elo_rating", false,
                "WHERE games_started > $eloProvisionalGames" );
    }


function cm_leadersEloProvisional() {
    global $eloProvisionalGames;
    cm_leadersCached( "elo_rating", false,
                "WHERE games_started <= $eloProvisionalGames" );
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
    $code_name = cm_requestFilter( "tournament_code_name",
                                   "/[A-Z0-9_]+/i", "" );

    global $tournamentCodeName;
    
    if( $code_name == "" ) {
        // default to current
        $code_name = $tournamentCodeName;
        }
    

    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );


    global $tournamentStartTime, $tournamentEndTime;
    
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

    $query = "SELECT COALESCE( SUM(prize), 0 ) ".
        "FROM $tableNamePrefix"."tournament_stats ".
        "WHERE tournament_code_name = '$code_name';";

    $result = cm_queryDatabase( $query );

    $prizesPaid = mysql_result( $result, 0, 0 );
    
    
    $query = "SELECT num_games_finished, prize, net_dollars, random_name ".
        "FROM $tableNamePrefix"."tournament_stats as stats ".
        "LEFT JOIN $tableNamePrefix"."users as users ".
        "     ON stats.user_id = users.user_id ".
        "WHERE tournament_code_name = '$code_name' ".
        // don't show house on leaderboard
        "AND stats.user_id != 0 ".
        "ORDER BY net_dollars DESC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );


    $prizes;
    $prizeTitle = "Prize<br>Paid";
    
    if( $prizesPaid == 0 ) {

        $scores = array();

        for( $i=0; $i<$numRows; $i++ ) {
            $net_dollars = mysql_result( $result, $i, "net_dollars" );

            $scores[$i] = $net_dollars;
            }
        
        $prizes = cm_tournamentGetPrizes( $numRows, $scores );
        $prizeTitle = "Tentative<br>Prize";
        }

    
    echo "<center><table border=0 cellspacing=10>";

    echo "<tr><td align=right></td>".
            "<td></td><td></td>".
            "<td valign=bottom align=right>Profit</td><td></td>".
            "<td valign=bottom align=right>Games<br>Played</td><td></td>".
            "<td valign=bottom align=right>$prizeTitle</td></tr>";

    echo "<tr><td colspan=8><hr></td></tr>";


    $places = array();

    for( $i=$numRows -1; $i>=0; $i-- ) {
        $net_dollars = mysql_result( $result, $i, "net_dollars" );

        $places[$i] = $i + 1;


        if( $i < $numRows -1 ) {

            if( $net_dollars ==
                mysql_result( $result, $i + 1, "net_dollars" ) ) {

                // tied with player below
                $places[$i] = $places[$i+1];
                }
            }
        }
        
        
    for( $i=0; $i<$numRows; $i++ ) {
        $random_name = mysql_result( $result, $i, "random_name" );
        $num_games_finished = mysql_result( $result, $i,
                                            "num_games_finished" );

        $net_dollars = mysql_result( $result, $i, "net_dollars" );

        $prize;
        if( $prizesPaid > 0 ) {
            $prize = mysql_result( $result, $i, "prize" );
            }
        else {
            $prize = $prizes[$i];
            }
        $prize = cm_formatBalanceForDisplay( $prize, true );
        
        $net_dollars = cm_formatBalanceForDisplay( $net_dollars, true );

        if( $i != 0 ) {
            echo "<tr><td colspan=8><hr></td></tr>";
            }

        $rowNum = $places[$i];
        
        echo "<tr><td align=right>$rowNum.</td>".
            "<td>$random_name</td><td></td>".
            "<td align=right>$net_dollars</td><td></td>".
            "<td align=right>$num_games_finished</td><td></td>".
            "<td align=right>$prize</td></tr>";

        $previousNetDollars = $net_dollars;
        $previousPrize = $prize;
        }
    echo "</table></center>";

    eval( $leaderFooter );
    }



function cm_listPastTournaments() {
    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );

    
    $query = "SELECT COUNT(*) as num_players, ".
        "MAX(update_time) as last_update, ".
        "tournament_code_name, MAX( entry_fee ) as fee, ".
        "SUM( prize ) as net_prizes ".
        "FROM $tableNamePrefix"."tournament_stats ".
        "GROUP BY tournament_code_name ORDER BY last_update DESC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );


    echo "<center><table border=0 cellspacing=10>";

    echo "<tr>".
            "<td></td><td></td><td></td><td></td>".
            "<td valign=bottom align=right>Entry<br>Fee</td><td></td>".
            "<td valign=bottom align=right>Player<br>Count</td><td></td>".
            "<td valign=bottom align=right>Prize<br>Pool</td></tr>";

    echo "<tr><td colspan=10><hr></td></tr>";

    for( $i=0; $i<$numRows; $i++ ) {
        
        $update_time = mysql_result( $result, $i, "last_update" );
        $code_name = mysql_result( $result, $i, "tournament_code_name" );
        
        $fee = mysql_result( $result, $i, "fee" );
        $net_prizes = mysql_result( $result, $i, "net_prizes" );
        $num_players = mysql_result( $result, $i, "num_players" );


        $fee = cm_formatBalanceForDisplay( $fee );
        if( $net_prizes == 0 ) {
            $net_prizes = "";
            }
        else {
            $net_prizes = cm_formatBalanceForDisplay( $net_prizes, true );
            }
            
        
        
        global $fullServerURL;
        $link = "$fullServerURL?action=tournament_report".
            "&tournament_code_name=$code_name";
        

        if( $i != 0 ) {
            echo "<tr><td colspan=10><hr></td></tr>";
            }
        
        echo "<tr><td>$update_time</td><td></td>".
            "<td><a href=$link>$code_name</code></td><td></td>".
            "<td align=right>$fee</td><td></td>".
            "<td align=right>$num_players</td><td></td>".
            "<td align=right>$net_prizes</td></tr>";
        }

    echo "</table>";
    }





function cm_listFutureTournaments() {
    global $tableNamePrefix, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );

    global $tournamentStartTime, $tournamentEndTime,
        $tournamentEntryFee, $tournamentCodeName;

    global $autoTournamentNamePrefix;

    if( strstr( $tournamentCodeName, $autoTournamentNamePrefix ) === FALSE ) {
        echo "<center>A manually-scheduled tournament is running.<br><br>".
            "The list of future automatic tournaments will be available<br>".
            "again when the manually-scheduled tournament is over.";
        return;
        }
        
    $firstName = $tournamentCodeName;

    $firstNumber;

    
    sscanf( $firstName,
            $autoTournamentNamePrefix ."_%d", $firstNumber );
    
    echo "<center><table border=0 cellspacing=10>";

    echo "<tr>".
            "<td valign=bottom>Starts In</td><td></td>".
            "<td valign=bottom align=right>Entry<br>Fee</td><td></td>".
            "<td valign=bottom align=right></td><td></td>".
        "<td valign=bottom align=right></td></tr>";

    echo "<tr><td colspan=8><hr></td></tr>";

    
    for( $i=0; $i<16; $i++ ) {

        $currentNumber = $firstNumber + $i;

        
        
        $currentName = $autoTournamentNamePrefix ."_$currentNumber";
        
        cm_checkForAutoTournament( $currentName );

    

        $time = time();
    
        $startTime = strtotime( $tournamentStartTime );

        $startsInSec = $startTime - $time;
        
        $startsIn = cm_formatDuration( $startsInSec );

        if( $startsInSec <= 0 ) {

            $endTime = strtotime( $tournamentEndTime );

            if( $time < $endTime ) {
                $startsIn = "Running Now";
                }
            else {
                // over completely
                $startsIn = "Ended";
                }
            }
        

        $fee = cm_formatBalanceForDisplay( $tournamentEntryFee );
            
        
        
        global $fullServerURL;
        $leaderLink = "<a href=$fullServerURL?action=tournament_report".
            "&tournament_code_name=$tournamentCodeName>Leaderboard</a>";

        $prizesLink = "<a href=$fullServerURL?action=tournament_prizes".
            "&tournament_code_name=$tournamentCodeName>Prizes</a>";

        if( $startsInSec > 0 ) {
            $leaderLink = "";
            }
        

        if( $i != 0 ) {
            echo "<tr><td colspan=8><hr></td></tr>";
            }
        
        echo "<tr><td>$startsIn</td><td></td>".
            "<td align=right>$fee</td><td></td>".
            "<td>$leaderLink</td><td></td><td>$prizesLink</td></tr>";
        }

    echo "</table>";
    }






// solves geometric series for (a) where r=$r and m=$m
// where the sum of the prizes is $P
// (a) is the minimum prize (the first term in the geometric series
// prize n = (a) * (1.5)^(n-1)
function cm_PminFormula( $P, $r, $m ) {
    // formula for P = Pmin *(1 - r^m)/(1-r)

    // thus Pmin = P * (1-r) / (1 - r^m )

    return $P * (1-$r) / ( 1 - pow( $r, $m ) );
    }


// returns an array of prizes, one for each player
// $inPlayerScores is an array of scores, one for each player, starting
// with first place (to deal with ties---tying players split total prize
// in their tie group)
function cm_tournamentGetPrizes( $inNumPlayers, $inPlayerScores ) {

    global $tournamentPrizePoolFraction, $tournamentMinPrize,
        $tournamentEntryFee, $tournamentPrizeRatio,
        $tournamentMinProfitForPrize;

    $fraction = $tournamentPrizePoolFraction;
    
    if( $inNumPlayers < 2 ) {
        $fraction = 1;
        }
    
    
    $prizePool = $inNumPlayers * $tournamentEntryFee *
        $fraction;

    
    // number of players that will get a non-zero prize
    $m = 1;
    $r = $tournamentPrizeRatio;

    // stop as soon as solving the geometric seriese results in a
    // minimum prize that's too low
    // OR
    // when we cross into the set of players that don't have profits high
    // high enough for a prize
    while( $m <= $inNumPlayers &&
           cm_PminFormula( $prizePool, $r, $m ) >= $tournamentMinPrize &&
           $inPlayerScores[ $m-1 ] >= $tournamentMinProfitForPrize ) {
        $m++;
        }

    $m--;

    if( $m == 0 ) {
        // no one got high enough score for prizes
        
        // given everyone entry fee back as prize

        if( $inNumPlayers == 0 ) {
            return array();
            }
        else {
            return array_fill( 0, $inNumPlayers, $tournamentEntryFee );
            }
        }
    

    $minPrize = 0;
    if( $m > 0 ) {
        $minPrize = cm_PminFormula( $prizePool, $r, $m );
        }
    
    $numWithoutPrizes = $inNumPlayers - $m;

    $result = array();

    $i = $inNumPlayers - 1;

    for( $j=0; $j<$numWithoutPrizes; $j++ ) {
        $result[$i] = 0;
        $i --;
        }

    $currentPrize = $minPrize;

    for( $j=0; $j<$m; $j++ ) {
        $result[$i] = $currentPrize;

        $currentPrize *= $r;
        
        $i --;
        }


    // now go back and deal with ties
    $tieStart = -1;
    $tieSum = 0;
    for( $i=1; $i<$inNumPlayers; $i++ ) {

        if( $inPlayerScores[$i] == $inPlayerScores[$i-1] ) {
            
            if( $tieStart == -1 ) {
                // start of a new tie group
                $tieStart = $i-1;
                $tieSum += $result[$i] + $result[$i-1];
                }
            else {
                // continuation of a tie group
                $tieSum += $result[$i];
                }
            }
        else if( $tieStart != -1 ) {
            // end of a tie group

            // round sum first
            $tieSum = number_format( $tieSum, 4 );
            
            $tiePrize = $tieSum / ( $i - $tieStart );

            // round down to nearest 10000th of dollar
            $tiePrize = floor( $tiePrize * 10000 ) / 10000;
            
            for( $j=$tieStart; $j<$i; $j++ ) {
                $result[$j] = $tiePrize;
                }
            $tieStart = -1;
            $tieSum = 0;
            }
        }
    
    if( $tieStart != -1 ) {
        // a tie that ran all the way to the end of the list

        // round sum first
        $tieSum = number_format( $tieSum, 4 );
        
        $tiePrize = $tieSum / ( $inNumPlayers - $tieStart );

        // round down to nearest 10000th of dollar
        $tiePrize = floor( $tiePrize * 10000 ) / 10000;
        
        for( $j=$tieStart; $j<$inNumPlayers; $j++ ) {
            $result[$j] = $tiePrize;
            }
        $tieStart = -1;
        $tieSum = 0;
        }
    
            

    return $result;
    }




function cm_tournamentPrizes() {
    $code_name = cm_requestFilter( "tournament_code_name",
                                   "/[A-Z0-9_]+/i", "" );

    global $tournamentCodeName;
    
    if( $code_name == "" ) {
        // default to current
        $code_name = $tournamentCodeName;
        }
    
    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;

    eval( $leaderHeader );
    
    if( $code_name != $tournamentCodeName ) {

        echo "<br><br><br><center>This tournament is not active.".
            "<br><br></center>";
        return;
        }

    $num_players = cm_requestFilter( "num_players", "/[0-9]+/i", "5" );

    $dummyScores = array();

    global $tournamentMinProfitForPrize;

    // create dummy scores evenly spaced out and all above min    
    for( $i=0; $i<$num_players; $i++ ) {
        $dummyScores[$i] =
            ( $num_players - $i ) + $tournamentMinProfitForPrize;
        }
    
    
    $prizes = cm_tournamentGetPrizes( $num_players, $dummyScores );

?>
            <center><FORM ACTION="server.php" METHOD="get">
    <INPUT TYPE="hidden" NAME="action" VALUE="tournament_prizes">
    <INPUT TYPE="hidden" NAME="tournament_code_name"
                 VALUE="<?php echo $code_name;?>">
    <INPUT TYPE="text" MAXLENGTH=9 SIZE=9 NAME="num_players"
             VALUE="<?php echo $num_players;?>"> Players 
    <INPUT TYPE="Submit" VALUE="Recompute">
    </FORM></center><br>
<?php
                 
    global $tournamentEntryFee, $tournamentPrizePoolFraction,
           $tournamentMinProfitForPrize;
    
    $entryFeeString = cm_formatBalanceForDisplay( $tournamentEntryFee );

    $fraction = $tournamentPrizePoolFraction;
    if( $num_players == 1 ) {
        $fraction = 1;
        }
    
    $prizePool =
        $tournamentEntryFee * $num_players * $fraction;
    $prizePool = cm_formatBalanceForDisplay( $prizePool );

    echo "<center><table border=0 cellpadding=10>";
    
    echo "<tr><td align=right>Entry fee</td><td>=</td>".
        "<td align=right>$entryFeeString</td></tr>";

    echo "<tr><td align=right>Total prize pool</td><td>=</td>".
        "<td align=right>$prizePool</td></tr>";

    echo "<tr><td align=right>Min profit for prize</td><td>=</td>".
        "<td align=right>$tournamentMinProfitForPrize</td></tr>";

    echo "</table></center><br>";
    
    
                 
    echo "<center><table border=0 cellspacing=10>";

    echo "<tr><td valign=bottom align=right>Place</td><td></td>".
            "<td valign=bottom align=right>Prize</td></tr>";

    echo "<tr><td colspan=3><hr></td></tr>";

    $numRows = count( $prizes );
    
    for( $i=0; $i<$numRows; $i++ ) {
                
        if( $i != 0 ) {
            echo "<tr><td colspan=3><hr></td></tr>";
            }

        $rowNum = $i + 1;
        $prize = cm_formatBalanceForDisplay( $prizes[$i], true );
        
        echo "<tr><td align=right>$rowNum.</td><td></td>".
            "<td align=right>$prize</td></tr>";
        }
    echo "</table></center>";

    eval( $leaderFooter );
    }







function cm_vsOneReport() {
    
    global $tableNamePrefix, $leaderboardUpdateInterval,
        $leaderHeader, $leaderFooter;

    $code_name = cm_requestFilter( "vs_one_code_name",
                                   "/[A-Z0-9_]+/i", "" );


    eval( $leaderHeader );


    global $vsOneStartTimes, $vsOneEndTimes;


    if( ! array_key_exists( $code_name, $vsOneStartTimes ) ) {
        echo "<center>Unknown contest code:  ".
            "<b>$code_name</b><br><br></center>";
        eval( $leaderFooter );

        return;
        }


    global $vsOnePrizeImages;
    
    $imageURL = $vsOnePrizeImages[$code_name];


    echo "<center><img border=0 src=\"$imageURL\"></center><br>";
    
        
    $time = time();

    $startTime = strtotime( $vsOneStartTimes[ $code_name ] );
    $endTime = strtotime( $vsOneEndTimes[ $code_name ] );


    if( $time >= $endTime ) {
        echo "<center>This contest is over.<br><br></center>";
        }
    else if( $time < $startTime ) {
        
        $timeString = cm_formatDuration( $startTime - $time );
        
        echo "<br><br><br><center>This contest will ".
            "start in $timeString.<br><br></center>";
        return;
        }
    else {
        // live
        $timeString = cm_formatDuration( $endTime - $time );
        
        echo "<center>This contest is live and will ".
            "end in $timeString.<br><br></center>";
        }
        


    
    cm_queryDatabase( "SET AUTOCOMMIT = 0;" );
    
    $query = "SELECT html_text, ".
        "  update_time, ".
        "  update_time < ".
        "   SUBTIME( CURRENT_TIMESTAMP, '$leaderboardUpdateInterval' ) ".
        "  AS stale, ".
        "TIMESTAMPDIFF( SECOND, update_time, CURRENT_TIMESTAMP ) ".
        "  AS seconds_old ".
        "FROM $tableNamePrefix"."vs_one_cache ".
        "WHERE vs_one_code_name = '$code_name' ".
        "FOR UPDATE;";

    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );

    $stale = 1;

    $html_text = '';
    if( $numRows > 0 ) {

        $stale = mysql_result( $result, 0, "stale" );

        if( !$stale ) {

            $html_text = mysql_result( $result, 0, "html_text" );
            }
        }


    if( $stale ) {
        ob_start();

        cm_vsOneReportGenerate();

        $html_text = ob_get_contents();

        ob_end_clean();
        }
    
    


    $ageString = "";
    
    if( $stale ) {
        $ageString = "0 seconds";
        }
    else {

        $seconds_old = mysql_result( $result, 0, "seconds_old");

        $ageString = cm_formatDuration( $seconds_old );
        }


    
    echo "<center>Updated $ageString ago.</center><br>";
        
    echo $html_text;
    
    eval( $leaderFooter );

    
    if( $stale ) {
        

        $query = "INSERT INTO $tableNamePrefix"."vs_one_cache ".
            "SET vs_one_code_name = '$code_name', ".
            "  update_time = CURRENT_TIMESTAMP,".
            "  html_text = '$html_text'".
            "ON DUPLICATE KEY UPDATE ".
            "  update_time = CURRENT_TIMESTAMP, ".
            "  html_text = '$html_text';";

        cm_queryDatabase( $query );
        }

    cm_queryDatabase( "COMMIT;" );
    cm_queryDatabase( "SET AUTOCOMMIT = 1;" );

    }



function cm_vsOneReportGenerate() {
    $code_name = cm_requestFilter( "vs_one_code_name",
                                   "/[A-Z0-9_]+/i", "" );


    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;



    
    
    
    $query = "SELECT coins_won, random_name ".
        "FROM $tableNamePrefix"."vs_one_scores as scores ".
        "LEFT JOIN $tableNamePrefix"."users as users ".
        "     ON scores.user_id = users.user_id ".
        "WHERE vs_one_code_name = '$code_name' ".
        // break ties by making older user_id win the tie
        "ORDER BY coins_won DESC, scores.user_id ASC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );


    
    echo "<center><table border=0 cellspacing=10>";

    echo "<tr><td align=right></td>".
            "<td></td><td></td><td></td>".
            "<td valign=bottom align=right>Coins Won/Lost</td></tr>";

    echo "<tr><td colspan=6><hr></td></tr>";        
        

    global $vsOneNumPrizes;
    
    $numPrizes = $vsOneNumPrizes[ $code_name ];
    
    for( $i=0; $i<$numRows; $i++ ) {
        $random_name = mysql_result( $result, $i, "random_name" );
        $coins_won = mysql_result( $result, $i,
                                            "coins_won" );

        if( $i != 0 ) {
            echo "<tr><td colspan=5><hr></td></tr>";
            }

        $rowNum = $i + 1;

        $star = "";
        if( $coins_won > 0 && $i < $numPrizes ) {
            // negative scores never get prizes, even if there are prizes left
            $star = "*";
            }
        
        echo "<tr><td align=right>$rowNum.</td>".
            "<td>$star</td><td>$random_name</td><td></td>".
            "<td align=right>$coins_won</td></tr>";
        }
    echo "</table></center>";
    }





function cm_amuletReport() {
    $amulet_id = cm_requestFilter( "amulet_id",
                                   "/[0-9]+/i", "0" );


    global $tableNamePrefix, $leaderboardLimit, $leaderHeader, $leaderFooter;

    global $amulets;
    
    
    eval( $leaderHeader );

    if( ! array_key_exists( $amulet_id, $amulets ) ) {

        echo "<br><br><br><center>Amulet $amulet_id not found.".
            "<br><br></center>";

        eval( $leaderFooter );
        return;
        }
    
    // show icon
    $pngURL = $amulets[$amulet_id][2];


    echo "<center><img border=0 src=\"$pngURL\"></center><br>";
    
        
    $time = time();

    $endTime = strtotime( $amulets[$amulet_id][0] );

    $currently_holding_user_id = 0;
    
    
    if( $time > $endTime ) {
        echo "<center>This amulet contest is over.<br><br></center>";
        }
    else {
        // live
        $timeString = cm_formatDuration( $endTime - $time );

        echo "<center>This amulet contest will ".
            "end in $timeString.<br><br></center>";

        $query = "SELECT holding_user_id, random_name ".
            "FROM $tableNamePrefix"."amulets as a ".
            "LEFT JOIN $tableNamePrefix"."users as users ".
            "     ON a.holding_user_id = users.user_id ".
            "WHERE amulet_id = $amulet_id AND holding_user_id != 0;";
        

        $result = cm_queryDatabase( $query );

        $numRows = mysql_numrows( $result );
        
        if( $numRows == 1 ) {
            $random_name = mysql_result( $result, 0, "random_name" );


            $currently_holding_user_id =
                mysql_result( $result, 0, "holding_user_id" );

            $currently_holding_points =
                cm_getAmuletPoints( $amulet_id,
                                    $currently_holding_user_id );

            global $amuletInactivityLimit;
        
            $query = "SELECT ".
                "TIMESTAMPDIFF( minute, ".
                "               CURRENT_TIMESTAMP, ".
                "               ADDTIME( last_amulet_game_time, ".
                "                        '$amuletInactivityLimit' ) ) ".
                "      as minutes_left ".
                "FROM $tableNamePrefix"."amulets ".
                "WHERE holding_user_id = '$currently_holding_user_id';";


            $result = cm_queryDatabase( $query );

            $minutesLeftPhrase = "";


            $numRows = mysql_numrows( $result );
        
            if( $numRows == 1 ) {
                $minutes_left =
                    mysql_result( $result, 0, "minutes_left" );

                $minutesLeftPhrase =
                    " ($minutes_left minutes left)";
                }
            
            
            echo "<center>Currently held ".
                "by <b>$random_name</b> with ".
                "<b>$currently_holding_points</b> ".
                "points$minutesLeftPhrase.<br><br></center>";
            }
        }

    
    
    
    $query = "SELECT users.user_id, random_name, points ".
        "FROM $tableNamePrefix"."amulet_points as points ".
        "LEFT JOIN $tableNamePrefix"."users as users ".
        "     ON points.user_id = users.user_id ".
        "WHERE amulet_id = $amulet_id ".
        "ORDER BY points DESC;";
    $result = cm_queryDatabase( $query );

    $numRows = mysql_numrows( $result );



    if( $numRows > 0 ) {
        
        echo "<center><table border=0 cellspacing=10>";

        echo "<tr><td align=right></td>".
            "<td></td>".
            "<td valign=bottom align=right>Points</td>".
            "</tr>";

        echo "<tr><td colspan=8><hr></td></tr>";

        
        
        for( $i=0; $i<$numRows; $i++ ) {
            $random_name = mysql_result( $result, $i, "random_name" );
            $points = mysql_result( $result, $i, "points" );

            $user_id = mysql_result( $result, $i, "user_id" );

            if( $currently_holding_user_id == $user_id ) {
                // leave them out of list
                continue;
                }
        
            if( $points > 0 ) {
                // skip showing 0-point entries
                // usually, these won't exist, because row is only added
                // when user scores a win.  But if a user racks up hold-time
                // penalty points, they might go back to 0 after having
                // a row in the table.  Keep it consistent by never showing
                // 0s in the leaderboard.

                
                
                echo "<tr><td>$random_name</td><td></td>".
                    "<td align=right>$points</td><td></td></tr>";
                }
            }
        echo "</table></center>";
        }
    
    eval( $leaderFooter );
    }




function cm_amuletSummary() {
    

    global $tableNamePrefix, $leaderHeader, $leaderFooter;

    global $amulets;
    
    
    eval( $leaderHeader );


    $time = time();
    
    foreach( $amulets as $amulet_id => $parameters ) {

        
        
    

        $endTime = strtotime( $parameters[0] );
        
        if( $time <= $endTime ) {
            // live

            $pngURL = $parameters[2];
            echo "<center><img border=0 src=\"$pngURL\"></center>";
            
            $query = "SELECT user_id, random_name ".
                "FROM $tableNamePrefix"."amulets as a ".
                "LEFT JOIN $tableNamePrefix"."users as users ".
                "     ON a.holding_user_id = users.user_id ".
                "WHERE amulet_id = $amulet_id AND holding_user_id != 0;";
            

            $result = cm_queryDatabase( $query );

            $numRows = mysql_numrows( $result );

            $held_by = "no one";
            $holding_points =  0;            
            $holding_points_string = "";
            $holding_user_id = 0;
            
            if( $numRows == 1 ) {
                $held_by = mysql_result( $result, 0, "random_name" );

                $holding_user_id = mysql_result( $result, 0, "user_id" );

                $holding_points =
                    cm_getAmuletPoints( $amulet_id,
                                        $holding_user_id );

                $holding_points_string =
                    " with <b>$holding_points</b> points";
                }

            
            echo "<center>Currently held ".
                "by <b>$held_by</b>$holding_points_string.<br><br></center>";

            $query = "SELECT random_name, points ".
                "FROM $tableNamePrefix"."amulet_points as points ".
                "LEFT JOIN $tableNamePrefix"."users as users ".
                "     ON points.user_id = users.user_id ".
                "WHERE amulet_id = $amulet_id ".
                "AND points.user_id != $holding_user_id ".
                "ORDER BY points DESC LIMIT 1;";

            $result = cm_queryDatabase( $query );

            $numRows = mysql_numrows( $result );
            
            if( $numRows == 1 ) {
                $random_name = mysql_result( $result, 0, "random_name" );
                $points = mysql_result( $result, 0, "points" );

                if( $points > $holding_points ) {
                    echo "<center><b>$random_name</b> ".
                        "leads with <b>$points</b> points.</center>";
                    }
                }                
            
            echo "<br><br><br><br>";
            }
        }
    
    eval( $leaderFooter );
    }




function cm_graphUserData( $inTitle, $inStatToGraph, $inWhereClause,
                           $inGroupByClause,
                           $inLimit ) {

    global $tableNamePrefix;
    
    // use GROUP BY clause to filter out duplicates in sub query
    // request 2x as many as needed in sub query
    $doubleLimit = 2 * $inLimit;
    $query = "SELECT stat_time, $inStatToGraph FROM ".
        "( SELECT stat_time, $inStatToGraph ".
        "  FROM $tableNamePrefix"."user_stats ".
        "  WHERE $inWhereClause ".
        "  ORDER BY stat_time DESC LIMIT $doubleLimit ) AS temp ".
        "GROUP BY $inGroupByClause ORDER BY stat_time DESC LIMIT $inLimit;";
    
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
                      "HOUR( stat_time )",
                      24 );

    echo "<br><br><br>";
    cm_graphUserData( "Days Ago", "users_last_day",
                      "HOUR(stat_time) = ".
		      " GREATEST( HOUR(CURRENT_TIMESTAMP) - 1, 0 ) AND ".
                      "MINUTE(stat_time) <= 2",
                      "DATE( stat_time )",
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
                           $payoutA, $payoutB, $payoutHouse ) {

  global $eloProvisionalGames, $eloKProvisional, $eloKMain, $eloFloor;
    
    $aE = cm_computeExpectedScore( $ratingA, $ratingB );
    $bE = 1 - $aE;


    $buyIn = ( $payoutA + $payoutB + $payoutHouse ) / 2;
    
    // score is win, loss, draw
    //            1,    0,  0.5

    // NOTE:  unlike Chess, it's possible for both players to lose money
    // It's also possible for one to draw while the other loses,
    // or both to draw.
    // Only one player can win, however.
    $aS;
    if( $payoutA > $buyIn ) {
        $aS = 1;
        }
    else if( $payoutA < $buyIn ) {
        $aS = 0;
        }
    else {
        $aS = 0.5;
        }
    
    $bS;
    if( $payoutB > $buyIn ) {
        $bS = 1;
        }
    else if( $payoutB < $buyIn ) {
        $bS = 0;
        }
    else {
        $bS = 0.5;
        }

    // thus, unlike Chess, $bS != 1 - $aS
    
    
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

    // scale K, affect on ratings, based on fraction of chips taken by winner
    // PLUS the fraciton of chips taken by house
    // tie games affect Elo more the longer they last (as tribute grows)
    // taking half of your opponent's chips has half effect on Elo
    // taking all of your opponent's chips has full effect on Elo
    $kScale = ( abs($payoutA - $payoutB) + $payoutHouse ) /
        ( $payoutA + $payoutB + $payoutHouse );

    $aK *= $kScale;
    $bK *= $kScale;

    // provisional doesn't affect non-provisional
    if( $bProvisional && ! $aProvisional ) {
        $aK = 0;
        }
    if( $aProvisional && ! $bProvisional ) {
        $bK = 0;
        }

    
            
    $ratingA += $aK * ( $aS - $aE );
    $ratingB += $bK * ( $bS - $bE );

    if( $ratingA < $eloFloor ) {
        $ratingA = $eloFloor;
        }
    if( $ratingB < $eloFloor ) {
        $ratingB = $eloFloor;
        }

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


            $query = "SELECT dollar_delta ".
                "FROM $tableNamePrefix"."game_ledger ".
                "WHERE dollar_delta >= 0 ".
                "AND game_id = $game_id AND user_id = 0;";

            $result3 = cm_queryDatabase( $query );
            $payoutHouse = mysql_result( $result3, 0, "dollar_delta" );

            
            $ratingA = $eloRatings[ $userA ];
            $ratingB = $eloRatings[ $userB ];

            $aN = $gamesPlayed[ $userA ];
            $bN = $gamesPlayed[ $userB ];
            

            $resultArray = cm_computeNewElo( $ratingA, $ratingB, $aN, $bN,
                                             $payoutA, $payoutB,
                                             $payoutHouse );

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





function cm_awardCabalPoints() {
    cm_checkPassword( "award_cabal_points" );

    echo "[<a href=\"server.php?action=show_data" .
         "\">Main</a>]<br><br><br>";

    $points = cm_requestFilter( "points", "/[0-9]+/" );
    $confirm = cm_requestFilter( "confirm", "/[1]/", "0" );

    if( $points <= 0 ) {
        echo "Points must be positive.";
        return;
        }

    if( $confirm == 0 ) {
        echo "Must check confirmation box.";
        return;
        }

    $codeName = cm_isVsOneRunning();
    
    if( $codeName == "" ) {
        echo "Contest not running.";
        return;
        }

    global $vsOneUserIDs, $tableNamePrefix;
    
    foreach( $vsOneUserIDs as $id ) {
        $random_name = cm_getUserData( $id, "random_name" );
        $email = cm_getUserData( $id, "email" );

        echo "Awarding $points points (contest $codeName) to ".
            "<b>$random_name</b> ($id:  $email)... ";
        /*
        $query =
            "INSERT INTO $tableNamePrefix"."vs_one_scores ".
            "SET user_id = '$id', ".
            "    vs_one_code_name = '$codeName', ".
            "    coins_won = $points ".
            "ON DUPLICATE KEY UPDATE ".
            "    coins_won = coins_won + $points;";
        */
        
        // bonus points are automated now (given the first time
        // a cabal member scores)
        //
        // do update-existing only here to handle cross-over to new,
        // automated method (for those cabal members that have already
        // scored today and did not get bonus points).
        $query =
            "UPDATE $tableNamePrefix"."vs_one_scores ".
            "SET coins_won = coins_won + $points ".
            "WHERE user_id = '$id' AND ".
            "      vs_one_code_name = '$codeName';";

        cm_queryDatabase( $query );

        $numUpdated = cm_getMySQLRowsMatchedByUpdate();

        if( $numUpdated == 0 ) {
            echo "(NO EXISTING ROW) ... ";
            }
            
        echo "done.<br>";
        }
    
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
 * @param $inRetryOnFailure is used internally to control one-retry behavior.
 *        Should always be left at 1 when called externally.
 * @return a result handle that can be passed to other mysql functions or
 *        FALSE on deadlock (if deadlock is not a fatal error).
 */
function cm_queryDatabase( $inQueryString, $inDeadlockFatal=1,
                           $inRetryOnFailure=1 ) {
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

            if( $inRetryOnFailure ) {
                $logMessage =
                    "Database query failed:\n$inQueryString\n" .
                    "MySQL error = ". mysql_error() .
                    "\n(error number $errorNumber)\n".
                    "Retrying one more time.\n" . cm_getBacktrace();
                
                cm_log( $logMessage );

                global $adminEmail;
                
                cm_mail( $adminEmail, "Cordial Minuet query retry",
                         $logMessage );
                
                cm_queryDatabase( $inQueryString, $inDeadlockFatal,
                                  // don't retry again if we fail again
                                  0 );
                }
            else {
                // we're already on a retry
                // this is a hard failure state
                
                cm_fatalError(
                    "Database query failed, even after retry:\n".
                    "$inQueryString\n" .
                    "MySQL error = ".mysql_error() .
                    "\n(error number $errorNumber)" );
                }
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

    // hide database password
    global $databasePassword;
    $trace = str_replace( $databasePassword, '******', $trace );
    
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

    
    // avoid spitting error message to client
    // some of our code works-around certain failures
    
    // echo( $logMessage . "\n" );

    // still, treat notices as reportable failures, because they usually
    // cause protocol failures for client
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

    return cm_inputFilter( $_REQUEST[ $inRequestVariable ], $inRegex,
                           $inDefault );
    }



// filters users input using a regex match
function cm_inputFilter( $inInput, $inRegex, $inDefault = "" ) {
    $numMatches = preg_match( $inRegex, $inInput, $matches );

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

            cm_log( "Failed $inFunctionName access with bad password." );
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
            
            $yubikey = cm_requestFilter( "yubikey", "/[a-z]+/", "" );
            
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
    
    global $useSMTP, $siteEmailAddress, $siteEmailDomain;

    if( $useSMTP ) {
        require_once "Mail.php";

        global $smtpHost, $smtpPort, $smtpUsername, $smtpPassword;

        $messageID = "<" . uniqid() . "@$siteEmailDomain>";
        
        $headers = array( 'From' => $siteEmailAddress,
                          'To' => $inEmail,
                          'Subject' => $inSubject,
                          'Date' => date( "r" ),
                          'Message-Id' => $messageID );
        
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

