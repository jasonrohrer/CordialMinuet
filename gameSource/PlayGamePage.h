#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"
#include "minorGems/game/game.h"
#include "minorGems/util/SimpleVector.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"



typedef enum GameMessageState {
    responseProcessed,
    gettingState,
    gettingStatePostMove,
    gettingStatePostBet,
    gettingStateAtEnd,
    sendingMove,
    sendingBet,
    sendingFold,
    sendingEnd,
    sendingStartNext,
    waitingMove,
    waitingBet,
    waitingEnd,
    waitingStartNext
    } GameMessageState;



#define MAX_SCORE_RANGE 106
#define NUM_CACHE_RECORDS 43

typedef struct PossibleScoreCacheRecord {
        // -1 if record not populated
        int recordAge;
        
        int ourChoices[6];
        int theirChoices[6];
        
        int columnChoiceForUs;
        int columnChoiceForThem;
        
        int revealChoiceForUs;

        char ourPossibleScores[106];
        char theirPossibleScores[106];
        
        char ourPossibleScoresFromTheirPerspective[106];

    } PossibleScoreCacheRecord;



// spot on the screen where a coin icon is displayed
// endpoint for flying coins
typedef struct CoinSpot {
        doublePair position;
        
        // pointer to a PlayGamePage member variable that 
        // counts (and is used to display) the coins here
        int *coinCount;
        
        } CoinSpot;


typedef struct PendingFlyingCoin {
        // these can be NULL if this is a pause between coin flights
        CoinSpot *start;
        CoinSpot *dest;
        
        float progress;
        
        int value;

        // an incremented ID unique to each coin
        int id;

        char soundPlayed;
        
    } PendingFlyingCoin;



typedef struct WatercolorStroke {
        doublePair pos;
        
        SpriteHandle sprite;
        
        char vertical;

        // fade-in progress, full strength when both ends hit 0
        // note that these represent top and bottom ends when vertical
        float leftEnd;
        float rightEnd;

        // to lighten or darken overall
        float globalFade;


        // ID of last coin that was pending when this stroke was added
        // this stroke will wait for it to finish flying before
        // drawing
        // can be -1
        int waitingForCoinIDToFinish;

        // flag for whether a computePossibleScores operation is pending
        // the drawing of this stroke
        char computePossibleScoresPending;
        
    } WatercolorStroke;




typedef struct ColumnPicker {
        doublePair pos;
        int targetColumn;
        
        // perhaps a blocked column that the picker
        // can't go to
        int trueClosestColumn;
        

        // tracks where player last put this picker
        // targetColumn may change as picker gets out of way of other
        // picker, but it will return to lastPlayerDropColumn whenever
        // it can, until the player moves it
        int lastPlayerDropColumn;
        

        // has a non-cached possible-score update been 
        // done for this target column?
        char hardScoreUpdate;
        
        char draggedInYet;

        char mouseOver;
        char held;
        char draw;

        // if mouse click not in center of picker, picker shouldn't
        // leap to center on mouse when mouse moves
        double heldOffset;
    } ColumnPicker;




class PlayGamePage : public ServerActionPage, public ActionListener {
        
    public:
        PlayGamePage();
        
        ~PlayGamePage();
        

        virtual void makeActive( char inFresh );

    
        virtual void actionPerformed( GUIComponent *inTarget );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();

        
        // override from GamePage to hide orange warning color
        // (because passing our 1/2 retry time is normal when we're waiting)
        virtual char noWarningColor() {
            return true;
            }
        
        virtual void pointerMove( float inX, float inY );
        virtual void pointerDown( float inX, float inY );
        virtual void pointerUp( float inX, float inY );
        
        virtual void keyDown( unsigned char inASCII );
        
        virtual void specialKeyDown( int inKeyCode );

    protected:
        
        char mRunning;

        int *mGameBoard;
        
        int mGameType;

        // ours first
        int mPlayerCoins[2];
        int mPotCoins[2];
        
        char mHideLeavePenalty;
        int mLeavePenalty;

        SpriteHandle mCoinSprite;
        SpriteHandle mCoinTenSprite;

        CoinSpot mPlayerCoinSpots[2];
        CoinSpot mPotCoinSpots[2];

        CoinSpot mHouseCoinSpot;
        CoinSpot mOpponentGoneCoinSpot;
        

        // two queues of flying coins that can move in parallel, 
        // at most one coin from each queue
        SimpleVector<PendingFlyingCoin> mFlyingCoins[2];

        // counts all coins, including those flying in transit
        int getNetPlayerCoins( int inPlayerNumber );
        int getNetPotCoins( int inPlayerNumber );
        


        time_t mMoveDeadline;
        float mMoveDeadlineFade;
        float mMoveDeadlineFadeDelta;

        // time how long we've been waiting for opponent's move
        // (only chime when we finally receive move if we've been waiting
        // for a while)
        time_t mWaitingStartTime;
        
        // track whether time running out chime has played yet
        char mChimePlayed;
        


        TextButton mCommitButton;

        // watch for pressing commit button while still dragging a slider
        // (dropping a slider makes commit button become visible again)
        char mCommitButtonJustPressed;

        TextButton mBetButton;
        TextButton mFoldButton;
        TextButton mLeaveButton;
        TextButton mLeaveConfirmButton;

