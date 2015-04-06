#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"





class WaitGamePage : public ServerActionPage, public ActionListener {
        
    public:
        WaitGamePage();
        
        ~WaitGamePage();
        
        
        virtual void actionPerformed( GUIComponent *inTarget );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );

        virtual void makeActive( char inFresh );

        virtual void step();

        
        // override from GamePage to hide orange warning color
        // (because passing our 1/2 retry time is normal when we're waiting)
        virtual char noWarningColor() {
            return true;
            }
        
        
    protected:

        TextButton mCancelButton;                
        TextButton mOKButton;                
        
        SimpleVector<double> mOtherGames;

        char mResponseProcessed;
        
        int mActivePlayerCount;
    };
