#include "WaitGamePage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"

#include "serialWebRequests.h"

#include "balanceFormat.h"


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




const char *waitGamePartNames[2] = { "status", "otherGameList" };

WaitGamePage::WaitGamePage()
        : ServerActionPage( "wait_game_start", 2, waitGamePartNames ),
          mCancelButton( mainFont, 0, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mCancelButton );
    

    setButtonStyle( &mCancelButton );
    

    mCancelButton.addActionListener( this );
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
    }







void WaitGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    
    doublePair pos = { 0, 0 };
    
    drawMessage( "waitingStart", pos );

    if( mOtherGames.size() > 0 ) {
        
        pos.y -= 72;
        
        drawMessage( "otherGames", pos );
        
                    
        pos.y -= 48;

        
        char **amountStrings = new char*[ mOtherGames.size() ];

        for( int i=0; i<mOtherGames.size(); i++ ) {
            amountStrings[i] = 
                formatDollarStringLimited( mOtherGames.getElementDirect( i ),
                                           false );
            }
        

        char *listString = join( amountStrings, mOtherGames.size(), "   " );

        for( int i=0; i<mOtherGames.size(); i++ ) {
            delete [] amountStrings[i];
            }
        delete [] amountStrings;
        

        drawMessage( listString, pos );
        delete [] listString;
        }
    
    }



void WaitGamePage::step() {
    ServerActionPage::step();

    if( isResponseReady() ) {
        
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
        


        char *status = getResponse( "status" );

        if( strcmp( status, "started" ) == 0) {
            setSignal( "started" );
            }
        else if( strcmp( status, "waiting" ) == 0 ) {
            // keep waiting
            startRequest();
            }
        
        delete [] status;
        }
    }







