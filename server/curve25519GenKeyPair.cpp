#include "minorGems/crypto/cryptoRandom.h"
#include "minorGems/crypto/keyExchange/curve25519.h"
#include "minorGems/formats/encodingUtils.h"


#include <stdlib.h>
#include <stdio.h>
#include <string.h>


static void usage() {
    printf( "Generates a secret key and matching "
            "secret key from scratch\n\n" );
    
    
    printf( "Outputs 32-bit keys as hex-encoded,\n"
            "64-character strings.\n\n" );
    
    

    printf( "Usage:\n" );
    
    printf( "  curve25519GenKeyPair\n\n" );


    printf( "Prints:\n" );

    printf( "  secret_key\n" );
    printf( "  public_key\n\n" );
    

    printf( "Example:\n" );
    
    printf( 
     "  curve25519GenKeyPair\n\n" );


    printf( "Prints:\n" );

    printf( 
      "  D4663364ACCADFC65819492786126BA203AE4BD0B4C8FE1E52756CBD183F9D1C\n" );
    printf( 
      "  6B7A77C655E1AF516F2771BE7D07F118D5D333CEA390FE954C83F357ADAE4059\n" );

    exit(0);
    }



int main( int inNumArgs, char **inArgs ) {
    if( inNumArgs != 1 ) {
        usage();
        }

    unsigned char secretKey[32];
    unsigned char ourPublicKey[32];
    
    char gotRandom = getCryptoRandomBytes( secretKey, 32 );
    
    if( !gotRandom ) {
        
        printf( "Failed to generate crypto-secure random bytes.\n" );
        return 1;
        }
    
        
    

    

    curve25519_genPublicKey( ourPublicKey, secretKey );
        

    char *secretKeyHex = hexEncode( secretKey, 32 );
    char *ourPublicKeyHex = hexEncode( ourPublicKey, 32 );
    
    printf( "%s\n%s\n", secretKeyHex, ourPublicKeyHex );
    

    delete [] ourPublicKeyHex;
    delete [] secretKeyHex;

    return 0;
    }

    

