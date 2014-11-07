#include "SendCheckGlobalPage.h"

#include "buttonStyle.h"

#include "message.h"
#include "accountHmac.h"


#include "minorGems/game/Font.h"
#include "minorGems/game/game.h"

#include "minorGems/util/stringUtils.h"
#include "minorGems/util/SettingsManager.h"

#include "minorGems/formats/encodingUtils.h"

#include "minorGems/crypto/hashes/sha1.h"



extern Font *mainFont;


extern char *accountKey;
extern int serverSequenceNumber;

extern double userBalance;
extern double checkCostGlobal;



static inline int min( int a, int b, int c ) {
    if( a <= b && a <= c ) {
        return a;
        }
    if( b <= a && b <= c ) {
        return b;
        }
    return c;
    }



// from wikipedia article on Levenshtein Distance
static int stringDistance( const char *inA, const char *inB ) {
    
    if( strcmp( inA, inB ) == 0 ) {
        return 0;
        }

    int lenA = strlen( inA );
    int lenB = strlen( inB );

    if( lenA == 0 ) {
        return lenB;
        }
    if( lenB == 0 ) {
        return lenA;
        }

    // previous row
    int *v0 = new int[ lenB + 1 ];
    
    // current row
    int *v1 = new int[ lenB + 1 ];
    
    for( int i=0; i<lenB + 1; i++ ) {
        v0[i] = i;
        }
    
    for( int i=0; i<lenA; i++ ) {
        
        v1[0] = i + 1;
        
        for( int j=0; j<lenB; j++ ) {
            int cost = ( inA[i] == inB[j] ) ? 0 : 1;
            v1[j+1] = min( v1[j] + 1, 
                           v0[j+1] + 1,
                           v0[j] + cost );
            }
        
        // copy current row into previous row for next iteration
        memcpy( v0, v1, ( lenB + 1 ) * sizeof( int ) );
        }
    
    
    int returnValue = v1[ lenB ];
    
    delete [] v0;
    delete [] v1;
    
    return returnValue;
    }


typedef struct CountryCodeMap {
        const char *code;
        const char *countryName;
    } CountryCodeMap;


