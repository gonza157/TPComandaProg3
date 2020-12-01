<?php
namespace App\Utils;

class Archivo{

   

//METODOS PARA EL GUARDADO DE IMAGENES

    public static function GuardarImagen(){
        $tmp_name = $_FILES['foto']['tmp_name'];
        $name= $_FILES['foto']['name'];
    
        $nombre = explode('.',$name)[0].'--'.time().'--.'.explode('.',$name)[1];
    
        $carpeta = '../src/Images/'.$nombre;
        move_uploaded_file($tmp_name, $carpeta );
        //ese move devuelve 1 si quiero saber si se guarod la imagen
    }

    public static function GuardarImagenConNombre($nombre){
        $tmp_name = $_FILES['foto']['tmp_name'];
        $name= $_FILES['foto']['name'];

        //'../Images/'.
        $carpeta = '../src/imagenes/'.$nombre.'.'.explode('.',$name)[1];
        var_dump( $_FILES['foto']);
        move_uploaded_file($tmp_name, $carpeta );
        //ese move devuelve 1 si quiero saber si se guarod la imagen
    }
    
    public static function BorrarImagen($OrigenCarpEImgABorrar, $DestinoCarpEImgABorrar){
        if(copy($OrigenCarpEImgABorrar, $DestinoCarpEImgABorrar)){
            unlink($OrigenCarpEImgABorrar);
            return 1;
        }
        else{
            return 0;
        }
    }

    


}