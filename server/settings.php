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

// cost in dollars
$usCheckCost = 1.50;

// email address and withdrawal details will be appended to this description
$lobCheckNote = "Withdrawal from Jason Rohrer's Cordial Minuet game for ";



$checkMethodAvailable = 1;

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



$minDeposit = "2.00";



$housePotFraction = 0.10;


?>