<template>
    <div class="form-area" @click="containerClick">
        <div v-show="resourceId">
            <div class="flex border-b border-40">
                <div class="w-1/5 py-6">
                    <label class="inline-block text-80 pt-2 leading-tight">{{ searchTitle }}</label>
                </div>
                <div class="py-6 px-8 w-1/2 item-variants-box">
                    <input type="text" :placeholder="searchTitle" class="w-full form-control form-input form-input-bordered search-input" @keyup="findVariants()" v-model="search" @focus="showVariants()">
                    <div class="variants" v-show="showVariantPanel" id="search-variants">
                        <ul>
                            <li v-for="variant in variants" @click="attachItem(variant.id)">
                                <div class="search-variant">
                                    <img :src="variant.img_url">
                                    {{ variant.name}}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="list-searched-items">
                <div class="selected-item" v-for="item in items">
                    <span class="delete-item" @click="deleteItem(item.id)">x</span>
                    <div class="photo">
                        <img :src="item.img_url">
                    </div>
                    <div class="name">{{ item.name }}</div>
                </div>

                <div class="no-items" v-if="items.length === 0">No added items</div>
            </div>
        </div>

        <div class="bg-warning py-4 px-4" v-show="!resourceId">
            <p>First save general information!</p>
        </div>
    </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import config from '../config'
import axios from 'axios'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () => ({
        search: '',
        searchTitle: '',
        items: [],
        variants: [],
        showVariantPanel: false
    }),

    created() {
        this.searchTitle = this.field.searchTitle || 'Search keyword';
    },

    mounted() {
        axios.get(config.apiUrl + '/items', {params: this.baseParams()}).then(response => {
            this.items = response.data
        });
    },

    methods: {
        baseParams: function(additional) {
            return {
                resourceId: this.resourceId,
                resourceModel: this.field.resourceModel,
                itemModel: this.field.itemModel,
                itemRelated: this.field.itemRelated,
                resourceFormat: this.field.resourceFormat,
                searchColumn: this.field.searchColumn,
                ...additional
            }
        },
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
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        findVariants: function() {
            axios.get(config.apiUrl + '/search', {
                params: this.baseParams({search: this.search})
            }).then(response => {
                this.variants = response.data;
                this.showVariants();
            });
        },

        deleteItem: function(id) {
            axios.delete(config.apiUrl + '/detach', {
                params: this.baseParams({itemId: id})
            }).then(response => {
                this.items = this.items.filter(function(item) {
                    return item.id !== id;
                });

                this.success('Successfully deleted!');
            });
        },

        attachItem: function(id) {
            axios.post(config.apiUrl + '/attach', this.baseParams({itemId: id})).then (response => {
                this.items.push(response.data);
                this.success('Successfully added!');
            }).catch ((error,) => {
                this.error(error.response.data.message);
            });
        },

        success: function (message) {
            this.$noty.success(message, {
                layout: 'bottomRight',
                timeout: 5000,
                killer: true,
            });
        },

        error: function (message) {
            this.$noty.error(message, {
                layout: 'bottomRight',
                timeout: 5000,
                killer: true,
            });
        },

        hideVariants: function() {
            this.showVariantPanel = false;
        },

        showVariants: function() {
            this.showVariantPanel = this.variants.length > 0
        },

        containerClick: function(event) {
            let specifiedElement = document.getElementById('search-variants');
            let isClickInside = specifiedElement.contains(event.target);

            if (!isClickInside && !event.target.classList.contains('search-input')) {
                this.hideVariants();
            }
        }
    },
}
</script>
