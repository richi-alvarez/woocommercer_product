<?php


$name = $_POST['nombre'];



$email=$_POST['Email'];

 

$docunumber = $_POST['docunumber'];



$telephone = $_POST['telephone'];



$doc = $_POST['doc'];



$frecuencia = $_POST['frecuencia'];



$apikey=$_POST['apikey'];



$privatekey=$_POST['privateKey'];



$languaje=$_POST['lenguage'];



$test=$_POST['test'];

 

$token=$_POST['token'];

$myarray=$_POST['myarray'];

$myarray2=json_decode($myarray);

$extra1=$_POST['extra1'];



$response=$_POST['response'];



$confirmacion=$_POST['confirmacion'];



$city=$_POST['city'];



$ip=$_POST['ip'];



$name_pl=$_POST['name_pl'];

$plan_name = str_replace(' ', '', $name_pl);





$description=$_POST['description'];



$currency=$_POST['currency'];



$amount=$_POST['amount'];

   

$addressd=$_POST['addressd']; 



$trial=$_POST['trial'];      

$shipping=$_POST['shipping'];                                                                              

$interval_count=$_POST['interval_count'];   

$interval_mounth=$_POST['interval_mounth'];       

$interval_day=$_POST['interval_day'];    

$interval_week=$_POST['interval_week']; 

$interval_year=$_POST['interval_year']; 

if ( $test=="false") {

  $testt=false;

}else{

   $testt=true;

}



require_once 'lib/vendor/autoload.php';

$epayco = new Epayco\Epayco(array(

    "apiKey" => $apikey,

    "privateKey" => $privatekey,

    "lenguage" => $languaje,

    "test" =>  $testt

));

$customer = $epayco->customer->create(array(
    "token_card" => $token,
    "name" => $name,
    "email" => $email,
    "phone" => $telephone,
    "default" => true,
    "city" => $city,
    "address" => $addressd,
    "cell_phone"=> $telephone,
));
 $valorArray= count($myarray2);
 if ($valorArray>=2) {

 $valores_split=0;
        $valorArray2= $valorArray-1;
        for ($i=$valorArray2; $i >= 0 ; $i--) { 
            $myarr=$myarray2[$i]; 
              $planss=$myarr->name.date("d-m-Y-h_i_sa");
              if ($myarr->interval=="month") {
          $interval_count= $interval_mounth;
              }else{
                if ($myarr->interval=="year") {
                  $interval_count= $interval_year;
                }else{
                   if ($myarr->interval=="week") {
                  $interval_count= $interval_week;
                }else{
                  $interval_count= $interval_day;
                }
                }
              }
   

 


$cadena_formateada = str_replace(' ', '-', $planss);

$plan = $epayco->plan->create(array(
     "id_plan" => $cadena_formateada ,
     "name" => $myarr->name,
     "description" => $myarr->name,
     "amount" => $myarr->amount,
     "currency" => $currency,
     "interval" => $myarr->interval,
     "interval_count" => $interval_count,
     "trial_days" => $trial
));

$sub = $epayco->subscriptions->create(array(
  "id_plan" => $plan->data->id_plan,
  "customer" => $customer->data->customerId,
  "token_card" => $token,
  "doc_type" => $doc,
  "doc_number" => $docunumber,
  "url_response" => $response,
  "url_confirmation" => $confirmacion,
  "address" => $addressd,
  "cell_phone"=> $telephone,

));
$subpay = $epayco->subscriptions->charge(array(
  "id_plan" => $plan->data->id_plan,
  "customer" => $customer->data->customerId,
  "token_card" => $token,
  "doc_type" => $doc,
  "doc_number" => $docunumber,
  "ip"=>$ip,
  "cell_phone"=> $telephone,
  "url_response" => $response,
  "url_confirmation" => $confirmacion,
  "address" => $addressd,

  //"extra1"=>$extra1,
    "city" =>$city,
));

$ref_payco = isset($subpay->data->ref_payco) ? $subpay->data->ref_payco : 'no hay ref_payco' ;
$factura2=0;
$factura2=$factura2+$ref_payco;

//$factura2=$factura2+$ref_payco." ".;

        };

 }else{

$plan = $epayco->plan->create(array(
     "id_plan" =>  $plan_name,
     "name" => $plan_name,
     "description" => $description,
     "amount" => (float)$amount,
     "currency" => $currency,
     "interval" => $frecuencia,
     "interval_count" => $interval_count,
     "trial_days" => $trial
));

$sub = $epayco->subscriptions->create(array(
  "id_plan" => $plan->data->id_plan,
  "customer" => $customer->data->customerId,
  "token_card" => $token,
  "doc_type" => $doc,
  "doc_number" => $docunumber,
  "url_response" => $response,
  "url_confirmation" => $confirmacion,
  "address" => $addressd,
  "cell_phone"=> $telephone,
 
));
$subpay = $epayco->subscriptions->charge(array(
  "id_plan" => $plan->data->id_plan,
  "customer" => $customer->data->customerId,
  "token_card" => $token,
  "doc_type" => $doc,
  "doc_number" => $docunumber,
  "ip"=>$ip,
  "cell_phone"=> $telephone,
  "url_response" => $response,
  "url_confirmation" => $confirmacion,
  "address" => $addressd,
  "city" =>$city,
 // "extra1"=>$extra1,
));
$ref_payco = isset($subpay->data->ref_payco) ? $subpay->data->ref_payco : 'no hay ref_payco' ;
echo $ref_payco;
echo "<br>";
 };
 

 
