<template>
    <div>
    <Transition mode="in-out">
        <Paying v-if="paying_wages_payout" :payout="paying_wages_payout" @canceled="paying_wages_payout=null" @created="paying_wages_payout=null"/>
        <PaginateTable v-else title="All Wages Payout" headers="id, incurred date, paid status, total net pay" url-segment="wages-payout" disable-edit>
            <template #default="{resource: payout}">
                <td>{{ payout.id }}</td>
                <td>{{ payout.incurred_at }}</td>
                <td>{{ payout.paid_status }} </td>
                <td>{{ payout.net_pay }}</td>
            </template>

            <template #actions="{resource: payout}">
                <a class="dropdown-item" target="_blank" :href="`/admin/wages-payout/${payout.id}/pdf?language=en`"><i class="ti ti-eye me-1"></i>View - English</a>
                <a class="dropdown-item" target="_blank" :href="`/admin/wages-payout/${payout.id}/pdf?language=lt`"><i class="ti ti-eye me-1"></i>View - Lithuanian</a>
                <button v-if="payout.paid_status !== 'all paid'" class="dropdown-item" @click="paying_wages_payout = payout"><i class="ti ti-credit-card me-1"></i>Pay</button>
            </template>
        </PaginateTable>
    </Transition>
    </div>
</template>

<script setup>
import {ref} from "vue"
import PaginateTable from "../../../../components/paginate-table.vue"
import Paying from "./paying.vue"

const paying_wages_payout = ref()
</script>