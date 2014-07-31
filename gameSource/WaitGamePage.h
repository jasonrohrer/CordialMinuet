#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"





class WaitGamePage : public ServerActionPage, public ActionListener {
        
    public:
        WaitGamePage();
        
        ~WaitGamePage();
        
        
        virtual void actionPerformed( GUIComponent *inTarget );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        
    protected:

        TextButton mCancelButton;

        
        

        void makeFieldsActive();

        void checkIfCreateButtonVisible();
                
        
    };
