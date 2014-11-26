#include "InPersonPage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"



extern Font *mainFont;


extern char *userEmail;






InPersonPage::InPersonPage()
        : ServerActionPage( "check_in_person_code", true ),
          mCodePicker( mainFont, 80, -100, 6, 0, translate( "code" ),
                       // don't use commas in code
                       false ),
          mStartButton( mainFont, 0, -200, 
                        translate( "start" ) ) {



    addComponent( &mStartButton );
    

    setButtonStyle( &mStartButton );
    

    mStartButton.addActionListener( this );

    addComponent( &mCodePicker );
    mCodePicker.setMax( 999999 );
    mCodePicker.setMin( 0 );
    }


        
InPersonPage::~InPersonPage() {
    }






void InPersonPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mStartButton ) {
        setStatus( NULL, false );

        setupRequestParameterSecurity();
        
        setActionParameter( "code", (int)( mCodePicker.getValue() ) );
                
        
        mStartButton.setVisible( false );
        
        mCodePicker.setAdjustable( false );
        
        startRequest();
        }
    }



void InPersonPage::makeActive( char inFresh ) {    


    if( !inFresh ) {
        return;
        }

    mCodePicker.setValue( 0 );
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfStartButtonVisible();
    }






void InPersonPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    doublePair labelPos = { 0, 138 };
    
    
    drawMessage( "seeCashier", labelPos, false );    

    labelPos.y -= 100;
    
    drawMessage( userEmail, labelPos, false );    
    }



void InPersonPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        checkIfStartButtonVisible();

        mCodePicker.setAdjustable( true );
        }
    }



void InPersonPage::checkIfStartButtonVisible() {
    char visible = true;

    double code = mCodePicker.getValue();
    
    if( code == 0 ) {
        visible = false;
        }

    mStartButton.setVisible( visible );
    }

    




