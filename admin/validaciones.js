
 function validarreporte(){


    // llamada de imput nombre reporte para validacion 
    nombreReporte = document.getElementById('nombreReporte').value;

  
// validacion de tipo de reportee
    if( nombreReporte   == null || nombreReporte  == 0 ){
         
      swal("Campo Tipo de reporte   obligatorio, Escoje el tipo de  reporte   !");
  
      return false;
  
  }
    // llamada de imput asignado para validacion 
  asignado = document.getElementById('asignado').value;
   // validacion de asginado 
  if( asignado   == null || asignado  == 0 ){
         
    swal("Campo asignado a: es   obligatorio, escoje tu nombre si estas regitrado en el sistema si no comunicate con sistemas    !");

    return false;


}

 



}




 