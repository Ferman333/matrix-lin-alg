<?php
include("../matrices.php");

/*$M = new Matrix( [
       [1,1,1,1,1,1],
       [0,1,0,1,0,1],
       [0,0,1,0,0,1],
       [0,0,0,1,0,0],
       [0,0,0,0,1,0],
       [0,0,0,0,0,1]
       
       /*[1,1,1,1],
       [0,1,0,1],
       [0,0,1,0],
       [0,0,0,1] ////
     ]);*/

$M = new Matrix( [
     [0, 31,-52, 18, -30],
     [0, 12, -14, 52, 26],
     [36, 102, 81, -94, 35],
     [30, 10, -34, -56, 12],
     [20, 13, 3, -6, -8]
     ]);

$b = [1, -4, 24, -35, 12];


echo "Matrix created\n";



$t1=hrtime(true);
$M_inv = Matrix::inv_GJ($M); #$X= Matrix::solve_GJ($M,$b);
$dt=hrtime(true)-$t1;
$dt=$dt/1e+6; #To milliseconds

echo "Matrix inverted ($dt ms). Checking...\n";


#Check if M_inv is the inverse: multiply M*M_inv
$P= Matrix::prod($M,$M_inv);
echo "Is M^{-1} really the inverse? ".Matrix::equals($P, Matrix::get_identity($M->get_w()), 1e-5)."\n";
echo $P;
#Matrix::to_col($b)

echo "Determinant: ";
$t1=hrtime(true);
echo Matrix::det($M);
echo "\n(calculated in ". (hrtime(true)-$t1)/1e+6 . " ms)\n";



?>