static CountryCodeMap countryMap[] = {
    { "AF", "Afghanistan" },
    { "AX", "Aland Islands" },
    { "AL", "Albania" },
    { "DZ", "Algeria" },
    { "AS", "American Samoa" },
    { "AD", "Andorra" },
    { "AO", "Angola" },
    { "AI", "Anguilla" },
    { "AQ", "Antarctica" },

    // grouped together
    { "AG", "Antigua" },
    { "AG", "Barbuda" },
    { "AG", "Antigua and Barbuda" },

    { "AR", "Argentina" },
    { "AM", "Armenia" },
    { "AW", "Aruba" },
    { "AU", "Australia" },
    { "AT", "Austria" },
    { "AZ", "Azerbaijan" },
    { "BS", "Bahamas" },
    { "BH", "Bahrain" },
    { "BD", "Bangladesh" },
    { "BB", "Barbados" },
    { "BY", "Belarus" },
    { "BE", "Belgium" },
    { "BZ", "Belize" },
    { "BJ", "Benin" },
    { "BM", "Bermuda" },
    { "BT", "Bhutan" },
    { "BO", "Bolivia" },

    // grouped together
    { "BA", "Bosnia" },
    { "BA", "Herzegovina" },
    { "BA", "Bosnia and Herzegovina" },

    { "BW", "Botswana" },
    { "BV", "Bouvet Island" },
    { "BR", "Brazil" },
    { "IO", "British Indian Ocean Territory" },
    
    // extended version of name
    { "BN", "Brunei" },
    { "BN", "Brunei Darussalam" },

    { "BG", "Bulgaria" },
    { "BF", "Burkina Faso" },
    { "BI", "Burundi" },
    { "KH", "Cambodia" },
    { "CM", "Cameroon" },
    { "CA", "Canada" },
    { "CV", "Cape Verde" },
    { "KY", "Cayman Islands" },
    { "CF", "Central African Republic" },
    { "TD", "Chad" },
    { "CL", "Chile" },
    { "CN", "China" },
    { "CX", "Christmas Island" },
    
    // two names
    { "CC", "Cocos Islands" },
    { "CC", "Keeling Islands" },
    
    { "CO", "Colombia" },
    { "KM", "Comoros" },
    { "CG", "Congo" },
    
    // extended name
    { "CD", "Democratic Republic of the Congo" },
    { "CD", "Congo" },
    
    { "CK", "Cook Islands" },
    { "CR", "Costa Rica" },
    
    // french name
    { "CI", "Cote D'Ivoire" },
    { "CI", "Ivory Coast" },
    
    { "HR", "Croatia" },
    { "CU", "Cuba" },
    { "CY", "Cyprus" },
    { "CZ", "Czech Republic" },
    { "DK", "Denmark" },
    { "DJ", "Djibouti" },
    { "DM", "Dominica" },
    { "DO", "Dominican Republic" },
    { "EC", "Ecuador" },
    { "EG", "Egypt" },
    { "SV", "El Salvador" },
    { "GQ", "Equatorial Guinea" },
    { "ER", "Eritrea" },
    { "EE", "Estonia" },
    { "ET", "Ethiopia" },

    // spanish name
    { "FK", "Falkland Islands" },
    { "FK", "Islas Malvinas" },

    { "FO", "Faroe Islands" },
    { "FJ", "Fiji" },
    { "FI", "Finland" },
    { "FR", "France" },
    { "GF", "French Guiana" },
    { "PF", "French Polynesia" },
    { "TF", "French Southern Territories" },
    { "GA", "Gabon" },
    { "GM", "Gambia" },
    { "GE", "Georgia" },
    { "DE", "Germany" },
    { "GH", "Ghana" },
    { "GI", "Gibraltar" },
    { "GR", "Greece" },
    { "GL", "Greenland" },
    { "GD", "Grenada" },
    { "GP", "Guadeloupe" },
    { "GU", "Guam" },
    { "GT", "Guatemala" },
    { "GG", "Guernsey" },
    { "GN", "Guinea" },
    { "GW", "Guinea-Bissau" },
    { "GY", "Guyana" },
    { "HT", "Haiti" },
    
    // grouped together
    { "HM", "Heard Island" },
    { "HM", "Mcdonald Islands" },
    { "HM", "Heard Island And Mcdonald Islands" },
    
    // two names
    { "VA", "Holy See" },
    { "VA", "Vatican City State" },

    { "HN", "Honduras" },
    { "HK", "Hong Kong" },
    { "HU", "Hungary" },
    { "IS", "Iceland" },
    { "IN", "India" },
    { "ID", "Indonesia" },
    
    // extended name
    { "IR", "Iran" },
    { "IR", "Islamic Republic Of Iran " },
    
    { "IQ", "Iraq" },
    { "IE", "Ireland" },
    { "IM", "Isle Of Man" },
    { "IL", "Israel" },
    { "IT", "Italy" },
    { "JM", "Jamaica" },
    { "JP", "Japan" },
    { "JE", "Jersey" },
    { "JO", "Jordan" },
    { "KZ", "Kazakhstan" },
    { "KE", "Kenya" },
    { "KI", "Kiribati" },
    { "KR", "Korea" },
    { "KW", "Kuwait" },
    { "KG", "Kyrgyzstan" },

    // extended name
    { "LA", "Lao People's Democratic Republic" },
    { "LA", "Laos" },

    { "LV", "Latvia" },
    { "LB", "Lebanon" },
    { "LS", "Lesotho" },
    { "LR", "Liberia" },
    
    // extended name
    { "LY", "Libyan Arab Jamahiriya" },
    { "LY", "Libya" },
    
    { "LI", "Liechtenstein" },
    { "LT", "Lithuania" },
    { "LU", "Luxembourg" },
    { "MO", "Macao" },
    { "MK", "Macedonia" },
    { "MG", "Madagascar" },
    { "MW", "Malawi" },
    { "MY", "Malaysia" },
    { "MV", "Maldives" },
    { "ML", "Mali" },
    { "MT", "Malta" },
    { "MH", "Marshall Islands" },
    { "MQ", "Martinique" },
    { "MR", "Mauritania" },
    { "MU", "Mauritius" },
    { "YT", "Mayotte" },
    { "MX", "Mexico" },

    // extended name
    { "FM", "Micronesia" },
    { "FM", "Federated States Of Micronesia" },

    { "MD", "Moldova" },
    { "MC", "Monaco" },
    { "MN", "Mongolia" },
    { "ME", "Montenegro" },
    { "MS", "Montserrat" },
    { "MA", "Morocco" },
    { "MZ", "Mozambique" },
    { "MM", "Myanmar" },
    { "NA", "Namibia" },
    { "NR", "Nauru" },
    { "NP", "Nepal" },
    
    { "NL", "Netherlands" },
    { "NL", "Holland" },

    { "AN", "Netherlands Antilles" },
    { "AN", "Antilles" },
    { "AN", "Dutch Antilles" },

    { "NC", "New Caledonia" },
    { "NZ", "New Zealand" },
    { "NI", "Nicaragua" },
    { "NE", "Niger" },
    { "NG", "Nigeria" },
    { "NU", "Niue" },
    { "NF", "Norfolk Island" },
    { "MP", "Northern Mariana Islands" },
    { "NO", "Norway" },
    { "OM", "Oman" },
    { "PK", "Pakistan" },
    { "PW", "Palau" },

    { "PS", "Palestinian Territory" },
    { "PS", "Occupied Palestinian Territory" },

    { "PA", "Panama" },
    { "PG", "Papua New Guinea" },
    { "PY", "Paraguay" },
    { "PE", "Peru" },
    { "PH", "Philippines" },
    { "PN", "Pitcairn" },
    { "PL", "Poland" },
    { "PT", "Portugal" },
    { "PR", "Puerto Rico" },
    { "QA", "Qatar" },
    { "RE", "Reunion" },
    { "RO", "Romania" },
    { "RU", "Russian Federation" },
    { "RW", "Rwanda" },
    { "BL", "Saint Barthelemy" },
    { "SH", "Saint Helena" },

    // two names
    { "KN", "Saint Kitts And Nevis" },
    { "KN", "Saint Christopher And Nevis" },

    { "LC", "Saint Lucia" },
    { "MF", "Saint Martin" },
    { "PM", "Saint Pierre And Miquelon" },

    // multiple names
    { "VC", "Saint Vincent" },
    { "VC", "Saint Vincent And Grenadines" },
    { "VC", "Grenadines" },
    
    { "WS", "Samoa" },
    { "SM", "San Marino" },
    { "ST", "Sao Tome And Principe" },
    { "SA", "Saudi Arabia" },
    { "SN", "Senegal" },
    { "RS", "Serbia" },
    { "SC", "Seychelles" },
    { "SL", "Sierra Leone" },
    { "SG", "Singapore" },
    { "SK", "Slovakia" },
    { "SI", "Slovenia" },
    { "SB", "Solomon Islands" },
    { "SO", "Somalia" },
    { "ZA", "South Africa" },

    { "GS", "South Georgia And Sandwich Island" },
    { "GS", "South Georgia" },
    { "GS", "Sandwich Island" },


    { "ES", "Spain" },
    { "LK", "Sri Lanka" },
    { "SD", "Sudan" },
    { "SR", "Suriname" },

    { "SJ", "Svalbard And Jan Mayen" },
    { "SJ", "Jan Mayen" },
    { "SJ", "Svalbard" },

    { "SZ", "Swaziland" },
    { "SE", "Sweden" },
    { "CH", "Switzerland" },

    { "SY", "Syrian Arab Republic" },
    { "SY", "Syria" },

    { "TW", "Taiwan" },
    { "TJ", "Tajikistan" },
    { "TZ", "Tanzania" },
    { "TH", "Thailand" },
    { "TL", "Timor-Leste" },
    { "TG", "Togo" },
    { "TK", "Tokelau" },
    { "TO", "Tonga" },

    { "TT", "Trinidad And Tobago" },
    { "TT", "Trinidad" },
    { "TT", "Tobago" },

    { "TN", "Tunisia" },
    { "TR", "Turkey" },
    { "TM", "Turkmenistan" },

    { "TC", "Turks And Caicos Islands" },
    { "TC", "Turks Islands" },
    { "TC", "Caicos Islands" },

    { "TV", "Tuvalu" },
    { "UG", "Uganda" },
    { "UA", "Ukraine" },
    { "AE", "United Arab Emirates" },

    { "GB", "United Kingdom" },
    { "GB", "UK" },
    { "GB", "U.K." },
    { "GB", "U.K." },
    { "GB", "Scotland" },
    { "GB", "Wales" },
    { "GB", "Great Britain" },

    { "US", "United States" },
    { "UM", "United States Outlying Islands" },
    { "UY", "Uruguay" },
    { "UZ", "Uzbekistan" },
    { "VU", "Vanuatu" },
    { "VE", "Venezuela" },
    { "VN", "Viet Nam" },
    
    { "VG", "British Virgin Islands" },
    
    { "VI", "US Virgin Islands" },
    { "VI", "U.S. Virgin Islands" },
    
    { "WF", "Wallis And Futuna" },
    { "WF", "Wallis" },
    { "WF", "Futuna" },

    { "EH", "Western Sahara" },
    { "YE", "Yemen" },
    { "ZM", "Zambia" },
    { "ZW", "Zimbabwe" },

    { "**", "END" }
    };



