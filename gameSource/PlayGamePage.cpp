#include "PlayGamePage.h"

#include "buttonStyle.h"
#include "message.h"



#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"
#include "minorGems/game/drawUtils.h"

#include "minorGems/util/stringUtils.h"



extern Font *mainFont;



const char *gameStatePartNames[6] = { "running", "boardLayout", 
                                      "yourCoins", "theirCoins", 
                                      "yourPotCoins", "theirPotCoins" };


static int cellSize = 70;
static int borderWidth = 2;
    
static int cellCenterStart = ( ( cellSize + borderWidth ) * 5 ) / 2;



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
    

    
    doublePair pos = { - cellCenterStart, cellCenterStart };
    
    if( mGameBoard != NULL ) {
        
        setDrawColor( 1, 1, 1, 1 );
        
        doublePair center  = {0,0};
        drawSquare( center, 
                    ( (cellSize + borderWidth) * 6 ) /  2 + borderWidth / 2 );
        

        for( int y=0; y<6; y++ ) {
            pos.x = -cellCenterStart;
            for( int x=0; x<6; x++ ) {
                
                setDrawColor( 0, 0, 0, 1 );
                
                drawSquare( pos, cellSize/2 );

                char *number = autoSprintf( "%d", mGameBoard[y*6 + x] );
            
                // tweak y down a bit as baseline offset for font
                pos.y -= 3;
                drawMessage( number, pos );
                pos.y += 3;

                pos.x += cellSize + borderWidth;            
                }
            pos.y -= cellSize + borderWidth;
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







