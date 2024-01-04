<template>
<div class="row">
    <div class="col-12" v-if="! info_loading">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="/assets/img/profile-banner.png" class="rounded-top">
            </div>
            <div class="p-4">
                <div class="user-profile-info">
                    <h6>MBCT{{id}}</h6>
                    <h4>{{ name }}</h4>
                    <ul class="list-inline d-flex gap-4">
                        <li><i class="ti ti-bookmark"></i> {{ type() }}</li>
                        <li><i class="ti ti-calendar"></i> Created On {{ created_at }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <small class="card-text text-uppercase">About</small>
                        <ul class="list-unstyled my-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-user"></i>
                                <div class="fw-bold mx-2">Name:</div>
                                <span>{{ name }}</span>
                            </li>

                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-bookmark"></i>
                                <div class="fw-bold mx-2">Type:</div>
                                <span>{{ type() }}</span>
                            </li>

                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-flag"></i>
                                <div class="fw-bold mx-2">Country:</div>
                                <span>{{ info.customerable.country?.name }}</span>
                            </li>

                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-home"></i>
                                <div class="fw-bold mx-2">Address:</div>
                                <span>{{ info.customerable?.address }}</span>
                            </li>

                            <template v-if="type() == 'Individual'">
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-calendar"></i>
                                    <div class="fw-bold mx-2">Birthday:</div>
                                    <span>{{ info.customerable?.birthday }}</span>
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-id"></i>
                                    <div class="fw-bold mx-2">ID Card:</div>
                                    <span>{{ info.customerable.id_card }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-checkbox"></i>
                                    <div class="fw-bold mx-2">PEP:</div>
                                    <span>{{ info.customerable.PEP == 0 ? 'No' : 'Yes' }}</span>
                                </li>
                            </template>
                            <template v-else>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-article"></i>
                                    <div class="fw-bold mx-2">Registration Number:</div>
                                    <span>{{ info.customerable.registration_number }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-aspect-ratio"></i>
                                    <div class="fw-bold mx-2">TIN:</div>
                                    <span>{{ info.customerable.TIN }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-user-check"></i>
                                    <div class="fw-bold mx-2">Director Name:</div>
                                    <span>{{ info.customerable.director_name }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-cash"></i>
                                    <div class="fw-bold mx-2">Share Capital:</div>
                                    <span>{{ info.customerable.share_capital }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-asterisk"></i>
                                    <div class="fw-bold mx-2">Activity:</div>
                                    <span>{{ info.customerable.activity.activity }}</span>
                                </li>
                            </template>
                        </ul>
                        <small class="card-text text-uppercase">Contact</small>
                        <ul class="list-unstyled my-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ti ti-mail"></i>
                                <div class="fw-bold mx-2">EMail:</div>
                                <span>{{ info.customerable.email }}</span>
                            </li>
                            <li class="d-flex mb-3">
                                <i class="ti ti-phone"></i>
                                <div class="fw-bold mx-2">Phones:</div>
                                <span v-if="!info.phones.length"> No phones founded</span>
                                <ul v-else class="list-unstyled">
                                    <li v-for="p in info.phones">{{ p.phone }}</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <Monitor :show-mini-summary="false" :for-customer="id" class="mb-4"/>
                <ShareHolders v-if="type() == 'Entity'" :customer-id="id" type="edit" />
                <FPC :customer-id="id" type="edit"/>
            </div>
        </div>
    </div>
    <div class="col-12" v-else>
        <waveSpinner />
    </div>
</div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue'
import waveSpinner from "../../components/spinners/wave-spinner.vue"
import Monitor from "../transactions/monitor.vue"
import FPC from "./files-phones-comments.vue"
import ShareHolders from "./share-holders.vue"

const props = defineProps(['id'])

const info=ref()
const info_loading = ref(true)

onMounted(()=>{
    jQuery.ajax('/admin/customers/info/'+ props.id, {
        method:'GET',
        success(res){
            info.value = res
            info_loading.value = false
        }
    }) 
})
const type = ()=> info.value.customerable_type == 'App\\Models\\IndividualCustomer' ? 'Individual' : 'Entity'
const name = computed(()=> type() == 'Individual'
            ?  info.value.customerable.name + ' ' + info.value.customerable.surname
            :  info.value.customerable.name 
)
const created_at = computed(()=> info.value.customerable.created_at.slice(0,10))
</script>