// returns index in countryMap
static int findClosestCountryCode( const char *inCountryName ) {
    
    int lowestScore = 9999999;
    int lowestIndex = 0;
    

    char *lowerCaseName = stringToLowerCase( inCountryName );
    unsigned int nameLength = strlen( lowerCaseName );

    int i=0;
    while( strcmp( countryMap[i].code, "**" ) != 0 ) {
        
        char *lowerCaseMapName = 
            stringToLowerCase( countryMap[i].countryName );
        
        int score = stringDistance( lowerCaseName,
                                    lowerCaseMapName );
        
        delete [] lowerCaseMapName;


        if( score < lowestScore ) {
            lowestScore = score;
            lowestIndex = i;
            }
        i++;
        }


    if( lowestScore > 0 ) {
        // try truncation mode to see if we can do even better
        
        i=0;
        while( strcmp( countryMap[i].code, "**" ) != 0 ) {
        
            char *lowerCaseMapName = 
                stringToLowerCase( countryMap[i].countryName );
            
            if( nameLength < strlen( lowerCaseMapName ) ) {
                // try truncated version
                
                lowerCaseMapName[ nameLength ] = '\0';
                
                int score = stringDistance( lowerCaseName,
                                            lowerCaseMapName );

                if( score < lowestScore ) {
                    lowestScore = score;
                    lowestIndex = i;
                    }
                }
            
            delete [] lowerCaseMapName;
            i++;
            }
        }

        
    delete [] lowerCaseName;
        
    
    return lowestIndex;
    }









