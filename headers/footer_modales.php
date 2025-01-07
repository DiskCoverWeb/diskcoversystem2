
    </div>	
	</div>
	


	<!-- Bootstrap JS -->
	<script src="../../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="../../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="../../assets/plugins/select2/js/select2-custom.js"></script>
	<script src="../../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="../../assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script src="../../dist/js/login.js"></script>
	<!--app JS-->
	<script src="../../assets/js/app.js"></script>
</body>

</html>


  
  <script>
	function IngClave(tipo,base=false)
    {
        $.ajax({
            data: {
                usuario: tipo
            },
            url: '../controlador/panel.php?IngClaveCredenciales=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if(response['res'] == 1){
                    $('#titulo_clave').text(response['nombre']);

                    if(base)
                    {
                        $('#BuscarEn').val(base);
                    }
                    $('#TipoSuper_MYSQL').val(tipo);
                    $("#clave_supervisor").modal('show');
                }else{
                    Swal.fire("Error", "Hubo un problema al obtener datos del supervisor.", "error");
                }
            }
        });
    }
  </script>

