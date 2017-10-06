<?php
if (!defined('ABSPATH') ) exit;
function Load_bitpay_Gateway() {
	
	if ( class_exists( 'WC_Payment_Gateway' ) && !class_exists( 'WC_Gateway_bitpay' ) && !function_exists('Woocommerce_Add_bitpay_Gateway') ) {
		
		add_filter('woocommerce_payment_gateways', 'Woocommerce_Add_bitpay_Gateway' );
		function Woocommerce_Add_bitpay_Gateway($methods) {
			$methods[] = 'WC_Gateway_bitpay';
			return $methods;
		}
		
		class WC_Gateway_bitpay extends WC_Payment_Gateway {
			
			public function __construct(){
				
				//by Woocommerce.ir
				$this->author = 'Woocommerce.ir';
				//by Woocommerce.ir
				
				
				$this->id = 'bitpay';
				$this->method_title = __('بیت پی', 'woocommerce');
				$this->method_description = __( 'تنظیمات درگاه پرداخت بیت پی برای افزونه فروشگاه ساز ووکامرس', 'woocommerce');
				$this->icon = apply_filters('WC_bitpay_logo', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets/images/logo.png');
				$this->has_fields = false;
				
				$this->init_form_fields();
				$this->init_settings();
				
				$this->title = $this->settings['title'];
				$this->description = $this->settings['description'];
				
				$this->api = $this->settings['api'];	
				$this->sandbox = $this->settings['sandbox'];
				
				$this->success_massage = $this->settings['success_massage'];
				$this->failed_massage = $this->settings['failed_massage'];
				$this->cancelled_massage = $this->settings['cancelled_massage'];
				
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) )
					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				else
					add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );	
				add_action('woocommerce_receipt_'.$this->id.'', array($this, 'Send_to_bitpay_Gateway_By_HANNANStd'));
				add_action('woocommerce_api_'.strtolower(get_class($this)).'', array($this, 'Return_from_bitpay_Gateway_By_HANNANStd') );
				
			}

		
			public function admin_options(){
				$action = $this->author;
				do_action( 'WC_Gateway_Payment_Actions', $action );			
				parent::admin_options();
			}
		
			public function init_form_fields(){
				$this->form_fields = apply_filters('WC_bitpay_Config', 
					array(
					
						'base_confing' => array(
							'title'       => __( 'تنظیمات پایه ای', 'woocommerce' ),
							'type'        => 'title',
							'description' => '',
						),
						'enabled' => array(
							'title'   => __( 'فعالسازی/غیرفعالسازی', 'woocommerce' ),
							'type'    => 'checkbox',
							'label'   => __( 'فعالسازی درگاه بیت پی', 'woocommerce' ),						
							'description' => __( 'برای فعالسازی درگاه پرداخت بیت پی باید چک باکس را تیک بزنید', 'woocommerce' ),
							'default' => 'yes',
							'desc_tip'    => true,
						),
						'title' => array(
							'title'       => __( 'عنوان درگاه', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( 'عنوان درگاه که در طی خرید به مشتری نمایش داده میشود', 'woocommerce' ),
							'default'     => __( 'بیت پی', 'woocommerce' ),
							'desc_tip'    => true,
						),
						'description' => array(
							'title'       => __( 'توضیحات درگاه', 'woocommerce' ),
							'type'        => 'text',
							'desc_tip'    => true,
							'description' => __( 'توضیحاتی که در طی عملیات پرداخت برای درگاه نمایش داده خواهد شد', 'woocommerce' ),
							'default'     => __( 'پرداخت امن به وسیله کلیه کارت های عضو شتاب از طریق درگاه بیت پی', 'woocommerce' )
						),
						'account_confing' => array(
							'title'       => __( 'تنظیمات حساب بیت پی', 'woocommerce' ),
							'type'        => 'title',
							'description' => '',
						),
						'api' => array(
							'title'       => __( 'Api', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( 'Api درگاه بیت پی', 'woocommerce' ),
							'default'     => '',
							'desc_tip'    => true
						),
						'sandbox' => array(
							'title'   => __( 'فعالسازی حالت آزمایشی', 'woocommerce' ),
							'type'    => 'checkbox',
							'label'   => __( 'فعالسازی حالت آزمایشی بیت پی', 'woocommerce' ),						
							'description' => __( 'برای فعال سازی حالت آزمایشی بیت پی چک باکس را تیک بزنید .', 'woocommerce' ),
							'default' => 'no',
							'desc_tip'    => true,
						),
						'payment_confing' => array(
							'title'       => __( 'تنظیمات عملیات پرداخت', 'woocommerce' ),
							'type'        => 'title',
							'description' => '',
						),
						'success_massage' => array(
							'title'       => __( 'پیام پرداخت موفق', 'woocommerce' ),
							'type'        => 'textarea',
							'description' => __( 'متن پیامی که میخواهید بعد از پرداخت موفق به کاربر نمایش دهید را وارد نمایید . همچنین می توانید از شورت کد {transaction_id} برای نمایش کد رهگیری (شماره تراکنش بیت پی) استفاده نمایید .', 'woocommerce' ),
							'default'     => __( 'با تشکر از شما . سفارش شما با موفقیت پرداخت شد .', 'woocommerce' ),
						),
						'failed_massage' => array(
							'title'       => __( 'پیام پرداخت ناموفق', 'woocommerce' ),
							'type'        => 'textarea',
							'description' => __( 'متن پیامی که میخواهید بعد از پرداخت ناموفق به کاربر نمایش دهید را وارد نمایید . همچنین می توانید از شورت کد {fault} برای نمایش دلیل خطای رخ داده استفاده نمایید . این دلیل خطا از سایت بیت پی ارسال میگردد .', 'woocommerce' ),
							'default'     => __( 'پرداخت شما ناموفق بوده است . لطفا مجددا تلاش نمایید یا در صورت بروز اشکال با مدیر سایت تماس بگیرید .', 'woocommerce' ),
						),
						'cancelled_massage' => array(
							'title'       => __( 'پیام انصراف از پرداخت', 'woocommerce' ),
							'type'        => 'textarea',
							'description' => __( 'متن پیامی که میخواهید بعد از انصراف کاربر از پرداخت نمایش دهید را وارد نمایید . این پیام بعد از بازگشت از بانک نمایش داده خواهد شد .', 'woocommerce' ),
							'default'     => __( 'پرداخت به دلیل انصراف شما ناتمام باقی ماند .', 'woocommerce' ),
						),
					)
				);
			}

			public function process_payment( $order_id ) {
				$order = new WC_Order( $order_id );	
				return array(
					'result'   => 'success',
					'redirect' => $order->get_checkout_payment_url(true)
				);
			}

			public function Send_to_bitpay_Gateway_By_HANNANStd($order_id){
				ob_start();
				global $woocommerce;
				$woocommerce->session->order_id_bitpay = $order_id;
				$order = new WC_Order( $order_id );
				$currency = $order->get_order_currency();
				$currency = apply_filters( 'WC_bitpay_Currency', $currency, $order_id );
				$action = $this->author;
				do_action( 'WC_Gateway_Payment_Actions', $action );			
				$form = '<form action="" method="POST" class="bitpay-checkout-form" id="bitpay-checkout-form">
						<input type="submit" name="bitpay_submit" class="button alt" id="bitpay-payment-button" value="'.__( 'پرداخت', 'woocommerce' ).'"/>
						<a class="button cancel" href="' . $woocommerce->cart->get_checkout_url() . '">' . __( 'بازگشت', 'woocommerce' ) . '</a>
					 </form><br/>';
				$form = apply_filters( 'WC_bitpay_Form', $form, $order_id, $woocommerce );				
				
				do_action( 'WC_bitpay_Gateway_Before_Form', $order_id, $woocommerce );	
				echo $form;
				do_action( 'WC_bitpay_Gateway_After_Form', $order_id, $woocommerce );
					
				if ( isset($_POST["bitpay_submit"]) ) {
					$action = $this->author;
					do_action( 'WC_Gateway_Payment_Actions', $action );		
					if(!extension_loaded('curl')){
						$order->add_order_note( __( 'تابع cURL روی هاست شما فعال نیست .', 'woocommerce') );
						wc_add_notice( __( 'تابع cURL روی هاست فروشنده فعال نیست .', 'woocommerce') , 'error' );
						return false;
					}
					
					$Amount = intval($order->order_total);
					$Amount = apply_filters( 'woocommerce_order_amount_total_IRANIAN_gateways_before_check_currency', $Amount, $currency );
					if ( strtolower($currency) == strtolower('IRT') || strtolower($currency) == strtolower('TOMAN')
						|| strtolower($currency) == strtolower('Iran TOMAN') || strtolower($currency) == strtolower('Iranian TOMAN')
						|| strtolower($currency) == strtolower('Iran-TOMAN') || strtolower($currency) == strtolower('Iranian-TOMAN')
						|| strtolower($currency) == strtolower('Iran_TOMAN') || strtolower($currency) == strtolower('Iranian_TOMAN')
						|| strtolower($currency) == strtolower('تومان') || strtolower($currency) == strtolower('تومان ایران')
					)
						$Amount = $Amount*10;
					else if ( strtolower($currency) == strtolower('IRHT') )							
						$Amount = $Amount*1000*10;
					else if ( strtolower($currency) == strtolower('IRHR') )							
						$Amount = $Amount*1000;
					
					$Amount = apply_filters( 'woocommerce_order_amount_total_IRANIAN_gateways_after_check_currency', $Amount, $currency );
					$Amount = apply_filters( 'woocommerce_order_amount_total_IRANIAN_gateways_irr', $Amount, $currency );
					$Amount = apply_filters( 'woocommerce_order_amount_total_bitpay_gateway', $Amount, $currency );
						
					$Description = 'خرید به شماره سفارش : '.$order->get_order_number();
					$Email = $order->billing_email;
					$Email = $Email ? $Email : '-';
					$FullName = $order->billing_first_name.' '.$order->billing_last_name;
					$FullName = $FullName ? $FullName : '-';
					
					//Hooks for iranian developer
					$Description = apply_filters( 'WC_bitpay_Description', $Description, $order_id );
					$Email = apply_filters( 'WC_bitpay_Email', $Email, $order_id );
					$FullName = apply_filters( 'WC_bitpay_FullName', $FullName, $order_id );
					do_action( 'WC_bitpay_Gateway_Payment', $order_id, $Description, $Email, $FullName );
					
					$CallbackURL = $this->bitpay_URL(urlencode(add_query_arg( 'wc_order', $order_id , WC()->api_request_url('WC_Gateway_bitpay') )));
					
					$Sandbox = $this->sandbox;
					
					if ( $Sandbox == "yes" || $Sandbox == "1" || $Sandbox == 1  ) {
						
						$url = 'http://bitpay.ir/payment-test/gateway-send';
						$send = 'http://bitpay.ir/payment-test/gateway-%s';
						$apiID = 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567';
						
					}
					else {
						
						$url = 'http://bitpay.ir/payment/gateway-send';
						$send = 'http://bitpay.ir/payment/gateway-%s';
						$apiID = $this->api;
					
					}
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POSTFIELDS, "api=$apiID&amount=$Amount&redirect=$CallbackURL&name=$FullName&email=$Email&description=$Description&reagentId=673582");
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch);
					if (is_numeric($result) and $result > 0) {
						do_action( 'WC_bitpay_Before_Send_to_Gateway', $order_id );
						ob_start();
						if (!headers_sent()) {
							header('Location: '.sprintf($send, $result));
							ob_end_clean();
							ob_end_flush();
							exit;
						}
						else {
							$redirect_page = sprintf($send, $result);
							echo "<script type='text/javascript'>window.onload = function () { top.location.href = '" . $redirect_page . "'; };</script>";
							exit;
						};
					}
					else {
						
						$fault = $result."1";
						
						$Note = sprintf( __( 'خطا در هنگام ارسال به بانک : %s', 'woocommerce'), $this->Fault_bitpay($fault) );
						$Note = apply_filters( 'WC_bitpay_Send_to_Gateway_Failed_Note', $Note, $order_id, $fault );
						$order->add_order_note( $Note );
						
						
						$Notice = sprintf( __( 'در هنگام اتصال به بانک خطای زیر رخ داده است : <br/>%s', 'woocommerce'), $this->Fault_bitpay($fault) );
						$Notice = apply_filters( 'WC_bitpay_Send_to_Gateway_Failed_Notice', $Notice, $order_id, $fault );
						if ( $Notice )
							wc_add_notice( $Notice , 'error' );
						
						do_action( 'WC_bitpay_Send_to_Gateway_Failed', $order_id, $fault );
					
					}
				}
			}

			public function Return_from_bitpay_Gateway_By_HANNANStd(){
				
				global $woocommerce;
				$action = $this->author;
				do_action( 'WC_Gateway_Payment_Actions', $action );			
				if ( isset($_GET['wc_order']) ) 
					$order_id = $_GET['wc_order'];
				else
					$order_id = $woocommerce->session->order_id_bitpay;
				if ( $order_id ) {
					
					$order = new WC_Order($order_id);
					$currency = $order->get_order_currency();		
					$currency = apply_filters( 'WC_bitpay_Currency', $currency, $order_id );
						
					if($order->status !='completed'){
		
						$Amount = intval($order->order_total);
						$Amount = apply_filters( 'woocommerce_order_amount_total_IRANIAN_gateways_before_check_currency', $Amount, $currency );
						if ( strtolower($currency) == strtolower('IRT') || strtolower($currency) == strtolower('TOMAN')
							|| strtolower($currency) == strtolower('Iran TOMAN') || strtolower($currency) == strtolower('Iranian TOMAN')
							|| strtolower($currency) == strtolower('Iran-TOMAN') || strtolower($currency) == strtolower('Iranian-TOMAN')
							|| strtolower($currency) == strtolower('Iran_TOMAN') || strtolower($currency) == strtolower('Iranian_TOMAN')
							|| strtolower($currency) == strtolower('تومان') || strtolower($currency) == strtolower('تومان ایران')
						)
							$Amount = $Amount*10;
						else if ( strtolower($currency) == strtolower('IRHT') )							
							$Amount = $Amount*1000*10;
						else if ( strtolower($currency) == strtolower('IRHR') )							
							$Amount = $Amount*1000;
					
						$Amount = apply_filters( 'woocommerce_order_amount_total_IRANIAN_gateways_after_check_currency', $Amount, $currency );
						$Amount = apply_filters( 'woocommerce_order_amount_total_IRANIAN_gateways_irr', $Amount, $currency );
						$Amount = apply_filters( 'woocommerce_order_amount_total_bitpay_gateway', $Amount, $currency );
						
						$Sandbox = $this->sandbox;
					
						if ( $Sandbox == "yes" || $Sandbox == "1" || $Sandbox == 1 ) {
							$url = 'http://bitpay.ir/payment-test/gateway-result-second';
							$apiID = 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567';
						} 
						else {
							$url = 'http://bitpay.ir/payment/gateway-result-second';
							$apiID = $this->api;
						}
						
						$id_get = $_POST['id_get'];
						$trans_id = $_POST['trans_id'];
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POSTFIELDS, "api=$apiID&id_get=$id_get&trans_id=$trans_id");
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
						$res = curl_exec($ch);
						curl_close($ch);
						if ($res == 1) {
							$status = 'completed';
							$transaction_id = $trans_id;
							$fault = 0;
						}
						else if ( $res == -1 || $res == -2 || $res == -3  ) {
							$status = 'failed';
							$transaction_id = $trans_id;
							$fault = $res;
						}
						else {
							$status = 'cancelled';
							$transaction_id = $trans_id;
							$fault = 0;
						}
						
						
						
						if ( $status == 'completed') {
							$action = $this->author;
							do_action( 'WC_Gateway_Payment_Actions', $action );
							if ( $transaction_id && ( $transaction_id !=0 ) )
								update_post_meta( $order_id, '_transaction_id', $transaction_id );
														
							$order->payment_complete($transaction_id);
							$woocommerce->cart->empty_cart();
							
							
							$Note = sprintf( __('پرداخت موفقیت آمیز بود .<br/> کد رهگیری (شماره تراکنش) : %s', 'woocommerce' ), $transaction_id );
							$Note = apply_filters( 'WC_bitpay_Return_from_Gateway_Success_Note', $Note, $order_id, $transaction_id );
							if ($Note)
								$order->add_order_note( $Note , 1 );
							
							$Notice = wpautop( wptexturize($this->success_massage));
							
							$Notice = str_replace("{transaction_id}",$transaction_id,$Notice);
							
							$Notice = apply_filters( 'WC_bitpay_Return_from_Gateway_Success_Notice', $Notice, $order_id, $transaction_id );
							if ($Notice)
								wc_add_notice( $Notice , 'success' );
							
							
							do_action( 'WC_bitpay_Return_from_Gateway_Success', $order_id, $transaction_id );
							
							wp_redirect( add_query_arg( 'wc_status', 'success', $this->get_return_url( $order ) ) );
							exit;
						}
						elseif ( $status == 'cancelled') {
							
							$action = $this->author;
							do_action( 'WC_Gateway_Payment_Actions', $action );		
							$tr_id = ( $transaction_id && $transaction_id != 0 ) ? ('<br/>شماره تراکنش : '.$transaction_id) : '';
					
							$Note = sprintf( __('کاربر در حین تراکنش از پرداخت انصراف داد . %s', 'woocommerce' ) , $tr_id);
							$Note = apply_filters( 'WC_bitpay_Return_from_Gateway_Cancelled_Note', $Note, $order_id, $transaction_id );
							if ( $Note )
								$order->add_order_note( $Note, 1 );
							
							
							$Notice =  wpautop( wptexturize($this->cancelled_massage));
							
							$Notice = str_replace("{transaction_id}",$transaction_id,$Notice);
							
							$Notice = apply_filters( 'WC_bitpay_Return_from_Gateway_Cancelled_Notice', $Notice, $order_id, $transaction_id );
							if ($Notice)
								wc_add_notice( $Notice , 'error' );
							
							do_action( 'WC_bitpay_Return_from_Gateway_Cancelled', $order_id, $transaction_id );
							
							wp_redirect(  $woocommerce->cart->get_checkout_url()  );
							exit;
							
						}
						else {
							
							$action = $this->author;
							do_action( 'WC_Gateway_Payment_Actions', $action );
							
							$tr_id = ( $transaction_id && $transaction_id != 0 ) ? ('<br/>شماره تراکنش : '.$transaction_id) : '';
							
							$Note = sprintf( __( 'خطا در هنگام بازگشت از بانک : %s %s', 'woocommerce'), $this->Fault_bitpay($fault) , $tr_id );
							$Note = apply_filters( 'WC_bitpay_Return_from_Gateway_Failed_Note', $Note, $order_id, $transaction_id, $fault , $transaction_id );
							if ($Note)
								$order->add_order_note( $Note , 1 );
							
							
							$Notice = wpautop( wptexturize($this->failed_massage));
							
							$Notice = str_replace("{transaction_id}",$transaction_id,$Notice);
							
							$Notice = str_replace("{fault}",$this->Fault_bitpay($fault),$Notice);
							$Notice = apply_filters( 'WC_bitpay_Return_from_Gateway_Failed_Notice', $Notice, $order_id, $transaction_id, $fault,$transaction_id );
							if ($Notice)
								wc_add_notice( $Notice , 'error' );
							
							do_action( 'WC_bitpay_Return_from_Gateway_Failed', $order_id, $transaction_id, $fault,$transaction_id );
							
							wp_redirect(  $woocommerce->cart->get_checkout_url()  );
							exit;
						}
				
				
					}
					else {
						$action = $this->author;
						do_action( 'WC_Gateway_Payment_Actions', $action );	
						$transaction_id = get_post_meta( $order_id, '_transaction_id', true );
						
						$Notice = wpautop( wptexturize($this->success_massage));
						
						$Notice = str_replace("{transaction_id}",$transaction_id,$Notice);
						
						$Notice = apply_filters( 'WC_bitpay_Return_from_Gateway_ReSuccess_Notice', $Notice, $order_id, $transaction_id );
						if ($Notice)
							wc_add_notice( $Notice , 'success' );
						
						
						do_action( 'WC_bitpay_Return_from_Gateway_ReSuccess', $order_id, $transaction_id );
							
						wp_redirect( add_query_arg( 'wc_status', 'success', $this->get_return_url( $order ) ) );
						exit;
					}
				}
				else {
					
					$action = $this->author;
					do_action( 'WC_Gateway_Payment_Actions', $action );		
					$fault = __('شماره سفارش وجود ندارد .', 'woocommerce' );
					$Notice = wpautop( wptexturize($this->failed_massage));
					$Notice = str_replace("{fault}",$fault, $Notice);
					$Notice = apply_filters( 'WC_bitpay_Return_from_Gateway_No_Order_ID_Notice', $Notice, $order_id, $fault );
					if ($Notice)
						wc_add_notice( $Notice , 'error' );		
					
					do_action( 'WC_bitpay_Return_from_Gateway_No_Order_ID', $order_id, $transaction_id, $fault );
						
					wp_redirect(  $woocommerce->cart->get_checkout_url()  );
					exit;
				}
			}
			
			public function bitpay_URL($url){
				$encoded='';
				$length=mb_strlen($url);
				for($i=0;$i<$length;$i++){
					$encoded.='%'.wordwrap(bin2hex(mb_substr($url,$i,1)),2,'%',true);
				}
				return $encoded;
			}
	
			private static function Fault_bitpay($err_code){
				$message = __('در حین پرداخت خطای سیستمی رخ داده است .', 'woocommerce' );
				switch($err_code){
					
                    case '1' :
						$message = __('تراکنش موفقیت آمیز بوده است .', 'woocommerce' );
                    break;
                    case '-11' :
						$message = __('api ارسالی با نوع api تعریف شده در درگاه سازگار نیست .', 'woocommerce' );
                    break;
                    case '-21' :
						$message = __('مقدار مبلغ نباید کمتر از 1000 ریال باشد .', 'woocommerce' );
                    break;
					case '-31' :
						$message = __('آدرس بازگشت از بانک معتبر نیست .', 'woocommerce' );
                    break;
					case '-41' :
						$message = __('درگاهی با اطلاعات ارسالی شما یافت نشده و یا در حالت انتظار می باشد .', 'woocommerce' );
                    break;	
					
					case '-1' :
						$message = __('api ارسالی با نوع api تعریف شده در درگاه سازگار نیست .', 'woocommerce' );
                    break;
                    case '-2' :
						$message = __('trans_id ارسال شده معتبر نمی باشد .', 'woocommerce' );
                    break;
					case '-3' :
						$message = __('id_get ارسال شده معتبر نمی باشد .', 'woocommerce' );
                    break;
					case '-4' :
						$message = __('چنین تراکنشی یا در سیستم وجود ندارد یا ناموفق بوده است .', 'woocommerce' );
                    break;						
					case '-500' :
						$message = __('برای ادامه پرداخت ، cURL را فعال کنید . برای فعالسازی با مدیر هاستینگ تماس بگیرید .', 'woocommerce' );
                    break;
	
				}
				return $message;
			}
 
		}
	}
}
add_action('plugins_loaded', 'Load_bitpay_Gateway', 0);