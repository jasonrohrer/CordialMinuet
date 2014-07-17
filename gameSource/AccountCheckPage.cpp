#include "AccountCheckPage.h"

#include "buttonStyle.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;


extern int userID;
extern char *userEmail;
extern char *accountKey;
extern int serverSequenceNumber;

extern char gamePlayingBack;






const char *checkUserPartNames[2] = { "userID", "sequenceNumber" };


AccountCheckPage::AccountCheckPage()
        : ServerActionPage( "check_user",
                            2, checkUserPartNames, true ),
          mNewAccountButton( mainFont, 0, 64, 
                             translate( "newAccount" ) ),
          mExistingAccountButton( mainFont, 0, -64, 
                                  translate( "existingAccount" ) ) {

    addComponent( &mNewAccountButton );
    addComponent( &mExistingAccountButton );
    
    setButtonStyle( &mNewAccountButton );
    setButtonStyle( &mExistingAccountButton );
    

    mNewAccountButton.addActionListener( this );
    mExistingAccountButton.addActionListener( this );

    setMinimumResponseTime( 1 );
    }

        
AccountCheckPage::~AccountCheckPage() {
    }

        
void AccountCheckPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mNewAccountButton ) {
        setSignal( "newAccount" );
        }
    else if( inTarget == &mExistingAccountButton ) {
        setSignal( "existingAccount" );
        }
    }


void AccountCheckPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }

    if( userEmail != NULL && accountKey != NULL ) {
        mNewAccountButton.setVisible( false );
        mExistingAccountButton.setVisible( false );

        char *status = autoSprintf( translate( "loggingIn" ), userEmail );
        
        setStatusDirect( status, false );
        
        delete [] status;
        
        setActionParameter( "email", userEmail );
        
        startRequest();
        }
    else {
        mNewAccountButton.setVisible( true );
        mExistingAccountButton.setVisible( true );
        }
    
    
    }




void AccountCheckPage::step() {
    ServerActionPage::step();

    if( isError() && ! mNewAccountButton.isVisible() ) {
        mNewAccountButton.setVisible( true );
        mExistingAccountButton.setVisible( true );
        }
    }

    
