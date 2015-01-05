#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "MenuPage.h"





class EnterTournamentPage : public ServerActionPage, public ActionListener {
        
    public:
        EnterTournamentPage();
        
        ~EnterTournamentPage();
        
        void setTournamentInfo( GameRecord inRecord );
        
        
        virtual void actionPerformed( GUIComponent *inTarget );

        virtual void makeActive( char inFresh );
        
        

        // for TAB and ENTER (switch fields and start server action)
        virtual void keyDown( unsigned char inASCII );
        
        
        virtual void draw( doublePair inViewCenter, 
                           double inViewSize );
        
        virtual void step();
        
    protected:

        TextButton mEnterButton;

        TextButton mCancelButton;

        
        GameRecord mTournamentInfo;
                
    };
