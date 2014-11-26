#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "NumberPicker.h"





class InPersonPage : public ServerActionPage, public ActionListener {
        
    public:
        InPersonPage();
        
        ~InPersonPage();
        
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        

        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        

        double getWithdrawalAmount();
        
    protected:
        
        NumberPicker mCodePicker;
        

        TextButton mStartButton;

        
        

        void checkIfStartButtonVisible();
                
        
    };
