$.validator.addMethod("msg", function(value) {
    return value != "Enter content";
}, 'Please enter some content');

$('form').validate({
    rules: {
      book_meta: {
        "msg":'',
        required: true
      }
    }
    , submitHandler: function(form){
        console.log(form);
    }
});
