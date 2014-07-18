#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"


class DepositDisplayPage : public ServerActionPage, public ActionListener {
        
    public:
        DepositDisplayPage();
        
        ~DepositDisplayPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();

        void setDepositAmount( double inAmount );

    protected:
        
        TextButton mOkayButton;

        double mOldBalance;
        double mDepositAmount;



    };
