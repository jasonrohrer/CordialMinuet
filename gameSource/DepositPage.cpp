#include "DepositPage.h"

#include "buttonStyle.h"

#include "message.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


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
    
    return ( sum % 10 == 0 );
    }





const char *makeDepositPartNames[2] = { "newAccount", "encryptedAccountKey" };


DepositPage::DepositPage()
        : ServerActionPage( "make_deposit",
                            2, makeDepositPartNames ),
        mEmailField( mainFont, 0, 96, 10, false, 
                       translate( "email" ),
                       NULL,
                       // forbid only spaces
                       " "),
          mCardNumberField( mainFont, 0, 32, 16, true,
                            translate( "card" ),
                        // allow only numbers
                        "0123456789" ),
          mExpireMonthField( mainFont, -100, -32, 2, true,
                             translate( "expMonth" ),
                             // allow only numbers
                             "0123456789" ),
          mExpireYearField( mainFont, 132, -32, 4, true,
                            translate( "expYear" ),
                             // allow only numbers
                             "0123456789" ),
          mCVCField( mainFont, -150, -96, 4, true,
                     translate( "cvc" ),
                     // allow only numbers
                     "0123456789" ),
          mAmountPicker( mainFont, 0, -150, 4, 2 ),
          mDepositeButton( mainFont, 150, -200, 
                           translate( "deposit" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mDepositeButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mDepositeButton );
    setButtonStyle( &mCancelButton );
    

    mDepositeButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    addComponent( &mAmountPicker );


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

    }

        
DepositPage::~DepositPage() {
    }



void DepositPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mDepositeButton ) {

        char existingUser = ( userID != -1 );

        setAttachAccountHmac( existingUser );
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void DepositPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }
    
    mEmailField.focus();
        
    for( int i=0; i<NUM_DEPOSIT_FIELDS; i++ ) {
        mFields[i]->setActive( true );
        }

    checkIfDepositButtonVisible();
    }


void DepositPage::draw( doublePair inViewCenter, 
                               double inViewSize ) {
        
    doublePair labelPos = { 0, 264 };
    drawMessage( "secure", labelPos, false );    
    
    doublePair labelPosB = { 0, 200 };
    drawMessage( "noStore", labelPosB, false );    


    if( ! mCardNumberField.isFocused() ) {
        
        char *cc = mCardNumberField.getText();
    
        int length = strlen( cc );
        
        if( length > 0 && 
            ( length < 12 ||
              ! checkLuhn( cc ) ) ) {
            
            // invalid CC
            doublePair labelPosCC = { 256, 32 };
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
    checkIfDepositButtonVisible();
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
