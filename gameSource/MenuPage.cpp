#include "MenuPage.h"

#include "buttonStyle.h"
#include "message.h"
#include "balanceFormat.h"

#include "minorGems/game/game.h"
#include "minorGems/game/Font.h"

#include "minorGems/util/stringUtils.h"


extern double userBalance;

extern Font *mainFont;



void MenuPage::clearListedGames() {
    for( int i=0; i<mListedGames.size(); i++ ) {
        GameRecord *r = mListedGames.getElement( i );
        delete [] r->gameID;
        }
    mListedGames.deleteAll();

    for( int i=0; i<mGameButtons.size(); i++ ) {
        TextButton *button = *( mGameButtons.getElement( i ) );
        button->setVisible( false );
        }
    }



MenuPage::MenuPage()
        : ServerActionPage( "list_games", true ),
          mDepositButton( mainFont, -150, 250, 
                          translate( "deposit" ) ),
          mWithdrawButton( mainFont, 150, 250, 
                         translate( "withdraw" ) ),
          mNewGameButton( mainFont, 0, -128, 
                          translate( "newGame" ) ),
          mPrevButton( mainFont, -150, -128, 
                       translate( "prevPage" ) ),
          mNextButton( mainFont, 150, -128, 
                       translate( "nextPage" ) ),
          mLimit( 10 ),
          mSkip( 0 ),
          mResponseProcessed( false ) {

    addComponent( &mDepositButton );
    addComponent( &mWithdrawButton );
    addComponent( &mNewGameButton );
    
    setButtonStyle( &mDepositButton );
    setButtonStyle( &mWithdrawButton );
    setButtonStyle( &mNewGameButton );
    

    mDepositButton.addActionListener( this );
    mWithdrawButton.addActionListener( this );
    mNewGameButton.addActionListener( this );

    setActionParameter( "limit", mLimit );
    setActionParameter( "skip", mSkip );
    mPrevButton.setVisible( false );
    mNextButton.setVisible( false );


    double y = 128;
    double x = -200;
    
    for( int i=0; i<mLimit; i++ ) {
        
        if( i % 3 == 0 ) {
            y -= 128;
            x = -200;
            }
        
        TextButton *button = new TextButton( mainFont, 
                                             x, y, "$0" );
        addComponent( button );
        button->addActionListener( this );
        
        setButtonStyle( button );

        button->setVisible( false );

        mGameButtons.push_back( button );

        x += 128;
        }
    }



MenuPage::~MenuPage() {
    
    for( int i=0; i<mGameButtons.size(); i++ ) {
        delete *( mGameButtons.getElement( i ) );
        }
    }




void MenuPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mDepositButton ) {
        setSignal( "deposit" );
        }
    if( inTarget == &mWithdrawButton ) {
        setSignal( "withdraw" );
        }
    if( inTarget == &mNewGameButton ) {
        setSignal( "newGame" );
        }
    if( inTarget == &mNextButton ) {
        mSkip += mLimit;
        setActionParameter( "skip", mSkip );
        startRequest();
        }
    if( inTarget == &mNextButton ) {
        mSkip += mLimit;
        setActionParameter( "skip", mSkip );
        
        mPrevButton.setVisible( false );
        mNextButton.setVisible( false );

        clearListedGames();

        mResponseProcessed = false;
        startRequest();
        }
    if( inTarget == &mPrevButton ) {
        mSkip -= mLimit;
        if( mSkip < 0 ) {
            mSkip = 0;
            }
        setActionParameter( "skip", mSkip );
        
        mPrevButton.setVisible( false );
        mNextButton.setVisible( false );
        
        clearListedGames();
        
        mResponseProcessed = false;
        startRequest();
        }
    }

    
        
void MenuPage::draw( doublePair inViewCenter, 
                     double inViewSize ) {
    
    char *balanceString = formatBalance( userBalance );
    
    doublePair pos = { 0, 250 };
    
    drawMessage( balanceString, pos );

    delete [] balanceString;

    char *gameCountString = autoSprintf( "%d listed games",
                                         mListedGames.size() );
    pos.y = 100;
    
    drawMessage( gameCountString, pos );
    delete [] gameCountString;
    }



void MenuPage::step() {
    ServerActionPage::step();

    
    if( isResponseReady() && ! mResponseProcessed ) {

        // parse response

        

        int numLines = getNumResponseParts();
        
        for( int i = 0; i<numLines; i++ ) {
            
            char *line = getResponse( i );

            int numParts;
            
            char **parts = split( line, "#", &numParts );

            delete [] line;

            if( numParts == 2 ) {
                
                if( i != numLines - 1 ) {
                    
                    GameRecord r;
                    r.gameID = stringDuplicate( parts[0] );
                
                    
                    sscanf( parts[1], "%lf", &( r.dollarAmount ) );
                    
                    mListedGames.push_back( r );

                    TextButton *button = *( mGameButtons.getElement( i ) );
                    
                    button->setVisible( true );
                    
                    char *dollarString = autoSprintf( "$%.2f", 
                                                      r.dollarAmount );
                    
                    button->setLabelText( dollarString );

                    delete [] dollarString;
                    }
                else {
                    // last line is not a game
                    
                    int morePages;
                    sscanf( parts[0], "%d", &morePages );
                    
                    if( morePages ) {
                        mNextButton.setVisible( true );
                        }
                    
                    sscanf( parts[1], "%d", &mSkip );
                    
                    if( mSkip > 0 ) {
                        mPrevButton.setVisible( true );
                        }
                    }
                }
            
            for( int p=0; p<numParts; p++ ) {
                delete [] parts[p];
                }
            delete [] parts;
            }
        
        mResponseProcessed = true;
        }
    }




void MenuPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }

    
    mPrevButton.setVisible( false );
    mNextButton.setVisible( false );
    
    clearListedGames();
    
    mResponseProcessed = false;
    startRequest();
    }
