#include "PlayGamePage.h"

#include "buttonStyle.h"
#include "message.h"



#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"



extern Font *mainFont;



const char *gameStatePartNames[6] = { "running", "boardLayout", 
                                      "yourCoins", "theirCoins", 
                                      "yourPotCoins", "theirPotCoins" };

PlayGamePage::PlayGamePage()
        : ServerActionPage( "get_game_state", 6, gameStatePartNames ),
          mGameBoard( NULL ) {


    }


        
PlayGamePage::~PlayGamePage() {
    if( mGameBoard != NULL ) {
        delete [] mGameBoard;
        }
    }






void PlayGamePage::actionPerformed( GUIComponent *inTarget ) {
    }







void PlayGamePage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
    
    doublePair pos = { -288, 288 };
    
    if( mGameBoard != NULL ) {
        
        for( int y=0; y<6; y++ ) {
            pos.x = -288;
            for( int x=0; x<6; x++ ) {
                
                char *number = autoSprintf( "%d", mGameBoard[y*6 + x] );
            
                drawMessage( number, pos );
                pos.x += 96;            
                }
            pos.y -= 96;
            }
        }
    
        

    //drawMessage( "test messag", pos );    
    }



void PlayGamePage::step() {
    ServerActionPage::step();

    if( isResponseReady() ) {
        char *gameBoardString = getResponse( "boardLayout" );
        
        int numParts;
        char **parts = split( gameBoardString, "#", &numParts );
        
        if( numParts == 36 ) {
            if( mGameBoard != NULL ) {
                delete [] mGameBoard;
                }
            mGameBoard = new int[36];
            
            for( int i=0; i<numParts; i++ ) {
                sscanf( parts[i], "%d", &( mGameBoard[i] ) );
                }
            }
        
        for( int i=0; i<numParts; i++ ) {
            delete [] parts[i];
            }
        delete [] parts;

        }
    }







