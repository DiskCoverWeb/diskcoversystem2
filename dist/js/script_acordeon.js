$(document).ready(function(){
    $('.menujq > ul > li:has(ul)').addClass('desplegable fa');
     $('.menujq > ul > li > a').click(function() {
       datos_cuenta($(this).text());
       var comprobar = $(this).next();
       $('.menujq li').removeClass('active');
       $(this).closest('li').addClass('active fa');
       if((comprobar.is('ul')) && (comprobar.is(':visible'))) {
          $(this).closest('li').removeClass('active');
          comprobar.slideUp('normal');
       }
       if((comprobar.is('ul')) && (!comprobar.is(':visible'))) {
          $('.menujq ul ul:visible').slideUp('normal');
          comprobar.slideDown('normal');
       }
    });
    $('.menujq > ul > li > ul > li:has(ul)').addClass('desplegable fa');
     $('.menujq > ul > li > ul > li > a').click(function() {
       datos_cuenta($(this).text());
       var comprobar = $(this).next();
       $('.menujq ul ul li').removeClass('active');
       $(this).closest('ul ul li').addClass('active fa');
       if((comprobar.is('ul ul')) && (comprobar.is(':visible'))) {
          $(this).closest('ul ul li').removeClass('active');
          comprobar.slideUp('normal');
       }
       if((comprobar.is('ul ul')) && (!comprobar.is(':visible'))) {
          $('.menujq ul ul ul:visible').slideUp('normal');
          comprobar.slideDown('normal');
       }
    });
    
    $('.menujq > ul > li > ul > li > ul > li:has(ul)').addClass('desplegable fa');
    $('.menujq > ul > li > ul > li > ul > li > a').click(function() {
       datos_cuenta($(this).text());
     var comprobar = $(this).next();
     $('.menujq ul ul ul li').removeClass('active');
     $(this).closest('ul ul ul li').addClass('active fa');
     if((comprobar.is('ul ul ul')) && (comprobar.is(':visible'))) {
        $(this).closest('ul ul ul li').removeClass('active');
        comprobar.slideUp('normal');
     }
     if((comprobar.is('ul ul ul ')) && (!comprobar.is(':visible'))) {
        $('.menujq ul ul ul ul:visible').slideUp('normal');
        comprobar.slideDown('normal');
     }
  });
  
  
  
  $('.menujq > ul > li > ul > li > ul > li ul > li:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
 
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul ul ul u ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
 
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul ul ul u ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> ul > li>:has(ul)').addClass('desplegable fa');
  $('.menujq > ul > li > ul > li > ul > li > ul > li > ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> ul > li> a').click(function() {
       datos_cuenta($(this).text());
   var comprobar = $(this).next();
   $('.menujq ul ul ul ul ul ul ul ul ul ul ul ul li').removeClass('active');
   $(this).closest('ul ul ul ul ul ul ul ul ul ul ul ul li').addClass('active fa');
   if((comprobar.is('ul ul ul ul ul ul ul ul u ul ul ul')) && (comprobar.is(':visible'))) {
      $(this).closest('ul ul ul ul ul ul ul ul ul ul ul ul li').removeClass('active');
      comprobar.slideUp('normal');
   }
   if((comprobar.is('ul ul ul ul ul ul ul ul ul ul ul ul ')) && (!comprobar.is(':visible'))) {
      $('.menujq ul ul ul ul ul ul ul ul ul ul ul ul ul:visible').slideUp('normal');
      comprobar.slideDown('normal');
   }
  });
  
  
  
  });
 
 function datos_cuenta(cuenta)
 {
   var select = cuenta.split('-');
   $('#MBoxCta').val(select[0]);
   $('#TextConcepto').val(select[1].trim());
   var tipo = $('#MBoxCta').val().split('.');
   let supe=0;
   for (var i = 0; i < tipo.length-1; i++) {
     if(i==0)
     {
       supe='';
       supe+=tipo[i]+'.';
     }else
     {
 
       supe+=tipo[i]+'.';
     }
     // console.log('h');
   }
   for (var i = 0; i < tipo.length; i++) {
     if(i==0)
     {
       ext='';
       ext+=tipo[i];
     }else
     {
 
       ext+=tipo[i];
     }
     // console.log('h');
   }
    // console.log(tipo);
   tip_cuenta(tipo[0]);
   cargar_presupuesto(select[0]);
   cargar_datos_cuenta(select[0]);
   $('#LabelCtaSup').val(supe);
   $('#MBoxCta').val(select[0]+'.');
   $('#TxtCodExt').val(ext);
 }
 
 function tip_cuenta(cuenta)
 {
    $.ajax({
     data:  {cuenta:cuenta},
     url:   '../controlador/contabilidad/ctaOperacionesC.php?tip_cuenta=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) { 
        $('#LabelTipoCta').val(response);              
     }
   });
 }