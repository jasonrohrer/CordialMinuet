#include "WaitGamePage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"

#include "serialWebRequests.h"


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




const char *waitGamePartNames[1] = { "status" };

WaitGamePage::WaitGamePage()
        : ServerActionPage( "wait_game_start", 1, waitGamePartNames ),
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
        setSignal( "back" );
        }
    }







void WaitGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    
    doublePair pos = { 0, 0 };
    
    drawMessage( "waitingStart", pos );    
    }



void WaitGamePage::step() {
    ServerActionPage::step();

    if( isResponseReady() ) {
        
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







