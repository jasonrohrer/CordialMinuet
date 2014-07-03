#include "whiteSprites.h"


SpriteHandle loadWhiteSprite( const char *inTGAFileName ) {
    Image *spriteImage = readTGAFile( inTGAFileName );
    
    int width = spriteImage->getWidth();
        
    int height = spriteImage->getHeight();
    
    int numPixels = width * height;
    
    double *channels[3];
    
    for( int c=0; c<3; c++ ) {
        channels[c] = spriteImage->getChannel( c );
        }
    
    Image rgbaImage( width, height, 4, false );
    
    
    
    // red into alpha
    memcpy( rgbaImage.getChannel( 3 ), spriteImage->getChannel( 0 ),
            sizeof( double ) * numPixels );
    
    // white into rest
    double *solidWhite = new double[ numPixels ];
    for( int i=0; i<numPixels; i++ ) {
        solidWhite[i] = 1;
        }
    
    // white into others
    memcpy( rgbaImage.getChannel( 0 ), solidWhite, 
            sizeof( double ) * numPixels ); 

    memcpy( rgbaImage.getChannel( 1 ), solidWhite, 
            sizeof( double ) * numPixels ); 

    memcpy( rgbaImage.getChannel( 2 ), solidWhite, 
            sizeof( double ) * numPixels ); 

    delete spriteImage;
    
    return fillSprite( &rgbaImage, false );
    }
