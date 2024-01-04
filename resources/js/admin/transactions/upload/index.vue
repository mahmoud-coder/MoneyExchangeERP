<template>
    <BasicCard title="Upload Transactions Excell sheet">
        <uploadInfo v-if="current_step == 'info'"/>
        <uploading v-else-if="current_step == 'uploading'"/>
        <analysis v-else-if="current_step == 'analysis'" />
    </BasicCard>
</template>

<script>
import BasicCard from "../../../components/basic-card.vue"
import uploadInfo from "./info.vue"
import uploading from "./uploading.vue"
import analysis from "./analysis.vue"

import {ref, provide} from 'vue'

export default {
    name: 'transactions-upload',
    components:{BasicCard, uploadInfo, uploading, analysis},
    setup(){
        const current_step = ref("info") // info | uploading | analysis
        provide("current-step", current_step)
        return {current_step}
    }
}
</script>