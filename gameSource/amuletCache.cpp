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

