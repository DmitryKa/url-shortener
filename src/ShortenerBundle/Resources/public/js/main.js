function validateUrl() {
    var input = document.getElementById('url_full_url');
    var urlRegex = /((https?)|(ftp)):\/\/(www\.)?\S+\.\S+/;
    if(!(input.value.indexOf('http://') === 0) &&
        !(input.value.indexOf('https://') === 0) &&
        !(input.value.indexOf('ftp://') === 0) ) {
        input.value = 'http://' + input.value;
    }
    if(input.value.match(urlRegex)) {
        return;
    }
    return false;
}