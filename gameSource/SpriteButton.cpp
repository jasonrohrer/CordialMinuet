#include "SpriteButton.h"
#include "whiteSprites.h"

#include "minorGems/util/log/AppLog.h"



SpriteButton::SpriteButton( SpriteHandle inSprite,
                            int inWide, int inHigh,
                            double inX, double inY,
                            double inDrawScale,
                            double inSizeFactor )
        : Button( inX, inY, 
                  inWide * inSizeFactor * inDrawScale,
                  inHigh * inSizeFactor * inDrawScale, 
                  inDrawScale ), 
          mShouldDestroySprite( false ),
          mSprite( inSprite ), 
          mOverrideHighlightColor( false ),
          mDrawScale( inDrawScale ) {

    }


SpriteButton::SpriteButton( const char *inTGAFileName, double inX, double inY,
                            double inDrawScale,
                            double inSizeFactor, 
                            char inWhiteSprite )
        : // placeholder until we load file below 
        Button( inX, inY, 
                1,
                1, 
                inDrawScale ),
        mShouldDestroySprite( false ),
        mSprite( NULL ), 
        mOverrideHighlightColor( false ),
        mDrawScale( inDrawScale ) {


    if( ! inWhiteSprite ) {
        
        Image *image = readTGAFile( inTGAFileName );
        
        if( image != NULL ) {
            // fill Button's values here
            mWide = image->getWidth() * inSizeFactor * inDrawScale;
            mHigh = image->getHeight() * inSizeFactor * inDrawScale;
            
            mSprite = fillSprite( image );
            mShouldDestroySprite = true;
            
            delete image;
            }
        }
    else {
        int w, h;
        
        mSprite = loadWhiteSprite( inTGAFileName, &w, &h );
    
        if( mSprite != NULL ) {
            // fill Button's values here
            mWide = w * inSizeFactor * inDrawScale;
            mHigh = h * inSizeFactor * inDrawScale;
            
            mShouldDestroySprite = true;
            }
        }

    if( mSprite == NULL ) {
        AppLog::errorF( "Failed to read file for SpriteButton: %s",
                        inTGAFileName );
        }
    
    }




SpriteButton::~SpriteButton() {
    if( mShouldDestroySprite && mSprite != NULL ) {
        freeSprite( mSprite );
        }
    }



void SpriteButton::setSprite( SpriteHandle inSprite, char inShouldDestroy ) {
    if( mShouldDestroySprite && mSprite != NULL ) {
        freeSprite( mSprite );
        }

    mShouldDestroySprite = inShouldDestroy;
    mSprite = inSprite;
    }



void SpriteButton::drawContents() {
    if( mSprite != NULL ) {
        
        if( mOverrideHighlightColor ) {
            setDrawColor( 1, 1, 1, 1 );
            }
        // else leave draw color as set by Button's draw function
        
        
        doublePair center = { 0, 0 };
        
        drawSprite( mSprite, center, mDrawScale );
        }
    }
