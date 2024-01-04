<template>
    <h6>Uploading</h6>
    <p class="text-secondary text-decoration-underline">
        Choose the crypto currency & upload the transaction sheet (.xlsx file).
    </p>
    <select-coin :coins="currencies" @selected="c => selected_currency = c" />
    <div class="text-center p-3" v-show="selected_currency">{{ `( ${selected_currency?.symbol} )` }}</div>
    <div id="upload-transactions-sheet" class="dropzone"></div>

    <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-outline-primary btn-sm" @click="currentStep = 'info'">Back</button>
        <button class="btn btn-outline-primary btn-sm" :disabled="!uploaded || !selected_currency" @click="currentStep = 'analysis'">Next</button>
    </div>
</template>

<script>
import selectCoin from "../../../components/select-coin.vue"
import selected_currency from "./selected_currency"

import {ref, inject, onMounted} from "vue"

export default {
    components:{selectCoin},
    emits:['currency_selected'],
    setup(){
        const uploaded = ref(false)
        const currencies = inject("currencies")
        const currentStep = inject("current-step")

        onMounted(()=>{
            const dz = new Dropzone("#upload-transactions-sheet", {
                url: '/admin/orders/upload/sheet',
                params(){
                    return {
                        '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            })
            dz.on("success", res => {
                toastr.success('The sheet have been uploaded', 'Success')
                uploaded.value = true
            })
        })

        return {selected_currency, currencies, currentStep, uploaded}
    }
}
</script>

<style scoped>
#upload-transactions-sheet{
    min-height: 150px;
    border:gray 1px dashed;
    border-radius: 8px;
}
</style>