<?php
include("../matrices.php");



$n=50;#250;

$M = Matrix::random_matrix($n,$n);
$b = Matrix::random_matrix(1,$n)->get_row(0);

echo "Matrix created\n";



$t1=hrtime(true);
$M_inv = Matrix::inv_GJ($M); #$X= Matrix::solve_GJ($M,$b);
$dt=hrtime(true)-$t1;
$dt=$dt/1e+6; #To milliseconds

echo "Matrix inverted ($dt ms). Checking...\n";




#Check if M_inv is the inverse: multiply M*M_inv
echo "Is M^{-1} really the inverse? ".Matrix::equals(Matrix::prod($M,$M_inv), Matrix::get_identity($n), 1e-5)."\n";
#Matrix::to_col($b)

echo "Determinant: ";
$t1=hrtime(true);
echo Matrix::det($M);
echo "\n(calculated in ". (hrtime(true)-$t1)/1e+6 . " ms)\n";



?>
