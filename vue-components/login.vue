<template>
	<div v-if="result">

		<div id="know--events--login-heading-container">
			<h3>{{result.App_Properties.FR_LOGIN_TITLE}}</h3>
		</div>
		<form v-on:submit.prevent v-on:submit="buttons.login.action()">
			<label class="required" style="display:inline;"></label> {{result.App_Properties.FR_REQUIRED_FIELD_INDICATOR}}<br/>
			<input type="hidden" name="my_event__post_action" value="login" />
			<label for="rs_event_id" class="required">{{result.App_Properties.FR_FIELD_EVENT_ID}}</label>
			<input class="roc_form_control" id="my_event__event_id" autocomplete="off" type="text" placeholder="ex. 1397" v-model="eventId" style="text-transform:uppercase;"/><!--ui-mask="9?9?9?9?9?9?9?9" ui-mask-placeholder ui-mask-placeholder-char="space"-->
			<label for="rs_event_code" class="required">{{result.App_Properties.FR_FIELD_EVENT_CODE}}</label>
			<input class="roc_form_control" id="my_event__event_code" autocomplete="off" type="text" placeholder="ex. NDF4G" v-model="eventCode" style="text-transform:uppercase;"/><!--ui-mask="*?*?*?*?*?*?*?*?*" ui-mask-placeholder ui-mask-placeholder-char="space"-->
			<div v-if="errors.eventId || errors.eventCode">
				<div v-bind:style="result.css.know__events__alert_container_styles" v-bind:class="[result.css.know__events__alert_container_classes, 
				{'know--events--heading-container' : !result.css.know__events__alert_container_classes}]">
					<!--<h6 v-bind:style="result.css.know__events__alert_heading_styles" v-bind:class="result.css.know__events__alert_heading_classes">Oops!</h6>-->
					{{result.App_Properties.FR_LOGIN_EMPTY_FIELDS}}
				</div>
			</div>
			<div v-if="errors.auth">
				<div v-bind:style="result.css.know__events__alert_container_styles" v-bind:class="[{'know--events--heading-container' : !result.css.know__events__alert_container_classes}, result.css.know__events__alert_container_classes]">
					<!--<h6 v-bind:style="result.css.know__events__alert_heading_styles" v-bind:class="result.css.know__events__alert_heading_classes">Oops!</h6>-->
					{{result.App_Properties.FR_LOGIN_AUTH_ERROR}}
				</div>
			</div>
			<button type="submit" v-bind:style="result.css.know__events__login_button_styles" style="margin-top: 10px;" v-bind:class="result.css.know__events__login_button_classes" v-bind:disabled="buttons.login.action_text">{{buttons.login.action_text ? buttons.login.action_text : 'Log In'}}</button>
		</form>

	</div>
</template>

<script>
	module.exports = {
		data: function() {
			var ctrl = this;
			var dataCtrl = {
				eventId : null,
				eventCode : null,
				result : null,
				errors : {
					eventId : false,
					eventCode : false
			 	},
			 	buttons : {
					login : {
						action_text : null,
						action : function(){

							if(dataCtrl.eventId && dataCtrl.eventCode){
								dataCtrl.errors.eventId = false;
								dataCtrl.errors.eventCode = false;
								dataCtrl.buttons.login.action_text = 'Logging in...';

								ctrl.platform__post(ajaxurl, {
									'action': 'know__events__custom_login_auth',
									Data : {
										eventId : dataCtrl.eventId,
										eventCode : dataCtrl.eventCode
									}
								})
								.then(function(result){

									result = result.data;

									dataCtrl.errors.auth = result.Errors.Auth;
									if(result.Auth == true) router.push('/');
									else {
										ctrl.scrolltop();
										dataCtrl.buttons.login.action_text = null;
									}
								});

							} else {
								dataCtrl.errors.eventId = true;
								dataCtrl.errors.eventCode = true;
								ctrl.scrolltop();
							}
							
						}
					}
				}
			}

			return dataCtrl;
		},
		mixins : [
			loadingAnimation,
			platform
		],
		methods : {
			scrolltop : function(){
		 		//document.getElementById('know--events--login-heading-container').scrollIntoView();
		 	}
		},
		created(){

			var ctrl = this;

			ctrl.platform__post(ajaxurl, {
				'action': 'know__events__custom_login_init'
			})
			.then(function(result){

				if(result.Authenticated == true) router.push('/');

				ctrl.result = result.data;
				ctrl.PageLoaded(true);
			});
		}
	}
</script>

<style>
	.required:after {
	  color: #e32;
	  content: ' *';
	  display: inline;
	}

	.roc_form_control{
	  width:100%;
	}

	.know--events--heading-container{
		margin-top: 5px;
	 /* border:1px solid red;*/
	  padding:5px;
	  color:red;
	}
</style>