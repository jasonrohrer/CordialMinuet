#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"


class WithdrawPage : public ServerActionPage, public ActionListener {
        
    public:
        WithdrawPage();
        
        ~WithdrawPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );

        virtual void step();

        virtual void draw( doublePair inViewCenter, double inViewSize );
        

    protected:
        
        TextButton mSendCheckButton;
        TextButton mAccountTransferButton;
        TextButton mCancelButton;

        double mCheckCost;
        double mTransferCost;


    };