//var_dump("token php");
//echo "<br>";
//die();



//$ref_payco = isset($subpay->data->ref_payco) ? $subpay->data->ref_payco : '' ;

$factura=isset($subpay->data->factura) ? $subpay->data->factura : "";

$descripcion=isset($description) ? $description : "" ;

$valor=isset($amount) ? $amount : "";

$iva=isset($subpay->data->iva) ? $subpay->data->iva : "";

$baseiva=isset($subpay->data->baseiva) ? $subpay->data->baseiva : "";

$moneda=isset($subpay->data->moneda) ? $subpay->data->moneda : ""; 

$banco=isset($subpay->data->banco) ? $subpay->data->banco:""; 

$estado=isset($subpay->data->estado) ? $subpay->data->estado : "" ;       

$respuesta=isset($subpay->data->respuesta) ? $subpay->data->respuesta:"";

$autorizacion=isset($subpay->data->autorizacion) ? $subpay->data->autorizacion: "";  
if ($factura2) {
 $recibo=$factura2;
}
else{
  $recibo=isset($subpay->data->recibo) ? $subpay->data->recibo:"" ;
}


$fecha=isset($subpay->data->fecha) ? $subpay->data->fecha : "";

$extra=isset($subpay->data->extra1) ? $subpay->data->extra1 : "";

//$status=$subpay->data->status;    

if (isset($subpay->data->status) && $subpay->data->status=="error") {

          $description=$subpay->data->description;

           $errors=$subpay->data->errors;

          echo $description."<br>"; 

          echo $errors;

          die();

        }        

?>

                                               <h6>Data</h6>

                                    <table class="table table-condensed">

                                         <!-- <tr>

                                            <td>ref_payco</td>

                                            <td><?php echo " ".$ref_payco; ?> </td>               

                                          </tr>-->



                                           <tr>

                                             <td>pedido</td>

                                              <td><?php echo " ".$extra1; ?> </td> 

                                          </tr>



                                          <tr>

                                              <td>descripcion </td>

                                              <td><?php echo " ".$descripcion; ?> </td> 

                                          </tr>



                                           <tr>

                                              <td>valor </td>

                                              <td><?php echo " ".number_format($valor, 2)."  $";  ?> </td> 

                                          </tr>



                                           <!--<tr>

                                              <td>iva </td>

                                              <td><?php echo " ".number_format($iva, 2)."  $"; ?> </td> 

                                          </tr>

                                            

                                           <tr>

                                              <td>baseiva</td>

                                              <td><?php echo " ".number_format($baseiva, 2)."  $";?> </td> 

                                          </tr>
-->


                                           <tr>

                                              <td>moneda</td>

                                              <td><?php echo " ".$moneda; ?> </td> 

                                          </tr>

                                          <tr>

                                              <td>banco</td>

                                              <td><?php echo " ".$banco; ?> </td> 

                                          </tr>

                                           

                                          <tr>

                                              <td>estado</td>

                                              <td><?php echo " ".$estado; ?> </td> 

                                          </tr>

                                              

                                          <tr>

                                              <td>respuesta</td>

                                              <td><?php echo " ".$respuesta; ?> </td> 

                                          </tr>

                                          <tr>

                                              <td>autorizacion</td>

                                              <td><?php echo " ".$autorizacion; ?> </td> 

                                          </tr>

                                          <tr>

                                              <td>recibo</td>

                                              <td><?php echo " ".$recibo; ?> </td> 

                                          </tr>

                                           <tr>

                                              <td>fecha</td>

                                              <td><?php echo " ".$fecha; ?> </td> 

                                          </tr>

                                     </table>



<script type="text/javascript">
  $('input[type="submit"]').prop("disable",false);
</script>


