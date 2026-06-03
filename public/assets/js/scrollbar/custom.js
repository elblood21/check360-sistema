var myElement = document.getElementById('simple-bar');
if (myElement) {
    try {
        new SimpleBar(myElement, { autoHide: true });
    } catch(e) {
        console.log('SimpleBar initialization skipped:', e);
    }
}