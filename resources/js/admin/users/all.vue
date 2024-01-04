<template>
<PaginateTable title="All Users" headers="id, name, eMail, team, joined on" url-segment="users">
    <template #default="{resource:u}">
        <td>{{ u.id }}</td>
        <td>{{ u.name }}</td>
        <td>{{ u.email }}</td>
        <td>{{ u.role_text }}</td>
        <td>{{ u.joining_date }}</td>
    </template>
</PaginateTable>
</template>

<script setup>
import PaginateTable from "../../components/paginate-table.vue"
</script>