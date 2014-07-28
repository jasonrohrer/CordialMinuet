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

extern double checkCost;
extern double transferCost;
extern double userBalance;






WithdrawPage::WithdrawPage()
        : ServerActionPage( "get_withdrawal_methods", true ),
          mSendCheckButton( mainFont, 0, 160, 
                             translate( "sendCheck" ) ),
          mAccountTransferButton( mainFont, 0, -64, 
                                  translate( "accountTransfer" ) ),
          mCancelButton( mainFont, 0, -200, 
                         translate( "cancel" ) ),
          mCheckAvailable( false ), 
          mTransferAvailable( false ) {

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
        setSignal( "sendCheck" );
        }
    else if( inTarget == &mAccountTransferButton ) {
        setSignal( "transfer" );
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

    mCheckAvailable = false;
    mTransferAvailable = false;

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
                    
                    mCheckAvailable = true;
                    
                    sscanf( parts[1], "%lf", &checkCost );
                    
                    if( userBalance >= checkCost + 0.01 ) {
                        mSendCheckButton.setVisible( true );
                        }
                    }
                else if( strcmp( parts[0], "account_transfer" ) == 0 ) {

                    mTransferAvailable = true;

                    sscanf( parts[1], "%lf", &transferCost );
                    
                    if( userBalance >= transferCost + 0.01 ) {
                        mAccountTransferButton.setVisible( true );
                        }
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
                            const char *inBalanceLowKey,
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

    if( inButton->isVisible() ) {
        drawMessage( message, labelPos );
        }
    
    delete [] message;

    if( ! inButton->isVisible() ) {
        labelPos = inButton->getPosition();
        
        drawMessage( inBalanceLowKey, labelPos );
        }
    }



void WithdrawPage::draw( doublePair inViewCenter, 
                             double inViewSize ) {


    if( mCheckAvailable ) {
        drawButtonNote( &mSendCheckButton, "checkNote", "checkBalanceLow",
                        checkCost );
        }
    if( mTransferAvailable ) {
        drawButtonNote( &mAccountTransferButton, "transferNote", 
                        "transferBalanceLow", 
                        transferCost );
        }
    
    }
    
