#include "PlayGamePage.h"

#include "buttonStyle.h"
#include "message.h"
#include "whiteSprites.h"

#include "serialWebRequests.h"



#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"
#include "minorGems/game/drawUtils.h"

#include "minorGems/util/stringUtils.h"



extern Font *mainFont;
extern Font *numbersFontFixed;
extern double frameRateFactor;



const char *gameStatePartNames[8] = { "running", "boardLayout", 
                                      "ourCoins", "theirCoins", 
                                      "ourPotCoins", "theirPotCoins",
                                      "ourMoves", "theirMoves" };

const char *waitMovePartNames[1] = { "status" };


static int cellSize = 70;
static int borderWidth = 2;
    
static double cellCenterStart = ( ( cellSize + borderWidth ) * 5 ) / 2;

static int cellXOffset = cellSize + borderWidth;


static void setUsColor() {
    setDrawColor( 0, 0.75, 0, 1 );
    }

static void setThemColor() {
    setDrawColor( 0.75, 0, 0, 1 );
    }


static doublePair lastMousePos = { 0, 0 };


PlayGamePage::PlayGamePage()
        : ServerActionPage( "get_game_state", 8, gameStatePartNames ),
          mGameBoard( NULL ),
          mCommitButton( mainFont, 0, -288, translate( "commit" ) ),
          mBetButton( mainFont, 0, -288, translate( "bet" ) ),
          mFoldButton( mainFont, 120, -288, translate( "fold" ) ),
          mLeaveButton( mainFont, -128, -288, translate( "leave" ) ),
          mBetPicker( mainFont, -64, -288, 3, 0, "" ),
          mColumnChoiceForUs( -1 ), mColumnChoiceForThem( -1 ),
          mScorePipSprite( loadWhiteSprite( "scorePip.tga" ) ),
          mScorePipExtraSprite( loadWhiteSprite( "scorePipExtra.tga" ) ),
          mScorePipEmptySprite( loadWhiteSprite( "scorePipEmpty.tga" ) ),
          mShowWatercolorDemo( false ),
          mParchmentSprite( loadSprite( "parchment.tga", false ) ),
          mRedWatercolorSprite( loadSprite( "redWatercolor.tga", false ) ),
          mBlueWatercolorSprite( loadSprite( "blueWatercolor.tga", false ) ),
          mRoundEnding( false ), 
          mRoundEndTime( 0 ) {
    
    for( int i=0; i<2; i++ ) {
        mPlayerCoins[i] = -1;
        mPotCoins[i] = -1;
        }

    double y = cellCenterStart + cellXOffset;
    double x = -cellCenterStart;
    
    for( int i=0; i<6; i++ ) {
        mColumnButtons[i] = 
            new TextButton( mainFont, x, y, "+" );
        
        addComponent( mColumnButtons[i] );
        
        
        setButtonStyle( mColumnButtons[i] );
    

        mColumnButtons[i]->addActionListener( this );
    
        x += cellXOffset;
        }
    

    addComponent( &mCommitButton );
    addComponent( &mBetButton );
    addComponent( &mFoldButton );
    addComponent( &mLeaveButton );
    addComponent( &mBetPicker );
    

    setButtonStyle( &mCommitButton );
    setButtonStyle( &mBetButton );
    setButtonStyle( &mFoldButton );
    setButtonStyle( &mLeaveButton );
    

    mCommitButton.addActionListener( this );
    mBetButton.addActionListener( this );
    mFoldButton.addActionListener( this );
    mLeaveButton.addActionListener( this );

    clearCacheRecords();

    doublePair pos;
    pos.y = - ( (MAX_SCORE_RANGE - 1) * 5 ) / 2;
    pos.x = 300;
    
    for( int i=0; i<MAX_SCORE_RANGE; i++ ) {
        mScorePipPositions[i] = pos;
        
        pos.y += 5;
        }
    
    mScorePipToLabel = -1;
    mScorePipLabelFade = 0;
    mScorePipLabelFadeDelta = -1;
    }


        
PlayGamePage::~PlayGamePage() {
    if( mGameBoard != NULL ) {
        delete [] mGameBoard;
        }

    for( int i=0; i<6; i++ ) {
        delete mColumnButtons[i];
        }
    
    freeSprite( mScorePipSprite );
    freeSprite( mScorePipExtraSprite );
    freeSprite( mScorePipEmptySprite );

    freeSprite( mParchmentSprite );
    freeSprite( mRedWatercolorSprite );
    freeSprite( mBlueWatercolorSprite );
    }



void PlayGamePage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }


    mScorePipToLabel = -1;
    mScorePipLabelFade = 0;
    mScorePipLabelFadeDelta = -1;


    mCommitButton.setVisible( false );
    mBetButton.setVisible( false );
    mFoldButton.setVisible( false );
    
    mLeaveButton.setVisible( true );

    mBetPicker.setVisible( false );

    if( mGameBoard != NULL ) {
        delete [] mGameBoard;
        mGameBoard = NULL;
        }
    
    mPlayerCoins[0] = -1;
    mPlayerCoins[1] = -1;

    clearCacheRecords();
    
    for( int i=0; i<6; i++ ) {
        mColumnButtons[i]->setVisible( false );
        }
    mColumnChoiceForUs = -1;
    mColumnChoiceForThem = -1;
    

    setActionName( "get_game_state" );
    setResponsePartNames( 8, gameStatePartNames );

    clearActionParameters();
    
    mMessageState = gettingState;
    
    startRequest();
    }





