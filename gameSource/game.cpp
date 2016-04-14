int versionNumber = 27;

// retain an older version number here if server is compatible
// with older client versions.
// Change this number (and number on server) if server has changed
// in a way that breaks old clients.
int accountHmacVersionNumber = 25;



#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <time.h>
#include <math.h>

//#define USE_MALLINFO

#ifdef USE_MALLINFO
#include <malloc.h>
#endif


#include "minorGems/graphics/Color.h"

#include "minorGems/sound/filters/ReverbSoundFilter.h"




#include "minorGems/util/SimpleVector.h"
#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"
#include "minorGems/util/random/CustomRandomSource.h"


// static seed
CustomRandomSource randSource( 34957197 );



#include "minorGems/util/log/AppLog.h"



#include "minorGems/game/game.h"
#include "minorGems/game/gameGraphics.h"
#include "minorGems/game/Font.h"
#include "minorGems/game/drawUtils.h"
#include "minorGems/game/diffBundle/client/diffBundleClient.h"




#include "FinalMessagePage.h"
#include "ServerActionPage.h"
#include "AutoUpdatePage.h"
#include "AccountCheckPage.h"
#include "DepositPage.h"
#include "NewAccountDisplayPage.h"
#include "MenuPage.h"
#include "DepositDisplayPage.h"
#include "ExistingAccountPage.h"
#include "WithdrawPage.h"
#include "InPersonPage.h"
#include "SendCheckPage.h"
#include "SendCheckGlobalPage.h"
#include "AccountTransferPage.h"
#include "CreateGamePage.h"
#include "EnterTournamentPage.h"
#include "WaitGamePage.h"
#include "PlayGamePage.h"
#include "ExtendedMessagePage.h"
#include "GetAmuletPage.h"


#include "serialWebRequests.h"
#include "TextField.h"

#include "chime.h"

#include "amuletCache.h"



GamePage *currentGamePage = NULL;


FinalMessagePage *finalMessagePage;
ExtendedMessagePage *extendedMessagePage;
ServerActionPage *getServerURLPage;
ServerActionPage *getRequiredVersionPage;
AutoUpdatePage *autoUpdatePage;
AccountCheckPage *accountCheckPage;
ServerActionPage *getDepositFeesPage;
DepositPage *depositPage;
NewAccountDisplayPage *newAccountDisplayPage;
ServerActionPage *getBalancePage;
ServerActionPage *dropAmuletPage;
MenuPage *menuPage;
DepositDisplayPage *depositDisplayPage;
ExistingAccountPage *existingAccountPage;
WithdrawPage *withdrawPage;
InPersonPage *inPersonPage;
SendCheckPage *sendCheckPage;
SendCheckGlobalPage *sendCheckGlobalPage;
AccountTransferPage *accountTransferPage;
DepositDisplayPage *withdrawalDisplayPage;
CreateGamePage *createGamePage;
EnterTournamentPage *enterTournamentPage;
WaitGamePage *waitGamePage;
ServerActionPage *leaveGamePage;
ServerActionPage *joinGamePage;
PlayGamePage *playGamePage;
GetAmuletPage *getAmuletPage;


// position of view in world
doublePair lastScreenViewCenter = {0, 0 };



// world with of one view
double viewWidth = 666;

// fraction of viewWidth visible vertically (aspect ratio)
double viewHeightFraction;

int screenW, screenH;

char initDone = false;

float mouseSpeed;

int musicOff;
float musicLoudness;
int diffHighlightsOff;

int webRetrySeconds;


double frameRateFactor = 1;


char firstDrawFrameCalled = false;


char upKey = 'w';
char leftKey = 'a';
char downKey = 's';
char rightKey = 'd';




char doesOverrideGameImageSize() {
    return true;
    }



void getGameImageSize( int *outWidth, int *outHeight ) {
    *outWidth = 666;
    *outHeight = 666;
    }


char shouldNativeScreenResolutionBeUsed() {
    return true;
    }

char isNonIntegerScalingAllowed() {
    return false;
    }


const char *getWindowTitle() {
    return "CORDIAL MINUET";
    }


const char *getAppName() {
    return "CORDIAL_MINUET";
    }

const char *getLinuxAppName() {
    // no dir-name conflict here because we're using all caps for app name
    return getAppName();
    }



const char *getFontTGAFileName() {
    return "font_32_64.tga";
    }


char isDemoMode() {
    return false;
    }


const char *getDemoCodeSharedSecret() {
    return "fundamental_right";
    }


const char *getDemoCodeServerURL() {
    return "http://FIXME/demoServer/server.php";
    }



char gamePlayingBack = false;


Font *mainFont;
Font *mainFontFixed;
Font *numbersFontFixed;

char *shutdownMessage = NULL;


// start at reflector URL
// first step is replacing it with server URL after reflection
char *serverURL = NULL;


char *userEmail = NULL;
int userID = -1;
char *accountKey = NULL;
// each new request to server must use next sequence number
int serverSequenceNumber = 0;

double depositFlatFee = 0;
double depositPercentage = 0;
double minDeposit = 2.00;
double maxDeposit = 999999.99;

double minGameStakes = 0.01;
double maxGameStakes = 999999999.99;



double userBalance = 0;
double checkCostUS = 0;
double checkCostGlobal = 0;
double transferCost = 0;

// gets set permanently to true for session if withdraw method list
// ever contains in_person
char inPersonMode = false;


int playerIsAdmin = 0;


// this is non-zero only if amuletID below is one we just picked up
int justAcquiredAmuletID = 0;
char *justAcquiredAmuletTGAURL = NULL;

int amuletID = 0;
int amuletPointCount;
int amuletBaseTime;
int amuletHoldPenaltyPerMinute;

double amuletStake = 3.00;

char waitingAmuletGame = false;




int moveWaitingSoundSprite = -1;
int chipSoundSprites[4] = { -1, -1, -1, -1 };


static char wasPaused = false;
static float pauseScreenFade = 0;

static char *currentUserTypedMessage = NULL;



// for delete key repeat during message typing
static int holdDeleteKeySteps = -1;
static int stepsBetweenDeleteRepeat;





#define SETTINGS_HASH_SALT "another_loss"


static const char *customDataFormatWriteString = 
    "version%d_mouseSpeed%f_musicOff%d_musicLoudness%f"
    "_webRetrySeconds%d_accountKey%s_email%s";

