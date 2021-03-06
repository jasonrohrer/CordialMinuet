#ifndef SPRITE_BUTTON_INCLUDED
#define SPRITE_BUTTON_INCLUDED


#include "Button.h"

#include "minorGems/game/gameGraphics.h"


class SpriteButton : public Button {
        
    public:
        
        // inSprite NOT destroyed by this class
        // inSizeFactor is how big the button is in each dimension
        // relative to the sprite (1 means no border around sprite, defaults
        // to 2)
        SpriteButton( SpriteHandle inSprite, 
                      // size of sprite in pixels
                      int inWide, int inHigh,
                      double inX, double inY,
                      double inDrawScale = 1.0,
                      double inSizeFactor = 2.0 );
        
        SpriteButton( const char *inTGAFileName, double inX, double inY,
                      double inDrawScale = 1.0,
                      double inSizeFactor = 2.0,
                      char inWhiteSprite = false );

        virtual ~SpriteButton();
        
        void setSprite( SpriteHandle inSprite, char inShouldDestroy = false );


    protected:
        char mShouldDestroySprite;
        SpriteHandle mSprite;

        // if false (default), highlight color set by Button class is 
        // kept when drawing sprite.  This works great for white sprites,
        // as they become highlighted.
        //
        // But for color sprites, this can be overriden by subclasses
        // (if true, sprites are drawn with white color instead)
        char mOverrideHighlightColor;

        double mDrawScale;
        
        
        // override
        virtual void drawContents();

        
    };



#endif
        
