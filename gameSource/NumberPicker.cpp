#include "NumberPicker.h"



NumberPicker::NumberPicker( Font *inDisplayFont, 
                            double inX, double inY,
                            int inMaxMainDigits,
                            int inMaxFractionDigits )
        : PageComponent( inX, inY ),
          mFont( inDisplayFont ),
          mMaxTotalDigits( inMaxMainDigits + inMaxFractionDigits ),
          mMaxMainDigits( inMaxMainDigits ),
          mMaxFractionDigits( inMaxFractionDigits ) {

    

    }



NumberPicker::~NumberPicker() {

    }



void NumberPicker::draw() {
    
    }

