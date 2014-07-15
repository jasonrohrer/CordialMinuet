#ifndef NUMBER_PICKER_INCLUDED
#define NUMBER_PICKER_INCLUDED


#include "minorGems/game/Font.h"
#include "minorGems/ui/event/ActionListener.h"

#include "PageComponent.h"
#include "SpriteButton.h"




class NumberPicker : public PageComponent, public ActionListener {
    
    public:

        // max digits sets the maximum possible
        // (digits are added/removed with function calls below)
        // Decimal point is at inX, inY (not drawn if fraction digits
        // turned off
        NumberPicker( Font *inDisplayFont, 
                      double inX, double inY,
                      int inMaxMainDigits,
                      int inMaxFractionDigits );

        virtual ~NumberPicker();

        // defaults to -1, no limit
        void setMax( double inMax );
        
        // defaults to 0, no limit
        void setMin( double inMin );
        
        // auto-forced into min/max range
        void setValue( double inValue );


        double getValue();
        


        virtual void draw();

        virtual void actionPerformed( GUIComponent *inTarget );




    protected:
        Font *mFont;

        int mMaxTotalDigits;
        int mMaxMainDigits;
        int mMaxFractionDigits;
        
        SpriteButton **mUpButtons;
        SpriteButton **mDownButtons;

        int *mDigits;
        
        double mMax;
        double mMin;

        
        double getValue( int inDigits[] );
        
        // what is the value of our digits if we tweak one digit up or down
        double getTweakedValue( int inDigitToTweak, int inTweakDelta );
        
        

    };


#endif
