#include "DepositDisplayPage.h"

#include "buttonStyle.h"
#include "balanceFormat.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;
extern Font *numbersFontFixed;


extern double userBalance;


extern int justAcquiredAmuletID;
extern char *justAcquiredAmuletTGAURL;

extern int amuletID;
extern int amuletPointCount;
extern int amuletHoldPenaltyPerMinute;
extern int amuletBaseTime;



const char *getBalancePartNames[6] = { "dollarBalance",
                                       "amulet_id",
                                       "amulet_tga_url",
                                       "amulet_point_count",
                                       "amulet_seconds_held",
                                       "amulet_hold_penalty_per_minute" };


DepositDisplayPage::DepositDisplayPage()
        : ServerActionPage( "get_balance",
                            6, getBalancePartNames, true ),
          mWithdraw( false ),
          mLeftGame( false ),
          mOkayButton( mainFont, 0, -200, 
                       translate( "OK" ) ),
          mOldBalance( 0 ),
          mDeltaAmount( 0 ),
          mVsOneCoins( 0 ),
          mAmuletIconSprite( loadSprite( "amuletIcon.tga", true ) ) {

    addComponent( &mOkayButton );
    setButtonStyle( &mOkayButton );
    mOkayButton.addActionListener( this );
    }


        
DepositDisplayPage::~DepositDisplayPage() {
    freeSprite( mAmuletIconSprite );
    }



void DepositDisplayPage::setWithdraw( char inWithdraw ) {
    mWithdraw = inWithdraw;
    }


void DepositDisplayPage::setLeftGame( char inLeftGame ) {
    mLeftGame = inLeftGame;
    }



void DepositDisplayPage::setBuyIn( double inBuyIn ) {
    mBuyIn = inBuyIn;
    }



void DepositDisplayPage::setVsOneCoins( int inCoins ) {
    mVsOneCoins = inCoins;
    }


        
void DepositDisplayPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mOkayButton ) {
        setSignal( "done" );
        }
    }



void DepositDisplayPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }
    mOldBalance = userBalance;
    
    mOkayButton.setVisible( false );

    startRequest();
    }



void DepositDisplayPage::setDeltaAmount( double inAmount ) {
    mDeltaAmount = inAmount;
    }



void DepositDisplayPage::step() {
    if( isResponseReady() ) {
        
        if( ! mOkayButton.isVisible() ) {
            
            justAcquiredAmuletID = 
                getResponseInt( "amulet_id" );
            
            if( amuletID != justAcquiredAmuletID ) {
                
                amuletID = justAcquiredAmuletID;
                
                if( justAcquiredAmuletID != 0 ) {
                    
                    if( justAcquiredAmuletTGAURL != NULL ) {
                        delete [] justAcquiredAmuletTGAURL;
                        }
                    justAcquiredAmuletTGAURL =
                        getResponse( 
                            "amulet_tga_url" );
                    }
                }    
            else {
                // already know we have it
                justAcquiredAmuletID = 0;
                }
        
            amuletPointCount = 
                getResponseInt( 
                    "amulet_point_count" );

            amuletHoldPenaltyPerMinute =
                getResponseInt( 
                    "amulet_hold_penalty_per_minute" );
            
            int secondsHeld =
                getResponseInt( 
                    "amulet_seconds_held" );
            
            int partialMinute = secondsHeld % 60;
            
            
            amuletBaseTime = game_time( NULL ) - partialMinute;
            
            
            mOkayButton.setVisible( true );
            }
        }
    ServerActionPage::step();
    }



