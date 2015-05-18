#ifndef MENU_PAGE_INCLUDED
#define MENU_PAGE_INCLUDED


#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"
#include "minorGems/util/SimpleVector.h"

#include "minorGems/game/game.h"


#include "TextButton.h"


typedef struct GameRecord {
        double dollarAmount;
        

        char isTournament;
        char isExp;
        double tournamentStakes;
        int tournamentSecondsLeft;
        unsigned int referenceSeconds;
        

        TextButton *button;
    } GameRecord;



class MenuPage : public ServerActionPage, public ActionListener {
        
    public:
        MenuPage();
        ~MenuPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
  
        virtual void makeActive( char inFresh );
        
        GameRecord getJoinedGame();

    protected:
        
        TextButton mDepositButton;
        TextButton mWithdrawButton;
        TextButton mNewGameButton;

        TextButton mPrevButton;
        TextButton mNextButton;
        
        TextButton mRefreshButton;

        SimpleVector<GameRecord> mListedGames;

        // 2 if not sure
        // 1 if allowed
        // 0 if not allowed        
        int mAreGamesAllowed;
        
        int mActivePlayerCount;
        char mHidePlayerCount;

        int mLimit;
        int mSkip;

        char mResponseProcessed;
        
        time_t mLastResponseTime;

        SimpleVector<TextButton*> mGameButtons;
        
        void clearListedGames();


        GameRecord mJoinedGame;
    };



#endif
