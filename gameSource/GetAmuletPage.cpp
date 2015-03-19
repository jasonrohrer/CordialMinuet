#include "GetAmuletPage.h"


#include "minorGems/game/diffBundle/client/diffBundleClient.h"
#include "minorGems/game/game.h"
#include "minorGems/game/drawUtils.h"

#include "minorGems/game/Font.h"

#include "message.h"

#include "amuletCache.h"

#include "buttonStyle.h"


#include "serialWebRequests.h"

#include "minorGems/graphics/converters/TGAImageConverter.h"



#include "minorGems/io/InputStream.h"


class BufferInputStream : public InputStream {
    public:

        // mBytes destroyed by caller after this class is done
        BufferInputStream( unsigned char *inBytes, int inNumBytes )
                : mBytes( inBytes ), mNumBytes( inNumBytes ), 
                  mPosition( 0 ) {
            }
        
        
        
        
        virtual long read( unsigned char *inBuffer, long inNumBytes ) {
            
            if( mPosition >= mNumBytes ) {
                return -1;
                }
            
            int numToRead = inNumBytes;
            
            int bytesLeft = mNumBytes - mPosition;
            if( bytesLeft < numToRead ) {
                numToRead = bytesLeft;
                }
            memcpy( inBuffer, &( mBytes[ mPosition ] ), numToRead );
            
            mPosition += numToRead;
            
            return numToRead;
            }
        

    private:
        unsigned char *mBytes;
        int mNumBytes;
        int mPosition;
    };








extern Font *mainFont;


extern int justAcquiredAmuletID;
extern char *justAcquiredAmuletTGAURL;

extern char amuletID;




GetAmuletPage::GetAmuletPage()
        : mWebRequest( -1 ),
          mOkayButton( mainFont, 0, -200, 
                       translate( "OK" ) ) {


    addComponent( &mOkayButton );
    setButtonStyle( &mOkayButton );
    mOkayButton.addActionListener( this );
    }

    


GetAmuletPage::~GetAmuletPage() {
    
        
    if( mWebRequest != -1 ) {
        clearWebRequestSerial( mWebRequest );
        }
    }




void GetAmuletPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }


    mStatusError = false;
    mStatusMessageKey = NULL;
        
        
    if( getAmuletSprite( amuletID ) == NULL ) {
        // need to fetch it
    
        mOkayButton.setVisible( false );
    

        // fixme: start web request
        mWebRequest = startWebRequestSerial( "GET", 
                                             justAcquiredAmuletTGAURL, 
                                             NULL );
        
        setWaiting( true );
        }
    else {
        // have it already
        setWaiting( false );
        
        mOkayButton.setVisible( true );
        }
    }
        



void GetAmuletPage::draw( doublePair inViewCenter, 
                           double inViewSize ) {

    doublePair labelPos = { 0, 100 };

    
    SpriteHandle sprite = getAmuletSprite( amuletID );


    if( ! mOkayButton.isVisible() || sprite != NULL ) {
        
        drawMessage( translate( "receivingAmulet" ), labelPos, false );
        }
    else {
        // getting amulet image failed
        drawMessage( translate( "receivingAmuletFailed" ), labelPos, false );
        }
    
    
    if( sprite != NULL ) {
        // got the amulet, draw it

        labelPos.y -= 100;
        
        setDrawColor( 1, 1, 1, 1 );
        drawSprite( sprite, labelPos );
        }
    }


        
void GetAmuletPage::step() {

    if( mWebRequest != -1 ) {
        int stepResult = stepWebRequestSerial( mWebRequest );
        
        
        if( stepResult != 0 ) {
            setWaiting( false );
            
            // got it or failed to get it
            justAcquiredAmuletID = 0;    
                
            mOkayButton.setVisible( true );    
            }
        
        switch( stepResult ) {
            case 0:
                break;
            case -1:
                mStatusError = true;
                mStatusMessageKey = "err_webRequest";
                clearWebRequestSerial( mWebRequest );
                mWebRequest = -1;
                break;
            case 1: {
                int size;
                
                unsigned char *result = 
                    getWebResultSerial( mWebRequest, &size );
                
                printf( "Result size = %d\n", size );
                
                clearWebRequestSerial( mWebRequest );
                mWebRequest = -1;
                
                BufferInputStream bufferStream( result, size );
                
                TGAImageConverter converter;
    
                Image *amuletImage = converter.deformatImage( &bufferStream );

                if( amuletImage != NULL ) {
                    
                    SpriteHandle sprite = fillSprite( amuletImage, false );
                    
                    delete amuletImage;
                    
                    cacheAmuletSprite( amuletID, sprite );
                    }
                
                delete [] result;
                }
                break;
            }
        }
    }




void GetAmuletPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mOkayButton ) {
        setSignal( "done" );
        }
    }
