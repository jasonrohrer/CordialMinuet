

MG_PATH = ../..

RANDOM = ${MG_PATH}/minorGems/crypto/cryptoRandom
RANDOM_CPP = ${RANDOM}.cpp
RANDOM_H = ${RANDOM}.h

CURVE = ${MG_PATH}/minorGems/crypto/keyExchange/curve25519
CURVE_CPP = ${CURVE}.cpp
CURVE_H = ${CURVE}.h

ENCODING = ${MG_PATH}/minorGems/formats/encodingUtils
ENCODING_CPP = ${ENCODING}.cpp
ENCODING_H = ${ENCODING}.h


DEPENDS = ${RANDOM_H} ${RANDOM_CPP} ${CURVE_H} ${CURVE_CPP} \
${ENCODING_H} ${ENCODING_CPP}


COMPILE_OPTIONS = -O3


all: curve25519GenKeys curve25519GenKeyPair curve25519GenSharedKey semaphoreOps getMagicSquare6

curve25519GenKeys: curve25519GenKeys.cpp ${DEPENDS}
	g++ -I${MG_PATH} ${COMPILE_OPTIONS} -o curve25519GenKeys curve25519GenKeys.cpp ${RANDOM_CPP} ${CURVE_CPP} ${ENCODING_CPP}

curve25519GenKeyPair: curve25519GenKeyPair.cpp ${DEPENDS}
	g++ -I${MG_PATH} ${COMPILE_OPTIONS} -o curve25519GenKeyPair curve25519GenKeyPair.cpp ${RANDOM_CPP} ${CURVE_CPP} ${ENCODING_CPP}

curve25519GenSharedKey: curve25519GenSharedKey.cpp ${DEPENDS}
	g++ -I${MG_PATH} ${COMPILE_OPTIONS} -o curve25519GenSharedKey curve25519GenSharedKey.cpp ${RANDOM_CPP} ${CURVE_CPP} ${ENCODING_CPP}


semaphoreOps:  semaphoreOps.cpp
	g++ ${COMPILE_OPTIONS} -o semaphoreOps semaphoreOps.cpp



getMagicSquare6: getMagicSquare6.cpp magicSquare6.cpp magicSquare6.h
	g++ -I${MG_PATH} ${COMPILE_OPTIONS} -o getMagicSquare6 getMagicSquare6.cpp magicSquare6.cpp