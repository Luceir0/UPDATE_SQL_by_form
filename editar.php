<?php
    ini_set('display_errors', 1);
    require_once 'conexiones.inc.php';
    try {
        $con = conecta();
        
        if(isset($_GET["id"])){
            
            $query = $con->prepare("SELECT * FROM persona WHERE id_persona=:id");
            $query->bindParam(":id",$id);
            
            //Ahora este dato va en el array $_GET, porque viene dado por un link, y no por un formulario:
            $id=$_GET["id"];
            $query->execute();
            $registro=$query->fetch();
            
            if($registro){
                
                echo '<form action="'.$_SERVER["PHP_SELF"].'"method = "post">';
                
                echo 'Nif <input type="text" name="nif" value="'.$registro["nif"].'"></br>';
                echo 'Nombre <input type="text" name="nombre" value="'.$registro["nombre"].'"></br>';
                echo 'Apellido 1 <input type="text" name="ap1" value="'.$registro["ap1"].'"></br>';
                echo 'Apellido 2 <input type="text" name="ap2" value="'.$registro["ap2"].'"></br>';
                
                //Conservamos este valor que nos viene dado de la consulta anterior, porque nos hará falta para el UPDATE del usuario:
                echo '<input type="hidden" name="id" value="'.$id.'"></br>';
                echo '<input value="Guardar" type="submit" name="aceptar">';
                
                echo '</form>';
            }

        }else{
            //Creamos las variables con todos los datos que vamos a cambiar del usuario, y les asignamos un valor:
            
            $id = $_POST["id"];
            $nif = $_POST["nif"];
            $nombre = $_POST["nombre"];
            $ap1 = $_POST["ap1"];
            $ap2 = $_POST["ap2"];
            
            //Preparamos la consulta de UPDATE:
            
            $consulta = $con -> prepare('UPDATE persona SET  nif=:nif, nombre=:nombre, ap1=:ap1, ap2=:ap2 WHERE id_persona=:id');
            
            //Bindeamos los valores de las variables del usuario al que queremos hacer update, a los placeholders de la consulta preparada.
            
            $consulta -> bindParam(":id",$id);
            $consulta -> bindParam(":nif",$nif);
            $consulta -> bindParam(":nombre",$nombre);
            $consulta -> bindParam(":ap1",$ap1);
            $consulta -> bindParam(":ap2",$ap2);
            
            //Ejecutamos (creamos la variable nregistros para saber en cuántos se hace el cambio):
            
            $nregistros = $consulta -> execute();
            
            //Mensaje para el usuario:
            
            echo "Se han actualizado ".$nregistros." registros";

        }
    
    }catch (Exception $ex) {
        echo $ex->getMessage();
    } finally {
        
        $con=null;
    }
?>


