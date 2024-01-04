<template>
    <h6>Notes for upload</h6>
    <ul>
        <li>
            Make Sure that your file has only one sheet, to delete the unwanted sheets , right click on theme then choose "Delete"
            <img class ="mt-2" src="/assets/img/one-sheet.png">
        </li>
        <li class="mt-3">
            The transactions have to be as follow:
            <img class ="mt-2" src="/assets/img/transactions-sample.png">
            <ul class="mt-2">
                <li>The maximum transactions count is <strong>Million</strong> </li>
                <li>The transactions have to begin from row <strong>'2' </strong>, as the first row is for titles.</li>
                <li>The date has to be in column <strong>'A' </strong>, make sure the cell format is "Date" or "Time".</li> 
                <li>The client name has to be in column <strong>'B' </strong>, first it search for the client, if not found then it create it as Individual Customer.</li> 
                <li>The type has to be in column <strong>'C' </strong>, and contain either <strong>'Buy' </strong> or <strong>'Sell' </strong> word.</li>
                <li>Total price has to be in column <strong>'D' </strong>.</li>
                <li>You <strong>CAN</strong> put the price in column <strong>'E' </strong>, and you can leave it empty. </li>
                <li>The total quantity of the Crypto has to be in column <strong>'F' </strong>.</li>
                <li>You have to put the payment method in column <strong>'G' </strong>, and the method has to be exist in the system, it will throw an error if not exist</li>
                <li>The fees has to be in column <strong>'H' </strong>, you can leave it empty for zero fees.</li>
            </ul>
        </li>
    </ul>
    <div class="d-flex justify-content-center">
        <button class="btn btn-outline-primary btn-sm" @click="currentStep = 'uploading'">Next</button>
    </div>
</template>

<script>
import {inject} from "vue"

export default {
    setup(){
        const currentStep = inject("current-step")
        function log(x){
            console.log(x)
        }
        return {log, currentStep}
    }
}
</script>