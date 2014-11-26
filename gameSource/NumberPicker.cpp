#include "NumberPicker.h"

#include "minorGems/util/stringUtils.h"


#include <math.h>
#include <limits.h>




static void setArrowButtonStyle( Button *inButton ) {
    inButton->setDrawBackground( false );
    
    inButton->setHoverColor( 1, 1, 1, 1 );
    inButton->setNoHoverColor( 0.5, 0.5, 0.5, 1 );
    inButton->setDragOverColor( 0.25, 0.25, 0.25, 1 );
    }


NumberPicker::NumberPicker( Font *inDisplayFont, 
                            double inX, double inY,
                            int inMaxMainDigits,
                            int inMaxFractionDigits,
                            const char *inLabelText,
                            char inUseCommas )
        : PageComponent( inX, inY ),
          mFont( inDisplayFont ),
          mMaxTotalDigits( inMaxMainDigits + inMaxFractionDigits ),
          mMaxMainDigits( inMaxMainDigits ),
          mMaxFractionDigits( inMaxFractionDigits ),
          mUsableDigits( inMaxMainDigits + inMaxFractionDigits ),
          mAdjustable( true ),
          mUseCommas( inUseCommas ),
          mLabelText( NULL ),
          mMax( DBL_MAX ), mMin( 0 ) {

    
    mUpButtons = new SpriteButton *[mMaxTotalDigits];
    mDownButtons = new SpriteButton *[mMaxTotalDigits];
    mDigits = new int[ mMaxTotalDigits ];

    double spacing = mFont->getFontHeight();

    double nextX = mMaxFractionDigits * spacing;

    for( int i=0; i<mMaxTotalDigits; i++ ) {
        mDigits[i] = 0;
        
        if( mMaxFractionDigits > 0 &&
            i == mMaxFractionDigits ) {
            // done with fraction digits...
            // leave room for period
            nextX -= spacing;
            }
        
        if( mUseCommas && 
            mMaxMainDigits > 4 &&
            ( i - mMaxFractionDigits ) != 0 &&
            ( i - mMaxFractionDigits ) % 3 == 0 ) {
            
            // leave room for comma
            nextX -= spacing;
            }

        mUpButtons[i] =  new SpriteButton( "numberUp.tga", 
                                           nextX, spacing,
                                           1, 1, true );
        
        mDownButtons[i] =  new SpriteButton( "numberDown.tga", 
                                             nextX, 
                                             (-spacing) + .15625 * spacing,
                                             1, 1, true  );

        nextX -= spacing;
        
        addComponent( mUpButtons[i] );
        addComponent( mDownButtons[i] );
        
        mUpButtons[i]->addActionListener( this );
        mDownButtons[i]->addActionListener( this );

        setArrowButtonStyle( mUpButtons[i] );
        setArrowButtonStyle( mDownButtons[i] );
        }


    if( inLabelText != NULL ) {
        mLabelText = stringDuplicate( inLabelText );
        }
    }



NumberPicker::~NumberPicker() {
    for( int i=0; i<mMaxTotalDigits; i++ ) {
        delete mUpButtons[i];
        delete mDownButtons[i];
        }
    
    delete [] mUpButtons;
    delete [] mDownButtons;
    
    delete [] mDigits;

    if( mLabelText != NULL ) {
        delete [] mLabelText;
        }
    }


double NumberPicker::getMaxPossible() {

    int *tempDigits = new int[ mMaxTotalDigits ];
    memcpy( tempDigits, mDigits, mMaxTotalDigits * sizeof( int ) );

    for( int i=0; i<mMaxTotalDigits; i++ ) {
        tempDigits[i] = 9;
        }
    
    double value = getValue( tempDigits );
    
    delete [] tempDigits;

    return value;
    }



void NumberPicker::setMax( double inMax ) {
    // truncate it to our fractional range
    double factor = pow( 10, mMaxFractionDigits );

    mMax = floor( inMax * factor ) / factor;

    //mMax = inMax;

    printf( "Passed max:  %f, floored max: %f\n", inMax, mMax );

    
    double maxPossible = getMaxPossible();
    
    if( mMax > maxPossible ) {
        mMax = maxPossible;
        }
    
    if( mMax < mMin ) {
        setMin( mMax );
        }


    double oldValue = getValue();
    
    
    // count how many digits are used
    setValue( inMax );
    
    mUsableDigits = mMaxTotalDigits;
    
    for( int i=mMaxTotalDigits-1; i>=0; i-- ) {
        
        if( mDigits[i] == 0 ) {
            mUsableDigits--;
            }
        else {
            break;
            }
        }
    
    if( mUsableDigits < mMaxFractionDigits + 1 ) {
        // always show complete fractional part, if that's all we have
        mUsableDigits = mMaxFractionDigits + 1;
        }
    
    
    for( int i=0; i<mMaxTotalDigits; i++ ) {
        if( i < mUsableDigits ) {
            mUpButtons[i]->setVisible( true );
            mDownButtons[i]->setVisible( true );
            }
        else {
            mUpButtons[i]->setVisible( false );
            mDownButtons[i]->setVisible( false );
            }
        }
    
    
    
    // restore value (and truncate it with new max)
    setValue( oldValue );
    }



