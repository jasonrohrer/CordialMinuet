#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"



typedef enum GameMessageState {
    responseProcessed,
    gettingState,
    gettingStatePostMove,
    gettingStatePostBet,
    sendingMove,
    sendingBet,
    sendingFold,
    sendingEnd,
    waitingMove,
    waitingBet,
    waitingEnd
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
        
        char ourPossibleScores[106];
        char theirPossibleScores[106];
        
        char ourPossibleScoresFromTheirPerspective[106];

    } PossibleScoreCacheRecord;



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
        
        
    protected:
        
        int *mGameBoard;
        
        // ours first
        int mPlayerCoins[2];
        int mPotCoins[2];


        TextButton mCommitButton;
        TextButton mBetButton;
        TextButton mFoldButton;

        NumberPicker mBetPicker;
        

        TextButton *mColumnButtons[6];


        int mColumnChoiceForUs;
        int mColumnChoiceForThem;

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
    };
