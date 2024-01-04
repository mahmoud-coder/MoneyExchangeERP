import {ref, onMounted} from 'vue'

/**
 * @param {string} url the base url , without query string
 * @param {string | function} queryString  [optional] a query string, or a function returns a query string.
 */
export function usePaginate(url, queryString = null){
    const loading = ref(true)
    const data = ref()
    const currentPage = ref(1)
    const lastPage=ref()
    const links = ref({})

    function getPage(page_url){
        loading.value = true
        if(queryString !== null){
            page_url += (page_url.indexOf('?') == -1 ? '?':'&') + (typeof queryString == 'string' ? queryString : queryString())
        }
        jQuery.ajax(page_url ,{
            method: 'GET',
            success(res){
                data.value = res.data
                links.value = res.links
                currentPage.value = res.meta.current_page
                lastPage.value = res.meta.last_page
                loading.value = false
            },
            error(xhr){
                console.log(xhr)
            }
        })
    }

    onMounted(() => {
        getPage(`${url}?page=1`)
    })

    return {
        loading,
        data,
        currentPage,
        lastPage,
        links,
        getPage
    }
}