SendCheckGlobalPage::SendCheckGlobalPage()
        : ServerActionPage( "send_check", true),
          mAmountPicker( mainFont, 176, 201, 6, 2, 
                         translate( "withdrawMoney" ) ),

          mCountryField( mainFont, 33, 126, 12, false, 
                         translate( "country" ),
                         "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                         "abcdefghijklmnopqrstuvwxyz"
                         ".'-, " ),

          mNameField( mainFont, 33, 62, 12, false, 
                      translate( "name" ),
                      "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                      "abcdefghijklmnopqrstuvwxyz"
                      ".'- " ),
          mAddress1Field( mainFont, 33, -2, 12, false, 
                          translate( "address1" ),
                          "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                          "abcdefghijklmnopqrstuvwxyz"
                          ".'- ,0123456789#" ),
          mAddress2Field( mainFont, -144, -66, 10, false, 
                          translate( "address2" ),
                          "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                          "abcdefghijklmnopqrstuvwxyz"
                          ".'- ,0123456789#" ),
          mCityField( mainFont, 224, -66, 5, false, 
                      translate( "city" ),
                      "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                      "abcdefghijklmnopqrstuvwxyz"
                      ".'-, " ),
          mProvinceField( mainFont, -74, -130, 5, false, 
                          translate( "province" ),
                          "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                          "abcdefghijklmnopqrstuvwxyz"
                          ".'-, " ),
          mPostalCodeField( mainFont, 224, -130, 5, true,
                            translate( "postalCode" ),
                            "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
                            "- 0123456789" ),
          mCountryVerified( false ),
          mSendCheckButton( mainFont, 150, -200, 
                             translate( "sendCheckButton" ) ),
          mCancelButton( mainFont, -150, -200, 
                         translate( "cancel" ) ) {



    addComponent( &mSendCheckButton );
    addComponent( &mCancelButton );
    

    setButtonStyle( &mSendCheckButton );
    setButtonStyle( &mCancelButton );
    

    mSendCheckButton.addActionListener( this );
    mCancelButton.addActionListener( this );


    addComponent( &mAmountPicker );
    
    
    

    mFields[0] = &mCountryField;
    mFields[1] = &mNameField;
    mFields[2] = &mAddress1Field;
    mFields[3] = &mAddress2Field;
    mFields[4] = &mCityField;
    mFields[5] = &mProvinceField;
    mFields[6] = &mPostalCodeField;

    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        addComponent( mFields[i] );
        mFields[i]->addActionListener( this );
        }
    
    
    
    // for testing
    /*
    mNameField.setText( "Jason Rohrer" );
    mAddress1Field.setText( "1208 L St." );
    mAddress2Field.setText( "" );
    mCityField.setText( "Davis" );
    mStateField.setText( "CA" );
    mZipField.setText( "95616" );
    */

    addServerErrorString( "CHECK_FAILED", "checkFailed" );
    addServerErrorString( "UNKNOWN_COUNTRY", "unknownCountry" );
    addServerErrorStringSignal( "MORE_INFO_NEEDED", "moreInfoNeeded" );
    }


        
