const Swal = require('sweetalert2');
export const toast = (title, type, position = "top-end") => {
    Swal.fire({
        toast: true,
        type: type,
        title: title,
        position: position,
        showConfirmButton: false,
        timer: 2500
    });
};

export const swal = (text, title = "", type = 'success') => {
    Swal.fire({
        type: type,
        title: title,
        text: text,
    })
};

export const confirmSomethingWithAlert = (message, title = 'Are you sure?') => {
    return swal.fire({
        title: title,
        text: message,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    });
};
