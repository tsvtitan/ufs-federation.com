<?
class authorize_model extends Model{

	function authorize_model()
	{
		parent::Model();
		
		$this->load->helper('url');
		$this->load->helper('string');
		$this->load->helper('email');
	}
    
    
    function create_array($arr)
    {
        $order= new stdClass();
        
            $order->next_orders_id=$arr['next_order_id'];
            
            $order->description='';
            foreach($arr['cart'] as $item)
            {
                $order->description.=$item->name.' - qty:'.$item->quantity.'; ';
            }
            
            $order->info['total']=$arr['sum']+$arr['ship']['shipping'];
            $order->info['cc_number']=$arr['billing']['card_number'];
            $order->info['cc_expires_month']=$arr['billing']['card_expires_month'];
            $order->info['cc_expires_year']=$arr['billing']['card_expires_year'];
            $order->info['cc_cvv']=$arr['billing']['cvv'];
            
            $order->billing['firstname']=$arr['billing']['fname'];
            $order->billing['lastname']=$arr['billing']['lname'];
            $order->billing['company']=$arr['ship']['company_name'];
            $order->billing['street_address']=$arr['billing']['addr'];
            $order->billing['city']=$arr['billing']['city'];
            $order->billing['state']=$arr['billing']['state'];
            $order->billing['postcode']=$arr['billing']['zip'];
            $order->billing['country']='USA';
            
            $order->customer['telephone']=$arr['ship']['phone'];
            $order->customer['email_address']=$arr['ship']['email'];
            
            $order->delivery['firstname']=$arr['ship']['fname'];
            $order->delivery['lastname']=$arr['ship']['lname'];
            $order->delivery['street_address']=$arr['ship']['addr'];
            $order->delivery['city']=$arr['ship']['city'];
            $order->delivery['state']=$arr['ship']['state'];
            $order->delivery['postcode']=$arr['ship']['zip'];
            $order->delivery['country']='USA';
        
        return $order;
    }

    
	function process(
                     $order, //array
                     $login, //string
                     $transkey, //string
                     $email_customer=true, //true or false
                     $email_merchant=true, //true or false
                     $test=false //true or false
                    )
     {

	      // Set the next order id
	      $new_order_id = $order->next_orders_id;

	      // Populate an array that contains all of the data to be submitted
	      $submit_data = array(
            'x_login'               => $login, // The login name as assigned to you by authorize.net
            'x_tran_key'            => $transkey,  // The Transaction Key (16 digits) is generated through the merchant interface
            'x_relay_response'      => 'FALSE', // AIM uses direct response, not relay response
            'x_delim_char'          => '|',
            'x_delim_data'          => 'TRUE', // The default delimiter is a comma
            'x_version'             => '3.1',  // 3.1 is required to use CVV codes
            'x_type'                => 'AUTH_CAPTURE',
            'x_method'              => 'CC',
            'x_amount'              => number_format($order->info['total'], 2),
            'x_card_num'            => $order->info['cc_number'],
            'x_exp_date'            => $order->info['cc_expires_month'] . substr($order->info['cc_expires_year'], -2),
            'x_card_code'           => $order->info['cc_cvv'],
            'x_email_customer'      => ($email_customer == true) ? 'TRUE': 'FALSE',
            'x_email_merchant'      => ($email_merchant == true) ? 'TRUE': 'FALSE',
            'x_invoice_num'         => $new_order_id,
            'x_first_name'          => $order->billing['firstname'],
            'x_last_name'           => $order->billing['lastname'],
            'x_company'             => $order->billing['company'],
            'x_address'             => $order->billing['street_address'],
            'x_city'                => $order->billing['city'],
            'x_state'               => $order->billing['state'],
            'x_zip'                 => $order->billing['postcode'],
            'x_country'             => $order->billing['country'],
            'x_phone'               => $order->customer['telephone'],
            'x_email'               => $order->customer['email_address'],
            'x_ship_to_first_name'  => $order->delivery['firstname'],
            'x_ship_to_last_name'   => $order->delivery['lastname'],
            'x_ship_to_address'     => $order->delivery['street_address'],
            'x_ship_to_city'        => $order->delivery['city'],
            'x_ship_to_state'       => $order->delivery['state'],
            'x_ship_to_zip'         => $order->delivery['postcode'],
            'x_ship_to_country'     => $order->delivery['country'],
            'x_description'         => $order->description,
            'x_test_request'        => ($test == true) ? 'TRUE' : 'FALSE');
          
          $data='';

		  // concatenate the submission data and put into variable $data
	      while(list($key, $value) = each($submit_data)) {
            $data .= $key . '=' . urlencode($value) . '&';
	      }

	      // Remove the last "&" from the string
	      $data = substr($data, 0, -1);

	      // Post order info data to Authorize.net
	      // cURL must be compiled into PHP
	      // Connection must be https

	      if($test == true){
            $url = 'https://test.authorize.net/gateway/transact.dll';
	      }else{
            $url = 'https://secure.authorize.net/gateway/transact.dll';
	      }

	      $ch = curl_init();

	      curl_setopt($ch, CURLOPT_URL,$url);
          curl_setopt($ch, CURLOPT_VERBOSE, 0);
	      curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.

	      $response = curl_exec($ch);

	      curl_close ($ch);

	      $exp_response = explode('|', $response);

          // If the response code is not 1 (approved) then redirect back to the payment page with the appropriate error message
	      if($exp_response[0] != '1'){
              $this->temp->authorize_error=$exp_response[3];
              return false;
	      }else{
              return true;
          }
	    }

}
?>