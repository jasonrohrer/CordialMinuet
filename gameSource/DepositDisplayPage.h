#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"


class DepositDisplayPage : public ServerActionPage, public ActionListener {
        
    public:
        DepositDisplayPage();
        
        ~DepositDisplayPage();
        
        // defaults to displaying post-deposit info
        // set to true to display post-withdrawal info instead.
        void setWithdraw( char inWithdraw );

        void setLeftGame( char inLeftGame );
        // for extra info on left game page
        void setBuyIn( double inBuyIn );

        // for case where vsOne contest is running and we just won/lost 
        // coins that count
        void setVsOneCoins( int inCoins );
        

        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();

        // always positive
        void setDeltaAmount( double inAmount );

    protected:
        
        char mWithdraw;
        char mLeftGame;

        TextButton mOkayButton;

        double mOldBalance;
        double mDeltaAmount;

        double mBuyIn;
        
        int mVsOneCoins;

        SpriteHandle mAmuletIconSprite;
    };
