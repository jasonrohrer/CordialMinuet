#include "PlayGamePage.h"

#include "buttonStyle.h"
#include "message.h"
#include "whiteSprites.h"

#include "serialWebRequests.h"

#include "chime.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"
#include "minorGems/game/drawUtils.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"



extern Font *mainFont;
extern Font *numbersFontFixed;
extern double frameRateFactor;


extern char waitingAmuletGame;



const int numGameStateParts = 11;
const char *gameStatePartNames[11] = { "running", "boardLayout",
                                      "gameType", 
                                      "ourCoins", "theirCoins", 
                                      "ourPotCoins", "theirPotCoins",
                                      "ourMoves", "theirMoves",
                                      "secondsLeft", "leavePenalty" };

const char *waitMovePartNames[1] = { "status" };


static int cellSize = 70;
static int borderWidth = 2;
    
static double cellCenterStart = ( ( cellSize + borderWidth ) * 5 ) / 2;

static int cellXOffset = cellSize + borderWidth;


static int nextCoinID = 0;


static Color usColor( .51, 0.682, .122, 1 );
static Color themColor( .85, .149, .216, 1 );

static float ourHueShift = 0;
static float theirHueShift = 0;


static void setUsColor() {
    setDrawColor( usColor.r, usColor.g, usColor.b, 1 );
    }

static void setThemColor() {
    setDrawColor( themColor.r, themColor.g, themColor.b, 1 );
    }


static int columnPickerStartingOffset = 31;


static doublePair lastMousePos = { 0, 0 };



static void shiftColorHue( Color *inColor, float inHueShift ) {
    
    float h, s, v;
    
    inColor->makeHSV( &h, &s, &v );

    h += inHueShift;
    
    if( h > 1 ) {
        h -= 1;
        }
    
    Color *newColor = Color::makeColorFromHSV( h, s, v );
    
    inColor->setValues( newColor );
    
    delete newColor;
    }



static void shiftImageHue( Image *inImage, float inHueShift ) {
    int h = inImage->getHeight();
    int w = inImage->getWidth();
    
    int numPixels = h * w;
    
    for( int i=0; i<numPixels; i++ ) {
        
        Color c = inImage->getColor( i );
        
        shiftColorHue( &c, inHueShift );
        
        inImage->setColor( i, c );
        }
    }




static float greenStrokeFade;
static float redStrokeFade;
static float blackStrokeFade;




// assumes strokes are 64 wide
// outArray must have room for however many strokes are in image
// and each stroke divided up into 6 sub-strokes
static void readWatercolorImages( const char *inTGAFileName,
                                  char inVertical,
                                  SpriteHandle outSpriteArray[][6],
                                  float inHueShift = 0 ) {
    
    Image *watercolorImage = readTGAFile( inTGAFileName );

    if( inHueShift != 0 ) {
        shiftImageHue( watercolorImage, inHueShift );
        }
    
    
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



// outSpriteArray must have enough room for rows * columns sprites
static void readCharacterGrid( const char *inTGAFileName,
                               int inNumRows, int inNumColumns,
                               SpriteHandle outSpriteArray[],
                               char inWhiteSprites = false ) {
    
    Image *numbersImage = readTGAFile( inTGAFileName );
    
    if( numbersImage != NULL ) {
        
        int width = numbersImage->getWidth();
        
        int height = numbersImage->getHeight();

        int numberWidth = width / inNumColumns;
        int numberHeight = height / inNumRows;
        

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
        
        int numChars = inNumRows * inNumColumns;

        for( int i=0; i<numChars; i++ ) {
            
            int x = i % inNumColumns;
            int y = i / inNumColumns;
            

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
            
            if( inWhiteSprites ) {
                outSpriteArray[i] = fillWhiteSprite( &paddedImage );
                }
            else {
                outSpriteArray[i] = fillSprite( &paddedImage, false );
                }
            delete oneNumberImage;
            }
        
        delete numbersImage;
        }

    }





PlayGamePage::PlayGamePage()
        : ServerActionPage( "get_game_state", numGameStateParts, 
                            gameStatePartNames ),
          mGameBoard( NULL ),
          mGameType( 0 ),
          mCoinSprite( loadWhiteSprite( "coin.tga" ) ),
          mCoinTenSprite( loadWhiteSprite( "coinTen.tga" ) ),
          mMoveDeadline( 0 ),
          mMoveDeadlineFade( 0 ),
          mMoveDeadlineFadeDelta( 1 ),
          mCommitButton( mainFont, -128, 288, translate( "commit" ) ),
          mCommitButtonJustPressed( false ),
          mBetButton( mainFont, 0, -288, translate( "bet" ) ),
          mFoldButton( mainFont, 120, -288, translate( "fold" ) ),
          mLeaveButton( mainFont, 128, 288, translate( "leave" ) ),
          mLeaveConfirmButton( mainFont, 
                               -128, 288, translate( "leaveConfirm" ) ),
          mBetPicker( mainFont, -64, -288, 3, 0, "" ),
          mCommitFlashPreSteps( 0 ),
          mCommitFlashProgress( 1.0 ),
          mCommitFlashDirection( -1 ),
          mColumnChoiceForUs( -1 ), mColumnChoiceForThem( -1 ),
          mRevealChoiceForUs( -1 ),
          mMouseOverTheirTurnNumber( -1 ),
          mMouseOverTheirColumn( -1 ),
          mMouseOverTheirRow( -1 ),
          mMouseOverOurColumn( -1 ),
          mMouseOverOurRow( -1 ),
          mMouseOverSquareLocked( false ),
          mScorePipSprite( loadWhiteSprite( "scorePip.tga" ) ),
          mScorePipExtraSprite( loadWhiteSprite( "scorePipExtra.tga" ) ),
          mScorePipEmptySprite( loadWhiteSprite( "scorePipEmpty.tga" ) ),
          mShowWatercolorDemo( true ),
          mParchmentSprite( loadSprite( "parchment.tga", true ) ),
          mInkGridSprite( loadSprite( "inkGrid.tga", false ) ),
          mColumnPickerSprite( loadWhiteSprite( "columnPicker.tga" ) ),
          mGuessSpriteRow( loadWhiteSprite( "guessMarkerRow.tga" ) ),
          mGuessSpriteColumn( loadWhiteSprite( "guessMarkerColumn.tga" ) ),
          mColumnHeaderSprite( loadSprite( "ilMondo.tga", false ) ),
          mRowHeaderSprite( loadSprite( "labisso.tga", false ) ),
          mSigilSprite( loadSprite( "minosons.tga", false ) ),
          mRoundEnding( false ), 
          mRoundEndTime( 0 ),
          mRoundStarting( false ),
          mRoundStartTime( 0 ) {
    

    if( SettingsManager::getIntSetting( "colorBlindMode", 0 ) == 1 ) {

        ourHueShift = 
            SettingsManager::getFloatSetting( "colorBlindOurColorHueShift", 
                                              0.0f );

        theirHueShift = 
            SettingsManager::getFloatSetting( "colorBlindTheirColorHueShift",
                                              0.0f );
        
        
        shiftColorHue( &usColor, ourHueShift );
        shiftColorHue( &themColor, theirHueShift );
        }
    
    Image *greenHeader = readTGAFile( "greenWatercolorHeader.tga" );
    Image *redHeader = readTGAFile( "redWatercolorHeader.tga" );
    
    if( ourHueShift != 0 ) {
        shiftImageHue( greenHeader, ourHueShift );
        }
    if( theirHueShift != 0 ) {
        shiftImageHue( redHeader, theirHueShift );
        }
    
    
    
    mGreenWatercolorHeaderSprite = fillSprite( greenHeader, false );
    mRedWatercolorHeaderSprite = fillSprite( redHeader, false );
    
    delete greenHeader;
    delete redHeader;
    

    greenStrokeFade = 
        SettingsManager::getFloatSetting( "greenStrokeFade", 1.0f );
    redStrokeFade = 
        SettingsManager::getFloatSetting( "redStrokeFade", 1.0f );
    blackStrokeFade = 
        SettingsManager::getFloatSetting( "blackStrokeFade", 1.0f );


    addServerErrorString( "GAME_ENDED", "gameEnded" );
    addServerErrorStringSignal( "GAME_ENDED", "gameEnded" );

    addServerErrorString( "GAME_EXPIRED", "gameExpired" );
    addServerErrorStringSignal( "GAME_EXPIRED", "gameExpired" );
    
    
    readCharacterGrid( "inkNumbers.tga", 6, 6, mInkNumberSprites );
    readCharacterGrid( "inkNumbersSuited.tga", 6, 6, mInkNumberSuitedSprites );

    readCharacterGrid( "inkHebrew.tga", 2, 6, mInkHebrewSprites );

    readCharacterGrid( "sansHebrew.tga", 1, 6, mSansHebrewSprites, true );
    

    
    readWatercolorImages( "greenWatercolorV.tga", true, 
                          mGreenWatercolorVSprites, ourHueShift );

    readWatercolorImages( "greenWatercolorH.tga", false, 
                          mGreenWatercolorHSprites, ourHueShift );

    readWatercolorImages( "redWatercolorV.tga", true, 
                          mRedWatercolorVSprites, theirHueShift );

    readWatercolorImages( "blackWatercolorV.tga", true, 
                          mBlackWatercolorVSprites );

    readWatercolorImages( "blackWatercolorH.tga", false, 
                          mBlackWatercolorHSprites );


    readWatercolorImages( "blackWatercolorVFlipped.tga", true, 
                          mBlackWatercolorVFlippedSprites );

    readWatercolorImages( "blackWatercolorHFlipped.tga", false, 
                          mBlackWatercolorHFlippedSprites );
    
    
    mInkGridCenter.x = 34;
    mInkGridCenter.y = -38;
    
    for( int i=0; i<6; i++ ) {
        mColumnPositions[i] = mInkGridCenter;
        mColumnPositions[i].x -= 138;
        mColumnPositions[i].x += i * 55;


        mRowPositions[i] = mInkGridCenter;
        mRowPositions[i].y += 137;
        mRowPositions[i].y -= i * 54;
        }
    
    mPickerUs.pos.y = -252;
    mPickerUs.mouseOver = false;
    mPickerUs.hardScoreUpdate = true;
    mPickerUs.held = false;
    mPickerUs.draw = false;

    mPickerThem.pos.y = -252;
    mPickerThem.hardScoreUpdate = true;
    mPickerThem.mouseOver = false;
    mPickerThem.held = false;
    mPickerThem.draw = false;    

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
    addComponent( &mLeaveConfirmButton );
    addComponent( &mBetPicker );
    

    setButtonStyle( &mCommitButton );
    setButtonStyle( &mBetButton );
    setButtonStyle( &mFoldButton );
    setButtonStyle( &mLeaveButton );
    setButtonStyle( &mLeaveConfirmButton );
    

    mCommitButton.addActionListener( this );
    mBetButton.addActionListener( this );
    mFoldButton.addActionListener( this );
    mLeaveButton.addActionListener( this );
    mLeaveConfirmButton.addActionListener( this );

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
    freeSprite( mInkGridSprite );

    freeSprite( mColumnHeaderSprite );
    freeSprite( mRowHeaderSprite );
    freeSprite( mSigilSprite );

    freeSprite( mGreenWatercolorHeaderSprite );
    freeSprite( mRedWatercolorHeaderSprite );

    for( int i=0; i<36; i++ ) {
        freeSprite( mInkNumberSprites[i] );
        freeSprite( mInkNumberSuitedSprites[i] );
        }

    for( int i=0; i<12; i++ ) {
        freeSprite( mInkHebrewSprites[i] );
        }
    for( int i=0; i<6; i++ ) {
        freeSprite( mSansHebrewSprites[i] );
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
            freeSprite( mBlackWatercolorVFlippedSprites[i][s] );
            freeSprite( mBlackWatercolorHFlippedSprites[i][s] );
            }
        }
    
    freeSprite( mColumnPickerSprite );
    freeSprite( mGuessSpriteRow );
    freeSprite( mGuessSpriteColumn );
    
    }



