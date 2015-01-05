#include "EnterTournamentPage.h"

#include "buttonStyle.h"

#include "message.h"
#include "balanceFormat.h"
#include "accountHmac.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"



extern Font *mainFont;
extern Font *numbersFontFixed;



extern double userBalance;

extern double minGameStakes;
extern double maxGameStakes;



EnterTournamentPage::EnterTournamentPage()
        : ServerActionPage( "enter_tournament" ),
          mEnterButton( mainFont, 150, -200, 
                        translate( "enterTournament" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mEnterButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mEnterButton );
    setButtonStyle( &mCancelButton );
    

    mEnterButton.addActionListener( this );
    mCancelButton.addActionListener( this );
    }


        
EnterTournamentPage::~EnterTournamentPage() {
    }



void EnterTournamentPage::setTournamentInfo( GameRecord inRecord ) {
    mTournamentInfo = inRecord;
    }


    

void EnterTournamentPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mEnterButton ) {
        setStatus( NULL, false );
        

        setupRequestParameterSecurity();
        
        char *feeAmountString = 
            autoSprintf( "%.2f", mTournamentInfo.dollarAmount );
        
        setParametersFromString( "fee_dollar_amount", 
                                 feeAmountString );
        delete [] feeAmountString;
        
        mEnterButton.setVisible( false );
        mCancelButton.setVisible( false );
                
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void EnterTournamentPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    mEnterButton.setVisible( true );
    mCancelButton.setVisible( true );
    }



// returns center pos of next line
static doublePair drawTimeLine( int inNumUnits, 
                                const char *inUnitName,
                                // plural
                                const char *inUnitsName,
                                doublePair inCenterPos ) {

    char *numberString = autoSprintf( "%d", inNumUnits );
    
        
    const char *unitString = 
        ( inNumUnits != 1 ) ? inUnitsName : inUnitName;    

    numbersFontFixed->drawString( numberString, inCenterPos, alignRight );
    mainFont->drawString( unitString, inCenterPos, alignLeft );
    
    delete [] numberString;
    

    inCenterPos.y -= 32;
    
    return inCenterPos;
    }





void EnterTournamentPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {


    setDrawColor( 1, 1, 1, 1 );

    doublePair pos = { 0, 264 };
    
    char *feeString = formatBalance( mTournamentInfo.dollarAmount );
    char *stakesString = formatBalance( mTournamentInfo.tournamentStakes );
    
    double widthA = numbersFontFixed->measureString( feeString );
    double widthB = numbersFontFixed->measureString( stakesString );
    
    double width = widthA;
    if( widthB > width ) {
        width = widthB;
        }
    
    mainFont->drawString( translate( "entryFee" ), pos, alignRight );

    doublePair pos2 = pos;
    
    pos2.x += width + 16;
    
    numbersFontFixed->drawString( feeString, pos2, alignRight );

    delete [] feeString;
    
    
    pos.y -= 64;
    
    mainFont->drawString( translate( "tournamentStakes" ), pos, alignRight );

    pos2 = pos;
    
    pos2.x += width + 16;
    
    numbersFontFixed->drawString( stakesString, pos2, alignRight );
    
    
    pos.y -= 64;
    
    
    mainFont->drawString( translate( "tournamentTimeLeft" ), 
                          pos, alignRight );

    double timeNumbersWidth = numbersFontFixed->measureString( "00" );
    
    pos.x += timeNumbersWidth + 16;
    //pos.y -= 32;

    int secondsLeft = mTournamentInfo.tournamentSecondsLeft - 
        ( game_time( NULL ) - mTournamentInfo.referenceSeconds );
    
    int days = secondsLeft / 86400;
    secondsLeft -= days * 86400;

    int hours = secondsLeft / 3600;
    secondsLeft -= hours * 3600;
            
    int minutes = secondsLeft / 60;
    secondsLeft -= minutes * 60;

    
    if( days > 0 ) {
        pos = drawTimeLine( days, 
                            translate( "day" ), translate( "days" ),
                            pos );
        }
    if( hours > 0 || days > 0 ) {
        pos = drawTimeLine( hours, 
                            translate( "hour" ), translate( "hours" ),
                            pos );
        }
    if( minutes > 0 || hours > 0 || days > 0 ) {
        pos = drawTimeLine( minutes, 
                            translate( "minute" ), translate( "minutes" ),
                            pos );
        }

    pos = drawTimeLine( secondsLeft, 
                        translate( "second" ), translate( "seconds" ),
                        pos );
        
    pos.y -= 64;

    pos.x -= timeNumbersWidth + 16;

    
    char *explain =
        autoSprintf( translate( "tournamentExplain" ), stakesString );
    
    
    delete [] stakesString;

    

    drawMessage( explain, pos );
    
    delete [] explain;
    }



void EnterTournamentPage::step() {
    ServerActionPage::step();
    
    
    if( isResponseReady() ) {
        setSignal( "created" );
        }
    else if( ! isActionInProgress() ) {
        mEnterButton.setVisible( true );
        mCancelButton.setVisible( true );
        }

    }

    

void EnterTournamentPage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }


    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        actionPerformed( &mEnterButton );
            
        return;
        }
    }



