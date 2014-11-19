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

extern double checkCostUS;
extern double checkCostGlobal;
extern double transferCost;
extern double userBalance;






WithdrawPage::WithdrawPage()
        : ServerActionPage( "get_withdrawal_methods", true ),
          mSendCheckUSButton( mainFont, 0, 160, 
                              translate( "sendCheckUS" ) ),
          mSendCheckGlobalButton( mainFont, 0, -64, 
                                  translate( "sendCheckGlobal" ) ),
          mAccountTransferButton( mainFont, 0, -64, 
                                  translate( "accountTransfer" ) ),
          mCancelButton( mainFont, 0, -200, 
                         translate( "cancel" ) ),
          mUSCheckAvailable( false ), 
          mGlobalCheckAvailable( false ), 
          mTransferAvailable( false ) {

    addComponent( &mSendCheckUSButton );
    addComponent( &mSendCheckGlobalButton );
    addComponent( &mAccountTransferButton );
    addComponent( &mCancelButton );

    setButtonStyle( &mSendCheckUSButton );
    setButtonStyle( &mSendCheckGlobalButton );
    setButtonStyle( &mAccountTransferButton );
    setButtonStyle( &mCancelButton );

    mSendCheckUSButton.addActionListener( this );
    mSendCheckGlobalButton.addActionListener( this );
    mAccountTransferButton.addActionListener( this );
    mCancelButton.addActionListener( this );
    }

        
WithdrawPage::~WithdrawPage() {
    }


        
void WithdrawPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mSendCheckUSButton ) {
        setSignal( "sendCheck" );
        }
    else if( inTarget == &mSendCheckGlobalButton ) {
        setSignal( "sendCheckGlobal" );
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

    
    mSendCheckUSButton.setVisible( false );
    mSendCheckGlobalButton.setVisible( false );
    mAccountTransferButton.setVisible( false );
    mCancelButton.setVisible( false );

    mUSCheckAvailable = false;
    mGlobalCheckAvailable = false;
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

        int numLines = getNumResponseParts();
        
        for( int i = 0; i<numLines; i++ ) {
            
            char *line = getResponse( i );

            int numParts;
            
            char **parts = split( line, "#", &numParts );

            delete [] line;

            if( numParts == 2 ) {
                if( strcmp( parts[0], "us_check" ) == 0 ) {
                    
                    mUSCheckAvailable = true;
                    
                    sscanf( parts[1], "%lf", &checkCostUS );
                    
                    if( userBalance >= checkCostUS + 0.01 ) {
                        mSendCheckUSButton.setVisible( true );
                        }
                    }
                else if( strcmp( parts[0], "global_check" ) == 0 ) {
                    
                    mGlobalCheckAvailable = true;
                    
                    sscanf( parts[1], "%lf", &checkCostGlobal );
                    
                    if( userBalance >= checkCostGlobal + 0.01 ) {
                        mSendCheckGlobalButton.setVisible( true );
                        }
                    }
                else if( strcmp( parts[0], "account_transfer" ) == 0 ) {

                    mTransferAvailable = true;

                    sscanf( parts[1], "%lf", &transferCost );
                    
                    if( userBalance >= transferCost + 0.01 ) {
                        mAccountTransferButton.setVisible( true );
                        }
                    }
                else if( strcmp( parts[0], "in_person" ) == 0 ) {
                    setSignal( "inPerson" );
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

    char any = false;
    
    if( mUSCheckAvailable ) {
        drawButtonNote( &mSendCheckUSButton, "checkNoteUS", 
                        "checkBalanceLowUS",
                        checkCostUS );
        any = true;
        }
    if( mGlobalCheckAvailable ) {
        drawButtonNote( &mSendCheckGlobalButton, "checkNoteGlobal", 
                        "checkBalanceLowGlobal",
                        checkCostGlobal );
        any = true;
        }
    if( mTransferAvailable ) {
        drawButtonNote( &mAccountTransferButton, "transferNote", 
                        "transferBalanceLow", 
                        transferCost );
        any = true;
        }
    

    if( !any && isResponseReady() ) {
        doublePair center = { 0, 0 };
        
        drawMessage( "noWithdrawMethods", center );
        }
    }
    