SendCheckGlobalPage::~SendCheckGlobalPage() {
    }



void SendCheckGlobalPage::clearFields() {
    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        mFields[i]->setText( "" );
        }
    }






void SendCheckGlobalPage::actionPerformed( GUIComponent *inTarget ) {
    if( inTarget == &mSendCheckButton ) {
        setStatus( NULL, false );
        
        setupRequestParameterSecurity();
                
        
        setParametersFromField( "name", &mNameField );
        setParametersFromField( "address1", &mAddress1Field );
        setParametersFromField( "address2", &mAddress2Field );
        setParametersFromField( "city", &mCityField );
        setParametersFromField( "province", &mProvinceField );
        setParametersFromField( "postal_code", &mPostalCodeField );

        
        // convert full name into ISO country code
        char *countryFullName = mCountryField.getText();
        
        const char *code = 
            countryMap[ findClosestCountryCode( countryFullName ) ].code;
        
        delete [] countryFullName;
        
        setParametersFromString( "country", code );
        


        setParametersFromString( "us_state", "" );
        
        double dollarAmount = mAmountPicker.getValue();
        
        char *dollarAmountString = autoSprintf( "%.2f", dollarAmount );
        
        setParametersFromString( "dollar_amount", 
                                 dollarAmountString );
        delete [] dollarAmountString;

        
        mSendCheckButton.setVisible( false );
        mCancelButton.setVisible( false );
        
        for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
            mFields[i]->setActive( false );
            mFields[i]->unfocus();
            }

        mAmountPicker.setAdjustable( false );
        
        startRequest();
        }
    else if( inTarget == &mCancelButton ) {
        setSignal( "back" );
        }
    }



void SendCheckGlobalPage::makeActive( char inFresh ) {
    
    if( ! isActionInProgress() ) {
        makeFieldsActive();
        }
    


    if( !inFresh ) {
        return;
        }
    
    setStatus( NULL, true );
    
    mResponseReady = false;
    
    
    checkIfSendCheckButtonVisible();

    // fix later with balance and check fee when page made active
    mAmountPicker.setValue( userBalance );
    mAmountPicker.setMin( checkCostGlobal + 0.01 );
    mAmountPicker.setMax( userBalance );
    }



void SendCheckGlobalPage::makeNotActive() {
    // paused? clear delete-held status
    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        mFields[i]->unfocus();
        }
    }




