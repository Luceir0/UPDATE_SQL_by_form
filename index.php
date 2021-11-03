<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Búsqueda param con pdo</title>
    </head>
    <body>
        <?php
        ini_set('display_errors', 1);
        require_once 'conexiones.inc.php';
        
        echo '<form action="'.$_SERVER["PHP_SELF"].'"method = "post">';
        echo 'Nombre <input type="text" name="nombre"></br>';
        echo 'Apellido 1 <input type="text" name="ap1"></br>';
        echo 'Apellido 2 <input type="text" name="ap2"></br>';
        echo '<input value="Consultar" type="submit" name="aceptar">';
        echo '</form>';
        
        $sql = "SELECT * FROM persona";
        $restrictions = '';
        $con = conecta();
        $possibleParams = ["nombre", "ap1", "ap2"];
        
        if (count($_POST) > 0) {
            try {
                $conn = conecta();

                //Recorrido de los posibles parámetros teniendo en cuenta los valores de $_POST, incluyendo las restricciones a string $restrictions:
                
                foreach ($possibleParams as $oneParam) {

                    if (!empty($_POST[$oneParam])) {

                        if (empty($restrictions)) {
                            $restrictions .= ' WHERE ' . $oneParam . "=:" . $oneParam;
                        } else {
                            $restrictions .= ' AND ' . $oneParam. "=:" . $oneParam;
                        }
                    }
                    
                }
                
                    $sql.= $restrictions;
                    $query = $conn -> prepare($sql);
                    
                    if($_POST["nombre"]) {    
                        $query -> bindParam(':nombre', $parametro_nombre, PDO::PARAM_STR);
                        $parametro_nombre = $_POST["nombre"];
                    }
                    
                    
                    if($_POST["ap1"]) {
                        $query -> bindParam(':ap1', $parametro_apellido1, PDO::PARAM_STR);
                        $parametro_apellido1 = $_POST["ap1"];
                    }
                    
                    
                    if($_POST["ap2"]) {
                        $query -> bindParam('ap2', $parametro_apellido2, PDO::PARAM_STR);
                        $parametro_apellido2 = $_POST["ap2"];
                    }
                    
                    
                    $query->execute();
                    
                    //Mostramos por pantalla todos los resultados de la consulta, convertidos en links.
                    //En cada link se nos va a mostrar la información del usuario de la tabla que cumpla con esas restricciones, para poder hacerle UPDATE:
                    
                    while($resultado = $query ->fetch()){
                        $id=$resultado["id_persona"];
                        echo "<a href='editar.php?id=$id'>".$resultado["nombre"]." ".$resultado["ap1"]." ".$resultado["ap2"]."<br>"."</a>";
                    }
                    
            } catch (Exception $ex) {
                echo $ex->getMessage();
            } finally {
                
                $query=null;
                $con=null;
            }
        }
?>
    </body>
</html>

