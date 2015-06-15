//function validateUrl() {
//    alert('tadam');
//}
//
//document.addEventListener('DOMContentLoaded', function () {
//    document.getElementById('url_form').addEventListener('submit', function() {
//            alert('moahaha!');
//            return false;
//        }
//    );
//});

function validateUrl() {
    var input = document.getElementById('url_full_url');
    if(!(input.value.indexOf('http://') === 0) &&
        !(input.value.indexOf('https://') === 0) &&
        !(input.value.indexOf('ftp://') === 0) ) {
        input.value = 'http://' + input.value;
    }
    if(input.value.match(/((https?)|(ftp)):\/\/(www\.)?\S+\.\S+/)) {
        return;
    }
    return false;
}