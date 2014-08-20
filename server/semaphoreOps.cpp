/*
 * Modification History
 *
 * 2007-February-22   Jason Rohrer
 * Created.
 */

// I found this document very helpful:
//   http://users.actcom.co.il/~choo/lupg/tutorials/
//                             multi-process/multi-process.html

#include <sys/sem.h>
#include <sys/stat.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>



// copied from the semctl man page:

#if defined(__GNU_LIBRARY__) && !defined(_SEM_SEMUN_UNDEFINED)
/* union semun is defined by including <sys/sem.h> */
#else
/* according to X/OPEN we have to define it ourselves */
union semun {
        int val;                    /* value for SETVAL */
        struct semid_ds *buf;       /* buffer for IPC_STAT, IPC_SET */
        unsigned short int *array;  /* array for GETALL, SETALL */
        struct seminfo *__buf;      /* buffer for IPC_INFO */
    };
#endif



// print usage message
void usage();



int main( int inNumArgs, char **inArgs ) {

    // first arg is key
    // second is semaphore operation
    
    if( inNumArgs < 3 ) {

        usage();
                
        return -1;
        }

    // default
    int semKey = 11141977;
    
    sscanf( inArgs[1], "%d", &semKey );

    int timeoutMS = -1;
    
    if( inNumArgs > 3 ) {
        sscanf( inArgs[3], "%d", &timeoutMS );
        }
    

    // this variable will contain the semaphore set. 
    int sem_set_id;

    // semaphore value, for semctl().                
    union semun sem_val;

    // structure for semaphore operations.           
    struct sembuf sem_op;


    // first we create a semaphore set with two semaphores in it
    // the first one is used as the lock
    // the second one is used as the signal

    if( strcmp( inArgs[2], "initLock" ) == 0 ) {
        // use exclusive mode if op is initLock
        sem_set_id = semget( semKey, 2, S_IRWXU | IPC_CREAT | IPC_EXCL );
        }
    else {
        // non-exlusive mode, and don't create
        sem_set_id = semget( semKey, 2, S_IRWXU );
        }
    if( sem_set_id == -1 ) {
        perror( "semget" );
        exit( -1 );
        }


    /*
      initLock  must be called once before the first lock operation.
                It resets the lock into an unlocked state.
                Call will fail if the semaphore already exists.
         
      lock      MUST be called by a process before it calls wait.

      unlock    Used to unlock a lock without waiting.
                If calling wait, there is no need to call unlock,
                because wait unlocks the lock.
      
      wait      causes a process to wait for the NEXT signal.
                Signals that were sent BEFORE the wait call are ignored.
                You must call lock before calling wait.

      signal    wakes up any processes that are waiting.

      remove    removes the semaphore set from the system


      The lock operation exists so that callers can ensure that a
      signal does not happen right before their call to wait.  If they
      decide to call wait, and a signal is sent between their decision
      and their actual call to wait, the signal will be lost (because
      wait waits for the next signal).

      An example usage might be:

        lock

        decide to call wait

        wait

      Another example:

        lock

        decide not to call wait

        unlock

    */

    
    if( strcmp( inArgs[2], "initLock" ) == 0 ) {
        // set the counter to 1 so that only one process calling "lock"
        // will get through
        sem_val.val = 1;
        semctl( sem_set_id, 0, SETVAL, sem_val );
        }
    else if( strcmp( inArgs[2], "lock" ) == 0 ) {
        sem_op.sem_num = 0;
        sem_op.sem_op = -1;  // decrement counter, or block if counter 0
        sem_op.sem_flg = 0;
        semop( sem_set_id, &sem_op, 1 );
        }
    else if( strcmp( inArgs[2], "unlock" ) == 0 ) {
        sem_op.sem_num = 0;
        sem_op.sem_op = +1;  // increment the counter
        sem_op.sem_flg = 0;
        semop( sem_set_id, &sem_op, 1 );
        }    
    else if( strcmp( inArgs[2], "wait" ) == 0 ) {
        // set the counter to 1 so that we can wait for the semaphore to become
        // zero
        sem_val.val = 1;
        semctl( sem_set_id, 1, SETVAL, sem_val );

        // unlock to allow signals through
        sem_op.sem_num = 0;
        sem_op.sem_op = +1;  // increment counter, let another process through
        sem_op.sem_flg = 0;
        semop( sem_set_id, &sem_op, 1 );
        

        // we block on the semaphore, unless it's value is 0
        // we wait for the value to be set to 0
        sem_op.sem_num = 1;
        sem_op.sem_op = 0;  // wait for sem to become zero
        sem_op.sem_flg = 0;
        
        if( timeoutMS == -1 ) {
            semop( sem_set_id, &sem_op, 1 );
            }
        else {
            unsigned int nsecPerMillisecond = 1000000;
            
            int timeoutSec = timeoutMS / 1000;
            int extraMS = timeoutMS % 1000;

            int extraNS = extraMS * nsecPerMillisecond;
            
            struct timespec timeoutStruct;
            timeoutStruct.tv_sec = timeoutSec;
            timeoutStruct.tv_nsec = extraNS;

            int result = semtimedop( sem_set_id, &sem_op, 1, &timeoutStruct );

            if( result == -1 && errno == EAGAIN ) {
                printf( "TIMEOUT" );
                exit( -1 );
                }
            }
        }
    else if( strcmp( inArgs[2], "signal" ) == 0 ) {
        // if inArgs[2] is "signal", or default

        // lock around our signal
        sem_op.sem_num = 0;
        sem_op.sem_op = -1;  // decrement counter, or block if counter 0
        sem_op.sem_flg = 0;
        semop( sem_set_id, &sem_op, 1 );


        int value = semctl( sem_set_id, 1, GETVAL, sem_val );

        if( value != 0 ) {
            // some processes have set the semaphore above 0 and are waiting
            // for it to be signaled back to 0
            
            // signal the semaphore, decreasing it's value to zero,
            // and letting waiting processes through
            sem_op.sem_num = 1;
            sem_op.sem_op = -1;
            sem_op.sem_flg = 0;
            semop(sem_set_id, &sem_op, 1);
            }
        // else don't signal, because we would block

        // unlock after signal
        sem_op.sem_num = 0;
        sem_op.sem_op = +1;  // increment counter, let another process through
        sem_op.sem_flg = 0;
        semop( sem_set_id, &sem_op, 1 );
        }
    else if( strcmp( inArgs[2], "remove" ) == 0 ) {
        semctl( sem_set_id, 0, IPC_RMID, sem_val );
        }
    else {
        usage();
        }
        
    return 0;
    }
    


void usage() {
    printf( "Usage:  semaphoreOps key op [timeout_ms]\n\n" );
    printf( "op can be these values:     \n"
            "initLock lock  unlock  wait   signal  remove\n\n" );
    printf( "The optional timeout_ms is a timeout in milliseconds\n"
            "and it is only used for the wait op.\n\n" );
    printf( "Example:  semaphoreOps 19282 initLock\n" );
    printf( "Example:  semaphoreOps 19282 lock\n" );
    printf( "Example:  semaphoreOps 19282 wait\n" );
    printf( "Example:  semaphoreOps 19282 remove\n" );
    printf( "Example:  semaphoreOps 19282 wait 5000\n\n" );

    printf( "On success, call exits without printing anything.\n"
            "On timeout of a wait, TIMEOUT is printed before exiting\n\n" );
    }

