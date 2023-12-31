<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2020/02/17 10:01
 */
?>
<style>
    .com-radio {
        display: inline-block;
        position: relative;
        color: #606266;
        cursor: pointer;
        line-height: 1;
        outline: 0;
        white-space: nowrap;
        font-weight: bold;
    }

    .com-radio.is-checked {
        color: #409EFF;
    }

    .com-radio .com-radio-point {
        display: inline-block;
        border: 1px solid #DCDFE6;
        border-radius: 100%;
        width: 14px;
        height: 14px;
        background-color: #FFF;
        cursor: pointer;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        vertical-align: middle;
        line-height: 1;
    }

    .com-radio .com-radio-point .com-radio-inside-point {
        background: #fff;
        display: inline-block;
        width: 100%;
        height: 100%;
        border-radius: 100%;
        content: " ";
        border: 4px solid #fff;
    }

    .com-radio .com-radio-point:hover,
    .com-radio.is-checked .com-radio-point {
        border-color: #409EFF;
    }

    .com-radio.is-checked .com-radio-inside-point {
        border-color: #409EFF;
    }


    .com-radio .com-radio-label {
        line-height: 1;
        font-size: 14px;
        display: inline-block;
        vertical-align: middle;
        padding-left: 10px;
    }
</style>
<template id="com-radio">
    <label class="com-radio"
           :class="[
           value === label ? 'is-checked' : '',
           ]"
           @click="onClick">
        <span class="app-radio-point">
            <span class="app-radio-inside-point"></span>
        </span>
        <span class="app-radio-label">
            <slot></slot>
        </span>
        <span style="display:inline-block;width: 25px;"></span>
    </label>
</template>
<script>
    Vue.component('com-radio', {
        template: '#com-radio',
        props: {
            value: {
                default: false,
            },
            label: {
                default: null,
            },
        },
        data() {
            return {};
        },
        methods: {
            onClick() {
                this.$emit('input', this.label);
                this.$emit('change', this.label);
            },
        },
    });
</script>