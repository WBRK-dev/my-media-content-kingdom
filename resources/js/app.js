// import './bootstrap';
import { createApp } from "vue";

// Hier komt een lijst met geimporteerde vue components

//    | vue var name         |     | vue module locatie                             |
import IncrButton             from "./vue-modules/basic-increment-button.vue"       ;
import IntermediateIncrButton from "./vue-modules/intermediate-increment-button.vue";

const app = createApp({});

// Hier komt een lijst met alle vue components

//            | html tagname                | | vue var name         |
app.component('basic-increment-button'       , IncrButton            );
app.component('intermediate-increment-button', IntermediateIncrButton);

app.mount("body");