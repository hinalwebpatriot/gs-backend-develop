<template>
    <div class="has-many-single-page-container">
        <div class="has-many-single-page" v-for="(item, index) of items">
            <div class="col-info">
                <div class="col-group">
                    <label>Title</label>
                    <input type="text" class="w-full form-control form-input form-input-bordered" v-model="item.title"/>
                </div>
                <div class="col-group">
                    <label>Description</label>
                    <textarea class="w-full form-control form-input min-h-textarea form-input-bordered" v-model="item.description"></textarea>
                </div>
            </div>
            <div class="col-price">
                <span type="button" class="remove-item" @click="removeService(index)">x</span>
                <div class="col-group">
                    <label>Price</label>
                    <input type="text" class="w-full form-control form-input form-input-bordered" v-model="item.price"/>
                </div>
                <div class="col-group">
                    <label>GST</label>
                    <input type="text" class="w-full form-control form-input form-input-bordered" v-model="item.gst"/>
                </div>
            </div>
        </div>

        <button type="button" class="ml-auto btn btn-default btn-primary mr-3" @click="addService">
            + Add service
        </button>

        <div v-for="messages of errors.errors">
            <p v-for="message of messages" class="my-2 text-danger">
                {{ message }}
            </p>
        </div>
    </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            items: this.field.value
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.requestName, this.items ? JSON.stringify(this.items) : '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        addService() {
            this.items.push({
                title: '',
                description: '',
                price: '',
                gst: '',
            })
        },

        removeService(index) {
            this.items.splice(index, 1);
        }
    },
}
</script>
