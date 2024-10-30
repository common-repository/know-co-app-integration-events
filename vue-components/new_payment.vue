<template>
	<div v-if="result">
		<form>
			<a href="#/" v-bind:style="result.css.know__events__button_styles" v-bind:class="result.css.know__events__button_classes">Cancel</a>
		</form>

		<div class="payment-section-heading">1. Payment amount</div>

		<div v-if="result.Payment_Installment_Options.length > 0">
			<div class="radio" v-for="payment_option in get_unpaid_installments()">
				<label>
					<input type="radio" v-model="paymentForm.amount_type" v-bind:value="payment_option.Id" v-on:click="set_payment_amount(payment_option)"> {{payment_option.Title}} ({{payment_option.Total_Amount | number(2)}})
				</label>
			</div>

			<div v-if="get_unpaid_installments().length > 0">
				<a v-if="!show_more_payment_options" v-on:click="show_more_payment_options = true;">Show more payment options</a>
				<a v-if="show_more_payment_options" v-on:click="show_more_payment_options = false;">Show less payment options</a>
			</div>
		</div>

		<div v-if="show_more_payment_options || get_unpaid_installments().length == 0">
			<div class="radio" v-for="payment_option in result.Payment_Options">
				<label>
					<input type="radio" v-model="paymentForm.amount_type" v-bind:value="payment_option.Id" v-on:click="set_payment_amount(payment_option)"> {{payment_option.Title}} ({{payment_option.Total_Amount | number(2)}})
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" v-model="paymentForm.amount_type" value="other" v-on:click="reset_other_amount()"> Other
				</label>
			</div>
			<div v-if="paymentForm.amount_type=='other'">
				<div class="input-group input-group-sm" id="otherValue" style="margin-bottom:5px;">
					<span class="input-group-addon">Amount $</span>
					<input type="text" id="otherPayment" class="form-control" placeholder="0.00" v-on:change="process_other_amount()" v-model="paymentForm.other_amount"v-centify/> 
				</div>
			</div>
		</div>

		<div v-if="validate_amount()">
			<div class="payment-section-heading">2. Billing address</div>
			<div v-for="(billing_address, index) in result.Billing_Addresses" class="radio">
				<label>
					<input type="radio" v-model="paymentForm.billing_address_type" v-bind:value="'Billing_' + index" v-on:click="client_billing_address_to_payment(billing_address)"> <span v-if="billing_address.Name">{{billing_address.Name}} - </span>{{billing_address.One_Line_Address}}
				</label>
			</div>
			<div class="radio">
				<label><input type="radio" v-on:click="alt_billing_address_to_payment()" v-model="paymentForm.billing_address_type" value="Alternate"> Alternate Billing Address</label>
			</div>
			<div v-if="paymentForm.billing_address_type && paymentForm.billing_address_type != 'Alternate'">
				<div v-if="paymentForm.billing_address_type && (paymentForm.card_details.name || paymentForm.card_details.address_line_1)" style="margin-top:15px;">
					<div>{{paymentForm.card_details.name}}</div>
					<div>{{paymentForm.card_details.address_line_1}}</div>
					<div v-if="paymentForm.card_details.address_line_2">{{paymentForm.card_details.address_line_2}}</div>
					<div>{{paymentForm.card_details.city}}, {{paymentForm.card_details.state}} {{paymentForm.card_details.zip}}</div>
				</div>
			</div>
			<div v-if="paymentForm.billing_address_type == 'Alternate'" style="margin-top:15px;">
				<div>
					<label class="know--events--required">Name</label>
					<input type="text" class="know--events--form-control" v-model="alt_billing_address.name" v-on:change="alt_billing_address_to_payment()">
				</div>
				<div>
					<label class="know--events--required">Line 1</label>
					<input type="text" class="know--events--form-control" v-model="alt_billing_address.line_1" v-on:change="alt_billing_address_to_payment()">
				</div>
				<div v-if="alt_billing_address.line_1">
					<label>Line 2</label>
					<input type="text" class="know--events--form-control" v-model="alt_billing_address.line_2" v-on:change="alt_billing_address_to_payment()">
				</div>
				<div>
					<label class="know--events--required">City</label>
					<input type="text" class="know--events--form-control" v-model="alt_billing_address.city" v-on:change="alt_billing_address_to_payment()">
				</div>

				<div style="display: flex;">
					<div style="width:50%; margin-right: 10px;">
						<label class="know--events--required">State</label>
						<input type="text" class="know--events--form-control" v-model="alt_billing_address.state" v-on:change="alt_billing_address_to_payment()">
					</div>
					<div style="width:50%;">
						<label class="know--events--required">Zip</label>
						<input type="text" class="know--events--form-control" v-model="alt_billing_address.zip" v-on:change="alt_billing_address_to_payment()">
					</div>
				</div>
			</div>
		</div>

		<div v-if="validate_amount() && validate_billing_address()">
			<div class="payment-section-heading">3. Payment information</div>

			<div v-if="result.Credit_Card_Processor.Name == 'stripe'">

				<div id="stripe-payment-request-button" style="margin-top:10px; margin-bottom:10px;"></div>
				
				<div id="stripe-card-element" style="margin-top: 10px;"></div>
				<div id="stripe-card-errors"></div>

				<button type="submit" v-on:click="cc_processors.stripe.submit.action()" v-bind:style="result.css.know__events__payment_process_button_styles" style="margin-top: 10px;" v-bind:class="result.css.know__events__payment_process_button_classes" v-bind:disabled="cc_processors.stripe.submit.action_text">{{cc_processors.stripe.submit.action_text ? cc_processors.stripe.submit.action_text : 'Submit payment'}}</button>

			</div>
			

			
		</div>
	</div>
