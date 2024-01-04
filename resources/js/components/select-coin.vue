<template>
    <div class="coin">
        <div class="dropdown-center">
            <button class="btn btn-warning btn-xl dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-brand-coinbase me-3"></i>
                Choose Coin
            </button>
            <ul class="dropdown-menu coins">
                <li v-for="(coin, i) in coins" :key="coin.id">
                    <div class="dropdown-item" @click="$emit('selected', coins[i])">
                        <img class="icon me-3" :src="`/storage/coins/${coin.symbol}.svg`" />
                        <span>{{ coin.symbol }}</span> <span class="name"> ( {{ coin.name }} )</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props:['coins'], //array of object as it in the DB
    emits:['selected']
}
</script>

<style scoped lang="scss">
.coin{
    display: flex;
    justify-content: center;
    .coins{z-index: 1200;}
    .dropdown-item{
        cursor: pointer;
        .icon{
            width:36px;
        }
        .name{color:rgb(214, 111, 42); font-size: 0.8rem;}
    }
}
</style>