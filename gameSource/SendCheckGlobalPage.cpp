#include "SendCheckGlobalPage.h"

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


extern char *accountKey;
extern int serverSequenceNumber;

extern double userBalance;
extern double checkCostGlobal;





SendCheckGlobalPage::SendCheckGlobalPage()
        : ServerActionPage( "send_check", true),
          mAmountPicker( mainFont, 176, 201, 6, 2, 
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
          mCityField( mainFont, -150, -66, 5, false, 
                      translate( "city" ),
                      "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                      "abcdefghijklmnopqrstuvwxyz"
                      ".'-, " ),
          mProvinceField( mainFont, 224, -66, 5, false, 
                          translate( "province" ),
                          "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                          "abcdefghijklmnopqrstuvwxyz"
                          ".'-, " ),
          mPostalCodeField( mainFont, -150, -130, 5, true,
                            translate( "postalCode" ),
                            "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                            "- 0123456789" ),
          mCountryField( mainFont, 224, -130, 5, false, 
                         translate( "country" ),
                         "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                         "abcdefghijklmnopqrstuvwxyz"
                         ".'-, " ),
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
    mFields[4] = &mProvinceField;
    mFields[5] = &mPostalCodeField;
    mFields[6] = &mCountryField;

    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        addComponent( mFields[i] );
        mFields[i]->addActionListener( this );
        }
    
    
    
    // for testing
    /*
    mNameField.setText( "Jason Rohrer" );
    mAddress1Field.setText( "1208 L St." );
    mAddress2Field.setText( "" );
    mCityField.setText( "Davis" );
    mStateField.setText( "CA" );
    mZipField.setText( "95616" );
    */

    addServerErrorString( "CHECK_FAILED", "checkFailed" );
    addServerErrorStringSignal( "MORE_INFO_NEEDED", "moreInfoNeeded" );
    }


        
SendCheckGlobalPage::~SendCheckGlobalPage() {
    }






void SendCheckGlobalPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mSendCheckButton ) {
        setStatus( NULL, false );
        
        setupRequestParameterSecurity();
                
        
        setParametersFromField( "name", &mNameField );
        setParametersFromField( "address1", &mAddress1Field );
        setParametersFromField( "address2", &mAddress2Field );
        setParametersFromField( "city", &mCityField );
        setParametersFromField( "province", &mProvinceField );
        setParametersFromField( "postal_code", &mPostalCodeField );
        setParametersFromField( "country", &mCountryField );
        
        setParametersFromString( "us_state", "" );
        
        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        setParametersFromString( "dollar_amount", 
                                 dollarAmountString );
        delete [] dollarAmountString;

        
        mSendCheckButton.setVisible( false );
        mCancelButton.setVisible( false );
        
        for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
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



void SendCheckGlobalPage::makeActive( char inFresh ) {
    
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
    mAmountPicker.setMin( checkCostGlobal + 0.01 );
    mAmountPicker.setMax( userBalance );
    }



void SendCheckGlobalPage::makeNotActive() {
    // paused? clear delete-held status
    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        mFields[i]->unfocus();
        }
    }




void SendCheckGlobalPage::makeFieldsActive() {
    mNameField.focus();
        
    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        mFields[i]->setActive( true );
        }
    
    mAmountPicker.setAdjustable( true );
    }



void SendCheckGlobalPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    
    if( checkCostGlobal > 0 ) {
        doublePair labelPos = { 0, 264 };

        char *message = autoSprintf( translate( "feeSubtracted" ),
                                     checkCostGlobal );
        
        drawMessage( message, labelPos, false );    
        delete [] message;
        }
    }



void SendCheckGlobalPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        checkIfSendCheckButtonVisible();

        if( ! mFields[0]->isActive() ) {
            makeFieldsActive();
            }
        mCancelButton.setVisible( true );
        }
    }



double SendCheckGlobalPage::getWithdrawalAmount() {
    return mAmountPicker.getValue();
    }



void SendCheckGlobalPage::checkIfSendCheckButtonVisible() {
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


    char *city = mCityField.getText();
    
    if( strlen( city ) < 1 ) {
        visible = false;
        }

    delete [] city;


    char *country = mCountryField.getText();
    
    if( strlen( country ) < 1 ) {
        visible = false;
        }

    delete [] country;


    mSendCheckButton.setVisible( visible );
    }




void SendCheckGlobalPage::switchFields( int inDir ) {
    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        
        if( mFields[i]->isFocused() ) {

            int next = i + inDir;
            
            if( next >= NUM_SEND_CHECK_GLOBAL_FIELDS ) {
                next = 0;
                }
            else if( next < 0 ) {
                next = NUM_SEND_CHECK_GLOBAL_FIELDS - 1;
                }
            mFields[next]->focus();
            return;
            }
        }
    }

    

void SendCheckGlobalPage::keyDown( unsigned char inASCII ) {
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
        
        if( mCountryField.isFocused() && mSendCheckButton.isVisible() ) {
            // process enter on last field
            actionPerformed( &mSendCheckButton );
            
            return;
            }
        else {
            switchFields();
            }
        }
    }



void SendCheckGlobalPage::specialKeyDown( int inKeyCode ) {
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
