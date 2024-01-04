import "../../scss/components.scss"
import { createApp, ref} from 'vue'
import Individual from './customers/individual.vue'
import Entity from './customers/entity.vue'
import AllCustomer from './customers/all.vue'
import CustomerDetails from './customers/details.vue'
import Order from './orders/order.vue'
import UploadTransactions from './transactions/upload/index.vue'
import Monitor from './transactions/monitor.vue'
import Turnover from './transactions/turnover.vue'
import Users from './users/all.vue'
import EmployeeCreateEdit from './employee/employee-create-edit.vue'
import Employees from './employee/all.vue'
import COA from './accounting/coa.vue'
import GeneralJournal from './accounting/general-journal.vue'
import Ledger from './accounting/ledger.vue'
import Expense from './expenses/add.vue'
import ExpensesList from './expenses/all.vue'
import PLStatement from './accounting/profit-loss-statement/index.vue'
import Reports from './reports/index'
import SalariesPayouts from './accounting/salaries-payouts.vue'
import Currency from './currencies/index.vue'
import PaymentMethod from './payment-methods/index.vue'

toastr.options.progressBar = true
toastr.options.newestOnTop = true

window.fix2 = x => parseFloat( x.toFixed(2) )
window.fix3 = x => parseFloat( x.toFixed(3) )
window.fix4 = x => parseFloat( x.toFixed(4) )

var el, app
if(el = document.getElementById('customers-individual')){
    app = createApp(Individual, {
        type: el.dataset.type ?? 'new'
    })
}else if(el = document.getElementById('customers-entity')){
    app = createApp(Entity,{
        type: el.dataset.type ?? 'new'
    })
}else if(el = document.getElementById('customers-all') ){
    app = createApp(AllCustomer)
}else if(el = document.getElementById('customers-details')){
    app = createApp(CustomerDetails, {id: el.dataset.customerId})
}else if(el = document.getElementById('order-component')){
    app = createApp(Order, {
        type: el.dataset.type,
        useStoredExchangeRate: el.dataset.useStoredExchangeRate !== undefined,
        useStoredFees: el.dataset.useStoredFees !== undefined
    })
}else if(el = document.getElementById('component-turnover')){
    app = createApp(Turnover)
}else if(el = document.getElementById('transaction-component')){
    app = createApp(Monitor, {
        type: el.dataset.type,
        showMiniSummary: el.dataset.showMiniSummary !== undefined
    })
}else if(el = document.getElementById('upload-transactions')){
    app = createApp(UploadTransactions)
    app.provide('currencies', JSON.parse(el.dataset.currencies))
}else if(el = document.getElementById('users-all')){
    app = createApp(Users)
}else if(el = document.getElementById('employee-create-edit')){
    app = createApp(EmployeeCreateEdit, {type: el.dataset.type , employee: el.dataset.employee})
}else if(el = document.getElementById('employees-all')){
    app = createApp(Employees)
}else if(el = document.getElementById('accounting-coa')){
    app = createApp(COA, {
        accounts: JSON.parse(el.dataset.accounts)
    })
}else if(el = document.getElementById('accounting-general-journal')){
    app = createApp(GeneralJournal, {
        accounts: JSON.parse(el.dataset.accounts)
    })
}else if(el = document.getElementById('accounting-ledger')){
    app = createApp(Ledger, {
        accounts: JSON.parse(el.dataset.accounts)
    })
}else if(el = document.getElementById('expenses')){
    app = createApp({components:{Expense}})
}else if(el = document.getElementById('accounting-reports')){
    app = createApp(Reports)
}else if(el = document.getElementById('expenses-list')){
    app = createApp({components:{ExpensesList}})
}else if(el = document.getElementById('accounting-profit-loss-statement')){
    app = createApp(PLStatement, {
        revenues: el.dataset.revenues ? JSON.parse(el.dataset.revenues) : null,
        expenses: el.dataset.expenses ? JSON.parse(el.dataset.expenses) : null
    })
}else if(el = document.getElementById('salaries-payouts')){
    app = createApp(SalariesPayouts)
    app.provide('employees', JSON.parse(el.dataset.employees))
    app.provide('accounts', JSON.parse(el.dataset.accounts))
    app.provide('equations', ref(JSON.parse(el.dataset.equations)))
}else if(el = document.getElementById('currencies')){
    app = createApp(Currency)
}else if(el = document.getElementById('payment-methods')){
    app = createApp(PaymentMethod)
}
app.mount(el)
