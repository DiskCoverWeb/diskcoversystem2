//Date picker
$('#desde').datepicker({
    dateFormat: 'dd/mm/yyyy',
  autoclose: true
});
$('#hasta').datepicker({
    dateFormat: 'dd/mm/yyyy',
  autoclose: true
});
//modificar url
function modificar(texto){
    var l1=$('#l1').attr("href");  
    var l1=l1+'&OpcDG='+texto;
    //asignamos
    $("#l1").attr("href",l1);
    
    var l2=$('#l2').attr("href");  
    var l2=l2+'&OpcDG='+texto;
    //asignamos
    $("#l2").attr("href",l2);
    
    var l4=$('#l4').attr("href");  
    var l4=l4+'&OpcDG='+texto;
    //asignamos
    $("#l4").attr("href",l4);
    
    var l5=$('#l5').attr("href");  
    var l5=l5+'&OpcDG='+texto;
    //asignamos
    $("#l5").attr("href",l5);
    
    var l6=$('#l6').attr("href");  
    var l6=l6+'&OpcDG='+texto;
    //asignamos
    $("#l6").attr("href",l6);
  //document.getElementById("mienlace").innerHTML = texto;
  //document.getElementById("mienlace").href = url;
  //document.getElementById("mienlace").target = destino;
} 