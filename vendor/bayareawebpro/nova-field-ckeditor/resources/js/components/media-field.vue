<script>
import spinner from './../assets/spinner'
import MediaBrowser from "./media-browser"
import HasUUID from "./mixins/HasUUID"
import interactsWithResources from "./mixins/interactsWithResources"
import { FormField, HandlesValidationErrors } from 'laravel-nova'
export default {
    props: ['resourceName', 'resourceId', 'field'],
    mixins: [FormField, HandlesValidationErrors,interactsWithResources, HasUUID],
    components: {MediaBrowser},
    data: ()=>({preview: null}),
    computed: {
        event(){
            return `ckeditor:media:${this.$options.uuid}`
        },
    },
    methods: {
        setInitialValue() {
            this.value = this.field.value  || null
            if(this.field.value){
                this.fetchResourceEntity('media',this.field.value).then(({url})=>{
                    this.preview = url
                })
            }
        },
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },
        handleChange(selected) {
            this.value = selected.id
            this.preview = selected.url
        },
        clearSelected() {
            this.value = null
            this.preview = null
        },
        openBrowser() {
            Nova.$emit(this.event)
        },
    },
    created(){
        this.$options.spinner = spinner
        this.$options.uuid = this.uuid()
        Nova.$on(`${this.event}:write`, this.handleChange)
    },
    beforeDestroy(){
        Nova.$off(`${this.event}:write`, this.handleChange)
    }
}
</script>
<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <template v-if="preview">
                <v-lazy-image
                    :src="preview"
                    :src-placeholder="$options.spinner"
                    class="shadow-md rounded mb-4 block"
                    :style="{
                        width: 'auto',
                        height: 'auto',
                        maxWidth: `${field.form_width}px`,
                        maxHeight: `${field.form_height}px`
                    }"
                />
                <button
                    type="button"
                    @click.prevent="clearSelected"
                    class="btn btn-default btn-primary inline-flex items-center relative">
                    Remove
                </button>
            </template>

            <button
                v-else
                type="button"
                @click.prevent="openBrowser"
                class="btn btn-default btn-primary inline-flex items-center relative">
                Select
            </button>
            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
            <media-browser :field-key="$options.uuid" :multiple="false"/>
        </template>
    </default-field>
</template>