static const char *customDataFormatReadString = 
    "version%d_mouseSpeed%f_musicOff%d_musicLoudness%f"
    "_webRetrySeconds%d_accountKey%10s_email%99s";


char *getCustomRecordedGameData() {    
    
    float mouseSpeedSetting = 
        SettingsManager::getFloatSetting( "mouseSpeed", 1.0f );
    int musicOffSetting = 
        SettingsManager::getIntSetting( "musicOff", 0 );
    float musicLoudnessSetting = 
        SettingsManager::getFloatSetting( "musicLoudness", 1.0f );
    int webRetrySecondsSetting = 
        SettingsManager::getIntSetting( "webRetrySeconds", 10 );
    
    char *email =
        SettingsManager::getStringSetting( "email" );
    if( email == NULL ) {
        email = stringDuplicate( "*" );
        }
    else {
        // put bogus email in recording files, since we don't
        // need a email during playback anyway (not communicating with 
        // server during playback)
        
        // want people to be able to share playback files freely without
        // divulging their emails
        delete [] email;

        email = stringDuplicate( "redacted@redacted.com" );
        }
    
    
    char *code = SettingsManager::getStringSetting( "accountKey" );
    
    if( code == NULL ) {
        code = stringDuplicate( "**********" );
        }
    else {
        // put bogus code in recording files, since we don't
        // need a valid code during playback anyway (not communicating with 
        // server during playback)
        
        // want people to be able to share playback files freely without
        // divulging their download codes
        delete [] code;

        code = stringDuplicate( "EMPTYDEEPS" );
        }
    


    char * result = autoSprintf(
        customDataFormatWriteString,
        versionNumber, mouseSpeedSetting, musicOffSetting, 
        musicLoudnessSetting,
        webRetrySecondsSetting, code, email );

    delete [] email;
    delete [] code;
    

    return result;
    }



char showMouseDuringPlayback() {
    // since we rely on the system mouse pointer during the game (and don't
    // draw our own pointer), we need to see the recorded pointer position
    // to make sense of game playback
    return true;
    }



char *getHashSalt() {
    return stringDuplicate( SETTINGS_HASH_SALT );
    }




void initDrawString( int inWidth, int inHeight ) {
    mainFont = new Font( getFontTGAFileName(), 6, 16, false, 16 );
    mainFont->setMinimumPositionPrecision( 1 );

    setViewCenterPosition( lastScreenViewCenter.x, lastScreenViewCenter.y );

    viewHeightFraction = inHeight / (double)inWidth;

    // square window for this game
    viewWidth = 666 * 1.0 / viewHeightFraction;
    
    
    setViewSize( viewWidth );
    }


void freeDrawString() {
    delete mainFont;
    }



void initFrameDrawer( int inWidth, int inHeight, int inTargetFrameRate,
                      const char *inCustomRecordedGameData,
                      char inPlayingBack ) {
    
    gamePlayingBack = inPlayingBack;
    
    screenW = inWidth;
    screenH = inHeight;
    
    if( inTargetFrameRate != 60 ) {
        frameRateFactor = (double)60 / (double)inTargetFrameRate;
        }
    
    TextField::setDeleteRepeatDelays( (int)( 30 / frameRateFactor ),
                                      (int)( 2 / frameRateFactor ) );
    


    setViewCenterPosition( lastScreenViewCenter.x, lastScreenViewCenter.y );

    viewHeightFraction = inHeight / (double)inWidth;

    
    // square window for this game
    viewWidth = 666 * 1.0 / viewHeightFraction;
    
    
    setViewSize( viewWidth );


    
    

    

    setCursorVisible( true );
    grabInput( false );
    
    // world coordinates
    setMouseReportingMode( true );
    
    
    
    
    mainFontFixed = new Font( getFontTGAFileName(), 6, 16, true, 16 );
    numbersFontFixed = new Font( getFontTGAFileName(), 6, 16, true, 16, 16 );
    
    mainFontFixed->setMinimumPositionPrecision( 1 );
    numbersFontFixed->setMinimumPositionPrecision( 1 );
    

    float mouseSpeedSetting = 1.0f;
    
    int musicOffSetting = 0;
    float musicLoudnessSetting = 1.0f;
    int webRetrySecondsSetting = 10;

    userEmail = new char[100];
    accountKey = new char[11];
    
    int readVersionNumber;
    
    int numRead = sscanf( inCustomRecordedGameData, 
                          customDataFormatReadString, 
                          &readVersionNumber,
                          &mouseSpeedSetting, 
                          &musicOffSetting,
                          &musicLoudnessSetting,
                          &webRetrySecondsSetting,
                          accountKey,
                          userEmail );
    if( numRead != 7 ) {
        // no recorded game?
        }
    else {

        if( readVersionNumber != versionNumber ) {
            AppLog::printOutNextMessage();
            AppLog::warningF( 
                "WARNING:  version number in playback file is %d "
                "but game version is %d...",
                readVersionNumber, versionNumber );
            }

        if( strcmp( accountKey, "**********" ) == 0 ) {
            delete [] accountKey;
            accountKey = NULL;
            }
        if( strcmp( userEmail, "*" ) == 0 ) {
            delete [] userEmail;
            userEmail = NULL;
            }
        }
    
    if( !inPlayingBack ) {
        // read REAL email and download code from settings file

        delete [] userEmail;
        
        userEmail = SettingsManager::getStringSetting( "email" );    

        
        delete [] accountKey;
        
        accountKey = SettingsManager::getStringSetting( "accountKey" );    
        }
    

    
    double mouseParam = 0.000976562;

    mouseParam *= mouseSpeedSetting;

    mouseSpeed = mouseParam * inWidth / viewWidth;

    musicOff = musicOffSetting;
    musicLoudness = musicLoudnessSetting;
    webRetrySeconds = webRetrySecondsSetting;

    serverURL = SettingsManager::getStringSetting( "reflectorURL" );

    if( serverURL == NULL ) {
        serverURL = 
            stringDuplicate( 
                "http://localhost/jcr13/castleReflector/server.php" );
        }


    

    finalMessagePage = new FinalMessagePage();
    extendedMessagePage = new ExtendedMessagePage();
    
    printf( "Starting fetching server URL from reflector %s\n",
            serverURL );


    const char *resultNamesA[1] = { "serverURL" };
    
    getServerURLPage = new ServerActionPage( "reflect", 
                                             1, resultNamesA, false );


    const char *resultNamesB[3] = { "requiredVersionNumber",
                                    "newVersionURL",
                                    "autoUpdateURL" };
    
    getRequiredVersionPage = new ServerActionPage( "check_required_version", 
                                                   3, resultNamesB, false );
    
    autoUpdatePage = new AutoUpdatePage();

    accountCheckPage = new AccountCheckPage();
    
    
    const char *resultNamesD[4] = { "flatFee",
                                    "percentage",
                                    "minDeposit",
                                    "maxDeposit" };
    
    getDepositFeesPage = new ServerActionPage( "get_deposit_fees", 
                                               4, resultNamesD, true );


    depositPage = new DepositPage();
    newAccountDisplayPage = new NewAccountDisplayPage();

    
    const char *resultNamesC[6] = { "dollarBalance",
                                    "amulet_id",
                                    "amulet_tga_url",
                                    "amulet_point_count",
                                    "amulet_seconds_held",
                                    "amulet_hold_penalty_per_minute" };
    
    getBalancePage = new ServerActionPage( "get_balance", 
                                             6, resultNamesC, true );

    dropAmuletPage = new ServerActionPage( "drop_amulet" );
    

    menuPage = new MenuPage();

    depositDisplayPage = new DepositDisplayPage();
    existingAccountPage = new ExistingAccountPage();
    
    withdrawPage = new WithdrawPage();
    inPersonPage = new InPersonPage();
    sendCheckPage = new SendCheckPage();
    sendCheckGlobalPage = new SendCheckGlobalPage();
    accountTransferPage = new AccountTransferPage();

    withdrawalDisplayPage = new DepositDisplayPage();
    withdrawalDisplayPage->setWithdraw( true );

    createGamePage = new CreateGamePage();
    enterTournamentPage = new EnterTournamentPage();
    waitGamePage = new WaitGamePage();
        
    const char *resultNamesE[3] = { "buy_in_dollar_amount", 
                                    "payout_dollar_amount",
                                    "payout_vs_one_coins" };

    leaveGamePage = new ServerActionPage( "leave_game",
                                          3, resultNamesE, true );

    joinGamePage = new ServerActionPage( "join_game" );


    playGamePage = new PlayGamePage();

    getAmuletPage = new GetAmuletPage();


    currentGamePage = getServerURLPage;

    currentGamePage->base_makeActive( true );


    moveWaitingSoundSprite = loadSoundSprite( "chime.aiff" );
    
    chipSoundSprites[0] = loadSoundSprite( "chipSmall.aiff" );
    chipSoundSprites[1] = loadSoundSprite( "chipBig.aiff" );
    chipSoundSprites[2] = loadSoundSprite( "chipSmallRake.aiff" );
    chipSoundSprites[3] = loadSoundSprite( "chipBigRake.aiff" );

    setSoundLoudness( musicLoudness );
    setSoundPlaying( true );

    initDone = true;
    }



