<?php
/**
 *
 */
class MatrizSimplex
{
    private $matriz;
    private $restricciones;
    private $variables;
    public function __construct($restricciones, $variables)
    {
        $this->restricciones=$restricciones;
        $this->variables=$variables;
        $this->matriz=array();
    }

    public function getMatriz()
    {
        return $this->matriz;
    }

    private function arrayAleatorio($fila)
    {
        $m=array();
        for ($i=0; $i < $this->variables; $i++) {
            if ($fila==$this->restricciones) {
                array_push($m, -1*rand(20, 100));
            } else {
                array_push($m, rand(1, 5));
            }
        }
        for ($i=$this->variables; $i <$this->restricciones+$this->variables ; $i++) {
            if ($fila!=$this->restricciones) {
                if ($i-$this->variables==$fila) {
                    array_push($m, 1);
                } else {
                    array_push($m, 0);
                }
            } else {
                array_push($m, 0);
            }
        }
        array_push($m, rand(20, 60));
        return $m;
    }

    public function crearMatriz()
    {
        for ($fila=0; $fila <= $this->restricciones; $fila++) {
            $m =$this->arrayAleatorio($fila);
            array_push($this->matriz, $m);
        }
    }
}


class Simplex
{
    private $matriz;
    private $iteracion;
    private $restricciones;
    private $variables;
    public function __construct($restricciones, $variables)
    {
        $this->iteracion=0;
        $matrizSimplex=new MatrizSimplex($restricciones, $variables);
        $matrizSimplex->crearMatriz();
        $this->matriz=$matrizSimplex->getMatriz();
        $this->variables=$variables;
        $this->restricciones=$restricciones;
    }

    public function metodoSimplex()
    {
        echo "metodo:<br><br>";
        while ($this->comprobarResultado()!=true) {
            $this->NuevaTabla($this->filaPivote(), $this->columnaPivote());
            $this->iteracion++;
        }
        echo "Numero de iteraciones: ".$this->iteracion."<br><br>";
    }

    public function columnaPivote()
    {
        $pos = 0;
        $aux = $this->matriz[$this->restricciones][0];
        for ($i = 0; $i < $this->variables + $this->restricciones; $i++) {
            if ($aux > $this->matriz[$this->restricciones][$i]) {
                $aux = $this->matriz[$this->restricciones][$i];
                $pos = $i;
            }
        }
        return $pos;
    }

    public function filaPivote()
    {
        $columna = $this->ColumnaPivote();
        $temp = 0;
        $razon = $this->matriz[0][$this->variables + $this->restricciones] / $this->matriz[0][$columna];
        $pos = 0;
        for ($i = 1; $i < $this->restricciones; $i++) {
            if ($this->matriz[$i][$columna] != 0) {
                $temp = $this->matriz[$i][$this->variables + $this->restricciones] / $this->matriz[$i][$columna];
                if ($razon > $temp && $temp >= 0) {
                    $razon = $temp;
                    $pos = $i;
                }
            }
        }
        return $pos;
    }

    public function nuevaTabla($Fila, $Columna)
    {
        $pivote = $this->matriz[$Fila][$Columna];
        $temp = 0;
        for ($i = 0; $i < $this->restricciones + $this->variables + 1; $i++) {
            $this->matriz[$Fila][$i] = intval($this->matriz[$Fila][$i] / $pivote);
        }
        for ($i = 0; $i < $this->restricciones+ 1; $i++) {
            $temp = $this->matriz[$i][$Columna];
            for ($j = 0; $j < $this->variables + $this->restricciones + 1; $j++) {
                if ($i != $Fila) {
                    $this->matriz[$i][$j] = $this->matriz[$i][$j] - $temp * $this->matriz[$Fila][$j];
                } else {
                    break;
                }
            }
        }
    }

    public function comprobarResultado()
    {
        $result = true;
        for ($i = 0; $i < $this->restricciones + $this->variables; $i++) {
            if ($this->matriz[$this->restricciones][$i] < 0) {
                $result = false;
                break;
            }
        }
        return $result;
    }

    public function imprimirMatriz()
    {
        $tabla = "<table border=1 bordercolor= #00000 bgcolor=#0FF00 >";
        foreach ($this->matriz as $value) {
            $tabla.="<tr>";
            foreach ($value as $numero) {
                $tabla.= "<td style=padding:3px;>".$numero."</td>";
            }
            $tabla.="</tr>";
            //echo "<br><br>";
        }
        $tabla .= "</table>";
        echo $tabla."<br>";
    }
}
$simplex = new simplex($_GET['rest'], $_GET['var']);
$simplex->imprimirMatriz();
$simplex->metodoSimplex();
$simplex->imprimirMatriz();
