#include "MenuPage.h"

#include "buttonStyle.h"
#include "message.h"
#include "balanceFormat.h"

#include "amuletCache.h"

#include "minorGems/game/game.h"
#include "minorGems/game/Font.h"

#include "minorGems/util/stringUtils.h"


extern double userBalance;

extern Font *mainFont;

extern char inPersonMode;

extern double minGameStakes;
extern double maxGameStakes;


extern int amuletID;
extern int amuletPointCount;


void MenuPage::clearListedGames() {
    mListedGames.deleteAll();

    for( int i=0; i<mGameButtons.size(); i++ ) {
        TextButton *button = *( mGameButtons.getElement( i ) );
        button->setVisible( false );
        }
    }



MenuPage::MenuPage()
        : ServerActionPage( "list_games", true ),
          mDepositButton( mainFont, -210, 260, 
                          translate( "deposit" ) ),
          mWithdrawButton( mainFont, 210, 260, 
                         translate( "withdraw" ) ),
          mNewGameButton( mainFont, 0, -220, 
                          translate( "newGame" ) ),
          mPrevButton( mainFont, -200, -188, 
                       translate( "prevPage" ) ),
          mNextButton( mainFont, 200, -188, 
                       translate( "nextPage" ) ),
          mRefreshButton( mainFont, -200, -188, 
                          translate( "refresh" ) ),
          mAreGamesAllowed( 2 ),
          mLimit( 9 ),
          mSkip( 0 ),
          mResponseProcessed( false ) {

    addComponent( &mDepositButton );
    addComponent( &mWithdrawButton );
    addComponent( &mNewGameButton );
    addComponent( &mPrevButton );
    addComponent( &mNextButton );
    addComponent( &mRefreshButton );
    
    setButtonStyle( &mDepositButton );
    setButtonStyle( &mWithdrawButton );
    setButtonStyle( &mNewGameButton );
    setButtonStyle( &mPrevButton );
    setButtonStyle( &mNextButton );
    setButtonStyle( &mRefreshButton );


    mDepositButton.addActionListener( this );
    mWithdrawButton.addActionListener( this );
    mNewGameButton.addActionListener( this );
    mPrevButton.addActionListener( this );
    mNextButton.addActionListener( this );
    mRefreshButton.addActionListener( this );

    setActionParameter( "limit", mLimit );
    setActionParameter( "skip", mSkip );
    mPrevButton.setVisible( false );
    mNextButton.setVisible( false );
    mRefreshButton.setVisible( false );


    double y = 100;
    double x = -200;
    
    for( int i=0; i<mLimit; i++ ) {
        
        if( i != 0 && i % 3 == 0 ) {
            y -= 96;
            x = -200;
            }
        
        TextButton *button = new TextButton( mainFont, 
                                             x, y, "$0" );
        addComponent( button );
        button->addActionListener( this );
        
        setButtonStyle( button );

        button->setVisible( false );

        mGameButtons.push_back( button );

        x += 200;
        }
    
    
    /*
    double topGap = 333 - ( mWithdrawButton.getPosition().y + 
                            0.5 * mWithdrawButton.getHeight() );
    */

    double sideGap = 21;

    mWithdrawButton.setPosition( 333 - sideGap 
                                 - mWithdrawButton.getWidth() / 2, 
                                 mWithdrawButton.getPosition().y );

    
    // tweak dep/withdraw button position
    
    double depositWidth = mDepositButton.getWidth();
    double withdrawWidth = mWithdrawButton.getWidth();
    
    double extra = ( withdrawWidth - depositWidth ) / 2;
    
    doublePair depositPos = mDepositButton.getPosition();
    
    mDepositButton.setPosition( depositPos.x + extra, depositPos.y );
    

    

    
    double gap = 333 + mDepositButton.getPosition().x 
        - 0.5 * mDepositButton.getWidth();
    
    
    if( gap < sideGap ) {
        
        double diff = sideGap - gap;
        

    
        doublePair depositPos = mDepositButton.getPosition();
        
        mDepositButton.setPosition( depositPos.x + diff, 
                                    depositPos.y );
        }
        
    }



MenuPage::~MenuPage() {
    
    for( int i=0; i<mGameButtons.size(); i++ ) {
        delete mGameButtons.getElementDirect( i );
        }
    }




void MenuPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mDepositButton ) {
        setSignal( "deposit" );
        }
    else if( inTarget == &mWithdrawButton ) {
        setSignal( "withdraw" );
        }
    else if( inTarget == &mNewGameButton ) {
        setSignal( "newGame" );
        }
    else if( inTarget == &mNextButton ) {
        mSkip += mLimit;
        setActionParameter( "skip", mSkip );
        
        mPrevButton.setVisible( false );
        mNextButton.setVisible( false );

        clearListedGames();

        mResponseProcessed = false;
        startRequest();
        }
    else if( inTarget == &mPrevButton ) {
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
    else if( inTarget == &mRefreshButton ) {
        setActionParameter( "skip", mSkip );
        
        mRefreshButton.setVisible( false );

        clearListedGames();
        
        mResponseProcessed = false;
        startRequest();
        }
    else {
        for( int i=0; i<mListedGames.size(); i++ ) {
            GameRecord *r = mListedGames.getElement( i );
            
            if( r->button == inTarget ) {
                
                if( r->dollarAmount <= userBalance ) {
                    
                    mJoinedGame = *r;
                    
                    setSignal( "join" );
                    }
                
                break;
                }
            }
        }
    
    }

    
        