void freeFrameDrawer() {
    delete mainFontFixed;
    delete numbersFontFixed;
    
    if( currentUserTypedMessage != NULL ) {
        delete [] currentUserTypedMessage;
        currentUserTypedMessage = NULL;
        }


    currentGamePage = NULL;
    delete finalMessagePage;
    delete extendedMessagePage;
    delete getServerURLPage;
    delete getRequiredVersionPage;
    delete autoUpdatePage;
    delete accountCheckPage;
    delete getDepositFeesPage;
    delete depositPage;
    delete newAccountDisplayPage;
    delete getBalancePage;
    delete dropAmuletPage;
    delete menuPage;
    delete depositDisplayPage;
    delete existingAccountPage;
    delete withdrawPage;
    delete inPersonPage;
    delete sendCheckPage;
    delete sendCheckGlobalPage;
    delete accountTransferPage;
    delete withdrawalDisplayPage;
    delete createGamePage;
    delete enterTournamentPage;
    delete waitGamePage;
    delete leaveGamePage;
    delete joinGamePage;
    delete playGamePage;
    delete getAmuletPage;
    

    if( shutdownMessage != NULL ) {
        delete [] shutdownMessage;
        shutdownMessage = NULL;
        }

    if( serverURL != NULL ) {
        delete [] serverURL;
        serverURL = NULL;
        }

    if( accountKey != NULL ) {
        delete [] accountKey;
        accountKey = NULL;
        }
    
    if( userEmail != NULL ) {
        delete [] userEmail;
        userEmail = NULL;
        }

    if( justAcquiredAmuletTGAURL != NULL ) {
        delete [] justAcquiredAmuletTGAURL;
        justAcquiredAmuletTGAURL = NULL;
        }
    
    freeAmuletCache();
    

    freeSoundSprite( moveWaitingSoundSprite );

    for( int i=0; i<4; i++ ) {
        freeSoundSprite( chipSoundSprites[i] );
        }
    }





    


// draw code separated from updates
// some updates are still embedded in draw code, so pass a switch to 
// turn them off
static void drawFrameNoUpdate( char inUpdate );




