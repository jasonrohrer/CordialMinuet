

// generates a 6x6 magic square into a newly-allocated length 36 array
// using inSeed
int *generateMagicSquare6( unsigned int inSeed );


// generates inNumSquares squares, using inSeed as
// the random seed starting point for the first square
// and continuing without reseeding for all subequent squares
// returns a newly-allocated array of newly-allocated length-36 arrays
int **generateMagicSquare6( unsigned int inSeed, int inNumSquares );
