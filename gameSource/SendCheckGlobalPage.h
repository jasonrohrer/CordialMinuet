#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"



#define NUM_SEND_CHECK_GLOBAL_FIELDS 7


class SendCheckGlobalPage : public ServerActionPage, public ActionListener {
        
    public:
        SendCheckGlobalPage();
        
        ~SendCheckGlobalPage();

        void clearFields();        
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        virtual void makeNotActive();
        

        // for TAB and ENTER (switch fields and start server action)
        virtual void keyDown( unsigned char inASCII );
        
        // for arrow keys (switch fields)
        virtual void specialKeyDown( int inKeyCode );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        

        double getWithdrawalAmount();
        
    protected:

        NumberPicker mAmountPicker;
        
        TextField mCountryField;
        TextField mNameField;
        TextField mAddress1Field;
        TextField mAddress2Field;
        TextField mCityField;
        TextField mProvinceField;
        TextField mPostalCodeField;
        
        char mCountryVerified;
        
        void verifyCountry();


        TextField *mFields[NUM_SEND_CHECK_GLOBAL_FIELDS];
        

        TextButton mSendCheckButton;

        TextButton mCancelButton;

        
        
        // -1 to move up
        void switchFields( int inDir = 1 );

        void makeFieldsActive();

        void checkIfSendCheckButtonVisible();
                
    };
