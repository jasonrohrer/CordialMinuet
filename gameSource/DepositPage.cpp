#include "DepositPage.h"

#include "buttonStyle.h"

#include "message.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/crypto/cryptoRandom.h"
#include "minorGems/crypto/keyExchange/curve25519.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"



extern Font *mainFont;


extern int userID;
extern char *userEmail;
extern char *accountKey;

extern char gamePlayingBack;




// verifies a credit card number with the Luhn formula
static char checkLuhn( const char *inCC ) {
    int numDigits = strlen( inCC );
    
    int *digits = new int[numDigits];
    
    for( int i=0; i<numDigits; i++ ) {
        digits[i] = inCC[i] - '0';
        }

    // every second digit, starting at right
    for( int i=numDigits-2; i>=0; i -= 2 ) {
        // double it
        digits[i] *= 2;
        
        if( digits[i] > 9 ) {
            
            // add its digits together if 10 or greater
            // ( 11 -> 2,   18 -> 9, etc)
            digits[i] = 1 + ( digits[i] - 10 );
            }
        }
    
    int sum = 0;
    
    for( int i=0; i<numDigits; i++ ) {
        sum += digits[i];
        }
    
    delete [] digits;

    return ( sum % 10 == 0 );
    }





const char *makeDepositPartNames[2] = { "newAccount", "encryptedAccountKey" };


DepositPage::DepositPage()
        : ServerActionPage( "make_deposit",
                            2, makeDepositPartNames ),
          mAmountPicker( mainFont, 34, 201, 4, 2, translate( "addMoney" ) ),
          mEmailField( mainFont, 0, 126, 10, false, 
                       translate( "email" ),
                       NULL,
                       // forbid only spaces
                       " "),
          mCardNumberField( mainFont, 0, 62, 16, true,
                            translate( "card" ),
                        // allow only numbers
                        "0123456789" ),
          mExpireMonthField( mainFont, -100, -2, 2, true,
                             translate( "expMonth" ),
                             // allow only numbers
                             "0123456789" ),
          mExpireYearField( mainFont, 132, -2, 4, true,
                            translate( "expYear" ),
                             // allow only numbers
                             "0123456789" ),
          mCVCField( mainFont, 0, -66, 4, true,
                     translate( "cvc" ),
                     // allow only numbers
                     "0123456789" ),
          mEmailFieldCanFocus( true ),
          mDepositeButton( mainFont, 150, -200, 
                           translate( "deposit" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ),
          mResponseProcessed( false ) {



    addComponent( &mDepositeButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mDepositeButton );
    setButtonStyle( &mCancelButton );
    

    mDepositeButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    addComponent( &mAmountPicker );
    
    
    mAmountPicker.setMin( 2 );
    mAmountPicker.setValue( 5.00 );


    mFields[0] = &mEmailField;
    mFields[1] = &mCardNumberField;
    mFields[2] = &mExpireMonthField;
    mFields[3] = &mExpireYearField;
    mFields[4] = &mCVCField;

    for( int i=0; i<NUM_DEPOSIT_FIELDS; i++ ) {
        addComponent( mFields[i] );
        mFields[i]->addActionListener( this );
        }
    mExpireMonthField.setMaxLength( 2 );
    mExpireYearField.setMaxLength( 4 );
    mCVCField.setMaxLength( 6 );
    

    // for testing
    mCardNumberField.setText( "4242424242424242" );
    //mCardNumberField.setText( "4000000000000002" );
    mEmailField.setText( "jasonrohrer@fastmail.fm" );
    mExpireMonthField.setText( "11" );
    mExpireYearField.setText( "2015" );
    mCVCField.setText( "137" );


    addServerErrorString( "ACCOUNT_EXISTS", "accountExists" );
    addServerErrorString( "PAYMENT_FAILED", "paymentFailed" );
    }


        
DepositPage::~DepositPage() {
    }



void DepositPage::setEmailFieldCanFocus( char inCanFocus ) {
    mEmailFieldCanFocus = inCanFocus;
    }




void DepositPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mDepositeButton ) {
        setStatus( NULL, false );

        char existingUser = ( userID != -1 );

        setAttachAccountHmac( existingUser );

        
        
        
        char gotKey = getCryptoRandomBytes( mSecretKey, 32 );

        if( !gotKey ) {
            setStatus( "err_encryption", true );
            return;
            }

        curve25519_genPublicKey( mPublicKey, mSecretKey );
        
        char *client_public_key = hexEncode( mPublicKey, 32 );
        setActionParameter( "client_public_key", client_public_key );

        char *tagString = autoSprintf( "%d", time( NULL ) );
        char *deposit_tag = hmac_sha1( client_public_key, tagString );
        delete [] client_public_key;
        delete [] tagString;

        setActionParameter( "deposit_tag", deposit_tag );
        delete [] deposit_tag;
        
        

        char *serverKeyHex = 
            SettingsManager::getStringSetting( "serverPublicKey" );
        

        if( serverKeyHex == NULL ) {
            setStatus( "err_encryption", true );
            return;
            }
        
        unsigned char *serverKey = hexDecode( serverKeyHex );
        delete [] serverKeyHex;
        
        if( serverKey == NULL ) {
            setStatus( "err_encryption", true );
            return;
            }
        
        curve25519_genSharedSecretKey( mSharedSecretKey, mSecretKey,
                                       serverKey );
        
        delete [] serverKey;
        
        char *sharedSecretKeyHex = hexEncode( mSharedSecretKey, 32 );
        

        char *email = mEmailField.getText();
        char *email_hmac = hmac_sha1( sharedSecretKeyHex, email );
        
        setActionParameter( "email", email );
        delete [] email;
        
        setActionParameter( "email_hmac", email_hmac );
        delete [] email_hmac;
        
        char *cardNumber = mCardNumberField.getText();        
        char *mm = mExpireMonthField.getText();
        char *yyyy = mExpireYearField.getText();
        char *cvc = mCVCField.getText();
        
        char *cardData =
            autoSprintf( "%s#%s#%s#%s", cardNumber, mm, yyyy, cvc );

        delete [] cardNumber;
        delete [] mm;
        delete [] yyyy;
        delete [] cvc;

        int numCardDataBytes = strlen( cardData );
        
        if( numCardDataBytes > 40 ) {
            delete [] cardData;
            setStatus( "err_encryption", true );
            return;
            }
        
        char *secretHmac0 = hmac_sha1( sharedSecretKeyHex, "0" );
        char *secretHmac1 = hmac_sha1( sharedSecretKeyHex, "1" );
        
        char *keyStreamHex = concatonate( secretHmac0, secretHmac1 );
        
        delete [] secretHmac0;
        delete [] secretHmac1;
        
        // 40 bytes
        unsigned char *keyStream = hexDecode( keyStreamHex );
        delete [] keyStreamHex;
        
        
        unsigned char *encryptedCardDataBytes = 
            new unsigned char[ strlen( cardData ) ];
        
        for( int i=0; i<numCardDataBytes; i++ ) {
            encryptedCardDataBytes[i] = cardData[i] ^ keyStream[i];
            }
        
        delete [] cardData;
        delete [] keyStream;

        char *encryptedCardDataHex = hexEncode( encryptedCardDataBytes,
                                                numCardDataBytes );
        delete [] encryptedCardDataBytes;
        
        
        setActionParameter( "card_data_encrypted", encryptedCardDataHex );
        delete [] encryptedCardDataHex;

        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        char *dollarAmountHmac = hmac_sha1( sharedSecretKeyHex, 
                                            dollarAmountString );
        
        setActionParameter( "dollar_amount", dollarAmountString );
        delete [] dollarAmountString;

        setActionParameter( "dollar_amount_hmac", dollarAmountHmac );
        delete [] dollarAmountHmac;
        
        

        
        delete [] sharedSecretKeyHex;

        
        mDepositeButton.setVisible( false );
        mCancelButton.setVisible( false );
        
        for( int i=0; i<NUM_DEPOSIT_FIELDS; i++ ) {
            mFields[i]->setActive( false );
            mFields[i]->unfocus();
            }

        mAmountPicker.setAdjustable( false );
        
        mResponseProcessed = false;
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void DepositPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );

    makeFieldsActive();
    
    checkIfDepositButtonVisible();
    }



