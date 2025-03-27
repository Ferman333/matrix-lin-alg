<?php
include("matrices.php");

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



$n=5;#250;

#$M = Matrix::random_matrix($n,$n);
#$bm = Matrix::random_matrix($n,1)->toArray();
#for($k=0;$k<$n;$k++) $b[$k] = $bm[$k][0];

echo "Matriz generada\n";



$t1=hrtime(true);
$M_inv = Matrix::inv_GJ($M); #$X= Matrix::solve_GJ($M,$b);
$dt=hrtime(true)-$t1;
$dt=$dt/1e+6; #To milliseconds

echo "Matriz invertida ($dt ms). Falta verificar...\n";



#$M_inv->show(4);

#print("\nM*M^{-1}=\n");
#Matrix::prod($M,$X) ->show(2);



echo "M^{-1} es en verdad la inversa? ".Matrix::equals(Matrix::prod($M,$M_inv), Matrix::get_identity($n), 1e-5)."\n";
#Matrix::to_col($b)

echo "Determinante: ";
$t1=hrtime(true);
echo Matrix::det($M);
echo "\n". (hrtime(true)-$t1)/1e+6 . " ms\n";



?>
