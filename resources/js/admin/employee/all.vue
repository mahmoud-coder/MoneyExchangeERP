<template>
<PaginateTable title="All Employees" headers="id, name, set sallary, pension, soc insurance, joined at" url-segment="employees">
    <template #default="{resource:d}">
        <td>{{ d.id }}</td>
        <td>{{ d.name }} {{d.surname}}</td>
        <td>{{ d.salary }}</td>
        <td>{{ d.pension }}%</td>
        <td>{{ d.social_insurance }}%</td>
        <td>{{ d.joined_at.slice(0,10) }}</td>
    </template>
</PaginateTable>
</template>

<script setup>
import PaginateTable from "../../components/paginate-table.vue"
</script>
