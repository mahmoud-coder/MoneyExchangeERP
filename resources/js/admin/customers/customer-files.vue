<template>

<span data-bs-toggle="modal" data-bs-target="#view-customer-files" class="text-primary cursor-pointer" style="text-decoration: underline;">
     View this customer Images and files
</span>

<!-- Modal -->
<div class="modal fade" id="view-customer-files" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Customer Files:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
      </div>
      <div class="modal-body">
        <hr/>
        <h4> Images:</h4>
        <div v-for="image in images" :key="image.id" class="border-bottom p-2">
            <div>{{ image.title }}</div>
            <div class="d-flex justify-content-between align-items-center">
                <img :src="`/storage/customers_files/${image.src}`" style="max-width: 80%; height:auto;">
                <button v-if="image.id !== deleting_file_id" class="btn btn-danger" @click="delete_file(image)">
                    <i class="ti ti-trash"></i> Delete
                </button>
                <wave-spinner v-else />
            </div>
        </div>
        <hr/>
        <h4>PDFs:</h4>
        <div v-for="pdf in pdfs" class="border-bottom p-2">
          <div class="d-flex justify-content-between align-items-center">
            <a :href="`/storage/customers_files/${pdf.src}`">{{ pdf.title }}</a>
            <button v-if="pdf.id !== deleting_file_id" class="btn btn-danger" @click="delete_file(pdf)">
                <i class="ti ti-trash"></i> Delete
            </button>
          </div>
        </div>
        <hr/>
        <h4>Other files:</h4>
        <div v-for="f in otherFiles" class="border-bottom p-2">
          <div class="d-flex justify-content-between align-items-center">
            <a :href="`/storage/customers_files/${f.src}`">{{ f.title }}</a>
            <button v-if="f.id !== deleting_file_id" class="btn btn-danger" @click="delete_file(f)">
                <i class="ti ti-trash"></i> Delete
            </button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</template>

<script setup>
import {ref, computed} from 'vue'
import waveSpinner from "../../components/spinners/wave-spinner.vue" 

const props = defineProps({
    files:{
        default:[]
    }
})

const deleting_file_id = ref()

const images= computed(()=> props.files.filter(f => f.type.split('/')[0] == 'image') )
const pdfs = computed(()=> props.files.filter(f => f.type =='application/pdf') )
const otherFiles = computed(()=> props.files.filter(f =>f.type.split("/")[0] !== 'image' && f.type !== 'application/pdf') )

function delete_file(file){
    deleting_file_id.value = file.id
    jQuery.ajax( '/admin/customers/info/', {
        method:'DELETE',
        data:{fileId: file.id},
        success(){
            props.files.splice(props.files.indexOf(file), 1)
            deleting_file_id.value = null
        },
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
}
</script>