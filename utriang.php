<?php
include("matrices.php");
include_once("exceptions.php");

/**
 * Class for upper-triangular matrices
 * @package LinAlg
 */
class UTriang extends Matrix {

/**
* Constructor of the class. It builds the matrix from an array input
*
* @param array $arr A 2-dimensional array of the form [[a_{11},...,a_{1n}], [a_{22},...,a_{2n}], ..., [a_{nn}]]. This array contains the matrix's entries
* @throws BadFormationException If the array for constructing the matrix is invalid, this method will throw an exception
*/
public function __construct(array $arr) {
  $fl=true;
  
  $this->height = count($arr); //Matrix's height
  $this->width = $this->height; //Matrix's width (square matrix)
  
  //Check if the array is not empty
  if($this->height == 0) {
      throw new BadFormationException("The matrix's height couldn't be zero");
  }
  
  
  for($i=0; $i< $this->height; $i++) {
    //Check if the rows are arrays
    if(!is_array($arr[$i])) {
      $fl=false;
      break;
    }
  
    //Check if the rows have descending length
    if(count($arr[$i]) != $this->width-$i) {
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
  $blnk=""; # Whitespaces to keep aligned the output string
  foreach($this->data as $M_i) {
    $out= $out.$blnk;
    foreach($M_i as $x) $out= $out . round($x, 5) . " ";
    for($j=0; $j< strlen(round($M_i[0], 5))+1; $j++) $blnk= $blnk . " ";
  $out = $out. "\n";
  }
  
  return $out;
}



/**
* Displays the matrix in screen.
*
* @param int $digits Number of precision digits, with -1 matrix's entries are not rounded off
*/
public function show($digits=-1) {
$blnk=""; # Whitespaces to keep aligned the output string

foreach($this->data as $M_i) {
  echo $blnk;
  foreach($M_i as $x) echo  $digits==-1? "$x ": round($x, $digits)." ";
  echo "\n";
  for($j=0; $j< strlen($digits==-1? $M_i[0]:round($M_i[0],$digits))+1; $j++) $blnk= $blnk." ";
}

}



/**
 * Converts the U-Triang matrix into a full matrix
 * @return Matrix The full squared matrix, with zeros in the lower half of the matrix
 */
public function to_matrix() :Matrix {
    $out=array();
    for($i=0; $i<$this->height; $i++) {
       for($j=0; $j<$i; $j++) $out[$i][]=0; #Fill with zeros
       $out[$i] = array_merge($i==0? array(): $out[$i], $this->data[$i]);
    }
    return new Matrix($out);
}



/**
 * Gets the upper half of a square matrix. Note: if the terms in the lower half are not zero, they will be ignored.
 * 
 * @param Matrix $M The full squared matrix to get its upper half
 * @return UTriang The upper triangular half of the matrix
 */
public static function from_matrix(Matrix $M) :UTriang {
    $out=array();
    if(!$M->is_squared()) { throw new DimensionException("Not squared matrix given!"); }
    
    for($i=0; $i< $M->height; $i++) {
       $out[] = array_slice($M->data[$i], $i);
    }
    
    return new UTriang($out);
}





/**
* Gets the i-th row of the matrix
* 
* @param int $i The number of row to be extracted (beggining at i=0)
* @return array The i-th row of the matrix, as an array
*/
public function get_row($i) {
  $zeros=array();
  for($j=0; $j<$i; $j++) $zeros[]=0;
  return array_merge($zeros, $this->data[$i]);
}




/**
* Gets the j-th column of the matrix
* 
* @param int $j The number of column to be extracted (beggining at j=0)
* @return array The j-th column of the matrix, as an array
*/
public function get_col($j) {
   $out=array();
   
   for($i=0; $i< $this->height; $i++) {
    if($i<=$j) $out[]= $this->data[$i][$j-$i];
    else $out[]=0;
   }
   return $out;
}


}

?>