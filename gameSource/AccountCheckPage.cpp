#include "AccountCheckPage.h"

#include "buttonStyle.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"


extern Font *mainFont;

const char *checkUserPartNames[2] = { "userID", "sequenceNumber" };


AccountCheckPage::AccountCheckPage()
        : ServerActionPage( "checkUser",
                            2, checkUserPartNames, false ),
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
    }

        
AccountCheckPage::~AccountCheckPage() {
    }

        
void AccountCheckPage::actionPerformed( GUIComponent *inTarget ) {
    }


void AccountCheckPage::makeActive( char inFresh ) {

    }

