#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"





class CreateGamePage : public ServerActionPage, public ActionListener {
        
    public:
        CreateGamePage();
        
        ~CreateGamePage();
        
        void clearAmountPicker();
        
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        

        // for TAB and ENTER (switch fields and start server action)
        virtual void keyDown( unsigned char inASCII );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        
    protected:

        NumberPicker mAmountPicker;
        

        TextButton mAmuletGameButton;

        TextButton mCreateButton;
        TextButton mCreateExpButton;

        TextButton mCancelButton;

        TextButton mDropAmuletButton;
        TextButton mDropAmuletConfirmButton;
        
        

        void makeFieldsActive();

        void checkIfCreateButtonVisible();
                
        
    };
