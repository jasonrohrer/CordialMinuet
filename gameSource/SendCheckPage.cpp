#include "SendCheckPage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"

#include "minorGems/network/web/URLUtils.h"



extern Font *mainFont;


extern char *accountKey;
extern int serverSequenceNumber;

extern double userBalance;
extern double checkCost;





SendCheckPage::SendCheckPage()
        : ServerActionPage( "send_us_check", true),
          mAmountPicker( mainFont, 34, 201, 4, 2, 
                         translate( "withdrawMoney" ) ),
          mNameField( mainFont, 33, 126, 12, false, 
                      translate( "name" ),
                      "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                      "abcdefghijklmnopqrstuvwxyz"
                      ".'- " ),
          mAddress1Field( mainFont, 33, 62, 12, false, 
                          translate( "address1" ),
                          "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                          "abcdefghijklmnopqrstuvwxyz"
                          ".'- ,0123456789#" ),
          mAddress2Field( mainFont, 33, -2, 12, false, 
                          translate( "address2" ),
                          "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                          "abcdefghijklmnopqrstuvwxyz"
                          ".'- ,0123456789#" ),
          mCityField( mainFont, 33, -66, 12, false, 
                      translate( "city" ),
                      "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                      "abcdefghijklmnopqrstuvwxyz"
                      ".'- " ),
          mStateField( mainFont, -132, -130, 2, true, 
                       translate( "state" ),
                       "ABCDEFGHIJKLMNOPQRSTUVWXYZ" ),
          mZipField( mainFont, 121, -130, 10, true,
                     translate( "zip" ),
                     "0123456789-" ),
          mSendCheckButton( mainFont, 150, -200, 
                             translate( "sendCheckButton" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mSendCheckButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mSendCheckButton );
    setButtonStyle( &mCancelButton );
    

    mSendCheckButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    addComponent( &mAmountPicker );
    
    
    

    mFields[0] = &mNameField;
    mFields[1] = &mAddress1Field;
    mFields[2] = &mAddress2Field;
    mFields[3] = &mCityField;
    mFields[4] = &mStateField;
    mFields[5] = &mZipField;

    for( int i=0; i<NUM_SEND_CHECK_FIELDS; i++ ) {
        addComponent( mFields[i] );
        mFields[i]->addActionListener( this );
        }
    mStateField.setMaxLength( 2 );
    mZipField.setMaxLength( 10 );
    
    
    /*
    // for testing
    mCardNumberField.setText( "4242424242424242" );
    //mCardNumberField.setText( "4000000000000002" );
    mEmailField.setText( "jasonrohrer@fastmail.fm" );
    mExpireMonthField.setText( "11" );
    mExpireYearField.setText( "2015" );
    mCVCField.setText( "137" );
    */
    }


        
SendCheckPage::~SendCheckPage() {
    }



void SendCheckPage::setParametersFromField( const char *inParamName,
                                            TextField *inField,
                                            const char *inHmacKey ) {
    
    char *value = inField->getText();
    char *value_hmac = hmac_sha1( inHmacKey, value );
        
    char *encodedValue = URLUtils::urlEncode( value );
    delete [] value;

    setActionParameter( inParamName, encodedValue );
    delete [] encodedValue;
        
    char *hmacParamName = autoSprintf( "%s_hmac", inParamName );

    setActionParameter( hmacParamName, value_hmac );
    delete [] hmacParamName;
    delete [] value_hmac;
    }



void SendCheckPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mSendCheckButton ) {
        setStatus( NULL, false );

        setActionParameter( "check_sequence_number", serverSequenceNumber );
        
        char *pureKey = getPureAccountKey();
        
        char *hmacKey = autoSprintf( "%s%d", pureKey, serverSequenceNumber );
        delete [] pureKey;
        
        
        setParametersFromField( "name", &mNameField, hmacKey );
        setParametersFromField( "address1", &mAddress1Field, hmacKey );
        setParametersFromField( "address2", &mAddress2Field, hmacKey );
        setParametersFromField( "city", &mCityField, hmacKey );
        setParametersFromField( "state", &mStateField, hmacKey );
        setParametersFromField( "zip", &mZipField, hmacKey );
        

        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        char *dollarAmountHmac = hmac_sha1( hmacKey, 
                                            dollarAmountString );
        
        setActionParameter( "dollar_amount", dollarAmountString );
        delete [] dollarAmountString;

        setActionParameter( "dollar_amount_hmac", dollarAmountHmac );
        delete [] dollarAmountHmac;
        
        delete [] hmacKey;
        
        mSendCheckButton.setVisible( false );
        mCancelButton.setVisible( false );
        
        for( int i=0; i<NUM_SEND_CHECK_FIELDS; i++ ) {
            mFields[i]->setActive( false );
            mFields[i]->unfocus();
            }

        mAmountPicker.setAdjustable( false );
        
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void SendCheckPage::makeActive( char inFresh ) {
    
    if( ! isActionInProgress() ) {
        makeFieldsActive();
        }
    


    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfSendCheckButtonVisible();

    // fix later with balance and check fee when page made active
    mAmountPicker.setMin( checkCost + 0.01 );
    mAmountPicker.setValue( userBalance );
    }



void SendCheckPage::makeNotActive() {
    // paused? clear delete-held status
    for( int i=0; i<NUM_SEND_CHECK_FIELDS; i++ ) {
        mFields[i]->unfocus();
        }
    }




void SendCheckPage::makeFieldsActive() {
    mNameField.focus();
        
    for( int i=0; i<NUM_SEND_CHECK_FIELDS; i++ ) {
        mFields[i]->setActive( true );
        }
    
    mAmountPicker.setAdjustable( true );
    }



void SendCheckPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    
    if( checkCost > 0 ) {
        doublePair labelPos = { 0, 264 };

        char *message = autoSprintf( translate( "feeSubtracted" ),
                                     checkCost );
        
        drawMessage( message, labelPos, false );    
        delete [] message;
        }
    }



void SendCheckPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        checkIfSendCheckButtonVisible();

        if( ! mFields[0]->isActive() ) {
            makeFieldsActive();
            }
        mCancelButton.setVisible( true );
        }
    }



double SendCheckPage::getWithdrawalAmount() {
    return mAmountPicker.getValue();
    }



void SendCheckPage::checkIfSendCheckButtonVisible() {
    char visible = true;

    char *name = mNameField.getText();
    
    if( strlen( name ) < 1 ) {
        visible = false;
        }
    delete [] name;
    
    
    char *address1 = mAddress1Field.getText();
    
    if( strlen( address1 ) < 1 ) {
        visible = false;
        }

    delete [] address1;


    char *city = mStateField.getText();
    
    if( strlen( city ) < 2 ) {
        visible = false;
        }

    delete [] city;


    char *state = mStateField.getText();
    
    if( strlen( state ) < 2 ) {
        visible = false;
        }

    delete [] state;


    char *zip = mZipField.getText();
    
    if( strlen( zip ) < 5 ||
        ( strlen( zip ) > 5 && strlen( zip ) < 10 ) ) {
        visible = false;
        }

    delete [] zip;


    mSendCheckButton.setVisible( visible );
    }




void SendCheckPage::switchFields( int inDir ) {
    for( int i=0; i<NUM_SEND_CHECK_FIELDS; i++ ) {
        
        if( mFields[i]->isFocused() ) {

            int next = i + inDir;
            
            if( next >= NUM_SEND_CHECK_FIELDS ) {
                next = 0;
                }
            else if( next < 0 ) {
                next = NUM_SEND_CHECK_FIELDS - 1;
                }
            mFields[next]->focus();
            return;
            }
        }
    }

    

void SendCheckPage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }

    if( inASCII == 9 ) {
        // tab
        switchFields();
        return;
        }

    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        
        if( mZipField.isFocused() && mSendCheckButton.isVisible() ) {
            // process enter on last field
            actionPerformed( &mSendCheckButton );
            
            return;
            }
        else {
            switchFields();
            }
        }
    }



void SendCheckPage::specialKeyDown( int inKeyCode ) {
    if( isActionInProgress() ) {
        return;
        }

    if( inKeyCode == MG_KEY_DOWN ) {
        switchFields();
        return;
        }
    else if( inKeyCode == MG_KEY_UP ) {
        switchFields(-1);
        return;
        }
    }
