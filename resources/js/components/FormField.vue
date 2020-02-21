<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">

            <input
                :id="field.name"
                type="hidden"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                :max="field.maxLength"
                :url="field.url"
                :method="field.method"
                :buttonLabel="field.buttonLabel"
                :outputFields="field.outputFields"
                :save="field.save"
                :inputFields="field.inputFields"
                v-model="value"
            />

   <button  for="field.name" type="button"  class="btn btn-default btn-primary"  @click="gofetch()">
   <span>{{field.buttonLabel}}</span>
   </button>

   <div v-if="hasdata">
   <p>Title: {{returnValue('title')}}</p>
   <p>Id: {{returnValue('id')}}</p>
   </div>

    <svg v-if="fetching" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="text-60 mx-auto block" style="width: 50px;"><circle cx="15" cy="15" r="11.7475"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="12.2525" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="11.7475"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg>

        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {

        return {
            hasdata: false,
            fetching : false,
            mustSave: true,
            valueObject: {}
        }
        
    },

    methods: {
        
        
        returnValue(key) {
            console.log('Return value. Raw value is :', this.value);
            console.log('Someone want to read return value', key);
            if (this.value)
            {
                console.log('We will send:', this.value[key]);
                //return this.value[key];
                return this.value;
            } else {
                console.log('The field value is not set!');

            }
              
        },
        
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            console.log('Set initial value:', this.value);
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            console.log('Filling. Lets see if we should store at all...');
            if (this.mustSave) {
              console.log('Yup we need to save. So lets do that...');
              formData.append(this.field.attribute, JSON.stringify(this.value) || '')
            }
            
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            console.log('Handle change');
            this.value = value
        },

        fieldProperty(property, def = '') {

            var peek = this.field[property];
            if (peek !== undefined && peek !== null) {
                return peek
            } else {
                return def;
            }

        },


        gofetch() {
            let fieldElement = '';
            let inputValueOnForm = null;
            let valueInPayload = null;
            let modifiedURL = this.field.url;
            console.log('Field at FETCH:' , this.field);
            console.log('We are gonna go fetch from ' , this.field.url);
            this.fetching = true;
            console.log('Save?:', this.fieldProperty('save',true));
            console.log('Input fields:', this.field.inputFields);
            console.log('Method:', this.field.method);
            this.field.inputFields.forEach(inputField => {
                console.log('Lets try to find input field ', inputField.formid);
                fieldElement = document.getElementById(inputField.formid);
                console.log('Element is ', fieldElement);
                inputValueOnForm = fieldElement.value || '';
                console.log('Input value is ', inputValueOnForm);
                modifiedURL = this.field.url.replace('$' + inputField.fetchkeyname, inputValueOnForm);
                console.log('Modified URL is ', modifiedURL);

            });

            axios.get(modifiedURL)
                .then(res => {
                    this.fetching = false;
                    console.log('We have data:', res.data);
                    this.value = res.data;
                    //console.log(this.value['cards'])
                    //fieldElement = document.getElementById('client_surname');
                    this.hasdata = true;
                    console.log('Output fields:', this.field.outputFields);

                    this.field.outputFields.forEach(outputField => {
                        console.log('Lets try to find output field ', outputField.formid);
                        fieldElement = document.getElementById(outputField.formid);
                        console.log('Element is ', fieldElement);
                        valueInPayload = this.returnValue(outputField.jsonkey);
                        console.log('Value in payload is ', valueInPayload);
                        fieldElement.value = JSON.stringify(valueInPayload);
                        //fieldElement.value = valueInPayload;

                    });



                }).catch(err => {
                    this.fetching = false;
                    console.log(err)
            })            

        },

    },
}
</script>
