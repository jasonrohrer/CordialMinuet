#include "DepositDisplayPage.h"

#include "buttonStyle.h"
#include "balanceFormat.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;
extern Font *numbersFontFixed;


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
    
    pos.x = -64;
    pos.y += 64;
    
    mainFont->drawString( translate( "oldBalance" ), pos, alignRight );

    char fullPrecision = false;
    
    char *oldBalanceString = 
        formatBalance( mOldBalance, false, &fullPrecision );

    char *deletaString = formatBalance( mDeltaAmount, fullPrecision );


    // estimate new balance for width measurements
    double newBalanceEstimate = mOldBalance;
    
    if( mWithdraw ) {
        newBalanceEstimate -= mDeltaAmount;
        }
    else {
        newBalanceEstimate += mDeltaAmount;
        }
    
    char *newBalanceEstimateString = 
        formatBalance( newBalanceEstimate, fullPrecision );


    double maxWidth = numbersFontFixed->measureString( oldBalanceString );
    
    double otherWidth = numbersFontFixed->measureString( deletaString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }
    
    otherWidth = numbersFontFixed->measureString( newBalanceEstimateString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }
    
    delete [] newBalanceEstimateString;


    double xOffset = 2 * mainFont->getFontHeight() + maxWidth - 64;
        


    pos.x = xOffset;
    numbersFontFixed->drawString( oldBalanceString, pos, alignRight );
    
    delete [] oldBalanceString;

    pos.x = -64;
    pos.y -= 64;

    const char *amountKey = "addedAmount";
    
    if( mWithdraw ) {
        amountKey = "withdrawAmount";
        }

    numbersFontFixed->drawString( translate( amountKey ), pos, alignRight );
    
    pos.x = xOffset;
    numbersFontFixed->drawString( deletaString, pos, alignRight );
    
    delete [] deletaString;
    
    pos.x = -64;
    pos.y -= 64;
 
    mainFont->drawString( translate( "newBalance" ), pos, alignRight );
    

    if( isResponseReady() ) {
        
        char *valueString = 
            formatBalance( getResponseDouble( "dollarBalance" ),
                           fullPrecision );
    
        pos.x = xOffset;
        numbersFontFixed->drawString( valueString, pos, alignRight );
    
        delete [] valueString;
        }
    }