void MenuPage::draw( doublePair inViewCenter, 
                     double inViewSize ) {


    doublePair pos = mDepositButton.getPosition();
    
    
    char *balanceString = formatBalance( userBalance );
    
    double balanceWidth = mainFont->measureString( balanceString );
    
    double buttonGap = 
        mWithdrawButton.getPosition().x - 
        mDepositButton.getPosition().x;
    
    buttonGap -= mWithdrawButton.getWidth() / 2;
    buttonGap -= mDepositButton.getWidth() / 2;
    
    if( buttonGap < balanceWidth + 2 * mainFont->getCharSpacing() ) {
        // not enough room, move down below buttons
        pos.y -= 64;
        }
        
    pos.x = 0;
    
    drawMessage( balanceString, pos );

    delete [] balanceString;


    if( amuletID != 0 ) {
        pos = mDepositButton.getPosition();
        
        pos.y -= 64;
        
        SpriteHandle amuletSprite = getAmuletSprite( amuletID );
        
        if( amuletSprite != NULL ) {
            setDrawColor( 1, 1, 1, 1 );
            drawSprite( amuletSprite, pos );
            }
        
        pos.x += 32;
        
        pos.y -= 3;
        char *scoreString = autoSprintf( "%d", amuletPointCount );
        
        mainFont->drawString( scoreString, pos, alignLeft );
        
        delete [] scoreString;
        }
    


    if( mListedGames.size() > 0 ) {    
        pos.x = 0;
        pos.y = mGameButtons.getElementDirect( 0 )->getPosition().y + 64;
        
        drawMessage( "gameList", pos );
        }
    else if( mAreGamesAllowed == 0 ) {
        pos.x = 0;
        pos.y = 0;
        drawMessage( "gamesNotAllowed", pos );
        }
    
    }


// if we have x items to show, we should use buttonsToUse[x] elements
// as the index order for our buttons
static int buttonsToUse[9][9] = 
{ 
    { 1, 0, 0, 0, 0, 0, 0, 0, 0 }, 
    { 1, 4, 0, 0, 0, 0, 0, 0, 0 }, 
    { 0, 1, 2, 0, 0, 0, 0, 0, 0 }, 
    { 0, 1, 2, 4, 0, 0, 0, 0, 0 }, 
    { 0, 1, 2, 4, 7, 0, 0, 0, 0 }, 
    { 0, 1, 2, 3, 4, 5, 0, 0, 0 }, 
    { 0, 1, 2, 3, 4, 5, 7, 0, 0 }, 
    { 0, 1, 2, 3, 4, 5, 6, 8, 0 }, 
    { 0, 1, 2, 3, 4, 5, 6, 7, 8 } 
    };



