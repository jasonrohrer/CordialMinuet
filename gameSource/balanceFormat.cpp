#include "balanceFormat.h"

#include <string.h>

#include "minorGems/util/stringUtils.h"



// adds grouping commas
// inString deleted by this call (or returned directly)
static char *addCommas( char *inString ) {
    
    int periodPosition =  0;
    
    int length = strlen( inString );
    
    for( int i=0; i<length; i++ ) {
        if( inString[i] == '.' ) {
            periodPosition = i;
            break;
            }
        }

    // ignore $
    int wholeDigitLength = periodPosition - 1;
    
    if( wholeDigitLength < 4 ) {
        // no commas
        return inString;
        }
    
    int numCommas = wholeDigitLength / 3;
    
    if( wholeDigitLength % 3 == 0 ) {
        numCommas --;
        }
    
    char *newString = new char[ length + numCommas + 1 ];
    newString[ length + numCommas ] = '\0';
    

    int newPos = length + numCommas - 1;
    
    int oldPos = length - 1;
    
    while( oldPos >= periodPosition ) {
        newString[newPos] = inString[oldPos];
        
        oldPos --;
        newPos --;
        }
    
    int batchCount = 0;
    
    while( oldPos >= 0 ) {
        
        newString[newPos] = inString[oldPos];

        oldPos --;
        newPos --;

        batchCount ++;
        
        if( oldPos >= 0 && inString[oldPos] != '$' && batchCount == 3 ) {
            newString[newPos] = ',';
            newPos --;
            
            batchCount = 0;
            }
        }

    delete [] inString;
    return newString;
    }



char *formatBalance( double inBalance, 
                     char inForceFullPrecision,
                     char *outFullPrecision ) {

    char *valueString = autoSprintf( "$%.4f", inBalance );

    int length = strlen( valueString );

    if( inForceFullPrecision ||
        valueString[ length - 2 ] != '0' ||
        valueString[ length - 1 ] != '0' ) {
        
        if( outFullPrecision != NULL ) {
            *outFullPrecision = true;
            }
        return addCommas( valueString );
        }
    
    // else 00 at end, and precision not forced
    
    // truncate
    valueString[length - 2] = '\0';
    
    if( outFullPrecision != NULL ) {
        *outFullPrecision = false;
        }
    
    return addCommas( valueString );
    }

