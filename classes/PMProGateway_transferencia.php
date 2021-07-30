<?php
	//include pmprogateway
	require_once ABSPATH. "wp-content/plugins/paid-memberships-pro/classes/gateways/class.pmprogateway.php";
	
	//load classes init method
	add_action('init', array('PMProGateway_transferencia', 'init'));
	
	class PMProGateway_transferencia extends PMProGateway
	{
		public static $gateways = array(
			"transferencia"
		);

		function __construct($gateway = NULL)
		{
			$this->gateway = $gateway;
			return $this->gateway;
		}										
		
		/**
		 * Run on WP init
		 *		 
		 * @since 1.8
		 */
		static function init()
		{			
			//make sure Pay by Check is a gateway option
			add_filter('pmpro_gateways', array('PMProGateway_transferencia', 'pmpro_gateways'));
			
			//add fields to payment settings
			add_filter('pmpro_payment_options', array('PMProGateway_transferencia', 'pmpro_payment_options'));
			add_filter('pmpro_payment_option_fields', array('PMProGateway_transferencia', 'pmpro_payment_option_fields'), 10, 2);
			add_filter('pmpro_checkout_after_payment_information_fields', array('PMProGateway_transferencia', 'pmpro_checkout_after_payment_information_fields'));

			//code to add at checkout
			$gateway = pmpro_getGateway();
			if($gateway == self::$gateways[0])
			{
				add_filter('pmpro_include_billing_address_fields', '__return_false');
				add_filter('pmpro_include_payment_information_fields', array('PMProGateway_transferencia' , 'pmpro_include_payment_information_fields') , 10);
				add_filter('pmpro_required_billing_fields', array('PMProGateway_transferencia', 'pmpro_required_billing_fields'));
				add_filter('pmpro_required_user_fields', array('PMProGateway_transferencia' , 'pmpro_required_user_fields') , 10 , 1);
			}

			add_action('pmpro_gateway_transferencia', array('PMProGateway_transferencia' , 'pmpro_gateway_transferencia') , 10);
		}
		
		/**
		 * Make sure Check is in the gateways list
		 *		 
		 * @since 1.8
		 */
		static function pmpro_gateways($gateways)
		{
			if(empty($gateways[self::$gateways[0]]))
				$gateways[self::$gateways[0]] = __(strtoupper(self::$gateways[0]), PMPRO_PLUGIN_NAME );
		
			return $gateways;
		}
		
		/**
		 * Get a list of payment options that the Check gateway needs/supports.
		 *		 
		 * @since 1.8
		 */
		static function getGatewayOptions()
		{			
			$options = array(
				'sslseal',
				'nuclear_HTTPS',
				'gateway_environment',
				'currency',
				'use_ssl',
				'tax_state',
				'tax_rate',
				self::$gateways[0]."_instructions",
				self::$gateways[0]."_nombre",
				self::$gateways[0]."_banco",
				self::$gateways[0]."_cuenta",
				self::$gateways[0]."_tipo",
				self::$gateways[0]."_iban",
				self::$gateways[0]."_email"
			);
			
			return $options;
		}
		
		/**
		 * Set payment options for payment settings page.
		 *		 
		 * @since 1.8
		 */
		static function pmpro_payment_options($options)
		{			
			//get stripe options
			$check_options = PMProGateway_transferencia::getGatewayOptions();
			
			//merge with others.
			$options = array_merge($check_options, $options);
			
			return $options;
		}

		/**
		 * Display fields for Check options.
		 *		 
		 * @since 1.8
		 */
		static function pmpro_payment_option_fields($values, $gateway)
		{
			$boolDisplay = ($gateway != self::$gateways[0]) ? "display: none;" : "";
			?>

			<tr class='pmpro_settings_divider gateway gateway_<?php echo(self::$gateways[0]); ?>' style='<?php echo($boolDisplay); ?> '>
				<td colspan='2'>
					<h3><?php echo(_e('Configuración de transferencias.', PMPRO_PLUGIN_NAME)); ?> </h3>
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope='row' valign='top'>
					<label for='<?php echo(self::$gateways[0]); ?>_nombre'><?php echo(_e('Nombre completo', PMPRO_PLUGIN_NAME)); ?> :</label>
				</th>
				<td>
					<input type='text' id='<?php echo(self::$gateways[0]); ?>_nombre' name='<?php echo(self::$gateways[0]); ?>_nombre' size='60' value='<?php echo(esc_attr($values[self::$gateways[0]."_nombre"])); ?>' />
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope='row' valign='top'>
					<label for='<?php echo(self::$gateways[0]); ?>_banco'><?php echo(_e('Nombre del Banco', PMPRO_PLUGIN_NAME)); ?> :</label>
				</th>
				<td>
					<input type='text' id='<?php echo(self::$gateways[0]); ?>_banco' name='<?php echo(self::$gateways[0]); ?>_banco' size='60' value='<?php echo(esc_attr($values[self::$gateways[0]."_banco"])); ?>' />
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope='row' valign='top'>
					<label for='<?php echo(self::$gateways[0]); ?>_cuenta'><?php echo(_e('Número de cuenta', PMPRO_PLUGIN_NAME)); ?> :</label>
				</th>
				<td>
					<input type='number' id='<?php echo(self::$gateways[0]); ?>_cuenta' min="0" name='<?php echo(self::$gateways[0]); ?>_cuenta' size='60' value='<?php echo(esc_attr($values[self::$gateways[0]."_cuenta"])); ?>' />
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope='row' valign='top'>
					<label for='<?php echo(self::$gateways[0]); ?>_tipo'><?php echo(_e('Tipo de cuenta', PMPRO_PLUGIN_NAME)); ?> :</label>
				</th>
				<td>
					<select class="form-control" id='<?php echo(self::$gateways[0]); ?>_tipo' name='<?php echo(self::$gateways[0]); ?>_tipo'>
					<?php
						foreach(transGetTiposCuentas() as $v => $cuenta){
							$selected = ( $values[self::$gateways[0]."_tipo"] == $v ) ? "selected='selected'" : "";
							
							if(empty($selected)):
					?>
							<option value="<?php echo($v); ?>">  <?php echo($cuenta); ?> </option>
					<?php
							else:
					?>
							<option value="<?php echo($v); ?>" <?php echo($selected); ?> >  <?php echo($cuenta); ?> </option>
					<?php
							endif;
						}
					?>
					</select>
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope='row' valign='top'>
					<label for='<?php echo(self::$gateways[0]); ?>_iban'><?php echo(_e('IBAN/RUT', PMPRO_PLUGIN_NAME)); ?> :</label>
				</th>
				<td>
					<input type='text' id='<?php echo(self::$gateways[0]); ?>_iban' name='<?php echo(self::$gateways[0]); ?>_iban' size='60' value='<?php echo(esc_attr($values[self::$gateways[0]."_iban"])); ?>' />
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope='row' valign='top'>
					<label for='<?php echo(self::$gateways[0]); ?>_email'><?php echo(_e('Correo', PMPRO_PLUGIN_NAME)); ?> :</label>
				</th>
				<td>
					<input type='email' id='<?php echo(self::$gateways[0]); ?>_email' name='<?php echo(self::$gateways[0]); ?>_email' size='60' value='<?php echo(esc_attr($values[self::$gateways[0]."_email"])); ?>' />
				</td>
			</tr>

			<tr class='gateway gateway_<?php echo(self::$gateways[0]); ?>'  style='<?php echo($boolDisplay); ?> '>
				<th scope="row" valign="top">
					<label for="<?php echo(self::$gateways[0]."_instructions"); ?>"><?php _e('Instrucciones', PMPRO_PLUGIN_NAME );?></label>					
				</th>
				<td>
					<textarea id="<?php echo(self::$gateways[0]."_instructions"); ?>" name="<?php echo(self::$gateways[0]."_instructions"); ?>" rows="3" cols="50" class="large-text"><?php echo wpautop(  wp_unslash( $values[self::$gateways[0]."_instructions"] ) ); ?></textarea>
					<p class="description"><?php _e('Se muestra en las páginas de confirmación y factura.', PMPRO_PLUGIN_NAME );?></p>
				</td>
			</tr>

			<?php
		}
		

		static function pmpro_include_payment_information_fields($include)
		{	
			trans_after_before_trans_check( self::$gateways[0] );

			return false;
		}

		static function pmpro_gateway_transferencia(){
			trans_after_before_trans_check( self::$gateways[0] );
		}

		/**
		 * Remove required billing fields
		 *		 
		 * @since 1.8
		 */
		static function pmpro_required_billing_fields($fields)
		{
			unset($fields["bfirstname"]);
			unset($fields["blastname"]);
			unset($fields["baddress1"]);
			unset($fields["bcity"]);
			unset($fields["bstate"]);
			unset($fields["bzipcode"]);
			unset($fields["bphone"]);
			unset($fields["bemail"]);
			unset($fields["bcountry"]);
			unset($fields["CardType"]);
			unset($fields["AccountNumber"]);
			unset($fields["ExpirationMonth"]);
			unset($fields["ExpirationYear"]);
			unset($fields["CVV"]);
			
			return $fields;
		}

		static function pmpro_required_user_fields($fields){
			unset($fields["username"]);
			unset($fields["password"]);
			unset($fields["password2"]);
			unset($fields["bemail"]);
			unset($fields["bconfirmemail"]);

			return $fields;
		}

		/**
		 * Show instructions on checkout page
		 * Moved here from pages/checkout.php
		 * @since 1.8.9.3
		 */
		static function pmpro_checkout_after_payment_information_fields() {
			global $gateway;
			global $pmpro_level;

			if($gateway == "check" && !pmpro_isLevelFree($pmpro_level)) {
				$instructions = pmpro_getOption("instructions");
				echo '<div class="' . pmpro_get_element_class( 'pmpro_check_instructions' ) . '">' . wpautop(wp_unslash( $instructions )) . '</div>';
			}
		}

		
		/**
		 * Process checkout.
		 *
		 */
		function process(&$order)
		{
			//clean up a couple values
			$order->payment_type = self::$gateways[0];
			$order->CardType = "";
			$order->cardtype = "";
			
			//check for initial payment
			if(floatval($order->InitialPayment) == 0)
			{
				//auth first, then process
				if($this->authorize($order))
				{						
					$this->void($order);										
					if(!pmpro_isLevelTrial($order->membership_level))
					{
						//subscription will start today with a 1 period trial
						$order->ProfileStartDate = date_i18n("Y-m-d") . "T0:0:0";
						$order->TrialBillingPeriod = $order->BillingPeriod;
						$order->TrialBillingFrequency = $order->BillingFrequency;													
						$order->TrialBillingCycles = 1;
						$order->TrialAmount = 0;
						
						//add a billing cycle to make up for the trial, if applicable
						if(!empty($order->TotalBillingCycles))
							$order->TotalBillingCycles++;
					}
					elseif($order->InitialPayment == 0 && $order->TrialAmount == 0)
					{
						//it has a trial, but the amount is the same as the initial payment, so we can squeeze it in there
						$order->ProfileStartDate = date_i18n("Y-m-d") . "T0:0:0";														
						$order->TrialBillingCycles++;
						
						//add a billing cycle to make up for the trial, if applicable
						if($order->TotalBillingCycles)
							$order->TotalBillingCycles++;
					}
					else
					{
						//add a period to the start date to account for the initial payment
						$order->ProfileStartDate = date_i18n("Y-m-d", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod, current_time("timestamp"))) . "T0:0:0";
					}
					
					$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
					return $this->subscribe($order);
				}
				else
				{
					if(empty($order->error))
						$order->error = __("Unknown error: Authorization failed.", PMPRO_PLUGIN_NAME );
					return false;
				}
			}
			else
			{
				//charge first payment
				if($this->charge($order))
				{							
					//set up recurring billing					
					if(pmpro_isLevelRecurring($order->membership_level))
					{						
						if(!pmpro_isLevelTrial($order->membership_level))
						{
							//subscription will start today with a 1 period trial
							$order->ProfileStartDate = date_i18n("Y-m-d") . "T0:0:0";
							$order->TrialBillingPeriod = $order->BillingPeriod;
							$order->TrialBillingFrequency = $order->BillingFrequency;													
							$order->TrialBillingCycles = 1;
							$order->TrialAmount = 0;
							
							//add a billing cycle to make up for the trial, if applicable
							if(!empty($order->TotalBillingCycles))
								$order->TotalBillingCycles++;
						}
						elseif($order->InitialPayment == 0 && $order->TrialAmount == 0)
						{
							//it has a trial, but the amount is the same as the initial payment, so we can squeeze it in there
							$order->ProfileStartDate = date_i18n("Y-m-d") . "T0:0:0";														
							$order->TrialBillingCycles++;
							
							//add a billing cycle to make up for the trial, if applicable
							if(!empty($order->TotalBillingCycles))
								$order->TotalBillingCycles++;
						}
						else
						{
							//add a period to the start date to account for the initial payment
							$order->ProfileStartDate = date_i18n("Y-m-d", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod, current_time("timestamp"))) . "T0:0:0";
						}
						
						$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
						if($this->subscribe($order))
						{
							$order->status = apply_filters("pmpro_check_status_after_checkout", "success");	//saved on checkout page	
							return true;
						}
						else
						{
							if($this->void($order))
							{
								if(!$order->error)
									$order->error = __("Unknown error: Payment failed.", PMPRO_PLUGIN_NAME );
							}
							else
							{
								if(!$order->error)
									$order->error = __("Unknown error: Payment failed.", PMPRO_PLUGIN_NAME );
								
								$order->error .= " " . __("A partial payment was made that we could not void. Please contact the site owner immediately to correct this.", PMPRO_PLUGIN_NAME );
							}
							
							return false;								
						}
					}
					else
					{
						//only a one time charge
						$order->status = apply_filters("pmpro_check_status_after_checkout", "success");	//saved on checkout page											
						return true;
					}
				}
				else
				{
					if(empty($order->error))
						$order->error = __("Unknown error: Payment failed.", PMPRO_PLUGIN_NAME );
					
					return false;
				}	
			}	
		}
		
		function authorize(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//simulate a successful authorization
			$order->payment_transaction_id = strtoupper(self::$gateways[0]) . $order->code;
			$order->updateStatus("authorized");													
			return true;					
		}
		
		function void(&$order)
		{
			//need a transaction id
			if(empty($order->payment_transaction_id))
				return false;
				
			//simulate a successful void
			$order->payment_transaction_id = strtoupper(self::$gateways[0]) . $order->code;
			$order->updateStatus("voided");					
			return true;
		}	
		
		function charge(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//simulate a successful charge
			$order->payment_transaction_id = strtoupper(self::$gateways[0]) . $order->code;
			$order->updateStatus("success");					
			return true;						
		}
		
		function subscribe(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//filter order before subscription. use with care.
			$order = apply_filters("pmpro_subscribe_order", $order, $this);
			
			//simulate a successful subscription processing
			$order->status = "success";		
			$order->subscription_transaction_id = strtoupper(self::$gateways[0]) . $order->code;				
			return true;
		}	
		
		function update(&$order)
		{
			//simulate a successful billing update
			return true;
		}
		
		function cancel(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//simulate a successful cancel			
			$order->updateStatus("cancelled");					
			return true;
		}	
	}