void NumberPicker::setMin( double inMin ) {
    mMin = inMin;

    if( mMax < inMin ) {
        setMax( inMin );
        }

    setValue( getValue() );
    }




void NumberPicker::setValue( double inValue, int inDigits[] ) {
    double factor = pow( 10, mMaxFractionDigits );
    

    double roundingTerm = 1.0 / pow( 10, mMaxFractionDigits + 1 );
    
    double roundedValue = inValue + roundingTerm;

    for( int i=0; i<mMaxTotalDigits; i++ ) {
        inDigits[i] = (int)( fmod( floor( roundedValue * factor ), 10 ) );
        
        factor /= 10;
        }
    }



void NumberPicker::setValue( double inValue ) {
    if( mMax >= 0 && 
        inValue >= mMax ) {

        inValue = mMax;
        }
    
    if( inValue <= mMin ) {
        inValue = mMin;
        }
    

    setValue( inValue, mDigits );
    
    for( int i=0; i<mUsableDigits; i++ ) {
            
        // can digit be tweaked up to a different value?
        int upTweaked = getTweakedValue( i, +1 );
        if( upTweaked != mDigits[i] ) {
            mUpButtons[i]->setVisible( true );
            }
        else {
            mUpButtons[i]->setVisible( false );
            }
        
        // can digit be tweaked down to a different value?
        double downTweaked = getTweakedValue( i, -1 );
        if( downTweaked != mDigits[i] ) {
            mDownButtons[i]->setVisible( true );
            }
        else {
            mDownButtons[i]->setVisible( false );
            }
        }
    
    if( !mAdjustable ) {
        for( int i=0; i<mUsableDigits; i++ ) {
            mUpButtons[i]->setVisible( false );
            }

        for( int i=0; i<mUsableDigits; i++ ) {
            mDownButtons[i]->setVisible( false );
            }
        }
    }



double NumberPicker::getValue( int inDigits[] ) {
    double value = 0;
    
    double factor = 1.0 / pow( 10, mMaxFractionDigits );
    

    for( int i=0; i<mMaxTotalDigits; i++ ) {
        value += factor * inDigits[i];
        
        factor *= 10;
        }

    return value;
    }



void NumberPicker::setAdjustable( char inAdjustable ) {
    mAdjustable = inAdjustable;
    setValue( getValue() );
    }



double NumberPicker::getValue() {
    return getValue( mDigits );
    }



int NumberPicker::getTweakedValue( int inDigitToTweak, int inTweakDelta ) {
    int *tempDigits = new int[ mMaxTotalDigits ];
    memcpy( tempDigits, mDigits, mMaxTotalDigits * sizeof( int ) );
    
    tempDigits[inDigitToTweak] += inTweakDelta;
    
    if( tempDigits[inDigitToTweak] >= 10 ) {
        tempDigits[inDigitToTweak] -= 10;
        }
    else if( tempDigits[inDigitToTweak] < 0 ) {
        tempDigits[inDigitToTweak] += 10;
        }

    double tempValue = getValue( tempDigits );
    
    
    if( tempValue > mMax ) {
        tempValue = mMax;
        }
    else if( tempValue < mMin ) {
        tempValue = mMin;
        }
    
    setValue( tempValue, tempDigits );
    

    int returnValue = tempDigits[inDigitToTweak];

    delete [] tempDigits;
    

    return returnValue;
    }





void NumberPicker::draw() {
    
    setDrawColor( 1, 1, 1, 1 );
    
    double spacing = mFont->getFontHeight();
    double nextX = mMaxFractionDigits * spacing;
    
    for( int i=0; i<mUsableDigits; i++ ) {
        if( mMaxFractionDigits > 0 &&
            i == mMaxFractionDigits ) {
            // done with fraction digits...
            
            // period
            doublePair pos = { nextX, 0 };
            
            mFont->drawString( ".", pos );

            nextX -= spacing;
            }

        if( mUseCommas &&
            mMaxMainDigits > 4 &&
            ( i - mMaxFractionDigits ) != 0 &&
            ( i - mMaxFractionDigits ) % 3 == 0 ) {
            
            doublePair pos = { nextX, 0 };
            
            mFont->drawString( ",", pos );

            nextX -= spacing;
            }
        

        doublePair pos = { nextX, 0 };
            
        
        char *s = autoSprintf( "%d", mDigits[i] );

        mFont->drawString( s, pos );
        
        delete [] s;
        
        nextX -= spacing;
        }
    
    setDrawColor( 1, 1, 1, 1 );

    
    if( mLabelText != NULL ) {
        doublePair labelPos = { nextX, 0 };
        
        mFont->drawString( mLabelText, labelPos, alignRight );
        }
    }



void NumberPicker::actionPerformed( GUIComponent *inTarget ) {
    for( int i=0; i<mMaxTotalDigits; i++ ) {
        if( inTarget == mUpButtons[i] ) {
            mDigits[i] ++;
            if( mDigits[i] > 9 ) {
                mDigits[i] -= 10;
                }
            }
        if( inTarget == mDownButtons[i] ) {
            mDigits[i] --;
            if( mDigits[i] < 0 ) {
                mDigits[i] += 10;
                }
            }
        }
    setValue( getValue() );
    
    fireActionPerformed( this );
    }