void PlayGamePage::actionPerformed( GUIComponent *inTarget ) {
    
    for( int i=0; i<6; i++ ) {
        if( inTarget == mColumnButtons[i] ) {
            
            int numMovesAlreadyMade = 0;
            for( int j=0; j<6; j++ ) {
                if( mOurChoices[j] != -1 ) {
                    numMovesAlreadyMade++;
                    }
                }

            
            
            if( mColumnChoiceForUs == i ) {
                mColumnChoiceForUs = -1;
                mColumnButtons[i]->setLabelText( "+" );
                }
            else if( mColumnChoiceForThem == i ) {
                mColumnChoiceForThem = -1;
                mColumnButtons[i]->setLabelText( "+" );
                }
            else if( mColumnChoiceForUs == -1 ) {
                mColumnChoiceForUs = i;
                mColumnButtons[i]->setLabelText( "x" );
               
                if( numMovesAlreadyMade == 4 ) {
                    // choose both at same time
                    
                    for( int j=0; j<6; j++ ) {
                        if( j != mColumnChoiceForUs && 
                            ! mColumnUsed[j] ) {
                            // force last unpicked column to them
                            mColumnChoiceForThem = j;
                            mColumnButtons[j]->setVisible( false );
                            break;
                            }
                        }
                    }
                }
            else if( mColumnChoiceForThem == -1 ) {
                mColumnChoiceForThem = i;
                mColumnButtons[i]->setLabelText( "x" );
                }

            
            if( mColumnChoiceForUs != -1 &&
                mColumnChoiceForThem != -1 ) {
                
                for( int j=0; j<6; j++ ) {
                    if( j != mColumnChoiceForUs && 
                        j != mColumnChoiceForThem &&
                        ! mColumnUsed[j] ) {
                        
                        mColumnButtons[j]->setVisible( false );
                        }
                    }
                mCommitButton.setVisible( true );
                mLeaveButton.setVisible( false );
                }
            else {
                
                if( numMovesAlreadyMade == 4 ) {
                    // last two moves are exclusive, essentially
                    // one choice
                    
                    // both chosen at same time
                    
                    // if both not chosen, turn both off

                    mColumnChoiceForUs = -1;
                    mColumnChoiceForThem = -1;
                    }
                

                for( int j=0; j<6; j++ ) {
                    if( j != mColumnChoiceForUs && 
                        j != mColumnChoiceForThem &&
                        ! mColumnUsed[j] ) {
                        
                        mColumnButtons[j]->setVisible( true );
                        mColumnButtons[j]->setLabelText( "+" );
                        }
                    }
                mCommitButton.setVisible( false );
                mLeaveButton.setVisible( true );
                }

            computePossibleScores();
            }
        }

    if( inTarget == &mCommitButton ) {
        
        mCommitButton.setVisible( false );
        mLeaveButton.setVisible( true );
    
        clearActionParameters();
        
        setActionName( "make_move" );
        setResponsePartNames( -1, NULL );
        
        setActionParameter( "our_column", mColumnChoiceForUs );
        setActionParameter( "their_column", mColumnChoiceForThem );
        
        mColumnButtons[mColumnChoiceForUs]->setVisible( false );
        mColumnButtons[mColumnChoiceForThem]->setVisible( false );

        mMessageState = sendingMove;
        
        setupRequestParameterSecurity();
        startRequest();
        }
    else if( inTarget == &mBetButton ) {
        
        mBetButton.setVisible( false );
        mFoldButton.setVisible( false );
        mLeaveButton.setVisible( true );

        clearActionParameters();
        
        setActionName( "make_bet" );
        setResponsePartNames( -1, NULL );
        
        int bet = (int)( mBetPicker.getValue() );

        setActionParameter( "bet", bet );
        
        mPotCoins[0] += bet;
        mPlayerCoins[0] -= bet;
        
        mBetPicker.setVisible( false );
        
        mMessageState = sendingBet;
        
        setupRequestParameterSecurity();
        startRequest();
        }
    else if( inTarget == &mFoldButton ) {
        
        mBetButton.setVisible( false );
        mFoldButton.setVisible( false );
        mLeaveButton.setVisible( true );
        
        clearActionParameters();
        
        setActionName( "fold_bet" );
        setResponsePartNames( -1, NULL );
        
        mBetPicker.setVisible( false );
        
        mMessageState = sendingFold;
        
        setupRequestParameterSecurity();
        startRequest();
        }
    else if( inTarget == &mLeaveButton ) {
        setSignal( "back" );
        
        if( mWebRequest != -1 ) {
            clearWebRequestSerial( mWebRequest );
            mWebRequest = -1;
            }
        }
    }




float leftEnd = 0;
float rightEnd = 0;



void PlayGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    

    
    doublePair pos = { - cellCenterStart, cellCenterStart };
    
    if( mGameBoard != NULL ) {
        
        setDrawColor( 1, 1, 1, 1 );
        
        doublePair center  = {0,0};
        drawSquare( center, 
                    ( (cellSize + borderWidth) * 6 ) /  2 + borderWidth / 2 );
        
        int cellIndex = 0;

        char finalReveal = false;

        if( mTheirWonSquares[0] != -1 ) {
            finalReveal = true;
            }
        
        
        for( int y=0; y<6; y++ ) {
            pos.x = -cellCenterStart;
            for( int x=0; x<6; x++ ) {
                
                setDrawColor( 0, 0, 0, 1 );
                
                
                char winningSquare = false;
                
                char theirPossibleWin = false;


                for( int i=0; i<3; i++ ) {
                    if( cellIndex == mOurWonSquares[i] ) {
                        setUsColor();
                        winningSquare = true;
                        }
                    else if( cellIndex == mTheirWonSquares[i] ) {
                        setThemColor();
                        winningSquare = true;
                        }
                    }

                                        
                char theirPossibleWinBlocked = false;
                for( int i=0; i<3; i++ ) {
                    if( y == mTheirChoices[i] ) {
                        theirPossibleWinBlocked = true;
                        break;
                        }
                    }
                        
                            
                if( ! theirPossibleWinBlocked && ! finalReveal ) {
                    
                    for( int j=0; j<3; j++ ) {
                        if( x == mOurChoices[ j * 2 + 1 ] ) {
                            theirPossibleWin = true;
                            break;
                            }
                        }
                    }
                
                

                drawSquare( pos, cellSize/2 );


                if( theirPossibleWin && !winningSquare ) {
                    setThemColor();
                    setDrawFade( 0.5 );
                    drawSquare( pos, cellSize/2 );
                    }


                char *number = autoSprintf( "%d", mGameBoard[y*6 + x] );
            
                // tweak y down a bit as baseline offset for font
                pos.y -= 3;

                if( x == mColumnChoiceForUs && ! mRowUsed[y] ) {
                    setUsColor();
                    }
                else if( x == mColumnChoiceForThem && ! mRowUsed[y] ) {
                    setThemColor();
                    }
                else {    
                    if( ! winningSquare && ! theirPossibleWin &&
                        ( mColumnUsed[x] || mRowUsed[y] ) ) {
                        setDrawColor( 1, 1, 1, 0.25 );
                        }
                    else {
                        setDrawColor( 1, 1, 1, 1 );
                        }
                    }
                    

                mainFont->drawString( number, 
                                      pos, alignCenter );

                delete [] number;
                
                pos.y += 3;

                pos.x += cellXOffset;
                
                cellIndex ++;
                }
            pos.y -= cellSize + borderWidth;
            }

        
        
        // draw score pips
        
        for( int i=0; i<MAX_SCORE_RANGE; i++ ) {
            char empty = true;
            
            doublePair pos = mScorePipPositions[i];

            char drawHighlight = false;
            if( mScorePipToLabel == i &&
                mScorePipLabelFade > 0 ) {
                drawHighlight = true;
                }

            if( mOurPossibleScoresFromTheirPerspective[i] ) {
                setUsColor();
                setDrawFade( 0.75 );
                drawSprite( mScorePipSprite, pos );
                
                empty = false;

                if( drawHighlight ) {
                    setDrawColor( 1, 1, 1, mScorePipLabelFade * 0.5 );
                    drawSprite( mScorePipSprite, pos );
                    }
                }
            if( mOurPossibleScores[i] ) {
                setUsColor();
                pos.x -= 2;
                drawSprite( mScorePipExtraSprite, pos );
                
                empty = false;

                if( drawHighlight ) {
                    setDrawColor( 1, 1, 1, mScorePipLabelFade * 0.5 );
                    drawSprite( mScorePipExtraSprite, pos );
                    }
                
                pos.x += 2;
                }
            
            pos.x += 16;

            
            if( mTheirPossibleScores[i] ) {
                setThemColor();
                drawSprite( mScorePipSprite, pos );
                
                empty = false;

                if( drawHighlight ) {
                    setDrawColor( 1, 1, 1, mScorePipLabelFade * 0.5 );
                    drawSprite( mScorePipSprite, pos );
                    }
                }

            if( empty && drawHighlight ) {
                setDrawColor( 1, 1, 1, 0.35 );
                
                pos.x -= 9;
                
                drawSprite( mScorePipEmptySprite, pos );
                }
            }
        
        if( mScorePipToLabel != -1 &&
            ( mScorePipLabelFade > 0 ||
              mScorePipLabelFadeDelta > 0 ) ) {
            
            mScorePipLabelFade += 
                mScorePipLabelFadeDelta * .05 * frameRateFactor;

            if( mScorePipLabelFade > 1 ) {
                mScorePipLabelFade = 1;
                }
            else if ( mScorePipLabelFade < 0 ) {
                mScorePipLabelFade = 0;
                }

            char *scoreString = autoSprintf( "%d", mScorePipToLabel );
            
            setDrawColor( 1, 1, 1, mScorePipLabelFade );
            
            doublePair pos = mScorePipPositions[mScorePipToLabel];

            pos.x -= 20;

            numbersFontFixed->drawString( scoreString, pos, alignRight );
            
            delete [] scoreString;
            }
        


        int ourScore = 0;
        for( int i=0; i<3; i++ ) {
            if( mOurWonSquares[i] != -1 ) {
                ourScore += mGameBoard[ mOurWonSquares[i] ];
                }
            }

        setDrawColor( 1, 1, 1, 1 );
        doublePair pos = { 319, 300 };
        char *scoreString = autoSprintf( "%d", ourScore );
        
        mainFont->drawString( scoreString, pos, alignRight );
        
        delete [] scoreString;


        if( finalReveal ) {
            int theirScore = 0;
            for( int i=0; i<3; i++ ) {
                if( mTheirWonSquares[i] != -1 ) {
                    theirScore += mGameBoard[ mTheirWonSquares[i] ];
                    }
                }

            setDrawColor( 1, 1, 1, 1 );
            doublePair pos = { 319, -300 };
            char *scoreString = autoSprintf( "%d", theirScore );
            
            mainFont->drawString( scoreString, pos, alignRight );
        
            delete [] scoreString;
            }
        
        }
    
    
    if( mPlayerCoins[0] != -1 ) {
        // draw coins
        pos.x = -264;
        pos.y = 288;
        
        char *number = autoSprintf( "%d", mPlayerCoins[0] );

        setUsColor();
        
        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;


        pos.y = 32;
        number = autoSprintf( "%d", mPotCoins[0] );
        
        if( mRunning ) {
            mainFont->drawString( number, 
                                  pos, alignRight );
            }
        
        delete [] number;



        pos.y = -288;

        number = autoSprintf( "%d", mPlayerCoins[1] );
        
        setThemColor();
        
        if( mRunning ) {
            mainFont->drawString( number, 
                                  pos, alignRight );
            }

        delete [] number;


        pos.y = -32;
        number = autoSprintf( "%d", mPotCoins[1] );
        
        if( mRunning ) {
            mainFont->drawString( number, 
                                  pos, alignRight );
            }

        delete [] number;
        }

    if( mShowWatercolorDemo ) {
        
        doublePair parchPos = { 0, 0 };
    
        setDrawColor( 1, 1, 1, 1 );

        drawSprite( mParchmentSprite, parchPos );
    
        toggleMultiplicativeBlend( true );

        drawSprite( mRedWatercolorSprite, parchPos );
    
        parchPos.y += 32;
        parchPos.x += 32;
        drawSprite( mBlueWatercolorSprite, parchPos );
    
        FloatColor spriteColors[4];
    
        for( int i=0; i<4; i++ ) {
            float value = leftEnd;
            if( i == 1 || i == 2 ) {
                value = rightEnd;
                }
            spriteColors[i].r = value;
            spriteColors[i].g = value;
            spriteColors[i].b = value;
            spriteColors[i].a = 1;
            }
    
        toggleAdditiveTextureColoring( true );
        drawSprite( mBlueWatercolorSprite, lastMousePos, spriteColors );
        toggleAdditiveTextureColoring( false );

        toggleMultiplicativeBlend( false );
        //drawMessage( "test messag", pos );    

        if( leftEnd > 0 ) {
            leftEnd -= 0.02;
            }
        else if( rightEnd > 0 ) {
            rightEnd -= 0.02;
            }
        }
    
        
    }



