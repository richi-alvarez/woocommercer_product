var p = $("#mostrardatos3").text();

ePayco.setPublicKey(p);



                             

                              $('#customer-form').submit(function(event) {  

                   // debugger       
                 
                              var shipping = $("#shipping").text();  
                              var myarray = $("#myarray").text();  

                                          var d = new Date();

                                        var p = $("#mostrardatos3").text();

                                          var  lang = $("#lang").text();

                                          var  p_c = $("#p_c").text();

                                          var p2 = $("#test").text();

                                          var extra1 = $("#extra1").text();

                                          var  response = $("#response").text();

                                          var  confirmacion = $("#confirmacion").text();

                                          var  city = $("#city").text();

                                          var  ip = $("#ip").text();

                                          var name_pl=$("#descripcion_p").text()+d.getMinutes()+d.getHours()+d.getSeconds();

                                          var description=$("#descripcion_p").text();

                                          var currency=$("#currency").text();

                                          var amount=$("#amount").text();

                                          var addressd=$("#addressd").text();

                          
                                          var Direccionf=document.getElementById('Direccionf').value;
                                      

                                           var tokenurl=$("#tokenurl").text();

                                            var trial=$("#trial").text();

                                            var interval_count=$("#interval_count").text();

                                            var interval_mounth=$("#interval_mounth").text();

                                            var interval_day=$("#interval_day").text();

                                            var interval_week=$("#interval_week").text();

                                            var interval_year=$("#interval_year").text();

                                  var id_p=  $('select[name="selec_plan"] option:selected').val(); 

                                  var name_p= $('select[name="selec_plan"] option:selected').text();

                                   var id_d=  $('select[name="documento"] option:selected').val(); 

                                  var name_d= $('select[name="documento"] option:selected').text();

                                  var nombre_f=document.getElementById('card[name]').value;

                                 var numbre_d = document.getElementById('cedula').value;

                                    var y=  $('select[name="selec_year_"] option:selected').val();

                                     var m=  $('select[name="selec_mont_"] option:selected').val(); 

                            //  var p = $("#mostrardatos3").text();

                             var  num_d=document.getElementById('cedula').value;

                           
if (!num_d) {
                            if (lang =="es") {     
                   alert('por favor ingresar el numero de documento en el paso anterior!');
}else{
   alert("please put  document's number in step before!");
}
                                }                 
                                  if (!nombre_f) {

                                  alert('please you have to put your name');

                                }

                               var em=document.getElementById('card[email]').value;

                                  if (!em) {

                                  alert('please you have to put your email');

                                }

                             var   te=document.getElementById('telephone').value;

                                if (!te) {

                                  alert('please you have to put your telephone');

                                }

                               

                                //m=document.getElementById('mm').value;

                                if (!m) {

                                  alert('por favor implementar el mes de expiración de la tarjeta');

                                }



                                //y=document.getElementById('y').value;

                                if (!y) {

                                  alert('por favor implementar el año de expiración de la tarjeta ');

                                }else{

                                   var ano=document.getElementById('y').value;

                                  if (ano.length <= 2) {

                          

                                  alert('por favor implementar el año de expiración de la tarjeta con 4 digitos');

                                }}



                               /* var tok = $("#mostrardatos3").text();

                                    if (!tok) { alert('before you keep on, you have to select a plan, in the previusly step');}

                                        */

                                         

                                        event.preventDefault();
  var lockNavigation = function(lock) {
    if (lock) {
      $('input[type="button"]').prop("disabled", true);
      $('input[type="submit"]').prop("disabled", true);
      window.wizardLocked(true);
    } else {
      $('input[type="button"]').prop("disabled", false);
      $('input[type="submit"]').prop("disabled", false);
      window.wizardLocked(false);
    }
  };
       if (num_d) {

                                

                              
                                        var $form = $(this);

                                        var button=document.getElementById('enviar');

                                        $form.find(button).prop("disabled", true);       

                                        var token = ePayco.token.create($form, function(error, token) {

                                        $form.find("button").prop("disabled", false);

                                         //console.log('token');

                                        if(!error && num_d) {
lockNavigation(true);
                                          var data=token;

                                         var urls= tokenurl;

                                     

                                     var data={

                                    "nombre":nombre_f,

                                    "Email":em,

                                    "docunumber":num_d,

                                    "telephone":te,

                                    "doc":id_d,

                                    "frecuencia":id_p,

                                    "apikey":p,

                                    "privateKey":p_c,

                                    "lenguage":lang,

                                    "test":p2,

                                    "token":token,

                                    "extra1":extra1,

                                    "response":response,

                                    "confirmacion":confirmacion,

                                    "city":city,

                                    "ip":ip,

                                    "name_pl":name_pl.toString(),

                                    "currency":currency,

                                    "amount":amount,

                                    "addressd":Direccionf,

                                    "description":description,

                                    "trial":trial,

                                    "interval_count":interval_count,
                                    
                                    "shipping":shipping,

                                    "myarray":myarray,

                                    "interval_mounth":interval_mounth,

                                    "interval_day":interval_day,

                                    "interval_week":interval_week,

                                    "interval_year":interval_year,



                                         
                                

                                      };   





                                          $.ajax({

                                             type:"POST",

                                                     url:urls,

                                                     data:data,

                                                      beforeSend:function(){

                                                      if(!token=="") {   

                                                        $("#customer-form").hide(); 

                                                        // alert($("input").val()); 

                                                         console.log(data);

                                                         $("#mostrardatos21").html('<div id="contenedor"><div class="loader" id="loader">Loading...</div></div>');

                                                          

                                                        

                                                        $("#mostrardatos2").html(data);

                                                        }

                                                        else 

                                                        {

                                                         alert("there are something wrong! ");

                                                         console.log("errorr=>",error.data.description);

                                                        

                                                         $("#mostrardatos2").html("ERRORR:::::::::...");

                                                       }

                                                                                                                 

                                                                          }, 

                                                      success: function(datos){

                                                        

                                                       

 $('input[type="submit"]').prop("disabled", false);
                                                  

                                                       $("#mostrardatos21").html('<div class="divstyles">'+datos+'</div>');

                                                                         }




                                          })

                                        console.log(urls,data);



                                         } // fin del if 
                                         else{ 
                                          if (lang =="es") {
  $("#mostrardatos21").html('<div class="divstyles2">verifica la informacion  del formulario</div>');

                                          }else{
                                              $("#mostrardatos21").html('<div class="divstyles2">verify the form information</div>');
                                          }
                                        

                                        }



})
  } 
                                  });

                                       