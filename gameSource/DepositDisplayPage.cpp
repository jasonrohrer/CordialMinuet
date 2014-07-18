#include "DepositDisplayPage.h"

#include "buttonStyle.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;


extern double userBalance;



const char *getBalancePartNames[1] = { "dollarBalance" };


DepositDisplayPage::DepositDisplayPage()
        : ServerActionPage( "get_balance",
                            1, getBalancePartNames, true ),
          mOkayButton( mainFont, 0, -200, 
                       translate( "OK" ) ),
          mOldBalance( 0 ),
          mDepositAmount( 0 ) {

    addComponent( &mOkayButton );
    setButtonStyle( &mOkayButton );
    mOkayButton.addActionListener( this );
    }


        
DepositDisplayPage::~DepositDisplayPage() {
    }


        
void DepositDisplayPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mOkayButton ) {
        setSignal( "done" );
        }
    }



void DepositDisplayPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }
    mOldBalance = userBalance;
    
    mOkayButton.setVisible( false );

    startRequest();
    }



void DepositDisplayPage::setDepositAmount( double inAmount ) {
    mDepositAmount = inAmount;
    }



void DepositDisplayPage::step() {
    if( isResponseReady() ) {
        mOkayButton.setVisible( true );
        }
    ServerActionPage::step();
    }



void DepositDisplayPage::draw( doublePair inViewCenter, 
                               double inViewSize ) {
    
    setDrawColor( 1, 1, 1, 1 );
    
    doublePair pos = { 0, 0 };
    
    pos.y += 64;
    
    mainFont->drawString( translate( "oldBalance" ), pos, alignRight );
    
    char *valueString = autoSprintf( "$%.2f", mOldBalance );

    double xOffset = 2 * mainFont->getFontHeight() + 
        mainFont->measureString( valueString );
    
    pos.x = xOffset;
    mainFont->drawString( valueString, pos, alignRight );
    
    delete [] valueString;

    pos.x = 0;
    pos.y -= 64;
 
    mainFont->drawString( translate( "addedAmount" ), pos, alignRight );
    
    valueString = autoSprintf( "$%.2f", mDepositAmount );
    
    pos.x = xOffset;
    mainFont->drawString( valueString, pos, alignRight );
    
    delete [] valueString;
    
    pos.x = 0;
    pos.y -= 64;
 
    mainFont->drawString( translate( "newBalance" ), pos, alignRight );
    

    if( isResponseReady() ) {
        
        valueString = autoSprintf( "$%.2f", 
                                   getResponseDouble( "dollarBalance" )  );
    
        pos.x = xOffset;
        mainFont->drawString( valueString, pos, alignRight );
    
        delete [] valueString;
        }
    }
