#include "WaitGamePage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"

#include "serialWebRequests.h"

#include "balanceFormat.h"

#include "amuletCache.h"

#include "chime.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"



extern Font *mainFont;


extern char *userEmail;
extern char *accountKey;
extern int serverSequenceNumber;

extern double userBalance;
extern double transferCost;

extern char waitingAmuletGame;



const char *waitGamePartNames[4] = { "status", "dollar_amount",
                                     "otherGameList", "activeUserCount" };

WaitGamePage::WaitGamePage()
        : ServerActionPage( "wait_game_start", 4, waitGamePartNames ),
          mCancelButton( mainFont, 0, -200, 
                         translate( "cancel" ) ),
          mOKButton( mainFont, 0, -100, 
                     translate( "okay" ) ),
          mResponseProcessed( false ),
          mActivePlayerCount( -1 ) {



    addComponent( &mCancelButton );
    addComponent( &mOKButton );
    

    setButtonStyle( &mCancelButton );
    setButtonStyle( &mOKButton );
    

    mCancelButton.addActionListener( this );
    mOKButton.addActionListener( this );
    
    mOKButton.setVisible( false );
    }


        
WaitGamePage::~WaitGamePage() {
    }






void WaitGamePage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mCancelButton ) {
        if( mWebRequest != -1 ) {
            clearWebRequestSerial( mWebRequest );
            mWebRequest = -1;
            }

        mOtherGames.deleteAll();
        setSignal( "back" );
        }
    else if( inTarget == &mOKButton ) {
        mOKButton.setVisible( false );
        mCancelButton.setVisible( true );
        setSignal( "started" );
        }
    }







void WaitGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    
    doublePair pos = { 0, 0 };

    if( !mOKButton.isVisible() ) {
        
        drawMessage( "waitingStart", pos );

        if( mOtherGames.size() > 0 ) {
        
            pos.y -= 72;
        
            drawMessage( "otherGames", pos );
        
                    
            pos.y -= 48;

        
            char **amountStrings = new char*[ mOtherGames.size() ];

            for( int i=0; i<mOtherGames.size(); i++ ) {
                amountStrings[i] = 
                    formatDollarStringLimited( 
                        mOtherGames.getElementDirect( i ), false );
                }
        

            char *listString = join( amountStrings, 
                                     mOtherGames.size(), "   " );

            for( int i=0; i<mOtherGames.size(); i++ ) {
                delete [] amountStrings[i];
                }
            delete [] amountStrings;
        

            drawMessage( listString, pos );
            delete [] listString;
            }

        if( mActivePlayerCount > 0 ) {
            char *activePlayerString = 
                formatDollarStringLimited( mActivePlayerCount, false, false );
    
            const char *key = "playersLong";
            if( mActivePlayerCount == 1 ) {
                key = "playerLong";
                }

            char *activePlayerDisplay = autoSprintf( translate( key ),
                                                     activePlayerString );
            delete [] activePlayerString;
    
            pos.x = 0;
            pos.y = 144;
            
            mainFont->drawString( activePlayerDisplay, pos, alignCenter );
            
            delete [] activePlayerDisplay;
            }
        

        }

    
    
    if( waitingAmuletGame ) {
        pos.x = 0;
        pos.y = 230;
        
        drawAmuletDisplay( pos );
        }
    
    if( mOKButton.isVisible() ) {
        char *dollarAmount = getResponse( "dollar_amount" );

        double value;
            
        int numRead = sscanf( dollarAmount, "%lf", &value );

        delete [] dollarAmount;
        
        
        char * formattedAmount;
        
        if( numRead == 1 ) {
            
            formattedAmount = formatDollarStringLimited( value, false );
            }
        else {
            formattedAmount = stringDuplicate( translate( "unknownStakes" ) );
            }
        
            
        doublePair pos = { 0, 100 };
        
        drawMessage( translate( "amuletGameStakesStarted" ), pos );
        pos.y = 50;
        
        drawMessage( formattedAmount, pos );
        
        delete [] formattedAmount;
        }
    
    }



void WaitGamePage::makeActive( char inFresh ) {
    ServerActionPage::makeActive( inFresh );
    
    if( !inFresh ) {
        return;
        }
    
    mResponseProcessed = false;
    }



void WaitGamePage::step() {
    ServerActionPage::step();

    if( isResponseReady() && !mResponseProcessed ) {
        
        mActivePlayerCount = getResponseInt( "activeUserCount" );

        char *otherGameList = getResponse( "otherGameList" );
        
        int numParts;
        char **parts = split( otherGameList, "#", &numParts );

        delete [] otherGameList;

        mOtherGames.deleteAll();
        
        for( int i=0; i<numParts; i++ ) {
            double value;
            
            int numRead = sscanf( parts[i], "%lf", &value );
            
            if( numRead == 1 ) {
                mOtherGames.push_back( value );
                }
            delete [] parts[i];
            }
        delete [] parts;
        
        
        mResponseProcessed = true;

        char *status = getResponse( "status" );

        if( strcmp( status, "started" ) == 0) {
            
            if( waitingAmuletGame ) {
                mCancelButton.setVisible( false );
                mOKButton.setVisible( true );
                // wake player up
                playChime();
                }
            else {
                // jump right into game
                setSignal( "started" );
                }
            }
        else if( strcmp( status, "waiting" ) == 0 ) {
            // keep waiting
            startRequest();
            mResponseProcessed = false;
            }
        
        delete [] status;
        }
    }







