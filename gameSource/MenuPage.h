#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"
#include "minorGems/util/SimpleVector.h"


#include "TextButton.h"


typedef struct GameRecord {
        double dollarAmount;
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
        
        double getJoinedGameDollarAmount();

    protected:
        
        TextButton mDepositButton;
        TextButton mWithdrawButton;
        TextButton mNewGameButton;

        TextButton mPrevButton;
        TextButton mNextButton;
        
        SimpleVector<GameRecord> mListedGames;
        
        int mLimit;
        int mSkip;

        char mResponseProcessed;
        
        SimpleVector<TextButton*> mGameButtons;
        
        void clearListedGames();


        double mJoinedGameDollarAmount;
    };
