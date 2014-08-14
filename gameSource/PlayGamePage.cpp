#include "PlayGamePage.h"

#include "buttonStyle.h"
#include "message.h"
#include "whiteSprites.h"



#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"
#include "minorGems/game/drawUtils.h"

#include "minorGems/util/stringUtils.h"



extern Font *mainFont;



const char *gameStatePartNames[8] = { "running", "boardLayout", 
                                      "ourCoins", "theirCoins", 
                                      "ourPotCoins", "theirPotCoins",
                                      "ourMoves", "theirMoves" };

const char *waitMovePartNames[1] = { "status" };


static int cellSize = 70;
static int borderWidth = 2;
    
static int cellCenterStart = ( ( cellSize + borderWidth ) * 5 ) / 2;

static int cellXOffset = cellSize + borderWidth;


static void setUsColor() {
    setDrawColor( 0, 0.75, 0, 1 );
    }

static void setThemColor() {
    setDrawColor( 0.75, 0, 0, 1 );
    }



PlayGamePage::PlayGamePage()
        : ServerActionPage( "get_game_state", 8, gameStatePartNames ),
          mGameBoard( NULL ),
          mCommitButton( mainFont, 0, -288, translate( "commit" ) ),
          mColumnChoiceForUs( -1 ), mColumnChoiceForThem( -1 ),
          mScorePipSprite( loadWhiteSprite( "scorePip.tga" ) ),
          mScorePipExtraSprite( loadWhiteSprite( "scorePipExtra.tga" ) ){
    
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
    

    setButtonStyle( &mCommitButton );
    

    mCommitButton.addActionListener( this );

    clearCacheRecords();
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
    }



void PlayGamePage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }

    mCommitButton.setVisible( false );
    
    if( mGameBoard != NULL ) {
        delete [] mGameBoard;
        mGameBoard = NULL;
        }
    
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
                            mColumnButtons[j]->setLabelText( "x" );
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
                }

            computePossibleScores();
            }
        }

    if( inTarget == &mCommitButton ) {
        
        mCommitButton.setVisible( false );
    
        clearActionParameters();
        
        setActionName( "make_move" );
        setResponsePartNames( -1, NULL );
        
        setActionParameter( "our_column", mColumnChoiceForUs );
        setActionParameter( "their_column", mColumnChoiceForThem );
        
        mColumnButtons[mColumnChoiceForUs]->setVisible( false );
        mColumnButtons[mColumnChoiceForThem]->setVisible( false );

        mMessageState = sendingMove;
        
        startRequest();
        }
    }







void PlayGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    

    
    doublePair pos = { - cellCenterStart, cellCenterStart };
    
    if( mGameBoard != NULL ) {
        
        setDrawColor( 1, 1, 1, 1 );
        
        doublePair center  = {0,0};
        drawSquare( center, 
                    ( (cellSize + borderWidth) * 6 ) /  2 + borderWidth / 2 );
        
        int cellIndex = 0;
        
        for( int y=0; y<6; y++ ) {
            pos.x = -cellCenterStart;
            for( int x=0; x<6; x++ ) {
                
                setDrawColor( 0, 0, 0, 1 );
                
                
                char winningSquare = false;
                
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

                drawSquare( pos, cellSize/2 );

                char *number = autoSprintf( "%d", mGameBoard[y*6 + x] );
            
                // tweak y down a bit as baseline offset for font
                pos.y -= 3;

                if( x == mColumnChoiceForUs ) {
                    setUsColor();
                    }
                else if( x == mColumnChoiceForThem ) {
                    setThemColor();
                    }
                else {    
                    if( ! winningSquare &&
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
        doublePair pos;
        pos.y = - ( 105 * 5 ) / 2;
        pos.x = 300;
        
        for( int i=0; i<105; i++ ) {
            pos.x = 300;
            
            setUsColor();
            if( mOurPossibleScoresFromTheirPerspective[i] ) {
                setDrawFade( 0.75 );
                drawSprite( mScorePipSprite, pos );
                }
            if( mOurPossibleScores[i] ) {
                setDrawFade( 1 );
                pos.x -= 2;
                drawSprite( mScorePipExtraSprite, pos );
                pos.x += 2;
                }
            
            pos.x += 16;

            setThemColor();
            if( mTheirPossibleScores[i] ) {
                drawSprite( mScorePipSprite, pos );
                }
            
            pos.y += 5;
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

        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;



        pos.y = -288;

        number = autoSprintf( "%d", mPlayerCoins[1] );
        
        setThemColor();
                
        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;


        pos.y = -32;
        number = autoSprintf( "%d", mPotCoins[1] );

        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;
        }
    

    //drawMessage( "test messag", pos );    
    }



// returns -1 if the int is ?
int stringToInt( const char *inString ) {
    
    int returnValue = -1;
    
    sscanf( inString, "%d", &returnValue );

    return returnValue;
    }



void PlayGamePage::step() {
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
    else if( mMessageState == gettingState ) {

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
                int theirChoiceIndex = 0;

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
                        
                        mTheirChoices[ theirChoiceIndex ] = theirChoice;
                        theirChoiceIndex++;
                        }
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

        for( int i=0; i<6; i++ ) {
            mColumnButtons[i]->setVisible( ! mColumnUsed[i] );
            mColumnButtons[i]->setLabelText( "+" );
            }
        
        computePossibleScores();

        mMessageState = responseProcessed;
        }
    else if( mMessageState == waitingMove ) {
        char *status = getResponse( "status" );

        if( strcmp( status, "move_ready" ) == 0 ) {
            
            // get the new game state
            setActionName( "get_game_state" );
            setResponsePartNames( 8, gameStatePartNames );

            clearActionParameters();
    
            mMessageState = gettingState;
            
            startRequest();
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










