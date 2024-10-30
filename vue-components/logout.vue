<template>
	<div>
        <!--we don't want to have anything here-->

	</div>
</template>

<script>
    module.exports = {
        mixins : [
            platform
        ],
        created(){
            var ctrl = this;
            ctrl.platform__post_r(ajaxurl, {
                'action': 'know__events__logout'
            }).then(function(){
                router.push('/login');
            });
        }
    }
</script>