<?php

/**
* Class to catch all possible errors with matrices
* @package LinAlg
* @subpackage Exceptions
*/
class MatrixException extends Exception {

public function __construct($msg, $code=0, $previous=null) {
  parent::__construct($msg, $code, $previous);
}

}



/**
* Class to catch errors related with size of matrices
* @package LinAlg
* @subpackage Exceptions
*/
class DimensionException extends MatrixException {

public function __construct($msg="Matrices or vectors are not the same size", $code=0, $previous=null) {
  parent::__construct($msg, $code, $previous);
}

}





/**
* Class to catch errors related with construction of matrices (eg. entries are not arrays, rows are not of same size, etc.)
* @package LinAlg
* @subpackage Exceptions
*/
class BadFormationException extends MatrixException {

public function __construct($msg="Error while constructing the matrix. Ensure that rows are arrays of same size, and that entries M[i][j] are of a numerical type.", $code=0, $previous=null) {
  parent::__construct($msg, $code, $previous);
}

}
?>
