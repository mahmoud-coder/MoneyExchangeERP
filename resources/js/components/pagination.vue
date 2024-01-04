<template>
<div class="paginate">
    <button type="button" :disabled="!links.first || currentPage == 1" @click="paginateFunction(links.first)" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
        <i class="ti ti-chevrons-left"></i>
    </button>
    <button type="button" :disabled="!links.prev" @click="paginateFunction(links.prev)" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
        <i class="ti ti-chevron-left"></i>
    </button>
    <div class="currentPage">
        <span>{{ currentPage }}</span>
    </div>
    <button type="button" :disabled="!links.next" @click="paginateFunction(links.next)" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
        <i class="ti ti-chevron-right"></i>
    </button>
    <button type="button" :disabled="!links.last || currentPage == lastPage" @click="paginateFunction(links.last)" class="btn rounded-pill btn-icon btn-outline-primary waves-effect">
        <i class="ti ti-chevrons-right"></i>
    </button>
</div>
</template>

<script>
export default {
    props:{
        links: Object,
        currentPage: Number,
        lastPage: Number,
        paginateFunction: Function
    }
}
</script>

<style scoped lang="scss">
.paginate{
    display: flex;
    justify-content: center;
    align-items: center;
    gap:10px;
    padding:20px 0;
    button{
        width:50px;
        height:50px;
    }
    .currentPage{
        border:1px solid gray;
        border-radius: 50%;
        width:60px;
        height:60px;
        display: flex;
        justify-content: center;
        align-items: center;
        span{
            font-size:1.2rem;
        }
    }
}
</style>