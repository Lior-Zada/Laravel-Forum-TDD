<template>
  <div>
    <input id="trix" type="hidden" :name="name" :value="value"/>
    <trix-editor ref="trix" input="trix" :placeholder="placeholder"></trix-editor>
  </div>
</template>

<script>
import Trix from 'trix';
import "trix/dist/trix.css";
 
export default {
    props: ['name','value', 'placeholder', 'shouldChange'],

    mounted(){
        this.$refs.trix.addEventListener('trix-change', e => {
            this.$emit('input', e.target.innerHTML);
        }),

        this.$watch('shouldChange', ()=>{
            this.$refs.trix.value = '';
        })
    }

};
</script>

<style>
</style>