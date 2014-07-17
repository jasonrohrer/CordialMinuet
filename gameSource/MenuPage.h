#include "GamePage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"


class MenuPage : public GamePage, public ActionListener {
        
    public:
        MenuPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );


    protected:
        
        TextButton mDepositButton;



    };
