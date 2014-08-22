#include "AccountTransferPage.h"

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





AccountTransferPage::AccountTransferPage()
        : ServerActionPage( "account_transfer", true),
          mAmountPicker( mainFont, 176, 75, 6, 2, 
                         translate( "transferMoney" ) ),
          mEmailField( mainFont, 33, 0, 12, false, 
                       translate( "email" ),
                       NULL,
                       // forbid spaces
                       " " ),
          mTransferButton( mainFont, 150, -200, 
                           translate( "transferButton" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mTransferButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mTransferButton );
    setButtonStyle( &mCancelButton );
    

    mTransferButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    addComponent( &mAmountPicker );
    
    
    addComponent( &mEmailField );
    mEmailField.addActionListener( this );
    

    addServerErrorString( "RECIPIENT_NOT_FOUND", "recipientNotFound" );
    }


        
AccountTransferPage::~AccountTransferPage() {
    }






void AccountTransferPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mTransferButton ) {
        setStatus( NULL, false );

        setupRequestParameterSecurity();
        
        setParametersFromField( "recipient_email", &mEmailField );
                

        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        setParametersFromString( "dollar_amount", 
                                 dollarAmountString );
        delete [] dollarAmountString;
        
        mTransferButton.setVisible( false );
        mCancelButton.setVisible( false );
        
        mEmailField.setActive( false );
        mEmailField.unfocus();
        
        mAmountPicker.setAdjustable( false );
        
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void AccountTransferPage::makeActive( char inFresh ) {
    
    if( ! isActionInProgress() ) {
        makeFieldsActive();
        }
    


    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfTransferButtonVisible();

    // fix later with balance and check fee when page made active
    mAmountPicker.setMin( transferCost + 0.01 );
    mAmountPicker.setMax( userBalance );
    }



void AccountTransferPage::makeNotActive() {
    // paused? clear delete-held status
    mEmailField.unfocus();
    }




void AccountTransferPage::makeFieldsActive() {
    mEmailField.focus();
    mEmailField.setActive( true );

    mAmountPicker.setAdjustable( true );
    }



void AccountTransferPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    
    if( transferCost > 0 ) {
        doublePair labelPos = { 0, 138 };

        char *message = autoSprintf( translate( "feeSubtracted" ),
                                     transferCost );
        
        drawMessage( message, labelPos, false );    
        delete [] message;
        }
    }



void AccountTransferPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        checkIfTransferButtonVisible();

        if( ! mEmailField.isActive() ) {
            makeFieldsActive();
            }
        mCancelButton.setVisible( true );
        }
    }



double AccountTransferPage::getWithdrawalAmount() {
    return mAmountPicker.getValue();
    }



void AccountTransferPage::checkIfTransferButtonVisible() {
    char visible = true;

    char *email = mEmailField.getText();
    
    if( strlen( email ) < 5 ||
        strstr( email, "@" ) == NULL ||
        strstr( email, "." ) == NULL ) {
        visible = false;
        }
    if( strcmp( email, userEmail ) == 0 ) {
        // can't send transfer to self
        visible = false;
        }

    delete [] email;

    mTransferButton.setVisible( visible );
    }

    

void AccountTransferPage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }


    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        
        if( mEmailField.isFocused() && mTransferButton.isVisible() ) {
            // process enter on last field
            actionPerformed( &mTransferButton );
            
            return;
            }
        }
    }



