<?php

$wordA = entry_requestFilter(
        "wordA", "/[a-z]+/i", "" );

$wordB = entry_requestFilter(
        "wordB", "/[a-z]+/i", "" );

$wordA = strtolower( $wordA );
$wordB = strtolower( $wordB );

if( $wordA == 'origin' && $wordB == 'ensemble' ) {
    $wordA = 'increment';
    $wordB = 'ensemble';
    }

header( "Location: ./$wordA$wordB" );



/**
 * Filters a $_REQUEST variable using a regex match.
 *
 * Returns "" (or specified default value) if there is no match.
 */
function entry_requestFilter( $inRequestVariable, $inRegex, $inDefault = "" ) {
    if( ! isset( $_REQUEST[ $inRequestVariable ] ) ) {
        return $inDefault;
        }
    
    $numMatches = preg_match( $inRegex,
                              $_REQUEST[ $inRequestVariable ], $matches );

    if( $numMatches != 1 ) {
        return $inDefault;
        }
        
    return $matches[0];
    }

?>