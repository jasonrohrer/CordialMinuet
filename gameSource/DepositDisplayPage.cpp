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
          mLeftGame( false ),
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


void DepositDisplayPage::setLeftGame( char inLeftGame ) {
    mLeftGame = inLeftGame;
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
    
    pos.x = 0;
    pos.y += 192;
    
    mainFont->drawString( translate( "oldBalance" ), pos, alignRight );

    char fullPrecision = false;
    
    char *oldBalanceString = 
        formatBalance( mOldBalance, false, &fullPrecision );

    char *deltaString = formatBalance( mDeltaAmount, fullPrecision );


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
    
    double otherWidth = numbersFontFixed->measureString( deltaString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }
    
    otherWidth = numbersFontFixed->measureString( newBalanceEstimateString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }
    
    delete [] newBalanceEstimateString;


    double xOffset = maxWidth / 2;
        


    pos.x = xOffset;
    pos.y -= 64;
    numbersFontFixed->drawString( oldBalanceString, pos, alignRight );
    
    delete [] oldBalanceString;

    pos.x = 0;
    pos.y -= 64;

    const char *amountKey = "addedAmount";
    
    if( mWithdraw ) {
        amountKey = "withdrawAmount";
        }
    else if( mLeftGame ) {
        amountKey = "leftWithAmount";
        }

    numbersFontFixed->drawString( translate( amountKey ), pos, alignRight );
    
    pos.x = xOffset;
    pos.y -= 64;
    numbersFontFixed->drawString( deltaString, pos, alignRight );
    
    delete [] deltaString;
    
    pos.x = 0;
    pos.y -= 64;
 
    mainFont->drawString( translate( "newBalance" ), pos, alignRight );
    

    if( isResponseReady() ) {
        
        char *valueString = 
            formatBalance( getResponseDouble( "dollarBalance" ),
                           fullPrecision );
    
        pos.x = xOffset;
        pos.y -= 64;
        numbersFontFixed->drawString( valueString, pos, alignRight );
    
        delete [] valueString;
        }
    }