void PlayGamePage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }


    mMoveDeadline = 0;
    mLastUnflownBet = 0;

    mScorePipToLabel = -1;
    mScorePipLabelFade = 0;
    mScorePipLabelFadeDelta = -1;

    mPickerUs.draw = false;
    mPickerThem.draw = false;

    mPickerUs.hardScoreUpdate = true;
    mPickerThem.hardScoreUpdate = true;

    mCommitButton.setVisible( false );
    mCommitButtonJustPressed = false;
    
    // clear flashing
    setButtonStyle( &mCommitButton );

    mCommitFlashPreSteps = 0;
    mCommitFlashProgress = 1.0;
    mCommitFlashDirection = -1;

    mBetButton.setVisible( false );
    mFoldButton.setVisible( false );
    
    mLeaveButton.setVisible( true );
    mLeaveConfirmButton.setVisible( false );

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
    setResponsePartNames( numGameStateParts, gameStatePartNames );

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
    mRunning = false;
    
    mMessageState = gettingState;
    
    startRequest();
    
    mWaitingStartTime = game_time( NULL );

    // if this is an amulet game that's starting, we
    // already played the chime when the amulet stake was ready
    if( !waitingAmuletGame ) {
        playChime();
        }
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
                mLeaveConfirmButton.setVisible( false );
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
                    mLeaveConfirmButton.setVisible( false );
                    }
                else {
                    mColumnButtons[mOurChoices[0]]->setVisible( true );
                    mColumnButtons[mOurChoices[2]]->setVisible( true );
                    mColumnButtons[mOurChoices[4]]->setVisible( true );

                    mCommitButton.setVisible( false );
                    // clear flashing
                    setButtonStyle( &mCommitButton );

                    mLeaveButton.setVisible( true );
                    mLeaveConfirmButton.setVisible( false );
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
                mLeaveConfirmButton.setVisible( false );
                }

            computePossibleScores();
            }
        }

    if( inTarget == &mCommitButton ) {
        mMoveDeadline = 0;

        int numMovesAlreadyMade = 0;
        for( int j=0; j<6; j++ ) {
            if( mOurChoices[j] != -1 ) {
                numMovesAlreadyMade++;
                }
            }

        if( numMovesAlreadyMade == 6 ) {
            if( mPickerUs.draw && mPickerUs.targetColumn != -1 ) {
                mRevealChoiceForUs = mPickerUs.targetColumn;
                }
            }
        else {    
            if( mPickerUs.draw && mPickerUs.targetColumn != -1 ) {
                mColumnChoiceForUs = mPickerUs.targetColumn;
                }
            if( mPickerThem.draw && mPickerThem.targetColumn != -1 ) {
                mColumnChoiceForThem = mPickerThem.targetColumn;
                }
            }
        

        mPickerUs.draw = false;
        mPickerThem.draw = false;
        mPickerUs.held = false;
        mPickerThem.held = false;

        mCommitButton.setVisible( false );
        mCommitButtonJustPressed = true;
        
        // clear flashing
        setButtonStyle( &mCommitButton );
    
        mLeaveButton.setVisible( true );
        mLeaveConfirmButton.setVisible( false );
    
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
                             mGreenWatercolorVSprites[mNextGreenVSprite],
                             true, false, greenStrokeFade );
            mNextGreenVSprite++;
            addColumnStroke( mColumnChoiceForThem, 
                             mRedWatercolorVSprites[mNextRedVSprite],
                             false, false, redStrokeFade );
            mNextRedVSprite++;

            setActionParameter( "their_column", mColumnChoiceForThem );

            setActionParameter( "our_column", mColumnChoiceForUs );
            mColumnButtons[mColumnChoiceForUs]->setVisible( false );

            mColumnButtons[mColumnChoiceForThem]->setVisible( false );
            }
        else {
            setActionName( "make_reveal_move" );
            
            int r;
            for( int w=0; w<3; w++ ) {
                int c = mOurWonSquares[ w ] % 6;
                if( c == mRevealChoiceForUs ) {
                    r = mOurWonSquares[ w ] / 6;
                    break;
                    }
                }
            
            
            addRowStroke( r,
                          mBlackWatercolorHFlippedSprites[mRevealChoiceForUs],
                          false, false, 0.75 * blackStrokeFade );
            /*
            addColumnStroke( mRevealChoiceForUs,
                             mBlackWatercolorVFlippedSprites[r],
                             true, 0.6 );
            */

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
        mLeaveConfirmButton.setVisible( false );

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
            
            mLastUnflownBet = bet;
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
                      coinValue,
                      nextCoinID++,
                      false };
                mFlyingCoins[0].push_back( coin );
                }

            mLastUnflownBet = 0;
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
        mLeaveConfirmButton.setVisible( false );
        
        clearActionParameters();
        
        setActionName( "fold_bet" );
        setResponsePartNames( -1, NULL );
        
        mBetPicker.setVisible( false );
        
        mMessageState = sendingFold;
        
        setupRequestParameterSecurity();
        startRequest();
        }
    else if( inTarget == &mLeaveButton ) {
        mLeaveButton.setVisible( false );
        mLeaveConfirmButton.setVisible( true );
        }
    else if( inTarget == &mLeaveConfirmButton ) {
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



void PlayGamePage::drawColumnPicker( ColumnPicker *inPicker ) {
    if( inPicker->draw ) {
        float fade = 1;
        
        if( ! inPicker->mouseOver && ! inPicker->held ) {
            if( ! inPicker->draggedInYet ) {
                fade = 0.75 * mCommitFlashProgress;
                }
            else {
                fade = 0.75;
                }
            }
        doublePair pos = inPicker->pos;
        if( inPicker->held ) {
            pos.y += 4;
            }
        
        setDrawFade( fade );

        drawSprite( mColumnPickerSprite, pos );
        
        if( inPicker->targetColumn != -1 ) {
            pos.y -= 16;

            float labelFade = 0;
            
            double dist = 
                fabs( pos.x - mColumnPositions[ inPicker->targetColumn ].x );
            
            if( dist < 27 ) {
                labelFade = 1 - dist / 27.0;
                }
            setDrawFade( fade * labelFade );
            
            drawSprite( mSansHebrewSprites[ inPicker->targetColumn ],
                        pos );
            }
        }


    if( mLeaveConfirmButton.isVisible() &&
        ! mHideLeavePenalty ) {
                
        doublePair pos = mLeaveConfirmButton.getPosition();
        
        pos.y -= 52;
        
        char *message = autoSprintf( translate( "leavePenalty" ), 
                                     mLeavePenalty );
        
        drawMessage( message, pos );
        
        delete [] message;
        }
    
    }




void PlayGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    

    
    doublePair pos = { - cellCenterStart, cellCenterStart };
    
    if( mGameBoard != NULL ) {
        
        setDrawColor( 1, 1, 1, 1 );


        
        char finalReveal = false;

        if( mTheirWonSquares[0] != -1 &&
            mTheirWonSquares[1] != -1 &&
            mTheirWonSquares[2] != -1 ) {
            finalReveal = true;
            }

        
        if( ! mShowWatercolorDemo ) {
            
        doublePair center  = {0,0};
        drawSquare( center, 
                    ( (cellSize + borderWidth) * 6 ) /  2 + borderWidth / 2 );
        
        int cellIndex = 0;
        
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
        
            } // end check for mShowWatercolorDemo
        
        
        // draw score pips
        
        if( mGameType == 0 )
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
        
        if( mGameType == 0 )
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
        
        if( mGameType == 0 )
        mainFont->drawString( scoreString, pos, alignRight );
        
        delete [] scoreString;


        if( mGameType == 0 )
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
                
                if( !mChimePlayed ) {
                    playChime();
                    mChimePlayed = true;
                    }
                
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
            
            if( mGameType == 1 ) {
                drawSprite( mInkNumberSuitedSprites[ mGameBoard[i] -  1 ], 
                            numberPos );
                }
            else {
                drawSprite( mInkNumberSprites[ mGameBoard[i] -  1 ], 
                            numberPos );
                }
            }

        for( int i=0; i<6; i++ ) {
            doublePair charPos;
            
            charPos.x = mColumnPositions[ i ].x + 4;
            charPos.y = mRowPositions[0].y + 42;
            
            drawSprite( mInkHebrewSprites[i], charPos );

            charPos.x = mColumnPositions[ 0 ].x - 49;
            charPos.y = mRowPositions[i].y;
            
            drawSprite( mInkHebrewSprites[i + 6], charPos );
            }
        

        doublePair columnHeaderPos = mInkGridCenter;
        columnHeaderPos.y += 220;
        
        drawSprite( mColumnHeaderSprite, columnHeaderPos );
        
        doublePair rowHeaderPos = mInkGridCenter;
        rowHeaderPos.x -= 220;

        drawSprite( mRowHeaderSprite, rowHeaderPos );

        doublePair headerPos = mInkGridCenter;
        headerPos.x -= 200;
        headerPos.y += 206;
        
        drawSprite( mSigilSprite, headerPos );
        

        setDrawColor( 1 - greenStrokeFade, 
                      1 - greenStrokeFade, 
                      1 - greenStrokeFade, 1 );
                
        columnHeaderPos.y += 9;
        columnHeaderPos.x += 5;
        drawSprite( mGreenWatercolorHeaderSprite, columnHeaderPos );


        setDrawColor( 1 - redStrokeFade, 
                      1 - redStrokeFade, 
                      1 - redStrokeFade, 1 );
        
        rowHeaderPos.x -= 9;
        rowHeaderPos.y += 3;
        drawSprite( mRedWatercolorHeaderSprite, rowHeaderPos );
        
        
        setDrawColor( 1, 1, 1, 1 );


        for( int i=0; i<mWatercolorStrokes.size(); i++ ) {
            // draw strokes until we find one that is still
            // fading in, then stop after that one (only fade in one
            // at a time
            
            WatercolorStroke *stroke = mWatercolorStrokes.getElement( i );


            if( stroke->waitingForCoinIDToFinish != -1 ) {
                // still waiting for our preceeding coin to land
                // before we increment our draw progress
                // don't draw any further strokes either.
                break;
                }

            float globalFade = stroke->globalFade;
            
            if( stroke->leftEnd == 0 && stroke->rightEnd == 0 ) {
                setDrawColor( 1-globalFade, 1-globalFade, 1-globalFade, 1 );
                
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

                    if( globalFade < 1 ) {
                        value = 1 - ( globalFade * ( 1 - value ) );
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

                if( stroke->rightEnd < 0.25 
                    &&
                    stroke->computePossibleScoresPending ) {
                    
                    computePossibleScores();
                    stroke->computePossibleScoresPending = false;
                    }
                    
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
            drawSquare( parchPos, 219 );
            }

        // draw held picker on top
        if( ! mPickerUs.held && ! mPickerUs.mouseOver ) {
            setUsColor();
            drawColumnPicker( &mPickerUs );
            }
        
        setThemColor();
        drawColumnPicker( &mPickerThem );
        
        if( mPickerUs.held || mPickerUs.mouseOver ) {
            setUsColor();
            drawColumnPicker( &mPickerUs );
            }

        if( mMouseOverSquareLocked ) {
            doublePair pos;
            pos.x = 228;
            
            if( mMouseOverOurRow != -1 ) {
                setUsColor();
                pos.y = mRowPositions[ mMouseOverOurRow ].y;
                }
            else if( mMouseOverTheirRow != -1 ) {
                setThemColor();
                pos.y = mRowPositions[ mMouseOverTheirRow ].y;
                }
            
            setDrawFade( 0.75 );

            drawSprite( mGuessSpriteRow, pos );

            if( mMouseOverTheirColumn != -1 && 
                ! ( mPickerThem.draw && 
                    mPickerThem.targetColumn == mMouseOverTheirColumn ) ) {
                
                pos.y = -228;
                
                pos.x = mColumnPositions[ mMouseOverTheirColumn ].x;
                
                setThemColor();

                setDrawFade( 0.75 );

                drawSprite( mGuessSpriteColumn, pos );
                }

            if( mMouseOverOurColumn != -1 && 
                ! ( mPickerUs.draw && 
                    mPickerUs.targetColumn == mMouseOverOurColumn ) ) {
                
                pos.y = -228;
                
                pos.x = mColumnPositions[ mMouseOverOurColumn ].x;
                
                setUsColor();

                setDrawFade( 0.75 );

                drawSprite( mGuessSpriteColumn, pos );
                }
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


        
    }



// returns -1 if the int is ?
int stringToInt( const char *inString ) {
    
    int returnValue = -1;
    
    sscanf( inString, "%d", &returnValue );

    return returnValue;
    }



int PlayGamePage::slidePicker( ColumnPicker *inPicker ) {
    if( inPicker->draw && 
        inPicker->targetColumn != -1 &&
        ! inPicker->held &&
        inPicker->pos.x != 
        mColumnPositions[inPicker->targetColumn].x ) {
        
        float oldTotalDelta = 
            mColumnPositions[inPicker->targetColumn].x - inPicker->pos.x;

        // Purho Easing function
        float delta = 0.2 * frameRateFactor *
            ( mColumnPositions[inPicker->targetColumn].x - inPicker->pos.x );
        
        if( delta < 0 ) {
            delta = floorf( delta );
            }
        else {
            delta = ceilf( delta );
            }
        
        if( fabsf( oldTotalDelta ) < fabsf( delta ) ) {
            // would move past the end!    
            inPicker->pos.x = mColumnPositions[inPicker->targetColumn].x;
            }
        else {
            inPicker->pos.x += delta;
            }
        

        // pickers could move via keyboard commands too
        if( mMouseOverTheirRow != -1 ||
            mMouseOverOurRow != -1 ) {
            
            mMouseOverTheirTurnNumber = -1;
            mMouseOverTheirRow = -1;
            mMouseOverTheirColumn = -1;
            
            mMouseOverOurColumn = -1;
            mMouseOverOurRow = -1;
            mMouseOverSquareLocked = false;
            
            computePossibleScores( true );
            }
        
        if( inPicker->pos.x == mColumnPositions[inPicker->targetColumn].x ) {
            // reached goal
            return 1;
            }
        else {
            // still moving
            return 2;
            }
        }
    return 0;
    }



void PlayGamePage::step() {

    // clear flag for next time
    // this only needs to be set during a single mouse up event
    mCommitButtonJustPressed = false;            
            

    if( mMoveDeadline != 0 ) {
        int secondsLeft = (int)( mMoveDeadline - game_time( NULL ) );

        if( secondsLeft < 0 ) {
            // past deadline, force them to leave game
            actionPerformed( &mLeaveConfirmButton );
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
        
        if( lastStroke->rightEnd > 0 && 
            lastStroke->waitingForCoinIDToFinish == -1 ) {

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
        
        if( mMouseOverSquareLocked ) {
            // clear locked square arrows once fade starts
            mMouseOverTheirTurnNumber = -1;
            mMouseOverTheirRow = -1;
            mMouseOverTheirColumn = -1;
            
            mMouseOverOurColumn = -1;
            mMouseOverOurRow = -1;
            mMouseOverSquareLocked = false;
            
            computePossibleScores( true );
            }
        
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

    if( ! mParchmentFadingOut && mParchmentFade > 0 && mRunning ) {
        mParchmentFade -= 0.02 * frameRateFactor;
        if( mParchmentFade < 0 ) {
            mParchmentFade = 0;
            }
        }

    // delay flying coins until all strokes added
    if( !anyStrokesStillAdding )
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

                        if( ( ( coin->value == 1 && coin->progress >= .95 )
                              ||
                              // big coin sound takes longer to reach peak
                              ( coin->progress >= .85 ) )
                            &&
                            ! coin->soundPlayed ) {
                            // coin almost done, start sound

                            
                            if( coin->value == 1 ) {
                                if( coin->dest->coinCount != NULL ) {
                                    playChipSound( 0 );
                                    }
                                else {
                                    // rake
                                    playChipSound( 2 );
                                    }
                                }
                            else {
                                // big coin
                                if( coin->dest->coinCount != NULL ) {
                                    playChipSound( 1 );
                                    }
                                else {
                                    // rake
                                    playChipSound( 2 );
                                    }
                                }
                            coin->soundPlayed = true;

                            // mute any coin that is flying in parallel
                            int parallelQueue = 0;
                            if( f == 0 ) {
                                parallelQueue = 1;
                                }
                            if( mFlyingCoins[parallelQueue].size() > c ) {
                                PendingFlyingCoin *parallelCoin = 
                                    mFlyingCoins[parallelQueue].
                                    getElement( c );
                                
                                parallelCoin->soundPlayed = true;
                                }
                            }
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
                    
                    // look for any strokes that are waiting for this
                    // coin to land
                    for( int s=0; s < mWatercolorStrokes.size(); s++ ) {
                        WatercolorStroke *stroke = 
                            mWatercolorStrokes.getElement( s );
                    
                        if( stroke->waitingForCoinIDToFinish ==
                            coin->id ) {
                            
                            // this stroke can start drawing now
                            stroke->waitingForCoinIDToFinish = -1;
                            }
                        }
                    

                    mFlyingCoins[f].deleteElement( c );
                    }
                }
            }
        }
    
    
    if( mCommitButton.isVisible() || 
        ( mPickerUs.draw && ! mPickerUs.draggedInYet ) ||
        ( mPickerThem.draw && ! mPickerThem.draggedInYet ) ) {

        if( ( mCommitButton.isVisible() && mCommitButton.isMouseOver() )
            || mPickerUs.mouseOver || mPickerThem.mouseOver
            || mPickerThem.held || mPickerUs.held ) {
            
            mCommitFlashPreSteps = 0;
            mCommitFlashProgress = 1;
            mCommitFlashDirection = -1;
            
            mCommitButton.setNoHoverColor( 1, 1, 1, mCommitFlashProgress );
            }
        else {
            
            mCommitFlashPreSteps ++;
            
            if( mCommitFlashPreSteps > 300 / frameRateFactor ) {
                

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
    

    
    int slidUs = slidePicker( &mPickerUs );
    int slidThem = slidePicker( &mPickerThem );

    if( slidUs != 2 && slidThem != 2 &&
        ! mPickerUs.held && ! mPickerThem.held ) {
        // neither still moving or held
        
        if( ! mPickerUs.hardScoreUpdate ||
            ! mPickerThem.hardScoreUpdate ) {
            
            // a picker still needs a hard score update, now that it
            // has come to rest
            computePossibleScores( false );
        
            mPickerUs.hardScoreUpdate = true;
            mPickerThem.hardScoreUpdate = true;
            }
        }
    



    ServerActionPage::step();

    if( checkSignal( "gameEnded" ) &&
        mFlyingCoins[0].size() == 0 &&
        mFlyingCoins[1].size() == 0 ) {
        
        // special case:
        // all pot coins fly back to their owners

        for( int p=0; p<2; p++ ) {
                
                        
            int coinValue = 1;
            if( mPotCoins[p] >= 10 ) {
                coinValue = 10;
                }

            for( int i=0; i<mPotCoins[p]; i += coinValue ) {
                if( mPotCoins[p] - i < coinValue ) {
                    coinValue = 1;
                    }

                PendingFlyingCoin coin = 
                    { &( mPotCoinSpots[p] ),
                      &( mPlayerCoinSpots[p] ),
                      0,
                      coinValue,
                      nextCoinID++,
                      false };
                mFlyingCoins[p].push_back( coin );
                }
            }
        
        clearSignal();
        }


    if( checkSignal( "gameExpired" ) &&
        mFlyingCoins[0].size() == 0 &&
        mFlyingCoins[1].size() == 0 ) {
        
        // special case:
        // we're not sure WHAT happened
        // did our bet make it through?  Who gets what coins?
        // we don't know, so just show game frozen in last-known state
        // Let post-leave screen sort it out.
        
        clearSignal();
        }
    

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
        
        mWaitingStartTime = game_time( NULL );
        mChimePlayed = false;
        
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
        
        mWaitingStartTime = game_time( NULL );
        mChimePlayed = false;
        
        startRequest();
        }
    else if( mMessageState == sendingFold ) {
        
        // fold sent
        
        // get end game state
        setActionName( "get_game_state" );
        setResponsePartNames( numGameStateParts, gameStatePartNames );
        
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

        mWaitingStartTime = game_time( NULL );
        mChimePlayed = false;

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
        
        mWaitingStartTime = game_time( NULL );
        mChimePlayed = false;

        startRequest();
        }
    else if( mMessageState == gettingState ||
             mMessageState == gettingStatePostMove ||
             mMessageState == gettingStatePostBet ||
             mMessageState == gettingStateAtEnd ) {
        
        char newStrokesAdded = false;

        mRunning = getResponseInt( "running" );
        

        int coins[2];
        int pots[2];
        
        coins[0] = getResponseInt( "ourCoins" );
        coins[1] = getResponseInt( "theirCoins" );

        pots[0] = getResponseInt( "ourPotCoins" );
        pots[1] = getResponseInt( "theirPotCoins" );

        
        if( mRunning &&
            coins[0] > 0 && coins[1] > 0 && 
            pots[0] == 0 && pots[1] == 0 ) {
            
            // the post-reveal coin distribution has happened
            // and there are still coins left for another round
            mRoundStarting = true;
            mRoundStartTime = game_time( NULL ) + 5;
            }

        if( ! mRunning ||
            ( pots[0] == 0 && pots[1] == 0 && 
              ( coins[0] == 0 || coins[1] == 0 ) ) ) {
            mHideLeavePenalty = true;
            }
        else {
            mHideLeavePenalty = false;
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
                          coinValue,
                          nextCoinID++,
                          false };
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
                              coinValue,
                              nextCoinID++,
                              false };
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
                
                char parallelOkay = false;
                if( mFlyingCoins[0].size() > mFlyingCoins[1].size() ) {
                    // queue 0 more full, make these new
                    // coins wait at the end of queue 1
                    flyingQueue = &( mFlyingCoins[0] );
                    }
                else if( mFlyingCoins[0].size() == 
                         mFlyingCoins[1].size() ) {
                    parallelOkay = true;
                    flyingQueue = &( mFlyingCoins[0] );
                    }
                else {
                    // queue 1 more full, make these new
                    // coins wait at the end of queue 1
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
                              coinValue,
                              nextCoinID++,
                              false };
                        flyingQueue->push_back( coin );
                        }
                    
                    if( mLastUnflownBet > 0 ) {
                        // opponent folded to our bet, which wasn't
                        // visibly added to pot yet
                        
                        SimpleVector<PendingFlyingCoin> *ourBetQueue = 
                            flyingQueue;
                        
                        if( parallelOkay ) {
                            ourBetQueue = &( mFlyingCoins[1] );
                            }

                        int coinValue = 1;
                        if( mLastUnflownBet >= 10 ) {
                            coinValue = 10;
                            }
                        
                        for( int i=0; i<mLastUnflownBet; i += coinValue ) {
                            if( mLastUnflownBet - i < coinValue ) {
                                coinValue = 1;
                                }
                            
                            PendingFlyingCoin coin = 
                                { &( mPlayerCoinSpots[0] ),
                                  &( mPotCoinSpots[0] ),
                                  0,
                                  coinValue,
                                  nextCoinID++,
                                  false };
                            ourBetQueue->push_back( coin );
                            }
                        
                        // these need to fly back to us after they
                        // fly into the pot
                        winnerPotToAward += mLastUnflownBet;
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
                          coinValue,
                          nextCoinID++,
                          false };
                    flyingQueue->push_back( coin );
                    }
                

                if( tie ) {
                    // swap winner and loser before distributing
                    // loser's coins
                    winner = loser;
                    winnerCoinSpot = 
                        &( mPlayerCoinSpots[loser] );

                    // let them fly in parallel if there aren't 
                    // already coins flying
                    if( parallelOkay ) {
                        flyingQueue = &( mFlyingCoins[1] );
                        }
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
                          coinValue,
                          nextCoinID++,
                          false };
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
                          coinValue,
                          nextCoinID++,
                          false };
                    flyingQueue->push_back( coin );
                    }
                
                }
            }
        
        
        // if bet hasn't flown yet, we're never going to show it fly
        // make sure it doesn't hang around until next round
        mLastUnflownBet = 0;
        
            
        mLeavePenalty = getResponseInt( "leavePenalty" );

        int secondsLeft = getResponseInt( "secondsLeft" );
        
        if( mMessageState != gettingStateAtEnd &&
            mRunning && secondsLeft >= 0 ) {
            mMoveDeadline = game_time( NULL ) + secondsLeft;

            int secondsWaiting = game_time( NULL ) - mWaitingStartTime;
            
            if( secondsWaiting > 10 &&
                mFlyingCoins[0].size() == 0 &&
                mFlyingCoins[1].size() == 0 ) {
                
                // don't play chime if there's already sound
                // that will come from opponent's flying coins
                
                playChime();
                }
            
            mChimePlayed = false;
        
            }
        else {
            // no deadline
            mMoveDeadline = 0;
            
            mWaitingStartTime = game_time( NULL );
            mChimePlayed = false;
            }


        mGameType = getResponseInt( "gameType" );
        
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
        mMouseOverTheirTurnNumber = -1;
        mMouseOverTheirRow = -1;
        mMouseOverTheirColumn = -1;
        
        mMouseOverOurColumn = -1;
        mMouseOverOurRow = -1;
        mMouseOverSquareLocked = false;
        
        char *ourMoves = getResponse( "ourMoves" );
        char *theirMoves = getResponse( "theirMoves" );
        
        int theirOldChoices[6];
        memcpy( theirOldChoices, mTheirChoices, 6 * sizeof( int ) );

        int ourOldWonSquares[3];
        memcpy( ourOldWonSquares, mOurWonSquares, 3 * sizeof( int ) );
        
        int theirOldWonSquares[3];
        memcpy( theirOldWonSquares, mTheirWonSquares, 3 * sizeof( int ) );

        int theirOldWonNumSquares = 0;
        
        for( int i=0; i<6; i++ ) {
            mRowUsed[i] = false;
            mColumnUsed[i] = false;
            mOurChoices[i] = -1;
            mTheirChoices[i] = -1;
            }
        for( int i=0; i<3; i++ ) {
            mOurWonSquares[i] = -1;
            mTheirWonSquares[i] = -1;

            if( theirOldWonSquares[i] != -1 ) {
                theirOldWonNumSquares++;
                }
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

                        char speedUp = true;
                        if( anyStrokesStillAdding ) {
                            // don't rush this stroke on top of last
                            // one that is drawing
                            speedUp = false;
                            }
                        
                        newStrokesAdded = true;
                        
                        addRowStroke( 
                            mTheirChoices[ theirChoiceMapping[i] ],
                            mGreenWatercolorHSprites[mNextGreenHSprite],
                            speedUp, true, greenStrokeFade );
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
                            
                            newStrokesAdded = true;
                            
                            addColumnStroke( c,
                                             mBlackWatercolorVSprites[r],
                                             false, false, 
                                             0.75 * blackStrokeFade );
                            /*
                            addRowStroke( r,
                                          mBlackWatercolorHSprites[c],
                                          false, 0.75 );
                            */
                            }

                        ourWonSquareCount ++;
                        }
                    
                    if( theirSelfChoice != -1 &&
                        ourOtherChoice != -1 ) {
                        
                        int theirWonIndex = 
                            theirSelfChoice * 6 + ourOtherChoice;
                        
                        mTheirWonSquares[ theirWonSquareCount ] =
                            theirWonIndex;
                        

                        // order of their won squares can change from
                        // first reveal to final reveal
                        // so, we have to check if a square is new
                        // before masking it
                        char presentBefore = false;
                        
                        for( int s=0; s<3; s++ ) {
                            if( theirOldWonSquares[s] == theirWonIndex ) {
                                presentBefore = true;
                                break;
                                }
                            }

                        if( !presentBefore ) {
                            
                            // a new won square
                            int r = 
                                mTheirWonSquares[ theirWonSquareCount ] / 6;
                            int c = 
                                mTheirWonSquares[ theirWonSquareCount ] % 6;
                            
                            newStrokesAdded = true;
                            
                            addColumnStroke( c,
                                             mBlackWatercolorVSprites[r],
                                             false, true, 
                                             .75 * blackStrokeFade );
                            
                            if( theirOldWonNumSquares == 0 ) {
                                // initial reveal, mask row too
                                addRowStroke( r,
                                              mBlackWatercolorHSprites[c],
                                              false, false, 
                                              .60 * blackStrokeFade );
                                }
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

        if( mRunning ) {
            // don't clear these if opponent left
            // still want to be able to mouse-over our last choices
            mColumnChoiceForUs = -1;
            mColumnChoiceForThem = -1;
            }
        
        
        if( ! mRunning || 
            getNetPotCoins(0) == 0 ||
            getNetPotCoins(1) == 0 ) {
            
            // one player left or either down to 0

            // game over

            mLeaveButton.setVisible( true );
            mLeaveConfirmButton.setVisible( false );
            }
        else if( mMessageState == gettingState 
                 ||
                 // OR matched bets
                 ( mMessageState == gettingStatePostBet &&
                   getNetPotCoins(0) == getNetPotCoins(1) ) 
                 ||
                 // OR no bets possible because one player has 0 coins
                 ( mMessageState == gettingStatePostMove &&
                   ( getNetPlayerCoins(0) == 0 ||
                     getNetPlayerCoins(1) == 0 ) ) ) {
            
            int numUsedColumns = 0;
            
            for( int i=0; i<6; i++ ) {
                if( !mShowWatercolorDemo ) {
                    mColumnButtons[i]->setVisible( ! mColumnUsed[i] );
                    mColumnButtons[i]->setLabelText( "+" );
                    }
                if( mColumnUsed[i] ) {
                    numUsedColumns ++;
                    }
                else {
                    if( ! mPickerUs.draw ) {
                        mPickerUs.draw = true;
                        mPickerUs.pos.x = mColumnPositions[0].x -
                            columnPickerStartingOffset;
                        mPickerUs.targetColumn = -1;
                        mPickerUs.trueClosestColumn = -1;
                        mPickerUs.lastPlayerDropColumn = -1;
                        mPickerUs.hardScoreUpdate = true;
                        mPickerUs.draggedInYet = false;
                        mPickerUs.held = false;
                        mPickerUs.mouseOver = false;
                        }
                    else if( ! mPickerThem.draw ) {
                        mPickerThem.draw = true;
                        mPickerThem.pos.x = mColumnPositions[5].x +
                            columnPickerStartingOffset;
                        mPickerThem.targetColumn = -1;
                        mPickerThem.trueClosestColumn = -1;
                        mPickerThem.lastPlayerDropColumn = -1;
                        mPickerThem.hardScoreUpdate = true;
                        mPickerThem.draggedInYet = false;
                        mPickerThem.held = false;
                        mPickerThem.mouseOver = false;
                        }
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
                
                if( !mShowWatercolorDemo ) {
                    mColumnButtons[mOurChoices[0]]->setVisible( true );
                    mColumnButtons[mOurChoices[2]]->setVisible( true );
                    mColumnButtons[mOurChoices[4]]->setVisible( true );
                
                    mColumnButtons[mOurChoices[0]]->setLabelText( "R" );
                    mColumnButtons[mOurChoices[2]]->setLabelText( "R" );
                    mColumnButtons[mOurChoices[4]]->setLabelText( "R" );
                    }
                
                mPickerUs.draw = true;
                mPickerUs.pos.x = mColumnPositions[0].x - 
                    columnPickerStartingOffset;
                mPickerUs.targetColumn = -1;
                mPickerUs.trueClosestColumn = -1;
                mPickerUs.lastPlayerDropColumn = -1;
                mPickerUs.hardScoreUpdate = true;
                mPickerUs.draggedInYet = false;
                mPickerUs.held = false;
                mPickerUs.mouseOver = false;
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
            mLeaveConfirmButton.setVisible( false );
            }
        
        // wait for strokes to add before jumping score graph
        // (allow for supense of watching reveal without score
        // graph giving it away).
        if( ! newStrokesAdded ) {
            computePossibleScores();
            }
        // else some strokes tagged to update score after they're done

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
            setResponsePartNames( numGameStateParts, gameStatePartNames );

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
            setResponsePartNames( numGameStateParts, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingStateAtEnd;
            
            startRequest();
            }
        else if( strcmp( status, "next_round_started" ) == 0 ) {
            // start of new round
            setActionName( "get_game_state" );
            setResponsePartNames( numGameStateParts, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingState;
            
            startRequest();
            }
        else if( strcmp( status, "opponent_left" ) == 0 ) {
            // get final state
            setActionName( "get_game_state" );
            setResponsePartNames( numGameStateParts, gameStatePartNames );

            clearActionParameters();
            mMessageState = gettingState;
            
            startRequest();
            
            mLeaveButton.setVisible( true );
            mLeaveConfirmButton.setVisible( false );
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




// realize that choices for other player, and order of those choices,
// don't matter for computing possible scores of a given player
// Thus, we can save a bunch of computation time by computing them
// separately than by walking entire game tree with 500K leaves.
static void computePossibleScoresForPlayer( 
    // these are -1 if not assigned yet
    int inColumnsToPlayer[3],
    int inRowsToPlayer[3],
    char inColumnAvailable[6],
    char inRowAvailable[6],
    int *inGameBoard,
    // where we should set flags when we compute that a score for this
    // player is possible
    char *inPossibleScoreMap ) {

    for( int c=0; c<3; c++ ) {
        
        if( inColumnsToPlayer[c] == -1 ) {
        
            // set from each of available cols
            for( int a=0; a<6; a++ ) {
                
                if( inColumnAvailable[a] ) {
                    //mark as not avail

                    inColumnAvailable[a] = false;
                    
                    inColumnsToPlayer[c] = a;

                    // recurse
                    computePossibleScoresForPlayer( 
                        inColumnsToPlayer,
                        inRowsToPlayer,
                        inColumnAvailable,
                        inRowAvailable,
                        inGameBoard,
                        inPossibleScoreMap );

                    // undo what we set
                    inColumnAvailable[a] = true;
                    }
                }
            
            // undo what we set and return
            inColumnsToPlayer[c] = -1;
            return;
            }
        }
    // all cols set

    for( int r=0; r<3; r++ ) {

        if( inRowsToPlayer[r] == -1 ) {
        
            // set from each of available cols
            for( int a=0; a<6; a++ ) {
                
                if( inRowAvailable[a] ) {
                    //mark as not avail

                    inRowAvailable[a] = false;
                    
                    inRowsToPlayer[r] = a;

                    // recurse
                    computePossibleScoresForPlayer( 
                        inColumnsToPlayer,
                        inRowsToPlayer,
                        inColumnAvailable,
                        inRowAvailable,
                        inGameBoard,
                        inPossibleScoreMap );

                    // undo what we set
                    inRowAvailable[a] = true;
                    }
                }
            
            // undo what we set and return
            inRowsToPlayer[r] = -1;
            return;
            }
        }

    // both rows and cols set.

    // compute score for player from these

    // set it in inPossibleScoreMap
    
    int score = 0;
    
    for( int p=0; p<3; p++ ) {
        score += 
            inGameBoard[ inRowsToPlayer[p] * 6 + inColumnsToPlayer[p] ];
        }
    
    inPossibleScoreMap[score] = true;

    return;
    }




void PlayGamePage::computePossibleScoresFast() {
    for( int i=0; i<MAX_SCORE_RANGE; i++ ) {
        mOurPossibleScores[i] = false;
        mTheirPossibleScores[i] = false;
        
        mOurPossibleScoresFromTheirPerspective[i] = false;
        }
    

    int columnsGivenUs[3];
    int rowsGivenUs[3];

    int columnsGivenThem[3];
    int rowsGivenThem[3];
    
    char columnsAvail[6];
    char rowsAvail[6];
    
    memset( columnsAvail, true, 6 );
    memset( rowsAvail, true, 6 );

    for( int i=0; i<3; i++ ) {
        columnsGivenUs[i] = -1;
        columnsGivenThem[i] = -1;
        rowsGivenUs[i] = -1;
        rowsGivenThem[i] = -1;
        }
    
    char pendingChoiceUsUsed = false;
    char pendingChoiceThemUsed = false;
    
    char pendingMouseOverSquareUsed = false;

    for( int i=0; i<3; i++ ) {
        columnsGivenUs[i] = mOurChoices[i*2];
        columnsGivenThem[i] = mOurChoices[i*2+1];
        
        if( columnsGivenUs[i] == -1 && ! pendingChoiceUsUsed ) {
            columnsGivenUs[i] = mColumnChoiceForUs;
            pendingChoiceUsUsed = true;
            }

        if( columnsGivenThem[i] == -1 && ! pendingChoiceThemUsed ) {
            columnsGivenThem[i] = mColumnChoiceForThem;
            pendingChoiceThemUsed = true;
            }


        rowsGivenUs[i] = mTheirChoices[i];
        rowsGivenThem[i] = mTheirChoices[ 3 + i ];

        if( rowsGivenThem[i] == -1 && 
            mMouseOverTheirRow != -1 &&
            !pendingMouseOverSquareUsed &&
            mMouseOverTheirTurnNumber == i ) {
            
            rowsGivenThem[i] = mMouseOverTheirRow;
                
            pendingMouseOverSquareUsed = true;
            }
        else if( rowsGivenUs[i] == -1 && 
            mMouseOverOurRow != -1 &&
            !pendingMouseOverSquareUsed ) {
            
            rowsGivenUs[i] = mMouseOverOurRow;
            
            if( mMouseOverOurColumn != -1 ) {
                columnsGivenUs[i] = mMouseOverOurColumn;
                }

            pendingMouseOverSquareUsed = true;
            }
        
        
        if( columnsGivenUs[i] != -1 ) {
            columnsAvail[ columnsGivenUs[i] ] = false;
            }
        if( columnsGivenThem[i] != -1 ) {
            columnsAvail[ columnsGivenThem[i] ] = false;
            }


        if( rowsGivenUs[i] != -1 ) {
            rowsAvail[ rowsGivenUs[i] ] = false;
            }
        if( rowsGivenThem[i] != -1 ) {
            rowsAvail[ rowsGivenThem[i] ] = false;
            }
        }
    
        

    // our possible scores from our perspective
    computePossibleScoresForPlayer( 
        columnsGivenUs,
        rowsGivenUs,
        columnsAvail,
        rowsAvail,
        mGameBoard,
        mOurPossibleScores );


    // their possible scores from our perspective
    computePossibleScoresForPlayer( 
        columnsGivenThem,
        rowsGivenThem,
        columnsAvail,
        rowsAvail,
        mGameBoard,
        mTheirPossibleScores );



    int numColumnsAvailable = 0;
    
    for( int i=0; i<6; i++ ) {
        if( columnsAvail[i] ) {
            numColumnsAvailable ++;
            }
        }

    
    if( numColumnsAvailable == 1 &&
        columnsGivenThem[2] == -1 ) {
        
        // one column left, it's going to be given to them, so
        // just give it now, so that we can see how their information
        // view will change by us making a given choice for US that leaves
        // only one column left that we can give THEM
        
        // essentially, on turn 3, dragging the green slider alone
        // should be enough to update the whole graph, even before
        // the red slider is dropped in the other column
        for( int i=0; i<6; i++ ) {
            if( columnsAvail[i] ) {
                columnsGivenThem[2] = i;
                
                // no longer available, now that we gave it to them
                columnsAvail[i] = false;
                break;
                }
            }
        }
    


    // if not final reveal
    if( mTheirWonSquares[0] == -1 || 
        mTheirWonSquares[1] == -1 ||
        mTheirWonSquares[2] == -1 ) {
        
        
        // clear info about what we've given ourselves
        for( int i=0; i<3; i++ ) {

            // but don't clear a pending reveal
            if( columnsGivenUs[i] != -1 && 
                // but don't clear a pending reveal
                columnsGivenUs[i] != mRevealChoiceForUs ) {
                
                if( mOurWonSquares[0] != -1 &&
                    mOurWonSquares[1] != -1 &&
                    mOurWonSquares[2] != -1 
                    &&
                    ( ( mOurWonSquares[0] % 6 == columnsGivenUs[i] &&
                        mOurWonSquares[0] / 6 == mMouseOverOurRow )
                      ||
                      ( mOurWonSquares[1] % 6 == columnsGivenUs[i] &&
                        mOurWonSquares[1] / 6 == mMouseOverOurRow )
                      ||
                      ( mOurWonSquares[2] % 6 == columnsGivenUs[i] &&
                        mOurWonSquares[2] / 6 == mMouseOverOurRow ) ) ) {
                    
                    // special case:
                    // player is mousing over one of our won squares
                    // after all picked, but pre-reveal
                    
                    // don't clear this column
                    }
                else {
                    // clear it
                    columnsAvail[ columnsGivenUs[i] ] = true;
                    
                    columnsGivenUs[i] = -1;
                    }
                }
            }
        }
    
    
     // our possible scores from their perspective
    computePossibleScoresForPlayer( 
        columnsGivenUs,
        rowsGivenUs,
        columnsAvail,
        rowsAvail,
        mGameBoard,
        mOurPossibleScoresFromTheirPerspective );
    }



static char possibleScoreMethod = true;
    

void PlayGamePage::computePossibleScores( char inCachedOnly ) {
    if( possibleScoreMethod ) {
        computePossibleScoresFast();
        }
    else {
        computePossibleScoresOld( inCachedOnly );
        }
    }



void PlayGamePage::computePossibleScoresOld( char inCachedOnly ) {
        
    if( loadCacheRecord() ) {
        // cached!
        return;
        }

    if( inCachedOnly ) {
        return;
        }
    
    for( int i=0; i<MAX_SCORE_RANGE; i++ ) {
        mOurPossibleScores[i] = false;
        mTheirPossibleScores[i] = false;
        
        mOurPossibleScoresFromTheirPerspective[i] = false;
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



void PlayGamePage::pickerReactToMouseMove( ColumnPicker *inPicker,
                                           ColumnPicker *inOtherPicker,
                                           float inX, float inY ) {
    inX = roundf( inX );
    
    if( inPicker->draw && inPicker->held ) {

        // preserve relative mouse-picker offset, so picker doesn't jump
        // to center itself on mouse as it moves
        float x = inX - inPicker->heldOffset;
        
        if( !inPicker->draggedInYet ) {
            if( x > mColumnPositions[5].x + columnPickerStartingOffset ) {
                
                x = mColumnPositions[5].x + columnPickerStartingOffset;

                if( inX > x ) {
                    inX = x;
                    }
                }
            else if( x < mColumnPositions[0].x - columnPickerStartingOffset ) {

                x = mColumnPositions[0].x - columnPickerStartingOffset;
                
                if( inX < x ) {
                    inX = x;
                    }
                }
            else if( x <= mColumnPositions[5].x &&
                     x >= mColumnPositions[0].x ) {
                inPicker->draggedInYet = true;
                }
            }
        else {    
            if( x > mColumnPositions[5].x ) {
                x = mColumnPositions[5].x;
                if( inX > x ) {
                    inX = x;
                    }
                }
            else if( x < mColumnPositions[0].x ) {
                x = mColumnPositions[0].x;
                if( inX < x ) {
                    inX = x;
                    }
                }
            }
        
        inPicker->pos.x = x;
        inPicker->heldOffset = inX - inPicker->pos.x;
        
        // closest useable
        int closestColumn = 0;
        float closestDist = 300000;
        
        // closest actual, even though it may be unpickable now
        int trueClosestColumn = 0;
        float trueClosestDist = 300000;
        
        char blockedColumns[6];

        if( mPickerUs.draw && ! mPickerThem.draw ) {
            memset( blockedColumns, false, 6 );
            // picking reveal, our-picks-for-them are blocked
            for( int p=0; p<3; p++ ) {
                int t = p * 2 + 1;
                blockedColumns[ mOurChoices[t] ] = true;
                }
            }
        else {
            // only unpicked allowed
            memcpy( blockedColumns, mColumnUsed, 6 );
            }
        
        
        for( int i=0; i<6; i++ ) {
            float dist = mColumnPositions[i].x - x;
            // square as a rough abs
            dist *= dist;
            if( dist < closestDist && ! blockedColumns[i] ) {
                closestDist = dist;
                closestColumn = i;
                }
            if( dist < trueClosestDist ) {
                trueClosestDist = dist;
                trueClosestColumn = i;
                }
            }
        
        int oldTarget = inPicker->targetColumn;
        int oldTrueClosest = inPicker->trueClosestColumn;
        
        inPicker->targetColumn = closestColumn;
        inPicker->trueClosestColumn = trueClosestColumn;
        
        if( inPicker == &mPickerUs ) {
            if( ! mPickerThem.draw ) {
                // reveal step
                mRevealChoiceForUs = closestColumn;
                }
            else {
                mColumnChoiceForUs = closestColumn;
                }
            }
        else {
            mColumnChoiceForThem = closestColumn;
            }
        

        

        float delta = mColumnPositions[closestColumn].x - x;

        if( delta == 0 ) {
            // an exact mouse movement that puts delta at 0 can cause
            // our loop to run forever below
            delta = 1;
            }

        if( inOtherPicker->draw && 
            closestColumn == inOtherPicker->targetColumn ) {
            // push it out of the way

            while( blockedColumns[ inOtherPicker->targetColumn ] ||
                   closestColumn == inOtherPicker->targetColumn ) {
                
                if( delta > 0 ) {
                    if( inOtherPicker->targetColumn > 0 ) {
                        inOtherPicker->targetColumn --;
                        }
                    else {
                        inOtherPicker->targetColumn ++;
                        delta = -delta;
                        }
                    }
                else {
                    if( inOtherPicker->targetColumn < 5 ) {
                        inOtherPicker->targetColumn ++;
                        }
                    else {
                        inOtherPicker->targetColumn --;
                        delta = -delta;
                        }
                    }
                }    
            }
        else if( inOtherPicker->draw &&
                 closestColumn != inOtherPicker->lastPlayerDropColumn ) {
            // other picker can go back to where player last dropped it,
            // because it's clear now
            inOtherPicker->targetColumn = inOtherPicker->lastPlayerDropColumn;
            }


        if( inOtherPicker == &mPickerUs ) {
            if( ! mPickerThem.draw ) {
                // reveal step
                mRevealChoiceForUs = inOtherPicker->targetColumn;
                }
            else {
                mColumnChoiceForUs = inOtherPicker->targetColumn;
                }
            }
        else {
            mColumnChoiceForThem = inOtherPicker->targetColumn;
            }


        
        if( closestColumn != oldTarget || 
            trueClosestColumn != oldTrueClosest ) {
            
            inPicker->hardScoreUpdate = false;
            
            // don't jump score graph to match closestColumn if
            // we're currently dragging over an unpickable column, because
            // that's confusing
            if( trueClosestColumn == closestColumn ) {
            
                // jump score graph in realtime as we drag, but only if cached
                computePossibleScores( true );
                }
            }
        }
    else if( inPicker->draw && ! inOtherPicker->held
        && inX > inPicker->pos.x - 22
        && inX < inPicker->pos.x + 22
        && inY > inPicker->pos.y - 32
        && inY < inPicker->pos.y + 32 ) {

        if( mMouseOverSquareLocked ) {
            mMouseOverTheirTurnNumber = -1;
            mMouseOverTheirRow = -1;
            mMouseOverTheirColumn = -1;

            mMouseOverOurColumn = -1;
            mMouseOverOurRow = -1;
            mMouseOverSquareLocked = false;
            
            computePossibleScores( true );
            }

        if( inOtherPicker->targetColumn == -1
            && inX > inOtherPicker->pos.x - 22
            && inX < inOtherPicker->pos.x + 22
            && inY > inOtherPicker->pos.y - 32
            && inY < inOtherPicker->pos.y + 32 ) {
            
            // if we're over both, and one is off end, give primacy
            // to one that is off the end
            inPicker->mouseOver = false;
            }
        else {
            inPicker->mouseOver = true;
            }
        }
    else {
        inPicker->mouseOver = false;
        }

    }




void PlayGamePage::pointerMove( float inX, float inY ) {
    lastMousePos.x = inX;
    lastMousePos.y = inY;
    
    leftEnd = 1;
    rightEnd = 1;

    mScorePipLabelFadeDelta = -1;


    pickerReactToMouseMove( &mPickerUs, &mPickerThem, inX, inY );
    pickerReactToMouseMove( &mPickerThem, &mPickerUs, inX, inY );


    
    if( mParchmentFade == 0 && !mMouseOverSquareLocked ) {
        
        int oldMouseOverTheirRow = mMouseOverTheirRow;
        mMouseOverTheirRow = -1;

        int oldMouseOverTheirColumn = mMouseOverTheirColumn;
        mMouseOverTheirColumn = -1;

        mMouseOverTheirTurnNumber = -1;

        int oldMouseOverOurRow = mMouseOverOurRow;
        mMouseOverOurRow = -1;

        int oldMouseOverOurColumn = mMouseOverOurColumn;
        mMouseOverOurColumn = -1;

        
        int numTheirWon = 0;
        int numOurWon = 0;
        for( int i=0; i<3; i++ ) {
            if( mOurWonSquares[i] != -1 ) {
                numOurWon++;
                }
            if( mTheirWonSquares[i] != -1 ) {
                numTheirWon++;
                }
            }


        for( int c=0; c<6; c++ ) {
            if( inX >= mColumnPositions[c].x - 27 &&
                inX <= mColumnPositions[c].x + 27 ) {
            
                char hitsUsedColumn = false;
                
                for( int i=0; i<3; i++ ) {
                    if( c == mOurChoices[ i * 2 + 1 ] 
                        ||
                        ( mOurChoices[ i * 2 + 1 ] == -1 
                          && 
                          c == mColumnChoiceForThem ) ) {
                
                        hitsUsedColumn = true;
    
                        // mouse over a column that we gave them
                    
                        for( int r=0; r<6; r++ ) {
                            if( inY >= mRowPositions[r].y - 27 &&
                                inY <= mRowPositions[r].y + 27 ) {
                    
                                char rowBlocked = false;

                                for( int j=0; j<6; j++ ) {
                                    if( r == mTheirChoices[j] ) {
                                        rowBlocked = true;
                                        break;
                                        }
                                    }
                            
                                if( !rowBlocked ) {
                                    // a possible row that they gave
                                    // themselves
                                    mMouseOverTheirColumn = c;
                                    mMouseOverTheirRow = r;
                                    mMouseOverTheirTurnNumber = i;
                                    }
                            

                                break;
                                }
                            }
                    
                        break;
                        }
                    else if( c == mColumnChoiceForUs ) {
                        
                        hitsUsedColumn = true;

                        // mouse over a column that we are about to give
                        // ourself
                        
                        for( int r=0; r<6; r++ ) {
                            if( inY >= mRowPositions[r].y - 27 &&
                                inY <= mRowPositions[r].y + 27 ) {
                    
                                char rowBlocked = false;

                                for( int j=0; j<6; j++ ) {
                                    if( r == mTheirChoices[j] ) {
                                        rowBlocked = true;
                                        break;
                                        }
                                    }
                            
                                if( !rowBlocked ) {
                                    // a possible row that they will
                                    // give us
                                    mMouseOverOurRow = r;
                                    }
                            

                                break;
                                }
                            }
                    
                        break;
                        }
                    else if( numOurWon == 3 && numTheirWon == 0 &&
                             mRevealChoiceForUs == -1 && 
                             c == mOurChoices[ i * 2 ] ) {
                        
                        hitsUsedColumn = true;

                        // pre-reveal, allow them to mouse over
                        // possible reveals for self
                        
                        for( int r=0; r<6; r++ ) {
                            if( inY >= mRowPositions[r].y - 27 &&
                                inY <= mRowPositions[r].y + 27 ) {
                                
                                int squareI = r * 6 + c;
                                
                                for( int i=0; i<3; i++ ) {
                                    if( mOurWonSquares[i] == squareI ) {
                                        mMouseOverOurRow = r;
                                        break;
                                        }
                                    }
                                
                                break;
                                }
                            }
                        break;
                        }
                    }
                
                

                if( mColumnChoiceForUs == -1 && !hitsUsedColumn ) {
                    
                    for( int i=0; i<3; i++ ) {
                        if( c == mOurChoices[ i * 2 ] ) {
                            hitsUsedColumn = true;
                            break;
                            }
                        }
                    }
                
                if( mColumnChoiceForUs == -1 && !hitsUsedColumn ) {

                    // falls in a totally unmarked column
                    
                    // treat mouse-over unmarked as if we are giving
                    // this square to ourselves to see what happens
                    
                    for( int r=0; r<6; r++ ) {
                        if( inY >= mRowPositions[r].y - 27 &&
                            inY <= mRowPositions[r].y + 27 ) {
                            
                            char rowBlocked = false;
                            
                            for( int j=0; j<6; j++ ) {
                                if( r == mTheirChoices[j] ) {
                                    rowBlocked = true;
                                    break;
                                    }
                                }
                            
                            if( !rowBlocked ) {
                                // a possible row that they will
                                // give us, in a column that we haven't 
                                // picked yet
                                
                                mMouseOverOurColumn = c;
                                mMouseOverOurRow = r;
                                }
                            

                            break;
                            }
                        }

                    }
                
                
                break;
                }
            }
            
        if( mMouseOverTheirRow != oldMouseOverTheirRow ||
            mMouseOverTheirColumn != oldMouseOverTheirColumn ||
            mMouseOverOurColumn != oldMouseOverOurColumn ||
            mMouseOverOurRow != oldMouseOverOurRow ) {
            computePossibleScores( true );
            }
        }
    

        
    
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



void PlayGamePage::pointerDown( float inX, float inY ) {
    
    if( mPickerUs.held || mPickerThem.held ) {
        // picker already held when this button pressed
        // this could be an extra press of a second or third mouse button,
        // and might occur outside the picker's boundaries, causing 
        // the picker to be dropped
        
        // also, this can allow two pickers to be dragged together strangely
        
        // ignore it
        return;
        }

    ColumnPicker *dominantPicker = &mPickerUs;
    ColumnPicker *nonDominantPicker = &mPickerThem;
    
    if( mPickerUs.targetColumn != -1 &&
        mPickerThem.targetColumn == -1 ) {
        dominantPicker = &mPickerThem;
        nonDominantPicker = &mPickerUs;
        }
    


    if( dominantPicker->draw 
        && inX > dominantPicker->pos.x - 22
        && inX < dominantPicker->pos.x + 22
        && inY > dominantPicker->pos.y - 32
        && inY < dominantPicker->pos.y + 32 ) {
        dominantPicker->held = true;
        dominantPicker->heldOffset = roundf(inX) - dominantPicker->pos.x;
        }
    else {
        dominantPicker->held = false;
        }

    // never let both be held at same time
    if( dominantPicker->held ) {
        return;
        }
    
    if( nonDominantPicker->draw 
        && inX > nonDominantPicker->pos.x - 22
        && inX < nonDominantPicker->pos.x + 22
        && inY > nonDominantPicker->pos.y - 32
        && inY < nonDominantPicker->pos.y + 32 ) {
        nonDominantPicker->held = true;
        nonDominantPicker->heldOffset = roundf(inX) - nonDominantPicker->pos.x;
        }
    else {
        nonDominantPicker->held = false;
        }
    }




void PlayGamePage::pointerUp( float inX, float inY ) {
    if( mPickerUs.held || mPickerThem.held ) {
        // dropping a picker
    
        if( mPickerUs.held ) {
            mPickerUs.draggedInYet = true;
            mPickerUs.mouseOver = false;
            mPickerUs.lastPlayerDropColumn = mPickerUs.targetColumn;
            }
        if( mPickerThem.held ) {
            mPickerThem.draggedInYet = true;
            mPickerThem.mouseOver = false;
            mPickerThem.lastPlayerDropColumn = mPickerThem.targetColumn;
            }


        // both pickers remember where they are now, because player
        // just dropped (player only dropped one, but a drop of either
        // sets both, so that the un-touched one doesn't jump unexpectedly 
        // later
        mPickerUs.lastPlayerDropColumn = mPickerUs.targetColumn;
        mPickerThem.lastPlayerDropColumn = mPickerThem.targetColumn;
        

        if( mPickerUs.draggedInYet && mPickerThem.draggedInYet ) {
            
            if( ! mCommitButtonJustPressed ) {
                // commit allowed now
                mCommitButton.setVisible( true );
                mCommitFlashPreSteps = 0;
                mCommitFlashProgress = 1.0;
                mCommitFlashDirection = -1;
                
                mLeaveButton.setVisible( false );
                mLeaveConfirmButton.setVisible( false );
                }
            }
        }
    
    mPickerUs.held = false;
    mPickerThem.held = false;

    // don't allow re-locking of square picks once fade-out has started
    if( mParchmentFade == 0 && 
        ( mMouseOverTheirRow != -1 || mMouseOverOurRow != -1 ) ) {
        
        // allow to change and relock if clicked when locked
        if( mMouseOverSquareLocked ) {
            
            mMouseOverSquareLocked = false;
            pointerMove( inX, inY );
            
            if( mMouseOverTheirRow != -1  || mMouseOverOurRow != -1 ) {
                
                mMouseOverSquareLocked = true;
                }
            }
        else {
            mMouseOverSquareLocked = true;
            }
        }
    }


static char pickingThemNext = false;



void PlayGamePage::keyDown( unsigned char inASCII ) {
    
    if( inASCII == 10 || inASCII == 13 ) {
        if( mCommitButton.isVisible() ) {
            actionPerformed( &mCommitButton );
            }
        else if( mBetButton.isVisible() ) {
            actionPerformed( &mBetButton );
            }
        }

    else if( inASCII == 'f' || inASCII == 'F' ) {
        if( mFoldButton.isVisible() ) {
            actionPerformed( &mFoldButton );
            }
        }

    
    else if( inASCII >= '1' && inASCII <= '6' ) {
        // column pick
        int colNumber = (int)( inASCII - '1' );
        
        char blockedColumns[6];
        
        if( mPickerUs.draw && ! mPickerThem.draw ) {
            memset( blockedColumns, false, 6 );
            // picking reveal, our-picks-for-them are blocked
            for( int p=0; p<3; p++ ) {
                int t = p * 2 + 1;
                blockedColumns[ mOurChoices[t] ] = true;
                }
            }
        else {
            // only unpicked allowed
            memcpy( blockedColumns, mColumnUsed, 6 );
            }

        if( mPickerUs.draw && mPickerThem.draw &&
            !mPickerUs.held && !mPickerThem.held ) {
            
            if( mColumnChoiceForUs == -1  || ! pickingThemNext ) {
                if( ! blockedColumns[ colNumber ] && 
                    mColumnChoiceForThem != colNumber ) {
                    
                    mColumnChoiceForUs = colNumber;
                    mPickerUs.targetColumn = colNumber;
                    mPickerUs.trueClosestColumn = colNumber;
                    mPickerUs.lastPlayerDropColumn = colNumber;
                    mPickerUs.draggedInYet = true;

                    computePossibleScores( true );
                    
                    pickingThemNext = true;

                    mCommitFlashPreSteps = 0;
                    mCommitFlashProgress = 1.0;
                    mCommitFlashDirection = -1;
                    
                    mCommitButton.setNoHoverColor( 1, 1, 1, 
                                                   mCommitFlashProgress );
                    }
                }
            else if( pickingThemNext ) {
                if( ! blockedColumns[ colNumber ]  && 
                    mColumnChoiceForUs != colNumber ) {
                    
                    mColumnChoiceForThem = colNumber;
                    mPickerThem.targetColumn = colNumber;
                    mPickerThem.trueClosestColumn = colNumber;
                    mPickerThem.lastPlayerDropColumn = colNumber;
                    mPickerThem.draggedInYet = true;
                    
                    computePossibleScores( true );
                    
                    pickingThemNext = false;
                    
                    mCommitFlashPreSteps = 0;
                    mCommitFlashProgress = 1.0;
                    mCommitFlashDirection = -1;

                    mCommitButton.setNoHoverColor( 1, 1, 1, 
                                                   mCommitFlashProgress );
                    
                    mCommitButton.setVisible( true );
                    mLeaveButton.setVisible( false );
                    mLeaveConfirmButton.setVisible( false );
                    }
                }
            }
        else if( mPickerUs.draw && 
                 ! mPickerThem.draw && 
                 ! mPickerUs.held ) {
            
            if( ! blockedColumns[ colNumber ] ) {
                mRevealChoiceForUs = colNumber;
                mPickerUs.targetColumn = colNumber;
                mPickerUs.trueClosestColumn = colNumber;
                mPickerUs.lastPlayerDropColumn = colNumber;
                mPickerUs.draggedInYet = true;

                computePossibleScores( true );

                pickingThemNext = false;
                
                mCommitFlashPreSteps = 0;
                mCommitFlashProgress = 1.0;
                mCommitFlashDirection = -1;

                mCommitButton.setNoHoverColor( 1, 1, 1, 
                                               mCommitFlashProgress );
                                    
                mCommitButton.setVisible( true );
                mLeaveButton.setVisible( false );
                mLeaveConfirmButton.setVisible( false );
                }
            }
        }
    
    else if( inASCII == '0' &&
        mColumnChoiceForUs != -1 && 
        mColumnChoiceForThem != -1 ) {
        // swap
        
        int temp = mColumnChoiceForThem;
        mColumnChoiceForThem = mColumnChoiceForUs;
        mColumnChoiceForUs = temp;
        
        mPickerUs.targetColumn = mColumnChoiceForUs;
        mPickerUs.trueClosestColumn = mColumnChoiceForUs;
        mPickerUs.lastPlayerDropColumn = mColumnChoiceForUs;
        
        mPickerThem.targetColumn = mColumnChoiceForThem;
        mPickerThem.trueClosestColumn = mColumnChoiceForThem;    
        mPickerThem.lastPlayerDropColumn = mColumnChoiceForThem;  
        
        computePossibleScores( true );

        mCommitFlashPreSteps = 0;
        mCommitFlashProgress = 1.0;
        mCommitFlashDirection = -1;
                
        mCommitButton.setNoHoverColor( 1, 1, 1, 
                                       mCommitFlashProgress );
        }
    
    

    return;
    
    // these can be enabled for testing

    if( inASCII == 'w' || inASCII == 'W' ) {
        mShowWatercolorDemo = ! mShowWatercolorDemo;
        }
    if( inASCII == 'p' || inASCII == 'p' ) {
        possibleScoreMethod = ! possibleScoreMethod;
        computePossibleScores( false );
        }
    }



void PlayGamePage::specialKeyDown( int inKeyCode ) {
    int betIncrement = 0;
    
    if( inKeyCode == MG_KEY_DOWN ) {
        betIncrement = -1;
        }
    else if( inKeyCode == MG_KEY_UP ) {
        betIncrement = 1;
        }
    else if( inKeyCode == MG_KEY_PAGE_DOWN ) {
        betIncrement = -10;
        }
    else if( inKeyCode == MG_KEY_PAGE_UP ) {
        betIncrement = 10;
        }

    if( betIncrement != 0 && mBetButton.isVisible() ) {
        double old = mBetPicker.getValue();
        
        mBetPicker.setValue( old + betIncrement );
        }


    if( ( inKeyCode == MG_KEY_RIGHT ||
          inKeyCode == MG_KEY_LEFT )
        &&
        mPickerUs.draw && ! mPickerThem.draw ) {
        
        // picking reveal, our-picks-for-them are blocked
        
        char blockedColumns[6];

        memset( blockedColumns, false, 6 );
        for( int p=0; p<3; p++ ) {
            int t = p * 2 + 1;
            blockedColumns[ mOurChoices[t] ] = true;
            }

        int colIncrement;
        
        if( inKeyCode == MG_KEY_RIGHT ) {
            colIncrement = 1;
            }
        else if( inKeyCode == MG_KEY_LEFT ) {
            colIncrement = -1;
            }
        

        int newCol = mRevealChoiceForUs + colIncrement;

        while( newCol < 6 && newCol >= 0 && blockedColumns[ newCol ] ) {
            newCol += colIncrement;
            }
            
        if( newCol >= 0 && newCol < 6 && ! blockedColumns[ newCol ] ) {
            mRevealChoiceForUs = newCol;
            mPickerUs.targetColumn = newCol;
            mPickerUs.trueClosestColumn = newCol;
            mPickerUs.lastPlayerDropColumn = newCol;
            mPickerUs.draggedInYet = true;

            computePossibleScores( true );

            pickingThemNext = false;
                
            mCommitFlashPreSteps = 0;
            mCommitFlashProgress = 1.0;
            mCommitFlashDirection = -1;

            mCommitButton.setNoHoverColor( 1, 1, 1, 
                                           mCommitFlashProgress );
                                    
            mCommitButton.setVisible( true );
            mLeaveButton.setVisible( false );
            mLeaveConfirmButton.setVisible( false );
            }
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




void PlayGamePage::addColumnStroke( int inColumn, SpriteHandle inSprite[6],
                                    char inSpeedUpStart, 
                                    char inScoreUpdatePending,
                                    float inGlobalFade ) {
    
    int lastFlyingCoinID = -1;
    
    for( int f=0; f<2; f++ ) {
        if( mFlyingCoins[f].size() > 0 ) {
            PendingFlyingCoin *coin = 
                mFlyingCoins[f].getElement( mFlyingCoins[f].size() - 1 );
            
            if( coin->id > lastFlyingCoinID ) {
                lastFlyingCoinID = coin->id;
                }
            }
        }
    
        

    doublePair subPos = mColumnPositions[inColumn];
    subPos.y += 64 + 64 + 32;
    
    for( int i=0; i<6; i++ ) {
        
        float leftEnd = 1;
        float rightEnd = 1;
        
        if( inSpeedUpStart ) {
            
            if( i == 0 ) {
                // speed up start of stroke drawing, because it contains
                // empty space
                leftEnd = 0;
                rightEnd = 0.5;
                }
            if( i == 1 ) {
                // have left end of second segment match right end of first
                leftEnd = 0.5;
                }
            }

        char scoreUpdate = false;
        if( inScoreUpdatePending && i == 5 ) {
            scoreUpdate = true;
            }

        WatercolorStroke stroke = { subPos,
                                    inSprite[i], true, leftEnd, rightEnd,
                                    inGlobalFade,
                                    lastFlyingCoinID,
                                    scoreUpdate };
    
        mWatercolorStrokes.push_back( stroke );
        
        subPos.y -= 64;
        }
    }

    

void PlayGamePage::addRowStroke( int inRow, SpriteHandle inSprite[6],
                                 char inSpeedUpStart, 
                                 char inScoreUpdatePending,
                                 float inGlobalFade ) {
    
    int lastFlyingCoinID = -1;
    
    for( int f=0; f<2; f++ ) {
        if( mFlyingCoins[f].size() > 0 ) {
            PendingFlyingCoin *coin = 
                mFlyingCoins[f].getElement( mFlyingCoins[f].size() - 1 );
            
            if( coin->id > lastFlyingCoinID ) {
                lastFlyingCoinID = coin->id;
                }
            }
        }


    doublePair subPos = mRowPositions[inRow];
    subPos.x -= 64 + 64 + 32;

    for( int i=0; i<6; i++ ) {
        float leftEnd = 1;
        float rightEnd = 1;
        
        if( inSpeedUpStart ) {
            
            if( i == 0 ) {
                // speed up start of stroke drawing, because it contains
                // empty space
                leftEnd = 0;
                rightEnd = 0.5;
                }
            if( i == 1 ) {
                // have left end of second segment match right end of first
                leftEnd = 0.5;
                }
            }
        
        char scoreUpdate = false;
        if( inScoreUpdatePending && i == 5 ) {
            scoreUpdate = true;
            }

        WatercolorStroke stroke = { subPos,
                                    inSprite[i], false, leftEnd, rightEnd,
                                    inGlobalFade,
                                    lastFlyingCoinID,
                                    scoreUpdate };
        
        mWatercolorStrokes.push_back( stroke );
        
        subPos.x += 64;
        }
    }










