<template>
	<div v-if="result">

		<form>
			<a href="#/logout" v-bind:style="result.css.know__events__button_styles" v-bind:class="result.css.know__events__button_classes">{{result.App_Properties.FR_LOG_OUT}}</a>
			<a href="#/payment" v-bind:style="result.css.know__events__button_styles" v-bind:class="result.css.know__events__button_classes">{{result.App_Properties.FR_NEW_PAYMENT_BUTTON}}</a>
		</form>

		<h3>{{result.Event_Name}}</h3>

		<div class="know--events--container">
		    <div class="know--events--grid-row">
		    	<div class="know--events--grid-item">
		    		<h4 style="margin:0px;">{{result.Event_Date | date('dddd, MMMM D, YYYY')}}</h4>
					<div>
						<strong>Setup: </strong>
						<span>{{result.Setup_Start | date('h:mm a')}}</span> to <span>{{result.Setup_End | date('h:mm a')}}</span>
					</div>
					<div>
						<strong>Event: </strong>
						<span>{{result.Event_Start | date('h:mm a')}}</span> to <span>{{result.Event_End | date('h:mm a')}}</span>
					</div>
					<div>
						<strong>Breakdown: </strong>
						<span>{{result.Breakdown_Start | date('h:mm a')}}</span> to <span>{{result.Breakdown_End | date('h:mm a')}}</span>
					</div>
					<div>
						<strong>{{result.App_Properties.FR_CONTRACT_FIELD_NAME}}:</strong> <span>{{result.Contract_Signed ? result.App_Properties.FR_CONTRACT_SIGNED : result.App_Properties.FR_CONTRACT_NOT_SIGNED}}</span>
					</div>
					<hr class="know--events--visible-mobile">
		    	</div>
		    	<div class="know--events--grid-item">
		    		<h5 style="margin:0px;">{{result.App_Properties.FR_EVENT_MANAGER_TITLE}}</h5>
					<div v-if="result.Event_Manager">
						<div>{{result.Event_Manager.Full_Name}}</div>
						<div v-if="result.Event_Manager.Daytime_Phone_Raw">
							<a v-bind:href="'tel:' + result.Event_Manager.Daytime_Phone_Raw">{{result.Event_Manager.Daytime_Phone_Pretty}}</a>
						</div>
						<div>
							<a v-bind:href="'mailto:' + result.Event_Manager.Email">{{result.Event_Manager.Email}}</a>
						</div>
					</div>
					<div v-if="!result.Event_Manager">{{result.App_Properties.FR_NOT_SELECTED}}</div>
		    	</div>
		    </div>
		</div>

		<hr>

		<div class="know--events--container">
		    <div class="know--events--grid-row">
		    	<div class="know--events--grid-item">
		    		<h5 style="margin:0px;">{{result.App_Properties.FR_CLIENT_TITLE}}</h5>
					<div v-if="result.Client">
						<div>{{result.Client.Full_Name}}</div>
						<div>
							<a v-bind:href="'tel:' + result.Client.Daytime_Phone_Raw">{{result.Client.Daytime_Phone_Pretty}}</a>
						</div>
						<div>
							<a v-bind:href="'mailto:' + result.Client.Email">{{result.Client.Email}}</a>
						</div>
					</div>
					<div v-if="!result.Client">{{result.App_Properties.FR_NOT_SELECTED}}</div>
					<hr class="know--events--visible-mobile">
		    	</div>
		    	<div class="know--events--grid-item">
		    		<h5 style="margin:0px;">{{result.App_Properties.FR_VENUE_TITLE}}</h5>
					<div v-if="result.Venue">
						<div>{{result.Venue.Name}}</div>
						<div v-if="result.Venue.Phone && result.Venue.Phone.is_valid">
							<a v-bind:href="'tel:' + result.Venue.Phone.raw">{{result.Venue.Phone.pretty}}</a>
						</div>
						<div v-html="$options.filters.nl2br(result.Venue.Address.Multi_Line_Address)"></div>
					</div>
					<div v-if="!result.Venue">{{result.App_Properties.FR_NOT_SELECTED}}</div>
		    	</div>
		    </div>
		</div>

		<hr>

		<h5>{{result.App_Properties.FR_PRODUCTS_TITLE}}</h5>
		<table>
			<thead>
				<tr>
					<th>{{result.App_Properties.FR_PRODUCTS_FIELD__ITEM}}</th>
					<th>{{result.App_Properties.FR_PRODUCTS_FIELD__UNIT_PRICE}}</th>
					<th>{{result.App_Properties.FR_PRODUCTS_FIELD__QUANTITY}}</th>
					<th>{{result.App_Properties.FR_PRODUCTS_FIELD__TOTAL}}</th>
				</tr>
			</thead>
			<tbody>
					<tr v-for="item in result.Products">
						<td>{{item.Name}}</td>
						<td>{{item.Unit_Price | currency}}</td>
						<td>{{item.Quantity | number(2)}}</td>
						<td>{{item.Total | currency}}</td>
					</tr>
				<tr>
					<td colspan="3" style="text-align:right;"><b>{{result.App_Properties.FR_PRODUCTS_FIELD__TOTAL}}</b></td>
					<td>{{result.Total | currency}}</td>
				</tr>
			</tbody>
		</table>

		<hr/>

		<h5>Payments</h5>
		<div>
			<table>
				<thead>
					<tr>
						<th>{{result.App_Properties.FR_PAYMENTS_FIELD__ID}}</th>
						<th>{{result.App_Properties.FR_PAYMENTS_FIELD__DESCRIPTION}}</th>
						<th>{{result.App_Properties.FR_PAYMENTS_FIELD__TIMESTAMP}}</th>
						<th>{{result.App_Properties.FR_PAYMENTS_FIELD__AMOUNT}}</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="item in result.Payments.Transactions">
						<td>{{item.Id}}</td>
						<td>{{item.Description}}</td>
						<td>{{item.Timestamp | date('MMM D, YYYY')}} at {{item.Timestamp | date('h:mm a')}}</td>
						<td>{{item.Amount | currency}}</td>
					</tr>
					<tr v-if="result.Payments.Transactions.length == 0">
						<td colspan="4">{{result.App_Properties.FR_PAYMENTS_NONE}}</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align:right;"><b>{{result.App_Properties.FR_PAYMENTS_BALANCE}}</b></td>
						<td>{{result.Balance | currency}}</td>
					</tr>
				</tbody>
			</table>
			
			<a href="#/payment" v-bind:style="result.css.know__events__button_styles" v-bind:class="result.css.know__events__button_classes">{{result.App_Properties.FR_NEW_PAYMENT_BUTTON}}</a>
		</div>

	</div>
</template>

<script>
	module.exports = {
		data: function() {
			var dataCtrl = {
				result : null
			}

			return dataCtrl;
		},
		mixins : [
			loadingAnimation,
			platform
		],
		created(){

			var ctrl = this;

			ctrl.platform__post_r(ajaxurl, {
			  'action': 'know__events__custom_event_details',
			  Data : {
				x : 'y'
			  }
			})
			.then(function(result){
				ctrl.result = result;
				ctrl.PageLoaded(true); 
			});
		}
	}
</script>

<style>
	.home-item-container{
	  width:100%;
	  height: 150px;
	  background-color: #eef0e5;
	  border-radius: 4px;
	  color:#635f54 !important;
	  font-size: 25px;

	  display: -webkit-box;
	  display: -moz-box;
	  display: -ms-flexbox;
	  display: -webkit-flex;
	  display: flex;
	  align-items: center;
	  justify-content: center;

	  -o-transition:.5s;
	  -ms-transition:.5s;
	  -moz-transition:.5s;
	  -webkit-transition:.5s;
	  transition:.5s;
	}

	.home-item-container:hover, .home-item-container:active{
	  color:#635f54 !important;
	  background-color: #e6e4cb !important;
	}
</style>