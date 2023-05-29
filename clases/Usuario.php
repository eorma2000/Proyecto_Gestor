<?php
    require_once "Conexion.php";
    class Usuario extends Conectar{

        public function agregarUsuario($datos){
            $conexion = Conectar::conexion();

            //esta mandando a llamar a un metodo sobre la misma clase
            // if(self::buscarUsuarioRepetido($datos['usuario'])){
            //     return 2; //si ya existe, debe que retornar 2
            // }else{
                
            // }
            $sql = "INSERT INTO gestor.gg_usuarios (nombre, fechaNacimiento, email, usuario, password) VALUES(?, ?, ?, ?, ?)";
            $query = $conexion->prepare($sql); //prepare: es un metodo de la API de mysqli
            //la "s" significa que son string los parametros
            $query->bind_param(
                'sssss',
                $datos['nombre'],
                $datos['fechaNacimiento'],
                $datos['email'],
                $datos['usuario'],
                $datos['password']); //los nombre dentro de datos deben que ser igual al nombre que tiene en datos (agregarUsuario.php)
            $exito = $query->execute(); //se ejecuta el query
            $query->close(); //se cierra el query
            return $exito; //exito: responde solo con un 0 o 1 
            
        }
        //corregir el registro repetido
        public function buscarUsuarioRepetido($usuario){
            $conexion = Conectar::conexion();//para acceder a la conexion

            $sql = "SELECT *
                    FROM gestor.gg_usuarios 
                    WHERE usuario = '$usuario' ";
            $result = mysqli_query($conexion, $sql);
            $datos = mysqli_fetch_array($result); //para que nos de en asociativo
            
            if ($datos['usuario'] != "" || $datos['usuario'] == $usuario) { 
                return 1;//si no es nulo nos regresa un 1
            }else{
                return 0; //si es nulo nos regresa un 0
            }
        }

        public function login($usuario, $password){
            $conexion = Conectar::conexion();

            $sql = "SELECT count(*) as existeUsuario FROM gestor.gg_usuarios WHERE usuario = '$usuario' AND password = '$password'";
            $result = mysqli_query($conexion, $sql);
            
            $respuesta = mysqli_fetch_array($result)['existeUsuario'];
            if($respuesta > 0){
                $_SESSION['usuario'] = $usuario;
                $sql = "SELECT id_usuario FROM gestor.gg_usuarios WHERE usuario = '$usuario' AND password = '$password'";
                $result = mysqli_query($conexion, $sql);
                $idUsuario = mysqli_fetch_row($result)[0];
                $_SESSION['idUsuario'] = $idUsuario;
                return 1;
            }else{
                return 0;
            }
        }
    }
?>