void DepositPage::makeFieldsActive() {
    if( mEmailFieldCanFocus ) {
        mEmailField.focus();
        }
    else {
        mEmailField.cursorReset();
        mCardNumberField.focus();
        }
    
    for( int i=0; i<NUM_DEPOSIT_FIELDS; i++ ) {
        if( !mEmailFieldCanFocus &&
            mFields[i] == &mEmailField ) {
            mFields[i]->setActive( false );
            }
        else {
            mFields[i]->setActive( true );
            }
        }
    
    mAmountPicker.setAdjustable( true );
    }



void DepositPage::draw( doublePair inViewCenter, 
                               double inViewSize ) {
        
    doublePair labelPos = { 0, 264 };
    drawMessage( "secure", labelPos, false );    
    
    doublePair labelPosB = { 0, -130 };
    drawMessage( "noStore", labelPosB, false );    


    if( ! mCardNumberField.isFocused() ) {
        
        char *cc = mCardNumberField.getText();
    
        int length = strlen( cc );
        
        if( length > 0 && 
            ( length < 12 ||
              ! checkLuhn( cc ) ) ) {
            
            // invalid CC
            doublePair labelPosCC = mCardNumberField.getPosition();
            labelPosCC.x = 258;
            drawMessage( "invalid", labelPosCC, false );  
            }

        delete [] cc;
        }
    


    /*
    doublePair labelPosC = { 0, 150 };
    char luhnCheck = checkLuhn( "4941598664272299" );
    
    char *message = autoSprintf( "Luhn test = %d\n", luhnCheck );
    drawMessage( message,  labelPosC );
    */
    }



void DepositPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        checkIfDepositButtonVisible();

        // last field (not email) should always be active
        if( ! mFields[ NUM_DEPOSIT_FIELDS - 1 ]->isActive() ) {
            makeFieldsActive();
            }
        mCancelButton.setVisible( true );
        }
    
    if( !mResponseProcessed && isResponseReady() ) {
        
        int newAccount = getResponseInt( "newAccount" );
        
        if( newAccount ) {
            
            char *encryptedAccountKeyHex =
                getResponse( "encryptedAccountKey" );
            

            char *sharedSecretKeyHex = hexEncode( mSharedSecretKey, 32 );

            char *secretHmac2 = hmac_sha1( sharedSecretKeyHex, "2" );
            char *secretHmac3 = hmac_sha1( sharedSecretKeyHex, "3" );
            
            delete [] sharedSecretKeyHex;
            
            char *keyStreamHex = concatonate( secretHmac2, secretHmac3 );
        
            delete [] secretHmac2;
            delete [] secretHmac3;
        
            // 40 bytes
            unsigned char *keyStream = hexDecode( keyStreamHex );
            delete [] keyStreamHex;
        

            int accountKeyLength = strlen( encryptedAccountKeyHex ) / 2;
            
            
            
            unsigned char *encryptedAccountKeyBin = 
                hexDecode( encryptedAccountKeyHex );
            
            delete [] encryptedAccountKeyHex;
            
            if( accountKey != NULL ) {
                delete [] accountKey;
                }
            
        
            accountKey = 
                new char[ accountKeyLength + 1 ];
        
            for( int i=0; i<accountKeyLength; i++ ) {
                accountKey[i] = encryptedAccountKeyBin[i] ^ keyStream[i];
                }
            accountKey[ accountKeyLength ] = '\0';
            
            delete [] encryptedAccountKeyBin;
            delete [] keyStream;


            
            if( userEmail != NULL ) {
                delete [] userEmail;
                }
            userEmail = mEmailField.getText();
            
            if( !gamePlayingBack ) {
                
                SettingsManager::setSetting( "email", userEmail );
                SettingsManager::setSetting( "accountKey", accountKey );
                }
            
            setSignal( "newAccount" );
            }
        else {
            setSignal( "existingAccount" );
            }
        
        mResponseProcessed = true;
        }
    
    }



double DepositPage::getDepositAmount() {
    return mAmountPicker.getValue();
    }





void DepositPage::checkIfDepositButtonVisible() {
    char visible = true;

    char *email = mEmailField.getText();
    
    if( strlen( email ) < 3 ||
        strstr( email, "@" ) == NULL ||
        strstr( email, "." ) == NULL ) {
        visible = false;
        }
    delete [] email;
    
    
    char *cc = mCardNumberField.getText();
    
    if( strlen( cc ) < 12 ||
        ! checkLuhn( cc ) ) {
        visible = false;
        }

    delete [] cc;


    char *mm = mExpireMonthField.getText();
    
    if( strlen( mm ) < 2 ) {
        visible = false;
        }

    delete [] mm;

    char *yyyy = mExpireYearField.getText();
    
    if( strlen( yyyy ) < 4 ) {
        visible = false;
        }

    delete [] yyyy;

    char *cvc = mCVCField.getText();
    
    if( strlen( cvc ) < 3 ) {
        visible = false;
        }

    delete [] cvc;


    mDepositeButton.setVisible( visible );
    }




void DepositPage::switchFields( int inDir ) {
    for( int i=0; i<NUM_DEPOSIT_FIELDS; i++ ) {
        
        if( mFields[i]->isFocused() ) {

            // auto-pad month an year field if needed
            if( mFields[i] == &mExpireMonthField ) {
                // leaving month field
                char *text = mExpireMonthField.getText();
                
                if( strlen( text ) == 1 ) {
                    char *newText = autoSprintf( "0%s\n", text );
                    mExpireMonthField.setText( newText );
                    delete [] newText;
                    }
                delete [] text;
                }
            else if( mFields[i] == &mExpireYearField ) {
                // leaving month field
                char *text = mExpireYearField.getText();
                
                if( strlen( text ) == 2 ) {
                    char *newText = autoSprintf( "20%s\n", text );
                    mExpireYearField.setText( newText );
                    delete [] newText;
                    }
                delete [] text;
                }

            int next = i + inDir;
            
            if( next >= NUM_DEPOSIT_FIELDS ) {
                next = 0;
                }
            else if( next < 0 ) {
                next = NUM_DEPOSIT_FIELDS - 1;
                }
            mFields[next]->focus();
            return;
            }
        }
    }

    

void DepositPage::keyDown( unsigned char inASCII ) {
    if( false /* FIXME:  some check for currently sending request */) {
        return;
        }

    if( inASCII == 9 ) {
        // tab
        switchFields();
        return;
        }

    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        
        if( mCVCField.isFocused() ) {
            // FIXME:  process enter on last field
            //startLogin();
            
            return;
            }
        else {
            switchFields();
            }
        }
    }



void DepositPage::specialKeyDown( int inKeyCode ) {
    if( false /* FIXME:  some check for currently sending request */ ) {
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
