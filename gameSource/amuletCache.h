#include "minorGems/game/gameGraphics.h"



// returns NULL if not present
SpriteHandle getAmuletSprite( int inID );


// sprite freed when amulet cache freed
void cacheAmuletSprite( int inID, SpriteHandle inSprite );



void freeAmuletCache();




void drawAmuletDisplay( doublePair inPos );
