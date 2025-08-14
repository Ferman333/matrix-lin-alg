<?php
include("../matrices.php");

#Example of a problematic matrix that requires pivoting for a good accurance
$M = new Matrix([[1.334e-4, 4.123e1, 7.912e2, -1.544e3],
                 [1.777, 2.367e-5, 2.07e1, -9.035e1],
                 [9.188, 0, -1.015e1, 1.988e-4],
                 [1.022e2, 1.442e4, -7.014e2, 5.321]]);

$b = [-711.56988662, -67.87297633, -0.9618012, 13824.121];

echo "Matrix created\n";


// Inverse matrix
$t1=hrtime(true);
$M_inv = Matrix::inv_GJ($M); 
$dt=hrtime(true)-$t1;
$dt=$dt/1e+6; #To milliseconds

echo "M^{-1}=\n";
echo $M_inv;
echo "Matrix inverted ($dt ms). Checking...\n";


#Check if M_inv is the inverse: multiply M*M_inv
$P= Matrix::prod($M,$M_inv);
echo "M*M^{-1}=\n";
echo $P;


//Solving the system
$t1=hrtime(true);
$x= Matrix::solve_GJ($M,$b);  # Exact solution is [1,1,1,1]
$dt=hrtime(true)-$t1;
$dt=$dt/1e+6; #To milliseconds

echo "Solucions of Mx=b:\n";
echo $x;
echo "Solved in $dt ms. Checking:\n";

$B=Matrix::prod($M,$x);
echo "Mx equals b: ". Matrix::equals($B, Matrix::to_col($b), 1e-6) ."\n";
echo $B;


echo "Determinant: ";
$t1=hrtime(true);
echo Matrix::det($M);
echo "\n(calculated in ". (hrtime(true)-$t1)/1e+6 . " ms)\n";



?>