static void drawPauseScreen() {

    double viewHeight = viewHeightFraction * viewWidth;

    setDrawColor( 1, 1, 1, 0.5 * pauseScreenFade );
        
    drawSquare( lastScreenViewCenter, 1.05 * ( viewHeight / 3 ) );
        

    setDrawColor( 0.2, 0.2, 0.2, 0.85 * pauseScreenFade  );
        
    drawSquare( lastScreenViewCenter, viewHeight / 3 );
        

    setDrawColor( 1, 1, 1, pauseScreenFade );

    doublePair messagePos = lastScreenViewCenter;

    messagePos.y += 4.5  * (viewHeight / 15);

    mainFont->drawString( translate( "pauseMessage1" ), 
                           messagePos, alignCenter );
        
    messagePos.y -= 1.25 * (viewHeight / 15);
    mainFont->drawString( translate( "pauseMessage2" ), 
                           messagePos, alignCenter );

    if( currentUserTypedMessage != NULL ) {
            
        messagePos.y -= 1.25 * (viewHeight / 15);
            
        double maxWidth = 0.95 * ( viewHeight / 1.5 );
            
        int maxLines = 9;

        SimpleVector<char *> *tokens = 
            tokenizeString( currentUserTypedMessage );


        // collect all lines before drawing them
        SimpleVector<char *> lines;
        
            
        while( tokens->size() > 0 ) {

            // build up a a line

            // always take at least first token, even if it is too long
            char *currentLineString = 
                stringDuplicate( *( tokens->getElement( 0 ) ) );
                
            delete [] *( tokens->getElement( 0 ) );
            tokens->deleteElement( 0 );
            
            

            

            
            char nextTokenIsFileSeparator = false;
                
            char *nextLongerString = NULL;
                
            if( tokens->size() > 0 ) {

                char *nextToken = *( tokens->getElement( 0 ) );
                
                if( nextToken[0] == 28 ) {
                    nextTokenIsFileSeparator = true;
                    }
                else {
                    nextLongerString =
                        autoSprintf( "%s %s ",
                                     currentLineString,
                                     *( tokens->getElement( 0 ) ) );
                    }
                
                }
                
            while( !nextTokenIsFileSeparator 
                   &&
                   nextLongerString != NULL 
                   && 
                   mainFont->measureString( nextLongerString ) 
                   < maxWidth 
                   &&
                   tokens->size() > 0 ) {
                    
                delete [] currentLineString;
                    
                currentLineString = nextLongerString;
                    
                nextLongerString = NULL;
                    
                // token consumed
                delete [] *( tokens->getElement( 0 ) );
                tokens->deleteElement( 0 );
                    
                if( tokens->size() > 0 ) {
                    
                    char *nextToken = *( tokens->getElement( 0 ) );
                
                    if( nextToken[0] == 28 ) {
                        nextTokenIsFileSeparator = true;
                        }
                    else {
                        nextLongerString =
                            autoSprintf( "%s%s ",
                                         currentLineString,
                                         *( tokens->getElement( 0 ) ) );
                        }
                    }
                }
                
            if( nextLongerString != NULL ) {    
                delete [] nextLongerString;
                }
                
            while( mainFont->measureString( currentLineString ) > 
                   maxWidth ) {
                    
                // single token that is too long by itself
                // simply trim it and discard part of it 
                // (user typing nonsense anyway)
                    
                currentLineString[ strlen( currentLineString ) - 1 ] =
                    '\0';
                }
                
            if( currentLineString[ strlen( currentLineString ) - 1 ] 
                == ' ' ) {
                // trim last bit of whitespace
                currentLineString[ strlen( currentLineString ) - 1 ] = 
                    '\0';
                }

                
            lines.push_back( currentLineString );

            
            if( nextTokenIsFileSeparator ) {
                // file separator

                // put a paragraph separator in
                lines.push_back( stringDuplicate( "---" ) );

                // token consumed
                delete [] *( tokens->getElement( 0 ) );
                tokens->deleteElement( 0 );
                }
            }   


        // all tokens deleted above
        delete tokens;


        double messageLineSpacing = 0.625 * (viewHeight / 15);
        
        int numLinesToSkip = lines.size() - maxLines;

        if( numLinesToSkip < 0 ) {
            numLinesToSkip = 0;
            }
        
        
        for( int i=0; i<numLinesToSkip-1; i++ ) {
            char *currentLineString = *( lines.getElement( i ) );
            delete [] currentLineString;
            }
        
        int lastSkipLine = numLinesToSkip - 1;

        if( lastSkipLine >= 0 ) {
            
            char *currentLineString = *( lines.getElement( lastSkipLine ) );

            // draw above and faded out somewhat

            doublePair lastSkipLinePos = messagePos;
            
            lastSkipLinePos.y += messageLineSpacing;

            setDrawColor( 1, 1, 0.5, 0.125 * pauseScreenFade );

            mainFont->drawString( currentLineString, 
                                   lastSkipLinePos, alignCenter );

            
            delete [] currentLineString;
            }
        

        setDrawColor( 1, 1, 0.5, pauseScreenFade );

        for( int i=numLinesToSkip; i<lines.size(); i++ ) {
            char *currentLineString = *( lines.getElement( i ) );
            
            if( false && lastSkipLine >= 0 ) {
            
                if( i == numLinesToSkip ) {
                    // next to last
                    setDrawColor( 1, 1, 0.5, 0.25 * pauseScreenFade );
                    }
                else if( i == numLinesToSkip + 1 ) {
                    // next after that
                    setDrawColor( 1, 1, 0.5, 0.5 * pauseScreenFade );
                    }
                else if( i == numLinesToSkip + 2 ) {
                    // rest are full fade
                    setDrawColor( 1, 1, 0.5, pauseScreenFade );
                    }
                }
            
            mainFont->drawString( currentLineString, 
                                   messagePos, alignCenter );

            delete [] currentLineString;
                
            messagePos.y -= messageLineSpacing;
            }
        }
        
        

    setDrawColor( 1, 1, 1, pauseScreenFade );

    messagePos = lastScreenViewCenter;

    messagePos.y -= 3.75 * ( viewHeight / 15 );
    //mainFont->drawString( translate( "pauseMessage3" ), 
    //                      messagePos, alignCenter );

    messagePos.y -= 0.625 * (viewHeight / 15);

    const char* quitMessageKey = "pauseMessage3";
    
    if( isQuittingBlocked() ) {
        quitMessageKey = "pauseMessage3b";
        }

    mainFont->drawString( translate( quitMessageKey ), 
                          messagePos, alignCenter );

    }



void deleteCharFromUserTypedMessage() {
    if( currentUserTypedMessage != NULL ) {
                    
        int length = strlen( currentUserTypedMessage );
        
        char fileSeparatorDeleted = false;
        if( length > 2 ) {
            if( currentUserTypedMessage[ length - 2 ] == 28 ) {
                // file separator with spaces around it
                // delete whole thing with one keypress
                currentUserTypedMessage[ length - 3 ] = '\0';
                fileSeparatorDeleted = true;
                }
            }
        if( !fileSeparatorDeleted && length > 0 ) {
            currentUserTypedMessage[ length - 1 ] = '\0';
            }
        }
    }




// returns either menuPage or getAmuletPage, depending on what's needed
static GamePage *menuOrAmuletPage() {
    if( justAcquiredAmuletID != 0 ) {
        return getAmuletPage;
        }
    else {
        return menuPage;
        }
    }




