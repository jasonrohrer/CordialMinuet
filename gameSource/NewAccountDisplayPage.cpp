#include "NewAccountDisplayPage.h"

#include "buttonStyle.h"
#include "message.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;


extern char *userEmail;
extern char *accountKey;





NewAccountDisplayPage::NewAccountDisplayPage()
        : mCopyToClipboardButton( mainFont, 0, 0, 
                                  translate( "copyToClipboard" ) ),
          mDoneButton( mainFont, 150, -128, 
                       translate( "done" ) ) {

    addComponent( &mCopyToClipboardButton );
    addComponent( &mDoneButton );
    
    setButtonStyle( &mCopyToClipboardButton );
    setButtonStyle( &mDoneButton );
    

    mCopyToClipboardButton.addActionListener( this );
    mDoneButton.addActionListener( this );
    }

        
void NewAccountDisplayPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mCopyToClipboardButton ) {
        
        char *accountString = autoSprintf( "%s  %s", userEmail, accountKey );
        
        setClipboardText( accountString );
        
        delete [] accountString;
        }
    else if( inTarget == &mDoneButton ) {
        setSignal( "done" );
        }
    }



void NewAccountDisplayPage::draw( doublePair inViewCenter, 
                                  double inViewSize ) {
    
    doublePair pos = { 0, 300 };
    
    drawMessage( "emailReceipt", pos );
    pos.y -= 128;
    
    drawMessage( "newAccountDetails", pos );
    pos.y -= 64;
    
    drawMessage( userEmail, pos );
    pos.y -= 48;
    
    drawMessage( accountKey, pos );
    pos.y -= 64;

    pos.y = -300;
    drawMessage( "savedLocally", pos );
    }

