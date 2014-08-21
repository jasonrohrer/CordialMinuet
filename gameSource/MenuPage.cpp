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
          mLimit( 9 ),
          mSkip( 0 ),
          mResponseProcessed( false ),
          mJoinedGameDollarAmount( 0 ) {

    addComponent( &mDepositButton );
    addComponent( &mWithdrawButton );
    addComponent( &mNewGameButton );
    addComponent( &mPrevButton );
    addComponent( &mNextButton );
    
    
    setButtonStyle( &mDepositButton );
    setButtonStyle( &mWithdrawButton );
    setButtonStyle( &mNewGameButton );
    setButtonStyle( &mPrevButton );
    setButtonStyle( &mNextButton );
    


    mDepositButton.addActionListener( this );
    mWithdrawButton.addActionListener( this );
    mNewGameButton.addActionListener( this );
    mPrevButton.addActionListener( this );
    mNextButton.addActionListener( this );

    setActionParameter( "limit", mLimit );
    setActionParameter( "skip", mSkip );
    mPrevButton.setVisible( false );
    mNextButton.setVisible( false );


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
    else {
        for( int i=0; i<mListedGames.size(); i++ ) {
            GameRecord *r = mListedGames.getElement( i );
            
            if( r->button == inTarget ) {
                
                if( r->dollarAmount <= userBalance ) {
                    
                    mJoinedGameDollarAmount = r->dollarAmount;
                
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


    if( mListedGames.size() > 0 ) {    
        pos.x = 0;
        pos.y = mGameButtons.getElementDirect( 0 )->getPosition().y + 64;
        
        drawMessage( "gameList", pos );
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
        
        int numGames = numLines - 1;

        for( int i = 0; i<numLines; i++ ) {
            
            char *line = getResponse( i );

            int numParts;
            
            char **parts = split( line, "#", &numParts );

            delete [] line;

            if( numParts == 1 && i != numLines - 1 ) {
                    
                GameRecord r;
                    
                sscanf( parts[0], "%lf", &( r.dollarAmount ) );
                    
                    
                int b = buttonsToUse[numGames - 1][i];
                    
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
                    button->setHoverColor( 1, 1, 1, 1 );
                    button->setNoHoverColor( 1, 1, 1, 1 );
                    button->setDragOverColor( 1, 1, 1, 1 );
                    }
                

                char *tip = autoSprintf( translate( tipKey ),
                                         dollarString );
                    
                button->setMouseOverTip( tip );

                delete [] tip;

                if( r.dollarAmount < 1000 ) {
                        
                    button->setLabelText( dollarString );
                    }
                else {
                    // too big to display on button
                    char sizeChar = 'K';
                        
                    double trimmedAmount = r.dollarAmount / 1000;
                        
                    if( trimmedAmount >= 1000 ) {
                        sizeChar = 'M';
                        trimmedAmount = trimmedAmount / 1000;
                        }

                    if( trimmedAmount >= 1000 ) {
                        sizeChar = 'B';
                        trimmedAmount = trimmedAmount / 1000;
                        }
                        
                    if( trimmedAmount >= 1000 ) {
                        sizeChar = 'T';
                        trimmedAmount = trimmedAmount / 1000;
                        }
                        
                        
                    const char *formatString = "$%.0f %c";
                        
                    if( trimmedAmount < 10 ) {
                        formatString = "$%.1f %c";
                        trimmedAmount = floor( 10 * trimmedAmount ) / 10;
                        }
                    else {
                        trimmedAmount = floor( trimmedAmount );
                        }

                    char *trimmedString = autoSprintf( formatString,
                                                       trimmedAmount,
                                                       sizeChar );

                    button->setLabelText( trimmedString );

                    delete [] trimmedString;
                    }
                    

                        
                delete [] dollarString;

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



double MenuPage::getJoinedGameDollarAmount() {
    return mJoinedGameDollarAmount;
    }


