<?php

// Basic settings
// You must set these for the server to work
// AND these must be shared by the ticketServer operating in the same
// database (because tickets are used to uniquely identify users).

$databaseServer = "localhost";
$databaseUsername = "testUser";
$databasePassword = "testPassword";
$databaseName = "test";

// The URL of to the server.php script.
$fullServerURL = "http://localhost/jcr13/minuetServer/server.php";



// The URL of the main, public-face website
$mainSiteURL = "http://FIXME";


// URL that users can follow to download latest version
$upgradeURL = "http://FIXME";

// URL of auto-update server
$autoUpdateURL = "http://192.168.0.3/jcr13/diffBundleServer/server.php";


// used by server for things that need to be
// uniquely generated by this server to be unguessable
$serverSecretKey = "0aa02f4b4fb72740bf927ecdc94fffd21506a3a3";



// these are default, non-secret key values
// they should be replaced in live environments with values from
// curve25519GenKeyPair
$serverCurve25519SecretKey =
"596187F726D13B9BDE01AC6AFE584763095E16F38EED52CA7643248F4258CBD1";
// embed this in the client so that the server can be authenticated
$serverCurve25519PublicKey = 
"6A166C30A27C233F78786B651073737B1397865862FF2B3DDFA3DD86A21DCB79";



// End Basic settings



// Customization settings

// Adjust these to change the way the server  works.


// Prefix to use in table names (in case more than one application is using
// the same database).
$tableNamePrefix = "minuetServer_";



// number of "readable base-32" digits (2-9,A-H,J-N,P-Z) in each account key.
// Account keys are broken up into clumps of 5 digits separated by "-"
// max supported length is 210 (with separators inserted, this is a
// 251-character string)
// 5 bits of security per digit.
$accountKeyLength = 20;



$curlPath = "/home/jcr13/curl-7.37.1/src/curl";


// settings for stripe.com payment processor
$stripeChargeURL = "https://api.stripe.com/v1/charges";
$stripeTokensURL = "https://api.stripe.com/v1/tokens";
$stripeBalanceHistoryURL = "https://api.stripe.com/v1/balance/history";

$stripeSecretKey = "sk_test_YFNDtqJP2HSlwGRsI2f55vs3";

// email address will be appended to this description
$stripeChargeDescription =
"Deposit for Jason Rohrer's Cordial Minuet game for ";


$stripeFlatFee = 0.30;
$stripePercentage = 2.9;




// settings for lob.com check-mailing service
$lobURL = "https://api.lob.com/v1/checks";

$lobAPIKey = "test_10f8920b3c49e0aa69798860c5eecfa809b";

$lobBankAccount = "bank_9cea4f3e314e883";


// what check acount should be brought back up to
$checkAccountTarget = 5000.00;
// threshold that triggers mailing a check that will bring check account
// balance back up to target
$checkAccountThreshold = 4000.00;

$refreshCheckName = "Chexx Inc. Americas";
$refreshCheckAddress = "4th Floor, 595 Howe St.";
$refreshCheckCity = "Vancouver";
$refreshCheckState = "BC";
$refreshCheckPostalCode = "V6C 2T5";
$refreshCheckCountry = "CA";


// cost in dollars
$usCheckCost = 3.00;

// these countries outside the US are also charged only $usCheckCost
$otherCountriesWithUSCheckCost = array( "GB", "CA" );

// cost in dollars for all other countries
$globalCheckCost = 7.00;


// recipient email address will be appended to this memo
$checkMemo = "Cordial Minuet withdrawl for ";

// email address and more details will be appended, put in letter
$checkComment = "Withdrawal from Jason Rohrer's Cordial Minuet game for ";

$chexxSubmitURL = "https://raven.chexxinc.com/realtime/submit";
$chexxResponseURL = "https://raven.chexxinc.com/realtime/response";

// test values, replace these with production values:
$chexxUserName = "ernest";
$chexxSharedSecret = "all good men die young";
$chexxPRN = 987743;




$usCheckMethodAvailable = 1;
$globalCheckMethodAvailable = 1;

$transferMethodAvailable = 0;

$transferCost = "0.00";






$enableLog = 1;


// should web-based admin require yubikey two-factor authentication?
$enableYubikey = 1;

