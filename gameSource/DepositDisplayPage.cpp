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



void DepositDisplayPage::setBuyIn( double inBuyIn ) {
    mBuyIn = inBuyIn;
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
    

    double spacing = 64;
    
    if( mLeftGame ) {
        spacing = 54;
        }

    pos.x = 0;
    pos.y += 192;

    if( mLeftGame ) {
        pos.y += 2 * spacing;
        }
    
    mainFont->drawString( translate( "oldBalance" ), pos, alignRight );

    char fullPrecision = false;
    
    char *oldBalanceString = 
        formatBalance( mOldBalance, false, &fullPrecision );

    char *deltaString = formatBalance( mDeltaAmount, fullPrecision );

    char *buyInString = formatBalance( mBuyIn, fullPrecision );

    // estimate new balance for width measurements
    double newBalanceEstimate = mOldBalance;
    
    if( mWithdraw ) {
        newBalanceEstimate -= mDeltaAmount;
        }
    else {
        newBalanceEstimate += mDeltaAmount;
        }

    if( mLeftGame ) {
        newBalanceEstimate -= mBuyIn;
        }
    
    char *newBalanceEstimateString = 
        formatBalance( newBalanceEstimate, fullPrecision );


    double maxWidth = numbersFontFixed->measureString( oldBalanceString );
    
    double otherWidth = numbersFontFixed->measureString( deltaString );
    
    if( otherWidth > maxWidth ) {
        maxWidth = otherWidth;
        }

    otherWidth = numbersFontFixed->measureString( buyInString );
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
    pos.y -= spacing;
    numbersFontFixed->drawString( oldBalanceString, pos, alignRight );
    
    delete [] oldBalanceString;

    
    if( mLeftGame ) {
        pos.x = 0;
        pos.y -= spacing;
        
        mainFont->drawString( translate( "joinedWithAmount" ), 
                                      pos, alignRight );
    
        pos.x = xOffset;
        pos.y -= spacing;
        numbersFontFixed->drawString( buyInString, pos, alignRight );
        }
    delete [] buyInString;


    pos.x = 0;
    pos.y -= spacing;

    const char *amountKey = "addedAmount";
    
    if( mWithdraw ) {
        amountKey = "withdrawAmount";
        }
    else if( mLeftGame ) {
        amountKey = "leftWithAmount";
        }

    mainFont->drawString( translate( amountKey ), pos, alignRight );
    
    pos.x = xOffset;
    pos.y -= spacing;
    numbersFontFixed->drawString( deltaString, pos, alignRight );
    
    delete [] deltaString;
    
    pos.x = 0;
    pos.y -= spacing;
 
    mainFont->drawString( translate( "newBalance" ), pos, alignRight );
    

    if( isResponseReady() ) {
        
        char *valueString = 
            formatBalance( getResponseDouble( "dollarBalance" ),
                           fullPrecision );
    
        pos.x = xOffset;
        pos.y -= spacing;
        numbersFontFixed->drawString( valueString, pos, alignRight );
    
        delete [] valueString;
        }
    }
