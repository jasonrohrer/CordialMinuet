#include "minorGems/crypto/cryptoRandom.h"
#include "minorGems/crypto/keyExchange/curve25519.h"
#include "minorGems/formats/encodingUtils.h"


#include <stdlib.h>
#include <stdio.h>
#include <string.h>


static void usage() {
    printf( "Generates a shared secret key from our secret key \n"
            "and a communication partner's public key\n\n" );
    
    printf( "Accepts and outputs 32-bit keys as hex-encoded,\n"
            "64-character strings.\n\n" );
    
    

    printf( "Usage:\n" );
    
    printf( "  curve25519GenKeys our_secret_key other_public_key\n\n" );


    printf( "Prints:\n" );

    printf( "  shared_secret_key\n\n" );
    

    printf( "Example:\n" );
    
    printf( 
     "  curve25519GenSharedKey "
     "596187F726D13B9BDE01AC6AFE584763095E16F38EED52CA7643248F4258CBD1 "
     "5A1444E9094E04F28C6CA7FBC12AD1753454CC51C58A38E87034FEE86D8A9F17\n\n" );


    printf( "Prints:\n" );

    printf( "  09B6F0DD916F857AFCC4319BF96E466810B8930A2660A360738DB236813A715E\n" );

    exit(0);
    }



int main( int inNumArgs, char **inArgs ) {
    if( inNumArgs != 3 ) {
        usage();
        }


    char *ourSecretKeyHex = inArgs[1];
    
    if( strlen( ourSecretKeyHex ) != 64 ) {
        usage();
        }
    

    unsigned char *ourSecretKey = hexDecode( ourSecretKeyHex );
    
    if( ourSecretKey == NULL ) {
        usage();
        }


    char *otherPublicKeyHex = inArgs[2];
    
    if( strlen( otherPublicKeyHex ) != 64 ) {
        usage();
        }
    

    unsigned char *otherPublicKey = hexDecode( otherPublicKeyHex );
    
    if( otherPublicKey == NULL ) {
        delete [] ourSecretKey;
        usage();
        }


    unsigned char sharedSecretKey[32];
    

    curve25519_genSharedSecretKey( sharedSecretKey, 
                                   ourSecretKey, otherPublicKey );
    

    char *sharedSecretKeyHex = hexEncode( sharedSecretKey, 32 );
    
    printf( "%s\n", sharedSecretKeyHex );
    

    delete [] otherPublicKey;
    delete [] ourSecretKey;
    delete [] sharedSecretKeyHex;

    return 0;
    }

    

