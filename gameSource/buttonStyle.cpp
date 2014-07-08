#include "buttonStyle.h"


void setButtonStyle( Button *inButton ) {
    inButton->setHoverColor( 1, 1, 1, 1 );
    inButton->setNoHoverColor( 1, 1, 1, 1 );
    inButton->setDragOverColor( 1, 1, 1, 1 );

    inButton->setFillColor( 0, 0, 0, 1 );
    
    inButton->setDragOverFillColor( 0, 0, 0, 1 );
    
    inButton->setBracketCoverLength( 16 );
    }

