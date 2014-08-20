#include "magicSquare6.h"


#include "minorGems/util/random/JenkinsRandomSource.h"

static JenkinsRandomSource randSource( time( NULL ) );





static int getMagicSum( int inD ) {
    return ( inD * ( inD * inD + 1 ) ) / 2;
    }



static int measureMagicDeviation( int *inArray, int inD ) {
    int magicSum = getMagicSum( inD );
    
    int totalDeviation = 0;
    
    for( int i=0; i<inD; i++ ) {
        int colSum = 0;
        int rowSum = 0;
        
        for( int j=0; j<inD; j++ ) {
            colSum += inArray[j * inD + i];
            rowSum += inArray[i * inD + j];
            }
        
        if( colSum > magicSum ) {
            totalDeviation += ( colSum - magicSum );
            }
        else {
            totalDeviation += ( magicSum - colSum );
            }

        if( rowSum > magicSum ) {
            totalDeviation += ( rowSum - magicSum );
            }
        else {
            totalDeviation += ( magicSum - rowSum );
            }
        }
    
    int diagASum = 0;
    int diagBSum = 0;
    
    for( int i=0; i<inD; i++ ) {
        diagASum += inArray[i * inD + i];
        diagBSum += inArray[(inD - i - 1) * inD + i];
        }

    
    if( diagASum > magicSum ) {
        totalDeviation += ( diagASum - magicSum );
        }
    else {
        totalDeviation += ( magicSum - diagASum );
        }
    
    if( diagBSum > magicSum ) {
        totalDeviation += ( diagBSum - magicSum );
        }
    else {
        totalDeviation += ( magicSum - diagBSum );
        }
    
    return totalDeviation;
    }





// pick two cells and swap
// returns true, because swap always done
static char swapRandom( int *inArray, int inNumCells ) {
    int swapPosA = randSource.getRandomBoundedInt( 0, inNumCells - 1 );
    
    int swapPosB = randSource.getRandomBoundedInt( 0, inNumCells - 1 );
    
    int temp = inArray[ swapPosA ];
    
    inArray[ swapPosA ] = inArray[ swapPosB ];
    inArray[ swapPosB ] = temp;
    
    return true;
    }




static char fillMagicRandom( int *inArray, int inD ) {
    int maxNumber = inD * inD;
        
    for( int i=0; i<maxNumber; i++ ) {
        inArray[i] = i + 1;
        }
    
    // Knuth/Durstenfeld/Fisher/Yates shuffle
    
    for( int i=0; i<maxNumber; i++ ) {
        
        int swapPos = randSource.getRandomBoundedInt( i, maxNumber - 1 );
        
        int temp = inArray[i];
        inArray[i] = inArray[swapPos];
        inArray[swapPos] = temp;
        }
    }




static char checkMagic( int *inArray, int inD ) {
    int magicSum = getMagicSum( inD );

    for( int i=0; i<inD; i++ ) {
        int colSum = 0;
        int rowSum = 0;
        
        for( int j=0; j<inD; j++ ) {
            colSum += inArray[j * inD + i];
            rowSum += inArray[i * inD + j];
            }
        
        if( colSum != magicSum || rowSum != magicSum ) {
            return false;
            }
        }
    
    int diagASum = 0;
    int diagBSum = 0;
    
    for( int i=0; i<inD; i++ ) {
        diagASum += inArray[i * inD + i];
        diagBSum += inArray[(inD - i - 1) * inD + i];
        }
    if( diagASum != magicSum || diagBSum != magicSum ) {
        return false;
        }

    return true;
    }




