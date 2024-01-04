<template>
<BasicCard title = "P & L statement">
    <div class="row">
        <InputSwitch class="col-2" label="Include Tax?" v-model="include_tax" />
        <transition>
        <InputText v-if="include_tax" label="Tax Ratio" v-model="tax_ratio"  hint="example: 10 , for 10%" class="col-4"/>
        </transition>
    </div>
    <table class="mt-4 table table-bordered table-hover table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Article</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <template v-if="sales_revenue">
                <tr>
                    <td>1</td>
                    <td>{{ t(sales_revenue.name) }}</td>
                    <td> </td>
                    <td>{{ sales_revenue.sum }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>{{ t(cost_of_sold_goods.name) }}</td>
                    <td>{{ cost_of_sold_goods.sum }}</td>
                    <td> </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="label">Gross Profit (Loss):</td>
                    <td></td>
                    <td>{{gross_profit}}</td>
                </tr>
            </template>
            <template v-else>
                <tr>
                    <td>1</td>
                    <td>Sales Revenue</td>
                    <td> </td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Cost Of sold goods</td>
                    <td>0</td>
                    <td> </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="label">Gross Profit (Loss):</td>
                    <td></td>
                    <td>0</td>
                </tr>

            </template>
            
            <tr>
                <td>4</td>
                <td class="label">Expenses:</td>
                <td></td>
                <td></td>
            </tr>
            <tr v-for="(a, index) in operating_expenses">
                <td>{{ 5 + index}}</td>
                <td>{{ t(a.name) }}</td>
                <td>{{ a.sum }}</td>
                <td></td>
            </tr>
            <template v-if="include_tax">
                <tr>
                    <td>{{ 5 + operating_expenses.length }}</td>
                    <td class="label">Profit (Loss) before tax</td>
                    <td> </td>
                    <td>{{ profit_before_tax }}</td>
                </tr>
                <tr>
                    <td>{{ 6 + operating_expenses.length}}</td>
                    <td>Income Tax</td>
                    <td>{{ tax }}</td>
                    <td> </td>
                </tr>
                <tr>
                    <td>{{ 7 + operating_expenses.length}}</td>
                    <td class="label">Net Profit (less)</td>
                    <td> </td>
                    <td>{{ profit_after_tax }}</td>
                </tr>
            </template>
            <tr v-else>
                <td>{{ 5 + operating_expenses.length }}</td>
                <td class="label">Profit (Loss): </td>
                <td> </td>
                <td>{{ profit_before_tax }}</td>
            </tr>
        </tbody>
    </table>
</BasicCard>
</template>

<script setup>
import {ref, onBeforeMount, computed} from 'vue'
import BasicCard from "../../../components/basic-card.vue"
import InputSwitch from "../../../components/input-switch.vue"
import InputText from "../../../components/input-text.vue"

const props = defineProps(['revenues', 'expenses'])

const lang = ref('en')
const include_tax = ref(false)
const tax_ratio = ref(0)


const sales_revenue = ref()
const cost_of_sold_goods = ref()
const gross_profit = computed(()=> sales_revenue.value ? pf(sales_revenue.value.sum - cost_of_sold_goods.value.sum) : 0)
const operating_expenses = ref([])
const profit_before_tax = computed(() => gross_profit.value - operating_expenses.value.reduce((a,b) => a + b.sum, 0))
const tax = computed(()=> profit_before_tax.value <= 0 ? 0 : pf(profit_before_tax.value * tax_ratio.value / 100) )
const profit_after_tax = computed(()=> pf(profit_before_tax.value - tax.value))

const en = x => JSON.parse(x).en
const lt = x => JSON.parse(x).lt
const t = x => lang.value == 'en' ? en(x) : lt(x)
const pf = x => parseFloat(x.toPrecision(5))

onBeforeMount(()=>{
    if(props.revenues) sales_revenue.value = props.revenues.find(a => a.type == 134)
    if(props.expenses){
        cost_of_sold_goods.value = props.expenses.find(a => a.type == 135)
        operating_expenses.value = props.expenses.filter(a => a.type != 135)
    }
})
</script>
<style scoped>
.label{
    font-weight: bold;
    text-decoration: underline;
}
</style>