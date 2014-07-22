#include "ExistingAccountPage.h"

#include "message.h"
#include "buttonStyle.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"


#include "minorGems/graphics/openGL/KeyboardHandlerGL.h"


extern Font *mainFont;


extern char gamePlayingBack;

extern char *userEmail;
extern char *accountKey;



ExistingAccountPage::ExistingAccountPage()
        : mEmailField( mainFont, 0, 128, 10, false, 
                       translate( "email" ),
                       NULL,
                       // forbid only spaces
                       " "),
          mKeyField( mainFont, 0, 0, 15, true,
                     translate( "accountKey" ),
                     // allow only ticket code characters
                     "23456789ABCDEFGHJKLMNPQRSTUVWXYZ-" ),
          mAtSignButton( mainFont, 252, 128, "@" ),
          mPasteButton( mainFont, 0, -80, translate( "paste" ), 'v', 'V' ),
          mLoginButton( mainFont, 150, -200, translate( "loginButton" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {
    
    
    // center this in free space
    /*
    mPasteButton.setPosition( ( 333 + mKeyField.getRightEdgeX() ) / 2,
                              -64 );
    */
    // align this one with the paste button
    mAtSignButton.setPosition( mEmailField.getRightEdgeX() + 48,
                               128 );
    
    
    if( userEmail != NULL && accountKey != NULL ) {
        mEmailField.setText( userEmail );
        mKeyField.setText( accountKey );
        }

    setButtonStyle( &mLoginButton );
    setButtonStyle( &mCancelButton );
    setButtonStyle( &mAtSignButton );
    setButtonStyle( &mPasteButton );

    mFields[0] = &mEmailField;
    mFields[1] = &mKeyField;

    
    addComponent( &mLoginButton );
    addComponent( &mCancelButton );
    addComponent( &mAtSignButton );
    addComponent( &mPasteButton );
    addComponent( &mEmailField );
    addComponent( &mKeyField );
    
    mLoginButton.addActionListener( this );
    mCancelButton.addActionListener( this );

    mAtSignButton.addActionListener( this );
    mPasteButton.addActionListener( this );
    
    mAtSignButton.setMouseOverTip( translate( "atSignTip" ) );
    
    // to dodge quit message
    setTipPosition( true );
    }

          
        
ExistingAccountPage::~ExistingAccountPage() {
    }



void ExistingAccountPage::makeActive( char inFresh ) {
    mEmailField.focus();
    mPasteButton.setVisible( false );
    mAtSignButton.setVisible( true );
    }



void ExistingAccountPage::makeNotActive() {
    for( int i=0; i<2; i++ ) {
        mFields[i]->unfocus();
        }
    }



void ExistingAccountPage::step() {
    mPasteButton.setVisible( isClipboardSupported() &&
                             mKeyField.isFocused() );
    mAtSignButton.setVisible( mEmailField.isFocused() );
    }



void ExistingAccountPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mLoginButton ) {
        processLogin();
        }
    if( inTarget == &mCancelButton ) {
        setSignal( "done" );
        }
    else if( inTarget == &mAtSignButton ) {
        mEmailField.insertCharacter( '@' );
        }
    else if( inTarget == &mPasteButton ) {
        char *clipboardText = getClipboardText();
        
        mKeyField.setText( clipboardText );
    
        delete [] clipboardText;
        }
    
    }



void ExistingAccountPage::switchFields() {
    if( mFields[0]->isFocused() ) {
        mFields[1]->focus();
        }
    else if( mFields[1]->isFocused() ) {
        mFields[0]->focus();
        }
    }

    

void ExistingAccountPage::keyDown( unsigned char inASCII ) {
    if( inASCII == 9 ) {
        // tab
        switchFields();
        return;
        }

    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        
        if( mKeyField.isFocused() ) {

            processLogin();
            
            return;
            }
        else if( mEmailField.isFocused() ) {
            switchFields();
            }
        }
    }



void ExistingAccountPage::specialKeyDown( int inKeyCode ) {
    if( inKeyCode == MG_KEY_DOWN ||
        inKeyCode == MG_KEY_UP ) {
        
        switchFields();
        return;
        }
    }



void ExistingAccountPage::processLogin() {
    if( userEmail != NULL ) {
        delete [] userEmail;
        }
    userEmail = mEmailField.getText();
        
    if( accountKey != NULL ) {
        delete [] accountKey;
        }
    accountKey = mKeyField.getText();

    if( !gamePlayingBack ) {
        
        SettingsManager::setSetting( "email", userEmail );
        SettingsManager::setSetting( "accountKey", accountKey );
        }
                
    setSignal( "done" );
    }
