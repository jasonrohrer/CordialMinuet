#include "MenuPage.h"

#include "buttonStyle.h"
#include "message.h"

#include "minorGems/game/game.h"
#include "minorGems/game/Font.h"

#include "minorGems/util/stringUtils.h"


extern double userBalance;

extern Font *mainFont;






MenuPage::MenuPage()
        : mDepositButton( mainFont, 0, 64, 
                          translate( "deposit" ) ) {

    addComponent( &mDepositButton );
    setButtonStyle( &mDepositButton );
    

    mDepositButton.addActionListener( this );
    }



void MenuPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mDepositButton ) {
        setSignal( "deposit" );
        }
    }

    
        
void MenuPage::draw( doublePair inViewCenter, 
                     double inViewSize ) {
    
    char *balanceString = autoSprintf( "$%.2f", userBalance );
    
    doublePair pos = { 0, 250 };
    
    drawMessage( balanceString, pos );

    delete [] balanceString;
    }