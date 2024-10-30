<template>
	<div>
    <loading-animation v-bind:class="{'hideElement' : !$root.show_loading_animation}"></loading-animation>
    <router-view v-bind:class="{'hideElement' : $root.show_loading_animation}"></router-view>
	</div>
</template>

<script>
    module.exports = {
      data : function(){
        var dataCtrl = {

        };
        return dataCtrl;
      },
      mixins : [
          loadingAnimation
      ],
      created(){
        	//router.push('login')
      }
    }
</script>

<style>
  .hideElement {
    display: none;
  }
	.know--school-management--button{
        display: inline-block;
        width: auto;
        height: auto;
        padding: 1em 2.5em;
        color: #fff;
        background-color: #272727;
        border-width: 0;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 12px;
        line-height: 1em;
        font-weight: normal;
        font-style: normal;
        text-transform: uppercase;
        letter-spacing: 0px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        font-size: 14px;
        text-transform: none;
    }

    .know--school-management--input { 
        width: 100%;
        padding: 12px;
        margin: 6px 0 4px;
        border: 1px solid #ccc;
        background: #fafafa;
        color: #000;
        font-family: sans-serif;
        font-size: 12px;
        line-height: normal;
        box-sizing: border-box;
        border-radius: 2px;
    }

 .know--school-management--label { 

    }

    /* POSITIONING */

.ksm-left {
  text-align: left;
}

.ksm-right {
  text-align: right;
}

.ksm-center {
  text-align: center;
  margin-left: auto;
  margin-right: auto;
}

.ksm-justify {
  text-align: justify;
}

/* ==== GRID SYSTEM ==== */

.ksm-container {
  width: 90%;
  margin-left: auto;
  margin-right: auto;
}

.ksm-row {
  position: relative;
  width: 100%;
}

.ksm-row [class^="ksm-col"] {
  float: left;
  margin: 0.5rem 2%;
  min-height: 0.125rem;
}

.ksm-col-1,
.ksm-col-2,
.ksm-col-3,
.ksm-col-4,
.ksm-col-5,
.ksm-col-6,
.ksm-col-7,
.ksm-col-8,
.ksm-col-9,
.ksm-col-10,
.ksm-col-11,
.ksm-col-12 {
  width: 96%;
}

.ksm-col-1-sm {
  width: 4.33%;
}

.ksm-col-2-sm {
  width: 12.66%;
}

.ksm-col-3-sm {
  width: 21%;
}

.ksm-col-4-sm {
  width: 29.33%;
}

.ksm-col-5-sm {
  width: 37.66%;
}

.ksm-col-6-sm {
  width: 46%;
}

.ksm-col-7-sm {
  width: 54.33%;
}

.ksm-col-8-sm {
  width: 62.66%;
}

.ksm-col-9-sm {
  width: 71%;
}

.ksm-col-10-sm {
  width: 79.33%;
}

.ksm-col-11-sm {
  width: 87.66%;
}

.ksm-col-12-sm {
  width: 96%;
}

.ksm-row::after {
  content: "";
  display: table;
  clear: both;
}

.ksm-hidden-sm {
  display: none;
}

@media only screen and (min-width: 33.75em) {  /* 540px */
  .ksm-container {
    width: 80%;
  }
}

@media only screen and (min-width: 45em) {  /* 720px */
  .ksm-col-1 {
    width: 4.33%;
  }

  .ksm-col-2 {
    width: 12.66%;
  }

  .ksm-col-3 {
    width: 21%;
  }

  .ksm-col-4 {
    width: 29.33%;
  }

  .ksm-col-5 {
    width: 37.66%;
  }

  .ksm-col-6 {
    width: 46%;
  }

  .ksm-col-7 {
    width: 54.33%;
  }

  .ksm-col-8 {
    width: 62.66%;
  }

  .ksm-col-9 {
    width: 71%;
  }

  .ksm-col-10 {
    width: 79.33%;
  }

  .ksm-col-11 {
    width: 87.66%;
  }

  .ksm-col-12 {
    width: 96%;
  }

  .ksm-hidden-sm {
    display: block;
  }
}

@media only screen and (min-width: 60em) { /* 960px */
  .ksm-container {
    width: 75%;
    max-width: 60rem;
  }
}

</style>