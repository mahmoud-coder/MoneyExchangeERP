<template>
    <BasicCard title="Date Range" class="mb-2">
        <p><b>Note:</b> if the selected date range has a closing journal entry, then it will start from the last closing entry</p>
        <div class="row my-3">
            <InputDate class="col-6" v-model="from" label="From:" hint="Leave it empty to start from the first financial transaction."/>
            <InputDate class="col-6" v-model="to" label="To:" hint="Leave it empty to end at the last financial transaction."/>
        </div>
        <button class="btn btn-primary float-end" @click="show"> <i class="ti ti-filter me-1"></i> Apply</button>
    </BasicCard>

    <WaveSpinner v-if="progress" class="m-auto" />
    <PLstatement v-else :revenues="revs" :expenses="exps" />
</template>

<script>
import {ref} from 'vue'
import PLstatement from './PLStatement.vue'
import InputDate from '../../../components/input-date.vue'
import BasicCard from '../../../components/basic-card.vue'
import WaveSpinner from '../../../components/spinners/wave-spinner.vue'

export default {
    name: 'Profit & Loss Statment',
    props: ['revenues', 'expenses'],
    components:{PLstatement, InputDate, BasicCard, WaveSpinner},
    setup(props){
        const revs = ref(props.revenues)
        const exps = ref(props.expenses)
        const from = ref(null)
        const to = ref(null)
        const progress = ref(false)

        function show()
        {
            progress.value = true
            jQuery.ajax({
                url: '/admin/ajax/revenues_and_expenses' ,
                method:'GET',
                data: {
                    from: from.value,
                    to: to.value
                },
                success(res){
                    revs.value = res.Revenue
                    exps.value = res.Expense
                },
                complete(){
                    progress.value = false
                }
            })
        }

        return {revs, exps, from, to, show, progress}
    }
}
</script>