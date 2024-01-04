<template>
    <div class="w-100 overflow-auto">
        <h6>General Journal Entry:</h6>
        <div>
            <inputRadio v-model="lang" :list="{en:'English', lt:'Lithuanian'}" />
        </div>
        <table class="table table-sm table-bordered table-active">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Content</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody v-if="forPaying">
                <!-- Debit - payable salary -->
                <tr>
                    <td>{{ accounts.payable_salary.code }}</td>
                    <td>{{ t(accounts.payable_salary.name) }}</td>
                    <td>{{ totals.net_pay }}</td>
                    <td></td>
                </tr>

                <!-- debit - payable insurance -->
                <tr>
                    <td>{{ accounts.payable_insurance.code }}</td>
                    <td>{{ t(accounts.payable_insurance.name) }}</td>
                    <td>{{ totals.all_insurance }}</td>
                    <td></td>
                </tr>

                <!-- debit - payable tax -->
                <tr>
                    <td>{{ accounts.payable_employees_tax.code }}</td>
                    <td>{{ t(accounts.payable_employees_tax.name) }}</td>
                    <td>{{ totals.tax }}</td>
                    <td></td>
                </tr>

                <!-- credit - cash -->
                <tr>
                    <td>{{ accounts.cash.code }}</td>
                    <td>{{ t(accounts.cash.name) }}</td>
                    <td></td>
                    <td>{{ wages() }}</td>
                </tr>

            
            </tbody>

            <!-- else if for-incurring -->
            <tbody v-else>
                <!-- Debit - Employee wages & related costs -->
                <tr>
                    <td>{{ accounts.wages.code }}</td>
                    <td>{{ t(accounts.wages.name) }}</td>
                    <td>{{ wages() }}</td>
                    <td></td>
                </tr>
        
                <!-- credit - payable salary  -->
                <tr>
                    <td>{{ accounts.payable_salary.code }}</td>
                    <td>{{ t(accounts.payable_salary.name) }}</td>
                    <td></td>
                    <td>{{ totals.net_pay }}</td>
                </tr>
        
                <!-- credit - payable insurance -->
                <tr>
                    <td>{{ accounts.payable_insurance.code }}</td>
                    <td>{{ t(accounts.payable_insurance.name) }}</td>
                    <td></td>
                    <td>{{ totals.all_insurance }}</td>
                </tr>
        
                <!-- credit - payable tax -->
                <tr>
                    <td>{{ accounts.payable_employees_tax.code }}</td>
                    <td>{{ t(accounts.payable_employees_tax.name) }}</td>
                    <td></td>
                    <td>{{ totals.tax }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import inputRadio from "../../../components/radio-vertical-list.vue"

export default {
    name:"Salary General Journal Entry",
    components:{inputRadio},
    props:{
        forPaying: Boolean,
        totals: Object
    },
    inject:["accounts"],
    data: () => ({
        lang: 'en'
    }),
    methods:{
        t(x){
            return JSON.parse(x)[this.lang]
        },
        wages(){
            return fix2(+this.totals.net_pay + +this.totals.all_insurance + +this.totals.tax)
        }
    }
}
</script>