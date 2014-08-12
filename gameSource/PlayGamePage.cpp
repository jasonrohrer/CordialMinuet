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
    
    for( int i=0; i<2; i++ ) {
        mPlayerCoins[i] = -1;
        mPotCoins[i] = -1;
        }

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
    
    doublePair pos = { -160, 160 };
    
    if( mGameBoard != NULL ) {
        
        for( int y=0; y<6; y++ ) {
            pos.x = -160;
            for( int x=0; x<6; x++ ) {
                
                char *number = autoSprintf( "%d", mGameBoard[y*6 + x] );
            
                drawMessage( number, pos );
                pos.x += 64;            
                }
            pos.y -= 64;
            }
        }
    
    
    if( mPlayerCoins[0] != -1 ) {
        // draw coins
        pos.x = -264;
        pos.y = 288;
        
        char *number = autoSprintf( "%d", mPlayerCoins[0] );
        
        setDrawColor( 0, .75, 0, 1 );
        
        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;


        pos.y = 32;
        number = autoSprintf( "%d", mPotCoins[0] );

        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;



        pos.y = -288;

        number = autoSprintf( "%d", mPlayerCoins[1] );
        
        setDrawColor( .75, 0, 0, 1 );
        
        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;


        pos.y = -32;
        number = autoSprintf( "%d", mPotCoins[1] );

        mainFont->drawString( number, 
                              pos, alignRight );
        delete [] number;
        }
    

    //drawMessage( "test messag", pos );    
    }



void PlayGamePage::step() {
    ServerActionPage::step();

    if( isResponseReady() ) {

        mPlayerCoins[0] = getResponseInt( "yourCoins" );
        mPlayerCoins[1] = getResponseInt( "theirCoins" );

        mPotCoins[0] = getResponseInt( "yourPotCoins" );
        mPotCoins[1] = getResponseInt( "theirPotCoins" );

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