void MenuPage::step() {
    ServerActionPage::step();

    
    if( isResponseReady() && ! mResponseProcessed ) {

        // parse response

        

        int numLines = getNumResponseParts();
        
        int numGames = numLines - 4;

        for( int i = 0; i<numLines; i++ ) {
            
            char *line = getResponse( i );

            if( i == 0 ) {
                // first line is are_games_allowed flag
                sscanf( line, "%d", &mAreGamesAllowed );
                
                if( userBalance < 0.01 || mAreGamesAllowed != 1 ) {
                    mNewGameButton.setVisible( false );
                    }
                else {
                    mNewGameButton.setVisible( true );
                    }

                delete [] line;
                
                continue;
                }
            else if( i == 1 ) {
                // second line is min_allowed_stakes
                sscanf( line, "%lf", &minGameStakes );
                
                delete [] line;
                
                continue;
                }
            else if( i == 2 ) {
                // third line is max_allowed_stakes
                sscanf( line, "%lf", &maxGameStakes );
                
                delete [] line;
                
                continue;
                }
            

            int numParts;
            
            char **parts = split( line, "#", &numParts );

            delete [] line;

            if( numParts == 4 && i == 3 ) {
                // fourth line MAY be tournament info
                
                GameRecord r;
                    
                r.isTournament = true;


                sscanf( parts[1], "%lf", &( r.dollarAmount ) );
                sscanf( parts[2], "%lf", &( r.tournamentStakes ) );
                sscanf( parts[3], "%d", &( r.tournamentSecondsLeft ) );

                r.referenceSeconds = game_time( NULL );

                int b = buttonsToUse[numGames - 1][i - 3];


                TextButton *button = mGameButtons.getElementDirect( b );
                    
                button->setVisible( true );
                    
                char *dollarString = formatBalance( r.dollarAmount );
                
                const char *tipKey = "enterTournamentButtonTip";
                
                if( r.dollarAmount > userBalance ) {
                    tipKey = "cannotJoinButtonTip";
                    
                    button->setHoverColor( 1, 1, 1, 0.5 );
                    button->setNoHoverColor( 1, 1, 1, 0.5 );
                    button->setDragOverColor( 1, 1, 1, 0.5 );
                    }
                else {
                    button->setHoverColor( 1, 1, 0, 1 );
                    button->setNoHoverColor( 1, 1, 0, 1 );
                    button->setDragOverColor( 1, 1, 0, 1 );
                    }
                

                char *tip = autoSprintf( translate( tipKey ),
                                         dollarString );
                
                delete [] dollarString;

                button->setMouseOverTip( tip );

                delete [] tip;
                

                char *limitedDollarString = 
                    formatDollarStringLimited( r.dollarAmount );
                
                button->setLabelText( limitedDollarString );

                delete [] limitedDollarString;

                        

                r.button = button;
                    
                mListedGames.push_back( r );                
                }
            else if( numParts == 1 && i != numLines - 1 ) {
                // normal game buy-in

                GameRecord r;
                    
                r.isTournament = false;
                
                char isTournamentMatch = false;
                if( strlen( parts[0] ) > 1 &&
                    parts[0][0] =='T' ) {

                    isTournamentMatch = true;
                    sscanf( parts[0], "T%lf", &( r.dollarAmount ) );
                    }
                else {
                    sscanf( parts[0], "%lf", &( r.dollarAmount ) );
                    }
                
                    
                int b = buttonsToUse[numGames - 1][i - 3];
                    
                TextButton *button = mGameButtons.getElementDirect( b );
                    
                button->setVisible( true );
                    
                char *dollarString = formatBalance( r.dollarAmount );
                
                const char *tipKey = "joinButtonTip";

                if( r.dollarAmount > userBalance ) {
                    tipKey = "cannotJoinButtonTip";
                    
                    button->setHoverColor( 1, 1, 1, 0.5 );
                    button->setNoHoverColor( 1, 1, 1, 0.5 );
                    button->setDragOverColor( 1, 1, 1, 0.5 );
                    }
                else {
                    
                    if( isTournamentMatch ) {
                        tipKey = "tournamentMatchButtonTip";

                        button->setHoverColor( 1, 1, 0.5, 1 );
                        button->setNoHoverColor( 1, 1, 0.5, 1 );
                        button->setDragOverColor( 1, 1, 0.5, 1 );
                        }
                    else {                        
                        button->setHoverColor( 1, 1, 1, 1 );
                        button->setNoHoverColor( 1, 1, 1, 1 );
                        button->setDragOverColor( 1, 1, 1, 1 );
                        }
                    }
                

                char *tip = autoSprintf( translate( tipKey ),
                                         dollarString );
                
                delete [] dollarString;

                button->setMouseOverTip( tip );

                delete [] tip;
                

                char *limitedDollarString = 
                    formatDollarStringLimited( r.dollarAmount );
                
                button->setLabelText( limitedDollarString );

                delete [] limitedDollarString;

                        

                r.button = button;
                    
                mListedGames.push_back( r );
                }
            else if( numParts == 2 ) {
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

                if( ! mPrevButton.isVisible() && ! mNextButton.isVisible() &&
                    mListedGames.size() > 0 ) {
                    
                    // list auto-refreshes every 5 seconds if completely empty
                    // don't want user spamming reload button in this case

                    // if there ARE games listed, make them wait 5 seconds
                    // before the refresh button appears
                    mRefreshButton.setVisible( false );
                    }
                }
            
            for( int p=0; p<numParts; p++ ) {
                delete [] parts[p];
                }
            delete [] parts;
            }
        
        mResponseProcessed = true;
        mLastResponseTime = game_time( NULL );
        }
    else if( mResponseProcessed && mListedGames.size() == 0 &&
             game_time( NULL ) - mLastResponseTime > 5 ) {
        
        // auto-refresh empty list every 5 seconds

        setActionParameter( "skip", mSkip );
        
        mPrevButton.setVisible( false );
        mNextButton.setVisible( false );
        
        clearListedGames();
    
        mResponseProcessed = false;
        startRequest();
        }
    else if( mResponseProcessed && mListedGames.size() > 0 &&
             game_time( NULL ) - mLastResponseTime > 5 ) {
        
        // let them refresh manually now

        // prevent refresh spam
        mRefreshButton.setVisible( true );
        }
    
    }




void MenuPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }

    // returning to page, not sure again
    mAreGamesAllowed = 2;
    mNewGameButton.setVisible( false );
    
    mPrevButton.setVisible( false );
    mNextButton.setVisible( false );
    mRefreshButton.setVisible( false );
    
    clearListedGames();
    

    if( inPersonMode ) {
        mDepositButton.setVisible( false );
        }
        

    mResponseProcessed = false;
    startRequest();
    }



GameRecord MenuPage::getJoinedGame() {
    return mJoinedGame;
    }


