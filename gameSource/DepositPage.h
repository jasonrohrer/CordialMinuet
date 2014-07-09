#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"



#define NUM_DEPOSIT_FIELDS 5


class DepositPage : public ServerActionPage, public ActionListener {
        
    public:
        DepositPage();
        
        ~DepositPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        // for TAB and ENTER (switch fields and start login)
        virtual void keyDown( unsigned char inASCII );
        
        // for arrow keys (switch fields)
        virtual void specialKeyDown( int inKeyCode );
        
    protected:
        
        TextField mEmailField;
        TextField mCardNumberField;
        TextField mExpireMonthField;
        TextField mExpireYearField;
        TextField mCVCField;
        

        TextField *mFields[NUM_DEPOSIT_FIELDS];
        

        TextButton mDepositeButton;

        TextButton mCancelButton;

        
        // -1 to move up
        void switchFields( int inDir = 1 );
    };