        NumberPicker mBetPicker;
        
        int mCommitFlashPreSteps;
        float mCommitFlashProgress;
        float mCommitFlashDirection;

        TextButton *mColumnButtons[6];


        int mColumnChoiceForUs;
        int mColumnChoiceForThem;

        int mRevealChoiceForUs;
        
        // if mousing over a column that we gave to them
        // can update possible score graph
        int mMouseOverTheirTurnNumber;
        int mMouseOverTheirColumn;
        int mMouseOverTheirRow;
        
        // note that mMouseOverOurColumn is no used if mColumnChoiceForUs is 
        // set or during pre-reveal click, because row is implied in those
        // cases
        int mMouseOverOurColumn;
        int mMouseOverOurRow;
        char mMouseOverSquareLocked;
        
        


        // track bet that hasn't been visually added to pot yet
        int mLastUnflownBet;

        GameMessageState mMessageState;
        

        char mColumnUsed[6];
        char mRowUsed[6];


        // choices are -1 for choices not made yet
        
        // our column choices
        // us, them, us, them, us, them
        int mOurChoices[6];

        // their row choices
        // us, us, us, them, them, them
        // (because we only find out their "them" choices at very end
        int mTheirChoices[6];
        
        
        // square indexes, or -1 for unknown
        int mOurWonSquares[3];
        int mTheirWonSquares[3];
        

        char mOurPossibleScores[MAX_SCORE_RANGE];
        char mTheirPossibleScores[MAX_SCORE_RANGE];
        
        char mOurPossibleScoresFromTheirPerspective[MAX_SCORE_RANGE];

        doublePair mScorePipPositions[MAX_SCORE_RANGE];
        
        int mScorePipToLabel;
        float mScorePipLabelFade;
        float mScorePipLabelFadeDelta;


        void computePossibleScores( char inCachedOnly=false );
        void computePossibleScoresOld( char inCachedOnly=false );
        void computePossibleScoresFast();

        SpriteHandle mScorePipSprite;
        SpriteHandle mScorePipExtraSprite;
        SpriteHandle mScorePipEmptySprite;

        char mShowWatercolorDemo;

        SpriteHandle mParchmentSprite;
        SpriteHandle mInkGridSprite;
        
        SpriteHandle mInkNumberSprites[36];
        SpriteHandle mInkNumberSuitedSprites[36];

        SpriteHandle mInkHebrewSprites[12];

        SpriteHandle mSansHebrewSprites[6];

        int mNextGreenVSprite;
        int mNextGreenHSprite;
        SpriteHandle mGreenWatercolorVSprites[3][6];
        SpriteHandle mGreenWatercolorHSprites[3][6];

        int mNextRedVSprite;
        SpriteHandle mRedWatercolorVSprites[3][6];
        
        SpriteHandle mBlackWatercolorVSprites[6][6];
        SpriteHandle mBlackWatercolorHSprites[6][6];

        SpriteHandle mBlackWatercolorVFlippedSprites[6][6];
        SpriteHandle mBlackWatercolorHFlippedSprites[6][6];
        
        SpriteHandle mColumnPickerSprite;
        SpriteHandle mGuessSpriteRow;
        SpriteHandle mGuessSpriteColumn;
        
        SpriteHandle mColumnHeaderSprite;
        SpriteHandle mRowHeaderSprite;
        
        SpriteHandle mSigilSprite;
        
        SpriteHandle mGreenWatercolorHeaderSprite;
        SpriteHandle mRedWatercolorHeaderSprite;
        


        ColumnPicker mPickerUs, mPickerThem;

        doublePair mInkGridCenter;
        
        // for centering a stroke on a column or row
        doublePair mColumnPositions[6];
        doublePair mRowPositions[6];
        
        SimpleVector<WatercolorStroke> mWatercolorStrokes;
        
        void addColumnStroke( int inColumn, SpriteHandle inSprite[6],
                              char inSpeedUpStart, 
                              char inScoreUpdatePending,
                              float inGlobalFade = 1.0 );

        void addRowStroke( int inRow, SpriteHandle inSprite[6],
                           char inSpeedUpStart, 
                           char inScoreUpdatePending,
                           float inGlobalFade = 1.0 );
        

        
        int mCurrentCacheAge;
        
        PossibleScoreCacheRecord mCacheRecords[ NUM_CACHE_RECORDS ];
        
        void clearCacheRecords();

        // store the current possible scores into the cache
        void storeCacheRecord();

        // loads possible scores from the cache, if a record
        // for this move configuration exists.
        // If not, returns false
        char loadCacheRecord();
        
        

        char mRoundEnding;
        int mRoundEndTime;

        char mRoundStarting;
        int mRoundStartTime;

        // 1 means complete fade
        float mParchmentFade;
        
        char mParchmentFadingOut;
        

        void pickerReactToMouseMove( ColumnPicker *inPicker,
                                     ColumnPicker *inOtherPicker,
                                     float inX, float inY );

        // return 
        // 0 if not sliding
        // 1 if just slid to stop
        // 2 if still has more to go
        int slidePicker( ColumnPicker *inPicker );
        
        void drawColumnPicker( ColumnPicker *inPicker );
    };