// 12-character Yubikey IDs, one list for each access password
// each list is a set of ids separated by :
// (there can be more than one Yubikey ID associated with each password)
$yubikeyIDs = array( "ccccccbjlfbi:ccccccbjnhjc:ccccccbjnhjn", "ccccccbjlfbi" );

// used for verifying response that comes back from yubico
// Note that these are working values, but because they are in a public
// repository, they are not secret and should be replaced with your own
// values (go here:  https://upgrade.yubico.com/getapikey/ )
$yubicoClientID = "9943";
$yubicoSecretKey = "rcGgz0rca1gqqsa/GDMwXFAHjWw=";


// For hashing admin passwords so that they don't appear in the clear
// in this file.
// You can change this to your own string so that password hashes in
// this file differ from hashes of the same passwords used elsewhere.
$passwordHashingPepper = "8ea60c7ea2b695a006205f5c133603bb155d85d4";

// passwords are given as hashes below, computed by:
// hmac_sha1( $passwordHashingPepper, $password )
// Where $passwordHashingPepper is used as the hmac key.

// For convenience, after setting a $passwordHashingPepper and chosing a
// password, hashes can be generated by invoking passwordHashUtility.php
// in your browser.

// default passwords that have been included as hashes below are:
// "secret" and "secret2"

// hashes of passwords for for web-based admin access
$accessPasswords = array( "6616d4211911cc5aa4d30adcf5af54c2814b6508",
                          "806a72a0c70240c99d36a38b1164fe8c7fdeda71" );





// Default behavior is NOT to depend on a cron job to call check_for_flush
// periodically.  With default config, some unlucky client call triggers the
// flush and waits for it to finish before that client is served.
// Better behavior can be had by disabling this and manually calling
// check_for_flush with cron.
// (Flush removes stale house checkouts, etc.)
$flushDuringClientCalls = true;



// starting key for SysV semaphores
// keys go up sequentially from this value
$startingSemKey = 1977;


// timeout in MS when waiting for other player before returning TIMEOUT
// should be matched up to retry on client (this should be shorter)
$waitTimeout = 12000;




$emailAdminOnFatalError = 0;

$adminEmail = "jason@server.com";



// enable to call the admin in an emergency using the Twilio phone API
$callAdminInEmergency = 0;

// must be a number registered with Twilio
$twilioFromNumber = "+15307565555";

$twilioToNumber = "+15307565555";

$twilioAcountID = "replace_me";

$twilioAuthToken = "replace_me";




// how many simultaneous mysql connections can we see before we
// start worrying and contacting the administator?
$mysqlConnectionCountThreshold = 50;




// mail settings

$siteEmailAddress = "Jason Rohrer <jcr13@cornell.edu>";

// if off, then raw sendmail is used instead 
$useSMTP = 0;

// SMTP requires that the PEAR Mail package is installed
// set the include path here for Mail.php, if needed:
/*
ini_set( 'include_path',
         ini_get( 'include_path' ) . PATH_SEPARATOR . '/home/jcr13/php' );
*/

$smtpHost = "ssl://mail.server.com";

$smtpPort = "465";

$smtpUsername = "jason@server.com";

$smtpPassword = "secret";











// header and footers for various pages
$header = "include( \"header.php\" );";
$footer = "include( \"footer.php\" );";


// header and footers for leaderboards
$leaderHeader = "include( \"header.php\" );";
$leaderFooter = "include( \"footer.php\" );";

$leaderboardLimit = 100;

$leaderboardUpdateInterval = "0 0:05:00.000";


// for admin view
$usersPerPage = 50;



// server shutdown mode
// causes server to respond with SHUTDOWN to most requests
// (still allows houses to be checked back in).
// Use this to weed people off of the server before installing updates, doing
// maintenance, etc.

$shutdownMode = 0;
// message to send to client explaining shutdown.
/* Separate lines with ## */ 
$shutdownMessage =
"The server is going to be rebooted##".
"to add a third CPU core.##".
"Should be back online soon.##".
"--Jason";

// turn on to disable clearing of old games and disable move timeouts
// if server has gone down during live games, this will protect
// players from being unfairly kicked when server brought back up
$gracePeriod = 0;



