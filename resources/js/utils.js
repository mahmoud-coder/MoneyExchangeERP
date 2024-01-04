/**
 * sending a DELETE request 
 * @param {string} name the name displayed in the prompt.
 * @param {string} deleting_request_url the url of the DELETE request, including the resource id.
 * @param {Funtion} [onBeforeDeleting]    optional function before sending the DELETE request. 
 * @param {Function} [onSuccess]    optional.
 * @param {Function} [onComplete]   optional.
 */
export function destroy_resource(name, deleting_request_url, onBeforeDeleting= null, onSuccess = null, onComplete = null){
    Swal.fire({
        title: `Are you sure you want to delete ${name}?`,
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, delete ${name}`,
        customClass: {
            confirmButton: 'btn btn-primary me-1',
            cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
    }).then(function(result) {
        if(result.value){
            if(onBeforeDeleting) onBeforeDeleting()
            jQuery.ajax(deleting_request_url,{
                method: 'DELETE',
                success(res){ if(res.success && onSuccess) onSuccess(res) },
                error(xhr){
                    if(xhr.responseJSON.message){
                        toastr.error(xhr.responseJSON.message, 'Error')
                    }
                },
                complete(){if(onComplete) onComplete()},
                headers:{
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
        }else if(result.dismiss === Swal.DismissReason.cancel){
            Swal.fire({
                title: 'Cancelled',
                icon: 'error',
                customClass: { confirmButton: 'btn btn-success' }
            });
        }
  });
}