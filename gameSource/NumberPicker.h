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


        virtual void draw();


    protected:
        Font *mFont;

        int mMaxTotalDigits;
        int mMaxMainDigits;
        int mMaxFractionDigits;
        
        SpriteButton *mUpButtons;
        SpriteButton *mDownButtons;
                
        
    };


#endif
