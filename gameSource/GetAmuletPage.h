#include "GamePage.h"

#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"



class GetAmuletPage : public GamePage, public ActionListener {
        
    public:
        
        GetAmuletPage();
        

        virtual ~GetAmuletPage();
        
        virtual void makeActive( char inFresh );

        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        

        virtual void actionPerformed( GUIComponent *inTarget );

    private:
        
        int mWebRequest;
        
        TextButton mOkayButton;
    };
