#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"


class AccountCheckPage : public ServerActionPage, public ActionListener {
        
    public:
        AccountCheckPage();
        
        ~AccountCheckPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );

        virtual void step();


    protected:
        
        TextButton mNewAccountButton;
        TextButton mExistingAccountButton;



    };
