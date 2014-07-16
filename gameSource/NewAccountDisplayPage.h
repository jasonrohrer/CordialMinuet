#include "GamePage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"


class NewAccountDisplayPage : public GamePage, public ActionListener {
        
    public:
        NewAccountDisplayPage();
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
    protected:
        
        TextButton mCopyToClipboardButton;
        TextButton mDoneButton;



    };
