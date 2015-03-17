#include "CreateGamePage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"

#include "amuletCache.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"



extern Font *mainFont;



extern double userBalance;

extern double minGameStakes;
extern double maxGameStakes;


extern int amuletID;

extern double amuletStake;


CreateGamePage::CreateGamePage()
        : ServerActionPage( "join_game" ),
          mAmountPicker( mainFont, 96, -75, 9, 2, 
                         translate( "$" ) ),
          mAmuletGameButton( mainFont, 0, 150, 
                             translate( "amuletGame" ) ),
          mCreateButton( mainFont, 150, -200, 
                         translate( "create" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {

    addServerErrorString( "AMULET_DROPPED", "amuletDropped" );

    addServerErrorStringSignal( "AMULET_DROPPED", "amuletDropped" );
    

    addComponent( &mAmuletGameButton );
    addComponent( &mCreateButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mAmuletGameButton );
    setButtonStyle( &mCreateButton );
    setButtonStyle( &mCancelButton );
    

    mAmuletGameButton.addActionListener( this );
    mCreateButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    mAmuletGameButton.setVisible( false );

    addComponent( &mAmountPicker );
    }


        
CreateGamePage::~CreateGamePage() {
    }



void CreateGamePage::clearAmountPicker() {
    mAmountPicker.setValue( 0.01 );
    }

    

void CreateGamePage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mCreateButton ) {
        setStatus( NULL, false );
        

        setupRequestParameterSecurity();
        
        
        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        setParametersFromString( "dollar_amount", 
                                 dollarAmountString );
        delete [] dollarAmountString;
        
        setActionParameter( "amulet_game", 0 );

        mAmuletGameButton.setVisible( false );
        mCreateButton.setVisible( false );
        mCancelButton.setVisible( false );
                
        mAmountPicker.setAdjustable( false );
        
        startRequest();
        }
    else if( inTarget == &mAmuletGameButton ) {
        setStatus( NULL, false );
        

        setupRequestParameterSecurity();
        
        
        double dollarAmount = amuletStake;
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        setParametersFromString( "dollar_amount", 
                                 dollarAmountString );
        delete [] dollarAmountString;
        
        setActionParameter( "amulet_game", 1 );

        mAmuletGameButton.setVisible( false );
        mCreateButton.setVisible( false );
        mCancelButton.setVisible( false );
                
        mAmountPicker.setAdjustable( false );
        
        mAmountPicker.setVisible( false );
        
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void CreateGamePage::makeActive( char inFresh ) {
    
    if( ! isActionInProgress() ) {
        makeFieldsActive();
        }    


    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfCreateButtonVisible();

    mAmountPicker.setMin( minGameStakes );

    double lowerLimit = userBalance;
    if( userBalance > maxGameStakes ) {
        lowerLimit = maxGameStakes;
        }

    mAmountPicker.setMax( lowerLimit );

    // make room in case of huge balance
    if( userBalance < 9999999 ) {
        mAmountPicker.setPosition( 34, mAmountPicker.getPosition().y );
        }
    else {
        mAmountPicker.setPosition( 96, mAmountPicker.getPosition().y );
        }
    
    mAmountPicker.setVisible( true );

    if( amuletID != 0 && 
        userBalance >= amuletStake ) {
        
        mAmuletGameButton.setVisible( true );


        char *amuletTip = autoSprintf( translate( "amuletTip" ),
                                       amuletStake );
        
        mAmuletGameButton.setMouseOverTip( amuletTip );
        delete [] amuletTip;

        mCreateButton.setMouseOverTip( translate( "nonAmuletTip" ) );

        //mAmountPicker.setPosition( 96, -75 );
        }
    else {
        mAmuletGameButton.setVisible( false );
        
        //mAmountPicker.setPosition( 96, 75 );

        mAmuletGameButton.setMouseOverTip( NULL );
        mAmuletGameButton.setMouseOverTip( NULL );
        }
    
    }



void CreateGamePage::makeFieldsActive() {
    mAmountPicker.setAdjustable( true );
    }



void CreateGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    
    doublePair pos = mAmountPicker.getPosition();

    if( mAmountPicker.isVisible() ) {

        pos.y += 96;
        
        pos.x += 3;
        
        setDrawColor( 1, 1, 1, 1 );
        mainFont->drawString( translate( "buyIn" ), pos, alignRight );
        }
    

    if( amuletID != 0 ) {
        pos = mAmuletGameButton.getPosition();
        
        pos.y += 64;
        
        SpriteHandle amuletSprite = getAmuletSprite( amuletID );
        
        if( amuletSprite != NULL ) {
            setDrawColor( 1, 1, 1, 1 );
            drawSprite( amuletSprite, pos );
            }
        
        if( userBalance < amuletStake ) {
            
            char *limitString = autoSprintf( translate( "amuletLimit" ),
                                             amuletStake );
            

            pos = mAmuletGameButton.getPosition();
            
            mainFont->drawString( limitString, pos, alignCenter );
            delete [] limitString;
            }

        }

    

    }



void CreateGamePage::step() {
    ServerActionPage::step();
    
    
    if( isResponseReady() ) {
        setSignal( "created" );
        }
    else if( ! isActionInProgress() ) {

        if( checkSignal( "amuletDropped" ) ) {
            amuletID = 0;
            }
            
        checkIfCreateButtonVisible();
        
        makeFieldsActive();
        
        mAmountPicker.setVisible( true );
        
        mCancelButton.setVisible( true );
        }
    
    }






void CreateGamePage::checkIfCreateButtonVisible() {
    char visible = true;


    mCreateButton.setVisible( visible );
    }

    

void CreateGamePage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }


    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        actionPerformed( &mCreateButton );
            
        return;
        }
    }



