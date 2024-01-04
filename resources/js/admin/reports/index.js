import {h} from 'vue'
import FIFO from './fifo'
export default {
    setup(){
        return ()=> h(FIFO)
    }
}