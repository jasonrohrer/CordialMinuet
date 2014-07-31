<?php




// must be called before using a semaphore the first time
// returns 0 on success, or non-zero on failure
function semInitLock( $inKey ) {
    return semaphoreOp( $inKey, "initLock" );
    }



// locks a semaphore so that other processes cannot lock or signal it
// must be called before calling semWait
function semLock( $inKey ) {
    return semaphoreOp( $inKey, "lock" );
    }



// unlocks a semaphore (can be called instead of semWait on a locked
// semaphore) 
function semUnlock( $inKey ) {
    return semaphoreOp( $inKey, "unlock" );
    }



// unlocks a semaphore and waits on that semaphore for a signal, giving
// up after $inTimeoutMS milliseconds, if not -1
// On timeout, returns -2
function semWait( $inKey,  $inTimeoutMS = -1 ) {
    return semaphoreOp( $inKey, "wait", $inTimeoutMS );
    }



// signals all processes that are waiting on a semaphore
function semSignal( $inKey ) {
    return semaphoreOp( $inKey, "signal" );
    }



// removes a semaphore from the system
function semRemove( $inKey ) {
    return semaphoreOp( $inKey, "remove" );
    }



// internal use only
// $inTimeoutMS can only be not -1 for wait op.
// returns -2 on timeout
function semaphoreOp( $inKey, $inOp, $inTimeoutMS = -1 ) {

    $timeoutString = "";
    if( $inTimeoutMS != -1 ) {
        $timeoutString = $inTimeoutMS;
        }
    
    $lastLine =
        exec( "./semaphoreOps  $inKey  $inOp $timeoutString",
              $output, $returnValue );

    if( strstr( $lastLine, "TIMEOUT" ) != FALSE ) {

        return -2;
        }
    
    //echo "Sem op ($inKey, $inOp ) last line $lastLine, ".
    //    "return $returnValue<br>\n";

    return $returnValue;
    }


?>