</template>

<script>
	module.exports = {
		data: function() {

			var ctrl = this;
			var dataCtrl = {};

			dataCtrl.result = null;

			dataCtrl.show_more_payment_options = false;

			dataCtrl.alt_billing_address = {
				line_1 : null,
				line_2 : null,
				city : null,
				state : null,
				zip : null,
				country : null
			};

			dataCtrl.cc_processors = {
				stripe : {
					submit : {
						action_text : null,
						action : function(){
							dataCtrl.cc_processors.stripe.submit.action_text = 'Processing...';
							dataCtrl.cc_processors.stripe.client.createToken(dataCtrl.cc_processors.stripe.card, {
								name : ctrl.paymentForm.card_details.name,
								address_line1 : ctrl.paymentForm.card_details.address_line_1,
								address_line2 : ctrl.paymentForm.card_details.address_line_2,
								address_city : ctrl.paymentForm.card_details.city,
								address_state : ctrl.paymentForm.card_details.state,
								address_zip : ctrl.paymentForm.card_details.zip,
								currency : 'usd'
							}).then(function(result) {
								if (result.error) {
									dataCtrl.cc_processors.stripe.submit.action_text = null;

									// Inform the user if there was an error.
									var errorElement = document.getElementById('card-errors');
									errorElement.textContent = result.error.message;
								} else {
									dataCtrl.paymentForm.Token = result.token;
									dataCtrl.submit_payment();
								}
							});
						}
					},
					style : {
					  base: {
					    color: '#32325d',
					    '::placeholder': {
					      color: '#aab7c4'
					    }
					  },
					  invalid: {
					    color: '#fa755a',
					    iconColor: '#fa755a'
					  }
					}
				}
			};

			dataCtrl.paymentForm = {
				amount_type : null,
				method : null,
				other_amount : null,
				check_number : null,
				billing_address_type : null,
				alt_billing_address : {},
				card_details : {}
			};

			dataCtrl.init = function(){
				return ctrl.platform__post_r(ajaxurl, {
					'action': 'know__events__payment_init'
				})
				.then(function(result){
					dataCtrl.result = result;

					if(dataCtrl.get_unpaid_installments().length == 0 && dataCtrl.result.Payment_Options.length == 0){
						dataCtrl.paymentForm.amount_type = 'other';
						dataCtrl.reset_other_amount();
					}

					ctrl.PageLoaded(true); 
				});
			};

			dataCtrl.init_cc_processor = function(){
				if(dataCtrl.result.Credit_Card_Processor.Name == 'stripe'){

					dataCtrl.cc_processors.stripe.client = Stripe(dataCtrl.result.Credit_Card_Processor.Publishable_Key);
					dataCtrl.cc_processors.stripe.elements = dataCtrl.cc_processors.stripe.client.elements();

					dataCtrl.cc_processors.stripe.card = dataCtrl.cc_processors.stripe.elements.create('card', {
						style: dataCtrl.cc_processors.stripe.style,
						hidePostalCode : true
					});

					dataCtrl.cc_processors.stripe.card.mount('#stripe-card-element');

					dataCtrl.cc_processors.stripe.card.addEventListener('change', function(event) {
						var displayError = document.getElementById('stripe-card-errors');
						if (event.error) {
							displayError.textContent = event.error.message;
						} else {
							displayError.textContent = '';
						}
					});

					var paymentRequest = dataCtrl.cc_processors.stripe.client.paymentRequest({
						country: 'US',
						currency: 'usd',
						total: {
							label: '', //this needs to be dynamic????
							amount: dataCtrl.paymentForm.amount * 100,
						},
						requestPayerName: false,
						requestPayerEmail: false
					});

					var prButton = dataCtrl.cc_processors.stripe.elements.create('paymentRequestButton', {
					  paymentRequest: paymentRequest,
					});

					paymentRequest.on('token', function(ev) {

						dataCtrl.paymentForm.Token = ev.token;
						dataCtrl.submit_payment().then(function(result){
							if(result.Transaction_Id) ev.complete('success');
							else ev.complete('fail');
						});
					});

					dataCtrl.cc_processors.stripe.show_payment_request_button = false;
					// Check the availability of the Payment Request API first.
					paymentRequest.canMakePayment().then(function(result) {
					  if (result) {
					  	prButton.mount('#stripe-payment-request-button');
					    dataCtrl.cc_processors.stripe.show_payment_request_button = true;
					  } else {
					  	//silence
					    //document.getElementById('payment-request-button').style.display = 'none';
					  }
					});
				}
			};


			/*dataCtrl.open_credit_card_modal = function(){

				//this will actually become the process payment button

				var amount = dataCtrl.paymentForm.amount.toLocaleString('en-US', {minimumFractionDigits : 2});
				
				if(dataCtrl.result.Credit_Card_Processor.Name == 'stripe' && dataCtrl.result.Credit_Card_Processor.Publishable_Key && dataCtrl.result.Credit_Card_Processor.Publishable_Key != ''){
					
					dataCtrl.stripe_processing = false;

					var handler = StripeCheckout.configure({
						key: dataCtrl.result.Credit_Card_Processor.Publishable_Key,
						locale: 'auto',
						token: function(token) {
							$timeout(function(){
								dataCtrl.stripe_processing = true;
								dataCtrl.paymentForm.Token = token;
								dataCtrl.submit_payment();
							});
						}
					});

					handler.open({
						description : 'New Payment',
						panelLabel : 'Process',
						email : dataCtrl.result.System_Email,
						allowRememberMe : false,
						amount : amount * 100,//convert to cents
						opened : function(){
							
						},
						closed : function(){
							$timeout(function(){
								if(dataCtrl.stripe_processing == false){
									console.log('closed modal');
									dataCtrl.payment_processing = false;
								}
							});
						}
					});


				} else if(dataCtrl.result.Credit_Card_Processor.Name == 'simplify' && dataCtrl.result.Credit_Card_Processor.Public_Key && dataCtrl.result.Credit_Card_Processor.Public_Key != ''){
					
					//Deprecated. Need front end modal though.
					SystemModal.dialog('New Payment', PlatformWebAppDirectory('accounting') + '/payments/credit_card_modal.html', [
					{
						Name : 'Cancel',
						Class : 'default'
					},
					{
						Name : 'Process',
						Class : 'primary',
						Callback : function(){

							if(!dataCtrl.paymentForm.card_details.card_number ||
								!dataCtrl.paymentForm.card_details.card_cvc ||
								!dataCtrl.paymentForm.card_details.card_exp_month ||
								!dataCtrl.paymentForm.card_details.card_exp_year){
								dataCtrl.open_credit_card_modal();
								return;
							}


							SimplifyCommerce.generateToken({
								key: dataCtrl.result.Credit_Card_Processor.Public_Key,
								card: {
									number: String(dataCtrl.paymentForm.card_details.card_number).replace(/ /g, ''),
									cvc: String(dataCtrl.paymentForm.card_details.card_cvc).replace(/ /g, ''),
									expMonth: dataCtrl.paymentForm.card_details.card_exp_month,
									expYear: dataCtrl.paymentForm.card_details.card_exp_year,
									name: dataCtrl.paymentForm.card_details.name,
									addressLine1: dataCtrl.paymentForm.card_details.address_line_1,
									addressLine2: dataCtrl.paymentForm.card_details.address_line_2,
									addressCity: dataCtrl.paymentForm.card_details.city,
									addressState: dataCtrl.paymentForm.card_details.state,
									addressZip: dataCtrl.paymentForm.card_details.zip,
								}
							}, function(data){
								// Check for errors
								if (data.error) {
									// Show any validation errors
									if (data.error.code == "validation") {
										for(var error in data.error.fieldErrors){
											SystemModal.alert(data.error.fieldErrors[error].message);
										}
									}

									dataCtrl.payment_processing = false;
								} else {
									// The token contains id, last4, and card type
									var token = data["id"];

									dataCtrl.paymentForm.credit_token = token;
									dataCtrl.submit_payment();
								}
							});
						}
					}
				], ctrl);

				}

			};*/

			dataCtrl.get_unpaid_installments = function(){
				var installments = [];

				if(dataCtrl.result && dataCtrl.result.Payment_Installment_Options){
					for(var x in dataCtrl.result.Payment_Installment_Options){
						if(dataCtrl.result.Payment_Installment_Options[x].Paid == false){
							installments.push(dataCtrl.result.Payment_Installment_Options[x]);
						}

					}
				}

				return installments;
			};

			dataCtrl.validate_amount = function(){
				//console.log(dataCtrl.paymentForm);
				if(!dataCtrl.paymentForm.amount || dataCtrl.paymentForm.amount <= 0){

					return false;

				}

				return true;
			};

			dataCtrl.alt_billing_address_to_payment = function(){
				
				dataCtrl.paymentForm.card_details.name = dataCtrl.alt_billing_address.name;
				dataCtrl.paymentForm.card_details.address_line_1 = dataCtrl.alt_billing_address.line_1;
				dataCtrl.paymentForm.card_details.address_line_2 = dataCtrl.alt_billing_address.line_2;
				dataCtrl.paymentForm.card_details.city = dataCtrl.alt_billing_address.city;
				dataCtrl.paymentForm.card_details.zip = dataCtrl.alt_billing_address.zip;
				dataCtrl.paymentForm.card_details.state = dataCtrl.alt_billing_address.state;

				dataCtrl.payment_handler_try_init();
			};

			dataCtrl.client_billing_address_to_payment = function(address_instance){

				dataCtrl.paymentForm.card_details.name = address_instance.User_Name;
				dataCtrl.paymentForm.card_details.address_line_1 = address_instance.Address_Line_1;
				dataCtrl.paymentForm.card_details.address_line_2 = address_instance.Address_Line_2;
				dataCtrl.paymentForm.card_details.city = address_instance.City;
				dataCtrl.paymentForm.card_details.state = address_instance.State;
				dataCtrl.paymentForm.card_details.zip = address_instance.Zip;

				dataCtrl.payment_handler_try_init();

			};

			dataCtrl.payment_handler_try_init = function(){
				if(dataCtrl.validate_amount() && dataCtrl.validate_billing_address()){
					ctrl.$forceUpdate();
					setTimeout(() => {
						dataCtrl.init_cc_processor();
					},100);
					
				}
			};

			dataCtrl.validate_billing_address = function(){
				if(dataCtrl.paymentForm.card_details.name && 
					dataCtrl.paymentForm.card_details.address_line_1 &&
					dataCtrl.paymentForm.card_details.city &&
					dataCtrl.paymentForm.card_details.state &&
					dataCtrl.paymentForm.card_details.zip){

					return true;
				}

				return false;
			};

			/*dataCtrl.validate_payment_form = function(){
				if(!dataCtrl.paymentForm.amount || dataCtrl.paymentForm.amount <= 0){

					return false;

				}

				if(
					!dataCtrl.paymentForm.card_details || 
					(!dataCtrl.paymentForm.card_details.name || dataCtrl.paymentForm.card_details.name==="") ||
					(!dataCtrl.paymentForm.card_details.address_line_1 || dataCtrl.paymentForm.card_details.address_line_1==="") ||
					(!dataCtrl.paymentForm.card_details.zip || dataCtrl.paymentForm.card_details.zip==="") ||
					(!dataCtrl.paymentForm.card_details.city || dataCtrl.paymentForm.card_details.city==="") ||
					(!dataCtrl.paymentForm.card_details.state || dataCtrl.paymentForm.card_details.state==="")
				) return false;

				return true;
			};

			/*dataCtrl.process_payment = function(){

				dataCtrl.payment_processing = true;
				dataCtrl.open_credit_card_modal();

			};*/

			dataCtrl.reset_other_amount = function(){
				dataCtrl.paymentForm.other_amount_text = 0;
				dataCtrl.paymentForm.amount = 0;
				dataCtrl.paymentForm.Subtotal = 0;
				dataCtrl.paymentForm.Tax = 0;
			};

			dataCtrl.process_other_amount = function(){
				dataCtrl.paymentForm.amount = parseFloat(dataCtrl.paymentForm.other_amount) + parseFloat(dataCtrl.other_amount_tax());
				dataCtrl.paymentForm.Subtotal = dataCtrl.paymentForm.other_amount;
				dataCtrl.paymentForm.Tax = dataCtrl.other_amount_tax();
			};

			dataCtrl.other_amount_tax = function(){
				var val = dataCtrl.paymentForm.other_amount * dataCtrl.result.Sales_Tax_Percentage / 100;
				return val.toFixed(2);
			};

			dataCtrl.set_payment_amount = function(payment_option){
				dataCtrl.paymentForm.amount = payment_option.Total_Amount;
				dataCtrl.paymentForm.Subtotal = payment_option.Amount;
				dataCtrl.paymentForm.Tax = payment_option.Sales_Tax;
			};

			dataCtrl.submit_payment = function(){

				delete dataCtrl.paymentForm.card_details.number;
				delete dataCtrl.paymentForm.card_details.cvc;
				delete dataCtrl.paymentForm.card_details.expMonth;
				delete dataCtrl.paymentForm.card_details.expYear;

				var data = JSON.stringify(dataCtrl.paymentForm);
				data = dataCtrl.paymentForm;

				//console.log(data);

				ctrl.platform__post_r(ajaxurl, {
					'action': 'know__events__payment_process',
					Credit_Card_Processor : dataCtrl.result.Credit_Card_Processor,
					data: data
				})
				.then(function(result){
					//fix this
					//router.push isnt sending to the right location
					if(result.Transaction_Id) router.push('/payment-confirmation/' + result.Transaction_Id); 
					else {
						alert('The was a problem processing your payment. Please try again.');
						location.reload();
					}
				});

			};

			return dataCtrl;

		},
		mixins : [
			loadingAnimation,
			platform
		],
		created(){

			var ctrl = this;

			ctrl.init();
		}
	}
</script>

<style>
	.know--events--form-control{
		width:100%;
	}

	.know--events--required:after {
		content:" *"; 
		color:red;
	}

	.payment-section-heading{
		margin-top: 30px;
		font-weight: bold;
		font-size: 120%;
	}

	#stripe-card-element {
	  box-sizing: border-box;

	  height: 40px;

	  padding: 10px 12px;

	  border: 1px solid gray;
	  border-radius: 4px;
	  background-color: white;

	  /*box-shadow: 0 1px 3px 0 #e6ebf1;
	  -webkit-transition: box-shadow 150ms ease;
	  transition: box-shadow 150ms ease;*/
	}

	.StripeElement--focus {
	  box-shadow: 0 1px 3px 0 #cfd7df;
	}

	.StripeElement--invalid {
	  border-color: #fa755a;
	}

	.StripeElement--webkit-autofill {
	  background-color: #fefde5 !important;
	}
</style>