#include "AutoUpdatePage.h"


#include "minorGems/game/diffBundle/client/diffBundleClient.h"
#include "minorGems/game/game.h"
#include "minorGems/game/drawUtils.h"

#include "message.h"

        

void AutoUpdatePage::draw( doublePair inViewCenter, 
                           double inViewSize ) {
    
    float progress = getUpdateProgress();

    const char *messageKey;
    if( progress < 1 ) {
        messageKey = "downloadingUpdate";
        }
    else {
        messageKey = "applyingUpdate";
        }

    doublePair labelPos = { 0, 100 };

    
    drawMessage( translate( messageKey ), labelPos, false );

    // border
    setDrawColor( 1, 1, 1, 1 );
    
    drawRect( -100, -10, 
               100, 10 );

    // inner black
    setDrawColor( 0, 0, 0, 1 );
    
    drawRect( -98, -8, 
               98, 8 );
    
    
    // progress
    setDrawColor( .9, .9, .9, 0 );
    drawRect( -98, -8, 
               -98 + progress * ( 98 * 2 ), 8 );

    }

        
void AutoUpdatePage::step() {
    int result = stepUpdate();

    if( result == -1 ) {
        setSignal( "failed" );
        return;
        }
    
    if( result == 1 ) {
        clearUpdate();
        
        char relaunched = relaunchGame();
        
        if( !relaunched ) {
            setSignal( "relaunchFailed" );
            }
        }
    
    }

        
