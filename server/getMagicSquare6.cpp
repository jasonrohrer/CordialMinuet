
#include <stdio.h>

#include "magicSquare6.h"


void usage() {
    printf( "Usage:  getMagicSquare6 seedNumber\n\n" );
    printf( "Example:  getMagicSquare6 19282\n" );
    }
    


int main( int inNumArgs, char **inArgs ) {
    
    if( inNumArgs != 2 ) {
        
        usage();
        return 0;
        }
    
    unsigned int seed;
    
    sscanf( inArgs[1], "%u", &seed );
    

    int *square = generateMagicSquare6( seed );
    
    printf( "%d", square[0] );
    
    for( int i=1; i<36; i++ ) {
        printf( "#%d", square[i] );
        }
    

    return 0;
    }
