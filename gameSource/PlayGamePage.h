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
        
    } PendingFlyingCoin;



typedef struct WatercolorStroke {
        doublePair pos;
        
        SpriteHandle sprite;
        
        char vertical;

        // fade-in progress, full strength when both ends hit 0
        // note that these represent top and bottom ends when vertical
        float leftEnd;
        float rightEnd;

    } WatercolorStroke;





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
        
        virtual void keyDown( unsigned char inASCII );
        
    protected:
        
        char mRunning;

        int *mGameBoard;
        
        // ours first
        int mPlayerCoins[2];
        int mPotCoins[2];
        
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


        TextButton mCommitButton;
        TextButton mBetButton;
        TextButton mFoldButton;
        TextButton mLeaveButton;

        NumberPicker mBetPicker;
        
        int mCommitFlashPreSteps;
        float mCommitFlashProgress;
        float mCommitFlashDirection;

        TextButton *mColumnButtons[6];


        int mColumnChoiceForUs;
        int mColumnChoiceForThem;

        int mRevealChoiceForUs;
        

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


        void computePossibleScores();

        SpriteHandle mScorePipSprite;
        SpriteHandle mScorePipExtraSprite;
        SpriteHandle mScorePipEmptySprite;

        char mShowWatercolorDemo;

        SpriteHandle mParchmentSprite;
        SpriteHandle mRedWatercolorSprite;
        SpriteHandle mBlueWatercolorSprite;
        SpriteHandle mInkGridSprite;
        
        SpriteHandle mInkNumberSprites[36];

        int mNextGreenVSprite;
        int mNextGreenHSprite;
        SpriteHandle mGreenWatercolorVSprites[3][6];
        SpriteHandle mGreenWatercolorHSprites[3][6];

        int mNextRedVSprite;
        SpriteHandle mRedWatercolorVSprites[3][6];
        
        SpriteHandle mBlackWatercolorVSprites[6][6];
        SpriteHandle mBlackWatercolorHSprites[6][6];

        doublePair mInkGridCenter;
        
        // for centering a stroke on a column or row
        doublePair mColumnPositions[6];
        doublePair mRowPositions[6];
        
        SimpleVector<WatercolorStroke> mWatercolorStrokes;
        
        void addColumnStroke( int inColumn, SpriteHandle inSprite[6] );
        void addRowStroke( int inRow, SpriteHandle inSprite[6] );
        

        
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
        
    };
