#include "DepositPage.h"

#include "buttonStyle.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;
extern Font *mainFontFixed;


extern int userID;
extern char *userEmail;
extern char *accountKey;

extern char gamePlayingBack;






const char *makeDepositPartNames[2] = { "newAccount", "encryptedAccountKey" };


DepositPage::DepositPage()
        : ServerActionPage( "make_deposit",
                            2, makeDepositPartNames ),
          mEmailField( mainFontFixed, mainFont, 0, 192, 10, false, 
                       translate( "email" ),
                       NULL,
                       // forbid only spaces
                       " "),
          mCardNumberField( mainFontFixed, mainFont, 0, 128, 10, true,
                            translate( "card" ),
                        // allow only numbers
                        "0123456789" ),
          mExpireMonthField( mainFontFixed, mainFont, -100, 64, 2, true,
                             translate( "expMonth" ),
                             // allow only numbers
                             "0123456789" ),
          mExpireYearField( mainFontFixed, mainFont, 150, 64, 4, true,
                            translate( "expYear" ),
                             // allow only numbers
                             "0123456789" ),
          mCVCField( mainFontFixed, mainFont, 0, 0, 4, true,
                     translate( "CVC" ),
                     // allow only numbers
                     "0123456789" ),
          mDepositeButton( mainFont, 0, -64, 
                           translate( "deposit" ) ),
          mCancelButton( mainFont, 0, -128, 
                         translate( "cancel" ) ) {



    addComponent( &mDepositeButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mDepositeButton );
    setButtonStyle( &mCancelButton );
    

    mDepositeButton.addActionListener( this );
    mCancelButton.addActionListener( this );


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
        
        if( mCardNumberField.isFocused() ) {
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
