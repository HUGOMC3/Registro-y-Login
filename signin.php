<?php
$errores = '';
$enviado=true;
// Comprobamos que el formulario haya sido enviado con las variables que hayamos puesto en index.view, deben llamarse igual!
if (isset($_POST['submit'])) {
	$Nombre = $_POST['Nombre'];
	$Apellido = $_POST['Apellido'];
    $Email = $_POST['Email'];
   	$Pass = $_POST['Password'];
   	$Hash = password_hash($Pass, PASSWORD_DEFAULT);

   	if (!empty($Nombre)) { //podemos combrobar con el apellido también

		$Nombre = filter_var($Nombre, FILTER_SANITIZE_SPECIAL_CHARS);//limpia o verifica que es un texto
		//echo $Nombre;
		
	} else {
		$errores .= 'Por favor ingresa un nombre <br />';
		$enviado=false;
	}

	if (!empty($Email)) { //comprobamos que es un email válido y que lo ha enviado
		$Email = filter_var($Email, FILTER_SANITIZE_EMAIL);

		if (!filter_var($Email, FILTER_VALIDATE_EMAIL)){
			$errores .= 'Por favor ingresa un correo valido <br />';
			$enviado=false;
		} 

	} else {
		$errores .= 'Por favor ingresa un correo <br />';
		$enviado=false;
	}

	if($enviado==false){ //lanzamos los errores que hayan podido ocurrir
		echo "$errores";
	}

	else{ //si todo ok


	//conectamos con la base de datos que se llama 'prueba_datos'	
		$conexion = new mysqli('localhost', 'root', '', 'prueba_datos');


			if ($conexion->connect_errno){
				die('Lo siento hubo un problema con el servidor');
			} 
			else {
				$sql = "SELECT * FROM usuarios"; //Traemos los elementos de la base de datos
	
				$connect = $conexion->query($sql); //La conexión se ejecuta
	
				if($connect->num_rows){ //Con este condicional vamos a comprobar que hay datos en la base de datos
		
		//El método fetch_assoc trae la información del elemento de cada fila que queramos
					$found=false;
					while($fila = $connect->fetch_assoc()){
				
						if($fila['Email']==$Email){
							$found=true;
							//Si el correo se encuentra en la base de datos, te muestra este mensaje, y te lleva a la pagina de login para que inicies sesión
							echo  "Hola ". $fila['Email'] . ' este usuario ya se encuentra registrado<br />';
							header('Location: index.php' );
					 	break;
						}

					}
					if($found==false){
						$sql1 = "INSERT INTO usuarios(ID,Nombre,Apellido,Password,Email) VALUES (null,'$Nombre','$Apellido','$Hash','$Email')";
						$connect = $conexion->query($sql1);
							
						if($conexion->affected_rows >= 1){ 
									//echo "hola, $Nombre te has registrado con éxito.";
									//session_start(); //Iniciamos una sesión del cliente
									// session_destroy();
//									$_SESSION['nombre'] = 'Vane';
							//include 'funciones.php';//pagina con lógica para saludar al usuario
							//comprueba($Email);//funcion comprueba nombre e inicia sesión



				//Si el correo no esta en la base de datos, registra los datos que ha introducido el usuario, y te lleva a la página de login para que inicies sesión
							header('Location: index.php' );
						} 
					}
					else{echo  "Hola ". $Email . ' este usuario ya se encuentra registrado<br />';}
					//redirigimos al usuario al LOGIN	
				}
			else {
				echo 'No hay datos en la base de datos';
			}
		}	
	}
}
require 'signin.view.php'; //llamamos a la web en html


?>