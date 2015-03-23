#include "amuletCache.h"

#include "minorGems/util/SimpleVector.h"




typedef struct {
        int id;
        SpriteHandle sprite;
    } cacheRecord;


static SimpleVector<cacheRecord> cache;






SpriteHandle getAmuletSprite( int inID ) {
    for( int i=0; i<cache.size(); i++ ) {
        if( cache.getElement(i)->id == inID ) {
            return cache.getElement(i)->sprite;
            }
        }
    return NULL;
    }



void cacheAmuletSprite( int inID, SpriteHandle inSprite ) {
    cacheRecord r = { inID, inSprite };
    
    cache.push_back( r );
    }



void freeAmuletCache() {
    for( int i=0; i<cache.size(); i++ ) {
        freeSprite( cache.getElement(i)->sprite );
        }

    cache.deleteAll();
    }




#include "minorGems/game/game.h"
#include "minorGems/game/Font.h"
#include "minorGems/util/stringUtils.h"

extern int amuletID;
extern int amuletPointCount;
extern int amuletBaseTime;
extern int amuletHoldPenaltyPerMinute;

extern Font *mainFont;

void drawAmuletDisplay( doublePair inPos ) {
    SpriteHandle amuletSprite = getAmuletSprite( amuletID );
        
    if( amuletSprite != NULL ) {
        setDrawColor( 1, 1, 1, 1 );
        drawSprite( amuletSprite, inPos );
        }
        
    inPos.x += 42;
        
    inPos.y -= 3;

    int currentPointCount = amuletPointCount;
        
    int secondsPassed = game_time( NULL ) - amuletBaseTime;
        
    int minutesPassed = secondsPassed / 60;
        
    currentPointCount -= amuletHoldPenaltyPerMinute * minutesPassed;

        
    char *scoreString = autoSprintf( "%d", currentPointCount );
        
    mainFont->drawString( scoreString, inPos, alignLeft );
        
    delete [] scoreString;
    }


