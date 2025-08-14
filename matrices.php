<?php
/**
* A package with different matrix classes for solving linear algebra problems.
* @author Droid Roar
* @version 1.0.0
* @package LinAlg
*/

include_once("exceptions.php");


/**
* Class with general matrix operations, and basic linear algebra methods
* @package LinAlg
*
*/

class Matrix {

/**
* @var array<array<float|int>> $data The content of the matrix, as a 2-dim array
*/
protected $data=array(); //Matrix's data (as array)

/**
* @var float|int $width The width (num. of columns) of the matrix.
* @var float|int $height The height (num. of rows) of the matrix.
*/
protected $width=0, $height;




/**
* Constructor of the class. It builds the matrix from an array input
*
* @param array $arr A 2-dimensional array, with all its array arguments of same size. This array contains the matrix's entries
* @throws BadFormationException If the array for constructing the matrix is invalid, this method will throw an exception
*/
public function __construct(array $arr) {
  $fl=true;
  
  $this->height = count($arr); //Matrix's height
  
  //Check if the array is not empty
  if($this->height == 0) {
      throw new BadFormationException("The matrix's height couldn't be zero");
  }
  
  //Check if the first row is an array
  if(!is_array($arr[0])) {
    throw new BadFormationException("Matrix's rows must be arrays");
  }
  
  $this->width = count($arr[0]);  //Matrix's width
  
  
  //Check if the first row is not empty
  if($this->width == 0) {
    throw new BadFormationException("Matrix's width couldn't be zero");
  }
  
  
  for($i=0; $i< $this->height; $i++) {
    //Check if the rows are arrays
    if(!is_array($arr[$i])) {
      $fl=false;
      break;
    }
  
    //Check if the rows are the same size
    if(count($arr[$i]) != $this->width) {
      $fl=false;
      break;
    } 
  } //End loop
  
  //If some row had an error, throw an exception
  if(!$fl) throw new BadFormationException();
  
  //Finally, save the matrix's array in $data variable
  $this->data = $arr;
  
}



/**
* Method to represent the matrix as a string.
*
* @return string The matrix's entries separated in rows and columns
*/
public function __toString() {
  $out="";
  foreach($this->data as $M_i) {
    foreach($M_i as $x) $out= $out . round($x, 5) . " ";
  $out = $out. "\n";
  }
  
  return $out;
}




/**
* Returns the matrix as an array object
*
* @return array<array<float|int>> The 2-dimensional array with the entries of the matrix
*/
public function get_data() { return $this->data; }



/**
* Returns the matrix's width
*
* @return int The width of the matrix
*/
public function get_w() { return $this->width; }



/**
* Returns the matrix's height
*
* @return int The height of the matrix
*/
public function get_h() { return $this->height; }




/**
* Returns the matrix's dimensions as an array [$height,$width]
*
* @return array<int> The dimensions [$height,$width] of the matrix
*/
public function get_dims() { return [$this->height, $this->width]; }



/**
* Says if the matrix is square
*
* @return boolean True if the matrix is square, False otherwise
*/
public function is_squared() { return $this->width == $this->height; }



/**
* Displays the matrix in screen.
*
* @param int $digits Number of precision digits, with -1 matrix's entries are not rounded off
*/
public function show($digits=-1) {

foreach($this->data as $M_i) {
  foreach($M_i as $x) echo  $digits==-1? "$x ": round($x, $digits)." ";
  echo "\n";
}

}




######
# Array operations
######


/**
* Converts an array to a column matrix
* 
* @param array $v The array to be converted in column matrix
* @return Matrix The column matrix asociated to $v
*/
public static function to_col(array $v) {
  $out=array();
  for($i=0; $i< count($v);$i++) $out[][0] = $v[$i];
  
  return new Matrix($out);
}






/**
* Converts an array to a row matrix
* 
* @param array $v The array to be converted in row matrix
* @return Matrix The row matrix asociated to $v
*/
public static function to_row(array $v) {
  return new Matrix( array($v) );
}






/**
* Gets the j-th column of the matrix
* 
* @param int $j The number of column to be extracted (beggining at j=0)
* @return array The j-th column of the matrix, as an array
*/
public function get_col($j) {
$out=array();
#$m= $this->data; // More speed if we don't access to the object's attributes repeatedly

for($i=0; $i< $this->height; $i++) $out[]= $this->data[$i][$j];
#unset($m);

return $out;
}






/**
* Gets the i-th row of the matrix
* 
* @param int $i The number of row to be extracted (beggining at i=0)
* @return array The i-th row of the matrix, as an array
*/
public function get_row($i) {
  return $this->data[$i];
}






/**
* Obtains nxn identity
* @param int $n The order of nxn identity
* @return Matrix The nxn identity
*/
public static function get_identity($n) {
$I=array();
for($i=0; $i<$n; $i++) {
  for($j=0; $j<$n; $j++) {
    if($j==$i) $I[$i][$j] = 1;
    else $I[$i][$j] = 0;
  }
}
return new Matrix($I);
}




/**
* Obtains nxn scalar matrix diag($s,..., $s)
*
* @param int $n The order of nxn identity
* @param float|int $s The scalar in the diagonal
* @return Matrix The nxn diagonal matrix diag($s,..., $s)
*/
public static function get_scalar($n, $s=1) {
$S=array();
for($i=0; $i<$n; $i++) {
  for($j=0; $j<$n; $j++) {
    if($j==$i) $S[$i][$j] = $s;
    else $S[$i][$j] = 0;
  }
}
return new Matrix($S);
}





/**
* Obtains nxm zero matrix
*
* @param int $n The height of matrix
* @param int $m The width of matrix
* @return Matrix The nxm zero matrix
*/
public static function get_zero($n, $m) {
$Z=array();
for($i=0; $i<$n; $i++) {
  for($j=0; $j<$m; $j++) $Z[$i][$j] = 0;
}
return new Matrix($Z);
}





/**
* Generates a random nxm matrix
*
* @param int $n The height of matrix
* @param int $m The width of matrix
* @param float $Linf The lower bound for generating random numbers in each entry
* @param float $Lsup The upper bound for generating random numbers in each entry
* @return Matrix A nxm matrix with random entries within the range [$Linf, $Lsup]
*/
public static function random_matrix($n,$m,$Linf=-100,$Lsup=100) {
$M=array();
for($i=0; $i<$n; $i++) {
  //Fills the [$i,$j] input with a random number between $Linf and $Lsup
  for($j=0; $j<$m; $j++) $M[$i][$j] = $Linf + ($Lsup-$Linf)*(rand()/getrandmax());
}
return new Matrix($M);
}






/**
* Check if matrix are equals, with a tolerance range
*
* @param Matrix $A The first Matrix to compare
* @param Matrix $B The second Matrix to compare
* @param float $lim The value of tolerance, meaning that differences |A[i][j]-B[i][j]| are less than $lim for all [i,j] entries. If $lim=0, it verifies exact coincidence between $A and $B.
* @return boolean True or false, depending on if both matrices are equal or not under this margin of tolerance
*/
public static function equals(Matrix $A,Matrix $B,$lim=0) {

if($lim==0) return $A->get_data() == $B->get_data();
else {
  $out = $A->get_dims() == $B->get_dims();
  if($out) {
    foreach($A->get_data() as $i=>$Ai) {
      foreach($Ai as $j=>$a) $out = $out && ( abs($a - $B->get_data()[$i][$j]) <$lim );
    }
  }
  return $out;
}

}



/**
* Matrix rotation about X axis
* @param float $th The angle of rotation
* @return Matrix The rotation matrix
*/
public static function rot_matrix_X($th) {

  return new Matrix([[1,0,0],
                     [0,cos($th),-sin($th)],
                     [0,sin($th),cos($th)]]);
}




/**
* Matrix rotation about Y axis
* @param float $th The angle of rotation
* @return Matrix The rotation matrix
*/
public static function rot_matrix_Y($th) {

  return new Matrix([[cos($th),0,-sin($th)],
                     [0,1,0],
                     [sin($th),0,cos($th)]]);
}





/**
* Matrix rotation about Z axis
* @param float $th The angle of rotation
* @return Matrix The rotation matrix
*/
public static function rot_matrix_Z($th) {

  return new Matrix([[cos($th),-sin($th),0],
                     [sin($th),cos($th),0],
                     [0,0,1]]);
}







######
# Vector operations
######


/**
* Dot product of vectors
*
* @param array $u The first vector to multiply
* @param array $v The second vector to multiply
* @return array The dot product $u*$v
*/
public static function dot_prod(array $u,array $v) {
$n=count($u);

if(count($v)!=$n) throw new DimensionException();

$sum=0;
for($i=0; $i<$n; $i++) $sum += $u[$i]*$v[$i];

return $sum;
}





/**
* Norm of a vector
*
* @param array $u A vector to calculate its euclidian norm
* @return float The norm |u| of the vector
*/
public static function norm($u) {
  return sqrt( Matrix::dot_prod($u,$u) );
}




/**
* Cross product of vectors
*
* @param array $u The first vector to multiply
* @param array $v The second vector to multiply
* @return array The cross product $u x $v
*/
public static function cross_prod(array $u,array $v) {

if(count($u)!=3 || count($v)!=3) throw new DimensionException("The vectors must have 3 dimensions");

$w=array();
for($i=0; $i<3; $i++) $w[] = $u[($i+1)%3]*$v[($i+2)%3] - $u[($i+2)%3]*$v[($i+1)%3];

return $w;
}





/**
*Gram-Schmidt orthogonalization of a set of "m" vectors in R^n euclidian space.
*
* @param Matrix $A A matrix of mxn, whose rows are the vectors of a basis to get orthogonal. The rows are vectors in R^n euclidian space, and the matrix is a list with "m" of those vectors. 
*/
public static function gram_schmidt(Matrix $A) {
$u=array();

for($i=0; $i< $A->get_h(); $i++) {
  $vi= $A->get_row($i); #Original rows
  $ui= $A->get_row($i); #Rows to be transformed

  for($k=0; $k<$i; $k++) $ui= Matrix::sum($ui, Matrix::scale(-Matrix::dot_prod($vi,$u[$k]), $u[$k]) );
  
  $ui= Matrix::scale(1.0/Matrix::norm($ui), $ui);
  $u[]=$ui;
}

return new Matrix($u);
}





/**
*Euler angles of a rotation matrix, in the standar intrinsec rotation ZX'Z''.
*
* @param Matrix $Rm A 3x3 special ortogonal matrix
* @return array The list (alpha, beta, gamma) of Euler angles
*/
public static function euler_angles(Matrix $Rm) {
  #Obtain spherical coordinates of Z* rotated vector (col[2] of $R)
  $R=$Rm->get_data();
  $th_z= acos($R[2][2]); #Azimutal angle, beta=$th_z
  
  $phi_z= acos( $R[0][2]/sin($th_z) );
  #If the Y component of Z* is negative, then $phi_z<0
  if($R[1][2]<0) $phi_z *= -1;
  
  #Define alpha angle as $phi_z+pi/2
  $a=$phi_z+acos(0);
  
  #Angle gamma
  $c= acos( Matrix::dot_prod([cos($a), sin($a), 0], $Rm->get_col(0)) );
  #If Z component of X* vector is negative, then gamma<0
  if($R[2][0]<0) $c *= -1;
  
  unset($R);
  return [$a, $th_z, $c];  
}






#######
# Matrix operations
#######


/**
* It gets the transpose of the matrix calling it
*
* @return Matrix The transposed matrix of $this
*/
public static function trans(Matrix $A) {

$B= array(); //New matrix

for($j=0; $j< $A->get_w(); $j++) {
  for($i=0; $i< $A->get_h(); $i++) $B[$j][$i] = $A->get_data()[$i][$j];
  }

return new Matrix($B);
}



/**
* It gets the trace of the matrix calling it
*
* @return float The trace of $this matrix
*/
public static function trace(Matrix $A) {

if(!$A->is_squared()) throw new DimensionException("The matrix must be squared!");

$sum=0;
for($j=0; $j< $A->get_w(); $j++) {
  $sum += $A->get_data()[$j][$j];
}

return $sum;
}




/**
* Sum of two  vectors (or matrices)
*
* @param array|Matrix $a First vector or matrix to add
* @param array|Matrix $b Second vector or matrix to add
* @return array|Matrix The sum $a+$b
*
*/
public static function sum(Matrix|array $a,Matrix|array $b) {

//Sum of two vectors (arrays)
if(is_array($a) && is_array($b)) {
  $c=array();
  $n =count($a);
  
  if(count($b)!=$n) throw new DimensionException(); #We can't sum vectors of different length
  
  for($i=0; $i<$n; $i++) $c[] = $a[$i]+$b[$i];
  return $c;
}


elseif(get_class($a)=="Matrix" && get_class($b)=="Matrix") {
  $c=array();
  
  if($a->get_dims() != $b->get_dims()) throw new DimensionException(); #We can't sum matrices of different dims
  
  $n=$a->get_h();
  $m=$a->get_w();
  for($i=0; $i<$n; $i++) {
    $ci=array();
    for($j=0; $j<$m; $j++) $ci[]= $a->get_data()[$i][$j] + $b->get_data()[$i][$j];
    $c[]=$ci;
  }
  return new Matrix($c);
  
} else throw new MatrixException("You can't sum a matrix and a vector.");

}





/**
* Scaling of a vector (or matrix)
*
* @param int|float|double $s Scaling factor
* @param array|Matrix $A Vector or matrix to scale
* @return array|Matrix The product $s*$A
*
*/
public static function scale($s, Matrix|array $A) {

if(is_array($A)) {
  $c=array();
  
  for($i=0; $i<count($A); $i++) $c[] = $s*$A[$i];
  return $c;
  
} elseif(get_class($A)=="Matrix") {
  $C=$A->get_data();
  
  foreach($C as $i=>$Ci) {
    foreach($Ci as $j=>$c) $C[$i][$j] *= $s;
  }
  
  return new Matrix($C);
 
}

}





/**
* Matrix product
*
* @param Matrix $A The matrix to the left of product AB
* @param Matrix $B The matrix to the right of product AB
* @return Matrix The product AB
*/
public static function prod(Matrix $A,Matrix $B) {

if($A->get_w() != $B->get_h()) throw new DimensionException("Incompatible sizes");

$C=array();
for($i=0;$i< $A->get_h(); $i++) {
  for($j=0; $j< $B->get_w(); $j++) {
    $C[$i][$j] = 0;
    for($k=0 ; $k< $A->get_w(); $k++) $C[$i][$j] += $A->get_data()[$i][$k]*$B->get_data()[$k][$j];
  }
}
return new Matrix($C);

}







/**
* Finds the inverse matrix of $M using Gauss-Jordan algorithm
*
* @param Matrix $M The matrix whose inverse will be obtained
* @param float $lim The limit error for numerical stability. If |x|<$lim, "x" counts as zero. Util for pivoting
* @return Matrix The inverse of $M
*/
public static function inv_GJ(Matrix $M, $lim=0.001) {
  $I=Matrix::get_identity($M->get_w());
  //$M must be squared!!
  $I->gauss_jordan($M,$lim,true); //$use_cols=true saves computational time
  return $I;
}







/**
* Solves the ecuations system with Gauss-Jordan algorithm
*
* @param Matrix $M The matrix of a system of the form Mx=b
* @param array $b_arr The array "b" with the results of system Mx=b
* @param float $lim The limit error for numerical stability. If |x|<$lim, "x" counts as zero. Util for pivoting
* @return Matrix The solution "x" as a column matrix
*/
public static function solve_GJ(Matrix $M,$b_arr, $lim=1e-6) {
  $b=Matrix::to_col($b_arr); //Column $b of system $A*$x = $b
  $b->gauss_jordan($M,$lim);
  return $b;
}







/**
* Gauss-Jordan method. It applies the same row operations in $this needed to convert a squared matrix $Ap into the identity matrix
*
* @param Matrix $Ap A squared non singular matrix, to be converted into the identity with row operations. The same operations apply over $this object matrix, transforming it as $Ap become the identity
*
* @param float $lim Limit error for numerical stability, if |$x|<$lim it counts like zero. Util for pivoting
*
* @param boolean $use_cols If it's true, it avoids doing the row operations in all columns using a list of the columns which entries in the actual row are not zero. Util if almost all the row is zero to save computation time (e.g., the identity), but it's insecure and the results could be innaccurate. Set it to false if you are not secure of how many zeros have the rows
*/

public function gauss_jordan(Matrix $Ap, $lim=1e-6, $use_cols=false) {

if(!$Ap->is_squared()) throw new DimensionException("Not squared matrix given!");

$A=new Matrix($Ap->get_data()); #Duplicate to avoid transforming $A with the row operations
$n=$A->get_h(); #Matrix nxn

$cols=array();

//Run over the $j columns, to obtain an upper-triangular matrix
for($j=0; $j<$n; $j++) {
  $fl=false; //Flag to check if matrix is regular (not singular)
  
  for($i=$j; $i<$n; $i++) { //Looking for a pivot
    
    if( abs($A->get_data()[$i][$j]) > $lim ) { //If |$x|>$lim it's not zero
      
      if($i != $j) { $A->permute_rows($j,$i, $j);    $this->permute_rows($j,$i); } //Pivot
      
      if($use_cols) { //Save columns used in the permutation
                        $tmp = ( isset($cols[$j])? $cols[$j]:$j );
                        $cols[$j] = ( isset($cols[$i])? $cols[$i]:$i );
                        $cols[$i] = $tmp; }
      
      $fl=true; break;
    } //End if(|$x|>$lim)
  
  } //End first for($i)
  
  if(!$fl) { throw new MatrixException("Singular matrix. Try to put a smaller limit (currently \$lim=$lim). If error persists, the matrix is not invertible.");
            break; }
  
  
  $this->scale_row($j, 1/$A->get_data()[$j][$j], 0, $cols,$j);
  $A->scale_row($j, 1/$A->get_data()[$j][$j], $j); //Normalize pivot
  
  
  for($i=$j+1; $i<$n; $i++) { // Elimination, for the $i rows after than $j
    if($A->get_data()[$i][$j] !=0) {
          $this->sum_rows($i,$j, -$A->get_data()[$i][$j], 0, $cols,$j);
          $A->sum_rows($i,$j, -$A->get_data()[$i][$j], $j);
    }
  } //End second for($i)
  
  
} //End first for($j)
  
  
  #Elimination of upper terms
  for($j=$n-1; $j>=0; $j--) {
    for($i=$j-1; $i>=0; $i--) {
      if($A->get_data()[$i][$j]!=0)  $this->sum_rows($i,$j, -$A->get_data()[$i][$j]);
    } //End for($i)
     
  } //End second for($j)


unset($A); #Delete $A duplicate matrix
}








/**
* Determinant by Gauss-Jordan
*
* @param Matrix $Ap The matrix whose determinant will be calculated
* @param float $lim Limit to adjust error by rounding, if |$x|<$lim it counts like zero. Util for pivoting
* @return float The determinant det($Ap) of matrix $Ap
*/
public static function det(Matrix $Ap,$lim=1e-6) {

if(!$Ap->is_squared()) throw new DimensionException("Not square matrix given!");

$A=new Matrix($Ap->get_data()); #Clone the argument to avoid editing it with the row operations
$det = 1;
$n=$A->get_h(); #Matrix nxn


//Run over $j columns, to obtain an upper-triangular matrix
for($j=0; $j<$n; $j++) {
  $fl=false; //Flag to check if matrix is regular (not singular)
  
  for($i=$j; $i<$n; $i++) { //Looking for a pivot
    if( abs($A->get_data()[$i][$j]) >$lim ) //If |$x|>$lim it's not zero
       {
         if($i != $j) { $A->permute_rows($j,$i, $j);   $det*=-1; } //Pivot
         
         $fl=true; break;
        } //End if(|$x|>$lim)
  } //End for($i)
  
  if(!$fl) { $det=0;   break; }  #Singular matrix
  
  
  $det *= $A->get_data()[$j][$j];
  $A->scale_row($j, 1/$A->get_data()[$j][$j], $j); //Normalize pivot
  
  
  for($i=$j+1; $i<$n; $i++) { // Elimination, for $i rows after than $j
    if($A->get_data()[$i][$j] !=0) {
           $A->sum_rows($i,$j, -$A->get_data()[$i][$j], $j);
    }
  } //End for($i)

} //End for($j)


unset($A); #Delete $A duplicate matrix
return $det;
}






#######
#Row operations
#######


#Permutes $i1 and $i2 rows
/**
 * Permutes (swaps) two rows in the matrix. The matrix that calls this method is transformed by this row operation.
 * 
 * @param int $i1 The first row to swap (counted from 0 to $height-1)
 * @param int $i2 The second row to swap (counted from 0 to $height-1)
 * @param int $j0 Index of the first column to take into account while swapping the rows (i.e., just the columns $j>=$j0 will be swapped). Util if the columns before $j0 are already equal.
 * @param array $cols A list or array with the columns to be taken into account while swapping, i.e., just the columns in this list will be swapped. Util if you know in which columns the rows are equal.
 * @param int $l Length of the $cols list to be taken into account while swapping, i.e., just the first $l elements of $cols will be swapped.
 */
public function permute_rows($i1,$i2, $j0=0, $cols=array(), $l=-1) { 
if(count($cols)!=0) { //Just operate with some columns (util for almost-zero rows)
  $ll= ($l==-1)? count($cols)-1 : $l;
  for($j=0; $j<=$ll; $j++) { $temp = $this->data[$i1][$cols[$j]]; $this->data[$i1][$cols[$j]] = $this->data[$i2][$cols[$j]]; $this-> data[$i2][$cols[$j]] = $temp; }
  
} else { //Operate starting in $j0 column
  for($j=$j0; $j< $this->width; $j++) { $temp = $this->data[$i1][$j]; $this->data[$i1][$j] = $this->data[$i2][$j]; $this->data[$i2][$j] = $temp; }
}

}




#Multiplies a row by a scalar
/**
 * Scales a row in the matrix. The matrix that calls this method is transformed by this row operation.
 * 
 * @param int $i The row to be scaled (counted from 0 to $height-1)
 * @param int $c The scalar
 * @param int $j0 Index of the first column to take into account while scaling the row (i.e., just the columns $j>=$j0 will be scaled). Util if the columns before $j0 are zero.
 * @param array $cols A list or array with the columns to be taken into account while scaling, i.e., just the columns in this list will be scaled. Util if you know in which columns the row is not zero.
 * @param int $l Length of the $cols list to be taken into account while scaling, i.e., just the first $l elements of $cols will be scaled.
 */
public function scale_row($i,$c, $j0=0, $cols=array(),$l=-1) { 
if(count($cols)!=0) { //Just operate with some columns (util for almost-zero rows)
  $ll= ($l==-1)? count($cols)-1 : $l;
  for($j=0; $j<=$ll; $j++) $this->data[$i][$cols[$j]] *= $c;

} else { //Operate starting in $j0 column
  for($j=$j0; $j< $this->width; $j++) $this->data[$i][$j] *= $c;
}

}





#Adds $c times $i2 row to the $i1 row
/**
 * Adds a scaled row in the matrix onto another row. The matrix that calls this method is transformed by this row operation.
 * 
 * @param int $i1 The row to be updated after adding the $i2 row (counted from 0 to $height-1)
 * @param int $i2 The row to be added onto $i1 (counted from 0 to $height-1)
 * @param int $j0 Index of the first column to take into account while adding the rows (i.e., just the columns $j>=$j0 will be added). Util if the columns before $j0 in the row $i2 are zero.
 * @param array $cols A list or array with the columns to be taken into account while adding, i.e., just the columns in this list will be added. Util if you know in which columns the row $i2 is not zero.
 * @param int $l Length of the $cols list to be taken into account while adding the rows, i.e., just the first $l elements of $cols will be added.
 */
public function sum_rows($i1,$i2,$c, $j0=0, $cols=array(),$l=-1) { 
if(count($cols)!=0) { //Just operate with some columns (util for almost-zero rows)
  $ll= ($l==-1)? count($cols)-1 : $l;
  for($j=0; $j<=$ll; $j++) $this->data[$i1][$cols[$j]] += $c*$this->data[$i2][$cols[$j]];
  
} else { //Operate starting in $j0 column
  for($j=$j0; $j< $this->width; $j++) $this->data[$i1][$j] += $c*$this->data[$i2][$j] ;
}

}






############
### Numeric operations
############

/**
* Obtains the exponential matrix
*
* @param Matrix $A The matrix argument
* @param int $N The number of iterations in the Taylor series to approximate the exponential
* @return Matrix The exponencial e^$A
*/
public static function exp(Matrix $A, $N=10) {
  if(!$A->is_squared()) throw new DimensionException("Not square matrix given!");
  
  $n=$A->get_h();
  $out= Matrix::get_zero($n,$n);
  $prod_m=Matrix::get_identity($n);
  $fact = 1;
  
  for($k=0; $k<$N; $k++) {
    $out = Matrix::sum($out, $prod_m);
    
    if($k==$N-1) break; #Avoid doing the following matrix product in the last step
    
    $prod_m = Matrix::prod($prod_m, $A); # Obtain A^(k+1)
    $prod_m = Matrix::scale(1.0/($k+1), $prod_m); # Obtain A^(k+1)/(k+1)!
  }
  
  return $out;
}






/**
* LU decomposition.
*
* @param Matrix $Ap Squared regular matrix to be decomposed into L*U product
* @param float $lim Limit error for numerical stability, if |$x|<$lim it counts like zero. Util for pivoting
*
* @return array<Matrix> A list with the matrix factors L,U, or L1,P1,...,U in case there was pivoting
*/

public static function LU_decomposition(Matrix $Ap, $lim=1e-6) { #, $use_cols=false

if(!$Ap->is_squared()) throw new DimensionException("Not squared matrix given!");

$U=new Matrix($Ap->get_data()); #Duplicate to avoid transforming $A with the row operations
$n=$U->get_h(); #Matrix nxn
$L=Matrix::get_identity($n);

$out=array();
$cols=array();

//Run over the $j columns, to obtain an upper-triangular matrix
for($j=0; $j<$n; $j++) {
  $fl=false; //Flag to check if matrix is regular (not singular)
  
  for($i=$j; $i<$n; $i++) { //Looking for a pivot (for now, the pivoting is unstable)
    
    if( abs($U->get_data()[$i][$j]) > $lim ) { //If |$x|>$lim it's not zero
      
      if($i != $j) { //Pivot
        if($j!=0) { #If $j!=0, save the previous lower matrix $L and reset it to the identity to save the permutation
          $out[]= $L;
          $cols=[];
          $L=Matrix::get_identity($n);
        }
        $U->permute_rows($j,$i, $j);
        $L->permute_rows($j,$i, 0, [$j,$i]);   $out[]=$L; #Save the permutation as part of factorization
        $L->permute_rows($j,$i, 0, [$j,$i]); #Take back to the identity
      } #End pivot
      
      /*if($use_cols) { //Save columns used in the permutation
                        $tmp = ( isset($cols[$j])? $cols[$j]:$j );
                        $cols[$j] = ( isset($cols[$i])? $cols[$i]:$i );
                        $cols[$i] = $tmp; }*/
      
      $fl=true; break;
    } //End if(|$x|>$lim)
  
  } //End first for($i)
  
  if(!$fl) { throw new MatrixException("Singular matrix. Try to put a smaller limit (currently \$lim=$lim). If error persists, the matrix is not invertible.");
            break; }
  
  
  $cols[] = $j;
  $uj= $U->get_data()[$j][$j];
  for($i=$j+1; $i<$n; $i++) { // Elimination, for the $i rows after than $j
    if($U->get_data()[$i][$j] !=0) {
          $L->sum_rows($i,$j, $U->get_data()[$i][$j]/$uj, 0, [$j]);
          $U->sum_rows($i,$j, -$U->get_data()[$i][$j]/$uj, $j);
          
    }
  } //End second for($i)
  
  
} //End for($j)

$out[] = $L;
$out[] = $U;


return $out;
}






} //End Matrix class


?>  
