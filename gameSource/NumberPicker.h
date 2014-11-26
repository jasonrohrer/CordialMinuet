#ifndef NUMBER_PICKER_INCLUDED
#define NUMBER_PICKER_INCLUDED


#include "minorGems/game/Font.h"
#include "minorGems/ui/event/ActionListener.h"

#include "PageComponent.h"
#include "SpriteButton.h"




class NumberPicker : public PageComponent, public ActionListener, 
                     public ActionListenerList {
    
    public:

        // max digits sets the maximum possible
        // (digits are added/removed with function calls below)
        // Decimal point is at inX, inY (not drawn if fraction digits
        // turned off
        NumberPicker( Font *inDisplayFont, 
                      double inX, double inY,
                      int inMaxMainDigits,
                      int inMaxFractionDigits,
                      const char *inLabelText = NULL,
                      char inUseCommas = true );

        virtual ~NumberPicker();

        // defaults to -1, no limit
        void setMax( double inMax );
        
        // defaults to 0, no limit
        void setMin( double inMin );
        
        // auto-forced into min/max range
        void setValue( double inValue );


        double getValue();
        
        // defaults to true
        // if false, all up/down buttons disabled
        void setAdjustable( char inAdjustable );
        

        virtual void draw();

        virtual void actionPerformed( GUIComponent *inTarget );




    protected:
        Font *mFont;

        int mMaxTotalDigits;
        int mMaxMainDigits;
        int mMaxFractionDigits;
        
        // useable given max limit
        int mUsableDigits;
        
        char mAdjustable;

        char mUseCommas;

        char *mLabelText;

        SpriteButton **mUpButtons;
        SpriteButton **mDownButtons;

        int *mDigits;
        
        double mMax;
        double mMin;


        void setValue( double inValue, int inDigits[] );

        double getValue( int inDigits[] );
        
        // what is the value of a digit if we tweak it up or down?
        // applies wrap-around
        // this includes capping of all-digit value to min/max range
        // (thus, sometimes a tweaked digit doesn't change)
        int getTweakedValue( int inDigitToTweak, int inTweakDelta );
        
        
        double getMaxPossible();
    };


#endif
