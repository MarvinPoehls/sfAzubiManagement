function deleteEmployee(id, removeFromList = false) {
    let onSuccess = function(){
        if (removeFromList) {
            $('#employee_'+id).remove();
        }
    };

    deleteEmployees([id], onSuccess);
}

function deleteCheckedEmployees() {
    let deleteIds = getCheckedElementsByName('deleteId');
    let onSuccess = function(){
        deleteIds.forEach(function (id) {
            let deleteRow = $('#employee_' + id);

            if (deleteRow.length) {
                deleteRow.remove();
            }
        });
    }

    deleteEmployees(deleteIds, onSuccess);
}

function getCheckedElementsByName(name) {
    let deleteIds = [];
    $('input[type="checkbox"][name='+ name +']').each(function() {
        if ($(this).is(':checked')) {
            deleteIds.push($(this).val());
        }
    });
    return deleteIds;
}

function deleteEmployees(deleteIds, onSuccess) {
    $.ajax({
        url: ROOT+"employee/edit/ajax",
        type: "POST",
        data: {
            action: 'deleteEmployees',
            ids: deleteIds,
        },
        success: onSuccess(),
        error: function (error){
            console.log(error.responseText);
        }
    });
}