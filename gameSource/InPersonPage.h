#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"





class InPersonPage : public ServerActionPage, public ActionListener {
        
    public:
        InPersonPage();
        
        ~InPersonPage();
        
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        virtual void makeNotActive();
        

        // for TAB and ENTER (switch fields and start server action)
        virtual void keyDown( unsigned char inASCII );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        

        double getWithdrawalAmount();
        
    protected:
        
        TextField mCodeField;
        

        TextButton mStartButton;

        
        

        void makeFieldsActive();

        void checkIfStartButtonVisible();
                
        
    };
