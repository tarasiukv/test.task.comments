import './bootstrap';
import {createApp} from 'vue';
import App from './App.vue';
import router from "./router";
import 'froala-editor/js/plugins.pkgd.min.js';
import 'froala-editor/css/froala_editor.pkgd.min.css';
import VueFroala from 'vue-froala-wysiwyg';

const app = createApp(App);

app.use(router);
app.use(VueFroala);
app.mount('#app');
