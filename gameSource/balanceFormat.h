
#include <stdlib.h>


// format balance values that may have 4 fractional digits

// if inForceFullPrecision is true (defaults to false), then all four
// fractional digits are show, even if the last two are 00
// otherwise, only necessary pairs of fractional digits are shown
// (examples:  14.10   14.01   14.0110   14.0111  )

// outFullPrecision is a pointer to where "full precision was used" 
// flag is optionally returned.
char *formatBalance( double inBalance, 
                     char inForceFullPrecision = false,
                     char *outFullPrecision = NULL );


// formats a dollar value so that values above 1000 are truncated and 
// abbreviated with K, M, etc.
// inSpaceBeforeUnit controls "$100M" vs "$100 M" behavior
//
// if inDollarSign is false, we assume that inDollarAmount is not dollars
// but some other integer, non-fractional value.
char *formatDollarStringLimited( double inDollarAmount,
                                 char inSpaceBeforeUnit = true,
                                 char inDollarSign = true );
