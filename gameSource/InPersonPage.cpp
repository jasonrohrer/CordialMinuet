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







InPersonPage::InPersonPage()
        : ServerActionPage( "check_in_person_code", true ),
          mCodeField( mainFont, 33, 0, 12, false, 
                      translate( "code" ),
                      NULL,
                      // forbid spaces
                      " " ),
          mStartButton( mainFont, 0, -200, 
                        translate( "start" ) ) {



    addComponent( &mStartButton );
    

    setButtonStyle( &mStartButton );
    

    mStartButton.addActionListener( this );

    addComponent( &mCodeField );
    mCodeField.addActionListener( this );
    }


        
InPersonPage::~InPersonPage() {
    }






void InPersonPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mStartButton ) {
        setStatus( NULL, false );

        setupRequestParameterSecurity();
        
        setParametersFromField( "code", &mCodeField );
                
        
        mStartButton.setVisible( false );
        
        mCodeField.setActive( false );
        mCodeField.unfocus();
        startRequest();
        }
    }



void InPersonPage::makeActive( char inFresh ) {
    
    if( ! isActionInProgress() ) {
        makeFieldsActive();
        }
    


    if( !inFresh ) {
        return;
        }

    mCodeField.setText( "" );
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfStartButtonVisible();
    }



void InPersonPage::makeNotActive() {
    // paused? clear delete-held status
    mCodeField.unfocus();
    }




void InPersonPage::makeFieldsActive() {
    mCodeField.focus();
    mCodeField.setActive( true );
    }



void InPersonPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    doublePair labelPos = { 0, 138 };
    
    
    drawMessage( "seeCashier", labelPos, false );    
    }



void InPersonPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        checkIfStartButtonVisible();

        if( ! mCodeField.isActive() ) {
            makeFieldsActive();
            }
        }
    }



void InPersonPage::checkIfStartButtonVisible() {
    char visible = true;

    char *code = mCodeField.getText();
    
    if( strlen( code ) < 2 ) {
        visible = false;
        }

    delete [] code;

    mStartButton.setVisible( visible );
    }

    

void InPersonPage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }


    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        
        if( mCodeField.isFocused() && mStartButton.isVisible() ) {
            // process enter on last field
            actionPerformed( &mStartButton );
            
            return;
            }
        }
    }



