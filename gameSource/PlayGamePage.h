#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"





class PlayGamePage : public ServerActionPage, public ActionListener {
        
    public:
        PlayGamePage();
        
        ~PlayGamePage();
        
        
        virtual void actionPerformed( GUIComponent *inTarget );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();

        
        // override from GamePage to hide orange warning color
        // (because passing our 1/2 retry time is normal when we're waiting)
        virtual char noWarningColor() {
            return true;
            }
        
        
    protected:
           
        int *mGameBoard;
        
    };
