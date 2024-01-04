<template>
    <label style="margin-bottom: 4px;">{{ props.label }}</label>
    <div class="root" :class="{active}" @keydown="handle_keydown" tabindex="-1" ref="root">
        <div class="select form-control" @click="active = !active">
            <span>{{ selected_account ? t(selected_account.name) : placeHolder}}</span>
            <i class="ti ti-chevron-down"></i>
        </div>
        <div :class="['result', dropdirection()]">
            <div class="search">
                <i class="ti ti-search"></i>
                <input type="text" v-model="search_by_code" @keyup="filter_by_code" placeholder="by code" class="code form-control">
                <input type="text" v-model="search_by_name" @keyup="filter_by_name" placeholder="by name" class="name form-control">
            </div>
            <div class="options">
                <div v-for="account in accounts_state_filtered" @click="select(account)" :class="['option', {selected: account.id == selected_account?.id}]" :key="account.id">
                    <div class="code">{{ account.code }}</div>
                    <div class="name">{{ t(account.name) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onBeforeMount, onMounted} from 'vue'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    modelValue:Object,
    label:{
        type: String,
        default: 'Account'
    },
    lang:{
        type: String,
        default: 'en'
    },
    accounts: {
        type:[String, Object]
    },
    placeHolder:{
        type:String,
        default: 'Choose an account'
    }
})

const active = ref(false)
const accounts_state = ref()
const accounts_state_filtered = ref()
const selected_account=ref(props.modelValue)
const search_by_code = ref()
const search_by_name = ref()

const root=ref()

function handle_keydown(e)
{
    if(e.key == "Escape" || e.key =="Enter"){
        active.value = false
        if(e.key == 'Enter' && selected_account.value) emit('update:modelValue', selected_account.value)
    }else if(e.key == "ArrowDown" || e.key == "ArrowUp"){
        e.preventDefault()
        let i = accounts_state_filtered.value.indexOf(selected_account.value)
        selected_account.value = accounts_state_filtered.value[e.key == "ArrowDown" ? ++i : --i]
    } 
}

function select(account)
{
    selected_account.value = account
    emit('update:modelValue', selected_account.value)
    active.value = false
}

function filter_by_code()
{
    search_by_name.value = null
    accounts_state_filtered.value = accounts_state.value.filter( a => (a.code).toString().indexOf(search_by_code.value) == 0 )
}

function filter_by_name()
{
    search_by_code.value = null
    accounts_state_filtered.value = accounts_state.value.filter( a => t(a.name).toLowerCase().indexOf(search_by_name.value.toLowerCase()) == 0 )
}

onBeforeMount(()=>{
    if(typeof props.accounts == "string"){
        accounts_state.value = JSON.parse(props.accounts)
    }else{
        accounts_state.value = props.accounts
    }
    accounts_state_filtered.value = accounts_state.value
})

onMounted(()=> {
    window.addEventListener('click', function(e){
        if(! root.value?.contains(e.target)){
            active.value = false
        }
    })
})

/**
 * @return String 'dropdown' | 'dropup'
 */
function dropdirection()
{
    if(! root.value) return 'dropdown'
    let {height, top} = root.value.getBoundingClientRect()
    return window.innerHeight - top - height >= top ? 'dropdown' : 'dropup'
}

const t = x => JSON.parse(x)[props.lang]
</script>

<style lang="scss" scoped>
$max-height: 200px;

.root{
    outline: none;
    position: relative;
    &.active{
        .result{
            display: block;
        }
    }
}
.select{
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    outline: none;
}
.search{
    display: flex;
    align-items: center;
    gap:5px;
    padding:10px;
    border-bottom: 1px solid #dcdcdc;
    .code{
        flex:0 0 150px;
    }
    .name{
        flex-grow: 1;
    }
}

.result{
    display: none;
    position: absolute;
    background: white;
    z-index: 1200;
    border: 1px solid #bbb;
    border-radius: 5px;
    box-shadow: 0 0 6px #ccc;
    width: 100%;
    &.dropdown{
        margin-top: 8px;
    }
    &.dropup{
        bottom: 100%;
        margin-bottom: 8px;
    }
}

.options{
    width:100%;
    max-height:$max-height;
    overflow-y: auto;
    .option{
        display: flex;
        gap:5px;
        cursor: pointer;
        padding:10px;
        &:hover{
            background: #cfcfcf;
        }
        &.selected{
            background:#7f7fe4;
            color:white;
            .code{
                color:white;
            }
        }
        .code{
            color:#aaa;
            font-size: 0.9rem;
            flex: 0 0 #{150 + 5 + 24}px;
            padding-left:30px;
        }
        .name{flex-grow: 1; padding-left:6px;}
    }
}
</style>