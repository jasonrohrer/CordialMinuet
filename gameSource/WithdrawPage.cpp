#include "WithdrawPage.h"

#include "buttonStyle.h"
#include "message.h"

#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"


extern Font *mainFont;


extern int userID;
extern char *userEmail;
extern char *accountKey;
extern int serverSequenceNumber;

extern char gamePlayingBack;








WithdrawPage::WithdrawPage()
        : ServerActionPage( "get_withdrawal_methods", true ),
          mSendCheckButton( mainFont, 0, 160, 
                             translate( "sendCheck" ) ),
          mAccountTransferButton( mainFont, 0, -64, 
                                  translate( "accountTransfer" ) ),
          mCancelButton( mainFont, 0, -200, 
                         translate( "cancel" ) ),
          mCheckCost( 0 ),
          mTransferCost( 0 ) {

    addComponent( &mSendCheckButton );
    addComponent( &mAccountTransferButton );
    addComponent( &mCancelButton );

    setButtonStyle( &mSendCheckButton );
    setButtonStyle( &mAccountTransferButton );
    setButtonStyle( &mCancelButton );

    mSendCheckButton.addActionListener( this );
    mAccountTransferButton.addActionListener( this );
    mCancelButton.addActionListener( this );
    }

        
WithdrawPage::~WithdrawPage() {
    }


        
void WithdrawPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mSendCheckButton ) {
        setSignal( "newAccount" );
        }
    else if( inTarget == &mAccountTransferButton ) {
        setSignal( "existingAccount" );
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void WithdrawPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }

    
    mSendCheckButton.setVisible( false );
    mAccountTransferButton.setVisible( false );
    mCancelButton.setVisible( false );
    
    startRequest();
    }




void WithdrawPage::step() {
    ServerActionPage::step();

    if( isError() ) {
        mCancelButton.setVisible( true );
        }
    else if( isResponseReady() && ! mCancelButton.isVisible() ) {
        
        mCancelButton.setVisible( true );

        // parse response

        int numParts = getNumResponseParts();
        
        for( int i = 0; i<numParts; i++ ) {
            
            char *line = getResponse( i );

            int numParts;
            
            char **parts = split( line, "#", &numParts );

            delete [] line;

            if( numParts == 2 ) {
                if( strcmp( parts[0], "us_check" ) == 0 ) {
                    
                    mSendCheckButton.setVisible( true );

                    sscanf( parts[1], "%lf", &mCheckCost );
                    }
                else if( strcmp( parts[0], "account_transfer" ) == 0 ) {
                    
                    mAccountTransferButton.setVisible( true );

                    sscanf( parts[1], "%lf", &mTransferCost );
                    }
                }
            
            
            for( int p=0; p<numParts; p++ ) {
                delete [] parts[p];
                }
            delete [] parts;
            }
        

        
        }
    }



static void drawButtonNote( Button *inButton, const char *inNoteKey,
                            double inCost ) {
    doublePair labelPos = inButton->getPosition();

    labelPos.y += 96;
    
    char *feeString;
    
    if( inCost > 0 ) {
        feeString = autoSprintf( "$%.2f %s", inCost,
                                 translate( "fee" ) );
        }
    else {
        feeString = stringDuplicate( translate( "free" ) );
        }
    
    
    char *message = autoSprintf( translate( inNoteKey ), feeString );
    delete [] feeString;
    
    drawMessage( message, labelPos );

    delete [] message;
    }



void WithdrawPage::draw( doublePair inViewCenter, 
                             double inViewSize ) {


    if( mSendCheckButton.isVisible() ) {
        drawButtonNote( &mSendCheckButton, "checkNote", mCheckCost );
        }
    if( mAccountTransferButton.isVisible() ) {
        drawButtonNote( &mAccountTransferButton, "transferNote", 
                        mTransferCost );
        }
    
    }
    
