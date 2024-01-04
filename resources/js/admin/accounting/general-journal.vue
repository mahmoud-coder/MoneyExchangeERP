<template>
<transition mode="out-in">
<!-- ALL ENTRIES from DB -->
<BasicCard title="Entries" v-if="show_entries_card">
    <button class="btn btn-outline-primary mb-4" @click="show_entries_card=false"> 
        <i class="ti ti-arrow-narrow-left"></i>
    </button>
    <div v-for="(entry, i) in entries" class="row my-4">
        <div class="col-9">
            <div class="entry">
                <div class="date my-1"><b>Date</b> {{ entry.date }} </div>
                <table class="table table-hover table-bordered table-sm">
                    <tbody>
                    <tr v-for="row in entry.details">
                        <td class="account-code">{{row.account.code}}</td>
                        <td class="account-name">{{ t(row.account.name) }}</td>
                        <template v-if="row.type == 'Debit'">
                            <td class="amount">{{row.amount}}</td>
                            <td> </td>
                        </template>
                        <template v-else>
                            <td> </td>
                            <td class="amount">{{row.amount}}</td>
                        </template>
                    </tr>
                    </tbody>
                </table>
                <div v-if="entry.notes" class="my-1">
                    <b>Notes:</b> {{entry.notes}}
                </div>
                <div v-if="entry.itemable_type == 'App\\Models\\Transaction'" class="my-1">
                    Created by Invoice number: <a target="__blank" :href="`/admin/transaction/${entry.itemable_id}`">MBTR{{ entry.itemable_id }}</a>
                </div>
                <div v-if="entry.itemable_type == 'App\\Models\\WagesPayout'" class="my-1">
                    Incurring Wages Payout <a target="__blank" :href="`/admin/wages-payout/${entry.itemable_id}/pdf?language=en`">(show the wages PDF)</a>
                </div>
                <div v-if="entry.itemable_type == 'App\\Models\\WagesPaid'" class="my-1">
                    Paying Wages Payout <a target="__blank" :href="`/admin/paid_wages/${entry.itemable_id}/pdf?language=en`">(show the wages PDF)</a>
                </div>
            </div>
        </div>
        <div class="align-items-center justify-content-end col-3 d-flex">
            <button class="btn bt-sm btn-outline-danger" @click="delete_entry(entry.id, i)">
                <i class="ti ti-trash"></i> 
                <span class="mt-1 ms-2">Delete This Entry</span>
            </button>
        </div>
    </div>
</BasicCard>

<!-- THE MAIN CARD -->
<TabCard v-else tabs="New Entry, All Entries">
    <div key="new-entry">
        <InputDate 
            class="mt-3"
            label="Date"
            v-model = "date"
            hint="The manual journal entry date"
            key="fofo"
        />
        <InputText
            class="mt-3"
            label="Notes"
            hint="Put a notes on this entry"
            v-model="notes"
        />
        <InputVRadio v-model="en_lt" :list="{en:'English', lt:'Lithuanian'}" label="Accounts names displaying language" class="mt-3 bg-lightest border mt-3 p-3 rounded-3 text-muted" />
        <div class="divider">
            <div class="divider-text">  Journal Entry </div>
        </div>
        <transition>
        <table v-if="entry.length" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Code</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th class="text-center bg-lightest"><span class="text-primary">Remove</span></th>
                </tr>
            </thead>
            <tbody>
                <TransitionGroup>
                <tr v-for="(r, i) in entry" :key="r.index">
                    <td>{{  t(r.account.name) }}</td>
                    <td>{{ r.account.code }}</td>
                    <template v-if=" r.debit_credit == 'debit' ">
                        <td>{{ r.amount }}</td>
                        <td></td>
                    </template>
                    <template v-else>
                        <td></td>
                        <td>{{ r.amount }}</td>
                    </template>
                    <td class="text-center bg-lightest">
                        <button class="btn btn-warning btn-sm" @click="entry.splice(i,1)"> <i class="ti ti-minus"></i> </button>
                    </td>
                </tr>
                </TransitionGroup>
            </tbody>
        </table>
        </transition>
        <div class="row mt-5">
            <div class="col-5">
                <AccountSelector :accounts="accounts" :lang="en_lt" v-model="account"/>
            </div>
            <div class="col-3">
                <InputText
                    label="Amount"
                    hint="You can us account code instead of its name"
                    v-model="account_amount"
                />
            </div>
            <div class="col-2 rounded-1 border d-flex justify-content-center align-items-center">
                <div>
                    <div>Debit Or Credit?</div>
                    <div class="form-check ms-3 mt-2">
                        <input type="radio"  class="form-check-input" value="debit" id="new-entry-debit-type" v-model="account_debit_credit">
                        <label class="form-check-label" for="new-entry-debit-type">Debit</label>
                    </div>
                    <div class="form-check ms-3">
                        <input type="radio"  class="form-check-input" value="credit" id="new-entry-credit-type" v-model="account_debit_credit">
                        <label class="form-check-label" for="new-entry-credit-type">Credit</label>
                    </div>
                </div>
            </div>
            <div class="col-2 justify-content-center d-flex align-items-center">
                <button class="btn btn-primary" @click="add"> <i class="ti ti-plus"></i>  </button>
            </div>
            <div class="divider">
                <div class="divider-text">  Saving </div>
            </div>
            <div class="my-3">
                <button class="btn btn-primary" @click="save_entry"  :disabled="saving">
                    <WaveSpinner v-if="saving" color="white" /> <span v-else>Save</span>
                </button>
            </div>
        </div>
    </div>

    <div key="all-entries">
        <InputDate class="mt-3" v-model="show_from" label="From:"/>
        <InputDate class="mt-3" v-model="show_to" label="To:"/>
        <div class="mt-3">
            Language:
            <div class="mt-2 ms-3 form-check">
                <input type="radio" class="form-check-input" value="en" v-model="en_lt" id="language-english">
                <label for="language-english" class="form-check-label">English</label> 
            </div>
            <div class="ms-3 form-check">
                <input type="radio" class="form-check-input" value="lt" v-model="en_lt" id="language-lithuanian">
                <label for="language-lithuanian" class="form-check-label">Lithuanian</label> 
            </div>
        </div>
        <div class="mt-4 pt-3 border-top">
            <button @click="show_entries" class="btn btn-outline-primary mt-3">Show</button>
            <a target="__blank" :href="`/admin/accounting/general-journal/entries/${query_string('pdf')}`" class="btn btn-outline-danger mt-3 ms-2">PDF</a>
            <a :href="`/admin/accounting/general-journal/entries/${query_string('excel')}`" class="btn btn-outline-success mt-3 ms-2">Excel</a>
        </div>
    </div>
