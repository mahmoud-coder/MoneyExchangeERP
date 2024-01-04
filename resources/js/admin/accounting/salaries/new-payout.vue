<template>
    <div>
    <div class="d-flex align-items-center justify-content-center mb-3 text-center">
        <span class="border px-3 py-2" v-html="hint.text" style="border-radius: 5px;" />
        <button v-if="hint.cell" class="btn btn-warning ms-1" @click="hint=default_hint()"> x </button>
    </div>
    <div>
        <button 
            class="btn btn-sm btn-outline-primary" 
            @click="additional_column_type = 'Earning'"
        >Add Additional Earning</button>
        <button 
            class="btn btn-sm btn-outline-primary"
            @click="additional_column_type = 'Deduction'"
        >Add Additional Deduction</button>
    </div>
    <transition>
    <AddAditionalColumn 
        v-if="additional_column_type" 
        :type="additional_column_type" 
        @added="x => new_column(x)"
        @canceled="additional_column_type = null"
    />
    </transition>
    <div :style="{width:'100%', overflowX:'auto'}">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>name</th>
                    <th>duties</th>
                    <th>set salary</th>
                    <th v-for="(column, i) in additional_earnings">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ column.name }}</span>
                            <button class="btn btn-sm btn-danger shadow-none py-1 px-2" @click="remove_additionals('earnings', column.name, i)">x</button>
                        </div>
                    </th>
                    <th>pesnion</th>
                    <th>NPD</th>
                    <th>income tax</th>
                    <th>insurance 19.5%</th>
                    <th v-for="(column, i) in additional_deductions">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ column.name }}</span>
                            <button class="btn bt-sm btn-danger shadow-none py-1 px-2" @click="remove_additionals('deductions', column.name, i)">x</button>
                        </div>
                    </th>
                    <th>total deducted</th>
                    <th>net pay</th>
                    <th>soc insurance</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(e, i) in employees" :key="e.id">
                    <td>{{ i + 1 }}</td>
                    <td>{{ e.name }} {{ e.surname }}</td>
                    <td>{{ e.duty }}</td>
                    <td>{{ e.salary }}</td>
                    <td v-for="column in additional_earnings">
                        <input type="text" v-model="additional_earnings_values[`${column.name}-${e.id}`]" class="form-control">
                    </td>
                    <td 
                        @click="hint = {text: e.calculations.pension.explain, cell: `pension${e.id}`}" 
                        :class="['cursor-pointer', {selected: hint.cell==`pension${e.id}`}]"
                    >{{ e.calculations.pension.value }}</td>
                    <td>{{ e.calculations.npd }}</td>
                    <td
                        @click="hint={text: e.calculations.tax.explain, cell:`tax${e.id}`}"
                        :class="['cursor-pointer', {selected: hint.cell==`tax${e.id}`}]"
                    >{{ e.calculations.tax.value }}</td>
                    <td
                        @click="hint={text: e.calculations.h_s_insurance.explain, cell:`h_s_insurance${e.id}`}"
                        :class="['cursor-pointer', {selected: hint.cell==`h_s_insurance${e.id}`}]"
                    >{{ e.calculations.h_s_insurance.value }}</td>
                    <td v-for="column in additional_deductions">
                        <input type="text" v-model="additional_deductions_values[`${column.name}-${e.id}`]" class="form-control">
                    </td>
                    <td>{{ total_deducted(e) }}</td>
                    <td>{{ e.calculations.net_pay }}</td>
                    <td
                        @click="hint={text: e.calculations.insurance.explain, cell:`insurance${e.id}`}"
                        :class="['cursor-pointer', {selected: hint.cell==`insurance${e.id}`}]"
                    >{{ e.calculations.insurance.value }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row mt-5">
        <div class="col-lg-4">
            <h6>Totals:</h6>
            <ul class="ms-2">
                <li><b>Net Pay:</b> {{ totals.net_pay }}</li>
                <li><b>Tax:</b> {{ totals.tax }}</li>
                <li>
                    <b>Insurance:</b>
                    <ul>
                        <li><b>Pension:</b> {{ totals.pension }}</li>
                        <li><b>Soc Insurance:</b> {{ totals.insurance }}</li>
                        <li><b>Insurance (19.5%):</b> {{ totals.h_s_insurance }}</li>
                        <li><b>Sum:</b>{{ totals.all_insurance }}</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="col-lg-8">
            <GeneralJournalEntry :totals="totals" />
        </div>
    </div>
    <div class="row my-2">
        <InputDate v-model="date" today label="Incurring Date:" hint="Salary Payout Date" class="col-lg-6"/>
        <InputText v-model="worked_days" label="Worked Days:" class="col-lg-3"/>
        <InputText v-model="worked_hours" label="Worked Hours:" class="col-lg-3"/>
    </div>
    <WaveSpinner v-if="saving" />
    <button v-else class="btn btn-primary mt-3" @click="save">Create the payout & the Journal Entry </button>
    </div>
</template>

<script setup>
import {ref, inject, watch} from "vue"
import AddAditionalColumn from "./add-aditional-column.vue"
import InputDate from "../../../components/input-date.vue"
import InputText from "../../../components/input-text.vue"
import GeneralJournalEntry from "./general-journal-entry.vue"
import WaveSpinner from "../../../components/spinners/wave-spinner.vue"

const saving = ref(false)

const hint = ref({})
const totals = ref({})
const employees = inject('employees')
const equations = inject('equations')
const additional_column_type = ref()

const additional_earnings = ref([])
const additional_earnings_values = ref({})

const additional_deductions = ref([])
const additional_deductions_values = ref({})

const date = ref()
const worked_days = ref()
const worked_hours = ref()
const default_hint = () => ({text:'Click at any pension, tax, or insurance, to show how it calculated.', cell:null})

watch([additional_earnings_values.value, additional_deductions_values.value], ()=>{
    for(var e of employees){
        e.calculations = {}
        e.calculations.earnings = earnings(e)
        e.calculations.pension = pension(e)
        e.calculations.tax = tax(e)
        e.calculations.insurance = insurance(e)
        e.calculations.h_s_insurance = h_s_insurance(e) // health insurance & state insurance (19.5%)
        e.calculations.net_pay = net_pay(e)
    }
    totals.value.net_pay = fix2( employees.reduce((a,b) => a + b.calculations.net_pay, 0) )
    totals.value.pension = fix2( employees.reduce((a,b) => a + b.calculations.pension.value, 0) )
    totals.value.insurance = fix2( employees.reduce((a,b) => a + b.calculations.insurance.value, 0) )
    totals.value.h_s_insurance = fix2(employees.reduce((a,b) => a + b.calculations.h_s_insurance.value, 0) )
    totals.value.all_insurance = fix2( totals.value.pension + totals.value.insurance + totals.value.h_s_insurance )
    totals.value.tax = fix2( employees.reduce((a,b) => a + b.calculations.tax.value, 0) )
    hint.value = default_hint()
}, {immediate: true})

function new_column(column){
    switch(additional_column_type.value){
        case 'Earning':
            additional_earnings.value.push(column)
            break
        case 'Deduction':
            additional_deductions.value.push(column)
            break
    }
    additional_column_type.value = null
}

function remove_additionals(type, head, index){
    const additionals = type == 'earnings' ? additional_earnings.value : additional_deductions.value
    const additionals_values = type  == 'earnings' ? additional_earnings_values.value : additional_deductions_values.value
    additionals.splice( index, 1 )
    Object.keys(additionals_values).forEach(x => {if(x.slice(0, x.lastIndexOf("-")) == head) delete additionals_values[x]})
}

/**
 * the Tax Base
 */
function earnings(e)
{
    let additional_earnings_sum = 
        Object.keys(additional_earnings_values.value)
        .filter(x=> {
            let id = x.slice( x.lastIndexOf("-")+1)
            let name = x.slice(0, x.lastIndexOf("-"))
            return id == e.id && additional_earnings.value.find(x => x.name == name).counted_in_tax_insurance
        })
        .reduce((a,b)=>a + +additional_earnings_values.value[b], 0)

    let additional_deductions_sum = 
        Object.keys(additional_deductions_values.value)
        .filter(x=> x.slice( x.lastIndexOf("-")+1) == e.id)
        .reduce((a,b)=>a + +additional_deductions_values.value[b], 0)
    e.calculations.additional_deductions = additional_deductions_sum

    return fix2(+e.salary + additional_earnings_sum - additional_deductions_sum)
}

function pension(e)
{
    if(e.pension == 0){
        return {
            value: 0,
            explain: decorate_hint(`The pension for <b>${e.name}</b>, is 0%, there are no pension`)
        }
    }
    let value = fix2(e.calculations.earnings * e.pension / 100)
    return {
        value,
        explain: decorate_hint(`(Total Earnings: ${e.calculations.earnings}) x (Pension: ${e.pension}%) = ${value}`)
    } 
}

function tax(e)
{
  
    const tax = 0.2
    const ratio_str = (x) => x*100+'%'
    // if there are no NPD (tax-free amount)
    if(! e.apply_tax_free_amount){
        e.calculations.npd = 0
        let value = fix2(e.calculations.earnings * tax)
        return {
            value,
            explain: decorate_hint(`The <b>${e.name}'s</b> NPD doesn't applied, <br> then the tax is ${ratio_str(tax)} of (total earnings: ${e.calculations.earnings}) = ${value}`)
        }
    }

    // if we got NPD
    for( const equation of equations.value){
        let earnings = e.calculations.earnings
        let min = equation.range[0]
        let max = equation.range.length == 1 ? Infinity : equation.range[1]
        if(earnings > min && earnings <= max){
            let npd = eval(
                `(function(){
                    var earnings = ${earnings};
                    var npd = fix2(${equation.npd});
                    return npd;
                })()`
            )
            if(npd < 0) npd = 0
            e.calculations.npd = npd
            let value = fix2(tax * (earnings - npd))
            let explain = equation.explain
                .replaceAll('earnings', earnings)
                .replaceAll('npd', npd)
                .replaceAll('tax_rate', ratio_str(tax))
                .replaceAll('value', value)
            return {
                value,
                explain: decorate_hint(explain)
            }
        }
    }
}

function insurance(e)
{
    let value = fix2(e.calculations.earnings * e.social_insurance / 100)
    return {
        value,
        explain: decorate_hint(`(Toal earning: ${e.calculations.earnings}) x ${e.social_insurance}% = ${value}`)
    }
}

function h_s_insurance(e)
{
    let value = fix2(e.calculations.earnings * 0.195)
    return {
        value,
        explain: decorate_hint(`(Total earning: ${e.calculations.earnings}) x 19.5% = ${value}`)
    }
}

function total_deducted(e)
{
    return fix2(
        e.calculations.pension.value 
        + e.calculations.tax.value 
        + e.calculations.h_s_insurance.value
        + e.calculations.additional_deductions
    )
}

function net_pay(e)
{
    let additional_earnings_not_counted_in_tax_insurance = 
        Object.keys(additional_earnings_values.value)
        .filter(x => {
            let id = x.slice( x.lastIndexOf("-")+1)
            let name = x.slice(0, x.lastIndexOf("-"))
            return id == e.id && !additional_earnings.value.find(x => x.name == name).counted_in_tax_insurance
        })
        .reduce((a,b)=> +additional_earnings_values.value[b] + a, 0)

    return fix2(
        e.calculations.earnings
        + additional_earnings_not_counted_in_tax_insurance
        - e.calculations.pension.value 
        - e.calculations.tax.value 
        - e.calculations.h_s_insurance.value
        )
}

function save()
{
    saving.value = true
    jQuery.ajax({
        method: 'POST',
        url: '/admin/wages-payout',
        data:{
            net_pay: totals.value.net_pay,
            tax: totals.value.tax,
            soc_insurance: totals.value.insurance,
            insurance_h_s: totals.value.h_s_insurance,
            pension: totals.value.pension,
            insurance_sum: totals.value.all_insurance,
            equations: JSON.stringify(equations.value),
            additional_earnings: JSON.stringify(additional_earnings.value),
            additional_deductions : JSON.stringify( additional_deductions.value ),
            incurred_at: date.value,
            worked_days: worked_days.value,
            worked_hours: worked_hours.value,
            details: {
                employees: employees.map(e => ({
                    employee_id: e.id,
                    salary: e.salary,
                    pension: e.calculations.pension.value,
                    npd: e.calculations.npd,
                    tax: e.calculations.tax.value,
                    insurance: e.calculations.insurance.value,
                    h_s_insurance: e.calculations.h_s_insurance.value,
                    additional_earnings_values: JSON.stringify(
                        Object.keys(additional_earnings_values.value)
                        .filter(k => k.slice(k.lastIndexOf("-") +1) == e.id)
                        .reduce((obj,k) => (obj[k.slice(0, k.lastIndexOf("-"))] = additional_earnings_values.value[k], obj) ,{})
                    ),
                    additional_deductions_values: JSON.stringify(
                        Object.keys(additional_deductions_values.value)
                        .filter(k => k.slice(k.lastIndexOf("-") +1) == e.id)
                        .reduce((obj,k) => (obj[k.slice(0, k.lastIndexOf("-"))] = additional_deductions_values.value[k], obj), {})
                    ),
                    total_deducted: total_deducted(e),
                    net_pay: e.calculations.net_pay
                }))
            }
        },
        success(res){
            if(res.success){
                toastr.success(res.message, 'Success')
            }else{
                toastr.error(res.message, "Error")
            }
        },
        error(xhr){
            if(xhr.status == 422){
                toastr.error(Object.values(xhr.responseJSON.errors)[0][0], "Error")
            }else{
                toastr.error(xhr.responseJSON.message, "Error")
            }
        },
        complete(){
            saving.value = false
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}

function decorate_hint(text){
    return text
            .replace(/[\w\s_-]+:/g, '<b>$&</b>')
            .replace(/\d+\.?\d*%?/g, '<span class="text-primary">$&</span>')
            .replaceAll(';', '<br>')
}
</script>

<style lang="scss" scoped>
td, th{
    text-wrap: nowrap;
}
input[type=text]{
    min-width: 100px;
    height: 24px;
}
td.selected{
    position: relative;
    &::after{
        content: '';
        position: absolute;
        border:3px solid #7367f0 ;
        left:0;
        right: 0;
        top:0;
        bottom: 0;
    }
}
</style>