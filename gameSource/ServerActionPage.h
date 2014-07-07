#ifndef SERVER_ACTION_PAGE_INCLUDED
#define SERVER_ACTION_PAGE_INCLUDED


#include "GamePage.h"

#include "TextButton.h"




// base class for pages that execute a server action and step
// to wait for and parse a response
// consolidates server contact and response error-handling code
class ServerActionPage : public GamePage {
    public:
        
        // inResponsePartNames copied internally, destroyed by caller
        ServerActionPage( const char *inActionName,
                          int inRequiredNumResponseParts,
                          const char *inResponsePartNames[],
                          char inAttachAccountHmac = true );
        

        virtual ~ServerActionPage();
        

        void setActionParameter( const char *inParameterName,
                                 const char *inParameterValue );
        
        void setActionParameter( const char *inParameterName,
                                 int inParameterValue );

        void setActionParameter( const char *inParameterName,
                                 double inParameterValue );
        



        // default behavior is to start request immediately upon
        // makeActive (this is great for single-action pages)
        // override makeActive to change this behavior
        virtual void makeActive( char inFresh );

        virtual void step();
        


        char isError();

        char isResponseReady();
        

        // result destroyed by caller
        char *getResponse( const char *inPartName );
        
        int getResponseInt( const char *inPartName );
        
        double getResponseDouble( const char *inPartName );
        


    protected:
        
        void startRequest();

        
        char *mActionName;

        SimpleVector<char*> mActionParameterNames;
        SimpleVector<char*> mActionParameterValues;
        
        char mAttachAccountHmac;
        
        int mNumResponseParts;
        
        SimpleVector<char*> mResponsePartNames;
        SimpleVector<char*> mResponseParts;
        

        int mWebRequest;
        char mResponseReady;
    };

        

#endif