// returns -1 if the int is ?
int stringToInt( const char *inString ) {
    
    int returnValue = -1;
    
    sscanf( inString, "%d", &returnValue );

    return returnValue;
    }



void PlayGamePage::step() {

    if( mRoundEnding && mRoundEndTime < game_time( NULL ) ) {
        clearActionParameters();
        
        setActionName( "end_round" );
        setResponsePartNames( -1, NULL );
        
        mMessageState = sendingEnd;
        
        setupRequestParameterSecurity();
        startRequest();
        mRoundEnding = false;
        }
    

    ServerActionPage::step();

    if( ! isResponseReady() ) {
        return;
        }
    
    
    
    if( mMessageState == sendingMove ) {
        
        // move sent
        
        // wait for opponent's move

        clearActionParameters();
        
        setActionName( "wait_move" );
        setResponsePartNames( 1, waitMovePartNames );

        mMessageState = waitingMove;
        
        startRequest();
        }
    else if( mMessageState == sendingBet ) {
        
        // bet sent
        
        // wait for opponent's bet

        clearActionParameters();
        
        setActionName( "wait_move" );
        setResponsePartNames( 1, waitMovePartNames );

        mMessageState = waitingBet;
        
        startRequest();
        }
    else if( mMessageState == sendingFold ) {
        
        // fold sent
        
        // new game started
        setActionName( "get_game_state" );
        setResponsePartNames( 8, gameStatePartNames );
        
        clearActionParameters();
        mMessageState = gettingState;
        
        startRequest();
        }
    else if( mMessageState == sendingEnd ) {
        
        // end sent
        
        // wait for opponent's end

        clearActionParameters();
        
        setActionName( "wait_move" );
        setResponsePartNames( 1, waitMovePartNames );

        mMessageState = waitingEnd;
        
        startRequest();
        }
    else if( mMessageState == gettingState ||
             mMessageState == gettingStatePostMove ||
             mMessageState == gettingStatePostBet ) {
        
        mRunning = getResponseInt( "running" );
        mPlayerCoins[0] = getResponseInt( "ourCoins" );
        mPlayerCoins[1] = getResponseInt( "theirCoins" );

        mPotCoins[0] = getResponseInt( "ourPotCoins" );
        mPotCoins[1] = getResponseInt( "theirPotCoins" );

        char *gameBoardString = getResponse( "boardLayout" );
        
        int numParts;
        char **parts = split( gameBoardString, "#", &numParts );
        
        delete [] gameBoardString;
        

        if( numParts == 36 ) {
            if( mGameBoard != NULL ) {
                delete [] mGameBoard;
                }
            mGameBoard = new int[36];
            
            for( int i=0; i<numParts; i++ ) {
                sscanf( parts[i], "%d", &( mGameBoard[i] ) );
                }
            }
        
        for( int i=0; i<numParts; i++ ) {
            delete [] parts[i];
            }
        delete [] parts;


        char *ourMoves = getResponse( "ourMoves" );
        char *theirMoves = getResponse( "theirMoves" );
        
        for( int i=0; i<6; i++ ) {
            mRowUsed[i] = false;
            mColumnUsed[i] = false;
            mOurChoices[i] = -1;
            mTheirChoices[i] = -1;
            }
        for( int i=0; i<3; i++ ) {
            mOurWonSquares[i] = -1;
            mTheirWonSquares[i] = -1;
            }
        
        if( strcmp( ourMoves, "#" ) != 0 ) {
            // some moves submitted
            
            int numOurParts;
            char **ourParts = split( ourMoves, "#", &numOurParts );

            int numTheirParts;
            char **theirParts = split( theirMoves, "#", &numTheirParts );
            
            if( numTheirParts != numOurParts ) {
                mStatusError = true;
                setStatus( "err_badServerResponse", true );
                }
            else {
                
                int ourChoiceIndex = 0;

                // their choice for them comes first, which might be ?
                // we store them in us, us, us, them, them, them order locally
                int theirChoiceMapping[6] = { 3, 0, 4, 1, 5, 2 };

                for( int i=0; i<numOurParts; i++ ) {
                    int ourChoice = stringToInt( ourParts[i] );
                    int theirChoice = stringToInt( theirParts[i] );
                    
                    if( ourChoice != -1 ) {
                        mColumnUsed[ourChoice] = true;
                        
                        mOurChoices[ ourChoiceIndex ] = ourChoice;
                        ourChoiceIndex++;
                        }
                    if( theirChoice != -1 ) {
                        mRowUsed[theirChoice] = true;
                        }
                    
                    mTheirChoices[ theirChoiceMapping[i] ] = theirChoice;
                    }
                
                
                int ourWonSquareCount = 0;
                int theirWonSquareCount = 0;
                
                for( int i=0; i<numOurParts; i += 2 ) {
                    
                    int ourSelfChoice = stringToInt( ourParts[i] );
                    int ourOtherChoice = stringToInt( ourParts[i+1] );
                    
                    int theirSelfChoice = stringToInt( theirParts[i] );
                    int theirOtherChoice = stringToInt( theirParts[i+1] );

                    if( ourSelfChoice != -1 &&
                        theirOtherChoice != -1 ) {
                        
                        int ourWonIndex = theirOtherChoice * 6 + ourSelfChoice;
                        
                        mOurWonSquares[ ourWonSquareCount ] =
                            ourWonIndex;
                        
                        ourWonSquareCount ++;
                        }
                    
                    if( theirSelfChoice != -1 &&
                        ourOtherChoice != -1 ) {
                        
                        int theirWonIndex = 
                            theirSelfChoice * 6 + ourOtherChoice;
                        
                        mTheirWonSquares[ theirWonSquareCount ] =
                            theirWonIndex;
                        
                        theirWonSquareCount ++;
                        }
                    
                    
                    }
                }
            
            for( int i=0; i<numOurParts; i++ ) {
                delete [] ourParts[i];
                }
            delete [] ourParts;
            
            for( int i=0; i<numTheirParts; i++ ) {
                delete [] theirParts[i];
                }
            delete [] theirParts;
            

            }
        delete [] ourMoves;
        delete [] theirMoves;

        mColumnChoiceForUs = -1;
        mColumnChoiceForThem = -1;

        
        if( ! mRunning || 
            mPotCoins[0] == 0 ||
            mPotCoins[1] == 0 ) {
            
            // one player left or either down to 0

            // game over

            mLeaveButton.setVisible( true );
            }
        else if( mMessageState == gettingState 
            ||
            ( mMessageState == gettingStatePostBet &&
              mPotCoins[0] == mPotCoins[1] ) ) {
            
            int numUsedColumns = 0;
            
            for( int i=0; i<6; i++ ) {
                mColumnButtons[i]->setVisible( ! mColumnUsed[i] );
                mColumnButtons[i]->setLabelText( "+" );
                
                if( mColumnUsed[i] ) {
                    numUsedColumns ++;
                    }
                }
            
            if( numUsedColumns == 0 ) {
                // new round just started, new grid
                // possible score cache is stale
                clearCacheRecords();
                }


            if( mTheirWonSquares[0] != -1 ) {
                // reveal has happened
                mRoundEnding = true;
                mRoundEndTime = game_time( NULL ) + 5;
                }
            }
        else {
            // betting time
            mBetPicker.setVisible( true );

            // we can't bet more than our opponent can afford
            int max = mPlayerCoins[1] + mPotCoins[1] - mPotCoins[0];

            // we can't bet more than we can afford
            if( mPlayerCoins[0] < max ) {
                max = mPlayerCoins[0];
                }

            mBetPicker.setMax( max );
            
            int minBet = mPotCoins[1] - mPotCoins[0];
            
            if( minBet < 0 ) {
                minBet = 0;
                }
            if( minBet > mPlayerCoins[0] ) {
                minBet = mPlayerCoins[0];
                }
            
            mBetPicker.setMin( minBet );
            
            mBetPicker.setValue( minBet );

            mBetButton.setVisible( true );
            if( minBet > 0 ) {
                mFoldButton.setVisible( true );
                }
            mLeaveButton.setVisible( false );
            }
        
        computePossibleScores();

        mMessageState = responseProcessed;
        }
    else if( mMessageState == waitingMove ||
             mMessageState == waitingBet ||
             mMessageState == waitingEnd ) {
        char *status = getResponse( "status" );

        if( strcmp( status, "move_ready" ) == 0 ) {
            
            // get the new game state
            setActionName( "get_game_state" );
            setResponsePartNames( 8, gameStatePartNames );

            clearActionParameters();
    
            if( mMessageState == waitingMove ) {
                mMessageState = gettingStatePostMove;
                }
            else if( mMessageState == waitingBet ) {
                mMessageState = gettingStatePostBet;
                }
            else {
                mMessageState = gettingState;
                }
            
            startRequest();
            }
        else if( strcmp( status, "round_ended" ) == 0 ) {
            // start of new round
            setActionName( "get_game_state" );
            setResponsePartNames( 8, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingState;
            
            startRequest();
            }
        else if( strcmp( status, "opponent_left" ) == 0 ) {
            // get final state
            setActionName( "get_game_state" );
            setResponsePartNames( 8, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingState;
            
            startRequest();
            
            mLeaveButton.setVisible( true );
            }
        else if( strcmp( status, "waiting" ) == 0 ) {
            // keep waiting
            startRequest();
            }
        
        delete [] status;
        }
    

    }






static void callOnAllPerm( int *inValuesToPermute, 
                           int inNumValues, int inNumSkip,
                           void inProcessPermutationCallback( 
                               int *inValues,
                               int inNumValues,
                               void *inExtraParam ),
                           void *inExtraParam ) {

    if( inNumSkip >= inNumValues ) {
        inProcessPermutationCallback( inValuesToPermute, inNumValues,
                                      inExtraParam );
        return;
        }
    

    // pick value to stick in spot at inNumSkip
    
    for( int i=inNumSkip; i<inNumValues; i++ ) {
        
        int temp = inValuesToPermute[inNumSkip];
        inValuesToPermute[ inNumSkip ] = inValuesToPermute[i];
        inValuesToPermute[i] = temp;
        
        callOnAllPerm( inValuesToPermute, inNumValues, inNumSkip+1,
                       inProcessPermutationCallback, inExtraParam );
        // revert
        inValuesToPermute[i] = inValuesToPermute[ inNumSkip ];
        inValuesToPermute[ inNumSkip ] = temp;
        }
    }



typedef struct ScoreSearchRecord {
        int *gameBoard;
        
        char *ourPossibleScores;
        char *theirPossibleScores;

        char *ourPossibleScoresFromTheirPerspective;
        
        int *ourChoices;
        int *theirChoices;

        int testOurChoices[6];

        // if true, testOurChoices (and our choices passed in as inValues)
        // are in {us, them, us, them, us, them} order
        // if false, choices are in {them, them, them, us, us, us } order
        //
        // if false, processing callbacks will tally scores only into
        // ourPossibleScoresFromTheirPerspective
        char ourChoicesInterleaved;

    } ScoreSearchRecord;



// p1's full move list is in ScoreSearchRecord
// p2's full move list is inValues
//
// result stored in record
static void getScoreForBothPlayerMoves( int *inValues, 
                                        int inNumValues,
                                        void *inExtraParam ) {

    ScoreSearchRecord *record = (ScoreSearchRecord*)inExtraParam;

    
    if( record->ourChoicesInterleaved ) {

        // our scores
        int ourScore = 0;
        for( int i=0; i<3; i++ ) {
            int y = inValues[i];
            int x = record->testOurChoices[ i*2 ];
        
            ourScore += record->gameBoard[ y * 6 + x ];
            }
        
        record->ourPossibleScores[ourScore] = true;
        
        
        // their scores
        int theirScore = 0;
        for( int i=0; i<3; i++ ) {
            int y = inValues[i + 3];
            int x = record->testOurChoices[ 1 + i*2 ];
            
            theirScore += record->gameBoard[ y * 6 + x ];        
            }
        
        record->theirPossibleScores[theirScore] = true;
        }
    else {
        // only compute our score range from their perspective
        int ourScore = 0;
        for( int i=0; i<3; i++ ) {
            int y = inValues[i];
            int x = record->testOurChoices[ i + 3 ];
        
            ourScore += record->gameBoard[ y * 6 + x ];
            }
        
        record->ourPossibleScoresFromTheirPerspective[ourScore] = true;
        }
    

    }



// returns numToSkip (already fixed choices in source
// fills in remaining choices in order (for input to permutation function)

static int fillInExtraChoices( int *inSourceChoices, int *inDestChoices ) {
    
    memcpy( inDestChoices, inSourceChoices, 6 * sizeof( int ) );

    // remaining rows can be used
    int numToSkip = 0;
    
    for( int i=0; i<6; i++ ) {
        if( inDestChoices[i] != -1 ) {
            numToSkip++;
            }
        }
    
    int nextIndex = numToSkip;
    
    for( int i=0; i<6; i++ ) {
        
        char alreadyUsed = false;
        for( int j=0; j<6; j++ ) {
            if( inDestChoices[j] == i ) {
                alreadyUsed = true;
                break;
                }
            }
        
        if( !alreadyUsed ) {
            inDestChoices[nextIndex] = i;
            nextIndex++;
            }
        }

    return numToSkip;
    }





// inValues are our fixed move
// their choices so far are in inExtraParam as a ScoreSearchRecord
static void testAllTheirMovesWithFixedOurMove( int *inValues, 
                                               int inNumValues,
                                               void *inExtraParam ) {
        
    ScoreSearchRecord *record = (ScoreSearchRecord*)inExtraParam;
    
    memcpy( record->testOurChoices, inValues,
            sizeof( int ) * inNumValues );
    
    
    int theirChoices[6];

    int numToSkip = fillInExtraChoices( record->theirChoices,
                                        theirChoices );
        
    callOnAllPerm( theirChoices, 6, numToSkip,
                   getScoreForBothPlayerMoves,
                   inExtraParam );
    }





void PlayGamePage::computePossibleScores() {
    for( int i=0; i<MAX_SCORE_RANGE; i++ ) {
        mOurPossibleScores[i] = false;
        mTheirPossibleScores[i] = false;
        
        mOurPossibleScoresFromTheirPerspective[i] = false;
        }
    
    if( loadCacheRecord() ) {
        // cached!
        return;
        }
    


    ScoreSearchRecord record;
    record.gameBoard = mGameBoard;
    record.ourPossibleScores = mOurPossibleScores;
    record.theirPossibleScores = mTheirPossibleScores;
    record.ourPossibleScoresFromTheirPerspective = 
        mOurPossibleScoresFromTheirPerspective;
    record.ourChoices = mOurChoices;
    record.theirChoices = mTheirChoices;
    record.ourChoicesInterleaved = true;



    // fill in our uncommitted choices too
    int ourUncommittedChoices[6];
    
    memcpy( ourUncommittedChoices, record.ourChoices, 6 * sizeof( int ) );
    
    
    int numToSkip = 0;
    for( int i=0; i<6; i++ ) {
        if( ourUncommittedChoices[i] != -1 ) {
            numToSkip++;
            }
        }
    if( numToSkip < 5 ) {
        ourUncommittedChoices[numToSkip] = mColumnChoiceForUs;
        numToSkip++;
        }
    // don't stick in our choice for them if we have no choice for us
    // for first computation, choices must come in pairs or partial pairs
    if( mColumnChoiceForUs != -1 && numToSkip < 6 ) {
        ourUncommittedChoices[numToSkip] = mColumnChoiceForThem;
        }



    // first, compute our possible scores from our perspective,
    // and their possible scores, given what we know about them
    int ourChoices[6];


    numToSkip = fillInExtraChoices( ourUncommittedChoices,
                                    ourChoices );
    

    callOnAllPerm( ourChoices, 6, numToSkip,
                   testAllTheirMovesWithFixedOurMove,
                   (void*)( &record ) );
    



    // now compute our possible scores from their perspective
    int ourChoicesTheirPerspective[6];
    
    // order is {them, them, them, us, us, us}
    // fill in our choices for them (which they know)
    int j = 0;
    for( int i=1; i<6; i+=2 ) {
        ourChoicesTheirPerspective[j] = ourUncommittedChoices[i];
        j++;
        }
    
    
    for( int j=0; j<3; j++ ) {
        if( ourChoicesTheirPerspective[j] == mColumnChoiceForThem ) {
            // already present, don't add it
            break;
            }
        if( ourChoicesTheirPerspective[j] == -1 ) {
            // add in our final uncommitted choice for them
            ourChoicesTheirPerspective[j] = mColumnChoiceForThem;
            break;
            }
        }
    
    // leave our choices for us empty (they don't know them)
    ourChoicesTheirPerspective[3] = -1;
    ourChoicesTheirPerspective[4] = -1;
    ourChoicesTheirPerspective[5] = -1;
    
    
    // unless reveal has happened
    if( mTheirWonSquares[0] != -1 ) {
        int j = 3;
        for( int i=0; i<6; i+=2 ) {
            ourChoicesTheirPerspective[j] = ourUncommittedChoices[i];
            j++;
            }
        }
    


    numToSkip = fillInExtraChoices( ourChoicesTheirPerspective,
                                    ourChoices );

    record.ourChoicesInterleaved = false;
    

    callOnAllPerm( ourChoices, 6, numToSkip,
                   testAllTheirMovesWithFixedOurMove,
                   (void*)( &record ) );

    storeCacheRecord();
    }




void PlayGamePage::clearCacheRecords() {
    for( int i=0; i<NUM_CACHE_RECORDS; i++ ) {
        mCacheRecords[i].recordAge = -1;
        }
    mCurrentCacheAge = 0;
    }



void PlayGamePage::storeCacheRecord() {
    int r = -1;
    int oldestAge = mCurrentCacheAge;

    // empty record?  or oldest?
    for( int i=0; i<NUM_CACHE_RECORDS; i++ ) {
        if( mCacheRecords[i].recordAge == -1 ) {
            r = i;
            break;
            }
        else {
            if( mCacheRecords[i].recordAge < oldestAge ) {
                oldestAge =  mCacheRecords[i].recordAge;
                r = i;
                }
            }
        }
    
    mCacheRecords[r].recordAge = mCurrentCacheAge;
    mCurrentCacheAge ++;

    memcpy( mCacheRecords[r].ourChoices, mOurChoices, 6 * sizeof( int ) );
    memcpy( mCacheRecords[r].theirChoices, mTheirChoices, 6 * sizeof( int ) );
    
    mCacheRecords[r].columnChoiceForUs = mColumnChoiceForUs;
    mCacheRecords[r].columnChoiceForThem = mColumnChoiceForThem;
    

    memcpy( mCacheRecords[r].ourPossibleScores, 
            mOurPossibleScores, MAX_SCORE_RANGE );
    
    memcpy( mCacheRecords[r].theirPossibleScores, 
            mTheirPossibleScores, MAX_SCORE_RANGE );
    
    memcpy( mCacheRecords[r].ourPossibleScoresFromTheirPerspective, 
            mOurPossibleScoresFromTheirPerspective, 
            MAX_SCORE_RANGE );
    }



char PlayGamePage::loadCacheRecord() {
    char found = false;
    
    for( int r=0; r<NUM_CACHE_RECORDS; r++ ) {
        if( mCacheRecords[r].recordAge == -1 ) {
            continue;
            }

        if( mCacheRecords[r].columnChoiceForUs != mColumnChoiceForUs
            ||
            mCacheRecords[r].columnChoiceForThem != mColumnChoiceForThem ) {
            
            continue;
            }
        
        char match = true;
        
        for( int c=0; c<6; c++ ) {
            if( mCacheRecords[r].ourChoices[c] != mOurChoices[c]
                ||
                mCacheRecords[r].theirChoices[c] != mTheirChoices[c] ) {
                match = false;
                break;
                }
            }
        
        if( !match ) {
            continue;
            }
        
        
        found = true;
        
        memcpy( mOurPossibleScores, mCacheRecords[r].ourPossibleScores, 
                MAX_SCORE_RANGE );
    
        memcpy( mTheirPossibleScores, mCacheRecords[r].theirPossibleScores, 
                MAX_SCORE_RANGE );
    
        memcpy( mOurPossibleScoresFromTheirPerspective, 
                mCacheRecords[r].ourPossibleScoresFromTheirPerspective, 
                MAX_SCORE_RANGE );

        mCacheRecords[r].recordAge = mCurrentCacheAge;
        mCurrentCacheAge++;
        }    
    
    return found;
    }



void PlayGamePage::pointerMove( float inX, float inY ) {
    lastMousePos.x = inX;
    lastMousePos.y = inY;
    
    leftEnd = 1;
    rightEnd = 1;

    mScorePipLabelFadeDelta = -1;
    
    doublePair pos = mScorePipPositions[0];
    
    if( inX > pos.x + 31 ||
        inX < pos.x - 64 ) {
        return;
        }
    
    if( inY < mScorePipPositions[0].y - 2 ||
        inY > mScorePipPositions[MAX_SCORE_RANGE-1].y + 2 ) {
        return;
        }
        

    for( int i=0; i<MAX_SCORE_RANGE; i++ ) {
        doublePair pos = mScorePipPositions[i];
    
        if( fabs( pos.y - inY ) <= 2 ) {
            
            // something to mouse-over here
                

            mScorePipToLabel = i;
            mScorePipLabelFadeDelta = 1;
            
            break;
            }
        }
    
    
    }



void PlayGamePage::keyDown( unsigned char inASCII ) {
    if( inASCII == 'w' || inASCII == 'W' ) {
        mShowWatercolorDemo = ! mShowWatercolorDemo;
        }
    }










