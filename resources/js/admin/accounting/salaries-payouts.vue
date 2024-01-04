<template>
<TabCard tabs="New Salaries Payout, All Salaries Payouts, Settings">
    <NewPayout />
    <AllSalaryPayout />
    <SalarySettings />
</TabCard>
</template>

<script setup>
import TabCard from "../../components/tab-card.vue"
import NewPayout from "./salaries/new-payout.vue"
import AllSalaryPayout from "./salaries/wages-payout-list/all-salary-payout.vue"
import SalarySettings from "./salaries/salary-settings.vue"
</script>