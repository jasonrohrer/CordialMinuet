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



const char *gameStatePartNames[9] = { "running", "boardLayout", 
                                      "ourCoins", "theirCoins", 
                                      "ourPotCoins", "theirPotCoins",
                                      "ourMoves", "theirMoves",
                                      "secondsLeft" };

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



// assumes strokes are 64 wide
// outArray must have room for however many strokes are in image
// and each stroke divided up into 6 sub-strokes
static void readWatercolorImages( const char *inTGAFileName,
                                  char inVertical,
                                  SpriteHandle outSpriteArray[][6] ) {
    
    Image *watercolorImage = readTGAFile( inTGAFileName );
    
    if( watercolorImage != NULL ) {
        int w = watercolorImage->getWidth();
        int h = watercolorImage->getHeight();
        
        int numStrokes;
        int strokeSpriteW;
        int strokeSpriteH;
        
        if( inVertical ) {
            numStrokes =  w / 64;
            strokeSpriteH = h;
            strokeSpriteW = 64;
            }
        else {
            numStrokes = h / 64;
            
            strokeSpriteH = 64;
            strokeSpriteW = w;
            }
        
        for( int i=0; i<numStrokes; i++ ) {
            
            int xOffset;
            int yOffset;
            
            if( inVertical ) {
                xOffset = i * 64;
                yOffset = 0;
                }
            else {
                xOffset = 0;
                yOffset = i * 64;
                }

            Image *strokeImage = 
                watercolorImage->getSubImage( xOffset, yOffset,
                                              strokeSpriteW, strokeSpriteH );
            
            
            // break into sub-strokes
            int substrokeStartOffset;
            if( inVertical ) {
                substrokeStartOffset = ( h - ( 6 * 64 ) ) / 2;
                }
            else {
                substrokeStartOffset = ( w - ( 6 * 64 ) ) / 2;
                }
            
            for( int s=0; s<6; s++ ) {
                int subXOffset, subYOffset;
                
                if( inVertical ) {
                    subXOffset = 0;
                    subYOffset = s * 64 + substrokeStartOffset;
                    }
                else {
                    subXOffset = s * 64 + substrokeStartOffset;
                    subYOffset = 0;
                    }
                Image *subStrokeImage = 
                    strokeImage->getSubImage( subXOffset, subYOffset,
                                                  64, 64 );
                

                outSpriteArray[i][s] = fillSprite( subStrokeImage, false );
              
                delete subStrokeImage;
                }
            

            //outSpriteArray[i] = fillSprite( strokeImage, false );
            
            delete strokeImage;
            }

        delete watercolorImage;
        }
    
    }






PlayGamePage::PlayGamePage()
        : ServerActionPage( "get_game_state", 9, gameStatePartNames ),
          mGameBoard( NULL ),
          mCoinSprite( loadWhiteSprite( "coin.tga" ) ),
          mCoinTenSprite( loadWhiteSprite( "coinTen.tga" ) ),
          mMoveDeadline( 0 ),
          mMoveDeadlineFade( 0 ),
          mMoveDeadlineFadeDelta( 1 ),
          mCommitButton( mainFont, 0, -288, translate( "commit" ) ),
          mBetButton( mainFont, 0, -288, translate( "bet" ) ),
          mFoldButton( mainFont, 120, -288, translate( "fold" ) ),
          mLeaveButton( mainFont, -128, -288, translate( "leave" ) ),
          mBetPicker( mainFont, -64, -288, 3, 0, "" ),
          mCommitFlashPreSteps( 0 ),
          mCommitFlashProgress( 1.0 ),
          mCommitFlashDirection( -1 ),
          mColumnChoiceForUs( -1 ), mColumnChoiceForThem( -1 ),
          mRevealChoiceForUs( -1 ),
          mScorePipSprite( loadWhiteSprite( "scorePip.tga" ) ),
          mScorePipExtraSprite( loadWhiteSprite( "scorePipExtra.tga" ) ),
          mScorePipEmptySprite( loadWhiteSprite( "scorePipEmpty.tga" ) ),
          mShowWatercolorDemo( false ),
          mParchmentSprite( loadSprite( "parchment.tga", true ) ),
          mRedWatercolorSprite( loadSprite( "redWatercolor.tga", false ) ),
          mBlueWatercolorSprite( loadSprite( "blueWatercolor.tga", false ) ),
          mInkGridSprite( loadSprite( "inkGrid.tga", false ) ),
          mRoundEnding( false ), 
          mRoundEndTime( 0 ),
          mRoundStarting( false ),
          mRoundStartTime( 0 ) {
    


    Image *numbersImage = readTGAFile( "inkNumbers.tga" );
    
    if( numbersImage != NULL ) {
        
        int width = numbersImage->getWidth();
        
        int height = numbersImage->getHeight();

        int numberWidth = width / 6;
        int numberHeight = height / 6;
        

        // pad individual sprite width and height to powers to 2
        int paddedW = numberWidth;
        int paddedH = numberHeight;

        int wPadOffset = 0;
        int hPadOffset = 0;

        double log2w = log( numberWidth ) / log( 2 );
        double log2h = log( numberHeight ) / log( 2 );
    

        int next2PowerW = (int)( ceil( log2w ) );
        int next2PowerH = (int)( ceil( log2h ) );
    
        if( next2PowerW != log2w ) {
            paddedW = (int)( pow( 2, next2PowerW ) );
            
            wPadOffset = ( paddedW - numberWidth ) / 2;
            }

        if( next2PowerH != log2h ) {
            paddedH = (int)( pow( 2, next2PowerH ) );
            
            hPadOffset = ( paddedH - numberHeight ) / 2;
            }
        
        for( int i=0; i<36; i++ ) {
            
            int x = i % 6;
            int y = i / 6;
            

            Image *oneNumberImage = 
                numbersImage->getSubImage( x * numberWidth, 
                                           y * numberHeight,
                                           numberWidth, numberHeight );
            
            int numChannels = oneNumberImage->getNumChannels();
    
            Image paddedImage( paddedW, paddedH, numChannels, false );
            
            int numPaddedPixels = paddedW * paddedH;

            for( int c=0; c<numChannels; c++ ) {
                double *destChannel = paddedImage.getChannel( c );
                double *sourceChannel = oneNumberImage->getChannel( c );
        
                for( int p=0; p<numPaddedPixels; p++ ) {
                    destChannel[p] = 1.0;
                    }

                for( int r=0; r<numberHeight; r++ ) {
                    // copy row
                    memcpy( &( destChannel[ (r+hPadOffset) * paddedW + 
                                            wPadOffset ] ),
                            &( sourceChannel[ r * numberWidth ] ),
                            sizeof( double ) * numberWidth );
                    }
                }
            
            mInkNumberSprites[i] = fillSprite( &paddedImage, false );
            
            delete oneNumberImage;
            }
        
        delete numbersImage;
        }
    

    
    readWatercolorImages( "greenWatercolorV.tga", true, 
                          mGreenWatercolorVSprites );

    readWatercolorImages( "greenWatercolorH.tga", false, 
                          mGreenWatercolorHSprites );

    readWatercolorImages( "redWatercolorV.tga", true, 
                          mRedWatercolorVSprites );

    readWatercolorImages( "blackWatercolorV.tga", true, 
                          mBlackWatercolorVSprites );

    readWatercolorImages( "blackWatercolorH.tga", false, 
                          mBlackWatercolorHSprites );
    
    
    mInkGridCenter.x = 0;
    mInkGridCenter.y = 0;
    
    for( int i=0; i<6; i++ ) {
        mColumnPositions[i] = mInkGridCenter;
        mColumnPositions[i].x -= 138;
        mColumnPositions[i].x += i * 55;


        mRowPositions[i] = mInkGridCenter;
        mRowPositions[i].y += 137;
        mRowPositions[i].y -= i * 54;
        }
    
    

    // put status message on top of screen so that errors don't
    // overlap with leave button
    setStatusPositiion( true );
    

    for( int i=0; i<2; i++ ) {
        mPlayerCoins[i] = -1;
        mPotCoins[i] = -1;

        mPlayerCoinSpots[i].coinCount = &( mPlayerCoins[i] );
        mPotCoinSpots[i].coinCount = &( mPotCoins[i] );
        
        mPlayerCoinSpots[i].position.x = -244;
        mPotCoinSpots[i].position.x = -244;
        }

    mPlayerCoinSpots[1].position.y = 291;
    mPotCoinSpots[1].position.y = 35;

    mPlayerCoinSpots[0].position.y = -285;
    mPotCoinSpots[0].position.y = -29;
    
    // off top of screen
    mHouseCoinSpot.position.x = 0;
    mHouseCoinSpot.position.y = 333 + 16;
    mHouseCoinSpot.coinCount = NULL;
    

    // off top of screen on opponent's side, to show where they
    // take pot if they win and leave before coins fly
    mOpponentGoneCoinSpot.position.x = mPlayerCoinSpots[1].position.x;
    mOpponentGoneCoinSpot.position.y = 333 + 16;
    mOpponentGoneCoinSpot.coinCount = NULL;
    


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
    
    freeSprite( mCoinSprite );
    freeSprite( mCoinTenSprite );

    freeSprite( mScorePipSprite );
    freeSprite( mScorePipExtraSprite );
    freeSprite( mScorePipEmptySprite );

    freeSprite( mParchmentSprite );
    freeSprite( mRedWatercolorSprite );
    freeSprite( mBlueWatercolorSprite );
    freeSprite( mInkGridSprite );

    for( int i=0; i<36; i++ ) {
        freeSprite( mInkNumberSprites[i] );
        }
    
    for( int i=0; i<3; i++ ) {
        for( int s=0; s<6; s++ ) {
            freeSprite( mGreenWatercolorVSprites[i][s] );
            freeSprite( mGreenWatercolorHSprites[i][s] );
            
            freeSprite( mRedWatercolorVSprites[i][s] );
            }
        }
    for( int i=0; i<6; i++ ) {
        for( int s=0; s<6; s++ ) {
            freeSprite( mBlackWatercolorVSprites[i][s] );
            freeSprite( mBlackWatercolorHSprites[i][s] );
            }
        }
    }



