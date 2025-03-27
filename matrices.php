<?php
/**
* Class to catch all possible errors with matrices
*/
class MatrixException extends Exception {

public function __construct($msg, $code=0, $previous=null) {
  parent::__construct($msg, $code, $previous);
}

}



/**
* Class to catch errors related with size of matrices
*/
class DimensionException extends MatrixException {

public function __construct($msg="Matrices or vectors are not the same size", $code=0, $previous=null) {
  parent::__construct($msg, $code, $previous);
}

}





/**
* Class to catch errors related with construction of matrices (eg. entries are not arrays, rows are not of same size, etc.)
*/
class BadFormationException extends MatrixException {

public function __construct($msg="Error while constructing the matrix. Ensure that rows are arrays of same size, and that entries M[i][j] are of a numerical type.", $code=0, $previous=null) {
  parent::__construct($msg, $code, $previous);
}

}



/**
* Class with matrix operations, and linear algebra methods
*
*/

class Matrix {

private $content=array(); //Matrix's content (as array)
public $width=0, $height;




/**
* Constructor of the class. It builds the matrix from an array input
*
* @param array $arr A 2-dimensional array, with all its array arguments of same size. This array contains the matrix's entries
*/
public function __construct($arr) {
$fl=false;

if(is_array($arr)) {
  $this->height = sizeof($arr); //Matrix's height
  
  if($this->height > 0) {
    
    if(is_array($arr[0])) $this->width = sizeof($arr[0]);  //Matrix's width
    
  } else echo "The height is zero\n"; //Matrix with 0 height

  
 if($this->width > 0) {
  for($i=0; $i< $this->height; $i++) {
    if(is_array($arr[$i])) {
      
      if(sizeof($arr[$i]) == $this->width) $fl=true;
      else { $fl=false; echo "Rows are not of same size\n"; break; }
    
    } else { $fl=false; echo "You're not giving a matrix (matrix's rows aren't arrays)\n"; break; } //No is_array() in rows

  } //End loop

 } else echo "Matrix's width is zero\n"; //Matrix with zero width

} else echo "You're not giving a matrix (argument is not an array)\n"; //No is_array() the matrix itself


//Finally, save the matrix's array in $content variable
if($fl) $this->content = $arr;
else echo "Fatal error: it was not possible to create the matrix\n";

}



/**
* Return the matrix as an array object
*
* @return array The 2-dimensional array with the entries of the matrix
*/
public function get_array() { return $this->content; }




/**
* Displays the matrix in screen.
*
* @param int $digits Number of precision digits, with -1 matrix's entries are not rounded off
*/
public function show($digits=-1) {

foreach($this->content as $M_i) {
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
public static function to_col($v) {
if(is_array($v)) {
  $out=array();
  for($i=0; $i< sizeof($v);$i++) $out[][0] = $v[$i];

  return new Matrix($out);
} else echo "Argument is not an array";
}






/**
* Converts an array to a row matrix
* 
* @param array $v The array to be converted in row matrix
* @return Matrix The row matrix asociated to $v
*/
public static function to_row($v) {
if(is_array($v)) {
  return new Matrix( array($v) );
} else echo "Argument is not an array";
}






/**
* Gets the j-th column of the matrix
* 
* @param int $j The number of column to be extracted (beggining at j=0)
* @return array The j-th column of the matrix, as an array
*/
public function get_col($j) {
$out=array();
$m= $this->content;

for($i=0; $i< $this->height; $i++) $out[]= $m[$i][$j];
unset($m);

return $out;
}






/**
* Gets the i-th row of the matrix
* 
* @param int $i The number of row to be extracted (beggining at i=0)
* @return array The i-th row of the matrix, as an array
*/
public function get_row($i) {
  return $this->content[$i];
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
  //Fills the ($i,$j) input with a random number between $Linf and $Lsup
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

if($lim==0) return $A->content == $B->content;
else {
 $out = $A->width == $B->width && $A->height == $B->height;
 if($out) {
  for($i=0; $i< $A->height; $i++) {
    for($j=0; $j< $A->width; $j++) $out =$out&& ($A->content[$i][$j] - $B->content[$i][$j]) <$lim && ($A->content[$i][$j] - $B->content[$i][$j]) >-$lim;
  }
 }
 return $out; }

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
public static function dot_prod($u,$v) {

if(is_array($u) && is_array($v)) {
$sum =0;
$n =sizeof($u);
 if(sizeof($v)==$n) {
  
  for($i=0; $i<$n; $i++) $sum += $u[$i]*$v[$i];
  
  return $sum;  
 } else echo "Vectors are not of same size";
} else echo "Arguments are not array";

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
*Gram-Schmidt orthogonalization of a set of "m" vectors in R^n euclidian space.
*
* @param Matrix $A A matrix of mxn, whose rows are the vectors of a basis to get orthogonal. The rows are vectors in R^n euclidian space, and the matrix is a list with "m" of those vectors. 
*/
public static function gram_schmidt(Matrix $A) {
$u=array();

for($i=0; $i< $A->height; $i++) {
  $vi= $A->content[$i];
  $ui= $A->content[$i]; #Used for the sum
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
  $R=$Rm->get_array();
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
public function trans() {
#$A_t= $this->content;
$B= array(); //New matrix

for($j=0; $j< $this->width; $j++) {
  $Aj=array();
  for($i=0; $i< $this->height; $i++) $B[$j][$i] = $this->content[$i][$j];
  }

return new Matrix($B);
}



/**
* Sum of two  vectors (or matrices)
*
* @param array|Matrix $a First vector or matrix to add
* @param array|Matrix $b Second vector or matrix to add
* @return array|Matrix The sum $a+$b
*
*/
public static function sum($a,$b) {

if(is_array($a) && is_array($b)) {
$c=array();
$n =sizeof($a);

 if(sizeof($b)==$n) {
  
  for($i=0; $i<$n; $i++) $c[] = $a[$i]+$b[$i];
  return $c;
  
 } else echo "Vectors are not of same size";


} elseif(get_class($a)=="Matrix" && get_class($b)=="Matrix") {
 $c=array();
 $n=$a->height;
 $m=$a->width;
 
 if($b->height==$n && $b->width==$m) {
   for($i=0; $i<$n; $i++) {
     $ci=array();
     for($j=0; $j<$m; $j++) $ci[]= $a->content[$i][$j] + $b->content[$i][$j];
     $c[]=$ci;
   }
 return new Matrix($c);
 
 } else echo "Matrices are not the same dimensions";
} else echo "Arguments are not array nor matrix";

}





/**
* Scaling of a vector (or matrix)
*
* @param int|float|double $s Scaling factor
* @param array|Matrix $A Vector or matrix to scale
* @return array|Matrix The product $s*$A
*
*/
public static function scale($s,$A) {

if(is_array($A)) {
$c=array();
  
  for($i=0; $i<sizeof($A); $i++) $c[] = $s*$A[$i];
  return $c;


} elseif(get_class($A)=="Matrix") {
 $c=array();
 
   for($i=0; $i< $A->height; $i++) {
     $ci=array();
     for($j=0; $j< $A->width; $j++) $ci[]= $s*$A->content[$i][$j];
     $c[]=$ci;
   }
 return new Matrix($c);
 
} else echo "Arguments are not array nor matrix";

}





/**
* Matrix product
*
* @param Matrix $A The matrix to the left of product AB
* @param Matrix $B The matrix to the right of product AB
* @return Matrix The product AB
*/
public static function prod(Matrix $A,Matrix $B) {
if($A->width == $B->height) {
$C=array();
for($i=0;$i< $A->height; $i++) {
  for($j=0; $j< $B->width; $j++) {
    $C[$i][$j] = 0;
    for($k=0 ; $k< $A->width; $k++) $C[$i][$j] += $A->content[$i][$k]*$B->content[$k][$j];
  }
}
return new Matrix($C);
} else echo "Error: incorrect size of the matrices";

}







/**
* Finds the inverse matrix of $M using Gauss-Jordan algorithm
*
* @param Matrix $M The matrix whose inverse will be obtained
* @param float $lim The limit error for numerical stability. If |x|<$lim, "x" counts as zero. Util for pivoting
* @return Matrix The inverse of $M
*/
public static function inv_GJ(Matrix $M, $lim=0.001) {
$I=Matrix::get_identity($M->width);
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
public static function solve_GJ(Matrix $M,$b_arr, $lim=0.001) {
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
* @param boolean $use_cols If it's true, it avoids from doing row operations in all columns if almost all the row is zero to save computation time (e.g., the identity). Set it to false if you are not secure of how many zeros have the rows
*/

public function gauss_jordan(Matrix $Ap,$lim=0.001,$use_cols=false) {

$A=new Matrix($Ap->content); #Duplicate to avoid transforming $A with the row operations

$n=$A->height; #Matrix nxn
 
if($A->width == $n) {
  $cols=array();
  
  //Run over $j columns, to obtain an upper-triangular matrix
  for($j=0; $j<$n; $j++) {
    $fl=false; //Flag to check if matrix is regular (not singular)
    
    for($i=$j; $i<$n; $i++) { //Looking for a pivot
      
      if($A->content[$i][$j] >$lim || $A->content[$i][$j] < -$lim) //If |$x|>$lim it's not zero
         {
           if($i != $j) { $A->perm($j,$i, $j);    $this->perm($j,$i); } //Pivot
           
           if($use_cols) { //Save permutation
                             $tmp = ( isset($cols[$j])? $cols[$j]:$j );
                             $cols[$j] = ( isset($cols[$i])? $cols[$i]:$i );
                             $cols[$i] = $tmp; }
           
           $fl=true; break;
          } //End if(|$x|>$lim)
    
    } //End for($i)
    
   if(!$fl) { echo "Singular matrix. Try to put a bigger limit (currently \$lim=$lim). If error persists, the matrix is not invertible\n";
              break; }
    
    
    $this->row_times_scalar($j,1/$A->content[$j][$j], 0,$use_cols,$cols,$j);
    $A->row_times_scalar($j,1/$A->content[$j][$j], $j); //Normalize pivot
    
    
    for($i=$j+1; $i<$n; $i++) { // Elimination, for $i rows after than $j
      if($A->content[$i][$j] !=0) {
             $this->sum_rows($i,$j,-$A->content[$i][$j], 0,$use_cols,$cols,$j);
             $A->sum_rows($i,$j,-$A->content[$i][$j], $j);
      }
    } //End for($i)
  
  
  } //End first for($j)
  
  
  #Elimination of upper terms
  for($j=$n-1; $j>=0; $j--) {
    for($i=$j-1; $i>=0; $i--) {
      if($A->content[$i][$j]!=0)  $this->sum_rows($i,$j,-$A->content[$i][$j]);
    } //End for($i)
     
  } //End second for($j)
 
} else echo "Not squared matrix in argument\n";


unset($A); #Delete $A duplicate matrix
}








/**
* Determinant by Gauss-Jordan
*
* @param Matrix $Ap The matrix whose determinant will be calculated
* @param float $lim Limit to adjust error by rounding, if |$x|<$lim it counts like zero. Util for pivoting
* @return float The determinant det($Ap) of matrix $Ap
*/
public static function det(Matrix $Ap,$lim=0.001) { //

$A=new Matrix($Ap->content);
$det = 1;

$n=$A->height; #Matrix nxn
 
if($A->width == $n) {
  $cols=array();
  
  //Run over $j columns, to obtain an upper-triangular matrix
  for($j=0; $j<$n; $j++) {
    $fl=false; //Flag to check if matrix is regular (not singular)
    
    for($i=$j; $i<$n; $i++) { //Looking for a pivot
      if($A->content[$i][$j] >$lim || $A->content[$i][$j] < -$lim) //If |$x|>$lim it's not zero
         {
           if($i != $j) { $A->perm($j,$i, $j);   $det*=-1; } //Pivot
           
           $fl=true; break;
          } //End if(|$x|>$lim)
    
    } //End for($i)
    
   if(!$fl) { $det=0;   break; }  #Singular matrix
    
    
    $det *= $A->content[$j][$j];
    $A->row_times_scalar($j,1/$A->content[$j][$j], $j); //Normalize pivot
    
    
    for($i=$j+1; $i<$n; $i++) { // Elimination, for $i rows after than $j
      if($A->content[$i][$j] !=0) {
             $A->sum_rows($i,$j,-$A->content[$i][$j], $j);
      }
    } //End for($i)
  
  
  } //End for($j)
  
  
} else echo "Not squared matrix in argument\n";


unset($A); #Delete $A duplicate matrix
return $det;
}






#######
#Row operations
#######


#Permutes $i1 and $i2 rows
public function perm($i1,$i2, $j0=0,$perm=false, $cols=array(), $l=0) { 
if($perm) { //Just operate with some columns (util for almost-zero rows)
  for($j=0; $j<=$l; $j++) { $temp = $this->content[$i1][$cols[$j]]; $this->content[$i1][$cols[$j]] = $this->content[$i2][$cols[$j]]; $this-> content[$i2][$cols[$j]] = $temp; }

} else { //Operate starting in $j0 column
  for($j=$j0; $j< $this->width; $j++) { $temp = $this->content[$i1][$j]; $this->content[$i1][$j] = $this->content[$i2][$j]; $this->content[$i2][$j] = $temp; }
}

}








#Multiplies a row by a scalar
public function row_times_scalar($i,$c, $j0=0,$perm=false,$cols=array(),$l=0) { 
if($perm) { //Just operate with some columns (util for almost-zero rows)
  for($j=0; $j<=$l; $j++) $this->content[$i][$cols[$j]] *= $c;

} else { //Operate starting in $j0 column
  for($j=$j0; $j< $this->width; $j++) $this->content[$i][$j] *= $c;

}

}







#Adds $c times $i2 row to the $i1 row
public function sum_rows($i1,$i2,$c, $j0=0,$perm=false,$cols=array(),$l=0) { 
if($perm) { //Just operate with some columns (util for almost-zero rows)
  for($j=0; $j<=$l; $j++) $this->content[$i1][$cols[$j]] += $c*$this->content[$i2][$cols[$j]];

} else { //Operate starting in $j0 column
  for($j=$j0; $j< $this->width; $j++) $this->content[$i1][$j] += $c*$this->content[$i2][$j] ;

}

}




} //End class
?>  