// set to 0 to shutdown in-progress games and
// block the creation of new ones (for example, at the end of a tournament)
$areGamesAllowed = 1;



$minDeposit = "2.00";
$maxDeposit = "999999.99";

// can limit total number of deposits one player can make
// (for running tournaments to limit re-buys).
$maxNumLifetimeDeposits = -1;



$housePotFraction = 0.13;

// maximum number of coins taken by the house percentage
$housePotLimit = 1;


// the number of coins that the house takes from each player
// at the start of each table
$houseTableCoins = 0;



// deposit threshold before we require more information from the user
// (to verify that card is not stolen, etc.)
// users can deposit up to this limit - 0.01 without submitting info
$depositWithNoInfoLimit = "500.00";



// withdrawal threshold before we require more information from
// the user for tax purposes
// users can withdraw up to this limit - 0.01 without submitting info
$withdrawalWithNoInfoYearlyLimit = "600.00";



$minGameStakes = "0.01";
$maxGameStakes = "999999999.99";


// number of coins in ante in first round
$anteCoins = 1;

// ante increase per round
// can be fractional, as ante is floored to the nearest coin
// thus, use 0.5 to have a 1-coin increase every other round
$anteIncrease = 1;


// number of coins paid by leaver to remaining player
$penaltyForLeaving = 6;



// 1 minute, plus grace period to cover one client retry
$moveTimeLimit = "0 0:01:22.000";

// don't tell the client about the grace period when giving them
// the deadline
$moveLimitGraceSeconds = 22;


// games with no action for this long are auto-ended and removed
// should be substantially longer than $moveTimeLimit
$staleGameTimeLimit = "0 0:02:00.000";


// time limit for sending end_round after reveal
// shorter than normal move time limit, so we don't keep
// one player waiting long to see the coin distribution if the other player
// bails or disconnects before sending end_round
// note that this time limit kicks in as soon as one player sends
// a properly sequenced end_round
$endRoundTimeLimit = "0 0:00:42.000";



// for Elo rating system

// rating of brand new players
$eloStartingRating = 1;

// elo ratings cannot fall below this
$eloFloor = 1;

// how many games a new player must play before their rating starts
// affecting other players
$eloProvisionalGames = 20;

$eloKProvisional = 64;

$eloKMain = 32;

// should manual recompute action be shown in admin UI
$eloManualRecompute = false;



// tournament settings
// for a single-stake tournament that is running now, embedded in the
// main game on the live server

// to switch off tournament completely
// otherwise, times below determine live interval
$tournamentLive = 1;

// no spaces, limit 255 characters
$tournamentCodeName = "test5";

// up-front fee for entering the tournament
$tournamentEntryFee = "5.00";

// all buy-ins matching this amount will count toward tournament
$tournamentStake = "0.10";

// how much one player can profit from another before being unable to
// replay that other again
// Eliminates the problem of feeding profit off of dummy accounts
$tournamentPairProfitLimit = "0.20";

$tournamentStartTime = "08-Jan-2015 11:00:00 -0800";
$tournamentEndTime = "09-Jan-2015 11:11:00 -0800";


// how much of the entry fees go toward prizes.
$tournamentPrizePoolFraction = "0.90";

// minimum prize to give out
$tournamentMinPrize = "2.50";

$tournamentPrizeRatio = 1.5;




// amulet settings

$amuletMaxStake = "3.00";

$amuletInactivityLimit = "0 2:00:00.000";

$amuletLastStandingPoints = 200;

$amuletHoldPenaltyPerMinute = 1;

$amuletJoinDelayMaxSec = 20;



// each amulet must have a unique ID that hasn't been used for past amulets
// the id must not be zero

// Elements in an amulet are ID, end time, and URLS to icon image TGA and PNG

$amulets = array(
    1 => array( "09-Mar-2015 11:11:00 -0800",
                "http://192.168.0.4/jcr13/testAmulet.tga",
                "http://cordialminuet.com/amulets/1.png" ),
    2 => array( "09-Mar-2015 11:11:00 -0800",
                "http://192.168.0.4/jcr13/testAmulet.tga",
                "http://cordialminuet.com/amulets/2.png" ) );
    
?>