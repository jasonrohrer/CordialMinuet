#include "MenuPage.h"

#include "buttonStyle.h"
#include "message.h"
#include "balanceFormat.h"

#include "minorGems/game/game.h"
#include "minorGems/game/Font.h"

#include "minorGems/util/stringUtils.h"


extern double userBalance;

extern Font *mainFont;






MenuPage::MenuPage()
        : mDepositButton( mainFont, 0, 64, 
                          translate( "deposit" ) ),
          mWithdrawButton( mainFont, 0, -64, 
                         translate( "withdraw" ) ) {

    addComponent( &mDepositButton );
    addComponent( &mWithdrawButton );
    
    setButtonStyle( &mDepositButton );
    setButtonStyle( &mWithdrawButton );
    

    mDepositButton.addActionListener( this );
    mWithdrawButton.addActionListener( this );
    }



void MenuPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mDepositButton ) {
        setSignal( "deposit" );
        }
    if( inTarget == &mWithdrawButton ) {
        setSignal( "withdraw" );
        }
    }

    
        
void MenuPage::draw( doublePair inViewCenter, 
                     double inViewSize ) {
    
    char *balanceString = formatBalance( userBalance );
    
    doublePair pos = { 0, 250 };
    
    drawMessage( balanceString, pos );

    delete [] balanceString;
    }