void DepositDisplayPage::draw( doublePair inViewCenter, 
                               double inViewSize ) {
    
    setDrawColor( 1, 1, 1, 1 );
    
    doublePair pos = { 0, 0 };
    

    double spacing = 64;
    
    if( mLeftGame ) {
        spacing = 54;
        }

    pos.x = 0;
    pos.y += 192;

    if( mLeftGame ) {
        pos.y += 2 * spacing;
        pos.y -= 31;
        }
    
    mainFont->drawString( translate( "oldBalance" ), pos, alignRight );

    char fullPrecisionBalance = false;
    char fullPrecisionDelta = false;
    

    // test both to see if either needs full precisoin
    char *oldBalanceString = 
        formatBalance( mOldBalance, false, &fullPrecisionBalance );

    char *deltaString = formatBalance( mDeltaAmount, false, 
                                       &fullPrecisionDelta );


    delete [] oldBalanceString;
    delete [] deltaString;
    
    char fullPrecision = fullPrecisionBalance || fullPrecisionDelta;

    // reformat with same precision setting for all

    oldBalanceString = formatBalance( mOldBalance, fullPrecision );

    deltaString = formatBalance( mDeltaAmount, fullPrecision );
    


    char *buyInString = formatBalance( mBuyIn, fullPrecision );

    // estimate new balance for width measurements
    double newBalanceEstimate = mOldBalance;
    
    if( mWithdraw ) {
        newBalanceEstimate -= mDeltaAmount;
        }
    else {
        newBalanceEstimate += mDeltaAmount;
        }

    if( mLeftGame ) {
        newBalanceEstimate -= mBuyIn;
        }
    
    char *newBalanceEstimateString = 
        formatBalance( newBalanceEstimate, fullPrecision );


    double maxWidth = numbersFontFixed->measureString( oldBalanceString );
    
    double otherWidth = numbersFontFixed->measureString( deltaString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }

    otherWidth = numbersFontFixed->measureString( buyInString );
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }
    

    
    otherWidth = numbersFontFixed->measureString( newBalanceEstimateString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }
    
    delete [] newBalanceEstimateString;


    double xOffset = maxWidth / 2;
        


    pos.x = xOffset;
    pos.y -= spacing;
    numbersFontFixed->drawString( oldBalanceString, pos, alignRight );
    
    delete [] oldBalanceString;

    
    if( mLeftGame ) {
        pos.x = 0;
        pos.y -= spacing;
        
        mainFont->drawString( translate( "joinedWithAmount" ), 
                                      pos, alignRight );
    
        pos.x = xOffset;
        pos.y -= spacing;
        numbersFontFixed->drawString( buyInString, pos, alignRight );
        }
    delete [] buyInString;


    pos.x = 0;
    pos.y -= spacing;

    const char *amountKey = "addedAmount";
    
    if( mWithdraw ) {
        amountKey = "withdrawAmount";
        }
    else if( mLeftGame ) {
        amountKey = "leftWithAmount";
        }

    mainFont->drawString( translate( amountKey ), pos, alignRight );
    
    pos.x = xOffset;
    pos.y -= spacing;
    numbersFontFixed->drawString( deltaString, pos, alignRight );
    
    delete [] deltaString;
    
    pos.x = 0;
    pos.y -= spacing;
 
    mainFont->drawString( translate( "newBalance" ), pos, alignRight );
    

    if( isResponseReady() ) {
        
        char *valueString = 
            formatBalance( getResponseDouble( "dollarBalance" ),
                           fullPrecision );
    
        pos.x = xOffset;
        pos.y -= spacing;
        numbersFontFixed->drawString( valueString, pos, alignRight );
    
        delete [] valueString;
        }


    if( mVsOneCoins != 0 ) {
        pos = mOkayButton.getPosition();
        
        pos.x -= 232;
        
        drawSprite( mAmuletIconSprite, pos );


        pos.x += 42;
        pos.y -= 3;

        char *vsOneCoinString;

        if( mVsOneCoins > 0 ) {
            vsOneCoinString = autoSprintf( "+%d", mVsOneCoins );
            }
        else {
            vsOneCoinString = autoSprintf( "%d", mVsOneCoins );
            }
        
        mainFont->drawString( vsOneCoinString, pos, alignLeft );
        
        delete [] vsOneCoinString;
        }
    
            
    }
