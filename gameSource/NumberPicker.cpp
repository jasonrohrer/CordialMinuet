#include "NumberPicker.h"

#include "minorGems/util/stringUtils.h"


#include <math.h>



NumberPicker::NumberPicker( Font *inDisplayFont, 
                            double inX, double inY,
                            int inMaxMainDigits,
                            int inMaxFractionDigits )
        : PageComponent( inX, inY ),
          mFont( inDisplayFont ),
          mMaxTotalDigits( inMaxMainDigits + inMaxFractionDigits ),
          mMaxMainDigits( inMaxMainDigits ),
          mMaxFractionDigits( inMaxFractionDigits ),
          mMax( -1 ), mMin( 0 ) {

    
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
        

        mUpButtons[i] =  new SpriteButton( "numberUp.tga", 
                                           nextX, spacing,
                                           1, 1, true );
        
        mDownButtons[i] =  new SpriteButton( "numberDown.tga", 
                                             nextX, -spacing,
                                             1, 1, true  );

        nextX -= spacing;
        
        addComponent( mUpButtons[i] );
        addComponent( mDownButtons[i] );
        
        mUpButtons[i]->setDrawBackground( false );
        mDownButtons[i]->setDrawBackground( false );
        
        mUpButtons[i]->addActionListener( this );
        mDownButtons[i]->addActionListener( this );
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
    }



void NumberPicker::setMax( double inMax ) {
    mMax = inMax;
    setValue( getValue() );
    }



void NumberPicker::setMin( double inMin ) {
    mMin = inMin;
    setValue( getValue() );
    }



void NumberPicker::setValue( double inValue ) {
    if( inValue == 20.20 ) {
        printf( "here = %f\n", inValue );
        }
    
    if( mMax >= 0 && 
        inValue > mMax ) {

        inValue = mMax;
        }
    if( inValue < mMin ) {
        inValue = mMin;
        }

    double factor = pow( 10, mMaxFractionDigits );
    

    double roundingTerm = 1.0 / pow( 10, mMaxFractionDigits + 1 );
    
    inValue += roundingTerm;

    for( int i=0; i<mMaxTotalDigits; i++ ) {
        mDigits[i] = ( (int)( inValue * factor ) ) % 10;
        
        factor /= 10;
        }
    }



double NumberPicker::getValue() {
    double value = 0;
    
    double factor = 1.0 / pow( 10, mMaxFractionDigits );
    

    for( int i=0; i<mMaxTotalDigits; i++ ) {
        value += factor * mDigits[i];
        
        factor *= 10;
        }

    return value;
    }




void NumberPicker::draw() {
    
    setDrawColor( 1, 1, 1, 1 );
    
    double spacing = mFont->getFontHeight();
    double nextX = mMaxFractionDigits * spacing;
    
    for( int i=0; i<mMaxTotalDigits; i++ ) {
        if( mMaxFractionDigits > 0 &&
            i == mMaxFractionDigits ) {
            // done with fraction digits...
            
            // period
            doublePair pos = { nextX, 0 };
            
            mFont->drawString( ".", pos );

            nextX -= spacing;
            }

        doublePair pos = { nextX, 0 };
            
        
        char *s = autoSprintf( "%d", mDigits[i] );

        mFont->drawString( s, pos );
        
        delete [] s;
        
        nextX -= spacing;
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
    printf( "New value = %f\n", getValue() );
    setValue( getValue() );
    }

