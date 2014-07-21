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

        virtual void draw( doublePair inViewCenter, double inViewSize );
        

    protected:
        
        TextButton mNewAccountButton;
        TextButton mExistingAccountButton;



    };
