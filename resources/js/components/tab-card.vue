<template>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li v-for="(tab, index) in tabs_array" class="nav-item" :key='tab'>
                    <button @click="active_tab = index" type="button" :class="{active: active_tab == index}" class="nav-link" role="tab" data-bs-toggle="tab">{{ tab }}</button>
                </li>
            </ul>
        </div>
        <div class="card-body mt-4">
            <OneChild :index="active_tab"> <slot/> </OneChild>
        </div>
    </div>
</template>

<script>
import {ref, computed} from 'vue'

function OneChild({index},{slots}){
    return slots.default()[0].children[index]
}
OneChild.props = ['index']

export default {
    props:{tabs: [String, Array]},
    components:{OneChild},
    setup(props){
        const active_tab = ref(0)
        const tabs_array = computed(()=>{
            if(props.tabs instanceof Array) return props.tabs
            return props.tabs.split(',').map(x => x.trim())
        })
        return {active_tab, tabs_array}
    }
}
</script>