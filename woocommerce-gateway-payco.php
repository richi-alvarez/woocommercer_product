<?php



/**



 * @since             1.0.0



 * @package           ePayco_Woocommerce_sub



 *



 * @wordpress-plugin



 * Plugin Name:       ePayco WooCommerce  Suscripction



 * Description:       Plugin ePayco WooCommerce.



 * Version:           3.7.0



 * Author:            Ricardo saldarriaga



 * Author URI:        



 *Lice






 * Domain Path:       /languages



 */







if (!defined('WPINC')) {



    die;



}







require_once(dirname(__FILE__) . '/lib/EpaycoOrders.php');







if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {






//define( 'payco', plugin_dir_path( __FILE__ ) );
    add_action('plugins_loaded', 'init_epayco_woocommerce_sub', 0);
 // add_action('admin_menu', 'wporg_options_page');
 /* function wporg_options_page()
{
    add_menu_page(
        'ePayco Recurrent payment',
        '<div class="wp-menu-image dashicons-before dashicons-admin-post"></div>Payco Reccurent',
        'manage_options',
        'payco',
        'Payco',
        plugin_dir_url(__FILE__) . 'images/payco.png',
        4
    );



}*/

    function init_epayco_woocommerce_sub()



    {
  /*
$tabs = array(
 
    'Reccurent'  =>  'Payment Reccurent',

    'Plans'    =>  'Plans',

    'Customers' => 'Customers',

    'subscriotions'  => 'subscriptions'
        
    );
  ?>
  <div id="icon-themes" class="icon32"><br/></div>
  <h2 class="nav-tab-wrapper">
    <?php
    foreach ( $tabs as $tab => $name ) {
      ?>
      <a class="nav-tab" href="?page=payco&tab=<?php echo $tab; ?>">
        <?php echo $name;
         ?>
      </a>
      <?php
    }
    ?>
      </h2>
<?php
 if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'Plans' ) {
   }
?>
<div class="wrap metabox-holder">             
     </form>
<?php
*/




        if (!class_exists('WC_Payment_Gateway')) {



            return;



        }







        class WC_ePayco_sub extends WC_Payment_Gateway



        {



            public function __construct()



            {



                $this->id = 'epayco_sub';



                $this->icon = 'https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png';



                $this->method_title = __('ePayco Checkout Suscripction', 'epayco_woocommerce_sub');



                $this->method_description = __('Suscripciones atravez de tarjetas de crédito.', 'epayco_woocommerce_sub');



                $this->order_button_text = __('Pagar', 'epayco_woocommerce_sub');



                $this->has_fields = false;



                $this->supports = array('products');







                $this->init_form_fields();



                $this->init_settings();







                $this->msg['message']   = "";



                $this->msg['class']     = "";







                $this->title = $this->get_option('epayco_title_sub');



                $this->epayco_customerid_sub = $this->get_option('epayco_customerid_sub');



                $this->epayco_secretkey_sub = $this->get_option('epayco_secretkey_sub');



                $this->epayco_publickey_sub = $this->get_option('epayco_publickey_sub');



                $this->epayco_p_key_sub = $this->get_option('epayco_p_key_sub');



                $this->description = $this->get_option('description');



                $this->epayco_testmode_sub = $this->get_option('epayco_testmode_sub');

           



                 $this->epayco_interval_count_sub = $this->get_option('epayco_interval_count_sub');
                 $this->epayco_interval_count_sub_day = $this->get_option('epayco_interval_count_sub_day');
                 $this->epayco_interval_count_sub_week = $this->get_option('epayco_interval_count_sub_week');
                 $this->epayco_interval_count_sub_year = $this->get_option('epayco_interval_count_sub_year');

                if ($this->get_option('epayco_reduce_stock_pending_sub') !== null ) {



                    $this->epayco_reduce_stock_pending_sub = $this->get_option('epayco_reduce_stock_pending_sub');



                }else{







                     $this->epayco_reduce_stock_pending_sub = "yes";



                }



                $this->epayco_type_checkout_sub=$this->get_option('epayco_type_checkout_sub');



                $this->epayco_endorder_state_sub=$this->get_option('epayco_endorder_state_sub');



                $this->epayco_url_response_sub=$this->get_option('epayco_url_response_sub');



                $this->epayco_url_confirmation_sub=$this->get_option('epayco_url_confirmation_sub');



                $this->epayco_lang_sub=$this->get_option('epayco_lang_sub')?$this->get_option('epayco_lang_sub'):'es';











                add_filter('woocommerce_thankyou_order_received_text', array(&$this, 'order_received_message'), 10, 2 );



                add_action('ePayco_init_sub', array( $this, 'ePayco_successful_request_sub'));



                add_action('woocommerce_receipt_' . $this->id, array(&$this, 'receipt_page'));



                add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_ePayco_response_sub' ) );



                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));



                add_action('wp_ajax_nopriv_returndata',array($this,'datareturnepayco_ajax_sub'));

              add_action( 'init', 'woocommerce_clear_cart_url' );


                if ($this->epayco_testmode_sub == "yes") {



                    if (class_exists('WC_Logger')) {



                        $this->log = new WC_Logger();



                    } else {



                        $this->log = WC_ePayco_sub::woocommerce_instance()->logger();



                    }



                }



            }







            function order_received_message( $text, $order ) {



                if(!empty($_GET['msg'])){



                    return $text .' '.$_GET['msg'];



                }



                return $text;



            }







            public function is_valid_for_use()



            {



                return in_array(get_woocommerce_currency(), array('COP', 'USD'));



            }







            public function admin_options()



            {



                ?>



                <style>



                    tbody{







                    }







                    .epayco-table tr:not(:first-child) {



                        border-top: 1px solid #ededed;



                    }







                    .epayco-table tr th{



                            padding-left: 15px;



                            text-align: -webkit-right;



                    }







                    .epayco-table input[type="text"]{



                            padding: 8px 13px!important;



                            border-radius: 3px;



                            width: 100%!important;



                    }



                    .epayco-table .description{



                        color: #afaeae;



                    }



                    .epayco-table select{



                            padding: 8px 13px!important;



                            border-radius: 3px;



                            width: 100%!important;



                            height: 37px!important;



                    }



                    .epayco-required::before{



                        content: '* ';



                        font-size: 16px;



                        color: #F00;



                        font-weight: bold;



                    }



                </style>



                <div class="container-fluid">



                    <div class="panel panel-default" style="">



                        <img  src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png">



                        <div class="panel-heading">



                            <h3 class="panel-title"><i class="fa fa-pencil"></i>Configuración <?php _e('ePayco', 'epayco_woocommerce_sub'); ?></h3>



                        </div>



                        <div style ="color: #31708f; background-color: #d9edf7; border-color: #bce8f1;padding: 10px;border-radius: 5px;">



                            <b>Este modulo le permite aceptar pagos seguros por la plataforma de pagos ePayco</b>



                            <br>Si el cliente decide pagar por ePayco, el estado del pedido cambiara a ePayco Esperando Pago



                            <br>Cuando el pago sea Aceptado o Rechazado ePayco envia una configuracion a la tienda para cambiar el estado del pedido.



                        </div>



                        <div class="panel-body" style="padding: 15px 0;background: #fff;margin-top: 15px;border-radius: 5px;border: 1px solid #dcdcdc;border-top: 1px solid #dcdcdc;">



                                <table class="form-table epayco-table">



                                <?php



                                    if ($this->is_valid_for_use()) :



                                        $this->generate_settings_html();



                                    else :



                                        if ( is_admin() && ! defined( 'DOING_AJAX')) {



                                            echo '<div class="error"><p><strong>' . __( 'ePayco: Requiere que la moneda sea USD O COP', 'epayco-woocommerce' ) . '</strong>: ' . sprintf(__('%s', 'woocommerce-mercadopago' ), '<a href="' . admin_url() . 'admin.php?page=wc-settings&tab=general#s2id_woocommerce_currency">' . __( 'Click aquí para configurar!', 'epayco_woocommerce_sub') . '</a>' ) . '</p></div>';



                                        }



                                    endif;



                                ?>



                                </table>



                        </div>



                    </div>



                </div>



                <?php



            }







            public function init_form_fields()



            {



                $this->form_fields = array(



                    'enabled' => array(



                        'title' => __('Habilitar/Deshabilitar', 'epayco_woocommerce_sub'),



                        'type' => 'checkbox',



                        'label' => __('Habilitar ePayco Checkout', 'epayco_woocommerce_sub'),



                        'default' => 'yes'



                    ),







                    'epayco_title_sub' => array(



                        'title' => __('<span class="epayco-required">Título</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'description' => __('Corresponde al titulo que el usuario ve durante el checkout.', 'epayco_woocommerce_sub'),



                        'default' => __('Checkout ePayco (Suscripciones)', 'epayco_woocommerce_sub'),



                        //'desc_tip' => true,



                    ),







                    'description' => array(



                        'title' => __('<span class="epayco-required">Descripción</span>', 'epayco_woocommerce_sub'),



                        'type' => 'textarea',



                        'description' => __('Corresponde a la descripción que verá el usuaro durante el checkout', 'epayco_woocommerce_sub'),



                        'default' => __('Checkout ePayco (Suscripciones por Tarjetas de crédito)', 'epayco_woocommerce_sub'),



                        //'desc_tip' => true,



                    ),







                    'epayco_customerid_sub' => array(



                        'title' => __('<span class="epayco-required">P_CUST_ID_CLIENTE</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'description' => __('ID de cliente que lo identifica en ePayco. Lo puede encontrar en su panel de clientes en la opción configuración.', 'epayco_woocommerce_sub'),



                        'default' => '',



                        //'desc_tip' => true,



                        'placeholder' => '',



                    ),







                    'epayco_secretkey_sub' => array(



                        'title' => __('<span class="epayco-required">PRIVATE_KEY</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'description' => __('LLave para autenticar y consumir los servicios de ePayco, Proporcionado en su panel de clientes en la opción configuración.', 'epayco_woocommerce_sub'),



                        'default' => '',



                        'placeholder' => ''



                    ),







                    'epayco_publickey_sub' => array(



                        'title' => __('<span class="epayco-required">PUBLIC_KEY</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'description' => __('LLave para autenticar y consumir los servicios de ePayco, Proporcionado en su panel de clientes en la opción configuración.', 'epayco_woocommerce_sub'),



                        'default' => '',



                        'placeholder' => ''



                    ),



                     'epayco_p_key_sub' => array(



                        'title' => __('<span class="epayco-required">P_KEY</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'description' => __('LLave para firmar la información enviada y recibida de ePayco. Lo puede encontrar en su panel de clientes en la opción configuración.', 'epayco_woocommerce_sub'),



                        'default' => '',



                        'placeholder' => ''



                    ),









                    'epayco_testmode_sub' => array(



                        'title' => __('Sitio en pruebas', 'epayco_woocommerce_sub'),



                        'type' => 'checkbox',



                        'label' => __('Habilitar el modo de pruebas', 'epayco_woocommerce_sub'),



                        'description' => __('Habilite para realizar pruebas', 'epayco_woocommerce_sub'),



                        'default' => 'no',



                    ),



                    

                        'epayco_interval_count_sub' => array(



                        'title' => __('<span class="epayco-required">Interval count monthly</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'label' => __('Seleccione un tipo de Checkout:', 'epayco_woocommerce_sub'),



                        'description' => __('Especifica la cantidad de intervalos por ejemplo si interval = “month” y interval_count es igual a 2 se cobrará cada 2 meses', 'epayco_woocommerce_sub'),



                        'default' => '',



                        'placeholder' => ''



                    ),
                            'epayco_interval_count_sub_day' => array(

                        'title' => __('<span class="epayco-required">Interval count dayly</span>', 'epayco_woocommerce_sub'),
                        'type' => 'text',

                        'label' => __('Seleccione un tipo de Checkout:', 'epayco_woocommerce_sub'),

                        'description' => __('Especifica la cantidad de intervalos por ejemplo si interval = “day” y interval_count es igual a 2 se cobrará cada 2 dias', 'epayco_woocommerce_sub'),

                        'default' => '',

                        'placeholder' => ''
                    ),
                             'epayco_interval_count_sub_week' => array(

                        'title' => __('<span class="epayco-required">Interval count week</span>', 'epayco_woocommerce_sub'),
                        'type' => 'text',

                        'label' => __('Seleccione un tipo de Checkout:', 'epayco_woocommerce_sub'),

                        'description' => __('Especifica la cantidad de intervalos por ejemplo si interval = “week” y interval_count es igual a 2 se cobrará cada 2 semanas', 'epayco_woocommerce_sub'),

                        'default' => '',

                        'placeholder' => ''
                    ),

                               'epayco_interval_count_sub_year' => array(

                        'title' => __('<span class="epayco-required">Interval count year</span>', 'epayco_woocommerce_sub'),
                        'type' => 'text',

                        'label' => __('Seleccione un tipo de Checkout:', 'epayco_woocommerce_sub'),

                        'description' => __('Especifica la cantidad de intervalos por ejemplo si interval = “year” y interval_count es igual a 3 se cobrará cada 3 años', 'epayco_woocommerce_sub'),

                        'default' => '',

                        'placeholder' => ''
                    ),





                    'epayco_type_checkout_sub' => array(



                        'title' => __('<span class="epayco-required">trial days</span>', 'epayco_woocommerce_sub'),



                        'type' => 'text',



                        'label' => __('Seleccione un tipo de Checkout:', 'epayco_woocommerce_sub'),



                        'description' => __('Numero de dias que se podrán probar tus servicios antes del cobro', 'epayco_woocommerce_sub'),



                        'default' => '',



                        'placeholder' => ''



                    ),







                    'epayco_endorder_state_sub' => array(



                        'title' => __('Estado Final del Pedido', 'epayco_woocommerce_sub'),



                        'type' => 'select',



                        'description' => __('Seleccione el estado del pedido que se aplicará a la hora de aceptar y confirmar el pago de la orden', 'epayco_woocommerce_sub'),



                        'options' => array('epayco-processing'=>"ePayco Procesando Pago","epayco-completed"=>"ePayco Pago Completado"),



                    ),







                    'epayco_url_response_sub' => array(



                        'title' => __('Página de Respuesta', 'epayco_woocommerce_sub'),



                        'type' => 'select',



                        'description' => __('Url de la tienda donde se redirecciona al usuario luego de pagar el pedido', 'epayco_woocommerce_sub'),



                        'options'       => $this->get_pages(__('Seleccionar pagina', 'payco-woocommerce')),



                    ),







                    'epayco_url_confirmation_sub' => array(



                        'title' => __('Página de Confirmación', 'epayco_woocommerce_sub'),



                        'type' => 'select',



                        'description' => __('Url de la tienda donde ePayco confirma el pago', 'epayco_woocommerce_sub'),



                        'options'       => $this->get_pages(__('Seleccionar pagina', 'payco-woocommerce')),



                    ),







                    'epayco_reduce_stock_pending_sub' => array(



                        'title' => __('Reducir el stock en transacciones pendientes', 'epayco_woocommerce_sub'),



                        'type' => 'checkbox',



                        'label' => __('Habilitar', 'epayco_woocommerce_sub'),



                        'description' => __('Habilite para reducir el stock en transacciones pendientes', 'epayco_woocommerce_sub'),



                        'default' => 'yes',



                    ),







                    'epayco_lang_sub' => array(



                        'title' => __('Idioma del Checkout', 'epayco_woocommerce_sub'),



                        'type' => 'select',



                        'description' => __('Seleccione el idioma del checkout', 'epayco_woocommerce_sub'),



                        'options' => array('es'=>"Español","en"=>"Inglés"),



                    ),







                );



            }







            /**



             * @param $order_id



             * @return array



             */



            public function process_payment($order_id)



            {



                



                $order = new WC_Order($order_id);







                $order->reduce_order_stock();



                



                if (version_compare( WOOCOMMERCE_VERSION, '2.1', '>=')) {



                    return array(



                        'result'    => 'success',



                        'redirect'  => add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))



                    );



                } else {



                    return array(



                        'result'    => 'success',



                        'redirect'  => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay' ))))



                    );



                }







            }







            function get_pages($title = false, $indent = true) {







                $wp_pages = get_pages('sort_column=menu_order');



                $page_list = array();



                if ($title) $page_list[] = $title;



                foreach ($wp_pages as $page) {



                    $prefix = '';


                    if ($indent) {



                        $has_parent = $page->post_parent;



                        while($has_parent) {



                            $prefix .=  ' - ';



                            $next_page = get_page($has_parent);



                            $has_parent = $next_page->post_parent;



                        }



                    }

                    $page_list[$page->ID] = $prefix . $page->post_title;



                }



                return $page_list;



            }



      











            /**



             * @param $order_id



             */



            public function receipt_page($order_id)



            {



                global $woocommerce;



                $order = new WC_Order($order_id);

$arrayName = array();

                              global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $current_shipping_cost = WC()->cart->get_cart_shipping_total();

        foreach($items as $item => $value) { 
              $_product =  wc_get_product( $value['data']->get_id());
               $price = get_post_meta($value['product_id'] , '_price', true);
                $aomunt1=$price*$value['quantity'];

               foreach($_product->attributes as $item => $values) { 
     
$data_susciption = $item;
   }
     
            $other = array( "name"=> $_product->name, "id"=>$_product->id, "interval"=> $data_susciption, 'price'=>$price , 'cantidad'=>$value['quantity'],'amount' =>$aomunt1);
 
            array_push($arrayName, $other );

          
        } 
        //WC()->cart->get_cart()
                //var_dump($data_susciption);
                 // die();
//echo "$ ".number_format($order->get_total(), 2);
//print_r($arrayName);


$datadasd=json_encode($arrayName);



$Amount=number_format($order->get_total(), 2);



                $descripcionParts = array();



                foreach ($order->get_items() as $product) {



                    $descripcionParts[] = $this->string_sanitize($product['name']);



                }







                $descripcion = implode(' - ', $descripcionParts);



                $currency = get_woocommerce_currency();



                $testMode = $this->epayco_testmode_sub == "yes" ? "true" : "false";



                $basedCountry = WC()->countries->get_base_country();



                $external=$this->epayco_type_checkout_sub;



                $redirect_url =get_site_url() . "/";

             $redirect_url2 =get_site_url() . "/finalizar-compra/order-received";
            //$redirect_url2 =get_site_url() . "/";
                $confirm_url=get_site_url() . "/";

                $redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );



                $redirect_url = add_query_arg( 'order_id', $order_id, $redirect_url );

  

                $confirm_url = add_query_arg( 'wc-api', get_class( $this ), $confirm_url );



                $confirm_url = add_query_arg( 'order_id', $order_id, $confirm_url );



                $confirm_url = $redirect_url.'&confirmation=1';


                $name_billing=$order->get_billing_first_name().' '.$order->get_billing_last_name();



                $address_billing=$order->get_billing_address_1();



                $phone_billing=@$order->billing_phone;



                $email_billing=@$order->billing_email;



                $order = new WC_Order($order_id);

                  $order_data = $order->get_data();

                 $order_billing_city = $order_data['billing']['city'];



                $tax=$order->get_total_tax();


$tax=number_format($tax, 2);

                if((int)$tax>0){

                $base_tax2=$order->get_total()-$tax;


 $base_tax=number_format($base_tax2, 2);
 
                }else{


 $base_tax=number_format(0, 2);
 $tax=number_format(0, 2);
                }

                if (!EpaycoOrders::ifExist($order_id)) {



                    $this->restore_order_stock($order_id);



                    EpaycoOrders::create($order_id,1);



                }

                $msgEpaycoCheckout = '<span class="animated-points">Cargando metodos de pago</span>



                           <br><small class="epayco-subtitle"> por favor realizar wl proceso de "Pagar con ePayco</small>';



               $epaycoButtonImage = 'https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/epayco/boton_de_cobro_epayco6.png';

               $ruta0='http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css';

               $rut='https://fonts.googleapis.com/icon?family=Material+Icons';

               $rut2=plugin_dir_url(__FILE__) .'/epayco-theme/assets/css/bootstrap.min.css';

               $ruta1=plugin_dir_url(__FILE__) .'/epayco-theme/assets/css/gsdk-bootstrap-wizard.css';

               $ruta2=plugin_dir_url(__FILE__) .'/payment-card-checkout/css/style.css';

               $ruta3=plugin_dir_url(__FILE__) .'/payment-card-checkout/js/index.js';

               $ruta4=plugin_dir_url(__FILE__) .'/epayco-theme/assets/js/jquery-2.2.4.min.js';

               $ruta5=plugin_dir_url(__FILE__) .'/epayco-theme/assets/js/bootstrap.min.js';

               $ruta6=plugin_dir_url(__FILE__) .'/epayco-theme/assets/js/jquery.bootstrap.wizard.js';

               $ruta7=plugin_dir_url(__FILE__) .'/epayco-theme/assets/js/gsdk-bootstrap-wizard.js';

               $ruta8=plugin_dir_url(__FILE__) .'/epayco-theme/assets/js/jquery.validate.min.js';

               $ruta9='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js';

               $ruta10='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js';
 //$ruta81=plugin_dir_url(__FILE__) .'/epayco-theme/assets/js/validate.epayco.js';
  $ruta81='https://s3-us-west-2.amazonaws.com/epayco/v1.0/epayco.min.js';
               $ruta11=plugin_dir_url(__FILE__) .'/data_token.js';

               $ip=$this->getIP();
//var_dump("expression",$ip);
               $tokenurl=plugin_dir_url(__FILE__) .'token.php';
            

                if ($this->epayco_lang_sub !== "es") {



                    $msgEpaycoCheckout = '<span class="animated-points">Loading payment methods</span>



                               <br><small class="epayco-subtitle"> If they do not load automatically, click on the "Pay with ePayco" button</small>';



                    $epaycoButtonImage = 'https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/btn7.png';

   $informationnav = '
                       <ul style="margin:0px;">

                                <li class="litama"><a href="#about" data-toggle="tab">Plan</a></li>

                                <li class="litama"><a href="#account" data-toggle="tab">information</a></li>

                                <li class="litama"><a href="#address" data-toggle="tab">Pay</a></li>

                            </ul>';
$recurrentlabel= '<label>Select recurrence of payment</label><br> ';
                                                  if ($data_susciption=="month") {
                                                                 $selected='  <option value="month">monthly</option>';
                                                                 $interval=$this->epayco_interval_count_sub;
                                                                    }
                                                                  
                                                                        if ($data_susciption=="year") {
                                                                             $selected='<option value="year">every year</option>';
                                                                             $interval=$this->epayco_interval_count_sub_year;
                                                                        }
                                                                        if ($data_susciption=="week") {
                                                                            $selected= '<option value="week">weekly</option>';
                                                                            $interval=$this->epayco_interval_count_sub_week;
                                                                        }
                                                                         if ($data_susciption=="day") {
                                                                                if ($this->epayco_interval_count_sub_day==="1") {
                                                                                    
                                                                                  $selected= '<option value="day">every '.$this->epayco_interval_count_sub_day.' day</option>';
                                                                                  $interval=$this->epayco_interval_count_sub_day;
                                                                                }else{
                                                                                    $selected= '<option value="day">every '.$this->epayco_interval_count_sub_day.' days</option>';
                                                                                       $interval=$this->epayco_interval_count_sub_day;
                                                                              
                                                                                }
                                                                         
                                                                             
                                                                        }


$tabla=' <table class="table table-condensed">

                                       <tr>

                                         <td>Description</td>

                                         <td>%s</td>               

                                       </tr>

                                       <tr>

                                          <td>Base tax </td>

                                           <td>%s $</td> 

                                        </tr>

                                        <tr>

                                          <td>Tax </td>

                                          <td>%s $</td> 

                                        </tr>

                                        <tr>

                                          <td>Amount </td>

                                          <td>%s $</td> 

                                        </tr>

                                         <tr>

                                          <td>Currency</td>

                                          <td>%s</td> 

                                        </tr>

                                      </table>         ';
                                      $select2='  <label>Select the type of document</label><br>    

                                             <select name="documento" class="form-control" id="documento" required>

                                                <option value="CC">CITIZENSHIP CARD</option>

                                                <option value="CE">FOREIGNER ID</option>

                                                <option value="PPN">PASSPORT</option>

                                                <option value="SSN">SOCIAL SECURITY NUMBER</option>

                                                <option value="LIC">DRIVING SCIENCE</option>

                                                <option value="NIT">TAX IDENTIFICATION NUMBER</option>

                                                <option value="TI">IDENTITY CARD</option>

                                                <option value="DNI">NATIONAL IDENTIFICATION DOCUMENT</option>

                                             </select>';
              
  $table2='<table class="table table-condensed">
                                     <tr>
                                         <td>Number</td>
                                         <td><input class="inputname" type="text" placeholder="000000000"  id="cedula" name="cedula" /></td>               
                                       </tr>

                                       <tr>
                                         <td>Name</td>
                                         <td>%s</td>               
                                       </tr>

                                        <tr>
                                          <td>Adress</td>
                                           <td><input  type="text"  value="%s" id="Direccionf" /></td>
                                        </tr>

                                        <tr>
                                          <td>Cellphne </td>
                                          <td> <input class="telephone" type="text"   id="telephone" value="%s" /></td>
                                        </tr>

                                        <tr>
                                          <td>Email </td>
                                          <td>%s</td> 
                                        </tr>

                                      </table>         
';


                }else{
                    $informationnav = '
                       <ul style="margin:0px;">

                                <li class="litama"><a href="#about" data-toggle="tab">Plan</a></li>

                                <li class="litama"><a href="#account" data-toggle="tab">información</a></li>

                                <li class="litama"><a href="#address" data-toggle="tab">Pago</a></li>

                            </ul>';

                                                          if ($data_susciption=="month") {
                                                                 $selected='  <option value="month">mensual</option>';
                                                                  $interval=$this->epayco_interval_count_sub;
                                                                    }
                                                                  
                                                                        if ($data_susciption=="year") {
                                                                             $selected='<option value="year">Anual</option>';
                                                                             $interval=$this->epayco_interval_count_sub_year;

                                                                        }
                                                                        if ($data_susciption=="week") {
                                                                             $selected= '<option value="week">semanal</option>';
                                                                              $interval=$this->epayco_interval_count_sub_week;
                                                                        }
                                                                        if ($data_susciption=="day") {
                                                                                if ($this->epayco_interval_count_sub_day==="1") {
                                                                                    
                                                                                  $selected= '<option value="day">cada '.$this->epayco_interval_count_sub_day.' dia</option>';
                                                                                  $interval=$this->epayco_interval_count_sub_day;
                                                                                }else{
                                                                                    $selected= '<option value="day">cada '.$this->epayco_interval_count_sub_day.' dias</option>';
                                                                                       $interval=$this->epayco_interval_count_sub_day;
                                                                              
                                                                                }
                                                                         
                                                                             
                                                                        }
                                                                    
                                               
                                                 
                                                
$tabla=' <table class="table table-condensed">

                                       <tr>

                                         <td>Descripción</td>

                                         <td>%s</td>               

                                       </tr>

                                       <tr>

                                          <td>Base iva</td>

                                           <td>%s $</td> 

                                        </tr>

                                        <tr>

                                          <td>iva </td>

                                          <td>%s $</td> 

                                        </tr>

                                        <tr>

                                          <td>Precio </td>

                                          <td>%s $</td> 

                                        </tr>

                                         <tr>

                                          <td>Moneda</td>

                                          <td>%s</td> 

                                        </tr>

                                      </table>         ';
                                      $select2=' <label>Seleccione el tipo de documento</label><br>    

                                             <select name="documento" class="form-control" id="documento" required>

                                                <option value="CC">CÉDULA DE CIUDADANÍA</option>

                                                <option value="CE">CÉDULA DE EXTRANJERÍA</option>

                                                <option value="PPN">PASAPORTE</option>

                                                <option value="SSN">NÚMERO DE SEGURIDAD SOCIAL</option>

                                                <option value="LIC">LICENCIA DE CONDUCCIÓN</option>

                                                <option value="NIT">NÚMERO DE INDENTIFICACIÓN TRIBUTARIA</option>

                                                <option value="TI">TARJETA DE IDENTIDAD</option>

                                                <option value="DNI">DOCUMENTO NACIONAL DE IDENTIFICACIÓN</option>

                                             </select>';
                                                                            $table2='<table class="table table-condensed">
                                     <tr>
                                         <td>Numero</td>
                                         <td><input class="inputname" type="text" placeholder="000000000"  id="cedula" name="cedula"  required /></td>               
                                       </tr>

                                       <tr>
                                         <td>Nombre</td>
                                         <td>%s</td>               
                                       </tr>

                                        <tr>
                                          <td>Direccion</td>
                                           <td><input  type="text"  value="%s" id="Direccionf" /></td>
                                        </tr>

                                        <tr>
                                          <td>Celular </td>
                                          <td> <input class="telephone" type="text"   id="telephone" value="%s" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required /></td>
                                        </tr>

                                        <tr>
                                          <td>Email </td>
                                          <td>%s</td> 
                                        </tr>

                                      </table>         
';
                                            

                }
           
 


                echo('

                    <style>

                      .litama{

                        width: 32.3333% !important;

                      }

                    </style>



                    ');



                echo sprintf(' 
                    <!DOCTYPE html>

<html lang="en">



<head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">

 <link href="%s" rel="stylesheet">

 <link href="%s"rel="stylesheet">

  <link href="%s"rel="stylesheet">



    <meta name="viewport" content="width=device-width" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <link href="%s" rel="stylesheet">

     <link href="%s"

      rel="stylesheet">

</head>



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



<body>             

 <div class="wizard-container">         

    <div class="card wizard-card" data-color="orange" id="wizardProfile">



         <div class="wizard-header">               

              <nav class="navbar navbar-default" role="navigation">

                  <div class="container">

                      <div class="col-sm-12">

                          <div class="navbar-header">

                             <img class="img-logo" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png" alt="">   

                           </div>                             

                        </div>

                     </div>

                    </nav>

                </div>



                        <div class="wizard-navigation">'.$informationnav.'</div>





                 <div class="tab-content">



                   <div class="tab-pane" id="about">

                        <div class="row">

                           <div class="col-sm-10 col-sm-offset-1">

                                <div class="form-group">

                                   '.$recurrentlabel.'

                                             <select name="selec_plan" class="form-control" id="selec_plan" onchange="selec_plans();" required>'.$selected.

'                                               

                                             </select>

                                   '.$tabla.'        

                                     </div>

                                </div>

                             </div>

                         </div> 



       

                      <div id="mostrardatos3" hidden="true" >%s</div>

                      <div id="test" hidden="true" >%s</div>

                       <div id="lang" hidden="true">%s</div>

                       <div id="p_c" hidden="true">%s</div>

                     <div class="tab-pane" id="account">

                      <div class="row">

                           <div class="col-sm-10 col-sm-offset-1">

                                <div class="form-group">

                               '.$select2.'

                                        <div class="col2">

                                        

                                        </div>

                                   '.$table2.'

                                     </div>

                                </div>

                             </div>

                     </div>

  




<script src="'.$ruta81.'"></script>
                    

                    <div class="tab-pane" id="address">

                        <div class="row">

                                    <div class="col-sm-12"> 

                                     <div id="mostrardatos21"></div>      

                                       <center> <div id="mostrardatos2">

                                   <div class="row">

                                   <div class="col-sm-10 col-sm-offset-1">

                                       <form  method="POST" id="customer-form" autocomplete="off" action="">

                                        <div class="container">

                                         <div class="col1">

                                            <div class="card">

                                              <div class="front">

                                                <div class="type">

                                                  <img class="bankid"/>

                                                </div>

                                              </div>

                                            </div>

                                          </div>

                                          <div class="col2">

                                             

                                            <input class="inputname" type="text" placeholder="Joe Doe" data-epayco="card[name]" id="card[name]" name="card[name]" value="%s" hidden="true"/><br>

                                             

                                            <input class="email" type="text" placeholder="JoeDoe@email.com" data-epayco="card[email]" id="card[email]" value="%s" hidden="true" /><br><br>

                                            

                                            <input class="telephone" type="text"  hidden="true"  value="%s" /><br><br>

                                            <label><i class="fas fa-credit-card"></i></label>

                                            <input class="number" type="text" maxlength="19" minlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" data-epayco="card[number]" id="card[number]" 
required
                                            /><br>

                                            <label><i class="fas fa-calendar-alt"></i></label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<label><i class="fas fa-calendar-alt"></i></label><br>

                                              <select name="selec_mont_" class="expire" id="mm"  data-epayco="card[exp_month]" >
                                                  <option value="01">1</option>
                                                  <option value="02">2</option>
                                                  <option value="03">3</option>
                                                  <option value="04">4</option>
                                                  <option value="05">5</option>
                                                  <option value="06">6</option>
                                                  <option value="07">7</option>
                                                  <option value="08">8</option>
                                                  <option value="09">9</option>
                                                  <option value="10">10</option>
                                                  <option value="11">11</option>
                                                  <option value="12">12</option>

                                            </select>

                                            <select name="selec_year_" class="expire" id="y"  data-epayco="card[exp_year]" >

                                                 <option value="2019">2019</option>
                                                 <option value="2020">2020</option>
                                                 <option value="2021">2021</option>
                                                 <option value="2022">2022</option>
                                                 <option value="2023">2023</option>
                                                 <option value="2024">2024</option>
                                                 <option value="2025">2025</option>
                                                 <option value="2026">2026</option>
                                                 <option value="2027">2027</option>
                                                 <option value="2028">2028</option>
                                                 <option value="2029">2029</option>
                                                 <option value="2030">2030</option>

                                            </select>

                                            <br>

                                           <label></label><br>
                                           <i class="fas fa-unlock-alt"></i>
                                            <input class="cvc"  type="password" placeholder="CVC" maxlength="5" minlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  data-epayco="card[cvc]" required />

                                            <button type="submit" class="buy" id="enviar" ><i class="material-icons"  >lock</i> Pay </button>

                                          </div>

                                        </div>

                                        </form>

                                         <script src="'.$ruta11.'"></script>

                                     

                                        <div class="form-group">

                                      <span class="icon-credit-card"></span>

                                      <div class="card-errors"></div>

                                 <div id="mostrardatos4" hidden="true" ></div> 

                                 <div id="mostrardatos"></div>

    

                                         </div>                                                

                                        </div>

                                    </div>

                                   </div></center>

                                    </div>

                              

                            

                                    <div class="col-sm-10 col-sm-offset-1">

                                         <div class="form-group">



                                          </div>

                                    </div>

                                </div>

                    </div>









                    </div>







                          <div class="wizard-footer height-wizard">

                            <div class="pull-right">

                                <input type="button" class="btn btn-next btn-fill btn-warning btn-wd btn-sm" name="next" value="Next" />

                                 <form action="%s/%s/"> 

                                <input type="submit" class="btn btn-finish btn-fill btn-warning btn-wd btn-sm" name="%s"   value="Finish" onClick="$this->vaciar_carrito_al_salir()"/>

                           

                                </form>

                               </div>

                               <div class="pull-left">

                                <input type="button" class="btn btn-previous btn-fill btn-default btn-wd btn-sm" name="previous" value="Previous" />

                               </div>

                            <div class="clearfix"></div>

                        </div>



    </div>

  </div>





                      <div id="extra1" hidden="true" >%s</div>

                       <div id="response" hidden="true">%s</div>

                       <div id="confirmacion" hidden="true">%s</div>

                        <div id="city" hidden="true">%s</div>

                        <div id="ip" hidden="true">%s</div>

                        <div id="descripcion_p" hidden="true">%s</div>

                        <div id="currency" hidden="true">%s</div>

                        <div id="amount" hidden="true">%s</div>

                        <div id="addressd" hidden="true">%s</div>

                        <div id="tokenurl" hidden="true">%s</div>

                        <div id="trial" hidden="true">%s</div>

                        <div id="interval_count" hidden="true">%s</div>
                        <div id="myarray" hidden="true">%s</div>
                         <div id="shipping" hidden="true">%s</div>
                         <div id="interval_mounth" hidden="true">%s</div>
                          <div id="interval_day" hidden="true">%s</div>
                           <div id="interval_week" hidden="true">%s</div>
                           <div id="interval_year" hidden="true">%s</div>


                      

               

                          </body>



 <script src="'.$ruta3.'"></script>

  <script src="'.$ruta4.'"></script>

  <script src="'.$ruta5.'"></script>

  <script src="'.$ruta6.'"></script>

  <script src="'.$ruta7.'"></script>

<script src="'.$ruta8.'"></script>




   <script src="'.$ruta9.'"></script>

    <script src="'.$ruta10.'"></script>



</html>


                ',$ruta1,$ruta2,$rut2,$ruta0,$rut,$descripcion,$base_tax,$tax,$Amount,$currency,$this->epayco_publickey_sub,$testMode,$this->epayco_lang_sub,$this->epayco_secretkey_sub,$name_billing,$address_billing,$phone_billing, $email_billing,$name_billing,$email_billing,$phone_billing, $redirect_url2,$order->get_id(),$order->get_id(), $order->get_id(),$redirect_url,$confirm_url,$order_billing_city,$ip,$descripcion,$currency,$order->get_total(),$address_billing,$tokenurl, $this->epayco_type_checkout_sub, $interval,$datadasd,$current_shipping_cost,$this->epayco_interval_count_sub, $this->epayco_interval_count_sub_day, $this->epayco_interval_count_sub_week, $this->epayco_interval_count_sub_year);



                   



                    $messageload = __('Espere por favor..Cargando checkout.','payco-woocommerce');



                    $js = "if(jQuery('button.epayco-button-render').length)    



                {



                jQuery('button.epayco-button-render').css('margin','auto');



                jQuery('button.epayco-button-render').css('display','block');



                }



                ";

                if (version_compare(WOOCOMMERCE_VERSION, '2.1', '>=')){
                    wc_enqueue_js($js);

                }else{
                    $woocommerce->add_inline_js($js);
                }

if ($ref_payco) {
 var_dump("vaciar_carrito_al_salir");

}else{
   // var_dump("no vaciar_carrito_al_salir");
}

            }




 public function vaciar_carrito_al_salir() {
    var_dump("vaciar_carrito_al_salir");
   // die();
  global $woocommerce;
try{
    if ( is_front_page() && isset( $_GET['empty-cart'] ) ) { 
        $woocommerce->cart->empty_cart(); 
    }
     if( function_exists('WC') ){

        WC()->cart->empty_cart();

    }

   }catch (Exception $e) {
  // exception is raised and it'll be handled here
  // $e->getMessage() contains the error message
    echo "string", $e;
}
}



            public function datareturnepayco_ajax_sub()



            {



                die();



            }






            public function block($message)



            {



                return 'jQuery("body").block({



                        message: "' . esc_js($message) . '",



                        baseZ: 99999,



                        overlayCSS:



                        {



                            background: "#000",



                            opacity: "0.6",



                        },



                        css: {



                            padding:        "20px",



                            zindex:         "9999999",



                            textAlign:      "center",



                            color:          "#555",



                            border:         "1px solid #aaa",



                            backgroundColor:"#fff",



                            cursor:         "wait",



                            lineHeight:     "24px",



                        }



                    });';



            }



           

               public function getIP()

    {

        if (getenv('HTTP_CLIENT_IP'))

            $ipaddress = getenv('HTTP_CLIENT_IP');

        else if(getenv('HTTP_X_FORWARDED_FOR'))

            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');

        else if(getenv('HTTP_X_FORWARDED'))

            $ipaddress = getenv('HTTP_X_FORWARDED');

        else if(getenv('HTTP_FORWARDED_FOR'))

            $ipaddress = getenv('HTTP_FORWARDED_FOR');

        else if(getenv('HTTP_FORWARDED'))

            $ipaddress = getenv('HTTP_FORWARDED');

        else if(getenv('REMOTE_ADDR'))

            $ipaddress = getenv('REMOTE_ADDR');

        else

            $ipaddress = '127.0.0.1';



        return $ipaddress;

    }



            public function agafa_dades($url) {



                if (function_exists('curl_init')) {



                    $ch = curl_init();



                    $timeout = 5;



                    $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';



                    curl_setopt($ch, CURLOPT_URL, $url);



                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);



                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);



                    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);



                    curl_setopt($ch, CURLOPT_HEADER, 0);



                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);



                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);



                    curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);



                    curl_setopt($ch,CURLOPT_MAXREDIRS,10);



                    $data = curl_exec($ch);



                    curl_close($ch);



                    return $data;



                }else{



                    $data =  @file_get_contents($url);



                    return $data;



                }



            }



            public function goter(){



                $context = stream_context_create(array(



                    'http' => array(



                        'method' => 'POST',



                        'header' => 'Content-Type: application/x-www-form-urlencoded',



                        'protocol_version' => 1.1,



                        'timeout' => 10,



                        'ignore_errors' => true



                    )



                ));



            }







            function check_ePayco_response_sub(){



                @ob_clean();



                if ( ! empty( $_REQUEST ) ) {



                    header( 'HTTP/1.1 200 OK' );



                    do_action( "ePayco_init_sub", $_REQUEST );



                } else {



                    wp_die( __("ePayco Request Failure", 'epayco-woocommerce') );



                }



            }







            /**



             * @param $validationData



             */



            function ePayco_successful_request_sub($validationData)



            {



               

            

                    global $woocommerce;



                    $order_id="";



                    $ref_payco="";



                    $signature="";



var_dump("22222");



                    if(isset($_REQUEST['x_signature'])){



                        $explode=explode('?',$_GET['order_id']);



                        $order_id=$explode[0];



                        //$order_id=$_REQUEST['order_id'];



                        $ref_payco=$_REQUEST['x_ref_payco'];

           var_dump("expression1",$ref_payco);

//

                    }else{



                         //Viene por el onpage



                        $explode=explode('?',$_GET['order_id']);

                        $explode2=explode('?',$_GET['ref_payco']);

                        $order_id=$explode[0];



                       



                            $strref_payco=explode("=",$explode[1]);



                            //$ref_payco=$_REQUEST['ref_payco'];

                            $ref_payco=$strref_payco[1];

                          

                     if ( !$ref_payco) {

                        $ref_payco=$explode2[0];

                        var_dump("expression4",$ref_payco);

                     }

                  



           var_dump("expression2",$ref_payco,$explode,$explode2,$strref_payco[1]);

die();



                            //Consultamos los datos



                            $message = __('Esperando respuesta por parte del servidor.','payco-woocommerce');



                            $js = $this->block($message);



                            $url = 'https://secure.epayco.co/validation/v1/reference/'.$ref_payco;



                            $responseData = $this->agafa_dades($url,false,$this->goter());



                            $jsonData = @json_decode($responseData, true);



                            $validationData = $jsonData['data'];



                           $ref_payco = $validationData['x_ref_payco'];

                }

                                              var_dump("validar if ref_payco es null");



                                

                                if (  $ref_payco == "NULL") {



                                    var_dump("es null");

                                    die();

                                     $order = new WC_Order($order_id);

                                 





                                   $message = 'Pago rechazado';



                                $messageClass = 'woocommerce-error';



                                $order->update_status('epayco-failed');



                                $order->add_order_note('Pago fallido');



                                 

                        if ($this->get_option('epayco_url_response_sub' ) == 0) {

                            $redirect_url = $order->get_checkout_order_received_url();

                        }else{

                            $woocommerce->cart->empty_cart();

                            $redirect_url = get_permalink($this->get_option('epayco_url_response_sub'));

                        }



                               $arguments=array();

                    foreach ($validationData as $key => $value) {

                        $arguments[$key]=$value;

                    }

                    unset($arguments["wc-api"]);

                    $arguments['msg']=urlencode($message);

                    $arguments['type']=$messageClass;

                    $redirect_url = add_query_arg($arguments , $redirect_url );

                    wp_redirect($redirect_url);

                    die();

                                }

   

                    



                    //Validamos la firma

                                var_dump("validar firma",$ref_payco);

//

                    if ($order_id!="" && $ref_payco!="") {



                        $order = new WC_Order($order_id);



                        $signature = hash('sha256',



                            $this->epayco_customerid_sub.'^'



                            .$this->epayco_p_key_sub.'^'



                            .$validationData['x_ref_payco'].'^'



                            .$validationData['x_transaction_id'].'^'



                            .$validationData['x_amount'].'^'



                            .$validationData['x_currency_code']



                        );



                    }



                    



                    $message = '';



                    $messageClass = '';



                    $current_state = $order->get_status();



var_dump($signature,$validationData['x_signature']);

//die();

                    if($signature == $validationData['x_signature']){



                        



                        switch ((int)$validationData['x_cod_response']) {



                            case 1:{

var_dump("case1",$validationData['x_ref_payco']);

                                //Busca si ya se descontó el stock



                                if (!EpaycoOrders::ifStockDiscount($order_id)) {



                                    



                                    //se descuenta el stock



                                    if (EpaycoOrders::updateStockDiscount($order_id,1)) {



                                        $this->restore_order_stock($order_id,'decrease');



                                    }



                                }



                                $message = 'Pago exitoso';



                                $messageClass = 'woocommerce-message';



                                $order->payment_complete($validationData['x_ref_payco']);



                                $order->update_status($this->epayco_endorder_state_sub);



                                $order->add_order_note('Pago exitoso');



                                



                            }break;



                            case 2: {

var_dump("case2");

                                $message = 'Pago rechazado';



                                $messageClass = 'woocommerce-error';



                                $order->update_status('epayco-failed');



                                $order->add_order_note('Pago fallido');



                                //$this->restore_order_stock($order->id);



                            }break;



                            case 3:{

var_dump("case3");

                                //Busca si ya se restauro el stock y si se configuro reducir el stock en transacciones pendientes  



                                if (!EpaycoOrders::ifStockDiscount($order_id) && $this->get_option('epayco_reduce_stock_pending_sub') == 'yes') {







                                    //reducir el stock



                                    if (EpaycoOrders::updateStockDiscount($order_id,1)) {



                                        $this->restore_order_stock($order_id,'decrease');



                                    }



                                }







                                $message = 'Pago pendiente de aprobación';



                                $messageClass = 'woocommerce-info';



                                $order->update_status('epayco-on-hold');



                                $order->add_order_note('Pago pendiente');



                            }break;



                            case 4:{

var_dump("case4");

                                $message = 'Pago fallido';



                                $messageClass = 'woocommerce-error';



                                $order->update_status('epayco-failed');



                                $order->add_order_note('Pago fallido');



                                //$this->restore_order_stock($order->id);



                            }break;



                            default:{

var_dump("default");

                                $message = 'Pago '.$_REQUEST['x_transaction_state'];



                                $messageClass = 'woocommerce-error';



                                $order->update_status('epayco-failed');



                                $order->add_order_note($message);



                               // $this->restore_order_stock($order->id);



                            }break;







                        }



                    //validar si la transaccion esta pendiente y pasa a rechazada y ya habia descontado el stock

var_dump("x_cod_response1",$current_state,$order_id,$validationData['x_cod_response']);

                    if($current_state == 'epayco-on-hold' || $current_state == 'on-hold'&& ((int)$validationData['x_cod_response'] == 2 || (int)$validationData['x_cod_response'] == 4) && EpaycoOrders::ifStockDiscount($order_id)){




var_dump("x_cod_response2",$current_state);


                        //si no se restauro el stock restaurarlo inmediatamente



                         $this->restore_order_stock($order_id);



                    };



                    }else {



                        $message = 'Firma no valida';



                        $messageClass = 'error';



                        $order->update_status('failed');



                        $order->add_order_note('Failed');



                        //$this->restore_order_stock($order_id);



                    }







                    



                    if (isset($_REQUEST['confirmation'])) {



                        $redirect_url = get_permalink($this->get_option('epayco_url_confirmation_sub'));







                        if ($this->get_option('epayco_url_confirmation_sub' ) == 0) {



                            echo "ok";



                            die();



                        }



                    }else{







                        if ($this->get_option('epayco_url_response_sub' ) == 0) {



                            $redirect_url = $order->get_checkout_order_received_url();



                        }else{



                            $woocommerce->cart->empty_cart();



                            $redirect_url = get_permalink($this->get_option('epayco_url_response_sub'));



                        }



                    }











                    $arguments=array();







                    foreach ($validationData as $key => $value) {



                        $arguments[$key]=$value;



                    }



                    unset($arguments["wc-api"]);







                    $arguments['msg']=urlencode($message);



                    $arguments['type']=$messageClass;



                    $redirect_url = add_query_arg($arguments , $redirect_url );







                    wp_redirect($redirect_url);



                    die();



            }







            /**



             * @param $order_id



             */



            public function restore_order_stock($order_id,$operation = 'increase')



            {



                //$order = new WC_Order($order_id);



                $order =  $order = wc_get_order($order_id);



                if (!get_option('woocommerce_manage_stock') == 'yes' && !sizeof($order->get_items()) > 0) {



                    return;



                }







                foreach ($order->get_items() as $item) {



                    // Get an instance of corresponding the WC_Product object



                    $product = $item->get_product();



                    $qty = $item->get_quantity(); // Get the item quantity



                    wc_update_product_stock($product, $qty, $operation);



                }



               /* foreach ($order->get_items() as $item) {



                    if ($item['product_id'] > 0) {



                        $_product = $order->get_product_from_item($item);



                        if ($_product && $_product->exists() && $_product->managing_stock()) {



                            $old_stock = $_product->stock;



                            $qty = apply_filters('woocommerce_order_item_quantity', $item['qty'], $this, $item);



                            $new_quantity = $_product->increase_stock($qty);



                            do_action('woocommerce_auto_stock_restored', $_product, $item);



                            $order->add_order_note(sprintf(__('Item #%s stock incremented from %s to %s.', 'woocommerce'), $item['product_id'], $old_stock, $new_quantity));



                            $order->send_stock_notifications($_product, $new_quantity, $item['qty']);



                        }



















                    }



                }*/



            }







            public function string_sanitize($string, $force_lowercase = true, $anal = false) {







                $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",



                               "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",



                               "â€”", "â€“", ",", "<", ".", ">", "/", "?");



                $clean = trim(str_replace($strip, "", strip_tags($string)));



                $clean = preg_replace('/\s+/', "_", $clean);



                $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;



                return $clean;



            }







            public function getTaxesOrder($order){



                



                $taxes=($order->get_taxes());



                $tax=0;



                foreach($taxes as $tax){



                    $itemtax=$tax['item_meta']['tax_amount'][0];



                    //var_dump($itemtax);



                }



                return $itemtax;



            }







        }



        



        /**



         * @param $methods



         * @return array



         */



        function woocommerce_epayco_add_gateway_sub($methods)



        {



            $methods[] = 'WC_ePayco_sub';



            return $methods;



        }



        add_filter('woocommerce_payment_gateways', 'woocommerce_epayco_add_gateway_sub');







        function epayco_woocommerce_sub_addon_settings_link_sub( $links ) {



            array_push( $links, '<a href="admin.php?page=wc-settings&tab=checkout&section=epayco_sub">' . __( 'Configuración' ) . '</a>' );



            return $links;



        }



        add_filter( "plugin_action_links_".plugin_basename( __FILE__ ),'epayco_woocommerce_sub_addon_settings_link_sub' );



    }







    //Actualización de versión



    global $epayco_db_version_sub;



    $epayco_db_version_sub = '1.0';







    //Verificar si la version de la base de datos esta actualizada 



    function epayco_update_db_check_sub()



    {



        global $epayco_db_version_sub;



        $installed_ver = get_option('epayco_db_version_sub');







        if ($installed_ver == null || $installed_ver != $epayco_db_version_sub) {



            EpaycoOrders::setup();



            update_option('epayco_db_version_sub', $epayco_db_version_sub);



        }







    }







    add_action('plugins_loaded', 'epayco_update_db_check_sub');







    function register_epayco_order_status_sud() {



        register_post_status( 'wc-epayco-failed', array(



            'label'                     => 'ePayco Pago Fallido',



            'public'                    => true,



            'show_in_admin_status_list' => true,



            'show_in_admin_all_list'    => true,



            'exclude_from_search'       => false,



            'label_count'               => _n_noop( 'ePayco Pago Fallido <span class="count">(%s)</span>', 'ePayco Pago Fallido <span class="count">(%s)</span>' )



        ));



        register_post_status( 'wc-epayco-canceled', array(



            'label'                     => 'ePayco Pago Cancelado',



            'public'                    => true,



            'show_in_admin_status_list' => true,



            'show_in_admin_all_list'    => true,



            'exclude_from_search'       => false,



            'label_count'               => _n_noop( 'ePayco Pago Cancelado <span class="count">(%s)</span>', 'ePayco Pago Cancelado <span class="count">(%s)</span>' )



        ));



        register_post_status( 'wc-epayco-on-hold', array(



            'label'                     => 'ePayco Pago Pendiente',



            'public'                    => true,



            'show_in_admin_status_list' => true,



            'show_in_admin_all_list'    => true,



            'exclude_from_search'       => false,



            'label_count'               => _n_noop( 'ePayco Pago Pendiente <span class="count">(%s)</span>', 'ePayco Pago Pendiente <span class="count">(%s)</span>' )



        ));



        register_post_status( 'wc-epayco-processing', array(



            'label'                     => 'ePayco Procesando Pago',



            'public'                    => true,



            'show_in_admin_status_list' => true,



            'show_in_admin_all_list'    => true,



            'exclude_from_search'       => false,



            'label_count'               => _n_noop( 'ePayco Procesando Pago <span class="count">(%s)</span>', 'ePayco Procesando Pago <span class="count">(%s)</span>' )



        ));



        register_post_status( 'wc-epayco-completed', array(



            'label'                     => 'ePayco Pago Completado',



            'public'                    => true,



            'show_in_admin_status_list' => true,



            'show_in_admin_all_list'    => true,



            'exclude_from_search'       => false,



            'label_count'               => _n_noop( 'ePayco Pago Completado <span class="count">(%s)</span>', 'ePayco Pago Completado <span class="count">(%s)</span>' )



        ));



    }



    add_action( 'plugins_loaded', 'register_epayco_order_status_sud' );







    function add_epayco_to_order_statuses_sub( $order_statuses ) {







        $new_order_statuses = array();







        foreach ( $order_statuses as $key => $status ) {







            $new_order_statuses[ $key ] = $status;



            if ( 'wc-cancelled' === $key ) {



                $new_order_statuses['wc-epayco-cancelled'] = 'ePayco Pago Cancelado';



            }



            if ( 'wc-failed' === $key ) {



                $new_order_statuses['wc-epayco-failed'] = 'ePayco Pago Fallido';



            }



            if ( 'wc-on-hold' === $key ) {



                $new_order_statuses['wc-epayco-on-hold'] = 'ePayco Pago Pendiente';



            }



            if ( 'wc-processing' === $key ) {



                $new_order_statuses['wc-epayco-processing'] = 'ePayco Procesando Pago';



            }



            if ( 'wc-completed' === $key ) {



                $new_order_statuses['wc-epayco-completed'] = 'ePayco Pago Completado';



            }



        }







        return $new_order_statuses;



    }



    add_filter( 'wc_order_statuses', 'add_epayco_to_order_statuses_sub' );







    add_action('admin_head', 'styling_admin_order_list_sub' );



    function styling_admin_order_list_sub() {



        global $pagenow, $post;







        if( $pagenow != 'edit.php') return; // Exit



        if( get_post_type($post->ID) != 'shop_order' ) return; // Exit







        // HERE we set your custom status



        $order_status_failed = 'epayco-failed';



        $order_status_on_hold = 'epayco-on-hold';



        $order_status_processing = 'epayco-processing';



        $order_status_completed = 'epayco-completed';



        ?>



        <style>



            .order-status.status-<?php echo sanitize_title( $order_status_failed); ?> {



                background: #eba3a3;



                color: #761919;



            }



            .order-status.status-<?php echo sanitize_title( $order_status_on_hold); ?> {



                background: #f8dda7;



                color: #94660c;



            }



            .order-status.status-<?php echo sanitize_title( $order_status_processing ); ?> {



                background: #c8d7e1;



                color: #2e4453;



            }



            .order-status.status-<?php echo sanitize_title( $order_status_completed ); ?> {



                background: #d7f8a7;



                color: #0c942b;



            }



        </style>



        <?php



    }



}