void PlayGamePage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }


    mScorePipToLabel = -1;
    mScorePipLabelFade = 0;
    mScorePipLabelFadeDelta = -1;


    mCommitButton.setVisible( false );
    // clear flashing
    setButtonStyle( &mCommitButton );

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

    mPotCoins[0] = -1;
    mPotCoins[1] = -1;

    clearCacheRecords();
    
    for( int i=0; i<6; i++ ) {
        mColumnButtons[i]->setVisible( false );
        }
    mColumnChoiceForUs = -1;
    mColumnChoiceForThem = -1;
    
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

    setActionName( "get_game_state" );
    setResponsePartNames( 9, gameStatePartNames );

    clearActionParameters();
    
    mRoundEnding = false;
    mRoundStarting = false;

    mFlyingCoins[0].deleteAll();
    mFlyingCoins[1].deleteAll();

    mWatercolorStrokes.deleteAll();
    
    mNextGreenVSprite = 0;
    mNextGreenHSprite = 0;
    mNextRedVSprite = 0;
    
    mParchmentFade = 1;
    mParchmentFadingOut = false;

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

            
            if( numMovesAlreadyMade == 6 ) {
                
                if( mRevealChoiceForUs == i ) {
                    mRevealChoiceForUs = -1;
                    mColumnButtons[i]->setLabelText( "R" );
                    }
                else if( mRevealChoiceForUs == -1 ) {
                    mRevealChoiceForUs = i;
                    mColumnButtons[i]->setLabelText( "x" );
                    }
                }
            else if( mColumnChoiceForUs == i ) {
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
                mCommitFlashPreSteps = 0;
                mCommitFlashProgress = 1.0;
                mCommitFlashDirection = -1;
                
                mLeaveButton.setVisible( false );
                }
            else if( numMovesAlreadyMade == 6 ) {
                if( mRevealChoiceForUs != -1 ) {
                    for( int j=0; j<6; j++ ) {
                        if( j != mRevealChoiceForUs ) {
                        
                            mColumnButtons[j]->setVisible( false );
                            }
                        }
                    mCommitButton.setVisible( true );
                    mCommitFlashPreSteps = 0;
                    mCommitFlashProgress = 1.0;
                    mCommitFlashDirection = -1;
                    
                    
                    mLeaveButton.setVisible( false );
                    }
                else {
                    mColumnButtons[mOurChoices[0]]->setVisible( true );
                    mColumnButtons[mOurChoices[2]]->setVisible( true );
                    mColumnButtons[mOurChoices[4]]->setVisible( true );

                    mCommitButton.setVisible( false );
                    // clear flashing
                    setButtonStyle( &mCommitButton );

                    mLeaveButton.setVisible( true );
                    }
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
                // clear flashing
                setButtonStyle( &mCommitButton );

                mLeaveButton.setVisible( true );
                }

            computePossibleScores();
            }
        }

    if( inTarget == &mCommitButton ) {
        mMoveDeadline = 0;

        mCommitButton.setVisible( false );
        // clear flashing
        setButtonStyle( &mCommitButton );
    
        mLeaveButton.setVisible( true );
    
        clearActionParameters();

        int numUsedColumns = 0;
            
        for( int i=0; i<6; i++ ) {
            if( mColumnUsed[i] ) {
                numUsedColumns ++;
                }
            }
        

        setResponsePartNames( -1, NULL );
        

        mMessageState = sendingMove;

        if( numUsedColumns < 6 ) {
            setActionName( "make_move" );

            addColumnStroke( mColumnChoiceForUs, 
                             mGreenWatercolorVSprites[mNextGreenVSprite] );
            mNextGreenVSprite++;
            addColumnStroke( mColumnChoiceForThem, 
                             mRedWatercolorVSprites[mNextRedVSprite] );
            mNextRedVSprite++;

            setActionParameter( "their_column", mColumnChoiceForThem );

            setActionParameter( "our_column", mColumnChoiceForUs );
            mColumnButtons[mColumnChoiceForUs]->setVisible( false );

            mColumnButtons[mColumnChoiceForThem]->setVisible( false );
            }
        else {
            setActionName( "make_reveal_move" );
            
            setActionParameter( "our_column", mRevealChoiceForUs );
            mColumnButtons[mRevealChoiceForUs]->setVisible( false );
            }
        
        
        setupRequestParameterSecurity();
        startRequest();
        }
    else if( inTarget == &mBetButton ) {
        mMoveDeadline = 0;

        mBetButton.setVisible( false );
        mFoldButton.setVisible( false );
        mLeaveButton.setVisible( true );

        clearActionParameters();
        
        setActionName( "make_bet" );
        setResponsePartNames( -1, NULL );
        
        int bet = (int)( mBetPicker.getValue() );

        setActionParameter( "bet", bet );
        
        if( getNetPotCoins( 0 ) == getNetPotCoins( 1 ) ) {
            // both players betting blind, and only lower player
            // will see opponent's higher bet (or both will see each other's
            // bets simultaneously if they bet the same amount coincidentally).
            // SO... don't fly coins here... hold back bet from pot until
            // reveal to make this clear
            }
        else {
            // we're the lower player, and if we match their bet, they 
            // will see it and be unable to raise.
            // if we raise them, they will see it.
            // SO, fly coins here to make this clear
            
            int coinValue = 1;
            if( bet >= 10 ) {
                coinValue = 10;
                }

            for( int i=0; i<bet; i += coinValue ) {
                if( bet - i < coinValue ) {
                    coinValue = 1;
                    }

                PendingFlyingCoin coin = 
                    { &( mPlayerCoinSpots[0] ),
                      &( mPotCoinSpots[0] ),
                      0,
                      coinValue };
                mFlyingCoins[0].push_back( coin );
                }
            }
        

        mBetPicker.setVisible( false );
        
        mMessageState = sendingBet;
        
        setupRequestParameterSecurity();
        startRequest();
        }
    else if( inTarget == &mFoldButton ) {
        mMoveDeadline = 0;

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
        mMoveDeadline = 0;
        
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

        if( mTheirWonSquares[0] != -1 &&
            mTheirWonSquares[1] != -1 &&
            mTheirWonSquares[2] != -1 ) {
            finalReveal = true;
            }
        
        int xPossibleWinsToClear = -1;
        
        if( mTheirWonSquares[0] != -1 && ! finalReveal ) {
            // partial reveal, we know they didn't win anything else in 
            // this column
            xPossibleWinsToClear = mTheirWonSquares[0] % 6;
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
                for( int i=0; i<6; i++ ) {
                    if( y == mTheirChoices[i] ) {
                        theirPossibleWinBlocked = true;
                        break;
                        }
                    }
                
                if( x == xPossibleWinsToClear ) {
                    theirPossibleWinBlocked = true;
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
                else if( x == mRevealChoiceForUs && winningSquare ) {
                    setDrawColor( .9, .9, 0, 1 );
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
        doublePair pos = { 319, -300 };
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
            doublePair pos = { 319, 300 };
            char *scoreString = autoSprintf( "%d", theirScore );
            
            mainFont->drawString( scoreString, pos, alignRight );
        
            delete [] scoreString;
            }
        

        if( mMoveDeadline != 0 ) {
            int secondsLeft = (int)( mMoveDeadline - game_time( NULL ) );
            
            if( secondsLeft < 11 ) {
                doublePair pos = { 0, 300 };
                
                
                if( secondsLeft > 5 ) {
                    if( mMoveDeadlineFade < 1 ) {
                        mMoveDeadlineFade += 
                            .05 * frameRateFactor;
                        if( mMoveDeadlineFade > 1 ) {
                            mMoveDeadlineFade = 1;
                            }
                        }
                    }
                else {
                    mMoveDeadlineFade += 
                        mMoveDeadlineFadeDelta * .1 * frameRateFactor;
                    
                    if( mMoveDeadlineFade > 1 ) {
                        mMoveDeadlineFade = 1;
                        mMoveDeadlineFadeDelta *= -1;
                        }
                    else if( mMoveDeadlineFade < 0 ) {
                        mMoveDeadlineFade = 0;
                        mMoveDeadlineFadeDelta *= -1;
                        }
                    }

                setDrawColor( 1, 0, 0, mMoveDeadlineFade );
                
                char *timeString = autoSprintf( "%d", secondsLeft );
            
                mainFont->drawString( timeString, pos, alignCenter );
        
                delete [] timeString;
                }
            else {
                mMoveDeadlineFade = 0;
                mMoveDeadlineFadeDelta = 1;
                }
                
            }
        }
    
    
    if( mPlayerCoins[0] != -1 ) {
        // draw coins
        pos = mPlayerCoinSpots[0].position;
        
        setDrawColor( 1, 1, 1, 1 );
        drawSprite( mCoinSprite, pos );
        
        pos.x -= 20;
        pos.y -= 3;
        
        char *number = autoSprintf( "%d", mPlayerCoins[0] );

        setUsColor();
        
        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;


        if( mPotCoins[0] > 0 ) {
            
            pos = mPotCoinSpots[0].position;
            
            setDrawColor( 1, 1, 1, 1 );
            drawSprite( mCoinSprite, pos );
            
            pos.x -= 20;
            pos.y -= 3;

            setUsColor();
        
            number = autoSprintf( "%d", mPotCoins[0] );
            
            mainFont->drawString( number, 
                                  pos, alignRight );
        
            delete [] number;
            }
        

        if( mRunning ) {

            pos = mPlayerCoinSpots[1].position;
            
            setDrawColor( 1, 1, 1, 1 );
            drawSprite( mCoinSprite, pos );
            
            pos.x -= 20;
            pos.y -= 3;
            
            number = autoSprintf( "%d", mPlayerCoins[1] );
            
            setThemColor();
            
            mainFont->drawString( number, 
                                  pos, alignRight );
            delete [] number;
            }
        else {
            pos = mPlayerCoinSpots[1].position;
            pos.x -= 20;
            pos.y -= 3;
            
            mainFont->drawString( translate( "gone" ), 
                                  pos, alignCenter );
            }



        if( mPotCoins[1] > 0 ) {
            pos = mPotCoinSpots[1].position;

            setDrawColor( 1, 1, 1, 1 );
            drawSprite( mCoinSprite, pos );
            
            pos.x -= 20;
            pos.y -= 3;
            
            setThemColor();

            number = autoSprintf( "%d", mPotCoins[1] );
        
            mainFont->drawString( number, 
                                  pos, alignRight );
            delete [] number;
            }

        }


    for( int f=0; f<2; f++ ) {
        if( mFlyingCoins[f].size() > 0 ) {
            

            for( int c=0; c < mFlyingCoins[f].size(); c++ ) {
                
                PendingFlyingCoin *coin = mFlyingCoins[f].getElement( c );
                
                if( coin->progress > 0 && coin->start != NULL ) {
                    
                    float easedProgress = 
                        sin( coin->progress * M_PI / 2 );
                    
                    doublePair pos = 
                        add( mult( coin->dest->position, 
                                   easedProgress ),
                             mult( coin->start->position, 
                                   1 - easedProgress ) );
            
                    setDrawColor( 1, 1, 1, 1 );
                    
                    if( coin->value == 1 ) {
                        drawSprite( mCoinSprite, pos );
                        }
                    else {
                        drawSprite( mCoinTenSprite, pos );
                        }
                    }
                }
            }
        }
    
    


    if( mShowWatercolorDemo && mGameBoard != NULL ) {
        
        doublePair parchPos = { 0, 0 };
    
        setDrawColor( 1, 1, 1, 1 );

        drawSprite( mParchmentSprite, parchPos );
    
        toggleMultiplicativeBlend( true );

        toggleAdditiveTextureColoring( true );

        float inkValue = (lastMousePos.x + 333 )/ 666;
        
        inkValue = 0;

        setDrawColor( inkValue, inkValue, inkValue, 1 );

        drawSprite( mInkGridSprite, mInkGridCenter );    

        for( int i=0; i<36; i++ ) {
            doublePair numberPos;
            numberPos.x = mColumnPositions[ i % 6 ].x;
            numberPos.y = mRowPositions[ i / 6 ].y;

            drawSprite( mInkNumberSprites[ mGameBoard[i] -  1 ], numberPos );  
            }


        setDrawColor( 1, 1, 1, 1 );

        for( int i=0; i<mWatercolorStrokes.size(); i++ ) {
            // draw strokes until we find one that is still
            // fading in, then stop after that one (only fade in one
            // at a time
            
            WatercolorStroke *stroke = mWatercolorStrokes.getElement( i );
            
            if( stroke->leftEnd == 0 && stroke->rightEnd == 0 ) {
                setDrawColor( 0, 0, 0, 1 );
                
                drawSprite( stroke->sprite, stroke->pos );
                }
            else {
                
                FloatColor spriteColors[4];
    
                for( int i=0; i<4; i++ ) {
                    float value = stroke->leftEnd;
                    
                    if( ! stroke->vertical && ( i == 1 || i == 2 ) ) {
                        value = stroke->rightEnd;
                        }
                    else if( stroke->vertical && ( i == 0 || i == 1 ) ) {
                        value = stroke->rightEnd;
                        }
                    spriteColors[i].r = value;
                    spriteColors[i].g = value;
                    spriteColors[i].b = value;
                    spriteColors[i].a = 1;
                    }
            
                drawSprite( stroke->sprite, stroke->pos, spriteColors );
                }
            

            if( stroke->leftEnd > 0 ) {
                stroke->leftEnd -= 0.02 * frameRateFactor;
                if( stroke->leftEnd < 0 ) {
                    stroke->leftEnd = 0;
                    }
                // don't draw any further strokes, we're not close to done 
                // with the start end of this one yet
                if( stroke->leftEnd > 0.75 ) {
                    break;
                    }
                }
            if( stroke->rightEnd > 0 ) {
                stroke->rightEnd -= 0.02 * frameRateFactor;
                if( stroke->rightEnd < 0 ) {
                    stroke->rightEnd = 0;
                    }
                
                // go on to start end of next stroke in parallel
                // with end-end of this stroke
                }
            }
        
        /*
        if( mColumnChoiceForUs != -1 ) {
            doublePair strokePos;
            
            strokePos = mColumnPositions[ mColumnChoiceForUs ];
            
            drawSprite( mGreenWatercolorVSprites[0], strokePos );
            }
        */
        

        /*
        toggleAdditiveTextureColoring( false );

        setDrawColor( 1, 1, 1, 1 );

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
        */

        toggleAdditiveTextureColoring( false );
        toggleMultiplicativeBlend( false );

        
        if( mParchmentFade > 0 ) {
            setDrawColor( 0, 0, 0, mParchmentFade );
            drawSquare( parchPos, 256 );
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

    if( mMoveDeadline != 0 ) {
        int secondsLeft = (int)( mMoveDeadline - game_time( NULL ) );

        if( secondsLeft < 0 ) {
            // past deadline, force them to leave game
            actionPerformed( &mLeaveButton );
            }
        }
    

    if( mRoundEnding && mRoundEndTime < game_time( NULL ) ) {
        clearActionParameters();
        
        setActionName( "end_round" );
        setResponsePartNames( -1, NULL );
        
        mMessageState = sendingEnd;
        
        setupRequestParameterSecurity();
        startRequest();
        mRoundEnding = false;
        }

    char anyStrokesStillAdding = false;
    if( mWatercolorStrokes.size() > 0 ) {
        
        WatercolorStroke *lastStroke = 
            mWatercolorStrokes.getElement( mWatercolorStrokes.size() - 1 );
        
        if( lastStroke->rightEnd > 0 ) {
            anyStrokesStillAdding = true;
            }
        }
    
    if( !mShowWatercolorDemo ) {
        anyStrokesStillAdding = false;
        }
    

    if( mRoundStarting && mRoundStartTime < game_time( NULL ) && 
        // wait for coins to finish flying before starting next round
        mFlyingCoins[0].size() == 0 &&
        mFlyingCoins[1].size() == 0 && 
        // wait for final strokes, too
        ! anyStrokesStillAdding ) {

        mParchmentFadingOut = true;
        
        if( mParchmentFade < 1 ) {
            // fade out before starting next round
            
            mParchmentFade += 0.02 * frameRateFactor;
            if( mParchmentFade > 1 ) {
                mParchmentFade = 1;
                }
            }
        else {
            mWatercolorStrokes.deleteAll();
            mNextGreenVSprite = 0;
            mNextGreenHSprite = 0;
            mNextRedVSprite = 0;

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


            clearActionParameters();
            
            setActionName( "start_next_round" );
            setResponsePartNames( -1, NULL );
            
            mMessageState = sendingStartNext;
            
            setupRequestParameterSecurity();
            startRequest();
            mRoundStarting = false;
            }
        }

    if( ! mParchmentFadingOut && mParchmentFade > 0 ) {
        mParchmentFade -= 0.02 * frameRateFactor;
        if( mParchmentFade < 0 ) {
            mParchmentFade = 0;
            }
        }


    for( int f=0; f<2; f++ ) {
        if( mFlyingCoins[f].size() > 0 ) {
            
            for( int c=0; c < mFlyingCoins[f].size(); c++ ) {
                PendingFlyingCoin *coin = mFlyingCoins[f].getElement( c );
        
                // first coin on list
                if( c == 0 ||
                    // or previous coin has gone far enough that 
                    // next coin can start moving too
                    // make sure previous coin is not a pause marker
                    ( mFlyingCoins[f].getElement( c - 1 )->start != NULL 
                      &&
                      // not moving out of previous coin's destination
                      // (coins cross paths and look confusing
                      mFlyingCoins[f].getElement( c - 1 )->dest !=
                      mFlyingCoins[f].getElement( c )->start
                      &&
                      mFlyingCoins[f].getElement( c - 1 )->progress > .25 ) ) {
                    

                    if( coin->progress == 0 && coin->start != NULL ) {
                        *( coin->start->coinCount ) -= coin->value;
                        }
            
                    if( coin->start != NULL && coin->dest != NULL ) {
                        
                        double dist = distance( coin->dest->position,
                                                coin->start->position );
                    
                        // constant speed, regardless of how far we are moving
                        coin->progress += frameRateFactor * 5.0 / dist;
                        }
                    else {
                        // no start or dest, this is a pause that runs
                        // over a fixed time interval, 3 seconds
                        coin->progress += frameRateFactor / 180.0;
                        }
                    }
                }
            
            // now delete any that are done
            for( int c=0; c < mFlyingCoins[f].size(); c++ ) {
                PendingFlyingCoin *coin = mFlyingCoins[f].getElement( c );
                if( coin->progress >= 1 ) {
                
                    if( coin->dest != NULL && 
                        coin->dest->coinCount != NULL ) {
                        
                        *( coin->dest->coinCount ) += coin->value;
                        }
                
                    mFlyingCoins[f].deleteElement( c );
                    }
                }
            }
        }
    
    
    if( mCommitButton.isVisible() ) {

        if( mCommitButton.isMouseOver() ) {
            mCommitFlashPreSteps = 0;
            mCommitFlashProgress = 1;
            mCommitFlashDirection = -1;
            
            mCommitButton.setNoHoverColor( 1, 1, 1, mCommitFlashProgress );
            }
        else {
            
            mCommitFlashPreSteps ++;
            
            if( mCommitFlashPreSteps > 120 / frameRateFactor ) {
                

                mCommitButton.setNoHoverColor( 1, 1, 1, mCommitFlashProgress );

                mCommitFlashProgress += 
                    mCommitFlashDirection * frameRateFactor * 0.03;
        
                if( mCommitFlashProgress > 1 ) {
                    mCommitFlashProgress = 1;
                    mCommitFlashDirection *= -1;
                    }
                else if( mCommitFlashProgress < 0.25 ) {
                    mCommitFlashProgress = 0.25;
                    mCommitFlashDirection *= -1;
                    }
                }
            }
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
        mMoveDeadline = 0;
        
        startRequest();
        }
    else if( mMessageState == sendingBet ) {
        
        // bet sent
        
        // wait for opponent's bet

        clearActionParameters();
        
        setActionName( "wait_move" );
        setResponsePartNames( 1, waitMovePartNames );

        mMessageState = waitingBet;
        mMoveDeadline = 0;

        startRequest();
        }
    else if( mMessageState == sendingFold ) {
        
        // fold sent
        
        // get end game state
        setActionName( "get_game_state" );
        setResponsePartNames( 9, gameStatePartNames );
        
        clearActionParameters();
        mMessageState = gettingStateAtEnd;
        
        startRequest();
        }
    else if( mMessageState == sendingEnd ) {
        
        // end sent
        
        // wait for opponent's end

        clearActionParameters();
        
        setActionName( "wait_move" );
        setResponsePartNames( 1, waitMovePartNames );

        mMessageState = waitingEnd;
        mMoveDeadline = 0;

        startRequest();
        }
    else if( mMessageState == sendingStartNext ) {
        
        // start next sent
        
        // wait for opponent's start next

        clearActionParameters();
        
        setActionName( "wait_move" );
        setResponsePartNames( 1, waitMovePartNames );

        mMessageState = waitingStartNext;
        mMoveDeadline = 0;

        startRequest();
        }
    else if( mMessageState == gettingState ||
             mMessageState == gettingStatePostMove ||
             mMessageState == gettingStatePostBet ||
             mMessageState == gettingStateAtEnd ) {
        
        mRunning = getResponseInt( "running" );
        

        int coins[2];
        int pots[2];
        
        coins[0] = getResponseInt( "ourCoins" );
        coins[1] = getResponseInt( "theirCoins" );

        pots[0] = getResponseInt( "ourPotCoins" );
        pots[1] = getResponseInt( "theirPotCoins" );

        
        if( coins[0] > 0 && coins[1] > 0 && 
            pots[0] == 0 && pots[1] == 0 ) {
            
            // the post-reveal coin distribution has happened
            // and there are still coins left for another round
            mRoundStarting = true;
            mRoundStartTime = game_time( NULL ) + 5;
            }


        if( mPlayerCoins[0] == -1 ) {
            // no info about coins until now, start of game
        
            // show first coins flying into pot
            
            for( int p=0; p<2; p++ ) {
                
                mPlayerCoins[p] = coins[p] + pots[p];
                mPotCoins[p] = 0;
                        
                
                int coinValue = 1;
                if( pots[p] >= 10 ) {
                    coinValue = 10;
                    }
                
                for( int i=0; i<pots[p]; i += coinValue ) {
                    if( pots[p] - i < coinValue ) {
                        coinValue = 1;
                        }

                    PendingFlyingCoin coin = 
                        { &( mPlayerCoinSpots[p] ),
                          &( mPotCoinSpots[p] ),
                          0,
                          coinValue };
                    mFlyingCoins[p].push_back( coin );
                    }
                }            
            }
        else {
            // not start of game, know player balances
            
            printf( "Before moving bets, our net coins = %d, "
                    "our net pot coins = %d\n",
                    getNetPlayerCoins(0), getNetPotCoins( 0 ) );
            
            for( int p=0; p<2; p++ ) {

                // coins moving into pots
                if( coins[p] < getNetPlayerCoins(p) &&
                    pots[p] > getNetPotCoins(p) ) {

                    int diff = getNetPlayerCoins(p) -  coins[p];
                
                    int coinValue = 1;
                    if( diff >= 10 ) {
                        coinValue = 10;
                        }
                    
                    for( int i=0; i<diff; i += coinValue ) {
                        if( diff - i < coinValue ) {
                            coinValue = 1;
                            }
                        PendingFlyingCoin coin = 
                            { &( mPlayerCoinSpots[p] ),
                              &( mPotCoinSpots[p] ),
                              0,
                              coinValue };
                        mFlyingCoins[p].push_back( coin );
                        }
                    }
                }

            
            
            if( !mRunning || 
                getNetPlayerCoins(0) < coins[0] ||
                getNetPlayerCoins(1) < coins[1] ) {
                
                // end of round, one player won coins
                
                
                char tie = false;
                
                int winner = 0;
                if( getNetPlayerCoins(1) < coins[1] ) {
                    winner = 1;

                    if( getNetPlayerCoins(0) < coins[0] ) {
                        tie = true;
                        }
                    }
                
                if( !tie &&
                    getNetPlayerCoins( 0 ) == coins[0] &&
                    coins[1] == 0 ) {
                    // opponent left, and we didn't win
                    winner = 1;
                    }
                
                int loser = ( winner + 1 ) % 2;

                
                int rawTableTotal = 
                    getNetPlayerCoins( winner ) + getNetPlayerCoins( loser ) +
                    getNetPotCoins( 0 )    + getNetPotCoins( 1 );
                
                int reportedTableTotal = coins[winner] + coins[loser]
                    + pots[winner] + pots[loser];
                

                int houseRake = rawTableTotal - reportedTableTotal;

                
                if( !mRunning ) {
                    // other player left, don't have enough information
                    // to compute the rake
                    houseRake = 0;
                    }


                // this is known
                int winnerPotToAward = getNetPotCoins(winner);
                
                
                // don't know loser pot, because they may have folded
                // a as-of-yet-unseen bet
                int loserPotContribution = coins[winner] + pots[winner]
                    - getNetPlayerCoins( winner ) - winnerPotToAward;
                
                
                if( tie ) {
                    loserPotContribution = getNetPotCoins( loser );
                    }


                if( !mRunning && coins[winner] == 0 ) {
                    // winner left
                    loserPotContribution = getNetPotCoins( loser );

                    // estimate rake at 10%, because we don't have
                    // information about true rake

                    int totalPot = loserPotContribution + winnerPotToAward;
                    
                    houseRake = totalPot / 10;
                    
                    loserPotContribution -= houseRake;
                    }
                

                // add these coins to the end of the fullest queue
                // thus, these coins will wait until pending flights are
                // finished before starting, and not run in parallel
                // (parallel used for [bets --> pot] only)
                SimpleVector<PendingFlyingCoin> *flyingQueue;
                
                if( mFlyingCoins[0].size() > mFlyingCoins[1].size() ) {
                    flyingQueue = &( mFlyingCoins[0] );
                    }
                else {
                    flyingQueue = &( mFlyingCoins[1] );
                    }
                
                if( loserPotContribution + houseRake > 
                    getNetPotCoins( loser ) ) {
                    // loser is opponent, and they folded after making
                    // an insufficient bet that we haven't seen yet
                    
                    // show their bet flying now
                    int diff =  loserPotContribution + houseRake 
                        - getNetPotCoins( loser );
                    
                    int coinValue = 1;
                    if( diff >= 10 ) {
                        coinValue = 10;
                        }

                    for( int i=0; i<diff; i += coinValue ) {    
                        if( diff - i < coinValue ) {
                            coinValue = 1;
                            }
                        
                        PendingFlyingCoin coin = 
                            { &( mPlayerCoinSpots[loser] ),
                              &( mPotCoinSpots[loser] ),
                              0,
                              coinValue };
                        flyingQueue->push_back( coin );
                        }
                    
                    // insert a coin flight pause after this, so that loser's
                    // insufficient pot is shown on the screen for a bit
                    // before the coin distribution is shown
                    PendingFlyingCoin coin = { NULL, NULL, 0, 1 };
                    flyingQueue->push_back( coin );
                    }
                else if( loserPotContribution + houseRake
                         < getNetPotCoins( loser ) ) {
                    // coins hanging in loser's pot that are unaccounted
                    // for.  Winner definitely didn't get them.
                    
                    // maybe they left the game, and the rake took these
                    // extra coins
                    houseRake += 
                        getNetPotCoins( loser ) - loserPotContribution;
                    }


                CoinSpot *winnerCoinSpot = &( mPlayerCoinSpots[winner] );
                
                if( !mRunning && coins[winner] == 0 ) {
                    // winner left
                    
                    // show their won coins flying off top of screen with them
                    winnerCoinSpot = &( mOpponentGoneCoinSpot );
                    }

                
                int coinValue = 1;
                if( winnerPotToAward >= 10 ) {
                    coinValue = 10;
                    }

                for( int i=0; i<winnerPotToAward; i += coinValue ) {
                    if( winnerPotToAward - i < coinValue ) {
                        coinValue = 1;
                        }
                    
                    PendingFlyingCoin coin = 
                        { &( mPotCoinSpots[winner] ),
                          winnerCoinSpot,
                          0,
                          coinValue };
                    flyingQueue->push_back( coin );
                    }
                

                if( tie ) {
                    // swap winner and loser before distributing
                    // loser's coins
                    winner = loser;
                    }

                coinValue = 1;
                if( loserPotContribution >= 10 ) {
                    coinValue = 10;
                    }

                for( int i=0; i<loserPotContribution; i += coinValue ) {    
                    if( loserPotContribution - i < coinValue ) {
                        coinValue = 1;
                        }
                    
                    PendingFlyingCoin coin = 
                        { &( mPotCoinSpots[loser] ),
                          winnerCoinSpot,
                          0,
                          coinValue };
                    flyingQueue->push_back( coin );
                    }

                
                coinValue = 1;
                if( houseRake >= 10 ) {
                    coinValue = 10;
                    }

                for( int i=0; i<houseRake; i += coinValue ) {
                    if( houseRake - i < coinValue ) {
                        coinValue = 1;
                        }
                    
                    PendingFlyingCoin coin = 
                        { &( mPotCoinSpots[loser] ),
                          &( mHouseCoinSpot ),
                          0,
                          coinValue };
                    flyingQueue->push_back( coin );
                    }
                
                }
            }
        
                
        
            
        

        int secondsLeft = getResponseInt( "secondsLeft" );
        
        if( mMessageState != gettingStateAtEnd &&
            mRunning && secondsLeft >= 0 ) {
            mMoveDeadline = game_time( NULL ) + secondsLeft;
            }
        else {
            // no deadline
            mMoveDeadline = 0;
            }

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
            
            mParchmentFadingOut = false;
            }
        
        for( int i=0; i<numParts; i++ ) {
            delete [] parts[i];
            }
        delete [] parts;

        
        mRevealChoiceForUs = -1;


        char *ourMoves = getResponse( "ourMoves" );
        char *theirMoves = getResponse( "theirMoves" );
        
        int theirOldChoices[6];
        memcpy( theirOldChoices, mTheirChoices, 6 * sizeof( int ) );

        int ourOldWonSquares[3];
        memcpy( ourOldWonSquares, mOurWonSquares, 3 * sizeof( int ) );
        
        int theirOldWonSquares[3];
        memcpy( theirOldWonSquares, mTheirWonSquares, 3 * sizeof( int ) );
        
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

                int movesToParse = numOurParts;
                // last move is reveal move, but ignore it
                if( movesToParse > 6 ) {
                    movesToParse = 6;
                    }
                
                if( numOurParts > 6 ) {
                    // our reveal already picked
                    mRevealChoiceForUs = stringToInt( ourParts[6] );
                    }

                for( int i=0; i<movesToParse; i++ ) {
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
                    
                    if( theirChoiceMapping[i] < 3 &&
                        theirOldChoices[ theirChoiceMapping[i] ] !=
                        mTheirChoices[ theirChoiceMapping[i] ] ) {
                        
                        // a new choice
                        addRowStroke( 
                            mTheirChoices[ theirChoiceMapping[i] ],
                            mGreenWatercolorHSprites[mNextGreenHSprite] );
                        mNextGreenHSprite++;
                        }
                    }
                
                
                int ourWonSquareCount = 0;
                int theirWonSquareCount = 0;
                
                for( int i=0; i<movesToParse; i += 2 ) {
                    
                    int ourSelfChoice = stringToInt( ourParts[i] );
                    int ourOtherChoice = stringToInt( ourParts[i+1] );
                    
                    int theirSelfChoice = stringToInt( theirParts[i] );
                    int theirOtherChoice = stringToInt( theirParts[i+1] );

                    if( ourSelfChoice != -1 &&
                        theirOtherChoice != -1 ) {
                        
                        int ourWonIndex = theirOtherChoice * 6 + ourSelfChoice;
                        
                        mOurWonSquares[ ourWonSquareCount ] =
                            ourWonIndex;
                        
                        if( mOurWonSquares[ ourWonSquareCount ] !=
                            ourOldWonSquares[ ourWonSquareCount ] ) {
                            
                            // a new won square
                            int r = mOurWonSquares[ ourWonSquareCount ] / 6;
                            int c = mOurWonSquares[ ourWonSquareCount ] % 6;
                            
                            addColumnStroke( c,
                                             mBlackWatercolorVSprites[r] );
                            addRowStroke( r,
                                          mBlackWatercolorHSprites[c] );
                            }

                        ourWonSquareCount ++;
                        }
                    
                    if( theirSelfChoice != -1 &&
                        ourOtherChoice != -1 ) {
                        
                        int theirWonIndex = 
                            theirSelfChoice * 6 + ourOtherChoice;
                        
                        mTheirWonSquares[ theirWonSquareCount ] =
                            theirWonIndex;
                        
                        if( mTheirWonSquares[ theirWonSquareCount ] !=
                            theirOldWonSquares[ theirWonSquareCount ] ) {
                            
                            // a new won square
                            int r = 
                                mTheirWonSquares[ theirWonSquareCount ] / 6;
                            int c = 
                                mTheirWonSquares[ theirWonSquareCount ] % 6;
                            
                            addColumnStroke( c,
                                             mBlackWatercolorVSprites[r] );
                            addRowStroke( r,
                                          mBlackWatercolorHSprites[c] );
                            }

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
            getNetPotCoins(0) == 0 ||
            getNetPotCoins(1) == 0 ) {
            
            // one player left or either down to 0

            // game over

            mLeaveButton.setVisible( true );
            }
        else if( mMessageState == gettingState 
            ||
            ( mMessageState == gettingStatePostBet &&
              getNetPotCoins(0) == getNetPotCoins(1) ) ) {
            
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


            if( mTheirWonSquares[0] != -1 &&
                mTheirWonSquares[1] != -1 &&
                mTheirWonSquares[2] != -1 ) {
                // reveal has happened
                mRoundEnding = true;
                mRoundEndTime = game_time( NULL ) + 5;
                }
            else if( numUsedColumns == 6 ) {
                // final move, reveal one of our columns
                mColumnButtons[mOurChoices[0]]->setVisible( true );
                mColumnButtons[mOurChoices[2]]->setVisible( true );
                mColumnButtons[mOurChoices[4]]->setVisible( true );
                
                mColumnButtons[mOurChoices[0]]->setLabelText( "R" );
                mColumnButtons[mOurChoices[2]]->setLabelText( "R" );
                mColumnButtons[mOurChoices[4]]->setLabelText( "R" );
                }
            }
        else {
            // betting time
            mBetPicker.setVisible( true );

            // we can't bet more than our opponent can afford
            int max = getNetPlayerCoins(1) + getNetPotCoins(1) - 
                getNetPotCoins(0);

            // we can't bet more than we can afford
            if( getNetPlayerCoins(0) < max ) {
                max = getNetPlayerCoins(0);
                }

            mBetPicker.setMax( max );
            
            int minBet = getNetPotCoins(1) - getNetPotCoins(0);
            
            if( minBet < 0 ) {
                minBet = 0;
                }
            if( minBet > getNetPlayerCoins(0) ) {
                minBet = getNetPlayerCoins(0);
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
             mMessageState == waitingEnd ||
             mMessageState == waitingStartNext ) {
        char *status = getResponse( "status" );

        if( strcmp( status, "move_ready" ) == 0 ) {
            
            // get the new game state
            setActionName( "get_game_state" );
            setResponsePartNames( 9, gameStatePartNames );

            clearActionParameters();
    
            if( mMessageState == waitingMove ) {
                mMessageState = gettingStatePostMove;
                }
            else if( mMessageState == waitingBet ) {
                mMessageState = gettingStatePostBet;
                }
            else if( mMessageState == waitingEnd ) {
                mMessageState = gettingStateAtEnd;
                }
            else {
                mMessageState = gettingState;
                }
            
            startRequest();
            }
        else if( strcmp( status, "round_ended" ) == 0 ) {
            // start of new round
            setActionName( "get_game_state" );
            setResponsePartNames( 9, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingStateAtEnd;
            
            startRequest();
            }
        else if( strcmp( status, "next_round_started" ) == 0 ) {
            // start of new round
            setActionName( "get_game_state" );
            setResponsePartNames( 9, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingState;
            
            startRequest();
            }
        else if( strcmp( status, "opponent_left" ) == 0 ) {
            // get final state
            setActionName( "get_game_state" );
            setResponsePartNames( 9, gameStatePartNames );

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
    

    int theirChoicesBackup[6];
    memcpy( theirChoicesBackup, mTheirChoices, 6 * sizeof( int ) );

    
    // unless reveal has happened
    if( mTheirWonSquares[0] != -1 &&
        mTheirWonSquares[1] != -1 &&
        mTheirWonSquares[2] != -1 ) {
        
        int j = 3;
        for( int i=0; i<6; i+=2 ) {
            ourChoicesTheirPerspective[j] = ourUncommittedChoices[i];
            j++;
            }
        }
    // or we have an uncommitted reveal waiting
    else if( mRevealChoiceForUs != -1 ) {
        int j = 3;
        for( int i=0; i<6; i+=2 ) {
            if( mRevealChoiceForUs == ourUncommittedChoices[i] ) {    
                ourChoicesTheirPerspective[j] = ourUncommittedChoices[i];

                
                // so, in this case, because we fill in -1 in order
                // (with fillInExtraChoices)
                // to determine which part of the permutation array is
                // fixed (the first part) and which part should be permuted
                // (the second part), we can't support injecting a fixed
                // move into the middle of the permutable part

                // thus, we need to swap it in our array into the first
                // spot AND swap their choice in their array
                
                if( j != 3 ) {
                    // the revealed move we're sticking in is not coming
                    // right at the end of our known moves
                    // (not   them, them, them, us, -1, -1 )
                    // (maybe them, them, them, -1, us, -1 )

                    ourChoicesTheirPerspective[3] = 
                        ourChoicesTheirPerspective[j];
                    ourChoicesTheirPerspective[j] = -1;
                    
                    char temp = mTheirChoices[0];
                    
                    // we saved a backup above to restore this later
                    mTheirChoices[0] = mTheirChoices[j-3];
                    mTheirChoices[j-3] = temp;
                    }
                }
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

    memcpy( mTheirChoices, theirChoicesBackup, 6 * sizeof( int ) );
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
    mCacheRecords[r].revealChoiceForUs = mRevealChoiceForUs;
    

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
            mCacheRecords[r].columnChoiceForThem != mColumnChoiceForThem
            ||
            mCacheRecords[r].revealChoiceForUs != mRevealChoiceForUs ) {
            
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




int PlayGamePage::getNetPlayerCoins( int inPlayerNumber ) {
    int count = mPlayerCoins[inPlayerNumber];
    
    for( int p=0; p<2; p++ ) {
        for( int f=0; f<mFlyingCoins[p].size(); f++ ) {
            PendingFlyingCoin *coin = mFlyingCoins[p].getElement( f );
            
            if( coin->dest == &( mPlayerCoinSpots[inPlayerNumber] ) ) {
                count += coin->value;
                }
            if( coin->progress == 0 &&
                coin->start == &( mPlayerCoinSpots[inPlayerNumber] ) ) {

                count -= coin->value;
                }
            }
        }
    return count;
    }

    

int PlayGamePage::getNetPotCoins( int inPlayerNumber ) {
    int count = mPotCoins[inPlayerNumber];
    
    for( int p=0; p<2; p++ ) {
        for( int f=0; f<mFlyingCoins[p].size(); f++ ) {
            PendingFlyingCoin *coin = mFlyingCoins[p].getElement( f );
            
            if( coin->dest == &( mPotCoinSpots[inPlayerNumber] ) ) {
                count += coin->value;
                }
            if( coin->progress == 0 &&
                coin->start == &( mPotCoinSpots[inPlayerNumber] ) ) {
                
                count -= coin->value;
                }
            }
        }
    return count;
    }




void PlayGamePage::addColumnStroke( int inColumn, SpriteHandle inSprite[6] ) {
    
    doublePair subPos = mColumnPositions[inColumn];
    subPos.y += 64 + 64 + 32;
    
    for( int i=0; i<6; i++ ) {
        
        WatercolorStroke stroke = { subPos,
                                    inSprite[i], true, 1, 1 };
    
        mWatercolorStrokes.push_back( stroke );
        
        subPos.y -= 64;
        }
    }

    

void PlayGamePage::addRowStroke( int inRow, SpriteHandle inSprite[6] ) {
    doublePair subPos = mRowPositions[inRow];
    subPos.x -= 64 + 64 + 32;

    for( int i=0; i<6; i++ ) {
        WatercolorStroke stroke = { subPos,
                                    inSprite[i], false, 1, 1 };
        
        mWatercolorStrokes.push_back( stroke );
        
        subPos.x += 64;
        }
    }