void SendCheckGlobalPage::makeFieldsActive() {
    mCountryField.focus();
    mCountryVerified = false;
    
    char *oldCountry = mCountryField.getText();
    verifyCountry();

    if( mCountryVerified ) {

        char *newCountry = mCountryField.getText();
        
        if( strcmp( newCountry, oldCountry ) == 0 ) {
            
            // country already typed in, jump to name field
            mNameField.focus();
            }
        else {
            mCountryField.setText( oldCountry );
            }

        delete [] newCountry;
        }
    delete [] oldCountry;


    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        mFields[i]->setActive( true );
        }
    
    mAmountPicker.setAdjustable( true );
    }



void SendCheckGlobalPage::draw( doublePair inViewCenter, 
                          double inViewSize ) {
        
    
    if( checkCostGlobal > 0 ) {
        doublePair labelPos = { 0, 264 };

        char *message = autoSprintf( translate( "feeSubtracted" ),
                                     checkCostGlobal );
        
        drawMessage( message, labelPos, false );    
        delete [] message;
        }
    }



void SendCheckGlobalPage::step() {
    ServerActionPage::step();
    
    if( ! isActionInProgress() ) {
        
        if( ! mCountryField.isFocused() && ! mCountryVerified ) {
            verifyCountry();
            }
        else if( mCountryField.isFocused() ) {
            mCountryVerified = false;
            }
        

        checkIfSendCheckButtonVisible();

        if( ! mFields[0]->isActive() ) {
            makeFieldsActive();
            }
        mCancelButton.setVisible( true );
        }
    }



double SendCheckGlobalPage::getWithdrawalAmount() {
    return mAmountPicker.getValue();
    }



void SendCheckGlobalPage::checkIfSendCheckButtonVisible() {
    char visible = true;

    char *name = mNameField.getText();
    
    if( strlen( name ) < 1 ) {
        visible = false;
        }
    delete [] name;
    
    
    char *address1 = mAddress1Field.getText();
    
    if( strlen( address1 ) < 1 ) {
        visible = false;
        }

    delete [] address1;


    char *city = mCityField.getText();
    
    if( strlen( city ) < 1 ) {
        visible = false;
        }

    delete [] city;


    char *country = mCountryField.getText();
    
    if( strlen( country ) < 1 ) {
        visible = false;
        }

    delete [] country;
    
    
    if( ! mCountryVerified ) {
        visible = false;
        }
    

    mSendCheckButton.setVisible( visible );
    }



void SendCheckGlobalPage::verifyCountry() {
    char *country = mCountryField.getText();
    
    if( strlen( country ) > 0 ) {
        
        int closestIndex = findClosestCountryCode( country );
        
        mCountryField.setText( 
            countryMap[closestIndex].countryName );
        
        mCountryVerified = true;
        }
    else {
        mCountryVerified = false;
        }
    
    delete [] country;
    }




void SendCheckGlobalPage::switchFields( int inDir ) {
    for( int i=0; i<NUM_SEND_CHECK_GLOBAL_FIELDS; i++ ) {
        
        if( mFields[i]->isFocused() ) {
            
            if( mFields[i] == &mCountryField ) {
                // leaving country field
                
                verifyCountry();
                }
            

            int next = i + inDir;
            
            if( next >= NUM_SEND_CHECK_GLOBAL_FIELDS ) {
                next = 0;
                }
            else if( next < 0 ) {
                next = NUM_SEND_CHECK_GLOBAL_FIELDS - 1;
                }
            mFields[next]->focus();
            return;
            }
        }
    }

    

void SendCheckGlobalPage::keyDown( unsigned char inASCII ) {
    if( isActionInProgress() ) {
        return;
        }

    if( inASCII == 9 ) {
        // tab
        switchFields();
        return;
        }

    if( inASCII == 10 || inASCII == 13 ) {
        // enter key
        
        if( mCountryField.isFocused() && mSendCheckButton.isVisible() ) {
            // process enter on last field
            actionPerformed( &mSendCheckButton );
            
            return;
            }
        else {
            switchFields();
            }
        }
    }



void SendCheckGlobalPage::specialKeyDown( int inKeyCode ) {
    if( isActionInProgress() ) {
        return;
        }

    if( inKeyCode == MG_KEY_DOWN ) {
        switchFields();
        return;
        }
    else if( inKeyCode == MG_KEY_UP ) {
        switchFields(-1);
        return;
        }
    }
