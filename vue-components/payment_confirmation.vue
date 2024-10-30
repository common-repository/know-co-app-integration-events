<template>
	<div v-if="result">
		<form>
			<a href="#/">Back to my event</a>
			<!--style="{{result.css.know__events__button_styles}}" class="{{result.css.know__events__button_classes}}"-->
		</form>

		<h3>{{result.App_Properties.FR_PAYMENTS_PROCESSED_TITLE}}</h3>

		{{get_text()}}<br>
		<br><a href="#/">{{result.App_Properties.FR_PAYMENTS_GO_HOME}}</a>
	</div>
</template>

<script>
	module.exports = {
		data: function() {

			var ctrl = this;
			var dataCtrl = {
				result : null
			};

			dataCtrl.init = function(){

				ctrl.transaction_id = ctrl.$route.params.transaction_id;

				ctrl.platform__post_r(ajaxurl, {
				  'action': 'know__events__payment_confirmation_init'
				}).then(function(result){
					ctrl.result = result;
					ctrl.PageLoaded(true); 
				});

			};

			dataCtrl.get_text = function(){
				return ctrl.result.App_Properties.FR_PAYMENTS_PROCESSED_TEXT.replace('TRANSACTIONID', ctrl.transaction_id)
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