<template>
    <div v-if="progress" class="d-flex flex-column align-items-center">
        <p>ANALYSIS...</p>
        <waveSpinner />
    </div>
    <div v-else>
        <p>
            <strong>Sell orders count: </strong> {{ statistics.sellCount }}
        </p>
        <p>
            <strong>Buy orders count: </strong> {{ statistics.buyCount }}
        </p>
        <div class="d-flex justify-content-center">
            <waveSpinner v-if="creating"/>
            <button v-else class="btn btn-outline-primary" @click="create">Create Transactions</button>
        </div>
    </div>
</template>

<script>
import {onMounted, ref, inject} from "vue"
import waveSpinner from "./../../../components/spinners/wave-spinner.vue"
import selected_currency from "./selected_currency"
export default {
    name:"analysis & create",
    components:{waveSpinner},
    setup(){
        const progress = ref(true)
        const creating = ref(false)
        const statistics = ref()
        const currentStep = inject("current-step")
        onMounted(()=>{
            jQuery.ajax({
                method:'POST',
                url:'/admin/orders/upload/analysis',
                success(res){
                    statistics.value = res
                    progress.value = false
                },
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
        })
        function create()
        {
            creating.value = true
            jQuery.ajax({
                method:'POST',
                url:'/admin/orders/create-from-uploaded-sheet',
                data:{currency_id: selected_currency.value.id},
                success(){
                    toastr.success('Created', 'Success')
                    currentStep.value = 'info'
                },
                error(xhr){
                    toastr.error(xhr.responseJSON.message, "Error")
                    console.log(xhr)
                },
                complete(){
                    creating.value = false
                },
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })

        }

        return {progress,creating, statistics, create, selected_currency}
    }
}
</script>