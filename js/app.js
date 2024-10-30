//var familyContent = document.getElementById('familyContent');
//familyContent.innerHTML = '';//reset
//Vue.use(VueRouter)
//var app_base = 'https://family.fairvillefriends.org';

const router = new VueRouter({
  routes: [
	{
	  path: '/home',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/home.vue'),
	  name: "Home"
	},
	{
	  path: '/login',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/login.vue'),
	  name: "Login"
	},
	{
	  path: '/login/:redirect',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/login.vue'),
	  name: "Login2"
	},
	{
	  path: '/logout',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/logout.vue'),
	  name: "Logout"
	},
	{
	  path: '/logout/:redirect',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/logout.vue'),
	  name: "Logout2"
	},
	{
	  path: '/payment',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/new_payment.vue'),
	  name : 'New Payment'
	},
	{
	  path: '/payment-confirmation/:transaction_id',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/payment_confirmation.vue'),
	  name : 'Payment Confirmation'
	},
	{
	  path: '*',
	  component: httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/404.vue')
	}
  ]
});

Vue.filter('nl2br', function (value) {
  var span = document.createElement('span');
  if (!value) return value;
	var lines = value.split('\n');

	for (var i = 0; i < lines.length; i++) {
		span.innerText = lines[i];
		span.textContent = lines[i];  //for Firefox
		lines[i] = span.innerHTML;
	}
	return lines.join('<br />');
});

Vue.filter('date', function(value, format) {
  if (value) {
    return moment(String(value)).format(format)
  }
});

Vue.filter('currency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});

Vue.filter('number', function (value, decimalPlaces) {
   return value.toFixed(decimalPlaces); 
});

Vue.directive('centify', {
	methods : {
		check : function(el, binding, vnode){

			var value = String(el.value);

			if (!value) value = "";
			if(value.replace(/[^-]/g, "").length==1){
				var isNegative = true;
			}
			value = value.replace(/[^\/\d]/g,'');
			value = value.replace(/\b0+/g, '');//remove leading 0
			if(value.length==0) value='0'+value;
			if(value.length==1) value='0'+value;
			if(value.length==2) value='0'+value;
			value = value.substr(0, value.length-2)+'.'+value.substr(value.length-2, value.length);
			if(isNegative) value = '-'+value;
			
			if(el.value != value){

				el.value = value;
				el.dispatchEvent(new Event('input', {bubbles: true}));
				el.dispatchEvent(new Event('change', {bubbles: true}));

			}

		}
	},
	bind : function(el, binding, vnode){
		binding.def.methods.check(el, binding, vnode);
	},
	update : function(el, binding, vnode){
		binding.def.methods.check(el, binding, vnode);
	}
});

var loadingAnimation = {
  methods : {
	PageLoaded : function(viewValue){
	  var ctrl = this;
	  if(viewValue == true) Vue.set(ctrl.$root, 'show_loading_animation', false);
	  else Vue.set(ctrl.$root, 'show_loading_animation', true);
	}
  },
  components: {
	'loading-animation' : httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/loading_animation.vue')
  },
};


var platform = {
  methods : {
	platform__responder : function(){
	  return {
		parse : function(initial_response){

		  if(typeof initial_response == 'string'){
			try {
				response = JSON.parse(initial_response);
			} catch (e) {
			  response = initial_response;
				if (e instanceof SyntaxError) {
					//make it error
				}/* else {
					printError(e, false);
				}*/
			}
		  } else response = initial_response;

		  function alert_user(content){
			alert(content);
		  }
		  
		  if(typeof response.errors == 'undefined' ||
			 typeof response.alerts == 'undefined' ||
			 typeof response.content == 'undefined'
			){
			console.log('Error retreiving response from server. Response: ');
			console.log(response);
		  } else {
			
			if(response.logout==true){
			  //window.location.replace('/system/logout?redirect='+encodeURIComponent(window.location.pathname+window.location.hash));
			  router.push('/logout/' + encodeURIComponent(window.location.hash.substr(1)));
			  return;
			}
			
			if(response.deny_page_permission==true){
			  //$location.url('/dashboard');
			  console.log('DENY');
			  router.push('/');
			  return;
			}

			for(var x = 0; x < response.errors.length; x++){
			  if(response.errors[x]) alert_user(response.errors[x]);
			  else console.log('Expected error. Null given.');
			}

			for(var x = 0; x < response.alerts.length; x++){
			  if(response.alerts[x]) alert_user(response.alerts[x]);
			  else console.log('Expected alert. Null given.');
			}

			for(var x = 0; x < response.logs.length; x++){
			  if(response.logs[x]) console.log(response.logs[x]);
			  else console.log('Expected log. Null given.');
			}
		  
			return response.content;
			  
		  }

		  return null;
			  
		  }
	  };
	},
	platform__post : function(url, params){

	  var objectToFormData = function(obj, form, namespace) {
		  
		var fd = form || new FormData();
		var formKey;
		
		for(var property in obj) {
		  if(obj.hasOwnProperty(property)) {
			
			if(namespace) {
			  formKey = namespace + '[' + property + ']';
			} else {
			  formKey = property;
			}
		   
			// if the property is an object, but not a File,
			// use recursivity.
			if(typeof obj[property] === 'object' && !(obj[property] instanceof File)) {
			  
			  objectToFormData(obj[property], fd, property);
			  
			} else {
			  
			  // if it's a string or a File object
			  fd.append(formKey, obj[property]);
			}
			
		  }
		}
		
		return fd;
		  
	  };



	  //for wordpress (only accepts multi-part form data)
	  var form_data = new FormData();

	  for ( var key in params ) {
		  form_data.append(key, params[key]);
	  }

	  return axios(url, {
		method: "post",
		data: objectToFormData(params),
		config: { 
		  headers: {
			'Content-Type': 'multipart/form-data'
		  }
		},
		withCredentials: true
	  });
	},
	platform__post_r : function(url, params){

	  var ctrl = this;

	  return ctrl.platform__post(url, params).then(function(response){
		return ctrl.platform__responder().parse(response.data);
	  });

	},
	platform__get : function(url){

	  return axios(url, {
		method: "get",
		withCredentials: true
	  });
	},
	platform__get_r : function(url){

	  var ctrl = this;

	  return ctrl.platform__get(url).then(function(response){
		return ctrl.platform__responder().parse(response.data);
	  });
	  
	}
  }
};

var app = new Vue({
  el: '#know__events__my_event_placeholder',
  components: {
	'main-component': httpVueLoader(know__events__portal_config.plugin_url + '/vue-components/main.vue')
  },
  template : '<div><main-component></main-component></div>',
  router,
  mixins : [
	loadingAnimation,
	//platform
  ],
  data : function(){
	return {
	  show_loading_animation : false
	};
  },
  mounted(){
	//var theScript = document.createElement('script');
	//theScript.setAttribute('src', 'https://kit.fontawesome.com/4a49962fd9.js');//need a new cdn for this
	//document.head.appendChild(theScript);
  }
});

router.beforeEach((to, from, next) => {
  //app.show_loading_animation = true;
  Vue.set(app, 'show_loading_animation', true);
  document.title = 'My Event - ' + to.name;
  next()
});