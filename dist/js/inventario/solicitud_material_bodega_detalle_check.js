	
function pedidos_contratista(orden)
{     
  var parametros = 
  {
    'order': orden,
  }
  $.ajax({
      url:   '../controlador/inventario/solicitud_material_bodegaC.php?pedidos_contratista_detalle_check=true',
      type:  'post',
      data: {parametros:parametros},
      dataType: 'json',
      success:  function (response) {           
         $('#tbl_body').html(response.tabla);       
        $('#lbl_contratista').text(response.datos[0]['Cliente']);
		$('#lbl_orden').text(response.datos[0]['Orden_No']); 
		if(response.estado=='.')
		{
			$('#btn_aprobar').css('display','block');
		}else
		{
			$('#btn_comprobante').css('display','block');
		}             
      }
  });
}

function cargar_rubro_linea(id,cc)
{
	var parametros = 
	  {
	    'cc':cc,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?listar_rubro=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	        op = '<option value="">Seleccione rubro</option>';           
	        response.forEach(function(item,i){
	        	op+='<option value="'+item.id+'">'+item.text+'</option>'
	        })       

	        $('#ddl_linea_rubro_'+id).html(op);
	      }
	  });

}

function guardar_linea(id)
{
	if($('#ddl_linea_cc_'+id).val()=='' || $('#ddl_linea_rubro_'+id).val()=='')
	{
		Swal.fire("","Seleccione todos los capos","info")
		return false;
	}
	var parametros = 
	  {
	    'cc':$('#ddl_linea_cc_'+id).val(),
	    'rubro':$('#ddl_linea_rubro_'+id).val(),
	    'ID':id,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?editarCCRubro=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	      	if(response==1)
	      	{
	      		Swal.fire('','Linea Editada','success').then(function(){
	      			pedidos_contratista(orden);	 
	      		})   
	      	}     
	      }
	  });

}


function GenerarEntrega()
{
	 let todosSeleccionados = $(`#form_entregados input[type="checkbox"]:not(:checked)`).length === 0;
    if (!todosSeleccionados) {
        Swal.fire('','Por favor, selecciona todos los checkboxes del formulario.','info');
        return false; // Retorna false si falta algún checkbox
    }
	var parametros = 
	  {
	    'orden':orden,
	  }
	  $.ajax({
	      url:   '../controlador/inventario/solicitud_material_bodegaC.php?AprobarEntrega=true',
	      type:  'post',
	      data: {parametros:parametros},
	      dataType: 'json',
	      success:  function (response) {
	       if(response==1)
	       {
	       	 Swal.fire("Checking aprobado","","success").then(function()
	       	 	{
	       	 		location.href = "../vista/inicio.php?mod=03&acc=checkAprobacion"
	       	 	});
	       }
	      }
	  });
}
