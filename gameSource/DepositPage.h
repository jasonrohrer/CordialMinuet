#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"



#define NUM_DEPOSIT_FIELDS 5


class DepositPage : public ServerActionPage, public ActionListener {
        
    public:
        DepositPage();
        
        ~DepositPage();


        // defaults to true
        void setEmailFieldCanFocus( char inCanFocus );
        
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        virtual void makeNotActive();
        

        // for TAB and ENTER (switch fields and start login)
        virtual void keyDown( unsigned char inASCII );
        
        // for arrow keys (switch fields)
        virtual void specialKeyDown( int inKeyCode );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        
        // with fee taken out
        double getDepositNetAmount();
        
    protected:

        NumberPicker mAmountPicker;
        
        TextField mEmailField;
        TextField mCardNumberField;
        TextField mExpireMonthField;
        TextField mExpireYearField;
        TextField mCVCField;
        

        TextField *mFields[NUM_DEPOSIT_FIELDS];

        char mEmailFieldCanFocus;
        
        TextField *mLastFocusedField;
        

        TextButton mDepositButton;

        TextButton mCancelButton;
        TextButton mClearButton;
        TextButton mAtSignButton;

        unsigned char mPublicKey[32];
        unsigned char mSecretKey[32];

        unsigned char mSharedSecretKey[32];
        
        
        // -1 to move up
        void switchFields( int inDir = 1 );

        void makeFieldsActive();

        void checkIfDepositButtonVisible();


        // when leaving a date field, add 0 or 20 prefix if needed
        // can pass any field in here.  Will ignore non-date fields
        void autoPadDateField( TextField *inField );
        

        char mResponseProcessed;
        
        
        void recomputeFee();
        
        double mFee;

        void clearPaymentData();
        
    };
