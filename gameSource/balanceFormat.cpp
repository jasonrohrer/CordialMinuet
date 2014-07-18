#include "balanceFormat.h"

#include <string.h>

#include "minorGems/util/stringUtils.h"



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
        return valueString;
        }
    
    // else 00 at end, and precision not forced
    
    // truncate
    valueString[length - 2] = '\0';
    
    if( outFullPrecision != NULL ) {
        *outFullPrecision = false;
        }
    
    return valueString;
    }

