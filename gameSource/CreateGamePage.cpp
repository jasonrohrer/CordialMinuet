#include "CreateGamePage.h"

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
extern char *accountKey;
extern int serverSequenceNumber;

extern double userBalance;
extern double transferCost;





CreateGamePage::CreateGamePage()
        : ServerActionPage( "join_game" ),
          mAmountPicker( mainFont, 96, 75, 9, 2, 
                         translate( "$" ) ),
          mCreateButton( mainFont, 150, -200, 
                         translate( "create" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mCreateButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mCreateButton );
    setButtonStyle( &mCancelButton );
    

    mCreateButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    addComponent( &mAmountPicker );
    }


        
CreateGamePage::~CreateGamePage() {
    }






void CreateGamePage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mCreateButton ) {
        setStatus( NULL, false );
        

        setupRequestParameterSecurity();
        
        
        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        setParametersFromString( "dollar_amount", 
                                 dollarAmountString );
        delete [] dollarAmountString;
        
        mCreateButton.setVisible( false );
        mCancelButton.setVisible( false );
                
        mAmountPicker.setAdjustable( false );
        
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void CreateGamePage::makeActive( char inFresh ) {
    
    if( ! isActionInProgress() ) {
        makeFieldsActive();
        }    


    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfCreateButtonVisible();

    mAmountPicker.setMin( 0.01 );
    mAmountPicker.setMax( userBalance );

    // make room in case of huge balance
    if( userBalance < 9999999 ) {
        mAmountPicker.setPosition( 34, mAmountPicker.getPosition().y );
        }
    else {
        mAmountPicker.setPosition( 96, mAmountPicker.getPosition().y );
        }
    }



void CreateGamePage::makeFieldsActive() {
    mAmountPicker.setAdjustable( true );
    }



void CreateGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    
    doublePair pos = mAmountPicker.getPosition();
    
    pos.y += 96;
    
    pos.x += 3;

    setDrawColor( 1, 1, 1, 1 );
    mainFont->drawString( translate( "buyIn" ), pos, alignRight );
    }



void CreateGamePage::step() {
    ServerActionPage::step();
    
    
    if( isResponseReady() ) {
        setSignal( "created" );
        }
    else if( ! isActionInProgress() ) {
        checkIfCreateButtonVisible();

        makeFieldsActive();
        
        mCancelButton.setVisible( true );
        }

    }






void CreateGamePage::checkIfCreateButtonVisible() {
    char visible = true;


    mCreateButton.setVisible( visible );
    }

    

void CreateGamePage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }


    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        actionPerformed( &mCreateButton );
            
        return;
        }
    }