</TabCard>
</transition>
</template>

<script setup>
import {ref, computed} from 'vue'
import BasicCard from "../../components/basic-card.vue"
import TabCard from "../../components/tab-card.vue"
import InputDate from "../../components/input-date.vue"
import InputText from "../../components/input-text.vue"
import InputVRadio from "../../components/radio-vertical-list.vue"
import AccountSelector from "../../components/account-selector.vue"
import WaveSpinner from "../../components/spinners/wave-spinner.vue"
import {destroy_resource} from "../../utils.js"

const props = defineProps(['accounts'])

const show_entries_card = ref(false)

// NEW ENTRY
const date = ref((new Date()).toISOString().split('T')[0])
const notes = ref()
const entry = ref([])
const account = ref()
const account_amount = ref()
const account_debit_credit = ref('debit')
const saving = ref(false)

// ALL ENTRIES
const show_from = ref(null)
const show_to = ref((new Date()).toISOString().split('T')[0])
const entries = ref()

// both 'ALL ENTRIES' and "NEW ENTREY"
const en_lt = ref('en') // en = account name in English, lt = in Lithuanian

const t = x => JSON.parse(x)[en_lt.value == 'en' ? 'en':'lt']

function query_string(as){
    var qs = `?as_${as}&language=${en_lt.value}`
    if(show_from.value) qs += `&from=${show_from.value}`
    if(show_to.value) qs += `&to=${show_to.value}`
    return qs
}
function add(){
    entry.value.push({account:account.value, amount:account_amount.value, debit_credit: account_debit_credit.value})
}
function save_entry(){
    saving.value = true
    let payload = {
        date: date.value,
        notes: notes.value,
        details : entry.value.map(e => ({account_id: e.account.id, amount: e.amount, type: e.debit_credit}) )
    }
    jQuery.ajax({
        url:'/admin/ajax/general-journal',
        method: 'POST',
        data: payload,
        success(res){
            toastr.success(res.message, 'Success')
        },
        error(xhr){},
        complete(){
            saving.value = false
        },
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}

function show_entries()
{
    jQuery.ajax({
        url:'/admin/accounting/general-journal/entries',
        method: 'GET',
        data: {
            from: show_from.value,
            to: show_to.value,
            language: en_lt.value
        },
        success(res){
            entries.value = res
            show_entries_card.value = true
        }
    })
}

function delete_entry(id, index)
{ 
    destroy_resource(
        `Entity# ${id}`, 
        `/admin/accounting/general-journal/entries/${id}`,
        null,
        ()=>{entries.value.splice(index, 1)},
        null
    )
}
</script>