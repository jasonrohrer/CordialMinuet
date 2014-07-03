#include "minorGems/game/gameGraphics.h"



// loads an RGB sprite from a TGA file in the graphics folder
// that features a white image on a black background, where
// darkness is interpreted as transparency.
// (Darkness is measured through red channel only, and other channels
//  are ignored)
SpriteHandle loadWhiteSprite( const char *inTGAFileName );
