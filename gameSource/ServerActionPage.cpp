#include "ServerActionPage.h"

#include "minorGems/util/SimpleVector.h"
#include "minorGems/util/stringUtils.h"
#include "minorGems/system/Time.h"


#include "serialWebRequests.h"
#include "accountHmac.h"

extern char *serverURL;



ServerActionPage::ServerActionPage( const char *inActionName,
                                    int inRequiredNumResponseParts,
                                    const char *inResponsePartNames[],
                                    char inAttachAccountHmac )
        : mActionName( stringDuplicate( inActionName ) ),
          mAttachAccountHmac( inAttachAccountHmac ),
          mNumResponseParts( inRequiredNumResponseParts ),
          mWebRequest( -1 ), mResponseReady( false ),
          mMinimumResponseSeconds( 0 ),
          mRequestStartTime( 0 ) {
    
    for( int i=0; i<mNumResponseParts; i++ ) {
        mResponsePartNames.push_back( 
            stringDuplicate( inResponsePartNames[i] ) );
        }
    }

        

ServerActionPage::~ServerActionPage() {
    
        
    if( mWebRequest != -1 ) {
        clearWebRequestSerial( mWebRequest );
        }


    delete [] mActionName;

    mActionParameterNames.deallocateStringElements();
    mActionParameterValues.deallocateStringElements();
        
    mResponsePartNames.deallocateStringElements();
    mResponseParts.deallocateStringElements();
    }

        

void ServerActionPage::setActionParameter( const char *inParameterName,
                                           const char *inParameterValue ) {
    for( int i=0; i<mActionParameterNames.size(); i++ ) {
        char *name = *( mActionParameterNames.getElement( i ) );
    
        if( strcmp( name, inParameterName ) == 0 ) {
            mActionParameterNames.deallocateStringElement( i );
            mActionParameterValues.deallocateStringElement( i );
            }
        }
    
    mActionParameterNames.push_back( stringDuplicate( inParameterName ) );
    mActionParameterValues.push_back( stringDuplicate( inParameterValue ) );
    }


        
void ServerActionPage::setActionParameter( const char *inParameterName,
                                           int inParameterValue ) {
    char *valueString = autoSprintf( "%d", inParameterValue );
    setActionParameter( inParameterName, valueString );
    delete [] valueString;
    }



void ServerActionPage::setActionParameter( const char *inParameterName,
                                           double inParameterValue ) {
    
    char *valueString = autoSprintf( "%d", inParameterValue );
    setActionParameter( inParameterName, valueString );
    delete [] valueString;
    }



void ServerActionPage::setAttachAccountHmac( char inShouldAttach ) {
    mAttachAccountHmac = inShouldAttach;
    }
        


void ServerActionPage::makeActive( char inFresh ) {
    if( !inFresh ) {
        return;
        }
    startRequest();
    }



void ServerActionPage::startRequest() {

    mResponseParts.deallocateStringElements();


    
    SimpleVector<char> actionParameterListChars;
    
    for( int i=0; i<mActionParameterNames.size(); i++ ) {
        char *name = *( mActionParameterNames.getElement(i) );
        char *value = *( mActionParameterValues.getElement(i) );
        
        char *pairString = autoSprintf( "&%s=%s", name, value );
        
        actionParameterListChars.appendElementString( pairString );
        
        delete [] pairString;
        }
    

    if( mAttachAccountHmac ) {
        
        char *accountHmac = getAccountHmac();
        
        actionParameterListChars.push_back( '&' );
        actionParameterListChars.appendElementString( accountHmac );
        
        delete [] accountHmac;
        }
    
    char *actionParameterListString = 
        actionParameterListChars.getElementString();
    


    char *fullRequestURL = autoSprintf( 
        "%s?action=%s%s",
        serverURL, mActionName, actionParameterListString );

    delete [] actionParameterListString;

    
    mWebRequest = startWebRequestSerial( "GET", 
                                         fullRequestURL, 
                                         NULL );
    
    delete [] fullRequestURL;
    

    mStatusError = false;
    mStatusMessageKey = NULL;

    mResponseReady = false;
    
    mRequestStartTime = Time::getCurrentTime();

    setWaiting( true );
    }

    

void ServerActionPage::step() {
    if( mWebRequest != -1 ) {
            
        int stepResult = stepWebRequestSerial( mWebRequest );
        
        
        if( mMinimumResponseSeconds > 0 &&
            Time::getCurrentTime() -  mRequestStartTime 
            < mMinimumResponseSeconds ) {
            
            // wait to process result
            return;
            }
        
        if( stepResult != 0 ) {
            setWaiting( false );
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
                char *result = getWebResultSerial( mWebRequest );
                clearWebRequestSerial( mWebRequest );
                mWebRequest = -1;
                     
                printf( "Web result = %s\n", result );
   
                if( strstr( result, "DENIED" ) != NULL ) {
                    mStatusError = true;
                    mStatusMessageKey = "requestDenied";
                    }
                else {
                    SimpleVector<char *> *lines = 
                        tokenizeString( result );
                    
                    if( lines->size() != mNumResponseParts + 1
                        ||
                        strcmp( *( lines->getElement( mNumResponseParts ) ), 
                                "OK" ) != 0 ) {

                        mStatusError = true;
                        setStatus( "err_badServerResponse", true );
                        

                        for( int i=0; i<lines->size(); i++ ) {
                            delete [] *( lines->getElement( i ) );
                            }
                        }
                    else {
                        // all except final OK 
                        for( int i=0; i<lines->size()-1; i++ ) {
                            mResponseParts.push_back( 
                                *( lines->getElement( i ) ) );
                            }
                        
                        delete [] *( lines->getElement( lines->size()-1 ) );
                        
                        mResponseReady = true;
                        }
                    delete lines;
                    }
                        
                        
                delete [] result;
                }
                break;
            }
        }
    }




char ServerActionPage::isError() {
    return mStatusError;
    }



char ServerActionPage::isResponseReady() {
    return mResponseReady;
    }



// result destroyed by caller
char *ServerActionPage::getResponse( const char *inPartName ) {
    for( int i=0; i<mResponseParts.size(); i++ ) {
        char *name = *( mResponsePartNames.getElement( i ) );
        
        if( strcmp( name, inPartName ) == 0 ) {
            return stringDuplicate( *( mResponseParts.getElement( i ) ) );
            }
        }
    return NULL;
    }



int ServerActionPage::getResponseInt( const char *inPartName ) {
    char *responseString = getResponse( inPartName );
    
    if( responseString != NULL ) {
        int returnValue = -1;
        
        sscanf( responseString, "%d", &returnValue );

        delete [] responseString;
        
        return returnValue;
        }
    else {
        return -1;
        }
    }


double ServerActionPage::getResponseDouble( const char *inPartName ) {
    char *responseString = getResponse( inPartName );
    
    if( responseString != NULL ) {
        double returnValue = -1.0;
        
        sscanf( responseString, "%lf", &returnValue );

        delete [] responseString;
        
        return returnValue;
        }
    else {
        return -1.0;
        }
    }



void ServerActionPage::setMinimumResponseTime( double inSeconds ) {
    mMinimumResponseSeconds = inSeconds;
    }