void drawFrame( char inUpdate ) {    


    if( !inUpdate ) {

        if( isQuittingBlocked() ) {
            // unsafe NOT to keep updating here, because pending network
            // requests can stall

            // keep stepping current page, but don't do any other processing
            // (and still block user events from reaching current page)
            if( currentGamePage != NULL ) {
                currentGamePage->base_step();
                }
            }

        drawFrameNoUpdate( false );
            
        drawPauseScreen();
        
        if( !wasPaused ) {
            if( currentGamePage != NULL ) {
                currentGamePage->base_makeNotActive();
                }
            // fade out music during pause
            //setMusicLoudness( 0 );
            }
        wasPaused = true;

        // handle delete key repeat
        if( holdDeleteKeySteps > -1 ) {
            holdDeleteKeySteps ++;
            
            if( holdDeleteKeySteps > stepsBetweenDeleteRepeat ) {        
                // delete repeat

                // platform layer doesn't receive event for key held down
                // tell it we are still active so that it doesn't
                // reduce the framerate during long, held deletes
                wakeUpPauseFrameRate();
                


                // subtract from messsage
                deleteCharFromUserTypedMessage();
                
                            

                // shorter delay for subsequent repeats
                stepsBetweenDeleteRepeat = (int)( 2/ frameRateFactor );
                holdDeleteKeySteps = 0;
                }
            }

        // fade in pause screen
        if( pauseScreenFade < 1 ) {
            pauseScreenFade += ( 1.0 / 30 ) * frameRateFactor;
        
            if( pauseScreenFade > 1 ) {
                pauseScreenFade = 1;
                }
            }
        

        return;
        }


    // not paused


    // fade pause screen out
    if( pauseScreenFade > 0 ) {
        pauseScreenFade -= ( 1.0 / 30 ) * frameRateFactor;
        
        if( pauseScreenFade < 0 ) {
            pauseScreenFade = 0;

            if( currentUserTypedMessage != NULL ) {

                // make sure it doesn't already end with a file separator
                // (never insert two in a row, even when player closes
                //  pause screen without typing anything)
                int lengthCurrent = strlen( currentUserTypedMessage );

                if( lengthCurrent < 2 ||
                    currentUserTypedMessage[ lengthCurrent - 2 ] != 28 ) {
                         
                        
                    // insert at file separator (ascii 28)
                    
                    char *oldMessage = currentUserTypedMessage;
                    
                    currentUserTypedMessage = autoSprintf( "%s %c ", 
                                                           oldMessage,
                                                           28 );
                    delete [] oldMessage;
                    }
                }
            }
        }    
    
    

    if( !firstDrawFrameCalled ) {
        
        // do final init step... stuff that shouldn't be done until
        // we have control of screen
        
        char *moveKeyMapping = 
            SettingsManager::getStringSetting( "upLeftDownRightKeys" );
    
        if( moveKeyMapping != NULL ) {
            char *temp = stringToLowerCase( moveKeyMapping );
            delete [] moveKeyMapping;
            moveKeyMapping = temp;
        
            if( strlen( moveKeyMapping ) == 4 &&
                strcmp( moveKeyMapping, "wasd" ) != 0 ) {
                // different assignment

                upKey = moveKeyMapping[0];
                leftKey = moveKeyMapping[1];
                downKey = moveKeyMapping[2];
                rightKey = moveKeyMapping[3];
                }
            delete [] moveKeyMapping;
            }


        firstDrawFrameCalled = true;
        }

    if( wasPaused ) {
        if( currentGamePage != NULL ) {
            currentGamePage->base_makeActive( false );
            }
        // fade music in
        //if( ! musicOff ) {
        //    setMusicLoudness( 1.0 );
        //    }
        wasPaused = false;
        }
    
    
    if( getServerShutdown() ) {
        currentGamePage = finalMessagePage;
        finalMessagePage->setMessageKey( "serverShutdownMessage" );
        finalMessagePage->setSubMessage( shutdownMessage );
        
        currentGamePage->base_makeActive( true );
        }
    

    if( currentGamePage != NULL ) {
        
        currentGamePage->base_step();

        // branches to process states depending on which page is active
        
        if( currentGamePage == getServerURLPage ) {
            
            if( getServerURLPage->isResponseReady() ) {
                
                if( serverURL != NULL ) {
                    delete [] serverURL;
                    }
                
                serverURL = getServerURLPage->getResponse( "serverURL" );
                
                printf( "Got server URL: %s\n", serverURL );

                currentGamePage = getRequiredVersionPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == getRequiredVersionPage ) {
            if( getRequiredVersionPage->isResponseReady() ) {
                
                int requiredVersionNumber = 
                    getRequiredVersionPage->getResponseInt( 
                        "requiredVersionNumber" );
                
                if( requiredVersionNumber > versionNumber ) {

                    char *autoUpdateURL = 
                        getRequiredVersionPage->getResponse( "autoUpdateURL" );

                    
                    char updateStarted = 
                        startUpdate( autoUpdateURL, versionNumber );
                    
                    delete [] autoUpdateURL;

                    if( ! updateStarted ) {
                        currentGamePage = finalMessagePage;
                        
                        finalMessagePage->setMessageKey( "upgradeMessage" );

                        char *downloadURL = 
                            getRequiredVersionPage->getResponse( 
                                "newVersionURL" );

                        finalMessagePage->setSubMessage( downloadURL );
                    
                        delete [] downloadURL;
                        
                        currentGamePage->base_makeActive( true );
                        }
                    else {
                        currentGamePage = autoUpdatePage;
                        currentGamePage->base_makeActive( true );
                        }
                    }
                else {
                    // version okay
                    // login
                    currentGamePage = accountCheckPage;
                    currentGamePage->base_makeActive( true );
                    }
                }
            }
        else  if( currentGamePage == autoUpdatePage ) {
            if( autoUpdatePage->checkSignal( "failed" ) ) {
                currentGamePage = finalMessagePage;
                        
                finalMessagePage->setMessageKey( "upgradeMessage" );
                
                char *downloadURL = 
                    getRequiredVersionPage->getResponse( 
                        "newVersionURL" );
                
                finalMessagePage->setSubMessage( downloadURL );
                
                delete [] downloadURL;
                
                currentGamePage->base_makeActive( true );
                }
            else if( autoUpdatePage->checkSignal( "relaunchFailed" ) ) {
                currentGamePage = finalMessagePage;
                        
                finalMessagePage->setMessageKey( "manualRestartMessage" );
                                
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == accountCheckPage ) {
            if( accountCheckPage->checkSignal( "newAccount" ) ) {
                currentGamePage = getDepositFeesPage;
                
                if( userEmail != NULL ) {
                    delete [] userEmail;
                    userEmail = NULL;
                    }
                depositPage->setEmailFieldCanFocus( true );
                
                currentGamePage->base_makeActive( true );
                }
            else if( accountCheckPage->checkSignal( "existingAccount" ) ) {
                currentGamePage = existingAccountPage;
                currentGamePage->base_makeActive( true );
                }
            else if( accountCheckPage->isResponseReady() ) {
                // logged in

                userID = 
                    accountCheckPage->getResponseInt( "userID" );
                serverSequenceNumber = 
                    accountCheckPage->getResponseInt( "sequenceNumber" );
                
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == getDepositFeesPage ) {
            if( getDepositFeesPage->isResponseReady() ) {
                

                depositFlatFee = 
                    getDepositFeesPage->getResponseDouble( "flatFee" );
                depositPercentage = 
                    getDepositFeesPage->getResponseDouble( "percentage" );
                minDeposit = 
                    getDepositFeesPage->getResponseDouble( "minDeposit" );
                maxDeposit = 
                    getDepositFeesPage->getResponseDouble( "maxDeposit" );
                
                if( maxDeposit <= 0 ) {
                    extendedMessagePage->setMessageKey( 
                        "depositBlocked" );
                
                    currentGamePage = extendedMessagePage;
                    currentGamePage->base_makeActive( true );
                    }
                else {
                    currentGamePage = depositPage;
                    currentGamePage->base_makeActive( true );
                    }
                }
            }
        else if( currentGamePage == depositPage ) {
            if( depositPage->checkSignal( "back" ) ) {

                if( userID == -1 ) {
                    currentGamePage = accountCheckPage;
                    currentGamePage->base_makeActive( true );
                    }
                else {
                    // logged in already
                    currentGamePage = menuOrAmuletPage();
                    currentGamePage->base_makeActive( true );
                    }
                }
            else if( depositPage->checkSignal( "newAccount" ) ) {
                currentGamePage = newAccountDisplayPage;
                currentGamePage->base_makeActive( true );
                }
            else if( depositPage->checkSignal( "existingAccount" ) ) {
                depositDisplayPage->setDeltaAmount( 
                    depositPage->getDepositNetAmount() );
                
                depositDisplayPage->setLeftGame( false );

                // never show these post-deposit
                depositDisplayPage->setVsOneCoins( 0 );

                currentGamePage = depositDisplayPage;
                currentGamePage->base_makeActive( true );
                }
            else if( depositPage->checkSignal( "moreInfoNeeded" ) ) {
                extendedMessagePage->setMessageKey( 
                    "moreDepositInfoNeededMessage" );
                
                currentGamePage = extendedMessagePage;
                currentGamePage->base_makeActive( true );
                }
            else if( depositPage->checkSignal( "clearAccount" ) ) {
                SettingsManager::setSetting( "email", "" );
                SettingsManager::setSetting( "accountKey", "" );
                
                if( userEmail != NULL ) {
                    delete [] userEmail;
                    userEmail = NULL;
                    }
                if( accountKey != NULL ) {
                    delete [] accountKey;
                    accountKey = NULL;
                    }
                userID = -1;
                userBalance = 0;
                serverSequenceNumber = 0;
                
                existingAccountPage->clearFields();
                sendCheckPage->clearFields();
                sendCheckGlobalPage->clearFields();
                
                createGamePage->clearAmountPicker();

                currentGamePage = accountCheckPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == newAccountDisplayPage ) {
            if( newAccountDisplayPage->checkSignal( "done" ) ) {
                currentGamePage = accountCheckPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == getBalancePage ) {
            if( getBalancePage->isResponseReady() ) {
                userBalance = 
                    getBalancePage->getResponseDouble( "dollarBalance" );
                
                
                justAcquiredAmuletID = 
                    getBalancePage->getResponseInt( "amulet_id" );
                
                if( amuletID != justAcquiredAmuletID ) {
                    
                    amuletID = justAcquiredAmuletID;

                    if( justAcquiredAmuletID != 0 ) {
                        
                        if( justAcquiredAmuletTGAURL != NULL ) {
                            delete [] justAcquiredAmuletTGAURL;
                            }
                        justAcquiredAmuletTGAURL =
                            getBalancePage->getResponse( 
                                "amulet_tga_url" );
                        }
                    }                
                else {
                    // already know we have it
                    justAcquiredAmuletID = 0;
                    }
                
                amuletPointCount = 
                    getBalancePage->getResponseInt( 
                        "amulet_point_count" );
                
                amuletHoldPenaltyPerMinute =
                    getBalancePage->getResponseInt( 
                        "amulet_hold_penalty_per_minute" );

                int secondsHeld =
                    getBalancePage->getResponseInt( 
                        "amulet_seconds_held" );
                
                int partialMinute = secondsHeld % 60;
                

                amuletBaseTime = game_time( NULL ) - partialMinute;
                

                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == depositDisplayPage ) {
            if( depositDisplayPage->checkSignal( "done" ) ) {
                userBalance = 
                    depositDisplayPage->getResponseDouble( "dollarBalance" );
                
                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == existingAccountPage ) {
            if( existingAccountPage->checkSignal( "done" ) ) {
                currentGamePage = accountCheckPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == menuPage ) {
            if( menuPage->checkSignal( "deposit" ) ) {
                
                currentGamePage = getDepositFeesPage;
                depositPage->setEmailFieldCanFocus( false );
                currentGamePage->base_makeActive( true );
                }
            else if( menuPage->checkSignal( "withdraw" ) ) {
                
                currentGamePage = withdrawPage;
                currentGamePage->base_makeActive( true );
                }
            else if( menuPage->checkSignal( "newGame" ) ) {
                
                currentGamePage = createGamePage;
                currentGamePage->base_makeActive( true );
                }
            else if( menuPage->checkSignal( "join" ) ) {
                GameRecord r = menuPage->getJoinedGame();
                
                if( r.isTournament ) {
                    enterTournamentPage->setTournamentInfo( r );
                    
                    currentGamePage = enterTournamentPage;
                    currentGamePage->base_makeActive( true );
                    }
                else {
                    char *dollarString = autoSprintf( "%.2f", r.dollarAmount );
                
                    joinGamePage->setupRequestParameterSecurity();
                    
                    joinGamePage->setParametersFromString( "dollar_amount",
                                                           dollarString );
                    delete [] dollarString;

                    if( r.isExp ) {
                        joinGamePage->setActionParameter( "game_type", 1 );
                        }
                    else {
                        joinGamePage->setActionParameter( "game_type", 0 );
                        }
                    
                    currentGamePage = joinGamePage;
                    currentGamePage->base_makeActive( true );
                    }
                }
            }
        else if( currentGamePage == withdrawPage ) {
            if( withdrawPage->checkSignal( "back" ) ) {
                
                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            else if( withdrawPage->checkSignal( "sendCheck" ) ) {
                
                currentGamePage = sendCheckPage;
                currentGamePage->base_makeActive( true );
                }
            else if( withdrawPage->checkSignal( "sendCheckGlobal" ) ) {
                
                currentGamePage = sendCheckGlobalPage;
                currentGamePage->base_makeActive( true );
                }
            else if( withdrawPage->checkSignal( "transfer" ) ) {
                
                currentGamePage = accountTransferPage;
                currentGamePage->base_makeActive( true );
                }
            else if( withdrawPage->checkSignal( "inPerson" ) ) {
                
                inPersonMode = true;
                
                currentGamePage = inPersonPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == sendCheckPage ) {
            if( sendCheckPage->checkSignal( "back" ) ) {
                
                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            else if( sendCheckPage->checkSignal( "moreInfoNeeded" ) ) {
                extendedMessagePage->setMessageKey( 
                    "moreWithdrawalInfoNeededMessage" );
                
                currentGamePage = extendedMessagePage;
                currentGamePage->base_makeActive( true );
                }
            else if( sendCheckPage->isResponseReady() ) {
                withdrawalDisplayPage->setDeltaAmount( 
                    sendCheckPage->getWithdrawalAmount() );
                
                currentGamePage = withdrawalDisplayPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == sendCheckGlobalPage ) {
            if( sendCheckGlobalPage->checkSignal( "back" ) ) {
                
                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            else if( sendCheckGlobalPage->checkSignal( "moreInfoNeeded" ) ) {
                extendedMessagePage->setMessageKey( 
                    "moreWithdrawalInfoNeededMessage" );
                
                currentGamePage = extendedMessagePage;
                currentGamePage->base_makeActive( true );
                }
            else if( sendCheckGlobalPage->isResponseReady() ) {
                withdrawalDisplayPage->setDeltaAmount( 
                    sendCheckGlobalPage->getWithdrawalAmount() );
                
                currentGamePage = withdrawalDisplayPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == accountTransferPage ) {
            if( accountTransferPage->checkSignal( "back" ) ) {
                
                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            else if( accountTransferPage->isResponseReady() ) {
                withdrawalDisplayPage->setDeltaAmount( 
                    accountTransferPage->getWithdrawalAmount() );
                
                currentGamePage = withdrawalDisplayPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == inPersonPage ) {
            if( inPersonPage->isResponseReady() ) {
                
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == withdrawalDisplayPage ) {
            if( withdrawalDisplayPage->checkSignal( "done" ) ) {
                userBalance = 
                    withdrawalDisplayPage->getResponseDouble( 
                        "dollarBalance" );
                
                currentGamePage = menuOrAmuletPage();
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == createGamePage ) {
            if( createGamePage->checkSignal( "back" ) ) {
                
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            else if( createGamePage->checkSignal( "created" ) ) {
                
                currentGamePage = waitGamePage;
                currentGamePage->base_makeActive( true );
                }
            else if( createGamePage->checkSignal( "dropAmulet" ) ) {
                
                currentGamePage = dropAmuletPage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == waitGamePage ) {
            if( waitGamePage->checkSignal( "back" ) ) {
                currentGamePage = leaveGamePage;
                currentGamePage->base_makeActive( true );
                }
            else if( waitGamePage->checkSignal( "started" ) ) {
                currentGamePage = playGamePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == leaveGamePage ) {
            if( leaveGamePage->isResponseReady() ) {

                double buyIn = leaveGamePage->getResponseDouble( 
                    "buy_in_dollar_amount" );
                
                double payout = leaveGamePage->getResponseDouble( 
                        "payout_dollar_amount" );

                int vsOneCoins = leaveGamePage->getResponseInt( 
                    "payout_vs_one_coins" );

                if( buyIn != 0 ) {
                    // buy-in happened

                    // so there's a real cash-out
                    depositDisplayPage->setDeltaAmount( payout );
                    depositDisplayPage->setLeftGame( true );
                    depositDisplayPage->setBuyIn( buyIn );
                    depositDisplayPage->setVsOneCoins( vsOneCoins );

                    if( vsOneCoins != 0 ) {
                        playChime();
                        }

                    currentGamePage = depositDisplayPage;
                    currentGamePage->base_makeActive( true );
                    }
                else {
                    // buy-in didn't happen
                    // left while waiting for game to start

                    // don't bother showing payout
                    currentGamePage = getBalancePage;
                    currentGamePage->base_makeActive( true );
                    }
                }
            }
        else if( currentGamePage == dropAmuletPage ) {
            if( dropAmuletPage->isResponseReady() ) {
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == enterTournamentPage ) {
            if( enterTournamentPage->isResponseReady() ) {
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            else if( enterTournamentPage->checkSignal( "back" ) ) {
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == joinGamePage ) {
            if( joinGamePage->isResponseReady() ) {
                currentGamePage = waitGamePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == playGamePage ) {
            if( playGamePage->checkSignal( "back" ) ) {
                currentGamePage = leaveGamePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == extendedMessagePage ) {
            if( extendedMessagePage->checkSignal( "done" ) ) {
                currentGamePage = getBalancePage;
                currentGamePage->base_makeActive( true );
                }
            }
        else if( currentGamePage == getAmuletPage ) {
            if( getAmuletPage->checkSignal( "done" ) ) {
                currentGamePage = menuPage;
                currentGamePage->base_makeActive( true );
                }
            }
        }


    // now draw stuff AFTER all updates
    drawFrameNoUpdate( true );



    // draw tail end of pause screen, if it is still visible
    if( pauseScreenFade > 0 ) {
        drawPauseScreen();
        }
    }



void drawFrameNoUpdate( char inUpdate ) {

    if( currentGamePage != NULL ) {
        currentGamePage->base_draw( lastScreenViewCenter, viewWidth );
        }

    }



// store mouse data for use as unguessable randomizing data
// for key generation, etc.
#define MOUSE_DATA_BUFFER_SIZE 20
int mouseDataBufferSize = MOUSE_DATA_BUFFER_SIZE;
int nextMouseDataIndex = 0;
// ensure that stationary mouse data (same value over and over)
// doesn't overwrite data from actual motion
float lastBufferedMouseValue = 0;
float mouseDataBuffer[ MOUSE_DATA_BUFFER_SIZE ];



void pointerMove( float inX, float inY ) {

    // save all mouse movement data for key generation
    float bufferValue = inX + inY;
    // ignore mouse positions that are the same as the last one
    // only save data when mouse actually moving
    if( bufferValue != lastBufferedMouseValue ) {
        
        mouseDataBuffer[ nextMouseDataIndex ] = bufferValue;
        lastBufferedMouseValue = bufferValue;
        
        nextMouseDataIndex ++;
        if( nextMouseDataIndex >= mouseDataBufferSize ) {
            nextMouseDataIndex = 0;
            }
        }
    

    if( isPaused() ) {
        return;
        }
    
    if( currentGamePage != NULL ) {
        currentGamePage->base_pointerMove( inX, inY );
        }
    }



void pointerDown( float inX, float inY ) {
    if( isPaused() ) {
        return;
        }
    
    if( currentGamePage != NULL ) {
        currentGamePage->base_pointerDown( inX, inY );
        }
    }



void pointerDrag( float inX, float inY ) {
    if( isPaused() ) {
        return;
        }
    
    if( currentGamePage != NULL ) {
        currentGamePage->base_pointerDrag( inX, inY );
        }
    }



void pointerUp( float inX, float inY ) {
    if( isPaused() ) {
        return;
        }
    
    if( currentGamePage != NULL ) {
        currentGamePage->base_pointerUp( inX, inY );
        }
    }







void keyDown( unsigned char inASCII ) {

    // taking screen shot is ALWAYS possible
    if( inASCII == '=' ) {    
        saveScreenShot( "screen" );
        }
    

    
    if( isPaused() ) {
        // block general keyboard control during pause


        switch( inASCII ) {
            case 13:  // enter
                // unpause
                pauseGame();
                break;
            }
        
        
        if( inASCII == 127 || inASCII == 8 ) {
            // subtract from it

            deleteCharFromUserTypedMessage();

            holdDeleteKeySteps = 0;
            // start with long delay until first repeat
            stepsBetweenDeleteRepeat = (int)( 30 / frameRateFactor );
            }
        else if( inASCII >= 32 ) {
            // add to it
            if( currentUserTypedMessage != NULL ) {
                
                char *oldMessage = currentUserTypedMessage;

                currentUserTypedMessage = autoSprintf( "%s%c", 
                                                       oldMessage, inASCII );
                delete [] oldMessage;
                }
            else {
                currentUserTypedMessage = autoSprintf( "%c", inASCII );
                }
            }
        
        return;
        }
    
    
    if( currentGamePage != NULL ) {
        currentGamePage->base_keyDown( inASCII );
        }

    
    switch( inASCII ) {
        case 'm':
        case 'M': {
#ifdef USE_MALLINFO
            struct mallinfo meminfo = mallinfo();
            printf( "Mem alloc: %d\n",
                    meminfo.uordblks / 1024 );
#endif
            }
            break;
        }
    }



void keyUp( unsigned char inASCII ) {
    if( inASCII == 127 || inASCII == 8 ) {
        // delete no longer held
        // even if pause screen no longer up, pay attention to this
        holdDeleteKeySteps = -1;
        }

    if( ! isPaused() ) {
        if( currentGamePage != NULL ) {
            currentGamePage->base_keyUp( inASCII );
            }
        }

    }







void specialKeyDown( int inKey ) {
    if( isPaused() ) {
        return;
        }
    

    if( currentGamePage != NULL ) {
        currentGamePage->base_specialKeyDown( inKey );
        }

	}



void specialKeyUp( int inKey ) {
    if( isPaused() ) {
        return;
        }
    

    if( currentGamePage != NULL ) {
        currentGamePage->base_specialKeyUp( inKey );
        }
	} 




char getUsesSound() {
    
    return ! musicOff;
    }









void drawString( const char *inString, char inForceCenter ) {
    
    setDrawColor( 1, 1, 1, 0.75 );

    doublePair messagePos = lastScreenViewCenter;

    TextAlignment align = alignCenter;
    
    if( initDone && !inForceCenter ) {
        // transparent message
        setDrawColor( 1, 1, 1, 0.75 );

        // stick messages in corner
        messagePos.x -= viewWidth / 2;
        
        messagePos.x +=  20;
    

    
        messagePos.y += (viewWidth * viewHeightFraction) /  2;
    
        messagePos.y -= 32;

        align = alignLeft;
        }
    else {
        // fully opaque message
        setDrawColor( 1, 1, 1, 1 );

        // leave centered
        }
    

    int numLines;
    
    char **lines = split( inString, "\n", &numLines );
    
    for( int i=0; i<numLines; i++ ) {
        

        mainFont->drawString( lines[i], messagePos, align );
        messagePos.y -= 32;
        
        delete [] lines[i];
        }
    delete [] lines;
    }





// called by platform to get more samples
void getSoundSamples( Uint8 *inBuffer, int inLengthToFillInBytes ) {
    // for now, do nothing (no sound)
    }




void playChime() {
    if( ! musicOff ) {
        playSoundSprite( moveWaitingSoundSprite );
        }
    }



void playChipSound( int inSound ) {
    if( ! musicOff ) {
        playSoundSprite( chipSoundSprites[inSound] );
        }
    }
