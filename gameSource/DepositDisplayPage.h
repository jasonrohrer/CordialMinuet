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
        

        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();

        // always positive
        void setDeltaAmount( double inAmount );

    protected:
        
        char mWithdraw;

        TextButton mOkayButton;

        double mOldBalance;
        double mDeltaAmount;



    };
