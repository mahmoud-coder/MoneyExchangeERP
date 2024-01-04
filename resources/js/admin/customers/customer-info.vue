<template>
    <div class="modal fade" id="view-customer-info" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Info:</h5>
        <hr />
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
      </div>
      <div class="modal-body">
        <div v-if="! info" class="d-flex justify-content-center align-items-center my-4">
            <wave-spinner />
        </div>
        <template v-else>
            <div class="row">
                <div class="col-md-6">
                    <span class="label">Name:</span> {{ info.name }}
                </div>
                <div class="col-md-6">
                    <span class="label">eMail:</span> {{ info.email }}
                </div>
            </div>
            <template v-if="info.type == 'Individual'">
                <div  class="row">
                    <div class="col-md-6">
                        <span class="label">Birthday</span> {{ info.customerable.birthday }}
                    </div>
                    <div class="col-md-6">
                        <span class="label">ID-card</span> {{ info.customerable.id_card }}
                    </div>
                </div>
                <div  class="row">
                    <div class="col-md-6">
                        <span class="label">Address</span> {{ info.customerable.address }}
                    </div>
                    <div class="col-md-6">
                        <span class="label">Country</span> {{ info.customerable.country.name}}
                    </div>
                </div>
            </template>
            <template v-else>
                <div  class="row">
                    <div class="col-md-6">
                        <span class="label">Registration_number</span> {{ info.customerable.registration_number }}
                    </div>
                    <div class="col-md-6">
                        <span class="label">TIN</span> {{ info.customerable.TIN }}
                    </div>
                </div>
                <div  class="row">
                    <div class="col-md-6">
                        <span class="label">Country</span> {{ info.customerable.country.name}}
                    </div>
                    <div class="col-md-6">
                        <span class="label">Address</span> {{ info.customerable.address }}
                    </div>
                </div>
                <div  class="row">
                    <div class="col-md-6">
                        <span class="label">Share Capital</span> {{ info.customerable.share_capital}}
                    </div>
                    <div class="col-md-6">
                        <span class="label">Director Name</span> {{ info.customerable.director_name }}
                    </div>
                </div>
            </template>
            <div class="row mt-4">
               <div class="col-md-6">
                    <table v-if="info.phones.length" class="table table-bordered table-striped">
                        <thead>
                            <tr> 
                                <th>#</th>
                                <th>Phones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p,i) in info.phones" :key="p.id">
                                <td>{{i+1}}</td>
                                <td>{{p.phone}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else>
                        <div class="note">No Phones added to {{ info.name }}</div>
                    </div>
               </div>
               <div class="col-md-6">
                <table v-if="info.comments.length" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Comment</th>
                            <th>Add by:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(c,i) in info.comments" :key="c.id">
                            <td>{{i+1}}</td>
                            <td>{{c.comment}}</td>
                            <td>{{c.user.name}}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="note">No Comments added to {{ info.name }}</div>
               </div>
            </div>
            <hr/>

            <!-- FIELS -->
            <hr/>
            <h5> Images:</h5>
            <div v-for="image in images" :key="image.id" class="border-bottom p-2">
                <div>{{ image.title }}</div>
                <div>
                    <img :src="`/storage/customers_files/${image.src}`" style="max-width: 90%; height:auto;">
                </div>
            </div>
            <hr/>
            <h5>PDFs:</h5>
            <div v-for="pdf in pdfs" class="border-bottom p-2">
            <div>
                <a :href="`/storage/customers_files/${pdf.src}`">{{ pdf.title }}</a>
            </div>
            </div>
            <hr/>
            <h5>Other files:</h5>
            <div v-for="f in otherFiles" class="border-bottom p-2">
            <div>
                <a :href="`/storage/customers_files/${f.src}`">{{ f.title }}</a>
            </div>
            </div>
        </template>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</template>

<script setup>
import {computed} from 'vue'
import waveSpinner from "../../components/spinners/wave-spinner.vue"

const props = defineProps(['info'])

const images= computed(()=> props.info.files.filter(f => f.type.split('/')[0] == 'image') )
const pdfs = computed(()=> props.info.files.filter(f => f.type =='application/pdf') )
const otherFiles = computed(()=> props.info.files.filter(f =>f.type.split("/")[0] !== 'image' && f.type !== 'application/pdf') )
</script>

<style scoped>
.label{
    font-weight: bold;
    font-size: 1.1em;
}
.note{
    font-style: italic;
    text-decoration: underline;
}
</style>