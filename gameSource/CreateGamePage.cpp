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




const char *createGamePartNames[1] = { "gameID" };

CreateGamePage::CreateGamePage()
        : ServerActionPage( "create_game", 1, createGamePartNames ),
          mAmountPicker( mainFont, 34, 75, 6, 2, 
                         translate( "buyIn" ) ),
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

        setActionParameter( "request_sequence_number", serverSequenceNumber );
        
        char *pureKey = getPureAccountKey();
        
        char *hmacKey = autoSprintf( "%s%d", pureKey, serverSequenceNumber );
        delete [] pureKey;

        char *tagString = autoSprintf( "%d", time( NULL ) );
        char *request_tag = hmac_sha1( hmacKey, tagString );
        delete [] tagString;

        setActionParameter( "request_tag", request_tag );
        delete [] request_tag;
        
        
        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        char *dollarAmountHmac = hmac_sha1( hmacKey, 
                                            dollarAmountString );
        
        setActionParameter( "dollar_amount", dollarAmountString );
        delete [] dollarAmountString;

        setActionParameter( "dollar_amount_hmac", dollarAmountHmac );
        delete [] dollarAmountHmac;
        
        delete [] hmacKey;
        
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
    }



void CreateGamePage::makeFieldsActive() {
    mAmountPicker.setAdjustable( true );
    }



void CreateGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    
    }



void CreateGamePage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
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



