
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import Vue from 'vue';

import store from './store';
import { BOOT_USERS_MODULE } from "./store/users/constants";
import UsersComponent from './components/UsersComponent.vue';

Vue.component('users-component', UsersComponent);

const app = new Vue({
    el: '#app',
    store,
    created() {
        this.$store.dispatch(BOOT_USERS_MODULE);
    }
});
