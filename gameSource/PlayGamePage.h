#include "ServerActionPage.h"



#include "minorGems/ui/event/ActionListener.h"


#include "TextButton.h"
#include "TextField.h"
#include "NumberPicker.h"



typedef enum GameMessageState {
    gettingState,
    sendingMove,
    waitingMove
    } GameMessageState;



class PlayGamePage : public ServerActionPage, public ActionListener {
        
    public:
        PlayGamePage();
        
        ~PlayGamePage();
        

        virtual void makeActive( char inFresh );

    
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
        
        // ours first
        int mPlayerCoins[2];
        int mPotCoins[2];


        TextButton mCommitButton;
        

        TextButton *mColumnButtons[6];


        int mColumnChoiceForUs;
        int mColumnChoiceForThem;

        GameMessageState mMessageState;
        
    };
