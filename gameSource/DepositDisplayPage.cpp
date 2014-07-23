#include "DepositDisplayPage.h"

#include "buttonStyle.h"
#include "balanceFormat.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;


extern double userBalance;



const char *getBalancePartNames[1] = { "dollarBalance" };


DepositDisplayPage::DepositDisplayPage()
        : ServerActionPage( "get_balance",
                            1, getBalancePartNames, true ),
          mWithdraw( false ),
          mOkayButton( mainFont, 0, -200, 
                       translate( "OK" ) ),
          mOldBalance( 0 ),
          mDeltaAmount( 0 ) {

    addComponent( &mOkayButton );
    setButtonStyle( &mOkayButton );
    mOkayButton.addActionListener( this );
    }


        
DepositDisplayPage::~DepositDisplayPage() {
    }



void DepositDisplayPage::setWithdraw( char inWithdraw ) {
    mWithdraw = inWithdraw;
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



void DepositDisplayPage::setDeltaAmount( double inAmount ) {
    mDeltaAmount = inAmount;
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

    char fullPrecision = false;
    
    char *valueString = formatBalance( mOldBalance, false, &fullPrecision );

    double xOffset = 2 * mainFont->getFontHeight() + 
        mainFont->measureString( valueString );
    
    pos.x = xOffset;
    mainFont->drawString( valueString, pos, alignRight );
    
    delete [] valueString;

    pos.x = 0;
    pos.y -= 64;

    const char *amountKey = "addedAmount";
    
    if( mWithdraw ) {
        amountKey = "withdrawAmount";
        }

    mainFont->drawString( translate( amountKey ), pos, alignRight );
    
    valueString = formatBalance( mDeltaAmount, fullPrecision );
    
    pos.x = xOffset;
    mainFont->drawString( valueString, pos, alignRight );
    
    delete [] valueString;
    
    pos.x = 0;
    pos.y -= 64;
 
    mainFont->drawString( translate( "newBalance" ), pos, alignRight );
    

    if( isResponseReady() ) {
        
        valueString = formatBalance( getResponseDouble( "dollarBalance" ),
                                     fullPrecision );
    
        pos.x = xOffset;
        mainFont->drawString( valueString, pos, alignRight );
    
        delete [] valueString;
        }
    }