// uses algorithm described in 
// "Yet Another Local Search Method for Constraint Solving"
// by Codognet and Diaz 
// Tries for inTryLimit attempts (or forever on -1)
// After that, scrambles with inNumScramblesOnRetry random pair swaps, then
// restarts (or fully-randomized square, if -1)
static void findMagicSquareTabuSearch( int *inArray, int inD, 
                                       int inTryLimit = -1, 
                                       int inNumScramblesOnRetry = -1 ) {
    int magicSum = getMagicSum( inD );
    
    int numCells = inD * inD;
    
    int *rowErrors = new int[ inD ];
    int *columnErrors = new int[ inD ];
    int diagErrors[2];

    int *cellErrors = new int[ numCells ];

    
    char *tabuFlags = new char[ numCells ];
    int *tabuTenures = new int[ numCells ];
    for( int i=0; i<numCells; i++ ) {
        tabuFlags[i] = false;
        tabuTenures[i] = 0;
        }
    
    // values suggested from paper
    //int tabuTenureLimit = inD - 1;
    //int tabuTableListLimit = inD * inD / 6;
    //float tabuResetPercentage = .10;
    
    // my own values, from experimentation
    int tabuTenureLimit = (int)( 2 * inD );
    int tabuTableListLimit = (int)( .1 * inD + 2 );
    float tabuResetPercentage = 0.5;
    
    int numTries = 0;
    int totalTries = 0;
    
    while( ! checkMagic( inArray, inD ) ) {
        int oldDeviation = measureMagicDeviation( inArray, inD );

        //printf( "Deviation %d\n", oldDeviation );
        
        
        if( inTryLimit > 0 && numTries > inTryLimit ) {
            
            // to avoid ruts in PRNG that line up with phase of our
            // algorithm and stick us in a "no magic squares" space
            // do one extra try each time, to change our phase
            inTryLimit++;


            // too many tries, start over
            if( inNumScramblesOnRetry == -1 ||
                totalTries > inTryLimit * 10 ) {
                // full random restart
                fillMagicRandom( inArray, inD );
                totalTries = 0;
                }
            else {
                // try scrambling instead
                for( int i=0; i<inNumScramblesOnRetry; i++ ) {
                    swapRandom( inArray, numCells );
                    }
                }
            

            numTries = 0;
            
            for( int i=0; i<numCells; i++ ) {
                tabuFlags[i] = false;
                tabuTenures[i] = 0;
                }
            }
        
        numTries++;
        totalTries++;
        
        // increment tabu tenures
        int tabuSize = 0;
        for( int i=0; i<numCells; i++ ) {
            if( tabuFlags[i] ) {
                tabuTenures[i] ++;
                
                if( tabuTenures[i] > tabuTenureLimit ) {
                    // been on tabu list too long
                    tabuFlags[i] = 0;
                    tabuTenures[i] = 0;
                    }
                else {
                    tabuSize ++;
                    }
                }
            }
        
        if( tabuSize > tabuTableListLimit ) {
            for( int i=0; i<numCells; i++ ) {
                // reset some at random
                if( tabuFlags[i] ) {
                    if( randSource.getRandomFloat() <= tabuResetPercentage ) {
                        
                        tabuFlags[i] = 0;
                        tabuTenures[i] = 0;
                        }
                    }
                }
            
            }
        

        int diagASum = 0;
        int diagBSum = 0;

        for( int i=0; i<inD; i++ ) {
            int colSum = 0;
            int rowSum = 0;
            
            for( int j=0; j<inD; j++ ) {
                colSum += inArray[j * inD + i];
                rowSum += inArray[i * inD + j];
                }
            
            rowErrors[i] = rowSum - magicSum;
            columnErrors[i] = colSum - magicSum;
        
            diagASum += inArray[i * inD + i];
            diagBSum += inArray[(inD - i - 1) * inD + i];
            }
        
        
        diagErrors[0] = diagASum - magicSum;
        diagErrors[1] = diagBSum - magicSum;

        
        for( int y=0; y<inD; y++ ) {
            for( int x=0; x<inD; x++ ) {
                int i = y * inD + x;
                
                cellErrors[i] = rowErrors[y] + columnErrors[x];
                
                if( y == x ) {
                    // cell on diag A
                    cellErrors[i] += diagErrors[0];
                    }
                else if( y == x - inD + 1 ) {
                    // cell on diag B
                    cellErrors[i] += diagErrors[1];
                    }
                
                if( cellErrors[i] < 0 ) {
                    cellErrors[i] = -cellErrors[i];
                    }
                }
            }
        

        // look for non-tabu cell with biggest error
        
        int biggestError = 0;
        int biggestErrorCell = -1;
        
        for( int i=0; i<numCells; i++ ) {
            
            if( !tabuFlags[i] ) {
                if( cellErrors[i] > biggestError ) {
                    biggestError = cellErrors[i];
                    biggestErrorCell = i;
                    }
                }
            }
        
        if( biggestErrorCell != -1 ) {
                
            // look for best swap that at least makes some improvement
            int bestSwapDeviation = inD * magicSum;
            int bestSwapIndex = -1;
            
            for( int i=0; i<numCells; i++ ) {
                
                if( i != biggestErrorCell && ! tabuFlags[i] ) {
                    int temp = inArray[biggestErrorCell];
                    inArray[biggestErrorCell] = inArray[i];
                    inArray[i] = temp;
                
                    int deviation = measureMagicDeviation( inArray, inD );
                    if( deviation <= bestSwapDeviation ) {
                        bestSwapDeviation = deviation;
                        bestSwapIndex = i;
                        }
                    
                    // swap back
                    temp = inArray[biggestErrorCell];
                    inArray[biggestErrorCell] = inArray[i];
                    inArray[i] = temp;
                    }
                }
            
            if( bestSwapDeviation >= oldDeviation ) {
                // found no improvement (or equal) from this big-error cell
                // at this location
                
                // local minimum
                
                // cell becomes tabu (local minimum that we're
                // taking the best possible step out of)
                tabuFlags[ biggestErrorCell ] = true;
                tabuTenures[ biggestErrorCell ] = 0;
                
                // mark swap partner as tabu too
                //tabuFlags[ bestSwapIndex ] = true;
                //tabuTenures[ bestSwapIndex ] = 0;
                }
            
            // make the swap regardless
            int temp = inArray[biggestErrorCell];
            inArray[biggestErrorCell] = inArray[bestSwapIndex];
            inArray[bestSwapIndex] = temp;
            
            //printf( "Swapping %d with %d\n", 
            //biggestErrorCell, bestSwapIndex );
            }
        

        }
    
    
    delete [] columnErrors;
    delete [] rowErrors;
    delete [] cellErrors;

    
    delete [] tabuFlags;
    delete [] tabuTenures;
    }














// returns a random 6x6 magic square in newly-allocated array
// using best known parameters for tabu search
static int *findMagicSquare6Fast() {

    int *result = new int[ 36 ];
    
    fillMagicRandom( result, 6 );

    findMagicSquareTabuSearch( result, 6, 1000, 35 );
    
    return result;
    }




int *generateMagicSquare6( unsigned int inSeed ) {
    randSource.reseed( inSeed );

    return findMagicSquare6Fast();
    }




int **generateMagicSquare6( unsigned int inSeed, int inNumSquares ) {
    int **results = new int*[ inNumSquares ];
    
    // use seed for first only
    if( inNumSquares > 0 ) {
        results[0] = generateMagicSquare6( inSeed );
        }

    // rest continue using same seed progression
    for( int i=1; i<inNumSquares; i++ ) {
        
        results[i] = findMagicSquare6Fast();
        }
    
    return results;
    }

    
